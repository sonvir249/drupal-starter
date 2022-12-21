#!/bin/bash

set -e

# ---------------------------------------------------------------------------- #
#
# Check the syntax of the most recent modified files. Ignore yaml files.
#
# ---------------------------------------------------------------------------- #

FILES=`git diff-tree --no-commit-id --name-only -r HEAD | grep -v yml$`

for FILE in $FILES
do
  if [ -f "$FILE" ]; then
    php -l "$FILE"
  fi
done
