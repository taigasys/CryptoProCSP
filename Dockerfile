FROM ubuntu:16.04

ENV container docker
# Переключаем Ubuntu в неинтерактивный режим — чтобы избежать лишних запросов
ENV DEBIAN_FRONTEND noninteractive
ENV TERM xterm
ENV LANG en_US.UTF-8

# Устанавливаем timezone
ENV TZ=Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Настройка systemd
RUN find /etc/systemd/system \
         /lib/systemd/system \
         -path '*.wants/*' \
         -not -name '*journald*' \
         -not -name '*systemd-tmpfiles*' \
         -not -name '*systemd-user-sessions*' \
         -exec rm \{} \;
RUN systemctl set-default multi-user.target

# Добавление репозитория php и установка окружения
RUN echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu trusty main" >> /etc/apt/sources.list && \
	apt-key adv --keyserver keyserver.ubuntu.com --recv-key E5267A6C && \
	apt update && \
    # Пакеты окружения
	apt install --no-install-recommends -y locales curl ca-certificates \
        # Пакеты необходимые для установки и компиляции
        alien libxml2 libxml2-dev libboost-dev build-essential \
        # Пакеты для отладки
        # wget strace nano \
        # PHP необходимой версии 5.6
        php5.6 php5.6-cli php5.6-dev php5.6-json php5.6-mcrypt php5.6-curl php5.6-SimpleXML && \
    # Обновление локали
	locale-gen en_US en_US.UTF-8 && dpkg-reconfigure locales

ADD dist /root/
ADD conf /root/

RUN mkdir /www && \
	cd /root && \
	chmod +x start.sh && \
	# Установка крипторо
	tar -xf linux-amd64_deb.tgz && \
	cd /root/linux-amd64_deb && \
	./install.sh && \
	dpkg -i lsb-cprocsp-kc2* && \
	# Устанвока КриптоПро ЭЦП Browser plug-in содержит необходимые библиотеки для компиляции и исходники расширений
	cd /root && \
	tar -xf cades_linux_amd64.tar.gz && \
	# При билде -kci выдает ошибки из-за не запущенного сервиса крипторо,
	# но необходим для компиляции модуля PHP, переустанавливается скриптом запуска
	alien -i cprocsp-pki-2.0.0-amd64-cades.rpm && \
	#alien -kci cprocsp-pki-2.0.0-amd64-plugin.rpm && \
	alien -kci lsb-cprocsp-devel-4.0.0-4.noarch.rpm && \
    # Подготовка исходников PHP к компиляции модуля
	tar -xf php-5.6.30.tar.bz2 && \
	cd /root/php-5.6.30 && \
	# Может выдать ошибки о недостатке пакетов, нужно доуснатовить (дописать на 34 строке)
	./configure && \
    # Компиляция модуля PHP
	cd .. && \
	cp Makefile.unix /opt/cprocsp/src/phpcades && \
	ln -s /opt/cprocsp/lib/amd64/libcppcades.so.2 /opt/cprocsp/lib/amd64/libcppcades.so && \
	cd /opt/cprocsp/src/phpcades/ && \
	eval `/opt/cprocsp/src/doxygen/CSP/../setenv.sh --64`; make -f Makefile.unix && \
	# Установка и включения модуля PHP
	ln -s /opt/cprocsp/src/phpcades/libphpcades.so $(php5.6 -i | grep 'extension_dir => ' | awk '{print $3}')/libcppcades.so && \
	echo "extension=libcppcades.so" > /etc/php/5.6/cli/conf.d/20-libcppcades.ini && \
	php5.6 -i | grep CSP && \
	# Подмена родной криптопрошной библиотеки на стандартную.
	# Из-за глюков с редиректом на http при запросе сертификатов при проверке подписей
	/opt/cprocsp/sbin/amd64/cpconfig -ini \\config\\apppath -add string libcurl.so /usr/lib/x86_64-linux-gnu/libcurl.so.4

# Порт для PHP API
EXPOSE 80

# Каталог с PHP файлами API
VOLUME ["/www"]
# Каталоги для systemd
VOLUME ["/sys/fs/cgroup"]
VOLUME ["/run"]

CMD ["/root/start.sh"]