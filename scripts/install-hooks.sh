#!/bin/bash

# Install Git Hooks for TDD

echo "🔗 Installing Git hooks for TDD..."

# Create .git/hooks directory if it doesn't exist
mkdir -p .git/hooks

# Copy pre-commit hook
cp .githooks/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit

echo "✅ Pre-commit hook installed successfully!"
echo ""
echo "The hook will:"
echo "  - Run code formatting (Laravel Pint)"
echo "  - Execute all tests"
echo "  - Check test coverage (minimum 80%)"
echo "  - Block commits if tests fail or coverage is too low"
echo ""
echo "To skip the hook temporarily (not recommended):"
echo "  git commit --no-verify -m 'your message'"
echo ""
echo "To uninstall hooks:"
echo "  rm .git/hooks/pre-commit"