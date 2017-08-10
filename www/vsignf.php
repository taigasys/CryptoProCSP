<?php
namespace CProCSP;

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

$jsonReply = new JsonReply();

if (!isset($_POST['hash'], $_POST['sign'])) {
    $jsonReply->sendError('Не переданы hash и sign');
}

$hash = $_POST['hash'];
$sign = $_POST['sign'];

try {
    /** @var \CProCSP\CPHashedData $hd */
    $hd = new CPHashedData();
    $hd->set_Algorithm(HASH_ALGORITHM_GOSTR_3411);
    $hd->SetHashValue($hash);

    /** @var \CProCSP\CPSignedData $sd */
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
    /** @var \CProCSP\CPSigners $signers */
    $signers = $sd->get_Signers();

    for ($i = 1; $i <= $signers; $i ++) {
        $signer = $signers->get_Item($i);
        $res[$i]['signingTime'] = $signer->get_SigningTime();

        /** @var \CProCSP\CPcertificate $cert */
        $cert = $signer->get_Certificate();
        $res[$i]['cert']['validToDate'] = $cert->get_ValidToDate();
        $res[$i]['cert']['certValidFromDate'] = $cert->get_ValidFromDate();
        $res[$i]['cert']['subjectName'] = $cert->get_SubjectName();
        $res[$i]['cert']['issuerName'] = $cert->get_IssuerName();
    }

    $jsonReply->data->signers = $signers;
    $jsonReply->data->property = $res;

} catch (\Exception $e) {
    $jsonReply->data->propertyMess = $e->getMessage();
}

$jsonReply->sendData();
