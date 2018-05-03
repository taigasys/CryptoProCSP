<?php
/**
 * Проверка прикрепленной подписи
 */
use lib\ErrorCodes;

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
 * 
 * @param string $params Строка параметров "CN=213, O=123213"
 * @return array
 */
function parseParams($params)
{
    $res = [];
    preg_match_all('/([a-zA-Z]+)=(\".*?\"|.*?),/', $params . ',', $output_array);

    if (isset($output_array[1], $output_array[2])) {
        foreach ($output_array[1] as $i => $row) {
            $res[$output_array[1][$i]] = $output_array[2][$i];
        }
    }
    return $res;
}

$jsonReply = new lib\JsonReply();

if (file_exists(__DIR__ . 'service.time')) {
    $jsonReply->sendError('Сервисные работы, попробуйте позже.');
}

if (!isset($_POST['sign'])) {
    $jsonReply->sendError('Не передан sign');
}

$sign = $_POST['sign'];
file_put_contents('sign.dat', $sign);
try {
    /** @var CProCSP\CPSignedData $sd */
    $sd = new CPSignedData();
    $sd->set_Content('123');

} catch (\Exception $e) {
    $jsonReply->sendError('Ошибка инициализации, ' . $e->getMessage());
}

//Проверка подписи
try {
    //$sd->Verify($sign, 0x01, 0); Выдает ошибку Invalid Signature. (0x80090006)
    // Инфа на форуме https://www.cryptopro.ru/forum2/default.aspx?g=posts&t=12002
    $sd->VerifyCades($sign, 0x01, 0);
    $jsonReply->data->verify = 1;
} catch (\Exception $e) {
    $jsonReply->data->verify = 0;
    $jsonReply->data->verifyMessage = ErrorCodes::getMessage($e->getCode(), $e->getCode() . '--' . $e->getMessage());
}

//Получение дополнительных данных
try {
    /** @var \CProCSP\CPSigners $signers */
    $signers = $sd->get_Signers();
} catch (\Exception $e) {
    $signers = null;
    $jsonReply->data->signersMessage = 'Подписанты не найдены';
}

$signersCount = 0;
if (null !== $signers) {
    try {
        $signersCount = $signers->get_Count();
    } catch (\Exception $e) {
        $signersCount = 0;
    }
}

if (null !== $signers && $signersCount > 0) {
    $res = [];

    for ($i = 1; $i <= $signersCount; $i++) {

        $signer = $signers->get_Item($i);
        //TODO try HELL!
        try {
            $res[$i - 1]['signingTime'] = $signer->get_SigningTime();
        } catch (\Exception $e) {
            $res[$i - 1]['signingTime'] = '';
        }

        try {
            /** @var \CProCSP\CPcertificate $cert */
            $cert = $signer->get_Certificate();
        } catch (\Exception $e) {
            $cert = null;
        }
        if (null !== $cert) {
            try {
                $res[$i - 1]['cert']['validToDate'] = $cert->get_ValidToDate();
            } catch (\Exception $e) {
                $res[$i - 1]['cert']['validToDate'] = '';
            }

            try {
                $res[$i - 1]['cert']['validFromDate'] = $cert->get_ValidFromDate();
            } catch (\Exception $e) {
                $res[$i - 1]['cert']['validFromDate'] = '';
            }

            try {
                $res[$i - 1]['cert']['subjectName'] = parseParams($cert->get_SubjectName());
            } catch (\Exception $e) {
                $res[$i - 1]['cert']['subjectName'] = '';
            }

            try {
                $res[$i - 1]['cert']['issuerName'] = parseParams($cert->get_IssuerName());
            } catch (\Exception $e) {
                $res[$i - 1]['cert']['issuerName'] = '';
            }

            try {
                $res[$i - 1]['cert']['certSerial'] = $cert->get_SerialNumber();
            } catch (\Exception $e) {
                $res[$i - 1]['cert']['certSerial'] = '';
            }
        }
    }

    $jsonReply->data->signers = $res;
}

$jsonReply->sendData();
