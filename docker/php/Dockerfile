FROM php:8.1.1-fpm-alpine3.15

RUN apk update \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        postgresql-dev \
    && apk --no-cache add \
        git \
        zsh \
        make \
        postgresql-libs \
    && pecl install xdebug-3.1.2 \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && pecl clear-cache \
    && rm -rf /tmp/* /var/cache/apk/* \
    && apk del .build-deps

RUN curl -OL https://getcomposer.org/download/2.2.4/composer.phar \
    && mv ./composer.phar /usr/bin/composer \
    && chmod +x /usr/bin/composer

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

ARG LINUX_USER_ID

RUN addgroup --gid $LINUX_USER_ID docker \
    && adduser --uid $LINUX_USER_ID --ingroup docker --home /home/docker --shell /bin/zsh --disabled-password --gecos "" docker

USER $LINUX_USER_ID

RUN wget https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh -O - | zsh
RUN echo 'export ZSH=/home/docker/.oh-my-zsh' > ~/.zshrc \
    && echo 'ZSH_THEME="simple"' >> ~/.zshrc \
    && echo 'plugins=(npm)' >> ~/.zshrc \
    && echo 'source $ZSH/oh-my-zsh.sh' >> ~/.zshrc \
    && echo 'PROMPT="%{$fg_bold[yellow]%}php_cli@docker_monitor %{$fg_bold[blue]%}%(!.%1~.%~)%{$reset_color%} "' > ~/.oh-my-zsh/themes/simple.zsh-theme

WORKDIR /srv/app
