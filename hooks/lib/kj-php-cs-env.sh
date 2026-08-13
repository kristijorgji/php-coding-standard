#!/usr/bin/env bash
# Shared loader for consumer .kj-php-coding-standard.env
# shellcheck shell=bash

kj_php_cs_load_env() {
	local repo_root="$1"
	local env_file="${repo_root}/.kj-php-coding-standard.env"
	if [ -f "$env_file" ]; then
		# shellcheck disable=SC1090
		set -a
		# shellcheck disable=SC1090
		source "$env_file"
		set +a
	fi
}

kj_php_cs_docker_exec() {
	local repo_root="$1"
	shift
	local container="${KJ_PHP_CS_DOCKER_CONTAINER:-}"
	local xdebug_mode="${KJ_PHP_CS_XDEBUG_MODE-}"

	if [ -z "$container" ]; then
		return 127
	fi

	if ! command -v docker >/dev/null 2>&1; then
		return 127
	fi

	if ! docker ps --format '{{.Names}}' 2>/dev/null | grep -qx "$container"; then
		return 127
	fi

	if [ -n "$xdebug_mode" ]; then
		docker exec -e "XDEBUG_MODE=${xdebug_mode}" -w /var/www/html/app "$container" "$@"
		return $?
	fi

	docker exec -w /var/www/html/app "$container" "$@"
	return $?
}
