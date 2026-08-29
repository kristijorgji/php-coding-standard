#!/usr/bin/env bash
# Shared loader and PHP runtime helpers for consumer .kj-php-coding-standard.env
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

kj_php_cs_docker_running() {
	local container="${KJ_PHP_CS_DOCKER_CONTAINER:-}"
	if [ -z "$container" ]; then
		return 1
	fi
	if ! command -v docker >/dev/null 2>&1; then
		return 1
	fi
	docker ps --format '{{.Names}}' 2>/dev/null | grep -qx "$container"
}

kj_php_cs_docker_exec() {
	local repo_root="$1"
	shift
	local container="${KJ_PHP_CS_DOCKER_CONTAINER:-}"
	local workdir="${KJ_PHP_CS_DOCKER_WORKDIR:-/var/www/html/app}"
	local xdebug_mode="${KJ_PHP_CS_RUN_XDEBUG-${KJ_PHP_CS_XDEBUG_MODE-}}"

	if ! kj_php_cs_docker_running; then
		return 127
	fi

	if [ -n "$xdebug_mode" ]; then
		docker exec -e "XDEBUG_MODE=${xdebug_mode}" -w "$workdir" "$container" "$@"
		return $?
	fi

	docker exec -w "$workdir" "$container" "$@"
	return $?
}

kj_php_cs_host_exec() {
	local repo_root="$1"
	shift
	local php_bin="${KJ_PHP_CS_PHP_BIN:-}"
	local xdebug_mode="${KJ_PHP_CS_RUN_XDEBUG-${KJ_PHP_CS_XDEBUG_MODE-}}"
	local -a cmd=("$@")

	if [ ${#cmd[@]} -eq 0 ]; then
		return 127
	fi

	if [ -n "$php_bin" ]; then
		case "${cmd[0]}" in
			composer) ;;
			*)
				cmd=("$php_bin" "${cmd[@]}")
				;;
		esac
	fi

	(
		cd "$repo_root" || exit 127
		if [ -n "$xdebug_mode" ]; then
			export XDEBUG_MODE="$xdebug_mode"
		fi
		"${cmd[@]}"
	)
}

# Run a command on host PHP, in the docker container, or auto (docker if running, else host).
# KJ_PHP_CS_PHP_RUNTIME=auto|host|docker (default auto).
# Optional KJ_PHP_CS_RUN_XDEBUG overrides KJ_PHP_CS_XDEBUG_MODE for this call.
kj_php_cs_run() {
	local repo_root="$1"
	shift
	local runtime="${KJ_PHP_CS_PHP_RUNTIME:-auto}"

	case "$runtime" in
		host)
			kj_php_cs_host_exec "$repo_root" "$@"
			return $?
			;;
		docker)
			kj_php_cs_docker_exec "$repo_root" "$@"
			return $?
			;;
		auto|"")
			if kj_php_cs_docker_running; then
				kj_php_cs_docker_exec "$repo_root" "$@"
				return $?
			fi
			kj_php_cs_host_exec "$repo_root" "$@"
			return $?
			;;
		*)
			echo "WARNING: unknown KJ_PHP_CS_PHP_RUNTIME=${runtime} (expected auto|host|docker)" >&2
			return 127
			;;
	esac
}
