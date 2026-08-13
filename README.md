# kristijorgji/php-coding-standard

PHPCS house style (Slevomat-based) plus a shared git pre-commit hook catalog.

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

Re-run after package upgrades. Optional Makefile:

```makefile
dev-init:
	vendor/bin/kj-php-coding-standard-install-hooks
verify-hooks:
	bash vendor/kristijorgji/php-coding-standard/scripts/check-hooks.sh
```

## Multi-PHP

One base ruleset. Set language target per app with PHPCS `php_version`. Optional additive standards can be added later; do not maintain full per-version rule forks.

## License

MIT
