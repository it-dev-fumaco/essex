FROM nginx:alpine

# Remove default config
RUN rm -f /etc/nginx/conf.d/default.conf

# Production nginx config (context: docker/nginx)
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/conf.d /etc/nginx/conf.d

# Ship static public assets inside nginx image (no shared code volume in production)
COPY public /var/www/html/public

# Create log dir and ensure nginx can write
RUN mkdir -p /var/log/nginx /var/cache/nginx \
    && chown -R nginx:nginx /var/cache/nginx /var/log/nginx

EXPOSE 80

STOPSIGNAL SIGQUIT

CMD ["nginx", "-g", "daemon off;"]
