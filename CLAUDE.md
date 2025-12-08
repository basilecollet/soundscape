<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.3.24
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- livewire/flux (FLUXUI_FREE) - v2
- livewire/livewire (LIVEWIRE) - v3
- livewire/volt (VOLT) - v1
- larastan/larastan (LARASTAN) - v3
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v3
- tailwindcss (TAILWINDCSS) - v4


## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Localization & Translations
- **IMPORTANT**: This application supports full localization with French (FR) and English (EN) translations.
- When creating or modifying any text displayed to users in the frontend (Blade views, Livewire components, Vue components, etc.), you MUST use translation keys instead of hardcoded strings.
- Default language is French (FR) - all new translations must include both FR and EN versions.

### Translation Files Structure
The application has 4 translation files per language (FR/EN):
- `lang/{locale}/admin.php` - Admin interface translations (dashboard, projects, content management, settings)
- `lang/{locale}/portfolio.php` - Portfolio public interface (home, about, projects, contact)
- `lang/{locale}/ui.php` - Common UI elements (buttons, status, messages, navigation)
- `lang/{locale}/domain.php` - Domain validation messages and errors

### Using Translations

**In Blade templates:**
```blade
{{ __('admin.projects.create') }}
{{ __('domain.project.cannot_publish_invalid_status', ['status' => $status]) }}
<h1>{{ __('portfolio.home.cta.ready_title') }}</h1>
```

**In Livewire/PHP:**
```php
session()->flash('success', __('admin.projects.created_successfully'));
$this->addError('title', __('domain.project.title.empty'));
```

**In Flux UI components:**
```blade
<flux:button>{{ __('ui.common.save') }}</flux:button>
```

### Adding New Translations
When adding new text:
1. Choose the appropriate translation file (admin/portfolio/ui/domain)
2. Add the translation key to BOTH `lang/en/*.php` AND `lang/fr/*.php`
3. Follow existing key naming conventions (dot notation)
4. Use descriptive key names (e.g., `projects.form.title.label` not just `title`)

### Never Hardcode Text
❌ **WRONG:**
```blade
<h1>Créer un nouveau projet</h1>
<button>Save</button>
```

✅ **CORRECT:**
```blade
<h1>{{ __('admin.projects.form.create_title') }}</h1>
<button>{{ __('ui.common.save') }}</button>
```

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.


=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs
- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms


=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.


