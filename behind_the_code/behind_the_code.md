## 04. Set up the database

Создание БД globe_bank_cms примерно так:  

    CREATE DATABASE globe_bank;

    GRANT ALL PRIVILEGES ON globe_bank.* TO 'webuser'@'localhost'
    IDENTIFIED BY 'secretpassword';

Перенос таблиц в командной строке
    
    # Import subjects and pages tables and data
    mysql -u webuser -p globe_bank < path/to/file.sql

## 