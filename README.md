CryptoPro CSP 4 в контенере
===========================
Контейнер включающий в себя:  
CryptoPro CSP 4 полностью запускаемый как криптопровайдер.  
PHP расширение для работы с подписями через интерфейсы CryptoPro CSP 4  
Небольшое API для работы с подписями по http
 
Лицензия CryptoPro CSP 4
------------------------
При установке CryptoPro автоматически выдается лицензия на тестовый период 3 месяца.
Необходимо установить нормальную лицензию.  
По лицензированию и докеру можно почитать на форуме https://www.cryptopro.ru/forum2/default.aspx?g=posts&t=12149.

Зачем это нужно
---------------
Данный контейнер не претендует на истину в последней инстанции.   
Является больше пособием для развертывания CryptoPro CSP 4 в среде Linux:Ubuntu  
Для продакшена его можно и нужно допиливать.  

Файлы и папки
-------------
    /conf - кнфигурационные файлы 
        Makefile.unix - Файл сборки расширения
        start.sh - скрипт запуска необходимых сервисов в контенере
    /dist - дистрибутивы программ и файлы необходимые для компиляции и запуска приложений описаны в установке.
        linux-amd64_deb.tgz - дистрибутив CryptoPro CSP 4
        cades_linux_amd64.tar.gz - дистрибутива КриптоПро ЭЦП Browser plug-in
        
        php-5.6.30.tar.bz2 - исходники необходимой версии PHP
        
        #Тестовые сертификаты устанавливаются для тестирования
        certnew.cer - корневой сертификат тестового УЦ КриптоПро 
        personal.cer - персональный сертификат сгенериный на тестовом УЦ для
        
        #Боевые сертификаты необходимы для проверки реальных сертификатов 
        GUC.crt - корневой сертификат минсвязи
    /www - каталог с PHP API
    /docker-compose-dev.yml - пример конфига для docker-compose 
    /Dockerfile - Файл сборки контенера
    /run.sh - скрипт запуска контейнера со всеми необходимыми опциями
    
Установка
---------
Предполагается что у вас установлен Docker.

Первым шагом необходимо скачать все дистрибутивы программ и сертификаты в папку dist 

linux-amd64_deb.tgz  
После регистрации https://www.cryptopro.ru/user/register качается на сайте https://www.cryptopro.ru/products/csp/downloads  
Включено 3 месяца пробной лицензии.  
        
cades_linux_amd64.tar.gz  
Дистрибутив КриптоПро ЭЦП Browser plug-in без регистрации качается тут http://www.cryptopro.ru/products/cades/plugin  
Прямая ссылка на 2 версию http://www.cryptopro.ru/products/cades/plugin/get_2_0  

php-5.6.30.tar.bz2  
Исходники необходимой версии PHP нужны для сборки берутся тут http://php.net/releases/  
Версию можно посмотреть командой php -v  

Тестовые сертификаты генерируются и качаются на тестовом УЦ http://www.cryptopro.ru/certsrv/  
certnew.cer прямая ссылка http://www.cryptopro.ru/products/cades/plugin/get_2_0  
personal.cer  
Персональный сертификат генерируется тут http://www.cryptopro.ru/certsrv/certrqma.asp  
Для генерации необходимо чтобы на компьютере был установлен криптопровайдер, КриптоПро ЭЦП Browser plug-in и корневой сертификат тестового УЦ  
Необходим для запуска скриптов для тестирования подписания.  
Тут http://www.cryptopro.ru/sites/default/files/products/cades/demopage/main.html страница проверки работы плагина.  

Боевые сертификаты необходимы для проверки реальных сертификатов   
GUC.crt качается тут https://e-trust.gosuslugi.ru/MainCA  

После чего запускаем сборку докера:  
```docker build -t cprocsp ./```
  
Если сборка прошла успешно можно запустить командой в каталоге проекта:  
```run.sh```   
  
Спустя 10-15 секунд на http://127.0.0.1:8095 можно посмотреть демо.  

Если что то пошло не так можно войти в контейнер и попытаться это решить:  
```docker exec -it cprocsp bash```

Описание API
-----------
В работе

Ссылки которые очень помогли
----------------------------
Инструкция по установке  
https://www.cryptopro.ru/forum2/default.aspx?g=posts&t=11300  

По установке плагина  
http://www.cryptopro.ru/forum2/default.aspx?g=posts&t=10928  
https://www.cryptopro.ru/forum2/default.aspx?g=posts&t=11540

Плагин для PHP7  
https://www.cryptopro.ru/forum2/default.aspx?g=posts&m=82396#post82396  

Инструкция тестовый сертификат  
http://pushorigin.ru/cryptopro/test-cert-crypto-pro  

Установка плагина в FireFox 53^  
https://support.cryptopro.ru/index.php?/Knowledgebase/Article/View/223/0/podderzhk-npapi-plginov-v-firefox-versii-53-i-vyshe  