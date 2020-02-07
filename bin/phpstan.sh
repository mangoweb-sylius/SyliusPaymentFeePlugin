#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# project root
cd "$(dirname "$DIR")"

set -x
vendor/bin/phpstan analyse \
	--level 7 \
	--memory-limit 2G \
	--configuration phpstan.neon \
	src tests
