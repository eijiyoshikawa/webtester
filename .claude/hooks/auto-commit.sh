#!/usr/bin/env bash
# Auto-commit and push HEAD to origin/corp/anju after Edit/Write tools.
# Triggered by Claude Code PostToolUse hooks. Reads tool JSON from stdin.

set -uo pipefail

REPO=/home/user/webtester
TARGET_BRANCH=corp/anju
LOG=/tmp/claude-auto-commit.log

INPUT=$(cat)
FILE=$(printf '%s' "$INPUT" | jq -r '.tool_input.file_path // .tool_response.filePath // empty' 2>/dev/null)

[ -z "$FILE" ] && exit 0

case "$FILE" in
  "$REPO"/*) ;;
  *) exit 0 ;;
esac

cd "$REPO" || exit 0

# Skip if path is gitignored or untracked-but-irrelevant
git add -A 2>>"$LOG"

if git diff --cached --quiet; then
  exit 0
fi

REL=${FILE#"$REPO"/}
TS=$(date +"%Y-%m-%d %H:%M:%S")

git commit -m "auto: ${REL} 更新 (${TS})" >>"$LOG" 2>&1 || exit 0

# Push current commit to remote corp/anju (fast-forward only).
PUSH_OUT=$(git push origin "HEAD:${TARGET_BRANCH}" 2>&1) || PUSH_RC=$?
echo "[$TS] push HEAD->$TARGET_BRANCH rc=${PUSH_RC:-0}: $PUSH_OUT" >>"$LOG"

# Also push current branch to its own remote so stop-hook git check passes.
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null)
if [ -n "$CURRENT_BRANCH" ] && [ "$CURRENT_BRANCH" != "HEAD" ] && [ "$CURRENT_BRANCH" != "$TARGET_BRANCH" ]; then
  BR_OUT=$(git push origin "$CURRENT_BRANCH" 2>&1) || BR_RC=$?
  echo "[$TS] push $CURRENT_BRANCH rc=${BR_RC:-0}: $BR_OUT" >>"$LOG"
fi

exit 0