=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation
- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues
- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization
- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] <name>` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error
- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.


=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure
- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.


=== fluxui-free/core rules ===

## Flux UI Free

- This project is using the free edition of Flux UI. It has full access to the free components and variants, but does not have access to the Pro components.
- Flux UI is a component library for Livewire. Flux is a robust, hand-crafted, UI component library for your Livewire applications. It's built using Tailwind CSS and provides a set of components that are easy to use and customize.
- You should use Flux UI components when available.
- Fallback to standard Blade components if Flux is unavailable.
- If available, use Laravel Boost's `search-docs` tool to get the exact documentation and code snippets available for this project.
- Flux UI components look like this:

<code-snippet name="Flux UI Component Usage Example" lang="blade">
    <flux:button variant="primary"/>
</code-snippet>


### Available Components
This is correct as of Boost installation, but there may be additional components within the codebase.

<available-flux-components>
avatar, badge, brand, breadcrumbs, button, callout, checkbox, dropdown, field, heading, icon, input, modal, navbar, profile, radio, select, separator, switch, text, textarea, tooltip
</available-flux-components>


=== livewire/core rules ===

## Livewire Core
- Use the `search-docs` tool to find exact version specific documentation for how to write Livewire & Livewire tests.
- Use the `php artisan make:livewire [Posts\CreatePost]` artisan command to create new components
- State should live on the server, with the UI reflecting it.
- All Livewire requests hit the Laravel backend, they're like regular HTTP requests. Always validate form data, and run authorization checks in Livewire actions.

## Livewire Best Practices
- Livewire components require a single root element.
- Use `wire:loading` and `wire:dirty` for delightful loading states.
- Add `wire:key` in loops:

    ```blade
    @foreach ($items as $item)
        <div wire:key="item-{{ $item->id }}">
            {{ $item->name }}
        </div>
    @endforeach
    ```

- Prefer lifecycle hooks like `mount()`, `updatedFoo()`) for initialization and reactive side effects:

<code-snippet name="Lifecycle hook examples" lang="php">
    public function mount(User $user) { $this->user = $user; }
    public function updatedSearch() { $this->resetPage(); }
</code-snippet>


## Testing Livewire

<code-snippet name="Example Livewire component test" lang="php">
    Livewire::test(Counter::class)
        ->assertSet('count', 0)
        ->call('increment')
        ->assertSet('count', 1)
        ->assertSee(1)
        ->assertStatus(200);
</code-snippet>


    <code-snippet name="Testing a Livewire component exists within a page" lang="php">
        $this->get('/posts/create')
        ->assertSeeLivewire(CreatePost::class);
    </code-snippet>


=== livewire/v3 rules ===

## Livewire 3

### Key Changes From Livewire 2
- These things changed in Livewire 2, but may not have been updated in this application. Verify this application's setup to ensure you conform with application conventions.
    - Use `wire:model.live` for real-time updates, `wire:model` is now deferred by default.
    - Components now use the `App\Livewire` namespace (not `App\Http\Livewire`).
    - Use `$this->dispatch()` to dispatch events (not `emit` or `dispatchBrowserEvent`).
    - Use the `components.layouts.app` view as the typical layout path (not `layouts.app`).

### New Directives
- `wire:show`, `wire:transition`, `wire:cloak`, `wire:offline`, `wire:target` are available for use. Use the documentation to find usage examples.

### Alpine
- Alpine is now included with Livewire, don't manually include Alpine.js.
- Plugins included with Alpine: persist, intersect, collapse, and focus.

### Lifecycle Hooks
- You can listen for `livewire:init` to hook into Livewire initialization, and `fail.status === 419` for the page expiring:

<code-snippet name="livewire:load example" lang="js">
document.addEventListener('livewire:init', function () {
    Livewire.hook('request', ({ fail }) => {
        if (fail && fail.status === 419) {
            alert('Your session expired');
        }
    });

    Livewire.hook('message.failed', (message, component) => {
        console.error(message);
    });
});
</code-snippet>


=== volt/core rules ===

## Livewire Volt

- This project uses Livewire Volt for interactivity within its pages. New pages requiring interactivity must also use Livewire Volt. There is documentation available for it.
- Make new Volt components using `php artisan make:volt [name] [--test] [--pest]`
- Volt is a **class-based** and **functional** API for Livewire that supports single-file components, allowing a component's PHP logic and Blade templates to co-exist in the same file
- Livewire Volt allows PHP logic and Blade templates in one file. Components use the `@livewire("volt-anonymous-fragment-eyJuYW1lIjoidm9sdC1hbm9ueW1vdXMtZnJhZ21lbnQtYmQ5YWJiNTE3YWMyMTgwOTA1ZmUxMzAxODk0MGJiZmIiLCJwYXRoIjoic3RvcmFnZVwvZnJhbWV3b3JrXC92aWV3c1wvMTUxYWRjZWRjMzBhMzllOWIxNzQ0ZDRiMWRjY2FjYWIuYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]))
</code-snippet>


### Volt Class Based Component Example
To get started, define an anonymous class that extends Livewire\Volt\Component. Within the class, you may utilize all of the features of Livewire using traditional Livewire syntax:


<code-snippet name="Volt Class-based Volt Component Example" lang="php">
use Livewire\Volt\Component;

new class extends Component {
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }
} ?>

<div>
    <h1>{{ $count }}</h1>
    <button wire:click="increment">+</button>
</div>
</code-snippet>


### Testing Volt & Volt Components
- Use the existing directory for tests if it already exists. Otherwise, fallback to `tests/Feature/Volt`.

<code-snippet name="Livewire Test Example" lang="php">
use Livewire\Volt\Volt;

test('counter increments', function () {
    Volt::test('counter')
        ->assertSee('Count: 0')
        ->call('increment')
        ->assertSee('Count: 1');
});
</code-snippet>


<code-snippet name="Volt Component Test Using Pest" lang="php">
declare(strict_types=1);

use App\Models\{User, Product};
use Livewire\Volt\Volt;

test('product form creates product', function () {
    $user = User::factory()->create();

    Volt::test('pages.products.create')
        ->actingAs($user)
        ->set('form.name', 'Test Product')
        ->set('form.description', 'Test Description')
        ->set('form.price', 99.99)
        ->call('create')
        ->assertHasNoErrors();

    expect(Product::where('name', 'Test Product')->exists())->toBeTrue();
});
</code-snippet>


