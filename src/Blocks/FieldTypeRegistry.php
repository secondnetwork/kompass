<?php

namespace Secondnetwork\Kompass\Blocks;

/**
 * Single source of truth for datafield types. Feeds the field-type selects and
 * resolves a datafield type to its builder display component and its
 * interactive edit widget.
 */
class FieldTypeRegistry
{
    /**
     * @return array<string,array<string,mixed>>
     */
    public function fieldTypes(): array
    {
        return config('kompass.field_types', []);
    }

    /**
     * Builder display component (under the kompass:: view namespace), e.g. block.text.
     */
    public function fieldComponent(string $type): string
    {
        return config("kompass.field_types.$type.display_component", 'block.text');
    }

    /**
     * Logical edit widget used by the interactive datafield editor:
     * input | image | oembed | editor.
     */
    public function fieldEditWidget(string $type): string
    {
        return config("kompass.field_types.$type.edit_widget", 'input');
    }

    /**
     * Options for the datafield-type <select> (those with select !== false).
     *
     * @return array<int,array{name:string,id:string,icon:?string}>
     */
    public function fieldSelectOptions(): array
    {
        $options = [];
        foreach ($this->fieldTypes() as $key => $definition) {
            if (! ($definition['select'] ?? true)) {
                continue;
            }
            $options[] = [
                'name' => __($definition['label'] ?? $key),
                'id' => $key,
                'icon' => $definition['icon'] ?? null,
            ];
        }

        return $options;
    }

    /**
     * Options for the settings field-type <select> (distinct vocabulary, e.g.
     * rich_text_box / switch).
     *
     * @return array<int,array{name:string,id:string,icon:?string}>
     */
    public function settingsFieldSelectOptions(): array
    {
        $options = [];
        foreach (config('kompass.setting_field_types', []) as $key => $definition) {
            $options[] = [
                'name' => __($definition['label'] ?? $key),
                'id' => $key,
                'icon' => $definition['icon'] ?? null,
            ];
        }

        return $options;
    }
}
