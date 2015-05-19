# REST API application with OAuth2 server on [Yii2](https://github.com/yiisoft/yii2)

This is Yii2 Rest App template with configured OAuth2 server (using https://github.com/Filsh/yii2-oauth2-server). Resolved all problems on adaptation OAuth2 server extension, built directory structure with versions (as recommended in [official guide](http://www.yiiframework.com/doc-2.0/guide-rest-versioning.html)), added some ready-to-use features for increase developing. 

You can use this template as start poing to create API side of your service.

## Run on [Docker](https://docs.docker.com/)
To quick run for testing code I've create this https://github.com/ikaras/yii2-oauth2-rest-docker - it using [Docker Compose](https://docs.docker.com/compose/) to up LEMP stack with all need configurations and ready for requests. Just follow instructions in there.

## Installation

**Install via Composer**

If you do not have [Composer](http://getcomposer.org/), you may install it by following the
[instructions at getcomposer.org](https://getcomposer.org/doc/00-intro.md).

1\. You can then install the application using the following commands:
```
composer global require "fxp/composer-asset-plugin:~1.0.0"
composer create-project --stability="dev" --prefer-source ikaras/yii2-oauth2-rest-template .
```

2\. Configure your Webservice, nginx or apache (see how [here](http://www.yiiframework.com/doc-2.0/guide-start-installation.html#configuring-web-servers)), to look at the `application/api/www` directory. I used domain `api.loc` in my tests.

3\. Change connection to your db in `application/api/common.php`

4\. Run migrations
```
php application/api/yiic migrate --migrationPath=@yii/rbac/migrations --interactive=0 \
php application/api/yiic migrate --migrationPath=@vendor/filsh/yii2-oauth2-server/migrations --interactive=0 \
php application/api/yiic migrate --interactive=0 \
```
