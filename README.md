# self-control-diary

[![Build Status](https://travis-ci.com/olegsklyarov/self-control-diary.svg?branch=master)](https://travis-ci.com/olegsklyarov/self-control-diary)

# Настройка локального окружения

## Исходные требования

Разработку можно вести на любой операционной системе, поддерживающей [docker](https://www.docker.com/get-started). Так
же должен быть установлен Git. Рекомендуемые версии:

```
> docker --version
Docker version 20.10.5

> docker-compose --version
docker-compose version 1.28.5

> git --version
git version 2.31.0
```

## Шаги по настройке окружения  

1. Сделать git clone данного репозитория и открыть в терминале директорию проекта
1. Выполнить команду `docker-compose up -d` (при первом запуске может потребоваться продолжительное время для скачивания необходимых зависимостей)
1. Зайти в командную строку внутри контейнера `docker exec -it -u www-data self-control-diary_php-fpm_1 bash`
1. (в docker) Установить зависимости из composer.json `composer install`
