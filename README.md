# Kompass

![Kompass](https://github.com/secondnetwork/kompass/blob/main/public/assets/kompass_md.png?raw=true)

[![Latest Kompass Version](https://img.shields.io/packagist/v/secondnetwork/kompass.svg?style=for-the-badge&label=Kompass&labelColor=FFA700&color=1A2A2C)](https://github.com/secondnetwork/kompass)
[![Laravel](https://img.shields.io/badge/v12.0-999999?style=for-the-badge&label=Laravel&labelColor=eb4432&color=1A2A2C)](https://laravel.com)
[![PHP 8.2](https://img.shields.io/badge/v8.2-999999?style=for-the-badge&label=PHP&labelColor=777BB4&color=1A2A2C)](https://php.com)		
[![License](https://img.shields.io/github/license/secondnetwork/kompass?style=for-the-badge)](https://github.com/secondnetwork/kompass)

## The development of Kompass


> [!WARNING]  
> Kompass is still in development

Kompass is a modern content management system (CMS) built on the [Laravel](http://laravel.com/) framework, utilizing the TALL stack (Tailwind CSS, Alpine.js, Laravel, and Livewire). This powerful combination allows for a seamless and dynamic user experience, making content management easier and more efficient.

[Development Roadmap status](https://kompass.secondnetwork.de/roadmap/)


## Requirements

Additionally Kompass requires you to use
- PHP 8.2 or newer 
- Laravel 11.30 or newer
- Livewire 3.6 or newer
- Tailwindcss 4.0 or newer

## Installation

Kompass is super easy to install. After creating your new Laravel application you can include Kompass.

```bash
composer require secondnetwork/kompass dev-main
```

With the command we install frontend asset, created new admin user and drop all tables from the database.

```bash
php artisan kompass:install  
```

**Publishing the configuration file**

```bash
php artisan vendor:publish --tag=assets --force && php artisan optimize:clear
```

## Documentation

Documentation is available at https://kompass.secondnetwork.de

## Screenshots
![screenshot-1](https://github.com/secondnetwork/kompass/blob/main/public/assets/screenshot-1.png?raw=true)
![screenshot-2](https://github.com/secondnetwork/kompass/blob/main/public/assets/screenshot-2.png?raw=true)

## Postcardware

We highly appreciate you sending us a postcard from your hometown. 

That we know you're using great package(s) from us.

Our address is: B&B. Markenagentur / Digital Unit | Georgstraße 56  30159 Hannover Germany

## Security

If you discover any security related issues, please email <github@secondnetwork.de> instead of using the issue tracker.

## Credits

-   [Andreas Farah](https://github.com/secondnetwork)
-   [All Contributors](../../contributors)

## License

The Kompass CMS is open-sourced software licensed under the [MIT](LICENSE.md).
