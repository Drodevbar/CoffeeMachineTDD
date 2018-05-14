FROM centos:7

ENV PHP_VERSION=72

RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm && \
    rpm -Uvh https://rpms.remirepo.net/enterprise/remi-release-7.rpm

RUN yum -y install php${PHP_VERSION} php${PHP_VERSION}-php-cli php${PHP_VERSION}-php-xml php${PHP_VERSION}-php-mbstring

RUN yum -y install epel-release curl

RUN curl -sS https://getcomposer.org/installer | php${PHP_VERSION} -- --filename=composer --install-dir=/usr/local/bin && \
    ln -s /usr/bin/php${PHP_VERSION} /usr/bin/php

WORKDIR /var/www

CMD ["./verify.sh"]