FROM circleci/php:7.3.16-fpm-node

RUN cd /home/circleci \
    && git clone https://github.com/suhanyujie/FirstWechatApplets.git \
    && cd /home/circleci/FirstWechatApplets \
    && composer config repo.packagist composer https://mirrors.aliyun.com/composer/ \
    && composer install --no-progress

CMD ["/bin/sh"]