### Common Patterns


<code-snippet name="CRUD With Volt" lang="php">
<?php

use App\Models\Product;
use function Livewire\Volt\{state, computed};

state(['editing' => null, 'search' => '']);

$products = computed(fn() => Product::when($this->search,
    fn($q) => $q->where('name', 'like', "%{$this->search}%")
)->get());

$edit = fn(Product $product) => $this->editing = $product->id;
$delete = fn(Product $product) => $product->delete();

?>

<!-- HTML / UI Here -->
</code-snippet>

<code-snippet name="Real-Time Search With Volt" lang="php">
    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Search..."
    />
</code-snippet>

<code-snippet name="Loading States With Volt" lang="php">
    <flux:button wire:click="save" wire:loading.attr="disabled">
        <span wire:loading.remove>Save</span>
        <span wire:loading>Saving...</span>
    </flux:button>
</code-snippet>


=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.


=== pest/core rules ===

## Pest

### Testing
- If you need to verify a feature is working, write or update a Unit / Feature test.

### Pest Tests
- All tests must be written using Pest. Use `php artisan make:test --pest <name>`.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files - these are core to the application.
- Tests should test all of the happy paths, failure paths, and weird paths.
- Tests live in the `tests/Feature` and `tests/Unit` directories.
- Pest tests look and behave like this:
<code-snippet name="Basic Pest Test Example" lang="php">
it('is true', function () {
    expect(true)->toBeTrue();
});
</code-snippet>

### Running Tests
- Run the minimal number of tests using an appropriate filter before finalizing code edits.
- To run all tests: `php artisan test`.
- To run all tests in a file: `php artisan test tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --filter=testName` (recommended after making a change to a related file).
- When the tests relating to your changes are passing, ask the user if they would like to run the entire test suite to ensure everything is still passing.

### Pest Assertions
- When asserting status codes on a response, use the specific method like `assertForbidden` and `assertNotFound` instead of using `assertStatus(403)` or similar, e.g.:
<code-snippet name="Pest Example Asserting postJson Response" lang="php">
it('returns all', function () {
    $response = $this->postJson('/api/docs', []);

    $response->assertSuccessful();
});
</code-snippet>

### Mocking
- Mocking can be very helpful when appropriate.
- When mocking, you can use the `Pest\Laravel\mock` Pest function, but always import it via `use function Pest\Laravel\mock;` before using it. Alternatively, you can use `$this->mock()` if existing tests do.
- You can also create partial mocks using the same import or self method.

### Datasets
- Use datasets in Pest to simplify tests which have a lot of duplicated data. This is often the case when testing validation rules, so consider going with this solution when writing tests for validation rules.

<code-snippet name="Pest Dataset Example" lang="php">
it('has emails', function (string $email) {
    expect($email)->not->toBeEmpty();
})->with([
    'james' => 'james@laravel.com',
    'taylor' => 'taylor@laravel.com',
]);
</code-snippet>


=== tailwindcss/core rules ===

## Tailwind Core

- Use Tailwind CSS classes to style HTML, check and use existing tailwind conventions within the project before writing your own.
- Offer to extract repeated patterns into components that match the project's conventions (i.e. Blade, JSX, Vue, etc..)
- Think through class placement, order, priority, and defaults - remove redundant classes, add classes to parent or child carefully to limit repetition, group elements logically
- You can use the `search-docs` tool to get exact examples from the official documentation when needed.

### Spacing
- When listing items, use gap utilities for spacing, don't use margins.

    <code-snippet name="Valid Flex Gap Spacing Example" lang="html">
        <div class="flex gap-8">
            <div>Superior</div>
            <div>Michigan</div>
            <div>Erie</div>
        </div>
    </code-snippet>


### Dark Mode
- If existing pages and components support dark mode, new pages and components must support dark mode in a similar way, typically using `dark:`.


=== tailwindcss/v4 rules ===

## Tailwind 4

- Always use Tailwind CSS v4 - do not use the deprecated utilities.
- `corePlugins` is not supported in Tailwind v4.
- In Tailwind v4, you import Tailwind using a regular CSS `@import` statement, not using the `@tailwind` directives used in v3:

