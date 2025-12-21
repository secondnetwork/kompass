# Project: Kompass CMS

## Project Overview

Kompass is a modern content management system (CMS) built on the Laravel framework, utilizing the TALL stack (Tailwind CSS V4, Alpine.js, Laravel, and Livewire V4). It is designed to provide a seamless and dynamic user experience for content management. The project is a Laravel package that can be installed into any Laravel application.

**Key Technologies:**

*   **Backend:** PHP 8.2+, Laravel 11+
*   **Frontend:** Tailwind CSS V4, Alpine.js, Vite, daisyui v5
*   **Dynamic UI:** Livewire V4
*   **Database:** Not specified, but likely MySQL, PostgreSQL, or SQLite (standard for Laravel)
*   **Image Processing:** Intervention Image
∏
## Building and Running

### Installation

1.  **Require the package:**
    ```bash
    composer require secondnetwork/kompass
    ```

2.  **Run the installation command:**
    This command will install frontend assets, create a new admin user, and drop all tables from the database.
    ```bash
    php artisan kompass:install
    ```

### Development

*   **Start the Vite development server:**
    ```bash
    bun run dev
    ```

*   **Build for production:
    ```bash
    bun run build
    ```

### Updating

To keep the frontend assets up-to-date, add the following to your `composer.json` file:

```json
"scripts": {
    "post-update-cmd": [
        "@php artisan vendor:publish --tag=kompass.assets --force"
    ]
}
```

## Development Conventions

*   **Styling:** The project uses Tailwind CSS for styling. Configuration can be found in `tailwind.config.js`.
*   **Frontend Build:** Vite is used for frontend asset bundling. The configuration is in `vite.config.js`.
*   **Dynamic Components:** Livewire is used for creating dynamic interfaces. Livewire components are located in `src/Livewire`.
*   **Blade Components:** The project utilizes Blade components, which are registered in the `KompassServiceProvider`.
*   **Image Handling:** Intervention Image is used for image processing, with configuration in `config/kompass.php`.
*   **Coding Style:** While not explicitly defined, the code follows standard Laravel and PSR conventions.
*   **Package Development:** This project is a Laravel package, so development involves working within the `src` directory and testing its integration with a Laravel application.

## Key Files

*   `composer.json`: Defines PHP dependencies and project metadata.
*   `package.json`: Defines JavaScript dependencies and build scripts.
*   `vite.config.js`: Configuration for the Vite frontend build tool.
*   `config/kompass.php`: Main configuration file for the Kompass package.
*   `src/KompassServiceProvider.php`: The main service provider that registers all the package's resources.
*   `routes/web.php`: Defines the routes for the CMS.
*   `resources/views`: Contains the Blade templates for the CMS.
*   `resources/css`: Contains the CSS for the CMS.
*   `resources/js`: Contains the JavaScript for the CMS.
*   `README.md`: Provides an overview of the project and installation instructions.

---
### Setup Livewire Locally for Contribution

Source: https://livewire.laravel.com/docs/4.x/contribution-guide

This snippet shows how to fork, clone, and set up the Livewire project locally for development. It includes installing composer dependencies and configuring Dusk. This process requires the GitHub CLI.

```bash
# Fork and clone Livewire
gh repo fork livewire/livewire --default-branch-only --clone=true --remote=false -- livewire
 
# Switch the working directory to livewire
cd livewire
 
# Install all composer dependencies
composer install
 
# Ensure Dusk is correctly configured
vendor/bin/dusk-updater detect --no-interaction
```

--------------------------------

### Setup Alpine.js Locally for Contribution

Source: https://livewire.laravel.com/docs/4.x/contribution-guide

This snippet outlines the steps to fork, clone, and build Alpine.js locally, including installing NPM dependencies and linking packages. This is necessary for contributing to Livewire when Alpine.js integration is involved. Requires NPM.

```bash
# Fork and clone Alpine
gh repo fork alpinejs/alpine --default-branch-only --clone=true --remote=false -- alpine
 
# Switch the working directory to alpine
cd alpine
 
# Install all npm dependencies
npm install
 
# Build all Alpine packages
npm run build
 
# Link all Alpine packages locally
cd packages/alpinejs && npm link && cd ../../
cd packages/anchor && npm link && cd ../../
cd packages/collapse && npm link && cd ../../
cd packages/csp && npm link && cd ../../
cd packages/docs && npm link && cd ../../
cd packages/focus && npm link && cd ../../
cd packages/history && npm link && cd ../../
cd packages/intersect && npm link && cd ../../
cd packages/mask && npm link && cd ../../
cd packages/morph && npm link && cd ../../
cd packages/navigate && npm link && cd ../../
cd packages/persist && npm link && cd ../../
cd packages/sort && npm link && cd ../../
cd packages/ui && npm link && cd ../../
 
# Switch the working directory back to livewire
cd ../livewire
 
# Link all packages
npm link alpinejs @alpinejs/anchor @alpinejs/collapse @alpinejs/csp @alpinejs/docs @alpinejs/focus @alpinejs/history @alpinejs/intersect @alpinejs/mask @alpinejs/morph @alpinejs/navigate @alpinejs/persist @alpinejs/sort @alpinejs/ui
 
# Build Livewire
npm run build
```

--------------------------------

### Run Laravel Development Server (Bash)

Source: https://livewire.laravel.com/docs/4.x/quickstart

Starts the Laravel development server. This command is used to run the application locally for testing and development purposes. Access the application via the provided URL.

```bash
php artisan serve
```

--------------------------------

### Default Livewire Layout File Structure

Source: https://livewire.laravel.com/docs/4.x/installation

An example of a default Livewire layout file (`app.blade.php`) including essential HTML structure, asset loading using Vite, and Livewire specific directives (`@livewireStyles`, `@livewireScripts`).

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body>
        {{ $slot }}

        @livewireScripts
    </body>
</html>
```

--------------------------------

### Livewire CSP Configuration Example

Source: https://livewire.laravel.com/docs/4.x/csp

Example of how to enable Livewire's CSP-safe mode by setting the 'csp_safe' option to true in the config/livewire.php file.

```php
'csp_safe' => true,
```

--------------------------------

### Real-world example: Loading states

Source: https://livewire.laravel.com/docs/4.x/javascript

Example of implementing custom loading indicators for specific components using message interceptors.

```APIDOC
## Message Interceptor: Loading States

### Description
Adds a loading class to a component's element when a message is sent and removes it when finished.

### Method
JavaScript Function

### Endpoint
N/A

### Parameters
#### Callback Parameters
- **component** (object) - The component instance.
- **onSend** (function) - Callback executed when the request is sent.
- **onFinish** (function) - Callback executed when message processing is complete.

### Request Example
```javascript
Livewire.interceptMessage(({ component, onSend, onFinish }) => {
    onSend(() => {
        component.el.classList.add('is-loading');
    });
    onFinish(() => {
        component.el.classList.remove('is-loading');
    });
});
```

### Response
#### Success Response (N/A)
N/A

#### Response Example
N/A
```

--------------------------------

### Setup Methods

Source: https://livewire.laravel.com/docs/4.x/testing

Methods for configuring the testing environment before interacting with components.

```APIDOC
## Setup Methods

### `Livewire::test('component.name')`

**Description:** Test the specified Livewire component.

**Method:** `test`

**Endpoint:** N/A

### `Livewire::test(ComponentClass::class, ['param' => $value])`

**Description:** Test a Livewire component using its class, optionally passing parameters to the `mount()` method.

**Method:** `test`

**Endpoint:** N/A

### `Livewire::actingAs($user)`

**Description:** Set the authenticated user for the current test.

**Method:** `actingAs`

**Endpoint:** N/A

### `Livewire::withQueryParams(['key' => 'value'])`

**Description:** Set URL query parameters for the test request.

**Method:** `withQueryParams`

**Endpoint:** N/A

### `Livewire::withCookie('name', 'value')`

**Description:** Set a single cookie for the test request.

**Method:** `withCookie`

**Endpoint:** N/A

### `Livewire::withCookies(['key1' => 'value1', 'key2' => 'value2'])`

**Description:** Set multiple cookies for the test request.

**Method:** `withCookies`

**Endpoint:** N/A

### `Livewire::withHeaders(['X-Header' => 'value'])`

