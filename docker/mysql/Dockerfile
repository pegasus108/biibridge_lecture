FROM --platform=linux/amd64 mysql:5.7
COPY ./docker/mysql/initdb.d /docker-entrypoint-initdb.d
CMD ["mysqld", "--default-time-zone=Asia/Tokyo"]