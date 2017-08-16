<?php
//namespace CProCSP;

/**
 * Автозагрузчик классов
 */
spl_autoload_register(function ($class) {
    $prefix = '';
    $base_dir = __DIR__ . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Разбирает строку параметров в массив
 * @param string $params Строка параметров "CN=213, O=123213"
 * @return array
 */
function parseParams($params){
    $res = [];
    preg_match_all("/([a-zA-Z]+)=(\".*?\"|.*?)\,/", $params . ',', $output_array);

    if (isset($output_array[1], $output_array[2])) {
        foreach ($output_array[1] as $i => $row) {

            $res[$output_array[1][$i]] = $output_array[2][$i];
        }
    }
    return $res;
}

$jsonReply = new lib\JsonReply();

if (!isset($_POST['hash'], $_POST['sign'])) {
    $jsonReply->sendError('Не переданы hash и sign');
}

$hash = $_POST['hash'];
$sign = $_POST['sign'];

try {
    /** @var CProCSP\CPHashedData $hd */
    $hd = new CPHashedData();
    $hd->set_Algorithm(HASH_ALGORITHM_GOSTR_3411);
    $hd->SetHashValue($hash);

    /** @var CProCSP\CPSignedData $sd */
    $sd = new CPSignedData();

    //Для получения объекта подписи необходимо задать любой контент. Это баг на форуме есть инфа.
    $sd->set_Content('123');

} catch (\Exception $e) {
    $jsonReply->sendError('Ошибка инициализации, ' . $e->getMessage());
}

//Проверка подписи
try {
    $sd->VerifyHash($hd, $sign, CADES_BES);
    $jsonReply->data->verify = 1;
} catch (\Exception $e) {
    $jsonReply->data->verify = 0;
    $jsonReply->data->verifyMess = $e->getMessage();
}

//Получение дополнительных данных
try {
    $res = [];
    //$sd->get_Certificates();
    /** @var \CProCSP\CPSigners $signers */
    $signers = $sd->get_Signers();
    if (null !== $signers) {
        $signersCount = $signers->get_Count();

        for ($i = 1; $i <= $signersCount; $i++) {
            $signer = $signers->get_Item($i);
            $res[$i-1]['signingTime'] = $signer->get_SigningTime();

            /** @var \CProCSP\CPcertificate $cert */
            $cert = $signer->get_Certificate();
            $res[$i-1]['cert']['validToDate'] = $cert->get_ValidToDate();
            $res[$i-1]['cert']['validFromDate'] = $cert->get_ValidFromDate();
            $res[$i-1]['cert']['subjectName'] = parseParams($cert->get_SubjectName());
            $res[$i-1]['cert']['issuerName'] = parseParams($cert->get_IssuerName());
        }

        $jsonReply->data->signers = $res;
    }

} catch (\Exception $e) {
    $jsonReply->data->propertyMess = $e->getMessage();
}

$jsonReply->sendData();
