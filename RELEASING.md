# Release Process

This document outlines the release process for Livewire Calendar maintainers.

## Semantic Versioning

We follow [Semantic Versioning](https://semver.org/) (SemVer):

**Version Format**: `MAJOR.MINOR.PATCH`

- **MAJOR**: Breaking changes to the public API
- **MINOR**: New features, new framework version support (backward compatible)
- **PATCH**: Bug fixes, documentation updates (backward compatible)

### Examples

- `4.1.0` → `4.1.1`: Bug fix (PATCH)
- `4.1.1` → `4.2.0`: New Laravel version support (MINOR)
- `4.2.0` → `5.0.0`: Breaking API changes (MAJOR)

## Branch Strategy

- **`main`**: Production-ready releases (protected)
- **`dev`**: Active development (default branch for PRs)
- **`X.x`**: Long-term support branches (created when next major version starts)

### Workflow

1. Feature branches → `dev` (via Pull Request)
2. `dev` → `main` (via Pull Request, triggers release)
3. Tag releases on `main` branch

## Release Checklist

### Pre-Release

- [ ] All tests passing on `dev` branch
- [ ] All PRs merged and reviewed
- [ ] CHANGELOG.md updated with version and date
- [ ] README.md updated if needed
- [ ] Version bumped in any version files (if applicable)
- [ ] Documentation reviewed and updated

### Release Steps

#### 1. Update CHANGELOG

Edit `CHANGELOG.md`:

```markdown
## [Unreleased]

## [X.Y.Z] - YYYY-MM-DD

### Added
- New feature description

### Fixed
- Bug fix description

### Changed
- Changes to existing functionality
```

Commit the CHANGELOG:
```bash
git add CHANGELOG.md
git commit -m "Update CHANGELOG for vX.Y.Z"
```

#### 2. Merge dev to main

Create a Pull Request from `dev` to `main`:

```bash
gh pr create --base main --head dev --title "Release vX.Y.Z" --body "Release version X.Y.Z

## Changes
[Summary of major changes]

## Checklist
- [x] CHANGELOG updated
- [x] Tests passing
- [x] Documentation updated
"
```

Review and merge the PR.

#### 3. Create Git Tag

After merging to `main`, create and push the tag:

```bash
git checkout main
git pull origin main
git tag -a X.Y.Z -m "Release vX.Y.Z"
git push origin X.Y.Z
```

**Note**: Do NOT include a "v" prefix in the tag name. Use `4.1.0`, not `v4.1.0`.

#### 4. Create GitHub Release

Create a GitHub release from the tag:

```bash
gh release create X.Y.Z \
  --title "vX.Y.Z - Brief Description" \
  --notes "$(cat <<'EOF'
## What's Changed

### Added
- Feature 1
- Feature 2

### Fixed
- Bug 1
- Bug 2

### Compatibility
- **PHP**: 7.4 - 8.4
- **Laravel**: 6 - 12
- **Livewire**: 2, 3, 4

**Full Changelog**: https://github.com/omnia-digital/livewire-calendar/compare/PREV_VERSION...X.Y.Z
EOF
)"
```

Or use GitHub's UI to create the release and use the "Auto-generate release notes" feature as a starting point.

#### 5. Verify Packagist

Within 5-10 minutes, verify the new version appears on Packagist:
- Visit https://packagist.org/packages/omnia-digital/livewire-calendar
- Confirm version X.Y.Z is listed
- Check that the package description is accurate

If it doesn't appear:
- Ensure the tag was pushed correctly: `git ls-remote --tags origin`
- Check Packagist webhook settings in GitHub repo settings
- Manually trigger update on Packagist if needed

### Post-Release

- [ ] Verify installation works: `composer require omnia-digital/livewire-calendar:^X.Y`
- [ ] Announce release on relevant channels (if major version)
- [ ] Close any related GitHub issues or milestones
- [ ] Update any project boards or tracking systems

## Release Types

### Patch Release (X.Y.Z → X.Y.Z+1)

**When**: Bug fixes, documentation updates, minor improvements

**Frequency**: As needed when critical bugs are found

**Example**: `4.1.0` → `4.1.1`

**Process**: Follow standard release checklist above

### Minor Release (X.Y.Z → X.Y+1.0)

**When**:
- New framework version support (Laravel, Livewire, PHP)
- New features (backward compatible)
- Deprecations (with migration path)

**Frequency**: Quarterly or when new framework versions are released

**Example**: `4.1.0` → `4.2.0`

**Process**:
1. Follow standard release checklist
2. Update compatibility matrix in README.md
3. Consider blog post or announcement for significant features

### Major Release (X.Y.Z → X+1.0.0)

**When**: Breaking changes to public API

**Frequency**: Rarely (only when absolutely necessary)

**Example**: `4.2.0` → `5.0.0`

**Process**:
1. Create LTS branch for previous major version:
   ```bash
   git checkout main
   git checkout -b 4.x
   git push origin 4.x
   ```

2. Update UPGRADE.md with detailed migration guide

3. Follow standard release checklist

4. Announce breaking changes prominently in release notes

5. Provide 12 months of bug fix support for previous major version

## Testing Before Release

Always test the package with different framework versions before releasing:

### Test Matrix

Create test environments for:

| Test Case | PHP | Laravel | Livewire |
|-----------|-----|---------|----------|
| Minimum   | 7.4 | 6.0     | 2.0      |
| LTS       | 8.2 | 10.0    | 3.0      |
| Latest    | 8.4 | 12.0    | 4.0      |

### Running Tests

```bash
composer test
```

Ensure all tests pass before releasing.

## Version Support Policy

### Current Major Version (e.g., 4.x)
- **Full support**: New features, bug fixes, security patches
- **Duration**: Until next major version is released

### Previous Major Version (e.g., 3.x)
- **Bug fixes**: Critical bugs and security issues only
- **Duration**: 12 months after next major version release
- **Branch**: Maintained on `X.x` branch (e.g., `3.x`)

### Older Versions (e.g., 2.x, 1.x)
- **No support**: Only security patches on request
- **Recommendation**: Upgrade to current version

## Emergency Hotfix Process

For critical security issues or breaking bugs:

1. Create hotfix branch from `main`:
   ```bash
   git checkout main
   git checkout -b hotfix/X.Y.Z+1
   ```

2. Fix the issue with tests

3. Update CHANGELOG with urgent notice:
   ```markdown
   ## [X.Y.Z+1] - YYYY-MM-DD

   ### Security
   - Fixed critical security vulnerability (CVE-XXXX-XXXXX)
   ```

4. Create PR to `main` with "HOTFIX" label

5. Fast-track review and merge

6. Create tag and release immediately

7. Notify users via GitHub Security Advisory if security-related

## Automation Opportunities

Future automation to consider:

- [ ] GitHub Actions workflow to auto-create draft release on tag push
- [ ] Automated CHANGELOG generation from commit messages
- [ ] Version bump automation script
- [ ] Multi-version testing matrix in CI/CD
- [ ] Automatic Packagist webhook verification

## Communication

### Release Announcements

For minor and major releases:

1. GitHub Release with detailed notes
2. Update package description on Packagist (if needed)
3. Consider:
   - GitHub Discussions announcement
   - Twitter/social media (for major releases)
   - Laravel News submission (for significant features)

### Security Advisories

For security releases:

1. Create GitHub Security Advisory
2. Assign CVE if applicable
3. Notify users through appropriate channels
4. Provide clear upgrade instructions

## Rollback Procedure

If a release has critical issues:

1. **Do NOT delete the tag** (Packagist and users may have already pulled it)

2. Create a new patch release fixing the issue:
   ```bash
   # If 4.1.0 has issues, create 4.1.1
   git revert <problematic-commit>
   # Follow normal release process for 4.1.1
   ```

3. Add notice to GitHub release:
   ```markdown
   ## ⚠️ Known Issues

   This release has been superseded by vX.Y.Z+1 due to [issue description].
   Please upgrade to vX.Y.Z+1 immediately.
   ```

4. Announce the issue and fixed version

## Questions?

If you're unsure about any part of the release process:

1. Review previous releases for examples
2. Check git history: `git log --oneline --graph main`
3. Consult with other maintainers
4. When in doubt, create a draft release for review first

## Useful Commands

```bash
# View all tags
git tag -l

# View tag details
git show X.Y.Z

# Compare versions
git diff 3.2.0..4.1.0

# View commits between versions
git log 3.2.0..4.1.0 --oneline

# Check current branch
git branch --show-current

# Verify tag was pushed
git ls-remote --tags origin

# List releases
gh release list
```
