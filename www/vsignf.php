<?php
namespace CProCSP;

//$hash = hash('gost-crypto', file_get_contents('test_certs/anketa.xlsx'));
//$sign = file_get_contents('test_certs/anketa.xlsx.sgn');
header('Content-Type: application/json');
if (!isset($_POST['hash'], $_POST['sign'])) {
    echo '{"state":"1", "mess":"Не переданы hash и sign"}';
    die();
}

$hash = $_POST['hash'];
$sign = $_POST['sign'];

try {
/** @var \CProCSP\CPHashedData $hd */
$hd = new CPHashedData();
$hd->set_Algorithm(HASH_ALGORITHM_GOSTR_3411);
$hd->SetHashValue($hash);

$sd = new CPSignedData();

} catch(Exception $e) {
    echo '{"state":"1", "mess":"Ошибка инициализации ', $e->getMessage(), '"}';
}

try {
    $sd->VerifyHash($hd, $sign, CADES_BES);

    echo '{"state":"0"}';
} catch(Exception $e) {
    echo '{"state":"1", "mess":"Ошибка проверки ', $e->getMessage(), '"}';
}

