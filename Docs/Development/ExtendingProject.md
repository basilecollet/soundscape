# Guide de Développement - Module Project

> Guide pratique pour étendre et maintenir le module Project en suivant les principes DDD/TDD

**Version:** 1.0
**Dernière mise à jour:** 2025-10-10
**Prérequis:** Lecture de `Docs/Architecture/ProjectModule.md`

---

## Table des matières

1. [Introduction](#introduction)
2. [Ajouter une nouvelle commande](#ajouter-une-nouvelle-commande)
3. [Ajouter un nouveau Value Object](#ajouter-un-nouveau-value-object)
4. [Ajouter une exception domaine](#ajouter-une-exception-domaine)
5. [Flow TDD](#flow-tdd)
6. [Bonnes pratiques](#bonnes-pratiques)
7. [Checklist avant commit](#checklist-avant-commit)

---

## Introduction

Ce guide vous accompagne **pas à pas** dans l'extension du module Project. Chaque section contient :

- ✅ **Template de code** prêt à l'emploi
- ✅ **Checklist TDD** complète
- ✅ **Exemples concrets** basés sur le code existant
- ✅ **Commandes Docker** pour tests

### Outils nécessaires

```bash
# Tests
make test                        # Tous les tests
make test-filter FILTER=NomTest  # Tests spécifiques

# Qualité
make pint                        # Formatage code
make phpstan                     # Analyse statique
```

---

## Ajouter une nouvelle commande

Nous allons créer une commande `PublishProject` qui publie un projet (Draft → Published).

### Étape 1 : Créer les tests (RED) ⭕

**Fichier :** `tests/Unit/Application/Admin/Commands/PublishProject/PublishProjectHandlerTest.php`

```php
<?php

declare(strict_types=1);

use App\Application\Admin\Commands\PublishProject\PublishProjectHandler;
use App\Domain\Admin\Entities\Enums\ProjectStatus;
use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;
use App\Domain\Admin\Exceptions\ProjectNotFoundException;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;

test('can publish a draft project', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    // Mock : récupérer un projet Draft
    $project = Project::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        status: ProjectStatus::Draft
    );

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->with(Mockery::on(fn ($arg) => $arg instanceof ProjectSlug && (string) $arg === 'my-project'))
        ->andReturn($project);

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')
        ->once()
        ->with(Mockery::on(function ($arg) {
            return $arg instanceof Project
                && $arg->getStatus() === ProjectStatus::Published;
        }))
        ->andReturnNull();

    $handler = new PublishProjectHandler($repository);

    $handler->handle('my-project');

    expect($project->getStatus())->toBe(ProjectStatus::Published);
});

test('handler throws exception when project not found', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->andThrow(ProjectNotFoundException::forSlug(ProjectSlug::fromString('non-existent')));

    $handler = new PublishProjectHandler($repository);

    expect(fn () => $handler->handle('non-existent'))
        ->toThrow(ProjectNotFoundException::class);
});

test('can publish an archived project', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    // Mock : projet Archived
    $project = Project::reconstitute(
        title: ProjectTitle::fromString('Archived Project'),
        slug: ProjectSlug::fromString('archived-project'),
        status: ProjectStatus::Archived
    );

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->andReturn($project);

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')
        ->once()
        ->andReturnNull();

    $handler = new PublishProjectHandler($repository);

    $handler->handle('archived-project');

    expect($project->getStatus())->toBe(ProjectStatus::Published);
});
```

**Lancer les tests :**
```bash
docker-compose exec app php artisan test --filter=PublishProjectHandlerTest
```

**Résultat attendu :** ⭕ RED - Tests échouent (classe n'existe pas)

---

### Étape 2 : Ajouter la méthode au domaine (GREEN - Partie 1) ✅

**Fichier :** `app/Domain/Admin/Entities/Project.php`

```php
// Ajouter cette méthode dans la classe Project

public function publish(): void
{
    $this->status = ProjectStatus::Published;
}
```

**Note :** La méthode existe déjà dans le projet actuel.

---

### Étape 3 : Créer le handler (GREEN - Partie 2) ✅

**Fichier :** `app/Application/Admin/Commands/PublishProject/PublishProjectHandler.php`

```php
<?php

declare(strict_types=1);

namespace App\Application\Admin\Commands\PublishProject;

use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Repositories\ProjectRepository;

final readonly class PublishProjectHandler
{
    public function __construct(
        private ProjectRepository $repository,
    ) {}

    public function handle(string $slug): void
    {
        $project = $this->repository->getBySlug(
            ProjectSlug::fromString($slug)
        );

        $project->publish();

        $this->repository->store($project);
    }
}
```

**Lancer les tests :**
```bash
docker-compose exec app php artisan test --filter=PublishProjectHandlerTest
```

**Résultat attendu :** ✅ GREEN - Tests passent

---

### Étape 4 : Refactor (REFACTOR) ♻️

**Questions à se poser :**
- Y a-t-il de la duplication ?
- Les noms sont-ils clairs ?
- Peut-on améliorer la lisibilité ?

**Dans notre cas :** Code simple et clair, pas de refactoring nécessaire.

---

### Étape 5 : Intégration dans le controller

**Fichier :** `app/Http/Controllers/Admin/ProjectController.php`

```php
use App\Application\Admin\Commands\PublishProject\PublishProjectHandler;

class ProjectController extends Controller
{
    public function __construct(
        private readonly GetProjectsHandler $getProjectsHandler,
        private readonly DeleteProjectHandler $deleteProjectHandler,
        private readonly PublishProjectHandler $publishProjectHandler, // ➕ Ajout
    ) {}

    // ➕ Nouvelle méthode
    public function publish(Project $project): RedirectResponse
    {
        try {
            $this->publishProjectHandler->handle($project->slug);

            return to_route('admin.project.index')
                ->with('success', 'Projet publié avec succès');
        } catch (ProjectNotFoundException $e) {
            return back()->withErrors(['error' => 'Projet introuvable']);
        }
    }
}
```

**Ajouter la route :**

```php
// routes/web.php ou routes/admin.php
Route::post('projects/{project}/publish', [ProjectController::class, 'publish'])
    ->name('admin.project.publish');
```

---

### Étape 6 : Tests d'intégration (Feature)

**Fichier :** `tests/Feature/Admin/Project/PublishProjectTest.php`

```php
<?php

use App\Models\Project;
use App\Models\User;

test('admin can publish a draft project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['status' => 'draft']);

    $response = $this->actingAs($user)
        ->post(route('admin.project.publish', $project));

    $response->assertRedirect(route('admin.project.index'));

    expect($project->fresh()->status)->toBe('published');
});

test('guest cannot publish project', function () {
    $project = Project::factory()->create(['status' => 'draft']);

    $response = $this->post(route('admin.project.publish', $project));

    $response->assertRedirect(route('login'));
});
```

**Lancer tous les tests :**
```bash
make test
```

---

### Checklist - Ajouter une commande

- [ ] **Étape 1 (RED)** - Tests unitaires du handler écrits
- [ ] **Étape 2 (GREEN)** - Méthode ajoutée à l'entité domaine
- [ ] **Étape 3 (GREEN)** - Handler créé et implémenté
- [ ] Tests unitaires passent ✅
- [ ] **Étape 4 (REFACTOR)** - Code refactorisé si nécessaire
- [ ] **Étape 5** - Intégration dans le controller
- [ ] **Étape 6** - Tests feature ajoutés
- [ ] Tous les tests passent (unitaires + feature)
- [ ] `make pint` - Code formaté
- [ ] `make phpstan` - Analyse statique OK
- [ ] Documentation mise à jour si nécessaire

---

## Ajouter un nouveau Value Object

Exemple : Créer `ProjectBudget` (budget estimé d'un projet)

### Étape 1 : Créer les tests (RED) ⭕

**Fichier :** `tests/Unit/Domain/Admin/Entities/ValueObjects/ProjectBudgetTest.php`

```php
<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\ValueObjects\ProjectBudget;
use App\Domain\Admin\Exceptions\InvalidProjectBudgetException;

test('can create project budget from positive number', function () {
    $budget = ProjectBudget::fromFloat(5000.50);

    expect($budget)->toBeInstanceOf(ProjectBudget::class)
        ->and($budget->toFloat())->toBe(5000.50)
        ->and((string) $budget)->toBe('5000.50');
});

test('can create budget with zero', function () {
    $budget = ProjectBudget::fromFloat(0.0);

    expect($budget->toFloat())->toBe(0.0);
});

test('it throws exception when budget is negative', function () {
    expect(fn () => ProjectBudget::fromFloat(-100.0))
        ->toThrow(InvalidProjectBudgetException::class, 'Project budget cannot be negative');
});

test('budget rounds to 2 decimals', function () {
    $budget = ProjectBudget::fromFloat(5000.999);

    expect($budget->toFloat())->toBe(5001.00);
});

test('two budgets with same value are equal', function () {
    $budget1 = ProjectBudget::fromFloat(5000.0);
    $budget2 = ProjectBudget::fromFloat(5000.0);

    expect($budget1)->toEqual($budget2);
});

test('can format budget with currency', function () {
    $budget = ProjectBudget::fromFloat(5000.50);

    expect($budget->format())->toBe('5 000,50 €');
});
```

**Lancer les tests :**
```bash
docker-compose exec app php artisan test --filter=ProjectBudgetTest
```

**Résultat attendu :** ⭕ RED

---

### Étape 2 : Créer l'exception (GREEN - Partie 1) ✅

**Fichier :** `app/Domain/Admin/Exceptions/InvalidProjectBudgetException.php`

```php
<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidProjectBudgetException extends \DomainException
{
    public static function negative(float $value): self
    {
        return new self(
            sprintf('Project budget cannot be negative (got %.2f)', $value)
        );
    }
}
```

---

### Étape 3 : Créer le Value Object (GREEN - Partie 2) ✅

**Fichier :** `app/Domain/Admin/Entities/ValueObjects/ProjectBudget.php`

```php
<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities\ValueObjects;

use App\Domain\Admin\Exceptions\InvalidProjectBudgetException;

final readonly class ProjectBudget
{
    private function __construct(
        private float $amount,
    ) {}

    public static function fromFloat(float $amount): self
    {
        // Validation : pas de budget négatif
        if ($amount < 0) {
            throw InvalidProjectBudgetException::negative($amount);
        }

        // Arrondi à 2 décimales
        $rounded = round($amount, 2);

        return new self($rounded);
    }

    public function toFloat(): float
    {
        return $this->amount;
    }

    public function __toString(): string
    {
        return (string) $this->amount;
    }

    public function format(string $currency = '€'): string
    {
        return number_format($this->amount, 2, ',', ' ') . ' ' . $currency;
    }
}
```

**Lancer les tests :**
```bash
docker-compose exec app php artisan test --filter=ProjectBudgetTest
```

**Résultat attendu :** ✅ GREEN

---

### Étape 4 : Intégration dans l'entité Project

**Fichier :** `app/Domain/Admin/Entities/Project.php`

```php
// 1. Ajouter la propriété
private function __construct(
    private ProjectTitle $title,
    private ProjectSlug $slug,
    private ProjectStatus $status,
    private ?ProjectDescription $description = null,
    private ?ProjectShortDescription $shortDescription = null,
    private ?ClientName $clientName = null,
    private ?ProjectDate $projectDate = null,
    private ?ProjectBudget $budget = null, // ➕ Ajout
) {}

// 2. Ajouter dans new()
public static function new(
    string $title,
    ?string $description = null,
    ?string $shortDescription = null,
    ?string $clientName = null,
    ?string $projectDate = null,
    ?float $budget = null, // ➕ Ajout
): self {
    // ...
    $budget = $budget !== null && $budget > 0 ? $budget : null;

    return new self(
        // ...
        budget: $budget !== null ? ProjectBudget::fromFloat($budget) : null,
    );
}

// 3. Ajouter dans reconstitute()
public static function reconstitute(
    ProjectTitle $title,
    ProjectSlug $slug,
    ProjectStatus $status,
    ?ProjectDescription $description = null,
    ?ProjectShortDescription $shortDescription = null,
    ?ClientName $clientName = null,
    ?ProjectDate $projectDate = null,
    ?ProjectBudget $budget = null, // ➕ Ajout
): self {
    return new self(
        $title,
        $slug,
        $status,
        $description,
        $shortDescription,
        $clientName,
        $projectDate,
        $budget, // ➕ Ajout
    );
}

// 4. Ajouter le getter
public function getBudget(): ?ProjectBudget
{
    return $this->budget;
}

// 5. Ajouter dans update()
public function update(
    ProjectTitle $title,
    ?ProjectDescription $description = null,
    ?ProjectShortDescription $shortDescription = null,
    ?ClientName $clientName = null,
    ?ProjectDate $projectDate = null,
    ?ProjectBudget $budget = null, // ➕ Ajout
): void {
    $this->title = $title;
    $this->description = $description;
    $this->shortDescription = $shortDescription;
    $this->clientName = $clientName;
    $this->projectDate = $projectDate;
    $this->budget = $budget; // ➕ Ajout
}

// 6. Ajouter dans toArray()
public function toArray(): array
{
    return [
        'title' => (string) $this->title,
        'slug' => (string) $this->slug,
        'status' => $this->status->value,
        'description' => $this->description !== null ? (string) $this->description : null,
        'short_description' => $this->shortDescription !== null ? (string) $this->shortDescription : null,
        'client_name' => $this->clientName !== null ? (string) $this->clientName : null,
        'project_date' => $this->projectDate?->format('Y-m-d'),
        'budget' => $this->budget?->toFloat(), // ➕ Ajout
    ];
}
```

---

### Étape 5 : Migration base de données

```bash
make artisan cmd="make:migration add_budget_to_projects_table"
```

**Fichier :** `database/migrations/XXXX_add_budget_to_projects_table.php`

```php
public function up(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->decimal('budget', 10, 2)->nullable()->after('project_date');
    });
}

public function down(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn('budget');
    });
}
```

```bash
make migrate
```

---

### Étape 6 : Mise à jour Repository

**Fichier :** `app/Infra/Repositories/Admin/ProjectDatabaseRepository.php`

```php
use App\Domain\Admin\Entities\ValueObjects\ProjectBudget;

// Dans getAll() et findBySlug()
return Project::reconstitute(
    title: ProjectTitle::fromString($model->title),
    slug: ProjectSlug::fromString($model->slug),
    status: ProjectStatus::from($model->status),
    description: $model->description !== null ? ProjectDescription::fromString($model->description) : null,
    shortDescription: $model->short_description !== null ? ProjectShortDescription::fromString($model->short_description) : null,
    clientName: $model->client_name !== null ? ClientName::fromString($model->client_name) : null,
    projectDate: $model->project_date !== null ? ProjectDate::fromString($model->project_date) : null,
    budget: $model->budget !== null ? ProjectBudget::fromFloat((float) $model->budget) : null, // ➕ Ajout
);
```

---

### Étape 7 : Tests d'intégration

**Fichier :** `tests/Unit/Domain/Admin/Entities/ProjectTest.php`

```php
test('project can have a budget', function () {
    $project = Project::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        status: ProjectStatus::Draft,
        budget: ProjectBudget::fromFloat(5000.0),
    );

    expect($project->getBudget())->not->toBeNull()
        ->and($project->getBudget()->toFloat())->toBe(5000.0);
});

test('project can have null budget', function () {
    $project = Project::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        status: ProjectStatus::Draft,
    );

    expect($project->getBudget())->toBeNull();
});

test('project toArray includes budget', function () {
    $project = Project::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        status: ProjectStatus::Draft,
        budget: ProjectBudget::fromFloat(5000.0),
    );

    $array = $project->toArray();

    expect($array)->toHaveKey('budget')
        ->and($array['budget'])->toBe(5000.0);
});
```

---

### Checklist - Ajouter un Value Object

- [ ] **Étape 1 (RED)** - Tests du VO écrits
- [ ] **Étape 2 (GREEN)** - Exception domaine créée
- [ ] **Étape 3 (GREEN)** - Value Object implémenté
- [ ] Tests du VO passent ✅
- [ ] **Étape 4** - Intégration dans l'entité Project
- [ ] **Étape 5** - Migration DB créée et exécutée
- [ ] **Étape 6** - Repository mis à jour
- [ ] **Étape 7** - Tests d'intégration ajoutés
- [ ] Tous les tests passent
- [ ] `make pint` - Code formaté
- [ ] `make phpstan` - Analyse statique OK

---

## Ajouter une exception domaine

Les exceptions domaine suivent un pattern simple et cohérent.

### Template d'exception

**Fichier :** `app/Domain/Admin/Exceptions/NomException.php`

```php
<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class NomException extends \DomainException
{
    // Factory method statique pour chaque cas d'erreur
    public static function methodName(/* params */): self
    {
        return new self('Message d\'erreur clair');
    }

    // Autre factory method si nécessaire
    public static function anotherCase(/* params */): self
    {
        return new self(
            sprintf('Message avec param: %s', $param)
        );
    }
}
```

### Exemples du projet

#### Exception simple (1 cas)

```php
final class InvalidProjectDescriptionException extends \DomainException
{
    public static function empty(): self
    {
        return new self('Project description cannot be empty');
    }
}
```

#### Exception avec paramètres

```php
final class InvalidProjectTitleException extends \DomainException
{
    public static function empty(): self
    {
        return new self('Project title cannot be empty');
    }

    public static function tooLong(int $length, int $maxLength = 255): self
    {
        return new self(
            sprintf('Project title cannot exceed %d characters (got %d)', $maxLength, $length)
        );
    }
}
```

#### Exception avec Value Object

```php
final class ProjectNotFoundException extends \DomainException
{
    public static function forSlug(ProjectSlug $slug): self
    {
        return new self(
            sprintf('Project with slug "%s" was not found.', (string) $slug)
        );
    }
}
```

### Bonnes pratiques

✅ **Toujours `final`** - Les exceptions ne doivent pas être étendues
✅ **Factory methods statiques** - `empty()`, `tooLong()`, `forSlug()`, etc.
✅ **Messages clairs** - Expliquer le problème et la valeur si pertinent
✅ **Extend `\DomainException`** - Distinction claire avec exceptions infrastructure
✅ **Nom explicite** - `Invalid*`, `*NotFound`, `Duplicate*`

---

## Flow TDD

### Inside-Out (Bottom-Up)

**Quand l'utiliser :** Quand on connaît bien le domaine et les règles métier.

**Ordre :**
1. Domain (Entities, VOs, Exceptions)
2. Infrastructure (Repository)
3. Application (Handler, DTO)
4. Presentation (Controller)

**Exemple : UpdateProject**

```
1. ⭕ Test: Project::update() method
   ✅ Implémentation: méthode dans Project.php

2. ⭕ Test: Repository::getBySlug()
   ✅ Implémentation: dans ProjectDatabaseRepository

3. ⭕ Test: UpdateProjectHandler
   ✅ Implémentation: handler complet

4. ⭕ Test: Controller integration
   ✅ Implémentation: méthode controller
```

**Avantages :**
- ✅ Domaine pur, sans dépendances
- ✅ Règles métier validées en premier
- ✅ Infrastructure adaptée au domaine

---

### Outside-In (Top-Down)

**Quand l'utiliser :** Quand on découvre le domaine ou pour des features complexes.

**Ordre :**
1. Presentation (Controller, acceptance tests)
2. Application (Handler)
3. Infrastructure (Repository)
4. Domain (Entities, VOs)

**Exemple : PublishProject**

```
1. ⭕ Test: Feature test - admin can publish project
   ✅ Implémentation: route + controller method (avec mocks)

2. ⭕ Test: PublishProjectHandler unit test
   ✅ Implémentation: handler (appelle repository)

3. ⭕ Test: Project::publish() method
   ✅ Implémentation: méthode domaine

4. ♻️ Refactor: Supprimer les mocks, intégration réelle
```

**Avantages :**
- ✅ Focus sur l'expérience utilisateur
- ✅ Découverte progressive du domaine
- ✅ Validation de la valeur métier

---

### Cycle RED-GREEN-REFACTOR

**Peu importe la direction (inside-out ou outside-in), suivez toujours :**

```
⭕ RED
│  1. Écrire un test qui échoue
│  2. Lancer le test → vérifier qu'il échoue bien
│  3. Comprendre pourquoi il échoue
│
↓
✅ GREEN
│  4. Écrire le code MINIMAL pour faire passer le test
│  5. Lancer le test → vérifier qu'il passe
│  6. Ne PAS ajouter de code superflu
│
↓
♻️ REFACTOR
│  7. Améliorer le code (sans changer le comportement)
│  8. Relancer les tests → vérifier qu'ils passent toujours
│  9. Commiter si satisfait
│
↓
⭕ Recommencer avec le test suivant
```

---

## Bonnes pratiques

### 1. Typage strict partout

```php
<?php

declare(strict_types=1);

// TOUJOURS en première ligne après <?php
```

### 2. Handlers readonly avec injection

```php
final readonly class MonHandler
{
    public function __construct(
        private ProjectRepository $repository,
        private OtherService $service,
    ) {}

    public function handle(MonDTO $data): void
    {
        // Logique
    }
}
```

### 3. Value Objects readonly et validés

```php
final readonly class MonValueObject
{
    private function __construct(
        private string $value,
    ) {}

    public static function fromString(string $value): self
    {
        // ✅ TOUJOURS valider à la création
        $value = trim($value);

        if (empty($value)) {
            throw MonException::empty();
        }

        return new self($value);
    }
}
```

### 4. Pas de logique métier dans les handlers

```php
// ❌ MAUVAIS - Logique métier dans handler
public function handle(PublishProjectData $data): void
{
    $project = $this->repository->getBySlug(...);

    if ($project->getStatus() === ProjectStatus::Draft) {
        $project->setStatus(ProjectStatus::Published);
    }
}

// ✅ BON - Logique métier dans domaine
public function handle(string $slug): void
{
    $project = $this->repository->getBySlug(...);
    $project->publish();  // Logique dans l'entité
    $this->repository->store($project);
}
```

### 5. Conversion chaînes vides → null

```php
// ✅ TOUJOURS dans les handlers avant création VOs
$description = $data->description !== null && trim($data->description) !== ''
    ? $data->description
    : null;

$project->update(
    description: $description !== null
        ? ProjectDescription::fromString($description)
        : null
);
```

### 6. Tests : arrange-act-assert

```php
test('descriptive test name', function () {
    // ARRANGE - Préparation
    $repository = Mockery::mock(ProjectRepository::class);
    $handler = new MonHandler($repository);
    $data = MonDTO::fromArray([...]);

    // ACT - Action
    $result = $handler->handle($data);

    // ASSERT - Vérification
    expect($result)->toBeInstanceOf(MonType::class);
});
```

### 7. Nommage explicite

```php
// ✅ BON
test('can publish a draft project', function () { ... });
test('handler throws exception when project not found', function () { ... });

// ❌ MAUVAIS
test('test publish', function () { ... });
test('exception', function () { ... });
```

### 8. Un test = une assertion principale

```php
// ✅ BON
test('can create project with title', function () {
    $project = Project::new(title: 'My Project');

    expect($project->getTitle())->toBeInstanceOf(ProjectTitle::class);
});

test('created project has draft status', function () {
    $project = Project::new(title: 'My Project');

    expect($project->getStatus())->toBe(ProjectStatus::Draft);
});

// ❌ ÉVITER - Trop d'assertions différentes
test('can create project', function () {
    $project = Project::new(title: 'My Project');

    expect($project->getTitle())->toBeInstanceOf(ProjectTitle::class)
        ->and($project->getStatus())->toBe(ProjectStatus::Draft)
        ->and($project->getSlug())->toBe('my-project')
        ->and($project->getDescription())->toBeNull();
});
```

---

## Checklist avant commit

### Tests

- [ ] **Tests unitaires** - Tous les nouveaux handlers/VOs/exceptions testés
- [ ] **Tests feature** - Intégration controller testée si applicable
- [ ] **Tous les tests passent** - `make test` ✅
- [ ] **Coverage suffisant** - Happy path + error path + edge cases

### Qualité de code

- [ ] **Pint** - `make pint` → Code formaté
- [ ] **PHPStan** - `make phpstan` → 0 erreur
- [ ] **Typage strict** - `declare(strict_types=1)` partout
- [ ] **Pas de TODOs** - Code de production propre

### Architecture

- [ ] **Séparation des couches** - Domain pur, pas de dépendances Laravel
- [ ] **Value Objects readonly** - Tous les nouveaux VOs sont readonly
- [ ] **Validation dans VOs** - Pas de validation dans handlers
- [ ] **Exceptions domaine** - Pas d'exceptions génériques

### Documentation

- [ ] **PHPDoc si nécessaire** - Surtout pour méthodes complexes ou interfaces
- [ ] **Commentaires utiles** - Seulement si vraiment nécessaire (code self-explanatory preferred)
- [ ] **README/Docs mis à jour** - Si architecture modifiée

### Git

- [ ] **Message de commit clair** - Format : `feat: add PublishProject command`
- [ ] **Commits atomiques** - Un commit = une fonctionnalité
- [ ] **Pas de code commenté** - Supprimer le code mort
- [ ] **Pas de console.log ou dd()** - Debug code supprimé

---

## Commandes utiles

### Tests

```bash
# Tous les tests
make test

# Tests spécifiques
docker-compose exec app php artisan test --filter=NomTest

# Tests avec coverage (si configuré)
make test-coverage

# Tests d'un dossier spécifique
docker-compose exec app php artisan test tests/Unit/Domain

# Tests avec verbosité
docker-compose exec app php artisan test -v
```

### Qualité

```bash
# Formatage code
make pint

# Formatage avec preview (dry-run)
docker-compose exec app ./vendor/bin/pint --test

# Analyse statique
make phpstan

# Analyse niveau spécifique
docker-compose exec app ./vendor/bin/phpstan analyse --level=5
```

### Base de données

```bash
# Créer migration
make artisan cmd="make:migration nom_migration"

# Lancer migrations
make migrate

# Rollback dernière migration
docker-compose exec app php artisan migrate:rollback

# Reset + migrate
docker-compose exec app php artisan migrate:fresh

# Avec seed
docker-compose exec app php artisan migrate:fresh --seed
```

### Artisan

```bash
# Créer factory
make artisan cmd="make:factory NomFactory"

# Créer seeder
make artisan cmd="make:seeder NomSeeder"

# Créer model + migration + factory + seeder
make artisan cmd="make:model Nom -mfs"
```

---

## Resources

### Références internes

- `Docs/Architecture/ProjectModule.md` - Architecture détaillée
- Tests existants dans `tests/Unit/Application/Admin/Commands/`
- Handlers existants dans `app/Application/Admin/Commands/`

### Documentation externe

- [Pest Documentation](https://pestphp.com/)
- [Mockery Documentation](http://docs.mockery.io/)
- [Laravel Testing](https://laravel.com/docs/12.x/testing)
- [PHPStan Documentation](https://phpstan.org/)

### Livres recommandés

- **Domain-Driven Design** - Eric Evans
- **Implementing Domain-Driven Design** - Vaughn Vernon
- **Clean Code** - Robert C. Martin
- **Test Driven Development: By Example** - Kent Beck

---

**Questions ou blocages ?**

1. Vérifier les exemples existants dans le code
2. Lire `Docs/Architecture/ProjectModule.md`
3. Consulter les tests unitaires similaires
4. Demander à l'équipe 💬

**Happy Coding! 🚀**