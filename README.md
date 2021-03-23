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

1. Сделать git clone данного репозитория и открыть в терминале директорию проект
1. Создать в корне проекта файл `.env.local` в котором будут храниться переменные для локального окружения   
1. Добавить в `.env.local` переменную окружения `DATABASE_URL` для доступа к базе данных. Проект поддерживает СУБД PostgreSQL или MySQL. 
   1. (PostgreSQL) `DATABASE_URL=postgresql://self-control-diary:self-control-diary@postgres:5432/self-control-diary?serverVersion=12&charset=utf8`
   1. (MySQL) `DATABASE_URL=mysql://self-control-diary:self-control-diary@mysql:3306/self-control-diary?serverVersion=5.7`
1. Выполнить команду `docker-compose up -d` (при первом запуске может потребоваться продолжительное время для скачивания необходимых зависимостей)
1. Зайти в командную строку в docker контейнере `docker exec -it -u www-data self-control-diary_php-fpm_1 bash`
1. (docker) Установить зависимости из composer.json `composer install`
1. (docker) Создать пустую базу данных в выбранной СУБД, накатить миграции `composer run-script recreate-db`
1. (docker) Запустить тесты `composer run-script test`
1. (docker) Не забыть выйти из docker контейнера `exit`

## Чек-лист перед отправкой Pull Request

1. Подтянуть в свою ветку свежий master (при необходимости).
1. Исправить конфликты между своей веткой и master (если есть).
1. Прогнать проверку code style командой `composer run-script fix-cs`, если найдены недочеты, то сделать коммит с исправлениями.
1. Прогнать тесты при помощи `composer run-script test`. Если тесты "упали", то сделать коммит и исправлениями, после чего заново прогнать тесты.
1. Сделать git push и [создать Pull Request](https://github.com/olegsklyarov/self-control-diary/compare).
