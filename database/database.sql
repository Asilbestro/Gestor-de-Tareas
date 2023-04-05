CREATE DATABASE IF NOT EXISTS gestorsymfony;
USE gestorsymfony;

CREATE TABLE IF NOT EXISTS users(
    id int(255) auto_increment not null,
    role varchar(50),
    name varchar(100),
    surname varchar(200),
    email varchar(255) unique,
    password varchar (255),
    created_at datetime,
    CONSTRAINT pk_users PRIMARY KEY (id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS tasks(
    id int(255) auto_increment not null,
    user_id int(255) not null,
    title varchar(255),
    content text,
    priority varchar(20),
    hours int(100),
    created_at datetime,
    CONSTRAINT pk_tasks PRIMARY KEY (id),
    CONSTRAINT fk_tasks_users FOREIGN KEY (user_id) REFERENCES users(id)
)ENGINE=InnoDb;

INSERT INTO users VALUES (NULL, 'ROLE_USER', 'Agustin','Silbestro', 'asilbestro7@gmail.com', '13760642',CURTIME());
INSERT INTO users VALUES (NULL, 'ROLE_USER', 'matias','Silbestro', 'msilbestro@gmail.com', '13760642',CURTIME());
INSERT INTO users VALUES (NULL, 'ROLE_USER', 'martin','Silbestro', 'martinsilbestro@gmail.com', '13760642',CURTIME());

INSERT INTO tasks VALUES (NULL, 1, 'tarea','Contenido de prueba 1', 'high', 40,CURTIME());
INSERT INTO tasks VALUES (NULL, 2, 'tarea 2','Contenido de prueba 2', 'medium', 15,CURTIME());
INSERT INTO tasks VALUES (NULL, 1, 'tarea 3','Contenido de prueba 3', 'low', 60,CURTIME());

