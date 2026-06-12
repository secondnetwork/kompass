<?php

namespace Secondnetwork\Kompass;

use Intervention\Image\Drivers\Gd\Driver;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\File;
use Secondnetwork\Kompass\Models\Meta;
use Secondnetwork\Kompass\Models\Page;
use Secondnetwork\Kompass\Models\Post;

/*
| Shared building blocks for the block-type definitions below. Extracted to keep
| the registry DRY — the resolved config is identical to inlining these literals.
*/
$styleSlate = ['rail' => 'border-l-slate-400', 'badge' => 'bg-slate-500', 'bar' => 'bg-base-200', 'accent' => 'text-slate-500'];
$controlsBasic = ['layout', 'color', 'advanced'];
$controlsContainer = ['container-layout', 'layout-grid', 'color', 'advanced'];

return [

    /*
    |--------------------------------------------------------------------------
    | Essential Settings
    |--------------------------------------------------------------------------
    |
    | Core configuration required for Kompass to function properly.
    |
    */

    'middleware' => ['web'],

    'storage' => [
        'disk' => env('FILESYSTEM_DRIVER', 'public'),
    ],

    'meta' => [
        'morph_type' => 'integer',
    ],

    'serializable_classes' => [
        Block::class,
        Datafield::class,
        Meta::class,
        File::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Configure authentication behaviour for the CMS.
    |
    */

    'auth' => [
        'password_login_enabled' => true,
        'force_passkey_on_first_login' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Optional features that can be enabled or disabled.
    |
    */

    'features' => [
        Features::profilePhotos(),
        Features::accountDeletion(),
        Features::activityLog(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | Available locales and date formatting for the application.
    |
    */

    'available_locales' => [
        'de',
        'en',
        'bg',
        'cs',
        'da',
        'el',
        'es',
        'et',
        'fi',
        'fr',
        'ga',
        'hr',
        'hu',
        'it',
        'lt',
        'lv',
        'mt',
        'nl',
        'pl',
        'pt',
        'ro',
        'sk',
        'sl',
        'sv',
        'tr',
        'no',
        'is',
        'sr',
        'bs',
        'sq',
        'mk',
        'ru',
        'uk',
    ],

    'dateformat' => 'd.m.Y H:i',

    /*
    |--------------------------------------------------------------------------
    | Image Processing
    |--------------------------------------------------------------------------
    |
    | Settings for Intervention Image V3 and image handling.
    |
    */

    'driver' => Driver::class,

    'generate_blur_placeholder' => true,

    'quality' => [
        'avif' => 50,
        'webp' => 80,
        'jpeg' => 85,
    ],

    'sizes' => [
        'thumbnail' => [
            'width' => 520,
            'height' => null,
            'method' => 'scale',
            'quality' => 60,
        ],
        'blog_single' => [
            'width' => 1200,
            'height' => null,
            'method' => 'scale',
            'quality' => 80,
        ],
        'landscape' => [
            'width' => 1280,
            'height' => 720,
            'method' => 'cover',
            'quality' => 75,
        ],
    ],

    'fallback' => [
        'width' => 2500,
        'height' => 2500,
        'method' => 'scaleDown',
        'quality' => 85,
    ],

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Disks
    |--------------------------------------------------------------------------
    |
    | Define which storage disks are used for different operations.
    |
    */

    'profile_photo_disk' => 'public',
    'default_img_upload_disk' => 'public',
    'default_img_download_disk' => 'public',

    'prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | Sets (Legacy)
    |--------------------------------------------------------------------------
    |
    | Legacy configuration for sets. Consider migrating to newer config.
    |
    */

    'sets' => [
        'default' => [
            'fallback' => 'border-all',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Block Types (single source of truth)
    |--------------------------------------------------------------------------
    |
    | Built-in block types consumed by Secondnetwork\Kompass\Blocks\BlockTypeRegistry.
    | Each entry feeds the add-block palette, the default datafields created on
    | add, the builder styling, the edit-control list and the frontend component
    | name. User-defined Blocktemplates rows are merged in at runtime; built-ins
    | win on a type collision. Available control keys map to the anonymous Blade
    | components under resources/views/components/block-controls/*.
    |
    */

    'block_types' => [
        'wysiwyg' => [
            'label' => 'Textblock',
            'icon' => 'blockquote',
            'component' => 'blocks.wysiwyg',
            'container' => false,
            'default_fields' => [['type' => 'wysiwyg', 'order' => 1]],
            'styling' => $styleSlate,
            'controls' => ['layout', 'alignment', 'link', 'color', 'advanced'],
            'palette' => true,
            'palette_image' => 'icons-blocks/default.png',
            'palette_border' => 'border-blue-600',
        ],
        'group' => [
            'label' => 'Layout Block',
            'icon' => '',
            'component' => 'blocks.group',
            'container' => true,
            'default_fields' => [],
            'styling' => ['rail' => 'border-l-indigo-500', 'badge' => 'bg-indigo-500', 'bar' => 'bg-indigo-500/10', 'accent' => 'text-indigo-600'],
            'controls' => $controlsContainer,
            'palette' => true,
            'palette_image' => 'icons-blocks/group.png',
            'palette_border' => 'border-purple-600',
        ],
        'accordiongroup' => [
            'label' => 'Accordion',
            'icon' => '',
            'component' => 'blocks.accordiongroup',
            'container' => true,
            'default_fields' => [],
            'styling' => ['rail' => 'border-l-emerald-500', 'badge' => 'bg-emerald-500', 'bar' => 'bg-emerald-500/10', 'accent' => 'text-emerald-600'],
            'controls' => $controlsContainer,
            'palette' => true,
            'palette_image' => 'icons-blocks/accordiongroup.png',
            'palette_border' => 'border-purple-600',
        ],
        'button' => [
            'label' => 'Button',
            'icon' => 'box-model-2',
            'component' => 'blocks.button',
            'container' => false,
            'default_fields' => [['type' => 'link', 'order' => 1]],
            'styling' => $styleSlate,
            'controls' => $controlsBasic,
            'palette' => true,
            'palette_image' => 'icons-blocks/button.png',
            'palette_border' => 'border-blue-600',
        ],
        'video' => [
            'label' => 'Video',
            'icon' => 'video',
            'component' => 'blocks.video',
            'container' => false,
            'default_fields' => [],
            'styling' => ['rail' => 'border-l-red-600', 'badge' => 'bg-slate-500', 'bar' => 'bg-slate-600/10', 'accent' => 'text-red-600'],
            'controls' => $controlsBasic,
            'palette' => true,
            'palette_image' => 'icons-blocks/videoplayer.png',
            'palette_border' => 'border-blue-600',
        ],
        'gallery' => [
            'label' => 'Images and Gallery',
            'icon' => 'photo',
            'component' => 'blocks.gallery',
            'container' => false,
            'default_fields' => [['type' => 'gallery', 'order' => 1, 'data' => []]],
            'styling' => ['rail' => 'border-l-blue-500', 'badge' => 'bg-blue-500', 'bar' => 'bg-blue-500/10', 'accent' => 'text-blue-600'],
            'controls' => ['layout', 'gallery', 'color', 'advanced'],
            'palette' => true,
            'palette_image' => 'icons-blocks/gallery.png',
            'palette_image_class' => 'rounded',
            'palette_border' => 'border-blue-600',
        ],
        'anchormenu' => [
            'label' => 'Anchor menu',
            'icon' => '',
            'component' => 'blocks.anchormenu',
            'container' => false,
            'default_fields' => [['name' => 'Name Anchormenu', 'type' => 'text', 'order' => 1]],
            'styling' => $styleSlate,
            'controls' => $controlsBasic,
            'palette' => false,
        ],
        'relationship' => [
            'label' => 'Relationship',
            'icon' => 'database',
            'component' => 'blocks.relationship',
            'container' => false,
            'default_fields' => [],
            'styling' => ['rail' => 'border-l-teal-500', 'badge' => 'bg-teal-500', 'bar' => 'bg-teal-500/10', 'accent' => 'text-teal-600'],
            'controls' => $controlsBasic,
            'palette' => true,
            'palette_image' => 'icons-blocks/default.png',
            'palette_border' => 'border-teal-600',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Models (Relationship block)
    |--------------------------------------------------------------------------
    |
    | Models the "relationship" block can query and list. Each entry registers
    | a selectable source by key. The block stores its chosen source, ordering
    | and limit in block meta (query-model, query-order, query-direction,
    | query-limit) and renders the matched records via kompass_query().
    |
    | Sources are now database-managed (the `query_sources` table, edited under
    | Admin → Query sources, seeded by QuerySourceSeeder). query_models() merges
    | any entries defined here with the database rows — config wins on a key
    | collision. Leave `query_models` empty to manage every source from the
    | backend, or add a literal entry here to ship a hard-coded, non-editable
    | source. Per-entry keys: label, model, label_field, order_fields,
    | url_pattern, status, item_view, wrapper_class, with.
    |
    */

    /*
    | Allowlist of models that database-defined query sources (the admin-managed
    | `query_sources` table, merged in by query_models()) may be backed by. A
    | source row stores one of these KEYS in `model_key`; the key is resolved to
    | the class here. User input never supplies a raw class name, so an arbitrary
    | class can never be instantiated. Add an entry to expose a model to the
    | "create source" backend screen.
    */
    'query_source_models' => [
        'pages' => Page::class,
        'posts' => Post::class,
    ],

    'query_models' => [
        // Managed in the database (Admin → Query sources). See QuerySourceSeeder
        // for the default Pages / Blog posts sources.
    ],

    /*
    |--------------------------------------------------------------------------
    | Field Types (datafields)
    |--------------------------------------------------------------------------
    |
    | Datafield types consumed by Secondnetwork\Kompass\Blocks\FieldTypeRegistry.
    | display_component = anonymous Blade component (under kompass::) used to
    | render the saved value in the builder; edit_widget = the interactive editor
    | widget (input|image|oembed|editor); select=false hides it from the
    | field-type picker.
    |
    */

    'field_types' => [
        'text' => ['label' => 'Text', 'icon' => 'tabler-letter-case', 'display_component' => 'block.text', 'edit_widget' => 'input'],
        'wysiwyg' => ['label' => 'WYSIWYG Editor', 'icon' => 'tabler-blockquote', 'display_component' => 'block.wysiwyg', 'edit_widget' => 'editor'],
        'image' => ['label' => 'Image', 'icon' => 'tabler-photo', 'display_component' => 'block.image', 'edit_widget' => 'image'],
        'gallery' => ['label' => 'Gallery', 'icon' => 'tabler-layout-grid-add', 'display_component' => 'block.gallery-field', 'edit_widget' => 'gallery'],
        'link' => ['label' => 'Link', 'icon' => 'tabler-link', 'display_component' => 'block.link', 'edit_widget' => 'input'],
        'true_false' => ['label' => 'true/false', 'icon' => 'tabler-toggle-left', 'display_component' => 'block.true_false', 'edit_widget' => 'input'],
        'file' => ['label' => 'File', 'icon' => 'tabler-file-zip', 'display_component' => 'block.file', 'edit_widget' => 'input'],
        'color' => ['label' => 'Color', 'icon' => 'tabler-palette', 'display_component' => 'block.color', 'edit_widget' => 'input'],
        'oembed' => ['label' => 'Video embed', 'icon' => 'tabler-brand-youtube', 'display_component' => 'block.text', 'edit_widget' => 'oembed', 'select' => false],
    ],

    /*
    |--------------------------------------------------------------------------
    | Setting Field Types
    |--------------------------------------------------------------------------
    |
    | Distinct vocabulary used by the global settings field-type picker
    | (components/elements/global.blade.php). Kept separate from field_types
    | because its ids (rich_text_box, switch) drive a different render switch.
    |
    */

    'setting_field_types' => [
        'text' => ['label' => 'Text', 'icon' => 'tabler-letter-case'],
        'wysiwyg' => ['label' => 'WYSIWYG Editor', 'icon' => 'tabler-blockquote'],
        'image' => ['label' => 'Image', 'icon' => 'tabler-photo'],
        'link' => ['label' => 'Link', 'icon' => 'tabler-link'],
        'switch' => ['label' => 'true or false', 'icon' => 'tabler-toggle-left'],
        'file' => ['label' => 'File', 'icon' => 'tabler-file-zip'],
    ],

];
