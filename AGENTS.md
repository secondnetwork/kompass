# Kompass CMS Agent Guidelines

This document provides essential information for agentic coding agents working on the Kompass CMS repository.

## 🛠 Build, Lint & Test Commands

### PHP (Laravel) Commands
- **Install Dependencies**: `composer install`
- **Database Migrations**: `php artisan migrate`
- **Run Tests**: No dedicated test suite found in `tests/`, but Laravel standard is `php artisan test` or `vendor/bin/phpunit`.
- **Run Single Test**: `php artisan test --filter NameOfTest`
- **Linting/Static Analysis**: Rector is configured. Run `vendor/bin/rector process`.
- **Clear Cache**: `php artisan optimize:clear`

### Frontend Commands
- **Install Dependencies**: `bun install` (preferred based on `bun.lock`) or `yarn install`.
- **Development**: `npm run dev` (starts Vite with `APP_URL='localhost:5088'`).
- **Build Assets**: `npm run build`
- **Vite Preview**: `npm run preview`

## 🎨 Code Style & Conventions

### PHP Backend
- **Namespace**: `Secondnetwork\Kompass\...` (mapped to `src/`).
- **Laravel Version**: Targets Laravel 11/12+ features.
- **Naming Conventions**:
  - **Classes**: PascalCase (e.g., `PagesTable`, `KompassServiceProvider`).
  - **Methods**: camelCase (e.g., `addPage`, `registerGates`).
  - **Models**: Singular PascalCase (e.g., `Page`, `Post`, `Datafield`).
  - **Tables**: Plural snake_case (e.g., `pages`, `blocks`).
- **Type Hinting**: Use strict typing where possible (e.g., `public function boot(): void`).
- **Error Handling**: Use Laravel's exception handling and validation rules in Livewire components (e.g., `$this->validate()`).
- **Dependencies**: TALL stack (Tailwind, Alpine, Laravel, Livewire). Proactively use Livewire 4.0 attributes like `#[Locked]` and `#[Layout]`.

### Imports & Architecture
- **Service Providers**: `src/KompassServiceProvider.php` handles most component registrations and boot logic.
- **Morph Maps**: Important for polymorphic relations (Page, Post, Datafield) are defined in the Service Provider.
- **Livewire Components**: Located in `src/Livewire/`. Controllers are in `src/Http/Controllers/`.
- **Helper Functions**: Custom helpers are in `src/Helpers/` and auto-loaded by the Service Provider.

### Frontend & Styling
- **CSS Framework**: Tailwind CSS 4.0 (oxide engine).
- **UI Components**: Uses Preline UI and DaisyUI.
- **Vite**: Configured to build assets into `public/assets/build`.

## 📂 Project Structure
- `src/`: Main package logic (PSR-4 `Secondnetwork\Kompass\`).
- `resources/views/`: Blade templates (prefixed with `kompass::`).
- `src/database/migrations/`: Migration files.
- `stubs/`: Default application files published during installation.

## ⚠️ Security & Best Practices
- **No Secrets**: Never hardcode API keys or credentials.
- **Cache**: Flush cache in Model boot methods (creating/updating/deleting) as seen in `Page.php`.
- **Slugs**: Use `Illuminate\Support\Str::slug` for URL generation.
- **Assets**: When modifying frontend assets, ensure `npm run build` is executed.
