FROM nginx:1.10-alpine

#ADD config/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY config/nginx /etc/nginx
RUN ln -s /etc/nginx/sites-enabled/esac.nl /etc/nginx/sites-available
RUN rm /etc/nginx/conf.d/default.conf

COPY public /var/www/public
