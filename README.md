# Глобус

Учебный каталог стран и исторических фактов. Проект использует Laravel 12, PostgreSQL и Bootstrap. Laravel Mix собирает интерфейс, а Node.js также обрабатывает загружаемые флаги.

Для запуска нужны PHP 8.3+, Composer, Node.js 22+ и база PostgreSQL. Скопируйте `.env.example` в `.env`, если файла ещё нет, укажите `DB_CONNECTION=pgsql` и заполните остальные параметры подключения.

```sh
composer install
npm ci
npm run dev
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan db:seed
php artisan serve
```

Команда `key:generate` нужна при создании нового `.env`, а `storage:link` — при первой настройке хранилища. Сидеры добавляют пять стран, флаги и начальные исторические факты. Запустите `npm run watch`, если нужно пересобирать стили и JavaScript при изменении исходников.

На странице страны видны описание и факты. Управление фактами находится в форме редактирования страны.

```sh
php artisan test
```
