FROM nginx:alpine
ARG TAG
LABEL TAG=${TAG}

ADD output_prod/ /usr/share/nginx/html/
