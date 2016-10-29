FROM mickadoo/php7
MAINTAINER michaeldevery@gmail.com

RUN git clone https://github.com/mickadoo/daft.git /opt/daft
RUN composer install -d /opt/daft

CMD ["php", "/opt/daft/index.php"]