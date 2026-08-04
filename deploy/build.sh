#!/usr/bin/env bash
# Build script for the server's deploy-app webhook (run with APP set, CWD = repo root).
# Required because this Dockerfile is multi-stage: a plain `docker build` would build
# the LAST stage (testing: dev deps + xdebug) instead of production.
set -euo pipefail
APP="${APP:-url-pusher}"

echo "[build] ${APP}:latest (target: production)"
docker build --target production -t "${APP}:latest" .

echo "[build] ${APP}-web:latest (nginx front)"
docker build -f docker/nginx.Dockerfile --build-arg "APP_IMAGE=${APP}:latest" -t "${APP}-web:latest" .
