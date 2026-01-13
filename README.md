## Denvaren - телеграм бот

Телеграм бот поможет вам не забыть о дне рождении вашего друга или близкого человека!
С его помощью вы можете создать список важных для вас дат, а бот заранее оповестит вас о её приближении и даже предложит поздравление.

## Порядок установки

```bash
git clone git@github.com:VladimirDubinin/denvaren.git {PROJECT_NAME}.loc
```

```bash
cd {PROJECT_NAME}.loc
```

```bash
composer install
```

Копировать .env файл и изменить настройки подключения к БД

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

```bash
php artisan migrate
```

## Установка в докере

Если нет make, то взять команды из makefile и выполнять напрямую

Создание контейнера.

```bash
make build
```

Запуск контейнера.

```bash
make up
```

Открыть консоль:

```
make shell
```

В консоли уже можно продолжить обычную установку с шага composer install

