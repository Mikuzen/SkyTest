## Требования перед установкой

Настроить локальное окружение:
* Docker 20.10.21 
* Docker-compose 1.26.0 * git 2.34.1

## Установка проекта

Сгенерировать SSH-ключ

```shell
  ssh-keygen
```

Просматриваем сгенерированный ключ из файла id_rsa.pub, используя команду cat путь к файлу id_rsa.pub

```shell
  cat path
```

Появится ssh ключ, выделить его от ssh по = скопировать и добавить его в github. После зайти в папку, в которой будет
располагаться проект выполнить команду

```shell
  git clone git@github.com:Mikuzen/SkyTest.git NameFolder
```

После клонирования проекта, зайти в папку проект и ввести команду

```shell
    cp .env.example .env
```

Скопируется файл .env.example с именем .env. Зайти в этот файл выполнить изменить строки:

```dotenv
    APP_DEBUG=false
    
    DB_DATABASE=Name your database 
    DB_USERNAME=Your username database
    DB_PASSWORD=Your password for user database
```

После этого выполняем команду docker-compose

```shell
    docker-compose up -d
```

Контейнеры запустили, чтобы проверить вводим команду:

```shell
    dockert ps
```

Заходим в контейнер skytest_app_1

```shell
docker exec -it skytest_app_1 bash
```

Выполняем команду composer, для загрузки зависимостей:

```shell
composer install
```

Выходим из контейнера введя `exit`

После в консоли вводим команду для создания алиаса sail:

```shell
  alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

И выполняем команды sail:

```shell
    sail up -d
    sail artisan migrate
```

## Postman коллекция

Коллекция для postman хранится в проекте по следующему пути:
`storage/collection/SkyTest.postman_collection.json`

Для работы в Postman необходимо создать Environment с данными:

```dotenv
token - Для подстановке токена при обращении к маршрутам для работы с файлами и папками
url - адрес для обращения к api (localhost - для локального проекта, для обращения к серверу -  http://89.108.64.184)
```

Чтобы получить api-token для поля token в env postman обращаться по маршрутам:
```
{{url}/api/register - регистрация пользователя
{{url}/api/login - аутентификация пользователя
```
