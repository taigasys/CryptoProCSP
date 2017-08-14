<?php
/**
 * Требует установки тестовых сертификатов как УЦ так и личного
 */

//Вспомогательные функции предварительной инициализации
function SetupStore($location, $name, $mode)
{
    $store = new CPStore();
    $store->Open($location, $name, $mode);
    return $store;
}

function SetupCertificates($location, $name, $mode)
{
    $store = SetupStore($location, $name, $mode);
    $certs = $store->get_Certificates();
    return $certs;
}

function SetupCertificate($location, $name, $mode, $find_type, $query, $valid_only, $number)
{
    $certs = SetupCertificates($location, $name, $mode);
    if ($find_type != null) {
        $certs = $certs->Find($find_type, $query, $valid_only);
        return $certs->Item($number);
    } else {
        $cert = $certs->Item($number);
        return $cert;
    }
}

function test_CPSignedData_Sign_Verify()
{
    try {
        $content = "test content";
        $address = "http://testca.cryptopro.ru/tsp/tsp.srf";
        $cert = SetupCertificate(
            CURRENT_USER_STORE,
            "My",
            STORE_OPEN_READ_ONLY,
            CERTIFICATE_FIND_SUBJECT_NAME,
            "test", //Идентификатор сертификата в строке CN
            0,
            1
        );

        if (!$cert) {
            return "Certificate not found";
        }

        $signer = new CPSigner();
        $signer->set_TSAAddress($address);
        $signer->set_Certificate($cert);

        $sd = new CPSignedData();
        $sd->set_Content($content);

        $sm = $sd->Sign($signer, 0, STRING_TO_UCS2LE);
        printf("Signature is:\n");
        printf($sm);
        printf("\n");
        $sd->Verify($sm, 0, VERIFY_SIGNATURE_ONLY);
        return 1;
    } catch (Exception $e) {
        printf($e->getMessage());
        return 0;
    }
}

if (test_CPSignedData_Sign_Verify() == 1) {
    printf("TEST OK\n");
} else {
    printf("TEST FAIL\n");
}

?>