FROM bitnami/laravel:7

USER root
#Solves the cache issue
RUN echo "opcache.revalidate_freq = 0" >> /opt/bitnami/php/etc/php.ini
#Solves the composer memory issue
RUN echo "memory_limit = -1" >> /opt/bitnami/php/etc/php.ini

USER bitnami
ENTRYPOINT [ "/app-entrypoint.sh" ]
CMD [ "php", "artisan", "serve", "--host=0.0.0.0", "--port=3000" ]