**Description:** Set custom HTTP headers for the test request.

**Method:** `withHeaders`

**Endpoint:** N/A

### `Livewire::withoutLazyLoading()`

**Description:** Disable lazy loading for all components within this test.

**Method:** `withoutLazyLoading`

**Endpoint:** N/A
```

--------------------------------

### Example CSP Headers for Livewire CSP-Safe Build

Source: https://livewire.laravel.com/docs/4.x/csp

Provides an example of Content Security Policy (CSP) headers configured to work with Livewire's CSP-safe build. It emphasizes removing `'unsafe-eval'` and using nonce-based script loading.

```http
Content-Security-Policy: default-src 'self';
                        script-src 'nonce-[random]' 'strict-dynamic';
                        style-src 'self' 'unsafe-inline';
```

--------------------------------

### Install Livewire Volt Package

Source: https://livewire.laravel.com/docs/4.x/volt

This command installs the Livewire Volt package into your project using Composer. This is the initial step to enable Volt's functional API for creating Livewire components.

```bash
composer require livewire/volt
```

--------------------------------

### Install Pest Browser Plugin and Playwright

Source: https://livewire.laravel.com/docs/4.x/testing

Installs the necessary dependencies for browser testing with Livewire and Pest. This involves using Composer for the Pest plugin and npm for Playwright, followed by Playwright's browser binary installation.

```bash
composer require pestphp/pest-plugin-browser --dev
npm install playwright@latest
npx playwright install
```

--------------------------------

### Livewire Component Example

Source: https://livewire.laravel.com/docs/4.x/wire-click

Defines a Livewire component with a public property and a method to handle a file download. It demonstrates how to integrate with Eloquent models and return a downloadable response.

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;

class ShowInvoice extends Component
{
    public Invoice $invoice;

    public function download()
    {
        return response()->download(
            $this->invoice->file_path, 'invoice.pdf'
        );
    }
}
```

--------------------------------

### Example Unit Test Structure in PHP

Source: https://livewire.laravel.com/docs/4.x/contribution-guide

This snippet demonstrates the basic structure of a unit test in PHP for Livewire. It extends a TestCase class and includes a placeholder for test logic. Unit tests focus on the PHP implementation.

```php
use Tests\TestCase;

class UnitTest extends TestCase
{
    public function test_livewire_can_run_action(): void
    {
       // ...
    }
}
```

--------------------------------

### Example Dynamic Child Component (PHP/Livewire)

Source: https://livewire.laravel.com/docs/4.x/nesting

A simple Livewire component that can be rendered dynamically. This serves as a placeholder for 'step-one' content in a multi-step form example. It demonstrates the basic structure of a Livewire component.

```php
<?php // resources/views/components/⚡step-one.blade.php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    Step One Content
</div>
```

--------------------------------

### Define Livewire Routes in Laravel

Source: https://livewire.laravel.com/docs/4.x/navigate

Example of defining routes for Livewire components in Laravel's web.php file. This setup allows for easy integration of Livewire components as distinct pages within a Laravel application.

```php
use App\Livewire\Dashboard;
use App\Livewire\ShowPosts;
use App\Livewire\ShowUsers;

Route::livewire('/', 'pages::dashboard');

Route::livewire('/posts', 'pages::show-posts');

Route::livewire('/users', 'pages::show-users');

```

--------------------------------

### Install Livewire v4 Beta (Composer)

Source: https://livewire.laravel.com/docs/4.x/installation

Installs the Livewire v4 beta version into a Laravel application using Composer. Ensure you have Laravel 10+ and PHP 8.1+.

```bash
composer require livewire/livewire:^4.0@beta
```

--------------------------------

### Livewire Simple Pagination Example (PHP)

Source: https://livewire.laravel.com/docs/4.x/pagination

Demonstrates using Laravel's `simplePaginate()` method for faster and simpler pagination, showing only next and previous links. Requires a `Post` model and a 'show-posts' view.

```php
public function render()
{
    return view('show-posts', [
        'posts' => Post::simplePaginate(10),
    ]);
}
```

--------------------------------

### PHP Component for Livewire Morphing Example

Source: https://livewire.laravel.com/docs/4.x/morphing

This PHP component demonstrates a simple 'Todos' example used to illustrate Livewire's morphing functionality. It includes properties for the current todo item and a list of existing todos, along with a method to add a new todo.

```php
class Todos extends Component
{
    public $todo = '';
 
    public $todos = [
        'first',
        'second',
    ];
 
    public function add()
    {
        $this->todos[] = $this->todo;
    }
}
```

--------------------------------

### Creating and Rendering Components with Namespaces (Artisan & Blade)

Source: https://livewire.laravel.com/docs/4.x/components

Provides examples of using Artisan to create a component within a custom namespace and rendering that component in a Blade view using the namespace prefix.

```bash
php artisan make:livewire admin::users-table

```

```html
<livewire:admin::users-table />

```

--------------------------------

### Import Livewire and Alpine.js in app.js

Source: https://livewire.laravel.com/docs/4.x/installation

Import Livewire and Alpine.js, along with any desired Alpine.js plugins, in your `resources/js/app.js` file when manually bundling assets. This sets up Livewire and Alpine for use in your application.

```javascript
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard'

Alpine.plugin(Clipboard)

Livewire.start()

```

--------------------------------

### Install Pest Testing Framework for Livewire

Source: https://livewire.laravel.com/docs/4.x/testing

This snippet shows the Composer commands to remove PHPUnit and install Pest as the development dependency, which is recommended for testing Livewire components in version 4.x.

```bash
composer remove phpunit/phpunit
composer require pestphp/pest --dev --with-all-dependencies
```

--------------------------------

### Generate Livewire Component Test File

Source: https://livewire.laravel.com/docs/4.x/testing

This Artisan command generates a Livewire component and automatically creates an associated test file, simplifying the setup for testing. The example shows the generated test file for a view-based component.

```bash
php artisan make:livewire post.create --test
```

```php
<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('post.create')
        ->assertStatus(200);
});
```

--------------------------------

### Testing Livewire Components with PHPUnit

Source: https://livewire.laravel.com/docs/4.x/testing

Demonstrates how to test Livewire components using PHPUnit, showcasing equivalent functionality to the Pest examples. This includes creating posts and asserting validation rules.

```php
<?php

namespace Tests\Feature\Livewire;

use Livewire\Livewire;
use App\Models\Post;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    public function test_can_create_post()
    {
        $this->assertEquals(0, Post::count());

        Livewire::test('post.create')
            ->set('title', 'My new post')
            ->set('content', 'Post content')
            ->call('save');

        $this->assertEquals(1, Post::count());
    }

    public function test_title_is_required()
    {
        Livewire::test('post.create')
            ->set('title', '')
            ->call('save')
            ->assertHasErrors('title');
    }
}
```

--------------------------------

### Manually Bundle Livewire and Alpine.js with Vite

Source: https://livewire.laravel.com/docs/4.x/installation

Manually bundle Livewire and Alpine.js using your JavaScript build tool (like Vite) for fine-grained control over initialization and to use Alpine.js plugins. Replace `@livewireScripts` with `@livewireScriptConfig` in your layout. This method requires importing Livewire and Alpine in your `app.js` file.

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body>
        {{ $slot }}

        @livewireScriptConfig
    </body>
</html>

```

--------------------------------

### Example Browser Test Structure in PHP

Source: https://livewire.laravel.com/docs/4.x/contribution-guide

This snippet shows the fundamental structure for a browser test in PHP for Livewire. It inherits from BrowserTestCase and provides a method for defining browser interaction tests. Browser tests primarily target the JavaScript implementation.

```php
use Tests\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    public function test_livewire_can_run_action()
    {
        // ...
    }
}
```

--------------------------------

### Real-world example: Global error handling

Source: https://livewire.laravel.com/docs/4.x/javascript

Example of implementing custom error handling for specific HTTP status codes like session expiration or forbidden access.

```APIDOC
## Request Interceptor: Global Error Handling

### Description
Provides a centralized way to handle specific HTTP error status codes globally.

### Method
JavaScript Function

### Endpoint
N/A

### Parameters
#### Callback Parameters
- **onError** (function) - Callback executed on error status codes.
- **response** (object) - The HTTP response object.
- **preventDefault** (function) - Function to prevent Livewire's default error handling.

