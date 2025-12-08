# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added - 2025-12-08

#### Localization Support
- **English translations** for the entire application (4 translation files)
  - `lang/en/admin.php` - Complete admin interface translations (398 lines)
  - `lang/en/portfolio.php` - Portfolio public interface (85 lines)
  - `lang/en/ui.php` - Common UI elements and messages (213 lines)
  - `lang/en/domain.php` - Domain validation messages (53 lines)
- Full EN/FR localization support with proper fallback
- French remains the default language (primary audience)
- Comprehensive translation key organization
- Documentation in README.md on how to use translations

#### Domain Exception Architecture Improvements
- **New exception**: `ProjectMissingRequiredDataException`
  - Handles the specific case when a project cannot be published due to missing description
  - Single responsibility - only manages missing data scenarios
- **Refactored**: `ProjectCannotBePublishedException`
  - Now only handles invalid status scenarios (e.g., trying to publish an archived project)
  - Removed nullable properties for better type safety
  - Removed semantic null usage (DDD best practice)
  - Cleaner, more explicit exception handling

### Changed - 2025-12-08

#### Domain Layer
- `Project::publish()` now throws two distinct exceptions instead of one:
  - `ProjectCannotBePublishedException::invalidStatus()` - for status violations
  - `ProjectMissingRequiredDataException::missingDescription()` - for missing data
- Exception messages consistently use "Technical:" prefix for debugging
- Improved type safety by removing nullable `$status` property from `ProjectCannotBePublishedException`

#### Application Layer
- Updated `ProjectFormEdit` component to handle separated exceptions
- Improved error message handling with specific catch blocks
- Removed defensive null-safe operators (not needed with non-nullable types)

#### Tests
- Updated test `cannot publish a project without description` to expect `ProjectMissingRequiredDataException`
- All 589 tests passing with improved exception architecture
- Added test coverage for separated exception scenarios

#### Documentation
- Added comprehensive **Localization** section to README.md
- Documented translation file structure and organization
- Added examples of translation usage in code
- Documented Domain Exception Architecture with code examples
- Explained the rationale behind separated exceptions (DDD principles)
- Updated project structure to show `lang/` directory
- Updated test coverage documentation (589+ passing tests)
- Updated Table of Contents with new Localization section

### Why These Changes?

**Localization:**
- Provides full support for both French and English audiences
- Makes the application more accessible and professional
- Follows Laravel best practices for internationalization

**Exception Architecture:**
- **Single Responsibility Principle**: Each exception represents one specific domain rule violation
- **Type Safety**: Eliminated nullable properties that could lead to runtime errors
- **Clear Intent**: Exception names clearly describe the exact problem
- **Better Error Handling**: Can catch and handle specific scenarios differently
- **DDD Compliance**: Each exception is a proper domain concept with clear boundaries

### Technical Details

**Affected Files:**
- Created:
  - `lang/en/admin.php`
  - `lang/en/portfolio.php`
  - `lang/en/ui.php`
  - `lang/en/domain.php`
  - `app/Domain/Admin/Exceptions/ProjectMissingRequiredDataException.php`
  - `CHANGELOG.md` (this file)

- Modified:
  - `app/Domain/Admin/Exceptions/ProjectCannotBePublishedException.php`
  - `app/Domain/Admin/Entities/Project.php`
  - `app/Livewire/Admin/ProjectFormEdit.php`
  - `tests/Unit/Domain/Admin/Entities/projectTest.php`
  - `README.md`

**Test Results:**
- All 589 tests passing ✅
- Code formatted with Laravel Pint ✅
- PHPStan level 9 compliant ✅

---

## [Previous Versions]

_For previous changes, see git commit history._
