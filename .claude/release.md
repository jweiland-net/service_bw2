# Release Preparation

## Pre-release checklist

Run the full QA suite in order before cutting a release:

```bash
CI=true ./Build/Scripts/runTests.sh -s lint
CI=true ./Build/Scripts/runTests.sh -s rector
CI=true ./Build/Scripts/runTests.sh -s cgl
CI=true ./Build/Scripts/runTests.sh -s functional
```

## Version bump — three files must be in sync

### 1. `ext_emconf.php`

```php
'version' => 'X.Y.Z',
```

### 2. `Documentation/guides.xml`

```xml
<project ... version="X.Y.Z" .../>
```

### 3. `Documentation/ChangeLog/Index.rst`

Prepend a new section at the top (after the `=========` heading underline):

```rst
Version X.Y.Z
=============

*   [BUGFIX] Short description of fix one
*   [BUGFIX] Short description of fix two
```

The `=` underline must be exactly as long as the heading text.

## What belongs in the changelog

Only user-facing fixes and features. Internal tooling commits (Rector,
`.claude/`, `.gitattributes`, CI config) are `export-ignore` and must
**not** appear in the changelog.

## Commit

```
[TASK] Release version X.Y.Z
```

No body needed for a plain version bump.

## Branch and push

**Pushing to `main` is blocked.** Always push releases on a dedicated branch:

```bash
git switch -c bugfix/release-X.Y.Z   # or feature/release-X.Y.Z for minor bumps
git push -u origin bugfix/release-X.Y.Z
```

Then open a pull request on GitHub and merge from there.
