### Getting started

```bash
cd docker/ && docker-compose --env-file=.env.local up --build -d
```

To access directly from local host the PostgreSQL database container

```bash
psql postgresql://postgres:password@127.0.0.1:15432/dbtest
```

Read this post on dev.to for more: https://dev.to/nicolasbonnici/how-to-build-a-clean-docker-symfony-5-2-php8-postegresql-nginx-project-3l5g

To hash admin password
```bash
src$  docker exec -i --env-file=.env.local php-fpm symfony console security:hash-password
```
To run Symfony messages and emails
```bash
cd src/ && docker exec -i --env-file=.env.local php-fpm symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async
```