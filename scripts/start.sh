#!/usr/bin/env bash

# Make us independent from working directory
pushd `dirname $0` > /dev/null
SCRIPT_DIR=`pwd`
popd > /dev/null

# Prerequisits
docker --version > /dev/null 2>&1 || { echo >&2 "Docker not found. Please install it via https://docs.docker.com/install/"; exit 1; }

# Ensure directory is writable for storing MySql and IDE data
chmod 777 "$SCRIPT_DIR/../.docker/"
mkdir -p -m 777 "$SCRIPT_DIR/../.docker/ide-home"

# Start server
echo "Starting docker containers..."
docker compose -p symfony -f "$SCRIPT_DIR/docker-compose.yml" up -d --build

# Documentation for end user
echo "Now open in browser http://127.0.0.1:8000"

