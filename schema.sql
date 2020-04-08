CREATE TABLE categories
(
 id    int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 value varchar(255) NOT NULL COMMENT 'Буквенный id категории для подстановки в value' ,
 name  varchar(255) NOT NULL COMMENT 'Название категории'
) COMMENT='Категории заданий';

CREATE TABLE cities
(
 id    int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 value varchar(255) NOT NULL COMMENT 'Буквенный id города для подстановки в value' ,
 name  varchar(255) NOT NULL COMMENT 'Название города'
) COMMENT='Города';

CREATE TABLE users
(
 id                 int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 email              varchar(255) NOT NULL COMMENT 'Почта пользователя' ,
 name               varchar(255) NOT NULL COMMENT 'Имя и фамилия пользователя' ,
 city_id            int NOT NULL COMMENT 'Выбранный пользователем город' ,
 password           varchar(255) NOT NULL COMMENT 'Хэш пароля' ,
 birthday           date NULL COMMENT 'День рождения' ,
 description        varchar(255) NULL COMMENT 'О себе' ,
 categories         varchar(255) NULL COMMENT 'Выбранные специализации' ,
 phone              varchar(255) NULL COMMENT 'Номер телефона' ,
 skype              varchar(255) NULL COMMENT 'Логин в скайпе' ,
 telegram           varchar(255) NULL COMMENT 'Логин в телеграме' ,
 notifications      varchar(255) NULL COMMENT 'Список включенных типов уведомлений' ,
 views              int unsigned NULL COMMENT 'Просмотры профиля' ,
 last_activity_time datetime NULL COMMENT 'Время последней активности на сайте' ,
UNIQUE KEY (email),
FOREIGN KEY (city_id) REFERENCES cities(id),
FULLTEXT KEY (name) COMMENT 'Индекс для поиска исполнителей по имени'
) COMMENT='Пользователи';

CREATE TABLE tasks
(
 id                int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 customer_id       int NOT NULL COMMENT 'Кто опубликовал' ,
 city_id           int NOT NULL COMMENT 'Указанный город' ,
 title             varchar(255) NOT NULL COMMENT 'Название' ,
 description       varchar(255) NOT NULL COMMENT 'Описание' ,
 category_id       int NOT NULL COMMENT 'Выбранная категория' ,
 files             varchar(255) NULL COMMENT 'Пути к файлам' ,
 location          varchar(255) NULL COMMENT 'Координаты' ,
 budget            int unsigned NULL COMMENT 'Бюджет' ,
 deadline          datetime NULL COMMENT 'Срок выполнения' ,
 status            varchar(255) NOT NULL DEFAULT 'new' COMMENT 'Статус' ,
 time_created      datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время создания' ,
 accepted_response int NULL COMMENT 'Выбранный отклик' ,
FOREIGN KEY (category_id) REFERENCES categories(id),
FOREIGN KEY (city_id) REFERENCES cities(id),
FOREIGN KEY (customer_id) REFERENCES users(id)
) COMMENT='Задания';

CREATE TABLE feedback
(
 id           int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 had_problems bool NOT NULL COMMENT 'Были ли проблемы с выполнением задания' ,
 comment      varchar(255) NULL COMMENT 'Комментарий заказчика' ,
 rating       tinyint unsigned NOT NULL COMMENT 'Оценка выполненного задания' ,
 task_id      int NOT NULL COMMENT 'Ссылка на задание' ,
 customer_id  int NOT NULL COMMENT 'Кто оставил отзыв' ,
 executor_id  int NOT NULL COMMENT 'Кто выполнил работу' ,
FOREIGN KEY (task_id) REFERENCES tasks(id),
FOREIGN KEY (customer_id) REFERENCES users(id),
FOREIGN KEY (executor_id) REFERENCES users(id)
) COMMENT='Отзывы';

CREATE TABLE messages
(
 id           int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 time_sent    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время отправки сообщения' ,
 task_id      int NOT NULL COMMENT 'Ссылка на задание' ,
 sender_id    int NOT NULL COMMENT 'Отправитель' ,
 recipient_id int NOT NULL COMMENT 'Получатель' ,
FOREIGN KEY (task_id) REFERENCES tasks(id),
FOREIGN KEY (sender_id) REFERENCES users(id),
FOREIGN KEY (recipient_id) REFERENCES users(id)
) COMMENT='Сообщения';

CREATE TABLE notifications
(
 id      int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 task_id int NOT NULL COMMENT 'Ссылка на задание' ,
 user_id int NOT NULL COMMENT 'Кому уведомление' ,
 type    varchar(255) NOT NULL COMMENT 'Тип ведомления' ,
 is_seen bool NOT NULL DEFAULT 0 COMMENT 'Просмотрено ли' ,
FOREIGN KEY (task_id) REFERENCES tasks(id),
FOREIGN KEY (user_id) REFERENCES users(id)
) COMMENT='Уведомления';

CREATE TABLE responses
(
 id           int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 task_id      int NOT NULL COMMENT 'Ссылка на задание' ,
 executor_id  int NOT NULL COMMENT 'Кто откликнулся' ,
 time_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время публикации отклика' ,
 sum          int unsigned NULL COMMENT 'Указанная сумма' ,
 comment      varchar(255) NULL COMMENT 'Комментарий' ,
 is_declined  bool NOT NULL DEFAULT 0 COMMENT 'Отклонено ли' ,
FOREIGN KEY (task_id) REFERENCES tasks(id),
FOREIGN KEY (executor_id) REFERENCES users(id)
) COMMENT='Отклики на задания';

ALTER TABLE tasks ADD FOREIGN KEY (accepted_response) REFERENCES responses(id);