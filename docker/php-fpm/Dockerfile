FROM php:8.0-fpm

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it

RUN set -eux; apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev libxslt-dev libgd-dev libwebp-dev && \
    pecl install apcu && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-configure gd --enable-gd --with-webp --with-jpeg --with-xpm --with-freetype && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo pdo_pgsql pgsql xsl gd && \
    docker-php-ext-enable apcu pdo_pgsql sodium && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' |  tee /etc/apt/sources.list.d/symfony-cli.list && \
    apt update && \
    apt install symfony-cli

RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - && apt-get install -y nodejs && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg |  apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" |  tee /etc/apt/sources.list.d/yarn.list && \
    apt update && apt install yarn

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD composer i -o ; mv assets/styles/app.css assets/styles/app.scss; yarn install; yarn dev

CMD wait-for-it db:5432 -- bin/console doctrine:migrations:migrate ;  php-fpm

EXPOSE 9000
