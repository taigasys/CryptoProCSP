#!/bin/bash

stop_requested=false
trap "stop_requested=true" TERM INT

wait_signal() {
    while ! $stop_requested; do
        sleep 1
    done
}

wait_exit() {
    while pidof $1; do
        sleep 1
    done
}

/sbin/init
# Ждем инициализации на всякий
sleep 5 && \
# Запускаем сервис криптопро - криптопровайдер
/etc/init.d/cprocsp start && \
cd /root && \
# Доустановка пакета не проходит при былде
alien -kci cprocsp-pki-2.0.0-amd64-cades.rpm && \
# Создание хранилища текущего пользователя
/opt/cprocsp/sbin/amd64/cpconfig -hardware reader -add HDIMAGE store && \
# Скачивание корневых и УЦ сертификатов
php5.6 getRootAndCACerts.php && \
# Установка коневых сертификатов
find ./root_certs/ -name *.cer -exec /opt/cprocsp/bin/amd64/certmgr -inst -store uroot -file {} \; && \
# Установка УЦ сертификатов
find ./ca_certs/ -name *.cer -exec /opt/cprocsp/bin/amd64/certmgr -inst -store uroot -file {} \; && \
# Запуск web сервера для ответов по API
php -S 0.0.0.0:80 -t /www

# Ждём SIGTERM или SIGINT
wait_signal

# Запрашиваем остановку
/etc/init.d/cprocsp stop

# Ждём завершения процессов по их названию
wait_exit "cprocsp"
