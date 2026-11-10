#!/bin/bash
# Northam theme deployment to SiteGround
set -euo pipefail

# Always run from the script's own folder
cd "$(dirname "$0")"

if [ ! -f .env ]; then
    echo "❌ .env not found. Copy .env.example to .env and fill it in."
    exit 1
fi
source .env

: "${NORTHAM_SSH_USER:?Missing NORTHAM_SSH_USER in .env}"
: "${NORTHAM_SSH_HOST:?Missing NORTHAM_SSH_HOST in .env}"
: "${NORTHAM_REMOTE_PATH:?Missing NORTHAM_REMOTE_PATH in .env}"
NORTHAM_SSH_PORT="${NORTHAM_SSH_PORT:-18765}"

LOCAL_PATH="./wordpress-theme/northam-kadence-child/"
REMOTE="$NORTHAM_SSH_USER@$NORTHAM_SSH_HOST:$NORTHAM_REMOTE_PATH"
RSYNC_OPTS=(-avz --delete -e "ssh -p $NORTHAM_SSH_PORT"
  --exclude ".DS_Store" --exclude ".git" --exclude "node_modules" --exclude "*.md")

echo "🔍 Dry run — nothing changes yet:"
rsync -n "${RSYNC_OPTS[@]}" "$LOCAL_PATH" "$REMOTE"

echo ""
echo "⚠️  Lines starting 'deleting' will be REMOVED from live."
read -r -p "Deploy for real? (y/N) " answer
[[ "$answer" =~ ^[Yy]$ ]] || { echo "Cancelled."; exit 0; }

if rsync "${RSYNC_OPTS[@]}" "$LOCAL_PATH" "$REMOTE"; then
    echo "✅ Deployment complete."
else
    echo "❌ rsync failed — check output above. Live site may be partially updated."
    exit 1
fi