### Request Example
```javascript
Livewire.interceptRequest(({ onError }) => {
    onError(({ response, preventDefault }) => {
        if (response.status === 419) {
            preventDefault();
            if (confirm('Your session has expired. Refresh the page?')) {
                window.location.reload();
            }
        }
        if (response.status === 403) {
            preventDefault();
            alert('You do not have permission to perform this action');
        }
    });
});
```

### Response
#### Success Response (N/A)
N/A

#### Response Example
N/A
```

--------------------------------

### Livewire Post Creation Component (PHP)

Source: https://livewire.laravel.com/docs/4.x/quickstart

Defines a Livewire component with properties for title and content, and a save method for validation and data handling. It includes a basic HTML form with Livewire directives for data binding and submission. The save method demonstrates validation and can be extended for database interaction.

```php
<?php

use Livewire\Component;

new class extends Component {
    public string $title = '';

    public string $content = '';

    public function save()
    {
        $this->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        dd($this->title, $this->content);
    }
};
?>

<form wire:submit="save">
    <label>
        Title
        <input type="text" wire:model="title">
        @error('title') <span style="color: red;">{{ $message }}</span> @enderror
    </label>

    <label>
        Content
        <textarea wire:model="content" rows="5"></textarea>
        @error('content') <span style="color: red;">{{ $message }}</span> @enderror
    </label>

    <button type="submit">Save Post</button>
</form>
```

--------------------------------

### Product Filtering Example with Multiple #[Url] Parameters

Source: https://livewire.laravel.com/docs/4.x/attribute-url

Provides a practical Livewire component example demonstrating how to filter products using multiple URL query parameters. It showcases #[Url] attributes for search, category, price range, and sorting, synchronizing them with the URL and updating the product list dynamically.

```php
<?php // resources/views/pages/⚡products.blade.php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Product;

new class extends Component {
    #[Url(as: 'q')]
    public $search = '';

    #[Url]
    public $category = 'all';

    #[Url]
    public $minPrice = 0;

    #[Url]
    public $maxPrice = 1000;

    #[Url]
    public $sort = 'name';

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->category !== 'all', fn($q) => $q->where('category', $this->category))
            ->whereBetween('price', [$this->minPrice, $this->maxPrice])
            ->orderBy($this->sort)
            ->paginate(20);
    }
};
?>

<div>
    <input type="text" wire:model.live="search" placeholder="Search products...">

    <select wire:model.live="category">
        <option value="all">All Categories</option>
        <option value="electronics">Electronics</option>
        <option value="clothing">Clothing</option>
    </select>

    <input type="range" wire:model.live="minPrice" min="0" max="1000">
    <input type="range" wire:model.live="maxPrice" min="0" max="1000">

    <select wire:model.live="sort">
        <option value="name">Name</option>
        <option value="price">Price</option>
        <option value="created_at">Newest</option>
    </select>

    @foreach($this->products as $product)
        <div wire:key="{{ $product->id }}">{{ $product->name }} - ${{ $product->price }}</div>
    @endforeach
</div>

```

--------------------------------

### Register Livewire Route for Post Creation (PHP)

Source: https://livewire.laravel.com/docs/4.x/quickstart

Registers a web route for the post creation page. When a user visits '/post/create', Laravel will render the specified Livewire component ('pages::post.create') within the application's layout.

```php
Route::livewire('/post/create', 'pages::post.create');
```

--------------------------------

### Creating and Managing Git Branches for Contributions

Source: https://livewire.laravel.com/docs/4.x/contribution-guide

This section details the Git commands necessary for preparing your code changes for a pull request. It covers creating a new branch, staging changes, committing them with a message, and pushing the branch to a remote repository.

```bash
git checkout -b my-feature
git add .
git commit -m "Add my feature"
git push origin my-feature

Enumerating objects: 13, done.
Counting objects: 100% (13/13), done.
Delta compression using up to 8 threads
Compressing objects: 100% (6/6), done.

To github.com:Username/livewire.git
 * [new branch]        my-feature -> my-feature
```

--------------------------------

### Test File Downloads in Livewire Using Assertions

Source: https://livewire.laravel.com/docs/4.x/downloads

Provides examples of how to test file downloads in Livewire using the `assertFileDownloaded()` and `assertNoFileDownloaded()` methods. These assertions help verify that the correct file is downloaded or that no download occurs.

```php
use App\Models\Invoice;

public function test_can_download_invoice()
{
    $invoice = Invoice::factory();
 
    Livewire::test(ShowInvoice::class)
        ->call('download')
        ->assertFileDownloaded('invoice.pdf');
}

```

```php
use App\Models\Invoice;

public function test_does_not_download_invoice_if_unauthorised()
{
    $invoice = Invoice::factory();
 
    Livewire::test(ShowInvoice::class)
        ->call('download')
        ->assertNoFileDownloaded();
}

```

--------------------------------

### Initial Rendered HTML for Livewire Morphing

Source: https://livewire.laravel.com/docs/4.x/morphing

This is the HTML output generated by the 'Todos' Livewire component during its initial render. It represents the starting state of the to-do list before any user interaction.

```html
<form wire:submit="add">
    <ul>
        <li>first</li>
 
        <li>second</li>
    </ul>
 
    <input wire:model="todo">
</form>
```

--------------------------------

### Apply data-loading Styles with Plain CSS

Source: https://livewire.laravel.com/docs/4.x/loading-states

This code provides examples of how to style elements based on the `data-loading` attribute using standard CSS, without relying on Tailwind CSS variants. It demonstrates styling the element itself and its children.

```css
[data-loading] {
    opacity: 0.5;
}

button[data-loading] {
    background-color: #ccc;
}

[data-loading] .loading-text {
    display: inline;
}

[data-loading] .default-text {
    display: none;
}
```

--------------------------------

### Livewire Counter Component Example (PHP)

Source: https://livewire.laravel.com/docs/4.x/javascript

A basic Livewire component in PHP demonstrating a counter with an increment method and a render method to display a view. This serves as a basis for understanding the $wire object's interaction with server-side logic.

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 1;

    public function increment()
    {
        $this->count++;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
```

--------------------------------

### Publish Livewire Configuration File (Artisan)

Source: https://livewire.laravel.com/docs/4.x/installation

Publishes Livewire's configuration file to your Laravel application's `config` directory, allowing for customization of its settings.

```bash
php artisan livewire:publish --config
```

--------------------------------

### Livewire Post Creation Component with Database Save (PHP)

Source: https://livewire.laravel.com/docs/4.x/quickstart

An enhanced Livewire component's save method that includes database interaction. After validation, it creates a new 'Post' record using Eloquent and redirects the user. This assumes a 'Post' model and corresponding database table are set up.

```php
public function save()
{
    $validated = $this->validate([
        'title' => 'required|max:255',
        'content' => 'required',
    ]);

    Post::create($validated); // Assumes you have a Post model and database table

    return $this->redirect('/posts');
}
```

--------------------------------

### Livewire Message Interceptors: Loading States Example

Source: https://livewire.laravel.com/docs/4.x/javascript

A practical example of using message interceptors to add and remove a CSS class for visual loading indicators on specific components. It attaches a 'is-loading' class on message send and removes it upon completion.

```javascript
Livewire.interceptMessage(({ component, onSend, onFinish }) => {
    onSend(() => {
        component.el.classList.add('is-loading')
    })
 
    onFinish(() => {
        component.el.classList.remove('is-loading')
    })
})
```

--------------------------------

### Supported Basic Livewire Expressions in CSP Mode

Source: https://livewire.laravel.com/docs/4.x/csp

Examples of basic Livewire expressions that are supported when CSP-safe mode is enabled. These include simple actions and model bindings.

```html
<!--  These work -->
<button wire:click="increment">+</button>
<button wire:click="decrement">-</button>
<button wire:click="reset">Reset</button>
<button wire:click="save">Save</button>
<input wire:model="name">
<input wire:model.live="search">
```

--------------------------------

### Registering a Custom Alpine.js Directive in Livewire Build

Source: https://livewire.laravel.com/docs/4.x/alpine

Provides an example of registering a custom Alpine.js directive, 'x-clipboard', within the main JavaScript file for use across a Livewire application.

