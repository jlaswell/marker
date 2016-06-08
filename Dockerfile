FROM realpage/php:7-cli

# Update the package repository and install applications
WORKDIR /usr/local

COPY infrastructure/fcron-3.2.0.src.tar.gz ./

# Update the package repository and install applications
RUN DEBIAN_FRONTEND=noninteractive apt-get -qq update \
    && apt-get -yqq install build-essential sendmail vim-tiny git zip unzip \
    && apt-get -yqq clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    # Install fcron
    && tar -xzf fcron-3.2.0.src.tar.gz \
    && rm fcron-3.2.0.src.tar.gz \
    && cd fcron-3.2.0 \
    && ./configure \
    && make \
    && make install


# Copy the application files to the container
ADD . /var/www/html

WORKDIR /var/www/html

# Install Composer
RUN php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php \

    # this hash needs to be updated with each composer version release
    && php -r "if (hash('SHA384', file_get_contents('composer-setup.php')) === '070854512ef404f16bac87071a6db9fd9721da1684cd4589b1196c3faf71b9a2682e2311b36a5079825e155ac7ce150d') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \

    # parallel dependency installation
    && composer global require hirak/prestissimo \

    # production-ready dependencies
    && composer install  --no-interaction --no-dev --prefer-dist

RUN fcrontab /var/www/html/crontab

CMD /usr/local/sbin/fcron -df