<code-snippet name="Tailwind v4 Import Tailwind Diff" lang="diff"
   - @tailwind base;
   - @tailwind components;
   - @tailwind utilities;
   + @import "tailwindcss";
</code-snippet>


### Replaced Utilities
- Tailwind v4 removed deprecated utilities. Do not use the deprecated option - use the replacement.
- Opacity values are still numeric.

| Deprecated |	Replacement |
|------------+--------------|
| bg-opacity-* | bg-black/* |
| text-opacity-* | text-black/* |
| border-opacity-* | border-black/* |
| divide-opacity-* | divide-black/* |
| ring-opacity-* | ring-black/* |
| placeholder-opacity-* | placeholder-black/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |


=== tests rules ===

## Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test` with a specific filename or filter.


=== soundscape-design rules ===

## 🎨 Soundscape Design System

This project follows a specific design system that must be adhered to for consistency across all UI components.

### 🎨 Color Palette
```css
/* Primary Colors - Use for main branding and accents */
--color-primary: #E6B886;           /* Orange doux/beige */
--color-secondary: #F5F1E8;         /* Beige très clair */
--color-accent-green: #8BA888;       /* Vert olive clair */
--color-accent-green-dark: #5D6B5D;  /* Vert olive foncé */
--color-dark: #3A3A3A;              /* Gris anthracite */
--color-light: #F8F6F3;             /* Blanc cassé */
--color-text: #2D2D2D;              /* Gris foncé pour texte */
```

**Usage:**
- Portfolio/Public interface: Always use `portfolio-*` classes (`bg-portfolio-primary`, `text-portfolio-accent`, etc.)
- Admin interface: Use `zinc-*` classes for neutral colors, colored accents for status (blue, green, purple)
- Never mix portfolio and admin color schemes

### 📐 Typography
- **Primary font**: `Kode Mono` (monospace for technical/audio aesthetic)
- **Fallbacks**: `ui-monospace, 'SF Mono', 'Monaco', 'Courier New', monospace`
- **Loading**: Google Fonts with `preconnect` and `font-display: swap`

**Hierarchy:**
- H1: `text-4xl md:text-5xl font-bold` (Hero sections)
- H2: `text-2xl md:text-3xl font-bold` (Section titles)
- H3: `text-xl font-semibold` (Subsections)
- Navigation: `text-sm` (Clean, minimal)
- Body: Default Tailwind sizing

### 📦 Layout & Spacing
- **Container**: Always use `container mx-auto px-6 lg:px-12`
- **Section padding**: `py-16` (main sections), `py-12` (secondary)
- **Card padding**: `p-6` (standard), `p-4` (compact)
- **Grid gaps**: `gap-6` (cards), `gap-4` (form elements)
- **Navigation spacing**: `space-x-8` (desktop), `space-y-4` (mobile)

### 🎯 Component Shapes
- **Cards**: `rounded-xl` (12px) for main cards
- **Buttons**: `rounded-lg` (8px) for interactive elements
- **Inputs**: `rounded-md` (6px) for form fields
- **Avatars**: `rounded-full` (circles) or `rounded-lg` (squares)
- **Responsive navbar**: `md:rounded-full` when scrolled/open

### 🎭 Interactions & Animation
**Standard Transitions:**
```css
transition-all duration-300      /* Cards, major elements */
transition-colors duration-200   /* Links, text changes */
```

**Hover Effects:**
- Cards: `hover:shadow-xl hover:-translate-y-1` (lift effect)
- Buttons: `hover:scale-105` (subtle zoom)
- Links: `hover:text-portfolio-accent`
- Icons: `group-hover:scale-110 transition-transform`

**Special States:**
- Loading: `wire:loading` with pulse effects
- Active navigation: Underline bar `h-[2px] bg-portfolio-accent`
- Mobile indicators: Colored dots `w-2 h-2 bg-portfolio-accent rounded-full`

### 🏗️ Interface Distinction

#### Portfolio/Public Interface
- **Colors**: portfolio-* color scheme (warm, minimal)
- **Layout**: Full-width sections with containers
- **Navigation**: Floating navbar with scroll behavior
- **Style**: Clean, minimal, audio-focused aesthetic

#### Admin Interface
- **Colors**: zinc-* neutrals + colored accents (blue/green/purple for status)
- **Layout**: Sidebar navigation + main content area
- **Components**: Flux UI components exclusively
- **Style**: Dark mode default, data-focused interface

### 📱 Responsive Patterns
**Breakpoints**: Mobile-first with `md:` (768px+) and `lg:` (1024px+)

**Common Patterns:**
- Grids: `grid-cols-1 md:grid-cols-2 lg:grid-cols-3`
- Typography: `text-4xl md:text-5xl`
- Spacing: `px-6 lg:px-12`
- Navigation: Hidden → Sidebar (mobile), Horizontal (desktop)

### ♿ Accessibility Requirements
- Focus visible: `*:focus-visible` with blue outline
- Reduced motion: `@media (prefers-reduced-motion: reduce)` support
- Semantic HTML: Proper heading hierarchy, ARIA labels
- Color contrast: All text meets WCAG AA standards

### 🎨 Visual Elements
- **SVG Icons**: Line-based, minimal style
- **Globe graphic**: Signature element for portfolio
- **Status indicators**: Colored bars on left border for admin cards
- **Empty states**: Centered with icon + descriptive text

### 🚫 Design Don'ts
- Never mix portfolio and admin color schemes
- Don't use colors outside the defined palette
- Avoid heavy shadows or gradients (except hero background)
- No complex animations (keep minimal and purposeful)
- Don't break the monospace typography hierarchy
- Never ignore responsive design patterns

### ✅ Component Creation Checklist
1. Choose correct color scheme (portfolio-* vs zinc-*)
2. Follow typography hierarchy
3. Use standard spacing patterns
4. Include hover/focus states
5. Add responsive breakpoints
6. Test accessibility (focus, color contrast)
7. Use appropriate border radius for element type
8. Include loading/empty states where needed

**Example Usage:**
```blade
<!-- Portfolio Button -->
<button class="bg-portfolio-accent hover:bg-portfolio-accent-dark text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
    Explore Work
