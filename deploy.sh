#!/bin/bash
# Northam theme deployment to SiteGround

# SiteGround SSH details
SSH_USER="u2161-b0eepd24ni5b"
SSH_HOST="ssh.northamdevon.co.uk"
SSH_PORT="18765"
REMOTE_PATH="/home/your-username/www/northamdevon.co.uk/public_html/wp-content/themes/northam-child/"
LOCAL_PATH="./northam-child/"

echo "🚀 Deploying Northam theme to SiteGround..."

rsync -avz -e "ssh -p $SSH_PORT" --delete $LOCAL_PATH $SSH_USER@$SSH_HOST:$REMOTE_PATH

echo "✅ Deployment complete!"