# kristijorgji/php-coding-standard

PHPCS / ECS house style (Slevomat-based) plus a shared git pre-commit hook catalog.

## Table of contents

- [Install (consumer app)](#install-consumer-app)
  - [ECS (recommended)](#ecs-recommended)
  - [PHPCS (XML, back-compat)](#phpcs-xml-back-compat)
  - [Git hooks](#git-hooks)
- [Multi-PHP](#multi-php)
- [License](#license)

## Install (consumer app)

```bash
composer require --dev kristijorgji/php-coding-standard:^0.3
```

Also require Easy Coding Standard in the app:

```bash
composer require --dev symplify/easy-coding-standard:^13
```

### ECS (recommended)

Create `ecs.php` at the app root and merge the shared configs:

```php
<?php declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

$base = require __DIR__ . '/vendor/kristijorgji/php-coding-standard/ecs/base.php';
$php85 = require __DIR__ . '/vendor/kristijorgji/php-coding-standard/ecs/php85.php';

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/tests',
    ])
    ->withRules(array_merge($base['rules'], $php85['rules']))
    ->withSkip(array_merge($base['skip'], $php85['skip']))
    ->withParallel()
    ->withCache(__DIR__ . '/.ecs_cache');
```

Apply each entry from `$base['rulesWithConfiguration']` / `$php85['rulesWithConfiguration']`
via `->withConfiguredRule($class, $config)`.

0.3.1 additions: spaces-only indentation (`IndentationTypeFixer`), left-aligned
phpdoc (`PhpdocAlignFixer` `align: left`), one blank line between class members
(`ClassAttributesSeparationFixer`, `MethodSpacingSniff`), and closure `use (`
wrapping via `MultiLineFunctionDeclaration`.

0.3.2: `ClassAttributesSeparationFixer` used `only_if_meta` for `const` and
`property` (blank line only when phpdoc/attributes exist; bare members stay
packed). Methods still use `one`.

0.3.3: `ClassAttributesSeparationFixer` uses `none` for `const`, `property`,
`trait_import`, and `case`. Despite the name, `none` still inserts a blank line
after a documented/attributed member and between different member kinds; bare
runs of the same kind stay packed. Methods still use `one`.

Also in 0.3.3: shared coverage gate tooling (see [Coverage gate](#coverage-gate)).

0.3.4: trailing commas forbidden on single-line calls/declarations/closures
(`DisallowTrailingComma*` `onlySingleLine: true`),
`Generic.Strings.UnnecessaryStringConcat` (`allowMultiline: true`, so
line-wrapped literal concatenations remain allowed; same-line `'a' . 'b'` is not),
and PHPUnit fixers (`PhpUnitConstructFixer`, `PhpUnitDedicateAssertFixer`,
`PhpUnitSetUpTearDownVisibilityFixer`, `PhpUnitTestAnnotationFixer` style
`prefix`, plus kubawerlos `PhpUnitAssertArgumentsOrderFixer` /
`PhpUnitDedicatedAssertFixer`). Assertion call style stays `$this->assert*`
(Rector `PreferPHPUnitThisCallRector`); `PhpUnitTestCaseStaticMethodCallsFixer`
is intentionally not included.

0.3.5: shared YAML lint CLI (`kj-php-coding-standard-yaml-lint`); see
[YAML lint](#yaml-lint).

Composer scripts:

```json
{
    "code-style": ["vendor/bin/ecs check"],
    "code-format": ["vendor/bin/ecs check --fix"]
}
```

### PHPCS (XML, back-compat)

The XML rulesets remain for consumers that have not migrated yet:

```xml
<rule ref="KristijorgjiCodingStandard"/>
```

Keep path excludes and `<config name="php_version" value="80200"/>` in the app file.

### Git hooks

Create `.kj-php-coding-standard.env` at the repo root:

```bash
KJ_PHP_CS_DOCKER_CONTAINER=my-app-php
KJ_PHP_CS_XDEBUG_MODE=off
KJ_PHP_CS_HOOKS=01-markdownlint,02-phpunit-related,03-code-analyse
# Optional coverage gate (also requires listing 05-coverage-gate in KJ_PHP_CS_HOOKS):
# KJ_PHP_CS_COVERAGE_MIN=80
# KJ_PHP_CS_COVERAGE_CLOVER=build/coverage/clover.xml
```

Available hooks: `01-markdownlint`, `02-phpunit-related`, `03-code-analyse`,
`04-postman-smoke-lint`, `05-coverage-gate`.

```bash
vendor/bin/kj-php-coding-standard-install-hooks
```

Install layout (gitignore the whole tree in the consumer):

```text
git_hooks/
  pre-commit
  installed/
    kristijorgji/
      php-coding-standard/
        lib/
        pre-commit.d/
```

```gitignore
/git_hooks/
```

The root `pre-commit` runs every executable under `installed/<vendor>/<package>/pre-commit.d/`, ordered by basename then path (`01-` before `02-` across packages).

Re-run after package upgrades. Optional Makefile:

```makefile
dev-init:
	vendor/bin/kj-php-coding-standard-install-hooks
verify-hooks:
	bash vendor/kristijorgji/php-coding-standard/scripts/check-hooks.sh
```

### Coverage gate

Shared clover threshold checker (no consumer-specific paths baked in):

```bash
vendor/bin/phpunit --coverage-clover build/coverage/clover.xml
vendor/bin/kj-php-coding-standard-coverage-check build/coverage/clover.xml 80
```

Suggested composer script:

```json
{
    "tests-coverage": [
        "vendor/bin/phpunit --coverage-clover build/coverage/clover.xml",
        "vendor/bin/kj-php-coding-standard-coverage-check build/coverage/clover.xml 80"
    ]
}
```

Run with `XDEBUG_MODE=coverage` (or another coverage driver). Gitignore the report
directory (for example `/build/coverage/`).

To also enforce the gate on commit, add `05-coverage-gate` to `KJ_PHP_CS_HOOKS`
and set `KJ_PHP_CS_COVERAGE_MIN` in `.kj-php-coding-standard.env`, then re-run
the installer. The hook exits 0 when `KJ_PHP_CS_COVERAGE_MIN` is unset.

### YAML lint

Parse one or more YAML files (paths are consumer-supplied; nothing is hardcoded):

```bash
vendor/bin/kj-php-coding-standard-yaml-lint path/to/config.yml another.yml
```

Suggested composer script:

```json
{
    "yaml-lint": [
        "vendor/bin/kj-php-coding-standard-yaml-lint serverless.yml"
    ]
}
```

## Multi-PHP

One base ruleset (`KristijorgjiCodingStandard` / `ecs/base.php`) for PHP 8.2+ consumers.

For PHP 8.5 apps, also load the additive `KristijorgjiCodingStandard85` / `ecs/php85.php`
(enum/attribute spacing and related Slevomat rules).

## License

MIT
