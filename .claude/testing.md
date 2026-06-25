# Testing & QA Commands

All test commands use `./Build/Scripts/runTests.sh`. Locally always prefix with
`CI=true` to suppress the `-it` TTY flag that swallows output when running
non-interactively.

## Available Test Suites (`-s <suite>`)

| Suite                  | Purpose                                      |
|------------------------|----------------------------------------------|
| `lint`                 | PHP syntax check across all non-.Build files |
| `cgl`                  | PHP-CS-Fixer — apply code style fixes        |
| `functional`           | PHPUnit functional tests (SQLite by default) |
| `phpstan`              | PHPStan static analysis                      |
| `phpstanBaseline`      | Regenerate PHPStan baseline                  |
| `rector`               | Apply Rector rules                           |
| `composerUpdate`       | `composer update` inside the container       |
| `composerValidate`     | `composer validate`                          |
| `composerNormalize`    | `composer normalize`                         |
| `clean`                | Remove temporary build artefacts             |
| `cleanCache`           | Remove cache folders                         |

## Common Invocations

```bash
# Lint all PHP files
CI=true ./Build/Scripts/runTests.sh -s lint

# Fix code style (run before every commit)
CI=true ./Build/Scripts/runTests.sh -s cgl

# Run all functional tests
CI=true ./Build/Scripts/runTests.sh -s functional

# Run a single test file
CI=true ./Build/Scripts/runTests.sh -s functional -- Tests/Functional/Domain/Model/RecordTest.php

# Run a specific test class or method (PHPUnit filter)
CI=true ./Build/Scripts/runTests.sh -s functional -- --filter testMethodName Tests/Functional/...

# Run functional tests against MariaDB
CI=true ./Build/Scripts/runTests.sh -s functional -d mariadb

# PHPStan analysis
CI=true ./Build/Scripts/runTests.sh -s phpstan
```

## Database Options (`-d <db>`)

Only relevant for the `functional` suite:

| Option     | Default |
|------------|---------|
| `sqlite`   | ✓       |
| `mysql`    |         |
| `mariadb`  |         |
| `postgres` |         |

## PHP Version (`-p <version>`)

Defaults to `8.2`. Supported: `8.2`, `8.3`, `8.4`.

```bash
CI=true ./Build/Scripts/runTests.sh -p 8.4 -s functional
```

## Functional Test Structure

```
Tests/Functional/
├── Client/
│   ├── Request/Portal/          # API request class tests
│   └── ServiceBwClientTest.php
├── Command/
│   ├── CacheWarmupCommandTest.php
│   └── PrepareForSolrIndexingCommandTest.php
├── Configuration/
│   └── ExtConfTest.php
├── Domain/
│   ├── Model/RecordTest.php
│   ├── Provider/
│   │   ├── LeistungenProviderTest.php
│   │   ├── LebenslagenProviderTest.php
│   │   └── OrganisationseinheitenProviderTest.php
│   └── Repository/
│       ├── LeistungenRepositoryTest.php
│       ├── LebenslagenRepositoryTest.php
│       ├── OrganisationseinheitenRepositoryTest.php
│       └── RepositoryFactoryTest.php
├── Fixtures/
│   └── tx_servicebw2_response.csv   # Shared CSV fixture for all functional tests
└── Helper/
    └── LanguageHelperTest.php
```

## Pre-commit Sequence

Always run these three suites in order before committing:

```bash
CI=true ./Build/Scripts/runTests.sh -s lint
CI=true ./Build/Scripts/runTests.sh -s rector
CI=true ./Build/Scripts/runTests.sh -s cgl
```

Lint runs first — it is a fast PHP syntax check and gates the subsequent
tools. Rector cannot correctly parse and transform code that has syntax
errors. Cgl runs last because rector may restructure code that then needs
reformatting.

## Rector

Rector is configured in `Build/rector/rector.php` and targets TYPO3 13 +
PHP 8.2.

```bash
# Dry-run — shows what would change without touching files
CI=true ./Build/Scripts/runTests.sh -s rector -n

# Apply all changes
CI=true ./Build/Scripts/runTests.sh -s rector
```

## Notes

- The CI workflow (`.github/workflows/ci.yml`) runs lint + functional tests on
  PHP 8.2/8.3/8.4 against MySQL, MariaDB, and PostgreSQL.