DROP DATABASE IF EXISTS sample_db;
CREATE DATABASE sample_db DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE USER 'sample'@'%' IDENTIFIED BY 'sample';
GRANT ALL ON sample_db.* TO 'sample'@'%' IDENTIFIED BY 'sample';
FLUSH PRIVILEGES;
