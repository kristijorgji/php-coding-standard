# kristijorgji/php-coding-standard

PHPCS house style (Slevomat-based) plus a shared git pre-commit hook catalog.

## Table of contents

- [Install (consumer app)](#install-consumer-app)
  - [PHPCS](#phpcs)
  - [Git hooks](#git-hooks)
- [Multi-PHP](#multi-php)
- [License](#license)

## Install (consumer app)

```bash
composer require --dev kristijorgji/php-coding-standard
```

### PHPCS

In your project `phpcs.xml`:

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

One base ruleset (`KristijorgjiCodingStandard`) for PHP 8.2+ consumers. Set the language target per app with PHPCS `php_version`. Do not maintain full per-version rule forks.

For PHP 8.5 apps, also reference the additive `KristijorgjiCodingStandard85` ruleset (enum/attribute spacing and related Slevomat rules):

```xml
<?xml version="1.0"?>
<ruleset name="App">
    <config name="php_version" value="80500"/>
    <rule ref="KristijorgjiCodingStandard"/>
    <rule ref="KristijorgjiCodingStandard85"/>
</ruleset>
```

## License

MIT
