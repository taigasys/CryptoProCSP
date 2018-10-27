#!/bin/bash

stop_requested=false
trap "stop_requested=true" SIGTERM SIGINT

wait_signal() {
    while ! $stop_requested; do
        sleep 1;

        cryptsrv=`pidof cryptsrv`
        if [ -z "$cryptsrv" ]
        then
            stop_requested=true
            echo "Error cryptsrv not required stopping"
        fi

        php=`pidof php`
        if [ -z "$php" ]
        then
            stop_requested=true
            echo "Error php not required stopping"
        fi
    done
}

wait_exit() {
    while pidof $1; do
        sleep 1
        echo "Wait services"
    done
}

/sbin/init
# Ждем инициализации на всякий
sleep 5 && \

# Запускаем сервис криптопро - криптопровайдер
/etc/init.d/cprocsp start && \
cd /root && \

# Доустановка пакета не проходит при билде
alien -kci cprocsp-pki-2.0.0-amd64-cades.rpm && \

# Создание хранилища текущего пользователя
/opt/cprocsp/sbin/amd64/cpconfig -hardware reader -add HDIMAGE store && \

# Скачивание корневых и УЦ сертификатов
php5.6 getRootAndCACerts.php && \

# Установка корневых сертификатов
find ./root_certs/ -name "*.cer" -exec /opt/cprocsp/bin/amd64/certmgr -inst -store uroot -file {} \; > root.log && \

# Установка УЦ сертификатов
find ./ca_certs/ -name "*.cer" -exec /opt/cprocsp/bin/amd64/certmgr -inst -store uroot -file {} \; > ca.log && \

# Запуск php-web сервер для ответов по API
sh -c "nohup php -S 0.0.0.0:80 -t /www &" && \

# Ждём SIGTERM или SIGINT
wait_signal

echo "Stoping services"

# Запрашиваем остановку запущенных процессов

if pidof "cryptsrv" > /dev/null
then
    /etc/init.d/cprocsp stop
fi

if pidof "php" > /dev/null
then
    kill $(pidof php)
fi

# Ждём завершения процессов по их названию
wait_exit "cryptsrv php"
