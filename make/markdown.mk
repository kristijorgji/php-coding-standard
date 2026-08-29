# Shared markdown lint/fix (include from the consumer Makefile).
# Override pins before include if needed:
#   PRETTIER_VERSION = 3.5.3
#   ML_VERSION = v0.17.2
#   include vendor/kristijorgji/php-coding-standard/make/markdown.mk
#
# Both tools use git-tracked paths (skips vendor/). Override MD_FILES before
# include to drop extra files (e.g. README.md if the consumer ignores it).

PRETTIER_VERSION ?= 3.5.3
ML_VERSION ?= v0.17.2
MD_FILES ?= $(shell git ls-files -- '*.md')

.PHONY: lint-markdown fix-markdown

lint-markdown:
	@echo "################################################################################"
	@echo "# markdownlint-cli2"
	@echo "################################################################################"
	@if [ -z "$(MD_FILES)" ]; then exit 0; fi
	@docker run --rm -v $(PWD):/data -w /data \
		davidanson/markdownlint-cli2:$(ML_VERSION) $(MD_FILES)

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
		davidanson/markdownlint-cli2:$(ML_VERSION) --fix $(MD_FILES)
