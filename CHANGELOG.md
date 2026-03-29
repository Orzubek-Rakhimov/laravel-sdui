# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-03-29

### Added
- Initial release of Laravel SDUI library
- Core `Screen` class for building declarative UI screens
- Component system with 11 built-in components:
  - Text component with variants and styling
  - Button component with variants and states
  - Image component with sizing and rounded corners
  - Stack component for vertical/horizontal layouts
  - Card component for contained content
  - Badge component for labels
  - Avatar component for profile pictures
  - Spacer component for layout spacing
  - Divider component for visual separators
  - ListItem component for list entries
  - StatGrid component for statistics display
- Action system supporting:
  - Navigation to routes with parameters
  - URL opening (in-app and external)
  - Event emission with custom data
  - Custom actions
  - Refresh page actions
- SDUIManager facade for screen creation and component serialization
- Service Provider for Laravel auto-discovery
- Artisan commands:
  - `sdui:make-component` for creating custom components
  - `sdui:preview` for previewing screens
- Comprehensive test suite with 76+ tests
- Type-safe implementation with PHP 8.3+ strict types
- Full support for Laravel 12 and 13

### Documentation
- Comprehensive README with installation and usage examples
- MIT License
- Changelog file
- Test documentation in tests/README.md

---

## How to Use This Changelog

- **Major version bumps** (1.0.0 → 2.0.0): Breaking changes
- **Minor version bumps** (1.0.0 → 1.1.0): New features (backward compatible)
- **Patch version bumps** (1.0.0 → 1.0.1): Bug fixes and small improvements

## Automatic Versioning Setup

This project uses **conventional commits** for automatic versioning and changelog generation.

### Commit Message Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types**: `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `chore`

**Examples**:

```bash
# New feature (minor bump)
git commit -m "feat(components): add new TextInput component"

# Bug fix (patch bump)
git commit -m "fix(screen): prevent duplicate children rendering"

# Breaking change (major bump)
git commit -m "feat!: redesign component API

BREAKING CHANGE: Component constructors now require explicit make() calls"
```

### Automatic Release Workflow

The repository should include GitHub Actions to:
1. Detect conventional commits
2. Bump version in `composer.json`
3. Update `CHANGELOG.md` automatically
4. Create Git tag and release

### Manual Versioning (Without CI/CD)

To update version and changelog manually, use standard-version:

```bash
# Install globally (one time)
npm install -g standard-version

# Bump patch version and update changelog
standard-version --patch

# Bump minor version
standard-version --minor

# Bump major version
standard-version --major

# Push tags and commits
git push --follow-tags origin main
```

Or manually:
1. Update `version` in relevant files
2. Add entry to CHANGELOG.md
3. Commit and tag: `git tag v1.0.1 && git push --tags`
