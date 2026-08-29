#!/usr/bin/env bash
# Hide vendor/ and turn off gitignore walking. File lists come from git ls-files.
set -euo pipefail

image=$1
shift
fix=()
if [[ ${1:-} == --fix ]]; then
  fix=(--fix)
  shift
fi
if [[ ${1:-} == -- ]]; then
  shift
fi
if [[ $# -eq 0 ]]; then
  exit 0
fi

cfg=$(mktemp)
trap 'rm -f "$cfg"' EXIT

if [[ -f .markdownlint-cli2.jsonc ]]; then
  sed 's/"gitignore"[[:space:]]*:[[:space:]]*true/"gitignore": false/' .markdownlint-cli2.jsonc > "$cfg"
  overlay=(-v "$cfg:/data/.markdownlint-cli2.jsonc:ro")
  config_args=()
else
  printf '%s\n' '{ "gitignore": false }' > "$cfg"
  overlay=(-v "$cfg:/tmp/markdownlint-cli2.make.jsonc:ro")
  config_args=(--config /tmp/markdownlint-cli2.make.jsonc)
fi

docker run --rm \
  -v "$PWD:/data" \
  "${overlay[@]}" \
  -w /data \
  --tmpfs /data/vendor \
  "$image" \
  "${config_args[@]}" \
  "${fix[@]}" \
  --no-globs \
  "$@"
