<?php

namespace Secondnetwork\Kompass\Helpers;

use Illuminate\Support\Str;

/**
 * Converts legacy Editor.js JSON ({time, blocks:[{type,data:{...}}]}) into the
 * flat block array used by the Notion-style KompassEditor:
 *   [ ['id' => 'block-xxxx', 'type' => 'p|h1|li|oli|blockquote|...', 'content' => 'html'], ... ]
 *
 * Idempotent for already-flat input; tolerant of JSON strings and empty values.
 */
class EditorMigrationHelper
{
    /**
     * Main entry point. Accepts any of:
     *   - JSON string (Editor.js shape, compiled-shape, or flat-shape)
     *   - Editor.js array: ['time'=>..., 'blocks'=>[...]]
     *   - Already-flat array: [['id','type','content'], ...]
     *   - Empty / null
     *
     * @return array<int, array{id:string,type:string,content:string}>
     */
    public static function toFlatBlocks(mixed $input): array
    {
        if (is_string($input)) {
            $input = trim($input);
            if ($input === '') {
                return self::seedBlock();
            }
            $decoded = json_decode($input, true);
            $input = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }

        if ($input === null || $input === [] || ! is_array($input)) {
            return self::seedBlock();
        }

        if (self::isEditorJsShape($input)) {
            $flat = [];
            foreach ($input['blocks'] as $block) {
                if (! is_array($block)) {
                    continue;
                }
                foreach (self::convertEditorJsBlock($block) as $converted) {
                    $flat[] = $converted;
                }
            }

            return $flat !== [] ? $flat : self::seedBlock();
        }

        if (self::isCompiledShape($input)) {
            return self::fromCompiledShape($input);
        }

        if (self::isFlatShape($input)) {
            return array_map(self::normalizeFlatBlock(...), array_values($input));
        }

        return self::seedBlock();
    }

    /**
     * Consolidate a flat block array into the canonical render-time shape used
     * by the frontend renderer. It returns a wrapped array { "blocks": [...] }
     * for compatibility with legacy renderers.
     */
    public static function compile(array $blocks): array
    {
        $compiledBlocks = [];
        $currentList = null;

        foreach ($blocks as $block) {
            $type = $block['type'] ?? 'p';
            $content = $block['content'] ?? '';
            $isBullet = $type === 'li';
            $isOrdered = $type === 'oli';

            if ($isBullet || $isOrdered) {
                $style = $isBullet ? 'unordered' : 'ordered';

                if ($currentList !== null
                    && isset($compiledBlocks[$currentList])
                    && $compiledBlocks[$currentList]['type'] === 'list'
                    && $compiledBlocks[$currentList]['data']['style'] === $style
                ) {
                    $compiledBlocks[$currentList]['data']['items'][] = $content;

                    continue;
                }

                $compiledBlocks[] = [
                    'type' => 'list',
                    'data' => [
                        'style' => $style,
                        'items' => [$content],
                    ],
                ];
                $currentList = array_key_last($compiledBlocks);

                continue;
            }

            $currentList = null;

            // Map internal types to Editor.js-compatible types for the frontend
            if (preg_match('/^h([1-6])$/', $type, $matches)) {
                $compiledBlocks[] = [
                    'type' => 'header',
                    'data' => [
                        'text' => $content,
                        'level' => (int) $matches[1],
                    ],
                ];
            } elseif ($type === 'p') {
                $compiledBlocks[] = [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => $content,
                    ],
                ];
            } elseif ($type === 'blockquote') {
                $compiledBlocks[] = [
                    'type' => 'quote',
                    'data' => [
                        'text' => $content,
                        'caption' => '',
                        'alignment' => 'left',
                    ],
                ];
            } else {
                $compiledBlocks[] = [
                    'type' => $type,
                    'data' => [
                        'content' => $content,
                        'text' => $content,
                    ],
                ];
            }
        }

