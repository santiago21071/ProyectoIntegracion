FROM php:7.4.33-apache
COPY ./ws/ /var/www/html/
WORKDIR /var/www/html
#RUN echo "ServerName localhost" | tee /etc/apache2/conf-available/fqdn.conf
#RUN a2enconf fqdn
#RUN curl -fsSL https://pkg.jenkins.io/debian-stable/jenkins.io.key | tee \
#  /usr/share/keyrings/jenkins-keyring.asc > /dev/null
#RUN echo deb [signed-by=/usr/share/keyrings/jenkins-keyring.asc] \
#  https://pkg.jenkins.io/debian-stable binary/ | tee \
#  /etc/apt/sources.list.d/jenkins.list > /dev/null
#RUN apt-get update
#RUN apt-get install jenkins

#RUN apt install openjdk-11-jre
#RUN java -version
RUN docker-php-ext-install pdo_mysql
EXPOSE 80
#CMD [ "php", "./ws/index.php" ]