```JavaScript
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
 
Alpine.directive('clipboard', (el) => {
    let text = el.textContent
 
    el.addEventListener('click', () => {
        navigator.clipboard.writeText(text)
    })
})
 
Livewire.start()
```

--------------------------------

### Example Livewire Component State (Plain String) - JSON

Source: https://livewire.laravel.com/docs/4.x/synthesizers

This JSON illustrates the serialized state of a simple Livewire component with a plain string property. It's a straightforward key-value pair.

```json
state: { title: '' }
```

--------------------------------

### Livewire Loading Directive Reference

Source: https://livewire.laravel.com/docs/4.x/wire-loading

A reference guide to Livewire's `wire:loading` directive and its available modifiers. This includes directives for targeting specific actions or properties, and various modifiers for controlling display, class, attribute manipulation, and delay intervals.

```html
wire:loading
wire:target="action"
wire:target="property"
wire:target.except="action"
```

--------------------------------

### Configuring Livewire and Vite for Custom JavaScript Bundling

Source: https://livewire.laravel.com/docs/4.x/alpine

Details the setup required to manually bundle Alpine.js with your JavaScript build using Vite, by including the @livewireScriptConfig directive in the layout and importing Livewire and Alpine in app.js.

```Blade
<html>
<head>
    <!-- ... -->
    @livewireStyles
    @vite(['resources/js/app.js'])
</head>
<body>
    {{ $slot }}
 
    @livewireScriptConfig 
</body>
</html>
```

```JavaScript
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
 
// Register any Alpine directives, components, or plugins here...
 
Livewire.start()
```

--------------------------------

### Livewire Cursor Pagination Example (PHP)

Source: https://livewire.laravel.com/docs/4.x/pagination

Illustrates using Laravel's `cursorPaginate()` for efficient pagination on large datasets. The URL will store an encoded cursor instead of a page number.

```php
public function render()
{
    return view('show-posts', [
        'posts' => Post::cursorPaginate(10),
    ]);
}
```

--------------------------------

### Publish Volt Service Provider

Source: https://livewire.laravel.com/docs/4.x/volt

After installing the Volt package, this Artisan command publishes Volt's service provider. This provider configures the directories where Volt will look for single-file components.

```bash
php artisan volt:install
```

--------------------------------

### Implement SPA Navigation with wire:navigate

Source: https://livewire.laravel.com/docs/4.x/wire-navigate

This example demonstrates how to use the `wire:navigate` directive to enable Single Page Application-like navigation. When links with this directive are clicked, Livewire intercepts the navigation, fetches the content in the background, and swaps the current page, resulting in faster and smoother transitions.

```html
<nav>
    <a href="/" wire:navigate>Dashboard</a>
    <a href="/posts" wire:navigate>Posts</a>
    <a href="/users" wire:navigate>Users</a>
</nav>
```

--------------------------------

### Running Livewire Tests with PHPUnit

Source: https://livewire.laravel.com/docs/4.x/contribution-guide

These commands illustrate how to execute tests within the Livewire project using the PHPUnit test runner. You can run all tests or filter to execute a specific test case.

```bash
vendor/bin/phpunit --filter "test_can_make_method_a_computed" # To run a specific test
vendor/bin/phpunit # To run all tests
```

--------------------------------

### Implement Address Data Binding with Livewire Synthesizer (PHP)

Source: https://livewire.laravel.com/docs/4.x/synthesizers

This PHP code defines a Livewire Synthesizer for the Address Data Transfer Object (DTO). It enables `wire:model` binding to the Address object's properties by implementing `match`, `dehydrate`, `hydrate`, `get`, and `set` methods. The `get` and `set` methods are crucial for direct property manipulation during data binding.

```php
use App\Dtos\Address;

class AddressSynth extends Synth
{
    public static $key = 'address';

    public static function match($target)
    {
        return $target instanceof Address;
    }

    public function dehydrate($target)
    {
        return [[
            'street' => $target->street,
            'city' => $target->city,
            'state' => $target->state,
            'zip' => $target->zip,
        ], []];
    }

    public function hydrate($value)
    {
        $instance = new Address;

        $instance->street = $value['street'];
        $instance->city = $value['city'];
        $instance->state = $value['state'];
        $instance->zip = $value['zip'];

        return $instance;
    }

    public function get(&$target, $key)
    {
        return $target->{$key};
    }

    public function set(&$target, $key, $value)
    {
        $target->{$key} = $value;
    }
}
```

--------------------------------

### Livewire Request Interceptors: Global Error Handling Example

Source: https://livewire.laravel.com/docs/4.x/javascript

An example demonstrating global error handling for specific HTTP status codes using request interceptors. It customizes responses for session expiration (419) and forbidden access (403) by preventing default handling and showing user-friendly messages or prompts.

```javascript
Livewire.interceptRequest(({ onError }) => {
    onError(({ response, preventDefault }) => {
        if (response.status === 419) {
            // Session expired
            preventDefault()
 
            if (confirm('Your session has expired. Refresh the page?')) {
                window.location.reload()
            }
        }
 
        if (response.status === 403) {
            // Forbidden
            preventDefault()
 
            alert('You do not have permission to perform this action')
        }
    })
})
```

--------------------------------

### Prefetch Pages on Hover with wire:navigate.hover

Source: https://livewire.laravel.com/docs/4.x/wire-navigate

This example shows how to prefetch pages when a user hovers over a link using the `.hover` modifier with `wire:navigate`. This optimization ensures that the target page is downloaded from the server before the user clicks, leading to near-instantaneous navigation upon click.

```html
<a href="/" wire:navigate.hover>Dashboard</a>
```

--------------------------------

### Livewire: Ignore Elements for Dragging in Sortable List

Source: https://livewire.laravel.com/docs/4.x/wire-sort

This example shows how to prevent specific elements within a sortable list item from initiating drag operations using the `wire:sort:ignore` directive. This is useful for interactive elements like buttons, allowing users to click them without starting a sort. The main list remains sortable.

```html
<ul wire:sort="sortItem">
    @foreach ($todo->items as $item)
        <li wire:sort:item="{{ $item->id }}">
            {{ $item->title }}

            <div wire:sort:ignore>
                <button type="button">Edit</button>
            </div>
        </li>
    @endforeach
</ul>
```

--------------------------------

### Dangerous Async State Mutation Example (PHP)

Source: https://livewire.laravel.com/docs/4.x/attribute-async

This PHP snippet illustrates a dangerous anti-pattern in Livewire: using the #[Async] attribute for an action that mutates component state. This can lead to unpredictable race conditions and lost updates, as shown with the 'increment' counter example.

```PHP
// Warning: This snippet demonstrates what NOT to do...

<?php // resources/views/components/⚡counter.blade.php

use Livewire\Attributes\Async;
use Livewire\Component;

new class extends Component {
    public $count = 0;

    #[Async] // Don't do this! 
    public function increment()
    {
        $this->count++; // State mutation in an async action 
    }
};

```

--------------------------------

### Generate Livewire Layout File (Artisan)

Source: https://livewire.laravel.com/docs/4.x/installation

Generates a default layout file for Livewire components, typically located at `resources/views/layouts/app.blade.php`. This file includes necessary Livewire directives.

```php
php artisan livewire:layout
```

--------------------------------

### Initiate File Download Using Laravel's Storage Facade

Source: https://livewire.laravel.com/docs/4.x/downloads

Demonstrates how to use Laravel's Storage facade within a Livewire component to initiate a file download. This approach is useful when files are stored using Laravel's filesystem abstraction.

```php
public function download()
{
    return Storage::disk('invoices')->download('invoice.csv');
}
```

--------------------------------

### Implement Pagination in Livewire Volt Components

Source: https://livewire.laravel.com/docs/4.x/volt

This example illustrates how to implement pagination in Livewire Volt functional components using the `usesPagination` function. It shows how to fetch paginated data using `Post::paginate(10)` and render pagination links. It also covers switching to Bootstrap styling.

```php
<?php
 
use function Livewire\Volt\{with, usesPagination};
 
usesPagination();
 
with(fn () => ['posts' => Post::paginate(10)]);
 
?>
 
<div>
    @foreach ($posts as $post)
        //
    @endforeach
 
    {{ $posts->links() }}
</div>
```

