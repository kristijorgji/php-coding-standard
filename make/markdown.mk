# Shared markdown lint/fix (include from the consumer Makefile).
# Override pins before include if needed:
#   PRETTIER_VERSION = 3.5.3
#   ML_VERSION = v0.17.2
#   include vendor/kristijorgji/php-coding-standard/make/markdown.mk
#
# Prettier uses git-tracked paths (skips vendor/). markdownlint uses **/*.md
# so consumer .markdownlint-cli2.jsonc ignores still apply.

PRETTIER_VERSION ?= 3.5.3
ML_VERSION ?= v0.17.2
MD_FILES ?= $(shell git ls-files -- '*.md')

.PHONY: lint-markdown fix-markdown

lint-markdown:
	@echo "################################################################################"
	@echo "# markdownlint-cli2"
	@echo "################################################################################"
	@docker run --rm -v $(PWD):/data -w /data \
		davidanson/markdownlint-cli2:$(ML_VERSION) "**/*.md"

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
	@docker run --rm -v $(PWD):/data -w /data \
		davidanson/markdownlint-cli2:$(ML_VERSION) --fix "**/*.md"