</button>

<!-- Admin Card -->
<div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-l-blue-500">
    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Total Content</h3>
    <p class="text-3xl font-bold text-gray-900">{{ $count }}</p>
</div>
```

This design system ensures consistency across all components while maintaining the distinct identity of each interface area.


=== soundscape-commands rules ===

## 🐳 Docker & Makefile Commands

This project runs in Docker containers. **NEVER** run commands directly - always use the Makefile.

### ✅ Correct Commands

**Artisan Commands:**
- Use: `make artisan cmd="migrate"`
- Use: `make artisan cmd="make:model Post"`
- Use: `make artisan cmd="test --filter=ProjectTest"`
- **NEVER** use: `php artisan migrate` (runs outside container)

**Pre-defined Shortcuts:**
```bash
make test                    # Run all Pest tests
make test-filter filter=X    # Run specific test
make test-coverage           # Generate coverage report
make pint                    # Format code (runs vendor/bin/pint in container)
make migrate                 # Run migrations
make fresh                   # Fresh migrations + seeders
make dev                     # Start services + Vite HMR
make shell                   # Access PHP container shell
make init                    # Initialize dev environment
```

**Why This Matters:**
- Ensures commands run in correct PHP 8.4 environment
- Uses PostgreSQL container (not local DB)
- Proper volume mounts and dependencies
- Consistent behavior across machines

### ❌ Never Do This

```bash
# WRONG - runs in host environment
php artisan migrate
vendor/bin/pint
composer install

# CORRECT - runs in Docker
make artisan cmd="migrate"
make pint
make composer cmd="install"
```

### Testing Workflow

1. After code changes: `make test-filter filter=testName` (minimal tests)
2. Confirm all related tests pass
3. Ask user if they want full suite: `make test`
4. Format before commit: `make pint`

### Architecture Enforcement

- **Respect DDD structure**: Domain → Application → Infra → Http
- **Always create tests**: Maintain ~1.67x test-to-code ratio minimum
- **Use Value Objects**: For domain validation and encapsulation
- **Commands/Queries**: Handler pattern for application logic
- **Never skip Pint**: Always run `make pint` before finalizing changes

### Color Scheme Separation

- **Portfolio pages**: Only use `portfolio-*` Tailwind classes
- **Admin pages**: Only use `zinc-*` + Flux UI components
- **Never mix**: Keep visual identity distinct between interfaces

This ensures the project runs consistently in its Docker environment and maintains architectural integrity.
</laravel-boost-guidelines>