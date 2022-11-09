FROM php:7.4.33-apache
COPY ./ws/ /var/www/html/
WORKDIR /var/www/html
#RUN echo "ServerName localhost" | tee /etc/apache2/conf-available/fqdn.conf
#RUN a2enconf fqdn
RUN docker-php-ext-install pdo_mysql
EXPOSE 80
#CMD [ "php", "./ws/index.php" ]