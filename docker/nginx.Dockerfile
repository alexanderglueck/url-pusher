# nginx front for the production deployment (built as url-pusher-web:latest).
# Copies public/ out of the already-built app image, so the Vite output and the
# downloadable .apk are served by nginx directly -- no shared volume, no start-order
# dependency between the containers.
ARG APP_IMAGE=url-pusher:latest

FROM ${APP_IMAGE} AS app

FROM nginx:1.27-alpine

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=app /app/public /app/public
