# 🏗️ Architecture Documentation

## Table of Contents

- [Overview](#overview)
- [Domain-Driven Design Principles](#domain-driven-design-principles)
- [Application Contexts](#application-contexts)
- [Portfolio Context Architecture](#portfolio-context-architecture)
- [Admin Context Architecture](#admin-context-architecture)
- [Testing Strategy](#testing-strategy)

## Overview

Soundscape is a sound engineer portfolio application with **two distinct contexts**:

1. **Portfolio Context**: Public-facing portfolio showcasing projects and engineer information
2. **Admin Context**: Administrative interface for managing projects and content

The application follows **Domain-Driven Design (DDD)** principles with a clear separation between:
- **Domain Layer**: Business logic, entities, value objects, and domain services
- **Application Layer**: Use cases, DTOs, application services, and orchestration
- **Infrastructure Layer**: Database repositories, external services, and technical implementations
- **HTTP Layer**: Controllers, requests, and presentation logic

## Domain-Driven Design Principles

### Layered Architecture

```
┌─────────────────────────────────────┐
│         HTTP Layer                  │  Controllers, Requests, Middleware
│  (Controllers, Livewire)            │
└─────────────────┬───────────────────┘
                  │
┌─────────────────▼───────────────────┐
│      Application Layer              │  Use Cases, DTOs, Services
│  (Commands, Queries, Services)      │
└─────────────────┬───────────────────┘
                  │
┌─────────────────▼───────────────────┐
│        Domain Layer                 │  Business Logic, Rules
│  (Entities, Value Objects,          │
│   Domain Services, Exceptions)      │
└─────────────────┬───────────────────┘
                  │
┌─────────────────▼───────────────────┐
│    Infrastructure Layer             │  Technical Implementations
│  (Repositories, Database, Cache)    │
└─────────────────────────────────────┘
```

### Key Principles

1. **Domain Independence**: Domain layer has no dependencies on infrastructure
2. **Repository Pattern**: Data access abstracted behind interfaces
3. **Value Objects**: Immutable objects with validation logic
4. **Domain Entities**: Business objects with identity and behavior
5. **Single Responsibility**: Each class has one reason to change
6. **Type Safety**: PHPStan level 9 compliance with strict types

## Application Contexts

Soundscape is organized around **two bounded contexts**:

### 1. Portfolio Context (Public Interface)

**Purpose**: Present sound engineer's work to potential clients

**Features**:
- Homepage with hero section and features
- About page with biography and services
- Projects showcase with detailed project pages
- Contact form with GDPR compliance
- Multi-language support (EN/FR)

**Domain Concerns**:
- Page content validation (minimum required fields)
- Section visibility management
- Published projects display
- Contact message handling

**User Roles**: Anonymous visitors, potential clients

### 2. Admin Context (Management Interface)

**Purpose**: Manage portfolio content and projects

**Features**:
- Dashboard with statistics
- Project CRUD with media management
- Content management system
- Contact message management
- Section visibility settings
- User settings and authentication

**Domain Concerns**:
- Project lifecycle (Draft → Published → Archived)
- Content validation and updates
- Media uploads with conversions
- Message tracking and responses

**User Roles**: Authenticated administrator (sound engineer)

### Context Separation

```
Portfolio Context              Admin Context
├── Public routes             ├── Authenticated routes
├── Read-only operations      ├── Write operations
├── Optimized for display     ├── Optimized for management
├── SEO-focused               ├── UX-focused (Flux UI)
└── Minimal UI                └── Rich admin interface
```

## Portfolio Context Architecture

### Problem Solved

**Before DDD Refactoring:**
- 316 lines of complex mock-heavy tests
- 34+ database queries per page load
- Validation logic scattered across application layer
- Tight coupling to Eloquent ORM
- Difficult to test without database

**After DDD Refactoring:**
- ~150 lines of pure domain tests
- 1 database query per page load (97% reduction)
- Validation logic encapsulated in domain entities
- Clean separation of concerns
- Fast, reliable tests

### Architecture Components

#### 1. Domain Layer

**Value Object: PageField**
```php
final readonly class PageField
{
    private function __construct(
        private string $key,
        private ?string $content,
    ) {}

    public static function fromKeyAndContent(string $key, ?string $content): self
    {
        return new self($key, $content);
    }

    public function isEmpty(): bool
    {
        return $this->content === null || trim($this->content) === '';
    }
}
```

**Abstract Entity: PortfolioPage**
```php
abstract class PortfolioPage
{
    protected function __construct(
        protected readonly array $fields,
    ) {}

    abstract public function hasMinimumContent(): bool;
    abstract public function getMissingFields(): array;

    protected function allFieldsHaveContent(array $requiredKeys): bool
    {
        foreach ($requiredKeys as $key) {
            $field = $this->findField($key);
            if ($field === null || $field->isEmpty()) {
                return false;
            }
        }
        return true;
    }
}
```

**Concrete Entity: HomePage**
```php
final class HomePage extends PortfolioPage
{
    private const HERO_REQUIRED_FIELDS = [
        'home_hero_title',
        'home_hero_subtitle',
        'home_hero_text',
    ];

    private const FEATURES_REQUIRED_FIELDS = [
        'home_feature_1_title',
        'home_feature_1_description',
        'home_feature_2_title',
        'home_feature_2_description',
        'home_feature_3_title',
        'home_feature_3_description',
    ];

    public function hasMinimumContent(): bool
    {
        // Hero always required
        if (!$this->allFieldsHaveContent(self::HERO_REQUIRED_FIELDS)) {
            return false;
        }

        // Features conditionally required
        if ($this->sectionVisibilityService->isSectionEnabled('features', 'home')) {
            return $this->allFieldsHaveContent(self::FEATURES_REQUIRED_FIELDS);
        }

        return true;
    }
}
```

**Repository Interface**
```php
interface PageContentRepositoryInterface
{
    /**
     * @param string $page (home, about, contact)
     * @return array<PageField>
     */
    public function getFieldsForPage(string $page): array;
}
```

#### 2. Infrastructure Layer

**Eloquent Repository Implementation**
```php
final readonly class PageContentEloquentRepository implements PageContentRepositoryInterface
{
    public function getFieldsForPage(string $page): array
    {
        // Single query fetches all fields for the page
        $pageContents = PageContent::where('page', $page)->get();

        // Transform Eloquent models to domain Value Objects
        return $pageContents->map(function (PageContent $content) {
            return PageField::fromKeyAndContent(
                $content->key,
                $content->content
            );
        })->all();
    }
}
```

#### 3. Application Layer

**Content Service**
```php
class ContentService
{
    public function __construct(
        private readonly SectionVisibilityQueryInterface $sectionVisibilityService,
        private readonly PageContentRepositoryInterface $pageContentRepository,
    ) {}

    public function getHomePage(): HomePage
    {
        $fields = $this->pageContentRepository->getFieldsForPage('home');
        return HomePage::reconstitute($fields, $this->sectionVisibilityService);
    }

    // Legacy methods for backward compatibility
    public function getHomeContent(): array { ... }
}
```

#### 4. HTTP Layer

**Controller**
```php
class HomeController extends Controller
{
    public function __construct(
        private readonly ContentService $contentService,
    ) {}

    public function __invoke(): View
    {
        // Use domain entity for validation
        $homePage = $this->contentService->getHomePage();

        if (!$homePage->hasMinimumContent()) {
            return view('portfolio.empty-state', [
                'title' => __('portfolio.empty_state.home.title'),
                'description' => __('portfolio.empty_state.home.description'),
            ]);
        }

        // Get data for view rendering
        $content = $this->contentService->getHomeContent();

        return view('portfolio.home', [
            'content' => $content,
            'seo' => [...],
        ]);
    }
}
```

### Testing Strategy

#### Domain Tests (Pure Unit Tests)

**No Database, Minimal Mocking**
```php
test('home page has minimum content with only hero section', function () {
    // Given: Create domain objects directly
    $fields = [
        PageField::fromKeyAndContent('home_hero_title', 'Title'),
        PageField::fromKeyAndContent('home_hero_subtitle', 'Subtitle'),
        PageField::fromKeyAndContent('home_hero_text', 'Text'),
    ];

    // Mock only domain interfaces
    /** @var SectionVisibilityQueryInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityQueryInterface::class);

    /** @var Expectation $expectation */
    $expectation = $sectionService->shouldReceive('isSectionEnabled');
    $expectation->with('features', 'home')->andReturn(false);

    // When: Reconstitute entity
    $page = HomePage::reconstitute($fields, $sectionService);

    // Then: Verify business rules
    expect($page->hasMinimumContent())->toBeTrue()
        ->and($page->getMissingFields())->toBeEmpty();
});
```

**Benefits:**
- Fast execution (<1ms per test)
- No database setup/teardown
- Tests business logic in isolation
- Easy to maintain
- PHPStan compliant

#### Infrastructure Tests

**Repository Implementation Tests**
```php
test('repository returns PageField array for page', function () {
    // Given: Page content in database
    PageContent::factory()->create([
        'key' => 'home_hero_title',
        'content' => 'Welcome',
        'page' => 'home'
    ]);

    // When: Get fields from repository
    $repository = app(PageContentRepositoryInterface::class);
    $fields = $repository->getFieldsForPage('home');

    // Then: Returns array of PageField value objects
    expect($fields)->toBeArray()
        ->and($fields[0])->toBeInstanceOf(PageField::class)
        ->and($fields[0]->getKey())->toBe('home_hero_title')
        ->and($fields[0]->getContent())->toBe('Welcome');
});
```

#### Feature Tests

**End-to-End Tests**
```php
test('home page displays when minimum content exists', function () {
    // Given: Minimum content in database
    PageContent::factory()->create([
        'key' => 'home_hero_title',
        'content' => 'Soundscape Audio',
        'page' => 'home'
    ]);
    PageContent::factory()->create([
        'key' => 'home_hero_subtitle',
        'content' => 'Professional Audio',
        'page' => 'home'
    ]);
    PageContent::factory()->create([
        'key' => 'home_hero_text',
        'content' => 'Transform your audio',
        'page' => 'home'
    ]);

    // And: Features section disabled
    SectionSetting::factory()->create([
        'section_key' => 'features',
        'page' => 'home',
        'is_enabled' => false
    ]);

    // When: User visits home page
    $response = $this->get('/');

    // Then: Page renders successfully
    $response->assertStatus(200)
        ->assertViewIs('portfolio.home')
        ->assertSee('Soundscape Audio');
});
```

### Performance Impact

**Query Reduction:**
- Before: 17+ queries per page (1 per field + conditional checks)
- After: 1 query per page (fetch all fields at once)
- Improvement: 97% reduction in database queries

**Test Execution:**
- Before: 316 lines of mock-heavy tests, ~500ms execution
- After: ~150 lines of pure domain tests, ~50ms execution
- Improvement: 90% faster test execution

### Extension Points

**Adding a New Page**

1. Create entity in `app/Domain/Portfolio/Entities/`:
```php
final class ProjectsPage extends PortfolioPage
{
    private const REQUIRED_FIELDS = ['projects_title', 'projects_intro'];

    public function hasMinimumContent(): bool
    {
        return $this->allFieldsHaveContent(self::REQUIRED_FIELDS);
    }
}
```

2. Add method to ContentService:
```php
public function getProjectsPage(): ProjectsPage
{
    $fields = $this->pageContentRepository->getFieldsForPage('projects');
    return ProjectsPage::reconstitute($fields);
}
```

3. Update controller to use entity:
```php
public function __invoke(): View
{
    $projectsPage = $this->contentService->getProjectsPage();

    if (!$projectsPage->hasMinimumContent()) {
        return view('portfolio.empty-state', [...]);
    }

    return view('portfolio.projects', [...]);
}
```

4. Write domain tests for new entity.

### CQRS Pattern: Section Visibility Management

The section visibility feature demonstrates a complete **Command Query Responsibility Segregation (CQRS)** implementation, separating read operations (Portfolio context) from write operations (Admin context).

#### Problem & Solution

**Challenge**: The original `SectionVisibilityService` mixed concerns:
- Portfolio needed to CHECK if sections are enabled (READ)
- Admin needed to MODIFY section settings (WRITE)
- Single service violated Single Responsibility Principle
- Difficult to test and maintain

**Solution**: Split into two dedicated services following CQRS:
1. **Query Service** (Portfolio) - READ-only operations
2. **Command Service** (Admin) - WRITE operations

#### Architecture Components

**Query Side (Portfolio Context)**

```php
// Domain Interface
interface SectionVisibilityQueryInterface
{
    public function isSectionEnabled(string $sectionKey, string $page): bool;
    public function getEnabledSectionsForPage(string $page): array;
    public function getNonDisableableSectionsForPage(string $page): array;
}

// Application Implementation
readonly class SectionVisibilityQueryService implements SectionVisibilityQueryInterface
{
    public function __construct(
        private SectionSettingRepository $sectionSettingRepository
    ) {}

    public function isSectionEnabled(string $sectionKey, string $page): bool
    {
        // Non-disableable sections always enabled
        if (!SectionKeys::isValidSectionForPage($sectionKey, $page)) {
            return true;
        }

        return $this->sectionSettingRepository->isSectionEnabled($sectionKey, $page);
    }

    // ... other read methods
}
```

**Command Side (Admin Context)**

```php
// Domain Interface
interface SectionSettingsCommandInterface
{
    public function setSectionEnabled(string $sectionKey, string $page, bool $enabled): bool;
    public function getAvailableSectionSettings(): array;
    public function bulkUpdateSectionSettings(array $settings): void;
}

// Application Implementation
readonly class SectionSettingsCommandService implements SectionSettingsCommandInterface
{
    public function __construct(
        private SectionSettingRepository $sectionSettingRepository
    ) {}

    public function setSectionEnabled(string $sectionKey, string $page, bool $enabled): bool
    {
        // Validate section can be modified
        if (!SectionKeys::isValidSectionForPage($sectionKey, $page)) {
            return false;
        }

        $this->sectionSettingRepository->setSectionEnabled($sectionKey, $page, $enabled);
        return true;
    }

    // ... other write methods
}
```

#### Service Bindings

```php
// AppServiceProvider.php
public function register(): void
{
    // Query Service (Portfolio - Read)
    $this->app->bind(
        SectionVisibilityQueryInterface::class,
        SectionVisibilityQueryService::class
    );

    // Command Service (Admin - Write)
    $this->app->bind(
        SectionSettingsCommandInterface::class,
        SectionSettingsCommandService::class
    );
}
```

#### Consumers

**Portfolio Consumers (Query Service)**:
- `HomePage::reconstitute()` - Checks which sections to validate
- `AboutPage::reconstitute()` - Checks optional sections
- `ContentService` - Provides page data with section flags

**Admin Consumers (Command Service)**:
- `SectionSettingsManager` (Livewire) - Toggles section visibility
- Admin settings interface - Manages section configuration

#### Testing Strategy

**Unit Tests (No Laravel dependencies)**:

```php
// Query Service Test
test('isSectionEnabled returns true for non-disableable sections', function () {
    /** @var SectionSettingRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(SectionSettingRepository::class);
    $service = new SectionVisibilityQueryService($repository);

    // Hero section is always enabled
    $result = $service->isSectionEnabled('hero', 'home');

    expect($result)->toBeTrue();
});

// Command Service Test
test('setSectionEnabled returns false for non-disableable sections', function () {
    /** @var SectionSettingRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(SectionSettingRepository::class);
    $service = new SectionSettingsCommandService($repository);

    // Cannot disable hero section
    $result = $service->setSectionEnabled('hero', 'home', false);

    expect($result)->toBeFalse();
});
```

**Feature Tests (Integration)**:

```php
test('admin can toggle section settings', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(SectionSettingsManager::class)
        ->call('toggleSection', 'features', 'home')
        ->assertDispatched('section-toggled');

    // Verify in database
    expect(SectionSetting::where('section_key', 'features')->first())
        ->is_enabled->toBeFalse();
});
```

#### Benefits

1. **Clear Separation of Concerns**: Read and write operations isolated
2. **Single Responsibility**: Each service has one purpose
3. **Testability**: Pure unit tests without Laravel bootstrapping
4. **Type Safety**: PHPStan level 9 compliant
5. **Maintainability**: Easy to understand and extend
6. **Performance**: No unnecessary coupling between contexts

#### Non-disableable Sections

Certain sections cannot be toggled off as they're essential:

**Home Page**:
- `hero` - Main landing section

**About Page**:
- `hero` - Page header
- `bio` - Core biography content

**Contact Page**:
- `hero` - Page header
- `form` - Contact form
- `info` - Contact information

#### Disableable Sections

Optional sections that can be toggled:

**Home Page**:
- `features` - Feature highlights
- `cta` - Call-to-action section

**About Page**:
- `experience` - Years/projects/clients statistics
- `services` - Services offered list
- `philosophy` - Work philosophy statement

## Admin Context Architecture

The Admin context provides management capabilities for the portfolio. The administrator (sound engineer) can manage projects, content, and settings through a secure interface.

### Projects Showcase Management

The core of the admin context is managing the projects that will be displayed on the public portfolio.

#### Domain Model

**Project Entity** - Represents a sound engineering project with lifecycle management:
- **Status Transitions**: Draft → Published → Archived
- **Value Objects**: Title, Slug, Description, ShortDescription, ClientName, ProjectDate
- **Media**: Featured image and gallery with automatic conversions (thumbnail, web, preview)
- **Bandcamp Integration**: Embed player for audio showcase

**Repository Pattern**:
- `ProjectRepository` interface in Domain layer
- `ProjectDatabaseRepository` implementation in Infrastructure layer

**Commands & Queries** (CQRS pattern):
- **Commands**: CreateProject, UpdateProject, PublishProject, ArchiveProject, DraftProject, DeleteProject
- **Queries**: GetProjects, GetProjectBySlug, GetPublishedProjects, GetPublishedProjectBySlug

#### Key Features

**Project Lifecycle**:
```
Draft → Published → Archived
  ↓         ↓
  ←─────────┘
  (back to draft)
```

- **Draft**: Project in creation, not visible publicly
- **Published**: Project visible on portfolio, requires description
- **Archived**: Historical project, not visible publicly

**Validation Rules**:
- Cannot publish without description
- Cannot archive draft projects
- Cannot publish archived projects (must go back to draft first)

**Media Management**:
- Featured image with 4 conversions (original, thumbnail 300x300, web 800x600, preview 1200x900)
- Gallery with same conversion pipeline
- Automatic responsive images generation
- Spatie Media Library integration

### Content Management System

**Dynamic Page Content**:
- Key-value content system for all portfolio pages
- Supports EN/FR translations
- Real-time editing with Livewire components
- Content validation per page

**Section Visibility (CQRS Implementation)**:

The section visibility feature follows a **CQRS pattern** with separate Query (Portfolio) and Command (Admin) services:

**Query Side (Portfolio - READ operations)**:
- Interface: `SectionVisibilityQueryInterface` (Domain/Portfolio/Services)
- Service: `SectionVisibilityQueryService` (Application/Portfolio/Services)
- Consumers: `HomePage`, `AboutPage`, `ContentService`
- Methods:
  - `isSectionEnabled(string $sectionKey, string $page): bool`
  - `getEnabledSectionsForPage(string $page): array`
  - `getNonDisableableSectionsForPage(string $page): array`

**Command Side (Admin - WRITE operations)**:
- Interface: `SectionSettingsCommandInterface` (Domain/Admin/Services)
- Service: `SectionSettingsCommandService` (Application/Admin/Services)
- Consumers: `SectionSettingsManager` (Livewire component)
- Methods:
  - `setSectionEnabled(string $sectionKey, string $page, bool $enabled): bool`
  - `getAvailableSectionSettings(): array`
  - `bulkUpdateSectionSettings(array $settings): void`

**Features**:
- Toggle optional sections on/off (features, services, philosophy, cta, experience)
- Non-disableable sections (hero, bio, form, info)
- Per-page configuration (home, about, contact)
- Clear separation between read (display) and write (configuration) concerns

### Contact Management

**Message Handling**:
- GDPR-compliant contact form
- Message persistence with read/unread status
- Dashboard widget showing recent messages
- Message search and filtering

### Admin Dashboard

**Statistics & Monitoring**:
- Total projects count by status
- Total content items
- Unread messages count
- Recent contact messages display
- Quick actions for common tasks

## Testing Strategy

### Test Distribution

- **Unit Tests**: Domain entities, value objects, services (~300 tests)
- **Feature Tests**: HTTP endpoints, controllers, Livewire components (~250 tests)
- **Infrastructure Tests**: Repositories, database interactions (~100 tests)

### Coverage Goals

- Domain Layer: 100% coverage
- Application Layer: >95% coverage
- Infrastructure Layer: >90% coverage
- Overall: >90% line coverage

### Test Patterns

1. **Arrange-Act-Assert**: Clear test structure
2. **Given-When-Then**: BDD-style readability
3. **Single Assertion**: One concept per test (where possible)
4. **Descriptive Names**: Tests document behavior
5. **No Implementation Details**: Test public interface only

---

For more details on specific patterns, see individual domain documentation.