```php
usesPagination(theme: 'bootstrap');
```

--------------------------------

### Supported Livewire Property Access and Updates in CSP Mode

Source: https://livewire.laravel.com/docs/4.x/csp

Shows examples of accessing and updating nested properties (e.g., user.name) and using $set with properties, which are supported in CSP-safe mode.

```html
<!--  These work -->
<input wire:model="user.name">
<input wire:model="settings.theme">
<button wire:click="$set('user.active', true)">Activate</button>
<div wire:show="user.role === 'admin'">Admin Panel</div>
```

--------------------------------

### Include Livewire Scripts and Styles for Alpine.js

Source: https://livewire.laravel.com/docs/4.x/installation

This HTML snippet demonstrates how to include Livewire's assets using `@livewireStyles` and `@livewireScripts`. This is necessary to enable Alpine.js functionality on pages that do not contain Livewire components. The `@livewireScripts` directive should be placed before the closing `</body>` tag.

```html
<!DOCTYPE html>
<html>
    <head>
        @livewireStyles
    </head>
    <body>
        <!-- No Livewire components, but we want Alpine -->
        <div x-data="{ open: false }">
            <button @click="open = !open">Toggle</button>
        </div>
 
        @livewireScripts
    </body>
</html>
```

--------------------------------

### Livewire Single-File Component Structure (PHP & Blade)

Source: https://livewire.laravel.com/docs/4.x/components

Example of a basic single-file Livewire component. It includes a PHP class extending Livewire\Component with a public property and a save method, and a Blade template with input and button elements bound via wire directives.

```php
<?php

use Livewire\Component;

new class extends Component {
    public $title = '';

    public function save()
    {
        // Save logic here...
    }
};
?>

<div>
    <input wire:model="title" type="text">
    <button wire:click="save">Save Post</button>
</div>
```

--------------------------------

### Configure Livewire Temporary File Upload Rules

Source: https://livewire.laravel.com/docs/4.x/uploads

This code example shows how to customize the global validation rules for temporary file uploads in Livewire. By modifying the `rules` key within the `temporary_file_upload` configuration array in `config/livewire.php`, you can specify allowed file types (mimes) and maximum file sizes. The example sets a 100MB limit and restricts uploads to PNG, JPEG, and PDF formats.

```php
'temporary_file_upload' => [
    // ...
    'rules' => 'file|mimes:png,jpg,pdf|max:102400', // (100MB max, and only accept PNGs, JPEGs, and PDFs)
],
```

--------------------------------

### Dependency Injection in Livewire Actions

Source: https://livewire.laravel.com/docs/4.x/actions

This example illustrates using Laravel's dependency injection within Livewire actions. By type-hinting a parameter with a repository or service, Livewire and Laravel automatically resolve and inject the dependency from the container.

```php
<?php // resources/views/components/post/⚡index.blade.php

use Illuminate\Support\Facades\Auth;
use App\Repositories\PostRepository;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function posts()
    {
        return Auth::user()->posts;
    }

    public function delete(PostRepository $posts, $postId) 
    {
        $posts->deletePost($postId);
    }
};


```

```html
<div>
    @foreach ($this->posts as $post)
        <div wire:key="{{ $post->id }}">
            <h1>{{ $post->title }}</h1>
            <span>{{ $post->content }}</span>

            <button wire:click="delete({{ $post->id }})">Delete</button> 
        </div>
    @endforeach
</div>

```

--------------------------------

### Configure Livewire Temporary Upload Directory

Source: https://livewire.laravel.com/docs/4.x/uploads

This PHP configuration example shows how to change the default directory where Livewire temporarily stores uploaded files. By updating the `directory` option within the `temporary_file_upload` configuration in `config/livewire.php`, you can specify a custom path on the configured disk. The example sets the directory to 'tmp'.

```php
'temporary_file_upload' => [
    // ...
    'directory' => 'tmp',
],
```

--------------------------------

### Livewire Nested Component Lifecycle Methods (PHP)

Source: https://livewire.laravel.com/docs/4.x/nesting

Provides examples of lifecycle methods within a Livewire nested component. These methods, such as `mount()` and `updated()`, allow the child component to manage its own initialization, authorization, and response to property updates independently of the parent component.

```php
public function mount($todo)
{
    $this->authorize('view', $todo);
}

public function updated($property)
{
    // Child-specific update logic
}
```

--------------------------------

### @teleport for Modals, Dropdowns, and Notifications

Source: https://livewire.laravel.com/docs/4.x/directive-teleport

Provides examples of using the @teleport directive for common UI patterns like modal dialogs, dropdown menus, and toast notifications. These examples illustrate targeting specific elements like 'body' or custom containers for rendering the teleported content, ensuring proper display regardless of parent element styling.

```blade
@teleport('body')
    <div class="fixed inset-0 bg-black/50" x-show="showModal">
        <div class="modal">
            <!-- Modal content... -->
        </div>
    </div>
@endteleport
```

```blade
@teleport('body')
    <div class="absolute" x-show="open" style="top: {{ $top }}px; left: {{ $left }}px;">
        <!-- Dropdown items... -->
    </div>
@endteleport
```

```blade
@teleport('#notifications-container')
    <div class="toast">
        {{ $message }}
    </div>
@endteleport
```

--------------------------------

### Initialize Livewire Component Properties

Source: https://livewire.laravel.com/docs/4.x/properties

Demonstrates how to set initial values for component properties using the `mount()` method in Livewire. This is useful for populating data when a component first renders.

```php
<?php // resources/views/components/⚡todos.blade.php

use LivewireComponent;

new class extends Component {
    public $todos = [];

    public $todo = '';

    public function mount()
    {
        $this->todos = ['Buy groceries', 'Walk the dog', 'Write code']; 
    }

    // ...
};

```

--------------------------------

### Valid and Invalid @teleport Usage (Single Root Element)

Source: https://livewire.laravel.com/docs/4.x/directive-teleport

Illustrates the constraint for the @teleport directive requiring a single root element within its statement. The 'Valid' example shows a single div wrapping the content, while the 'Invalid' example demonstrates the error of using multiple root elements directly inside @teleport.

```blade
@teleport('body')
    <div>
        <h2>Title</h2>
        <p>Content</p>
    </div>
@endteleport
```

```blade
@teleport('body')
    <h2>Title</h2>
    <p>Content</p>
@endteleport
```

--------------------------------

### Triggering Component Actions with wire:click

Source: https://livewire.laravel.com/docs/4.x/wire-click

Shows how to use the `wire:click` directive to invoke a component's method when an HTML element is clicked. Includes examples for basic calls and passing parameters.

```html
<button type="button" wire:click="download"> 
    Download Invoice
</button>

<button wire:click="delete({{ $post->id }})">Delete</button>
```

--------------------------------

### PHP Non-Reactive Props Example

Source: https://livewire.laravel.com/docs/4.x/attribute-reactive

Illustrates a scenario where Livewire props are not reactive by default. When a parent component updates, only the parent's state is sent to the server, not the child's. This example shows that without #[Reactive], child components won't automatically update when parent data changes.

```php
<?php // resources/views/components/⚡todos.blade.php

use Livewire\Component;

new class extends Component {
    public $todos = [];

    public function addTodo($text)
    {
        $this->todos[] = ['text' => $text];
        // Child components with $todos props won't automatically update
    }
};
?>
 
<div>
    <livewire:todo-count :$todos />
 
    <button wire:click="addTodo('New task')">Add Todo</button>
</div>

```

--------------------------------

### Initialize Pest Testing Framework

Source: https://livewire.laravel.com/docs/4.x/testing

This command initializes the Pest testing framework in your Laravel project, typically creating a `tests/Pest.php` configuration file.

```bash
./vendor/bin/pest --init
```

--------------------------------

### Test Event Communication Between Components (Pest)

Source: https://livewire.laravel.com/docs/4.x/testing

Demonstrates testing communication between Livewire components through event dispatches. This example shows how a 'post-created' event affects a 'post-count-badge' component.

```php
it('updates post count when event is dispatched', function () {
    $badge = Livewire::test('post-count-badge')
        ->assertSee('0');

    Livewire::test('post.create')
        ->set('title', 'New post')
        ->call('save')
        ->assertDispatched('post-created');

    $badge->dispatch('post-created')
        ->assertSee('1');
});
```

