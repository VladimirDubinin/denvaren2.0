## Denvaren - телеграм бот, который поможет не забыть о важной дате 

С его помощью можно создать список важных дат, а бот заранее оповестит о её приближении и даже поздравление, 
сгенерированное с помощью нейросети.

## Порядок установки

```bash
git clone git@github.com:VladimirDubinin/denvaren2.0.git {PROJECT_NAME}.loc
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

В env необходимо указать токен бота в поле TELEGRAM_BOT_TOKEN, секретный токен в поле SECRET_KEY, 
указать модель нейросети в параметре OPENROUTER_MODEL и ключ в OPENROUTER_API_KEY

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
