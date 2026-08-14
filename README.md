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
```

Available hooks: `01-markdownlint`, `02-phpunit-related`, `03-code-analyse`, `04-postman-smoke-lint`.

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

## Multi-PHP

One base ruleset (`KristijorgjiCodingStandard` / `ecs/base.php`) for PHP 8.2+ consumers.

For PHP 8.5 apps, also load the additive `KristijorgjiCodingStandard85` / `ecs/php85.php`
(enum/attribute spacing and related Slevomat rules).

## License

MIT