--------------------------------

### Trigger Basic File Download in Livewire Component

Source: https://livewire.laravel.com/docs/4.x/downloads

This example demonstrates how to trigger a file download from a Livewire component using a standard Laravel download response. It includes the component's PHP class and its corresponding Blade view, showing how to attach the download action to a button.

```php
<?php // resources/views/components/⚡show-invoice.blade.php

use Livewire\Component;
use App\Models\Invoice;

new class extends Component {
    public Invoice $invoice;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function download()
    {
        return response()->download( 
            $this->invoice->file_path, 'invoice.pdf'
        );
    }
};

```

```html
<div>
    <h1>{{ $invoice->title }}</h1>
 
    <span>{{ $invoice->date }}</span>
    <span>{{ $invoice->amount }}</span>
 
    <button type="button" wire:click="download">Download</button> 
</div>
```

--------------------------------

### Livewire Component Snapshot Example

Source: https://livewire.laravel.com/docs/4.x/hydration

Illustrates the JSON snapshot created by Livewire during the dehydration process. This snapshot captures the component's state and memo information, essential for re-creating the component on subsequent server requests.

```json
{
    "state": {
        "count": 1
    },

    "memo": {
        "name": "counter",

        "id": "1526456"
    }
}

```

--------------------------------

### Configure Composer Post-Update Command for Livewire Assets

Source: https://livewire.laravel.com/docs/4.x/installation

Add a post-update command to your `composer.json` file to automatically publish Livewire assets when Livewire is updated. This ensures your published assets remain current with the Livewire version.

```json
{
    "scripts": {
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=livewire:assets --ansi --force"
        ]
    }
}

```

--------------------------------

### Listen for Livewire Initialization Events

Source: https://livewire.laravel.com/docs/4.x/javascript

Register callbacks for when Livewire is initialized on the page. 'livewire:init' runs before initialization, and 'livewire:initialized' runs immediately after. These are useful for setting up custom extensions or configurations before Livewire fully loads.

```javascript
document.addEventListener('livewire:init', () => {
    // Runs after Livewire is loaded but before it's initialized
    // on the page...
})

document.addEventListener('livewire:initialized', () => {
    // Runs immediately after Livewire has finished initializing
    // on the page...
})
```

--------------------------------

### Bind Select Dropdown with Dynamic Options

Source: https://livewire.laravel.com/docs/4.x/wire-model

This example demonstrates binding a select dropdown to a Livewire property, where the options are generated dynamically using a Blade foreach loop iterating over a collection of states.

```html
<select wire:model="state">
    @foreach (\App\Models\State::all() as $state)
        <option value="{{ $state->id }}">{{ $state->label }}</option>
    @endforeach
</select>
```

--------------------------------

### Assert Event Dispatches in Tests (Pest)

Source: https://livewire.laravel.com/docs/4.x/testing

Verify that specific events are dispatched from a Livewire component using `assertDispatched()`. This example checks if a 'post-created' event is dispatched.

```php
it('dispatches event when post is created', function () {
    Livewire::test('post.create')
        ->set('title', 'New post')
        ->call('save')
        ->assertDispatched('post-created');
});
```

--------------------------------

### Livewire Component for File Upload

Source: https://livewire.laravel.com/docs/4.x/uploads

This Livewire component, written in PHP, handles file uploads using the `WithFileUploads` trait. It accepts a file upload and stores it to a specified disk and path. This component is designed to work with the file upload testing example.

```php
<?php // resources/views/components/⚡upload-photo.blade.php

use Livewire\WithFileUploads;
use Livewire\Component;

new class extends Component {
    use WithFileUploads;

    public $photo;

    public function upload($name)
    {
        $this->photo->storeAs('/', $name, disk: 'avatars');
    }

    // ...
};
```

--------------------------------

### Assert Dispatched Events with Parameters (Pest)

Source: https://livewire.laravel.com/docs/4.x/testing

Test if events are dispatched with specific parameters using `assertDispatched()`. This example checks for a 'notify' event with a 'message' parameter.

```php
it('dispatches notification when deleting post', function () {
    Livewire::test('post.show')
        ->call('delete', postId: 3)
        ->assertDispatched('notify', message: 'Post deleted');
});
```

--------------------------------

### Publish Livewire Assets to Public Directory

Source: https://livewire.laravel.com/docs/4.x/installation

Publish Livewire's JavaScript assets to your public directory using the `php artisan livewire:publish --assets` command. This allows serving assets directly via your web server. Ensure assets are updated after Livewire updates by adding a script to `composer.json`.

```bash
php artisan livewire:publish --assets

```

--------------------------------

### Registering Livewire Class-Based Components

Source: https://livewire.laravel.com/docs/4.x/components

Demonstrates how to register individual class-based components, locations for components, and namespaces for components within a service provider.

```php
use Livewire\Livewire;

// In a service provider's boot() method (e.g., App\Providers\AppServiceProvider)

// Register an individual class-based component
Livewire::addComponent(
    name: 'todos',
    \App\Livewire\Todos::class
);

// Register a location for class-based components
Livewire::addLocation(
    classNamespace: 'App\Admin\Livewire'
);

// Create a namespace for class-based components
Livewire::addNamespace(
    namespace: 'admin',
    classNamespace: 'App\Admin\Livewire',
    classPath: app_path('Admin/Livewire'),
    classViewPath: resource_path('views/admin/livewire')
);
```

--------------------------------

### Livewire Component Example with Validation and Update Method

Source: https://livewire.laravel.com/docs/4.x/security

A Livewire component demonstrating property validation using #[Validate] and an update method. This component fetches and updates a 'Post' model, relying on route-level middleware for initial authorization.

```php
<?php

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Validate;

class UpdatePost extends Component
{
    public Post $post;

    #[Validate('required|min:5')]
    public $title = '';

    public $content = '';

    public function mount()
    {
        $this->title = $this->post->title;
        $this->content = $this->post->content;
    }

    public function update()
    {
        $this->post->update([
            'title' => $this->title,
            'content' => $this->content,
        ]);
    }
}
```

--------------------------------

### Applying Global Middleware to Livewire Update Route

Source: https://livewire.laravel.com/docs/4.x/security

Shows how to customize the default Livewire update route to apply middleware globally to all Livewire AJAX/fetch requests. This example applies 'LocalizeViewPaths' middleware.

```php
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle)
        ->middleware(App\Http\Middleware\LocalizeViewPaths::class);
});
```

--------------------------------

### Creating Livewire Components (PHP)

Source: https://livewire.laravel.com/docs/4.x/upgrading

Commands to create Livewire components in single-file or multi-file formats. The `--mfc` flag enables multi-file components, and `livewire:convert` can switch between formats.

```php
php artisan make:livewire create-post        # Single-file (default)
php artisan make:livewire create-post --mfc  # Multi-file
php artisan livewire:convert create-post     # Convert between formats
```

--------------------------------

### Assign Different Layouts Per Page

Source: https://livewire.laravel.com/docs/4.x/attribute-layout

Shows a pattern for utilizing different layouts for various sections of an application. This example demonstrates assigning distinct layouts ('admin', 'marketing', 'dashboard') to different full-page components, promoting a modular and organized structure.

```php
// Admin pages
new #[Layout('layouts::admin')] class extends Component { }

// Marketing pages
new #[Layout('layouts::marketing')] class extends Component { }

// Dashboard pages
new #[Layout('layouts::dashboard')] class extends Component { }

```

--------------------------------

### Example Livewire Component State (Stringable) - JSON

Source: https://livewire.laravel.com/docs/4.x/synthesizers

This JSON represents the serialized state of a Livewire component when a property is a Laravel Stringable. It uses a metadata tuple to indicate the original type, allowing Livewire to hydrate it correctly.

```json
state: { title: ['', { s: 'str' }] }
```

--------------------------------

# Tailwind CSS Documentation

Tailwind CSS is a utility-first CSS framework that generates styles by scanning HTML, JavaScript, and template files for class names. It provides a comprehensive design system through CSS utility classes, enabling rapid UI development without writing custom CSS. The framework operates at build-time, analyzing source files and generating only the CSS classes actually used in the project, resulting in optimized production bundles with zero runtime overhead.

