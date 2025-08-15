# 🧪 Test-Driven Development (TDD) Guidelines

## Philosophie TDD

Ce projet suit une approche **Test-Driven Development** stricte. Aucune fonctionnalité ne doit être développée sans tests écrits au préalable.

## 🔄 Le Cycle TDD

### 1. RED ❌ - Écrire un test qui échoue
```bash
make test-watch  # Lance les tests en mode watch
```
Écrivez d'abord un test qui décrit le comportement souhaité. Le test doit échouer.

### 2. GREEN ✅ - Faire passer le test
Écrivez le code minimal nécessaire pour faire passer le test. Pas plus.

### 3. REFACTOR 🔧 - Améliorer le code
Une fois le test vert, refactorisez le code tout en gardant les tests verts.

## 📏 Règles TDD Obligatoires

### Règle 1: Test First
- **JAMAIS** de code de production sans test écrit d'abord
- Un test = Une fonctionnalité
- Si vous ne savez pas quoi tester, vous ne savez pas quoi coder

### Règle 2: Un Test à la Fois
- N'écrivez qu'un seul test à la fois
- Ne passez au test suivant que quand le précédent est vert
- Gardez les tests petits et focalisés

### Règle 3: Code Minimal
- Écrivez le code le plus simple qui fait passer le test
- Pas d'over-engineering
- YAGNI (You Aren't Gonna Need It)

### Règle 4: Refactoring Continu
- Refactorisez après chaque test vert
- Les tests doivent rester verts pendant le refactoring
- Si un test casse pendant le refactoring, revenez en arrière

## 🎯 Structure des Tests

### Tests Unitaires
```php
// tests/Unit/Models/PageContentTest.php
test('page content can be retrieved by key', function () {
    // Arrange
    $content = PageContent::factory()->create([
        'key' => 'test_key',
        'content' => 'Test content'
    ]);
    
    // Act
    $result = PageContent::getContent('test_key');
    
    // Assert
    expect($result)->toBe('Test content');
});
```

### Tests de Feature
```php
// tests/Feature/ContactFormTest.php
test('contact form sends email when submitted', function () {
    // Arrange
    Mail::fake();
    
    // Act
    $response = $this->post('/contact', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Test message'
    ]);
    
    // Assert
    $response->assertRedirect('/');
    Mail::assertSent(ContactMail::class);
});
```

### Tests de Composants Livewire
```php
// tests/Feature/Livewire/ContactSectionTest.php
test('contact section displays content from database', function () {
    // Arrange
    PageContent::factory()->create([
        'key' => 'contact_text',
        'content' => 'Contact us here'
    ]);
    
    // Act & Assert
    Livewire::test(ContactSection::class)
        ->assertSee('Contact us here');
});
```

## 📊 Métriques de Qualité

### Coverage Minimum
- **Global**: 80%
- **Nouveaux fichiers**: 100%
- **Modèles**: 100%
- **Composants Livewire**: 90%
- **Controllers**: 85%

### Vérifier la couverture
```bash
make test-coverage
```

### Mutation Testing (optionnel)
```bash
make test-mutate
```

## 🚀 Commandes TDD

### Développement
```bash
# Mode TDD interactif (recommandé)
make tdd

# Tests en mode watch
make test-watch

# Run tests for a specific file
make test-file path=tests/Feature/ContactFormTest.php

# Create a new test
make test-create name=Feature/ProductTest
```

### Validation
```bash
# Run all tests
make test

# Run with coverage
make test-coverage

# Run only unit tests
make test-unit

# Run only feature tests
make test-feature
```

## 📝 Conventions de Nommage des Tests

### Structure
```
tests/
├── Unit/
│   ├── Models/          # Tests des modèles
│   ├── Services/         # Tests des services
│   └── Helpers/          # Tests des helpers
├── Feature/
│   ├── Auth/            # Tests d'authentification
│   ├── Api/             # Tests API
│   ├── Livewire/        # Tests des composants Livewire
│   └── Pages/           # Tests des pages
└── Browser/             # Tests Dusk (si nécessaire)
```

### Nommage
- Utilisez des descriptions claires et en anglais
- Commencez par un verbe d'action
- Soyez spécifique sur ce qui est testé

```php
// ✅ BON
test('user can update their profile name', function () {});
test('contact form validates email format', function () {});
test('product price cannot be negative', function () {});

// ❌ MAUVAIS
test('test profile', function () {});
test('it works', function () {});
test('validation', function () {});
```

## 🔴 Anti-Patterns à Éviter

### 1. Tests After
```php
// ❌ MAUVAIS - Code écrit avant le test
public function processPayment($amount) {
    // Code complexe écrit sans test
}

// Test écrit après coup
test('payment is processed', function () {
    // Test qui valide du code existant
});
```

### 2. Tests Trop Larges
```php
// ❌ MAUVAIS - Test qui teste trop de choses
test('entire checkout process works', function () {
    // Teste le panier, le paiement, l'email, etc.
});

// ✅ BON - Tests focalisés
test('cart calculates total correctly', function () {});
test('payment is processed successfully', function () {});
test('order confirmation email is sent', function () {});
```

### 3. Tests Dépendants
```php
// ❌ MAUVAIS - Tests qui dépendent d'autres tests
test('create user', function () {
    $this->user = User::create([...]);
});

test('update user', function () {
    // Utilise $this->user du test précédent
});

// ✅ BON - Tests indépendants
test('user can be updated', function () {
    $user = User::factory()->create();
    // Test update
});
```

## 🎓 Workflow TDD Exemple

### Exemple: Ajouter une fonctionnalité de newsletter

#### 1. RED - Écrire le test
```php
// tests/Feature/NewsletterTest.php
test('user can subscribe to newsletter', function () {
    $response = $this->post('/newsletter/subscribe', [
        'email' => 'user@example.com'
    ]);
    
    $response->assertRedirect('/');
    $response->assertSessionHas('success', 'Successfully subscribed!');
    
    $this->assertDatabaseHas('newsletter_subscribers', [
        'email' => 'user@example.com'
    ]);
});
```

#### 2. GREEN - Implémenter
```php
// app/Http/Controllers/NewsletterController.php
public function subscribe(Request $request)
{
    $request->validate(['email' => 'required|email']);
    
    NewsletterSubscriber::create([
        'email' => $request->email
    ]);
    
    return redirect('/')->with('success', 'Successfully subscribed!');
}
```

#### 3. REFACTOR - Améliorer
```php
// app/Services/NewsletterService.php
class NewsletterService
{
    public function subscribe(string $email): void
    {
        NewsletterSubscriber::firstOrCreate(['email' => $email]);
        event(new UserSubscribedToNewsletter($email));
    }
}
```

## 📚 Ressources

- [Pest PHP Documentation](https://pestphp.com/)
- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [TDD by Example - Kent Beck](https://www.amazon.com/Test-Driven-Development-Kent-Beck/dp/0321146530)

## ⚖️ Enforcement

- **Pre-commit hooks** vérifient la couverture de tests
- **CI/CD** bloque les PRs sans tests
- **Code reviews** doivent valider l'approche TDD
- Les commits doivent montrer le cycle RED-GREEN-REFACTOR

---

> "Test-driven development is not about testing, it's about design and specification." - Kent Beck