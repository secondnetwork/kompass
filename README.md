# Kompass

![Kompass](https://github.com/secondnetwork/kompass/blob/main/public/assets/kompass_md.png?raw=true)

[![Latest Kompass Version](https://img.shields.io/packagist/v/secondnetwork/kompass.svg?style=for-the-badge&label=Kompass&labelColor=FFA700&color=1A2A2C)](https://github.com/secondnetwork/kompass)
[![Laravel](https://img.shields.io/badge/v13.0-999999?style=for-the-badge&label=Laravel&labelColor=eb4432&color=1A2A2C)](https://laravel.com)
[![PHP](https://img.shields.io/badge/v8.3-999999?style=for-the-badge&label=PHP&labelColor=777BB4&color=1A2A2C)](https://php.com)		
[![License](https://img.shields.io/github/license/secondnetwork/kompass?style=for-the-badge)](https://github.com/secondnetwork/kompass)

## The development of Kompass

Kompass is what a modern Laravel CMS should feel like. Built on Tailwind CSS 4, Livewire 4, and Laravel 13 — with a block builder, media library, drag-and-drop menus builder, multilingual content, passkey login, SEO, and role-based permissions baked in. No workarounds. No compromises. Built for developers who expect more.

Still using WordPress or TYPO3? There's a better way. Kompass combines the power of Laravel with everything you need for modern web projects — without the legacy overhead.

## Key Features

- **Modern Tech Stack**: Built with Laravel 13, PHP 8.2+, Livewire 4, and Tailwind CSS 4
- **Block Builder**: Flexible block system for creating dynamic page layouts.
- **Media Library**: Full-featured media management.
- **Menu Builder**: Drag & Drop menu management
- **User Management**: Role-based access control with Spatie Laravel Permission
- **Passkey Authentication**: Passwordless login via biometrics or device PIN
- **Multilingual Support**: Full multilingual content management for multiple languages

## Requirements

Additionally Kompass requires you to use
- PHP 8.3 or newer 
- Laravel 13 or newer
- Livewire 4.0 or newer
- Tailwindcss 4.0 or newer

## Installation

Kompass is super easy to install. After creating your new Laravel application you can include Kompass.

```bash
composer require secondnetwork/kompass
```

With the command we install frontend asset, created new admin user and drop all tables from the database.

```bash
php artisan kompass:install  
```

## Publishing Kompass's frontend assets in future updates

```bash
php artisan vendor:publish --tag=kompass.assets --force && php artisan optimize:clear
```

To keep assets up-to-date and avoid issues in future updates, we strongly recommend that you add the following command to your composer.json file:

```json
{
    "scripts": {
        "post-update-cmd": [
            // Other scripts
            "@php artisan vendor:publish --tag=kompass.assets --force"
        ]
    }
}
```

## Languages

Kompass ships with translations for the admin panel in:

- 🇩🇪 German (`de`)
- 🇬🇧 English (`en`)
- 🇪🇸 Spanish (`es`)
- 🇫🇷 French (`fr`)

The translation files live in the package under `resources/lang/{locale}.json` and are
loaded automatically based on your app's `app.locale` (set via the `APP_LOCALE` env variable).

### Overriding or adding a language

JSON translations defined in your own application take precedence over the package's.
To customize a string or add a new locale, create/edit `lang/{locale}.json` in your project:

```json
{
    "Choose color": "Farbe wählen",
    "Documentation": "Dokumentation"
}
```

Each entry is a flat key/value pair; missing keys fall back to the English source string.
Contributions of additional locales to the package itself are welcome.

## Documentation

Documentation is available at https://kompass.secondnetwork.de

## Screenshots

at https://kompass.secondnetwork.de/docs/screenshots

## Security

If you discover any security related issues, please email <github@secondnetwork.de> instead of using the issue tracker.

## Credits

-   [Andreas Farah](https://github.com/secondnetwork)
-   [All Contributors](../../contributors)

## License

The Kompass CMS is open-sourced software licensed under the [MIT](LICENSE.md).