The framework includes an extensive default color palette (18 colors with 11 shades each), responsive breakpoint system, customizable design tokens via CSS custom properties, and support for dark mode, pseudo-classes, pseudo-elements, and media queries through variant prefixes. Tailwind CSS v4.1 introduces CSS-first configuration using the `@theme` directive, native support for custom utilities via `@utility`, seamless integration with modern build tools through Vite, PostCSS, and framework-specific plugins, and enhanced arbitrary value syntax for maximum flexibility.

## Installation with Vite

Installing Tailwind CSS using the Vite plugin for modern JavaScript frameworks.

```bash
# Create a new Vite project
npm create vite@latest my-project
cd my-project

# Install Tailwind CSS and Vite plugin
npm install tailwindcss @tailwindcss/vite
```

```javascript
// vite.config.ts
import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    tailwindcss(),
  ],
})
```

```css
/* src/style.css */
@import "tailwindcss";
```

```html
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/src/style.css" rel="stylesheet">
</head>
<body>
  <h1 class="text-3xl font-bold underline">
    Hello world!
  </h1>
</body>
</html>
```

## Utility Classes with Variants

Applying conditional styles using variant prefixes for hover, focus, and responsive breakpoints.

```html
<!-- Hover and focus states -->
<button class="bg-sky-500 hover:bg-sky-700 focus:outline-2 focus:outline-offset-2 focus:outline-sky-500 active:bg-sky-800">
  Save changes
</button>

<!-- Responsive breakpoints -->
<div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
  <!-- 3 columns on mobile, 4 on tablets, 6 on desktop -->
</div>

<!-- Dark mode support -->
<div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
  Content adapts to color scheme preference
</div>

<!-- Multiple variants stacked -->
<button class="bg-violet-500 hover:bg-violet-600 focus:ring-2 focus:ring-violet-300 disabled:opacity-50 disabled:cursor-not-allowed md:text-lg">
  Submit
</button>
```

## Custom Theme Configuration

Defining custom design tokens using the `@theme` directive in CSS.

```css
/* app.css */
@import "tailwindcss";

@theme {
  /* Custom fonts */
  --font-display: "Satoshi", "sans-serif";
  --font-body: "Inter", system-ui, sans-serif;

  /* Custom colors */
  --color-brand-50: oklch(0.98 0.02 264);
  --color-brand-100: oklch(0.95 0.05 264);
  --color-brand-500: oklch(0.55 0.22 264);
  --color-brand-900: oklch(0.25 0.12 264);

  /* Custom breakpoints */
  --breakpoint-3xl: 120rem;
  --breakpoint-4xl: 160rem;

  /* Custom spacing */
  --spacing-18: calc(var(--spacing) * 18);

  /* Custom animations */
  --ease-fluid: cubic-bezier(0.3, 0, 0, 1);
  --ease-snappy: cubic-bezier(0.2, 0, 0, 1);
}
```

```html
<!-- Using custom theme tokens -->
<div class="font-display text-brand-500 3xl:text-6xl">
  Custom design system
</div>
```

## Arbitrary Values

Using square bracket notation for one-off custom values without leaving HTML.

```html
<!-- Arbitrary property values -->
<div class="top-[117px] lg:top-[344px]">
  Pixel-perfect positioning
</div>

<div class="bg-[#bada55] text-[22px] before:content-['Festivus']">
  Custom hex colors, font sizes, and content
</div>

<!-- Arbitrary properties -->
<div class="[mask-type:luminance] hover:[mask-type:alpha]">
  Any CSS property
</div>

<!-- CSS variables -->
<div class="bg-(--my-brand-color) fill-(--icon-color)">
  Reference custom properties
</div>

<!-- Grid with arbitrary values -->
<div class="grid grid-cols-[1fr_500px_2fr]">
  Complex grid layouts
</div>

<!-- Type hints for ambiguous values -->
<div class="text-(length:--my-var)">
  Font size from CSS variable
</div>
<div class="text-(color:--my-var)">
  Color from CSS variable
</div>
```

## Color System

Working with Tailwind's comprehensive color palette and opacity modifiers.

```html
<!-- Using default color palette -->
<div class="bg-sky-500 border-pink-300 text-gray-950">
  Color utilities across all properties
</div>

<!-- Opacity modifiers -->
<div class="bg-black/75 text-white/90">
  Alpha channel with percentage
</div>

<div class="bg-pink-500/[71.37%]">
  Arbitrary opacity values
</div>

<div class="bg-cyan-400/(--my-alpha-value)">
  Opacity from CSS variable
</div>

<!-- Dark mode color variants -->
<div class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700">
  <span class="text-pink-600 dark:text-pink-400">
    Adapts to color scheme
  </span>
</div>

<!-- Color utilities reference -->
<!-- bg-* (background), text-* (text), border-* (border) -->
<!-- decoration-* (text decoration), outline-* (outline) -->
<!-- shadow-* (box shadow), ring-* (ring shadow) -->
<!-- accent-* (form controls), caret-* (text cursor) -->
<!-- fill-* (SVG fill), stroke-* (SVG stroke) -->
```

## Dark Mode

Implementing dark mode with CSS media queries or manual toggle.

```html
<!-- Using prefers-color-scheme (default) -->
<div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
  <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg">
    Content automatically adapts
  </div>
</div>
```

```css
/* Manual dark mode toggle with class selector */
@import "tailwindcss";

@custom-variant dark (&:where(.dark, .dark *));
```

```html
<!-- Manual dark mode -->
<html class="dark">
  <body>
    <div class="bg-white dark:bg-black">
      Controlled by .dark class
    </div>
  </body>
</html>
```

```javascript
// Dark mode toggle logic
// On page load or theme change
document.documentElement.classList.toggle(
  "dark",
  localStorage.theme === "dark" ||
    (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)
);

// User chooses light mode
localStorage.theme = "light";

// User chooses dark mode
localStorage.theme = "dark";

// User chooses system preference
localStorage.removeItem("theme");
```

## State Variants

Styling elements based on pseudo-classes and parent/sibling state.

```html
<!-- Form state variants -->
<input
  type="email"
  required
  class="border-gray-300
         focus:border-sky-500
         focus:ring-2
         focus:ring-sky-300
         invalid:border-pink-500
         invalid:text-pink-600
         disabled:bg-gray-100
         disabled:opacity-50
         placeholder:text-gray-400"
  placeholder="you@example.com"
/>

<!-- List item variants -->
<ul role="list">
  <li class="py-4 first:pt-0 last:pb-0 odd:bg-gray-50 even:bg-white">
    Item content
  </li>
</ul>

<!-- Parent state with group -->
<a href="#" class="group">
  <h3 class="text-gray-900 group-hover:text-white">Title</h3>
  <p class="text-gray-500 group-hover:text-white">Description</p>
</a>

<!-- Sibling state with peer -->
<form>
  <input type="email" class="peer" />
  <p class="invisible peer-invalid:visible text-red-500">
    Please provide a valid email address.
  </p>
</form>

<!-- Has variant -->
<label class="has-checked:bg-indigo-50 has-checked:ring-indigo-200">
  <input type="radio" class="checked:border-indigo-500" />
  Option
</label>
```

## Responsive Design

Building mobile-first responsive layouts with breakpoint variants.

```html
<!-- Mobile-first responsive grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
  <!-- Adapts from 1 to 6 columns -->
</div>

<!-- Responsive spacing and typography -->
<div class="px-4 sm:px-6 lg:px-8">
  <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold">
    Responsive heading
  </h1>
  <p class="mt-2 sm:mt-4 text-sm sm:text-base lg:text-lg">
    Text scales with viewport
  </p>
</div>

<!-- Container queries -->
<div class="@container">
  <div class="flex flex-col @md:flex-row @lg:gap-8">
    <!-- Responds to parent container width -->
  </div>
</div>

<!-- Min/max width breakpoints -->
<div class="hidden md:block">Desktop only</div>
<div class="block md:hidden">Mobile only</div>
<div class="min-[900px]:grid-cols-3">Custom breakpoint</div>
<div class="max-md:text-center">Below medium</div>
```

## Custom Utilities

Creating reusable custom utility classes with variant support.

