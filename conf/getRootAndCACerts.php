<?php
echo 'Получение коневых сертификатов' , "\n";
if(!@mkdir(__DIR__ . '/root_certs/') && !is_dir(__DIR__ . '/root_certs/')){
    echo 'Ошибка создание директории root сертификатов' , "\n";
    die();
}

$html = file_get_contents('http://e-trust.gosuslugi.ru/MainCA');
preg_match_all('/.*\/Shared\/DownloadCert\?thumbprint=(.*)\"\>/', $html, $output_array);
if (!isset($output_array[1])){
    echo 'Ссылки на сертификаты не найдены' , "\n";
    die();
}
$count = 0;
foreach ($output_array[1] as $link){
    copy(
        'http://e-trust.gosuslugi.ru/Shared/DownloadCert?thumbprint=' . $link,
        __DIR__ . '/root_certs/' . $link . '.cer'
    );
    $count++;
}
unset($html);
echo 'Загруженено ' . $count . "\n";

echo 'Получение сертификатов акредитованных УЦ' , "\n";
if(!@mkdir(__DIR__ . '/ca_certs/') && !is_dir(__DIR__ . '/ca_certs/')){
    echo 'Ошибка создание директории CA сертификатов' , "\n";
    die();
}

$xml = file_get_contents('http://e-trust.gosuslugi.ru/CA/DownloadTSL?schemaVersion=0');
$ucs = new SimpleXMLElement($xml);
$now = new DateTime();
$count = 0;

foreach ($ucs->{'УдостоверяющийЦентр'} as $uc){
    if ((string)$uc->{'СтатусАккредитации'}->{'Статус'} !== 'Действует') {
        continue;
    }
    foreach ($uc->{'ПрограммноАппаратныеКомплексы'}->{'ПрограммноАппаратныйКомплекс'} as $compl) {
        foreach ($compl->{'КлючиУполномоченныхЛиц'}->{'Ключ'} as $keys) {
            foreach ($keys->{'Сертификаты'}->{'ДанныеСертификата'} as $cert) {
                $dateTo = DateTime::createFromFormat(DateTime::ATOM, (string)$cert->{'ПериодДействияДо'});
                if ($dateTo > $now){//Дата окончания действия сертификата больше текущей
                    file_put_contents(
                        __DIR__ . '/ca_certs/' . $cert->{'СерийныйНомер'} . '.cer',
                        $cert->{'Данные'}
                    );
                }
            }
        }
    }
    $count++;
}
echo 'Загруженено ' . $count . "\n";
