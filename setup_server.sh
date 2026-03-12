#!/bin/bash
# OutfitGo Server Setup Script

set -e

echo "Updating system..."
sudo apt-get update && sudo apt-get upgrade -y

echo "Installing Docker..."
sudo apt-get install -y ca-certificates curl gnupg lsb-release
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

echo "Installing Nginx..."
sudo apt-get install -y nginx

echo "Creating directories..."
sudo mkdir -p /home/ubuntu/backend
sudo mkdir -p /home/ubuntu/frontend
sudo chown -R ubuntu:ubuntu /home/ubuntu/backend /home/ubuntu/frontend

echo "Configuring Nginx..."
sudo tee /etc/nginx/sites-available/outfitgo <<EOF
server {
    listen 80;
    server_name _;

    root /home/ubuntu/frontend;
    index index.html;

    location / {
        try_files \$uri \$uri/ /index.html;
    }

    location /api {
        proxy_pass http://localhost:8000;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
    }
}
EOF

sudo ln -sf /etc/nginx/sites-available/outfitgo /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx

echo "Setup complete! Please update GitHub Secrets and deploy."