```css
/* Simple custom utility */
@utility content-auto {
  content-visibility: auto;
}

/* Complex utility with nesting */
@utility scrollbar-hidden {
  &::-webkit-scrollbar {
    display: none;
  }
}

/* Functional utility with theme values */
@theme {
  --tab-size-2: 2;
  --tab-size-4: 4;
  --tab-size-github: 8;
}

@utility tab-* {
  tab-size: --value(--tab-size-*);
}

/* Supporting arbitrary, bare, and theme values */
@utility opacity-* {
  opacity: --value([percentage]);
  opacity: calc(--value(integer) * 1%);
  opacity: --value(--opacity-*);
}

/* Utility with modifiers */
@utility text-* {
  font-size: --value(--text-*, [length]);
  line-height: --modifier(--leading-*, [length], [*]);
}

/* Negative value support */
@utility inset-* {
  inset: --spacing(--value(integer));
  inset: --value([percentage], [length]);
}

@utility -inset-* {
  inset: --spacing(--value(integer) * -1);
  inset: calc(--value([percentage], [length]) * -1);
}
```

```html
<!-- Using custom utilities -->
<div class="content-auto scrollbar-hidden tab-4">
  Custom utilities work with variants
</div>

<div class="hover:tab-github lg:tab-[12]">
  Variants and arbitrary values supported
</div>

<div class="text-2xl/relaxed">
  Utility with modifier (font-size/line-height)
</div>
```

## Custom Variants

Registering custom conditional styles with the `@custom-variant` directive.

```css
/* Simple custom variant */
@custom-variant theme-midnight (&:where([data-theme="midnight"] *));

/* Variant with media query */
@custom-variant any-hover {
  @media (any-hover: hover) {
    &:hover {
      @slot;
    }
  }
}

/* ARIA state variant */
@custom-variant aria-asc (&[aria-sort="ascending"]);
@custom-variant aria-desc (&[aria-sort="descending"]);

/* Data attribute variant */
@custom-variant data-checked (&[data-ui~="checked"]);
```

```html
<!-- Using custom variants -->
<html data-theme="midnight">
  <button class="theme-midnight:bg-black theme-midnight:text-white">
    Midnight theme button
  </button>
</html>

<th aria-sort="ascending" class="aria-asc:rotate-0 aria-desc:rotate-180">
  Sortable column
</th>

<div data-ui="checked active" class="data-checked:underline">
  Checked state
</div>

<!-- Arbitrary variants -->
<div class="[&.is-dragging]:cursor-grabbing [&_p]:mt-4">
  One-off custom selectors
</div>
```

## Applying Variants in CSS

Using the `@variant` directive to apply variants within custom CSS.

```css
/* Single variant */
.my-element {
  background: white;

  @variant dark {
    background: black;
  }
}

/* Nested variants */
.my-button {
  background: white;

  @variant dark {
    background: gray;

    @variant hover {
      background: black;
    }
  }
}

/* Compiled output */
.my-element {
  background: white;
}

@media (prefers-color-scheme: dark) {
  .my-element {
    background: black;
  }
}
```

## Layer Organization

Organizing custom styles into Tailwind's cascade layers.

```css
@import "tailwindcss";

/* Base styles for HTML elements */
@layer base {
  h1 {
    font-size: var(--text-2xl);
    font-weight: bold;
  }

  h2 {
    font-size: var(--text-xl);
    font-weight: 600;
  }

  body {
    font-family: var(--font-body);
  }
}

/* Reusable component classes */
@layer components {
  .btn {
    padding: --spacing(2) --spacing(4);
    border-radius: var(--radius);
    font-weight: 600;
    transition: all 150ms;
  }

  .btn-primary {
    background-color: var(--color-blue-500);
    color: white;
  }

  .card {
    background-color: var(--color-white);
    border-radius: var(--radius-lg);
    padding: --spacing(6);
    box-shadow: var(--shadow-xl);
  }

  /* Third-party component overrides */
  .select2-dropdown {
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
  }
}
```

```html
<!-- Components can be overridden by utilities -->
<div class="card rounded-none">
  Square corners despite card class
</div>

<button class="btn btn-primary hover:bg-blue-600 disabled:opacity-50">
  Component with utility overrides
</button>
```

## Functions and Directives

Using Tailwind's CSS functions for dynamic values and opacity adjustments.

```css
/* Alpha function for opacity */
.my-element {
  color: --alpha(var(--color-lime-300) / 50%);
  background: --alpha(var(--color-blue-500) / 25%);
}

/* Spacing function */
.my-element {
  margin: --spacing(4);
  padding: calc(--spacing(6) - 1px);
}

/* In arbitrary values */
<div class="py-[calc(--spacing(4)-1px)] mt-[--spacing(8)]">
  <!-- ... -->
</div>

/* Source directive for additional content */
@source "../node_modules/@my-company/ui-lib";

/* Apply directive for inline utilities */
.select2-dropdown {
  @apply rounded-b-lg shadow-md;
}

.select2-search {
  @apply rounded border border-gray-300;
}

.select2-results__group {
  @apply text-lg font-bold text-gray-900;
}
```

## Pseudo-elements

Styling ::before, ::after, ::placeholder, and other pseudo-elements.

```html
<!-- Required field indicator -->
<label>
  <span class="after:ml-0.5 after:text-red-500 after:content-['*']">
    Email
  </span>
  <input type="email" class="placeholder:text-gray-400 placeholder:italic" placeholder="you@example.com" />
</label>

<!-- File input styling -->
<input
  type="file"
  class="file:mr-4 file:rounded-full file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-violet-700 hover:file:bg-violet-100"
/>

<!-- Custom list markers -->
<ul class="list-disc marker:text-sky-400">
  <li>First item</li>
  <li>Second item</li>
</ul>

<!-- Text selection styling -->
<div class="selection:bg-fuchsia-300 selection:text-fuchsia-900">
  <p>Select this text to see custom colors</p>
</div>

<!-- First letter drop cap -->
<p class="first-letter:float-left first-letter:mr-3 first-letter:text-7xl first-letter:font-bold first-line:uppercase first-line:tracking-widest">
  Typography with pseudo-elements
</p>
```

## Media Queries

Conditional styling based on user preferences and device capabilities.

```html
<!-- Reduced motion -->
<button class="transition hover:-translate-y-1 motion-reduce:transition-none motion-reduce:hover:translate-y-0">
  Respects user preference
</button>

<button class="motion-safe:animate-spin">
  Only animates if motion allowed
</button>

<!-- Contrast preference -->
<label>
  <input class="contrast-more:border-gray-400 contrast-less:border-gray-100" />
  <p class="opacity-75 contrast-more:opacity-100">
    Adjusts for contrast needs
  </p>
</label>

<!-- Pointer type -->
<div class="grid grid-cols-4 gap-2 pointer-coarse:grid-cols-2 pointer-coarse:gap-4">
  <!-- Larger touch targets on touch devices -->
</div>

<!-- Orientation -->
<div class="portrait:hidden">
  Hidden in portrait mode
</div>

<div class="landscape:grid-cols-2">
  Layout adapts to orientation
</div>

<!-- Print styles -->
<article class="print:hidden">
  Not shown when printing
</article>

<div class="hidden print:block">
  Only visible in print
</div>

<!-- Feature support -->
<div class="flex supports-[display:grid]:grid supports-backdrop-filter:backdrop-blur">
  Progressive enhancement
</div>
```

## Summary

Tailwind CSS provides a complete utility-first design system that eliminates the need for writing custom CSS in most cases. The framework's primary use cases include rapid prototyping, building production applications with consistent design systems, creating responsive layouts, implementing dark mode, and maintaining design consistency across large teams. By using utility classes directly in markup, developers can iterate quickly, avoid naming conventions, and prevent CSS bloat since only used styles are generated.

The v4.1 release enhances the developer experience with CSS-first configuration, eliminating JavaScript configuration files for most projects. Integration patterns include using the Vite plugin for modern frameworks, PostCSS for custom build pipelines, the Tailwind CLI for simple projects, and CDN scripts for rapid prototyping. The framework excels at component-driven development when combined with React, Vue, Svelte, or other modern frameworks, where utility classes are co-located with component logic. Custom design systems can be fully defined in CSS using `@theme`, with project-specific utilities and variants extending the framework's capabilities without writing JavaScript plugins.