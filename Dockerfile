FROM mickadoo/php7
MAINTAINER michaeldevery@gmail.com

RUN git clone https://github.com/mickadoo/daft.git /opt/daft-app
RUN composer install -d /opt/daft-app

CMD ["php", "/opt/daft-app/index.php"]