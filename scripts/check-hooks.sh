#!/usr/bin/env bash
# Verify consumer git hooks registration.

set -euo pipefail

REPO_ROOT="${KJ_PHP_CS_REPO_ROOT:-$(pwd)}"
cd "$REPO_ROOT"

HOOKS_PATH="$(git config --get core.hooksPath || true)"
if [ "$HOOKS_PATH" != "./git_hooks" ] && [ "$HOOKS_PATH" != "git_hooks" ]; then
	echo "FAIL: core.hooksPath is '${HOOKS_PATH:-unset}', expected ./git_hooks"
	echo "Run: vendor/bin/kj-php-coding-standard-install-hooks"
	exit 1
fi

if [ ! -x git_hooks/pre-commit ]; then
	echo "FAIL: git_hooks/pre-commit missing or not executable"
	exit 1
fi

if [ ! -d git_hooks/pre-commit.d ]; then
	echo "FAIL: git_hooks/pre-commit.d missing"
	exit 1
fi

count=0
for f in git_hooks/pre-commit.d/*; do
	[ -f "$f" ] || continue
	[ -x "$f" ] || { echo "FAIL: $f not executable"; exit 1; }
	count=$((count + 1))
done

if [ "$count" -eq 0 ]; then
	echo "FAIL: no hooks in git_hooks/pre-commit.d"
	exit 1
fi

echo "OK: core.hooksPath=$HOOKS_PATH ($count hook(s) in pre-commit.d)"
