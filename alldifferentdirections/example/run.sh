#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

"$DIR/../bin/traveller.php" < "$DIR/input.txt" > "$DIR/output.txt" \
  && diff "$DIR/expected_output.txt" "$DIR/output.txt" && echo "OK!" || echo "Wrong!"
