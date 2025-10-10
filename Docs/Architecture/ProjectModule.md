# Architecture du Module Project

> Documentation de l'architecture DDD/CQRS du module de gestion des projets portfolio

**Version:** 1.0
**Dernière mise à jour:** 2025-10-10
**Auteurs:** Équipe Soundscape

---

## Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture en couches](#architecture-en-couches)
3. [Règles métier critiques](#règles-métier-critiques)
4. [Value Objects](#value-objects)
5. [Commandes et Queries](#commandes-et-queries)
6. [Flow de données](#flow-de-données)
7. [Gestion des erreurs](#gestion-des-erreurs)
8. [Points d'attention](#points-dattention)
9. [Roadmap](#roadmap)

---

## Vue d'ensemble

Le module **Project** permet la gestion complète des projets portfolio d'un ingénieur du son. Il suit une architecture **Domain-Driven Design (DDD)** avec séparation **CQRS (Command Query Responsibility Segregation)**.

### Principes architecturaux

- ✅ **DDD** - Le domaine métier est au cœur, isolé de l'infrastructure
- ✅ **CQRS** - Séparation stricte entre opérations d'écriture (Commands) et de lecture (Queries)
- ✅ **Always Valid** - Les Value Objects garantissent leurs invariants à la création
- ✅ **Immutabilité** - Les Value Objects sont readonly, le slug d'un projet est immutable
- ✅ **TDD** - Développement piloté par les tests (RED-GREEN-REFACTOR)

### Technologies

- **PHP 8.3** avec typage strict
- **Laravel 12** pour l'infrastructure
- **Pest** pour les tests
- **Mockery** pour les mocks

---

## Architecture en couches

```
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                        │
│              (Controllers, Livewire, Views)                  │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                   APPLICATION LAYER                          │
│         (Handlers, DTOs, Commands, Queries)                  │
│  • CreateProjectHandler                                      │
│  • UpdateProjectHandler                                      │
│  • DeleteProjectHandler                                      │
│  • GetProjectsHandler                                        │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                     DOMAIN LAYER                             │
│       (Entities, Value Objects, Repositories, Rules)         │
│  • Project (Entity)                                          │
│  • ProjectTitle, ProjectSlug, ClientName, etc. (VOs)        │
│  • ProjectRepository (Interface)                             │
│  • Exceptions domaine                                        │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                 INFRASTRUCTURE LAYER                         │
│          (Repositories, Eloquent Models, DB)                 │
│  • ProjectDatabaseRepository                                 │
│  • Project Model (Eloquent)                                  │
└─────────────────────────────────────────────────────────────┘
```

### 1. Domain Layer (`app/Domain/Admin/`)

**Responsabilité :** Règles métier pures, indépendantes de toute infrastructure.

**Structure :**
```
Domain/Admin/
├── Entities/
│   ├── Project.php                 # Entité agrégat
│   ├── Enums/
│   │   └── ProjectStatus.php       # Draft, Published, Archived
│   └── ValueObjects/
│       ├── ProjectTitle.php
│       ├── ProjectSlug.php
│       ├── ProjectDescription.php
│       ├── ProjectShortDescription.php
│       ├── ClientName.php
│       └── ProjectDate.php
├── Repositories/
│   └── ProjectRepository.php       # Interface repository
└── Exceptions/
    ├── ProjectNotFoundException.php
    ├── DuplicateProjectSlugException.php
    ├── InvalidProjectTitleException.php
    ├── InvalidClientNameException.php
    ├── InvalidProjectDescriptionException.php
    └── InvalidProjectShortDescriptionException.php
```

**Règles :**
- ❌ **Aucune dépendance** vers Laravel ou infrastructure
- ✅ Contient la logique métier pure
- ✅ Les Value Objects sont `readonly` et validés
- ✅ Les exceptions sont spécifiques au domaine

### 2. Application Layer (`app/Application/Admin/`)

**Responsabilité :** Orchestration des cas d'usage, coordination entre domaine et infrastructure.

**Structure :**
```
Application/Admin/
├── Commands/
│   ├── CreateProject/
│   │   └── CreateProjectHandler.php
│   ├── UpdateProject/
│   │   └── UpdateProjectHandler.php
│   └── DeleteProject/
│       └── DeleteProjectHandler.php
├── Queries/
│   └── GetProjects/
│       └── GetProjectsHandler.php
└── DTOs/
    ├── CreateProjectData.php
    ├── UpdateProjectData.php
    └── ProjectData.php
```

**Règles :**
- ✅ Handlers sont `readonly` avec injection de dépendances
- ✅ Pas de logique métier (délégation au domaine)
- ✅ Conversion des DTOs en Value Objects
- ✅ Gestion des chaînes vides → `null` avant création des VOs

### 3. Infrastructure Layer (`app/Infra/Repositories/Admin/`)

**Responsabilité :** Implémentation concrète de la persistance.

**Structure :**
```
Infra/Repositories/Admin/
└── ProjectDatabaseRepository.php   # Implémente ProjectRepository
```

**Règles :**
- ✅ Implémente les interfaces du domaine
- ✅ Utilise Eloquent pour la persistance
- ✅ Convertit entre modèles Eloquent et entités domaine
- ✅ Lance des exceptions domaine si nécessaire

### 4. Presentation Layer (`app/Http/Controllers/Admin/`)

**Responsabilité :** Interface utilisateur, HTTP, formulaires.

**Structure :**
```
Http/Controllers/Admin/
└── ProjectController.php
```

**Règles :**
- ✅ Injection des handlers dans le constructeur
- ✅ Validation Laravel (Form Requests)
- ✅ Délégation aux handlers
- ❌ Aucune logique métier

---

## Règles métier critiques

### 🔐 Règle 1 : Slug Immutable

**Le slug d'un projet ne change JAMAIS après création.**

```php
// ✅ CORRECT
$project = Project::new(title: 'My Project');  // slug: "my-project"
$project->update(title: ProjectTitle::fromString('New Title'));
echo $project->getSlug();  // Toujours "my-project"

// ❌ INTERDIT - Impossible de modifier le slug
// Pas de méthode setSlug() ou updateSlug()
```

**Raison :** Stabilité des URLs, SEO, références externes.

---

### 🔐 Règle 2 : Gestion du Status

**Les changements de status se font via des commandes dédiées.**

```php
// ✅ CORRECT - Méthodes dédiées sur l'entité
$project->publish();   // Draft → Published
$project->archive();   // * → Archived
$project->draft();     // * → Draft

// ❌ INTERDIT - Pas via update()
// $project->update(..., status: $newStatus)  // N'existe pas
```

**Commandes dédiées (à implémenter) :**
- `PublishProjectHandler` - Publie un projet (Draft → Published)
- `ArchiveProjectHandler` - Archive un projet
- `DraftProjectHandler` - Repasse en brouillon

---

### 🔐 Règle 3 : Validation "Always Valid"

**Un Value Object ne peut JAMAIS exister dans un état invalide.**

```php
// ✅ CORRECT
$title = ProjectTitle::fromString('Valid Title');  // OK

// ❌ LANCE InvalidProjectTitleException
ProjectTitle::fromString('');         // Vide
ProjectTitle::fromString('   ');      // Espaces uniquement
ProjectTitle::fromString(str_repeat('a', 256));  // > 255 chars
```

**Implication :** La validation se fait **à la création** du VO, pas ailleurs.

---

### 🔐 Règle 4 : Gestion des Champs Optionnels

**Les champs optionnels vides sont convertis en `null`.**

```php
// ✅ CORRECT - Conversion dans les handlers
$description = $data->description !== null && trim($data->description) !== ''
    ? $data->description
    : null;

// Si $description est null → pas de VO créé
// Si $description a une valeur → VO créé avec validation
$project->update(
    description: $description !== null
        ? ProjectDescription::fromString($description)
        : null
);
```

**Logique :**
- Pas de données → `null` (pas de VO)
- Données présentes → VO créé + validation stricte
- Formulaires envoient `""` → converti en `null` par handlers

---

### 🔐 Règle 5 : Factory Methods

**Deux méthodes pour créer un Project :**

```php
// 1. Création d'un nouveau projet (génère slug, status Draft)
Project::new(
    title: 'My Project',
    description: 'Description',
    // ...
);

// 2. Reconstitution depuis DB (slug + status fournis)
Project::reconstitute(
    title: $title,
    slug: $slug,
    status: $status,
    // ...
);
```

**❌ INTERDIT :** Utiliser `new()` pour reconstitution ou `reconstitute()` pour création.

---

## Value Objects

### Vue d'ensemble

| Value Object | Limite | Vide autorisé | Spécificités |
|--------------|--------|---------------|--------------|
| `ProjectTitle` | 255 chars | ❌ Non | Trim auto |
| `ClientName` | 255 chars | ❌ Non | Title Case, normalisation espaces |
| `ProjectDescription` | ∞ Illimitée | ❌ Non | Markdown, multiligne |
| `ProjectShortDescription` | 500 chars | ❌ Non | Résumé court |
| `ProjectSlug` | - | ❌ Non | Format strict (lowercase, hyphens) |
| `ProjectDate` | - | - | Carbon wrapper |
| `ProjectStatus` | - | - | Enum (Draft, Published, Archived) |

### Validation détaillée

#### ProjectTitle

```php
ProjectTitle::fromString('My Project');  // ✅ OK

// ❌ Exceptions
ProjectTitle::fromString('');                    // InvalidProjectTitleException
ProjectTitle::fromString('   ');                 // InvalidProjectTitleException
ProjectTitle::fromString(str_repeat('a', 256));  // InvalidProjectTitleException (> 255)
```

#### ClientName

```php
ClientName::fromString('acme corporation');  // ✅ "Acme Corporation" (Title Case)

// Normalisation avancée
ClientName::fromString('société d\'étude');  // ✅ "Société D'Étude"
ClientName::fromString('ACME   INC');        // ✅ "Acme Inc" (espaces normalisés)

// ❌ Exceptions
ClientName::fromString('');                  // InvalidClientNameException
ClientName::fromString(str_repeat('a', 256)); // InvalidClientNameException (> 255)
```

#### ProjectDescription

```php
$markdown = "# Title\n\n**Bold** text";
ProjectDescription::fromString($markdown);  // ✅ Préserve markdown

// ❌ Exception
ProjectDescription::fromString('');         // InvalidProjectDescriptionException
ProjectDescription::fromString('   ');      // InvalidProjectDescriptionException
```

**⚠️ Pas de limite de longueur** - Descriptions peuvent être très longues.

#### ProjectShortDescription

```php
ProjectShortDescription::fromString('Court résumé');  // ✅ OK
ProjectShortDescription::fromString(str_repeat('a', 500));  // ✅ Limite exacte OK

// ❌ Exceptions
ProjectShortDescription::fromString('');                    // Exception
ProjectShortDescription::fromString(str_repeat('a', 501));  // Exception (> 500)
```

#### ProjectSlug

```php
// ✅ Valides
ProjectSlug::fromString('my-project');       // OK
ProjectSlug::fromString('project-2024-v2');  // OK avec chiffres

// Génération depuis titre
ProjectSlug::fromTitle(ProjectTitle::fromString('Mon Projet Été 2024 !'));
// → "mon-projet-ete-2024"

// ❌ Invalides
ProjectSlug::fromString('My-Project');    // Majuscules interdites
ProjectSlug::fromString('my project');    // Espaces interdits
ProjectSlug::fromString('my_project');    // Underscores interdits
ProjectSlug::fromString('-my-project');   // Début par tiret interdit
ProjectSlug::fromString('my-project-');   // Fin par tiret interdit
```

---

## Commandes et Queries

### Commandes (Write Side)

#### 1. CreateProject

**Handler :** `CreateProjectHandler`

**Responsabilités :**
1. Crée l'entité `Project` via `Project::new()`
2. Vérifie l'unicité du slug via `findBySlug()`
3. Lance `DuplicateProjectSlugException` si slug existe
4. Persiste via `repository->store()`
5. Retourne le `ProjectSlug`

**Flux :**
```
Controller → CreateProjectData (DTO)
    → CreateProjectHandler
        → Project::new() (génère slug)
        → repository->findBySlug() (vérification)
        → repository->store()
    → ProjectSlug (retour)
```

**Tests :** `CreateProjectHandlerTest.php` (5 tests)

---

#### 2. UpdateProject

**Handler :** `UpdateProjectHandler`

**Responsabilités :**
1. Récupère le projet via `getBySlug()` (lance exception si absent)
2. Convertit les chaînes vides en `null`
3. Appelle `$project->update()` (slug et status préservés)
4. Persiste via `repository->store()`

**Flux :**
```
Controller → UpdateProjectData (DTO)
    → UpdateProjectHandler
        → repository->getBySlug() (récupération)
        → $project->update() (mise à jour)
        → repository->store()
```

**⚠️ Comportement :**
- **Slug** reste inchangé
- **Status** reste inchangé
- Champs optionnels peuvent être réinitialisés à `null`

**Tests :** `UpdateProjectHandlerTest.php` (7 tests)

---

#### 3. DeleteProject

**Handler :** `DeleteProjectHandler`

**Responsabilités :**
1. Supprime via `repository->delete(slug)`
2. Lance `ProjectNotFoundException` si projet inexistant

**Flux :**
```
Controller → DeleteProjectHandler
    → repository->delete(slug)
        → Lance exception si 0 suppression
```

**⚠️ Actuellement : Hard delete** (suppression définitive)

**Tests :** `DeleteProjectHandlerTest.php` (3 tests)

---

### Queries (Read Side)

#### GetProjects

**Handler :** `GetProjectsHandler`

**Responsabilités :**
1. Récupère tous les projets via `repository->getAll()`
2. Transforme en `ProjectData` (DTOs)
3. Retourne une `Collection`

**Flux :**
```
Controller → GetProjectsHandler
    → repository->getAll()
        → Collection<Project>
    → map to ProjectData
        → Collection<ProjectData>
```

**Tests :** `GetProjectsHandlerTest.php` (3 tests)

---

## Flow de données

### Exemple : Création d'un projet

```
1. User soumet formulaire
   ↓
2. ProjectController::store(CreateProjectRequest $request)
   ↓
3. CreateProjectData::fromArray($request->validated())
   ↓
4. CreateProjectHandler::handle(CreateProjectData $data)
   ↓
5. Project::new(title: $data->title, ...)
   │  • Crée ProjectTitle::fromString($data->title)  → Validation
   │  • Génère ProjectSlug::fromTitle($title)
   │  • Status = ProjectStatus::Draft
   │  • Crée autres Value Objects avec validation
   ↓
6. repository->findBySlug($slug)  → Vérification unicité
   │  • Si existe → DuplicateProjectSlugException
   ↓
7. repository->store($project)
   │  • Convertit Project → Eloquent Model
   │  • ProjectDatabase::updateOrCreate(...)
   ↓
8. Retour ProjectSlug
   ↓
9. Redirect vers admin.project.index
```

### Exemple : Mise à jour d'un projet

```
1. User modifie formulaire (change title, vide description)
   ↓
2. ProjectController::update(Project $project, UpdateProjectRequest $request)
   ↓
3. UpdateProjectData avec slug du projet
   ↓
4. UpdateProjectHandler::handle(UpdateProjectData $data)
   ↓
5. repository->getBySlug($slug)
   │  • Si absent → ProjectNotFoundException
   ↓
6. Conversion chaînes vides → null
   │  $description = trim($data->description) !== '' ? $data->description : null
   ↓
7. $project->update(
   │     title: ProjectTitle::fromString($data->title),
   │     description: $description !== null
   │         ? ProjectDescription::fromString($description)
   │         : null
   │  )
   │  • Slug et status préservés
   ↓
8. repository->store($project)
   ↓
9. Redirect
```

---

## Gestion des erreurs

### Exceptions domaine

| Exception | Quand ? | HTTP Code suggéré |
|-----------|---------|-------------------|
| `ProjectNotFoundException` | Projet introuvable par slug | 404 |
| `DuplicateProjectSlugException` | Slug déjà existant à la création | 409 Conflict |
| `InvalidProjectTitleException` | Titre vide ou > 255 chars | 422 Unprocessable |
| `InvalidClientNameException` | Nom client vide ou > 255 chars | 422 Unprocessable |
| `InvalidProjectDescriptionException` | Description vide | 422 Unprocessable |
| `InvalidProjectShortDescriptionException` | Description courte vide ou > 500 chars | 422 Unprocessable |

### Stratégie de gestion

**Au niveau Application (Handlers) :**
```php
// Les handlers laissent remonter les exceptions domaine
public function handle(CreateProjectData $data): ProjectSlug
{
    $project = Project::new(...);  // Peut lancer Invalid*Exception

    $existing = $this->repository->findBySlug($project->getSlug());
    if ($existing !== null) {
        throw DuplicateProjectSlugException::forSlug($project->getSlug());
    }

    $this->repository->store($project);
    return $project->getSlug();
}
```

**Au niveau Presentation (Controllers) :**
```php
// Option 1 : Try/catch dans controller
try {
    $slug = $this->createProjectHandler->handle($data);
    return redirect()->route('admin.project.show', $slug);
} catch (DuplicateProjectSlugException $e) {
    return back()->withErrors(['title' => 'Un projet avec ce titre existe déjà']);
} catch (InvalidProjectTitleException $e) {
    return back()->withErrors(['title' => $e->getMessage()]);
}

// Option 2 : Global exception handler (recommandé)
// app/Exceptions/Handler.php
```

---

## Points d'attention

### ❌ Pièges à éviter

1. **Ne jamais utiliser `Project::new()` pour reconstitution**
   ```php
   // ❌ INTERDIT
   $project = Project::new(title: $dbRow['title'], ...);

   // ✅ CORRECT
   $project = Project::reconstitute(
       title: ProjectTitle::fromString($dbRow['title']),
       slug: ProjectSlug::fromString($dbRow['slug']),
       status: ProjectStatus::from($dbRow['status']),
       // ...
   );
   ```

2. **Ne jamais créer un VO avec une valeur vide**
   ```php
   // ❌ INTERDIT - Lance exception
   $description = ProjectDescription::fromString('');

   // ✅ CORRECT - Utiliser null
   $description = null;
   ```

3. **Ne jamais bypasser les handlers**
   ```php
   // ❌ INTERDIT - Accès direct au repository
   $repository->store($project);

   // ✅ CORRECT - Toujours via handler
   $handler->handle($data);
   ```

4. **Ne jamais valider dans les handlers**
   ```php
   // ❌ INTERDIT - Validation dans handler
   if (strlen($data->title) > 255) {
       throw new \Exception('...');
   }

   // ✅ CORRECT - Validation dans VO
   ProjectTitle::fromString($data->title);  // Lance exception si invalide
   ```

5. **Toujours convertir chaînes vides → null**
   ```php
   // ✅ CORRECT - Dans les handlers
   $clientName = $data->clientName !== null && trim($data->clientName) !== ''
       ? $data->clientName
       : null;
   ```

### ✅ Bonnes pratiques

1. **Typage strict partout**
   ```php
   declare(strict_types=1);
   ```

2. **Handlers readonly avec injection**
   ```php
   final readonly class CreateProjectHandler
   {
       public function __construct(
           private ProjectRepository $repository,
       ) {}
   }
   ```

3. **Value Objects readonly**
   ```php
   final readonly class ProjectTitle
   {
       private function __construct(
           private string $title,
       ) {}
   }
   ```

4. **Tests unitaires pour tous les handlers**
   - Chemins heureux (happy path)
   - Cas d'erreur (exceptions)
   - Cas limites (null, vide, etc.)

---

## Roadmap

### 🔴 Priorité Haute - Fonctionnalités manquantes

#### 1. Commandes de gestion du status

**À implémenter :**
- [ ] `PublishProjectHandler` - Publie un projet (Draft → Published)
- [ ] `ArchiveProjectHandler` - Archive un projet (→ Archived)
- [ ] `DraftProjectHandler` - Repasse en brouillon (→ Draft)

**Structure :**
```
Application/Admin/Commands/
├── PublishProject/
│   └── PublishProjectHandler.php
├── ArchiveProject/
│   └── ArchiveProjectHandler.php
└── DraftProject/
    └── DraftProjectHandler.php
```

**Règles métier :**
- Vérifier les transitions valides (ex: on ne peut pas publier un projet archivé ?)
- Events domaine (ProjectPublished, ProjectArchived, etc.)
- Vérifications supplémentaires (projet complet avant publication ?)

---

#### 2. Soft Deletes

**Actuellement :** Hard delete (suppression définitive)

**À considérer :**
- Soft delete avec `deleted_at`
- Restauration de projets supprimés
- Purge définitive après X jours

---

#### 3. Events Domaine

**À implémenter :**
```
Domain/Admin/Events/
├── ProjectCreated.php
├── ProjectUpdated.php
├── ProjectDeleted.php
├── ProjectPublished.php
└── ProjectArchived.php
```

**Cas d'usage :**
- Notifications
- Audit log
- Synchronisation avec services externes
- Cache invalidation

---

### 🟡 Priorité Moyenne

#### 4. Query enrichies

**À considérer :**
- `GetProjectBySlugHandler` - Récupère un projet par slug
- `GetPublishedProjectsHandler` - Liste projets publiés (pour portfolio public)
- `SearchProjectsHandler` - Recherche par titre/description
- Filtres par status, date, client

---

#### 5. Validation métier avancée

**À considérer :**
- Validation cohérence globale (ex: projet avec média obligatoire avant publication)
- Règles de nommage des slugs (réservation de slugs ?)
- Limite nombre de projets par utilisateur

---

### 🟢 Priorité Basse

#### 6. Optimisations

- Cache des projets publiés
- Eager loading automatique des relations
- Pagination des listes

---

## Références

### Documentation externe
- [Domain-Driven Design par Eric Evans](https://www.domainlanguage.com/ddd/)
- [CQRS pattern](https://martinfowler.com/bliki/CQRS.html)
- [Laravel Documentation](https://laravel.com/docs/12.x)

### Fichiers clés du projet
- `app/Domain/Admin/Entities/Project.php` - Entité agrégat
- `app/Domain/Admin/Repositories/ProjectRepository.php` - Interface repository
- `app/Infra/Repositories/Admin/ProjectDatabaseRepository.php` - Implémentation
- `app/Application/Admin/Commands/` - Handlers des commandes
- `tests/Unit/Application/Admin/Commands/` - Tests des handlers

---

**Questions ou suggestions ?**
Contactez l'équipe de développement ou ouvrez une issue sur le repository.