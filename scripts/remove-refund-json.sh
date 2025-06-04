#!/bin/bash
# Simple helper to remove the legacy refund_requests.json file
set -e
FILE="$(dirname "$0")/../assets/uploads/refund_requests.json"
if [ -f "$FILE" ]; then
  echo "Removing $FILE"
  rm "$FILE"
else
  echo "No refund_requests.json file found"
fi