        return [
            'time' => (int) (microtime(true) * 1000),
            'blocks' => $compiledBlocks,
            'version' => '2.28.2',
        ];
    }

    /**
     * Same as compile() but returns a pretty-printed JSON string (used by the
     * Livewire component for the persistence-side $value).
     */
    public static function compileJson(array $blocks): string
    {
        return json_encode(
            self::compile($blocks),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT,
        );
    }

    /**
     * Returns ONLY the flat blocks array, ready for @foreach.
     *
     * @return array<int, array>
     */
    public static function toCompiledArray(mixed $input): array
    {
        $compiled = self::compile(self::toFlatBlocks($input));

        return $compiled['blocks'] ?? [];
    }

    /**
     * Returns the full wrapped object {"blocks": [...]}, compatible with $data->blocks.
     */
    public static function toCompiledObject(mixed $input): object
    {
        return json_decode(json_encode(self::compile(self::toFlatBlocks($input))));
    }

    /**
     * Returns blocks in the flat render shape consumed by the frontend Blade
     * renderers (components/blocks/wysiwyg, hero, card, download):
     *   - text blocks:  ['type' => 'p|h1..h6|blockquote|...', 'content' => 'html']
     *   - lists:        ['type' => 'list', 'data' => ['type' => 'ordered|unordered', 'items' => ['html', ...]]]
     *
     * Unlike compile()/toCompiledArray() (which emit the Editor.js shape with
     * text nested under data.text), this keeps text at the top level under
     * `content`, which is what those renderers read.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function toRenderBlocks(mixed $input): array
    {
        $out = [];
        $currentList = null;

        foreach (self::toFlatBlocks($input) as $block) {
            $type = $block['type'] ?? 'p';
            $content = $block['content'] ?? '';

            if ($type === 'li' || $type === 'oli') {
                $style = $type === 'oli' ? 'ordered' : 'unordered';

                if ($currentList !== null
                    && ($out[$currentList]['type'] ?? null) === 'list'
                    && ($out[$currentList]['data']['type'] ?? null) === $style
                ) {
                    $out[$currentList]['data']['items'][] = $content;

                    continue;
                }

                $out[] = [
                    'type' => 'list',
                    'data' => ['type' => $style, 'items' => [$content]],
                ];
                $currentList = array_key_last($out);

                continue;
            }

            $currentList = null;
            $out[] = ['type' => $type, 'content' => $content];
        }

        return $out;
    }

    /**
     * Editor.js JSON has a top-level "blocks" array whose first element has a
     * "data" key. We do not require "time" because some payloads omit it.
     */
    public static function isEditorJsShape(mixed $data): bool
    {
        if (! is_array($data) || ! isset($data['blocks']) || ! is_array($data['blocks'])) {
            return false;
        }
        foreach ($data['blocks'] as $block) {
            if (is_array($block) && array_key_exists('data', $block)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Converts a single Editor.js block into one or more flat blocks (list
     * blocks expand into one flat block per item).
     *
     * @return array<int, array{id:string,type:string,content:string}>
     */
    public static function convertEditorJsBlock(array $block): array
    {
        $type = $block['type'] ?? 'paragraph';
        $data = $block['data'] ?? [];
        $sourceId = isset($block['id']) && is_string($block['id']) ? $block['id'] : null;

        switch ($type) {
            case 'header':
                $level = (int) ($data['level'] ?? 2);
                $level = max(1, min(6, $level));

                return [self::makeBlock('h'.$level, (string) ($data['text'] ?? ''), $sourceId)];

            case 'paragraph':
                return [self::makeBlock('p', (string) ($data['text'] ?? ''), $sourceId)];

            case 'quote':
                return [self::makeBlock('blockquote', (string) ($data['text'] ?? ''), $sourceId)];

            case 'list':
                $style = ($data['style'] ?? 'unordered') === 'ordered' ? 'oli' : 'li';
                $items = is_array($data['items'] ?? null) ? $data['items'] : [];
                if ($items === []) {
                    return [self::makeBlock('p', '', $sourceId)];
                }
                $out = [];
                foreach ($items as $item) {
                    $out[] = self::makeBlock($style, self::stringifyListItem($item), null);
                }

                return $out;

            case 'code':
                // No code block in the new editor's blockTypes — degrade to a
                // paragraph with HTML-escaped contents.
                $code = is_string($data['code'] ?? null) ? $data['code'] : '';

                return [self::makeBlock('p', htmlspecialchars($code, ENT_QUOTES | ENT_HTML5, 'UTF-8'), $sourceId)];

            default:
                $text = '';
                foreach (['text', 'caption', 'content'] as $key) {
                    if (isset($data[$key]) && is_string($data[$key])) {
                        $text = $data[$key];
                        break;
                    }
                }

                return [self::makeBlock('p', $text, $sourceId)];
        }
    }

    /**
     * Flat shape: list of {id?,type?,content?}. type/content optional, id is filled in.
     */
    private static function isFlatShape(array $data): bool
    {
        if ($data === []) {
            return false;
        }
        foreach ($data as $row) {
            if (! is_array($row)) {
                return false;
            }
            if (! array_key_exists('type', $row) && ! array_key_exists('content', $row)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Compiled shape: list of either {type,content} or {type:"list", data:{type, items:[]}}.
     */
    private static function isCompiledShape(array $data): bool
    {
        if ($data === []) {
            return false;
        }
        foreach ($data as $row) {
            if (! is_array($row)) {
                return false;
            }
            if (($row['type'] ?? null) === 'list' && is_array($row['data'] ?? null)) {
                continue;
            }
            if (array_key_exists('type', $row) && array_key_exists('content', $row) && ! array_key_exists('data', $row)) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * @return array<int, array{id:string,type:string,content:string}>
     */
    private static function fromCompiledShape(array $data): array
    {
        $flat = [];
        foreach ($data as $row) {
            if (($row['type'] ?? null) === 'list') {
                $style = ($row['data']['type'] ?? 'unordered') === 'ordered' ? 'oli' : 'li';
                $items = is_array($row['data']['items'] ?? null) ? $row['data']['items'] : [];
                foreach ($items as $item) {
                    $flat[] = self::makeBlock($style, self::stringifyListItem($item), null);
                }

                continue;
            }
            $flat[] = self::makeBlock(
                (string) ($row['type'] ?? 'p'),
                (string) ($row['content'] ?? ''),
                null,
            );
        }

        return $flat !== [] ? $flat : self::seedBlock();
    }

    private static function normalizeFlatBlock(array $row): array
    {
        return self::makeBlock(
            (string) ($row['type'] ?? 'p'),
            (string) ($row['content'] ?? ''),
            isset($row['id']) && is_string($row['id']) ? $row['id'] : null,
        );
    }

    /**
     * @return array{id:string,type:string,content:string}
     */
    private static function makeBlock(string $type, string $content, ?string $sourceId): array
    {
        return [
            'id' => $sourceId !== null && $sourceId !== '' ? $sourceId : 'block-'.Str::random(9),
            'type' => $type,
            'content' => $content,
        ];
    }

    /**
     * @return array<int, array{id:string,type:string,content:string}>
     */
    private static function seedBlock(): array
    {
        return [self::makeBlock('p', '', null)];
    }

    private static function stringifyListItem(mixed $item): string
    {
        if (is_string($item)) {
            return $item;
        }
        if (is_array($item) && isset($item['content']) && is_string($item['content'])) {
            return $item['content'];
        }
        if (is_array($item) && isset($item['text']) && is_string($item['text'])) {
            return $item['text'];
        }

        return '';
    }
}
