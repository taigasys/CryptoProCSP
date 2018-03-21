CryptoPro CSP 4 в контейнере
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
Проверка текущей лицензии  
```
/opt/cprocsp/sbin/amd64/cpconfig -license -view
```
Установка лицензии    
```
/opt/cprocsp/sbin/amd64/cpconfig -license -set XXXXX-XXXXX-XXXXX-XXXXX-XXXXX
```

Зачем это нужно
---------------
Данный контейнер не претендует на истину в последней инстанции.   
Является больше пособием для развертывания CryptoPro CSP 4 в среде Linux:Ubuntu  
Для продакшена его можно и нужно допиливать.  

Файлы и папки
-------------
    /conf - кнфигурационные файлы 
        Makefile.unix - Файл сборки расширения
        getRootAndCACert.php - скрипт получения корневых и УЦ сертификатов, необходимы для проверки подписей
        start.sh - скрипт инициализации и запуска необходимых сервисов в контейнере
        
    /dist - дистрибутивы программ и файлы необходимые для компиляции и запуска приложений описаны в установке.
        linux-amd64_deb.tgz - дистрибутив CryptoPro CSP 4
        cades_linux_amd64.tar.gz - дистрибутива КриптоПро ЭЦП Browser plug-in
        php-5.6.30.tar.bz2 - исходники необходимой версии PHP
        
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

После чего запускаем сборку докера:  
```
docker build -t cprocsp ./
```
  
Если сборка прошла успешно можно запустить командой в каталоге проекта:  
```
run.sh
```   
  
Спустя 10-15 секунд на http://127.0.0.1:8095 можно посмотреть демо.  

Если что то пошло не так можно войти в контейнер и попытаться это решить:  
```
docker exec -it cprocsp bash
```

Корневые сертификаты и сертификаты УЦ
----------------------------------------
Боевые сертификаты необходимы для проверки реальных сертификатов  
Качается тут https://e-trust.gosuslugi.ru/MainCA  

Сертификаты УЦ представлены в реестре http://e-trust.gosuslugi.ru/CA.  
Там же можно загрузить список в xml представлении http://e-trust.gosuslugi.ru/CA/DownloadTSL?schemaVersion=0

Установка всех сертификатов в папке с расширением .cer  
```
find . -name *.cer -exec /opt/cprocsp/bin/amd64/certmgr -inst -store uroot -file {} \;
```

Тестовые сертификаты
--------------------
Можно использовать для тестирования подписи.
Тестовые сертификаты генерируются и качаются на тестовом УЦ http://www.cryptopro.ru/certsrv/  
Коневой сертификат тестового УЦ прямая ссылка http://www.cryptopro.ru/products/cades/plugin/get_2_0
Установка тестового корневого сертификата УЦ  
``` 
/opt/cprocsp/bin/amd64/certmgr -inst -store uroot -file /root/certnew.cer
```

Команды генерации хранилища могут вызывать ошибки при установленном пакете lsb-cprocsp-kc2*  
решается его удалением и установкой после генерации)  

Генерация запроса на сертификат (Должен быть установлен коневой сертификат тестового УЦ)  
```
/opt/cprocsp/bin/amd64/cryptcp -creatrqst -dn "E=mail@bk.ru, CN=test" -ku -cont '\\.\HDIMAGE\test' -provtype 75 main.req
```
Получившийся файл вставляем в поле "Сохраненный запрос" тут http://www.cryptopro.ru/certsrv/certrqxt.asp и качаем сертификат.  
Показать содержимое для копирования  
```
cat main.req
```

Установка сгенерированного в УЦ сертификата.
```
/opt/cprocsp/bin/amd64/certmgr -inst -file /www/certnew.cer -cont '\\.\HDIMAGE\test'
```

Описание API
-----------
Для проверки открепленной подписи шлем пост запрос на ``` /vsignf.php ```  
С двума параметрами:  
 - hash - Хеш документа по GOST`у  
 - sign - подпись
 
В ответ прилетит json:   
Если ошибка до проверки сертификата:   
```
{
    "status":1,
    "mess":"Описание ошибки"
}
```  

Если проверка прошла:
```
{
    "status":0, // 0 - все хорошо
    "data":{
        "verify":0, // Проверка 1 - пройдена подпись валидна, 0 - не пройдена подпсись не валидна  
        "verifyMessage":"сообщение об рошибки с кодом (0x800B010A)",
        "signers":[ // подписанты
            {
                "signingTime":"", //дата подписания
                "cert":{ // данные сертификата подписи
                    "validToDate":"16.05.2018 11:56:29", // Валиден до
                    "validFromDate":"16.02.2018 11:46:29",  // Валиден от
                    "subjectName":{ // Данные подписанта
                        "C":"RU",
                        "CN":"Test"
                    },
                    "issuerName":{// Данные УЦ выдавшего сертификат
                        "CN":"CRYPTO-PRO Test Center 2",
                        "O":"CRYPTO-PRO LLC",
                        "L":"Moscow",
                        "C":"RU",
                        "E":"support@cryptopro.ru"
                    },
                    "certSerial":"120025DA...00025DAA0" // Серийный сертификата
                }
            }
        ]
    }
}
```

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

Страница проверки работы плагина  
http://www.cryptopro.ru/sites/default/files/products/cades/demopage/main.html    