## Установка

```bash
git clone https://github.com/whethally/dummyjson.git
cd dummyjson
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

---

## Структура проекта

> [COMMANDS]
> 1. ImportDummyJson.php `# Команда импорта любых сущностей (products, users и т.д.)`
> 2. AddDummyJsonProduct.php `# Команда добавления нового продукта`
> 3. AddDummyJsonUser.php `# Команда добавления нового пользователя`

> [SERVICES]
> 1. DummyJsonImporter.php `# Сервис для обращения к DummyJSON API (GET, POST)`

---

## Artisan-команды

### Импорт данных из DummyJSON

Импорт `products`, `users`, `posts`, `recipes`:

```bash
./vendor/bin/sail artisan app:import-dummy-json products
```

```bash
./vendor/bin/sail artisan app:import-dummy-json users
```

Импорт только определённых товаров, например, **iPhone**:

```bash
./vendor/bin/sail artisan app:import-dummy-json products --search="iPhone"
```

---

### Добавление нового продукта

Пример добавления **iPhone 99 Pro Max**:

```bash
./vendor/bin/sail artisan app:add-dummy-json-product --title="iPhone 99 Pro Max" --description="The latest concept iPhone" --price=1999 --brand="Apple" --category="smartphones"
```

---

### Добавление нового пользователя

Пример добавления пользователя **Vitaly Vitaly Vitaly**:

```bash
./vendor/bin/sail artisan app:add-dummy-json-user --firstName="Vitaly" --lastName="Vitaly" --maidenName="Vitaly" --email="vitaly.vitaly@test.ru" --password="TestPassword"
```

---

## Зависимости

- PHP 8.4.1
- Laravel 12
- Docker
- MySQL
