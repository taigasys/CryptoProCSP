<?php
//$xml = file_get_contents('TSLExt.1.0.xml');
$xml = file_get_contents('http://e-trust.gosuslugi.ru/CA/DownloadTSL?schemaVersion=0');
$ucs = new SimpleXMLElement($xml);
$now = new DateTime();

$i = 0;
foreach ($ucs->{'УдостоверяющийЦентр'} as $uc){
    echo $i , "\n";
    echo 'Название:', $uc->{'Название'} , "\n";
    echo 'Статус: ', $uc->{'СтатусАккредитации'}->{'Статус'} , "\n";
    if ((string)$uc->{'СтатусАккредитации'}->{'Статус'} !== 'Действует') {
        continue;
    }
    foreach ($uc->{'ПрограммноАппаратныеКомплексы'}->{'ПрограммноАппаратныйКомплекс'} as $compl) {
        echo 'Комплекс: ', $compl->{'Псевдоним'} , "\n";
        foreach ($compl->{'КлючиУполномоченныхЛиц'}->{'Ключ'} as $keys) {

            foreach ($keys->{'Сертификаты'}->{'ДанныеСертификата'} as $cert) {
                $dateTo = DateTime::createFromFormat(DateTime::ATOM, (string)$cert->{'ПериодДействияДо'});

                if ($dateTo > $now){
                    echo "\n";
                    echo 'КомуВыдан: ', $cert->{'КомуВыдан'} , "\n";
                    echo 'ПериодДействияДо: ', $cert->{'ПериодДействияДо'} , "\n";
                    echo 'Данные: ', $cert->{'Данные'} , "\n";

                    //file_put_contents(__DIR__ . '/ca_certs/' . $cert->{'СерийныйНомер'} . '.cer', $cert->{'Данные'});
                }
            }
        }
    }

    echo "\n\n";

    $i += 1;
}
