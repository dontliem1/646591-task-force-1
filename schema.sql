CREATE TABLE categories
(
 id    int NOT NULL AUTO_INCREMENT PRIMARY KEY,
 icon  varchar(255) NOT NULL COMMENT 'Буквенный id иконки',
 name  varchar(255) NOT NULL COMMENT 'Название категории'
) COMMENT='Категории заданий';

CREATE TABLE cities
(
 id    int NOT NULL AUTO_INCREMENT PRIMARY KEY,
 city  varchar(255) NOT NULL COMMENT 'Название города',
 lat   decimal(10,8) NOT NULL COMMENT 'Широта',
 lng   decimal(11,8) NOT NULL COMMENT 'Долгота'
) COMMENT='Города';

CREATE TABLE users
(
 id                 int NOT NULL AUTO_INCREMENT PRIMARY KEY,
 email              varchar(255) NOT NULL COMMENT 'Почта пользователя',
 name               varchar(255) NOT NULL COMMENT 'Имя и фамилия пользователя',
 city_id            int NOT NULL COMMENT 'Выбранный пользователем город',
 password           varchar(255) NOT NULL COMMENT 'Хэш пароля',
 dt_add             date NOT NULL COMMENT 'Время создания',
 last_activity_time datetime NULL COMMENT 'Время последней активности на сайте',
UNIQUE KEY (email),
FOREIGN KEY (city_id) REFERENCES cities(id),
FULLTEXT KEY (name) COMMENT 'Индекс для поиска исполнителей по имени'
) COMMENT='Пользователи';

CREATE TABLE profiles
(
 id                 int NOT NULL AUTO_INCREMENT PRIMARY KEY,
 user_id            int NOT NULL COMMENT 'Пользователь',
 about              varchar(255) NULL COMMENT 'О себе',
 address            varchar(255) NULL COMMENT 'Адрес',
 bd                 date NULL COMMENT 'День рождения',
 categories         varchar(255) NULL COMMENT 'Выбранные специализации',
 phone              varchar(255) NULL COMMENT 'Номер телефона',
 skype              varchar(255) NULL COMMENT 'Логин в скайпе',
 telegram           varchar(255) NULL COMMENT 'Логин в телеграме',
 notifications      varchar(255) NULL COMMENT 'Список включенных типов уведомлений',
 views              int unsigned NULL COMMENT 'Просмотры профиля',
UNIQUE KEY (user_id),
FOREIGN KEY (user_id) REFERENCES users(id),
FULLTEXT KEY (categories) COMMENT 'Индекс для фильтрации исполнителей по категориям'
) COMMENT='Профили пользователей';

CREATE TABLE bookmarks
(
 id             int NOT NULL AUTO_INCREMENT PRIMARY KEY,
 user_id        int NOT NULL COMMENT 'Пользователь',
 bookmarked_id  int NOT NULL COMMENT 'Избранный пользователь',
FOREIGN KEY (user_id) REFERENCES users(id),
FOREIGN KEY (bookmarked_id) REFERENCES users(id)
) COMMENT='Избранное пользователей';

CREATE TABLE tasks
(
 id                int NOT NULL AUTO_INCREMENT PRIMARY KEY,
 customer_id       int NOT NULL COMMENT 'Кто опубликовал',
 city_id           int NOT NULL COMMENT 'Указанный город',
 name              varchar(255) NOT NULL COMMENT 'Название',
 description       text NOT NULL COMMENT 'Описание',
 category_id       int NOT NULL COMMENT 'Выбранная категория',
 files             varchar(255) NULL COMMENT 'Пути к файлам',
 address           varchar(255) NULL COMMENT 'Адрес',
 lat               decimal(10,8) NOT NULL COMMENT 'Широта',
 lng               decimal(11,8) NOT NULL COMMENT 'Долгота',
 budget            int unsigned NULL COMMENT 'Бюджет',
 expire            date NULL COMMENT 'Срок выполнения',
 status            varchar(255) NOT NULL DEFAULT 'new' COMMENT 'Статус',
 dt_add            date NOT NULL COMMENT 'Время создания',
 accepted_reply    int NULL COMMENT 'Выбранный отклик',
 executor_id       int NULL COMMENT 'Выбранный исполнитель',
FOREIGN KEY (category_id) REFERENCES categories(id),
FOREIGN KEY (city_id) REFERENCES cities(id),
FOREIGN KEY (customer_id) REFERENCES users(id),
FOREIGN KEY (executor_id) REFERENCES users(id)
) COMMENT='Задания';

CREATE TABLE opinions
(
 id           int NOT NULL AUTO_INCREMENT PRIMARY KEY,
 dt_add       date NOT NULL COMMENT 'Дата оставления отзыва',
 had_problems bool NOT NULL COMMENT 'Были ли проблемы с выполнением задания',
 description  text NULL COMMENT 'Комментарий заказчика',
 rate         tinyint unsigned NOT NULL COMMENT 'Оценка',
 task_id      int NOT NULL COMMENT 'Ссылка на задание',
 customer_id  int NOT NULL COMMENT 'Кто оставил отзыв',
 executor_id  int NOT NULL COMMENT 'Кто выполнил работу',
FOREIGN KEY (task_id) REFERENCES tasks(id),
FOREIGN KEY (customer_id) REFERENCES users(id),
FOREIGN KEY (executor_id) REFERENCES users(id)
) COMMENT='Отзывы';

CREATE TABLE messages
(
 id           int NOT NULL AUTO_INCREMENT PRIMARY KEY,
 time_sent    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время отправки сообщения',
 message      text NOT NULL COMMENT 'Текст сообщения',
 task_id      int NOT NULL COMMENT 'Ссылка на задание',
 sender_id    int NOT NULL COMMENT 'Отправитель',
 recipient_id int NOT NULL COMMENT 'Получатель',
FOREIGN KEY (task_id) REFERENCES tasks(id),
FOREIGN KEY (sender_id) REFERENCES users(id),
FOREIGN KEY (recipient_id) REFERENCES users(id)
) COMMENT='Сообщения';

CREATE TABLE notifications
(
 id      int NOT NULL AUTO_INCREMENT PRIMARY KEY,
 task_id int NOT NULL COMMENT 'Ссылка на задание',
 user_id int NOT NULL COMMENT 'Кому уведомление',
 type    varchar(255) NOT NULL COMMENT 'Тип уведомления',
 is_seen bool NOT NULL DEFAULT 0 COMMENT 'Просмотрено ли',
FOREIGN KEY (task_id) REFERENCES tasks(id),
FOREIGN KEY (user_id) REFERENCES users(id)
) COMMENT='Уведомления';

CREATE TABLE replies
(
 id           int NOT NULL AUTO_INCREMENT PRIMARY KEY,
 task_id      int NOT NULL COMMENT 'Ссылка на задание',
 executor_id  int NOT NULL COMMENT 'Кто откликнулся',
 dt_add       date NOT NULL COMMENT 'Дата публикации отклика',
 rate         tinyint unsigned NOT NULL COMMENT 'Оценка выполненного задания',
 offer        int unsigned NULL COMMENT 'Указанная сумма',
 description  text NULL COMMENT 'Комментарий',
 is_declined  bool NOT NULL DEFAULT 0 COMMENT 'Отклонено ли',
FOREIGN KEY (task_id) REFERENCES tasks(id),
FOREIGN KEY (executor_id) REFERENCES users(id)
) COMMENT='Отклики на задания';

ALTER TABLE tasks ADD FOREIGN KEY (accepted_reply) REFERENCES replies(id);