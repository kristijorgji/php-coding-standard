---
name: generic-package-hygiene
description: >-
  Keeps kristijorgji/php-coding-standard free of consumer, employer, client, and
  reference-project identifiers. Use when editing README, examples, hooks, bin
  scripts, commit messages, or publishing this package.
---

# Generic package hygiene

## Rules

1. Never name or hint at a consumer app, employer, client, reference project, or private repository path in this package.
2. Do not commit a denylist of forbidden names. The list itself would be the leak.
3. Docs and examples use neutral placeholders only:
   - container: `my-app-php`
   - paths: `app/`, `tests/`, `build/coverage/clover.xml`
   - package refs: `kristijorgji/php-coding-standard`, `vendor/bin/...`
4. Invent example snippets. Do not copy prose, paths, class names, or fixture content from a real consumer.
5. Hook and bin messages stay generic (`coverage gate failed`, `clover report not found`). No app-specific runbooks.
6. Before finishing any prose change, re-read the diff for names, host paths under `/Users/`, and private clone URLs.

## When this skill applies

- Adding or editing README sections, hook scripts, bin tools, Cursor rules/skills
- Writing commit messages or PR descriptions for this repository
- Shipping a new tag that includes docs or scripts
