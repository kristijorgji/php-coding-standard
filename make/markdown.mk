# Shared markdown lint/fix (include from the consumer Makefile).
# Override pins before include if needed:
#   PRETTIER_VERSION = 3.5.3
#   ML_VERSION = v0.17.2
#   include vendor/kristijorgji/php-coding-standard/make/markdown.mk
#
# Both tools use git-tracked paths (skips vendor/). Override MD_FILES before
# include to drop extra files (e.g. README.md if the consumer ignores it).
# markdownlint turns off gitignore walking (MD_FILES is already from git) and
# hides vendor/ with tmpfs so Docker does not scan Composer deps.

PRETTIER_VERSION ?= 3.5.3
ML_VERSION ?= v0.17.2
MD_FILES ?= $(shell git ls-files -- '*.md')
KJ_PHP_CS_MAKE_DIR := $(patsubst %/,%,$(dir $(lastword $(MAKEFILE_LIST))))

.PHONY: lint-markdown fix-markdown

lint-markdown:
	@echo "################################################################################"
	@echo "# markdownlint-cli2"
	@echo "################################################################################"
	@if [ -z "$(MD_FILES)" ]; then exit 0; fi
	@$(KJ_PHP_CS_MAKE_DIR)/run-markdownlint.sh davidanson/markdownlint-cli2:$(ML_VERSION) -- $(MD_FILES)

fix-markdown:
	@echo "################################################################################"
	@echo "# Prettier (Restricted to Markdown)"
	@echo "################################################################################"
	@if [ -z "$(MD_FILES)" ]; then exit 0; fi
	@docker run --rm \
		-v $(PWD):/work \
		-w /work \
		--user $$(id -u):$$(id -g) \
		tmknom/prettier:$(PRETTIER_VERSION) \
		--write $(MD_FILES) \
		--parser markdown \
		--ignore-path .gitignore
	@echo "################################################################################"
	@echo "# markdownlint-cli2 --fix"
	@echo "################################################################################"
	@$(KJ_PHP_CS_MAKE_DIR)/run-markdownlint.sh davidanson/markdownlint-cli2:$(ML_VERSION) --fix -- $(MD_FILES)
