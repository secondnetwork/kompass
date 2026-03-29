# Kompass

![Kompass](https://github.com/secondnetwork/kompass/blob/main/public/assets/kompass_md.png?raw=true)

[![Latest Kompass Version](https://img.shields.io/packagist/v/secondnetwork/kompass.svg?style=for-the-badge&label=Kompass&labelColor=FFA700&color=1A2A2C)](https://github.com/secondnetwork/kompass)
[![Laravel](https://img.shields.io/badge/v13.0-999999?style=for-the-badge&label=Laravel&labelColor=eb4432&color=1A2A2C)](https://laravel.com)
[![PHP](https://img.shields.io/badge/v8.3-999999?style=for-the-badge&label=PHP&labelColor=777BB4&color=1A2A2C)](https://php.com)		
[![License](https://img.shields.io/github/license/secondnetwork/kompass?style=for-the-badge)](https://github.com/secondnetwork/kompass)

## The development of Kompass

Kompass is a modern content management system (CMS) built on the [Laravel](http://laravel.com/) framework, utilizing the TALL stack (Tailwind CSS, Alpine.js, Laravel, and Livewire). This powerful combination allows for a seamless and dynamic user experience, making content management easier and more efficient.

## Key Features

- **Modern Tech Stack**: Built with Laravel 13, PHP 8.3+, Livewire 4, and Tailwind CSS 4
- **Multilingual Support**: Full multilingual content management for multiple languages
- **Block-Based Content**: Flexible block system for creating dynamic page layouts
- **Media Library**: Full-featured media management with folder support
- **User Management**: Role-based access control with Laravel Permission

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

## Documentation

Documentation is available at https://kompass.secondnetwork.de

## Screenshots
![screenshot-1](https://github.com/secondnetwork/kompass/blob/main/public/assets/screenshot-1.png?raw=true)
![screenshot-2](https://github.com/secondnetwork/kompass/blob/main/public/assets/screenshot-2.png?raw=true)

## Security

If you discover any security related issues, please email <github@secondnetwork.de> instead of using the issue tracker.

## Credits

-   [Andreas Farah](https://github.com/secondnetwork)
-   [All Contributors](../../contributors)

## License

The Kompass CMS is open-sourced software licensed under the [MIT](LICENSE.md).
