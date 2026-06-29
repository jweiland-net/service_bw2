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

**Pushing to `main` is blocked.** Stay on `main` locally and push directly
to a new remote branch — do **not** create a local branch:

```bash
git push origin main:refs/heads/bugfix/release-X.Y.Z
# or for feature releases:
git push origin main:refs/heads/feature/release-X.Y.Z
```

Then open a pull request on GitHub and merge from there.

> **Note:** `gh` is not installed. Open the PR URL printed by git push
> in the browser manually.
