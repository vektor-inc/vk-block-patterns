#!/bin/bash

set -ex

PLUGIN_NAME='vk-block-patterns'
PLUGIN_DIR=$(cd $(dirname $(dirname $0)); pwd)

dist_dir="${PLUGIN_DIR}/dist"
src_dir="${dist_dir}/${PLUGIN_NAME}"
ZIPBALL="${dist_dir}/${PLUGIN_NAME}.zip"

[[ -e "${dist_dir}" ]] || mkdir "${dist_dir}"
[[ -e "${src_dir}" ]] || mkdir "${src_dir}"
[[ -e "${ZIPBALL}" ]] && rm -r "${ZIPBALL}"

cd "${dist_dir}"

zip -r "${ZIPBALL}" "${PLUGIN_NAME}/"

exit 0