#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
cat "$DIR/input.txt" | "$DIR/../bin/traveller.php"
