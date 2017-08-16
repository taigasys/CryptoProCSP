<?php

namespace CProCSP;

/**
 * Глобальные константы тип усовершенствованной подписи, например для CPSignedData::VerifyHash
 * @var int CADES_BES Тип подписи CAdES BES
 * @var int CADES_DEFAULT Тип подписи по умолчанию (CAdES-X Long Type 1)
 * @var int CADES_T Тип подписи CAdES T
 * @var int CADES_X_LONG_TYPE_1 Тип подписи CAdES-X Long Type 1
 */
const CADES_BES = 0x01;
const CADES_DEFAULT = 0x00;
const CADES_T = 0x05;
const CADES_X_LONG_TYPE_1 = 0x5D;

/**
 * Глобальные константы тип кодировки контента, например для CPSignedData::Sign
 * @var int ENCODE_ANY Data is saved as a base64-encoded string or a pure binary sequence.
 * This encoding type is used only for input data that has an unknown encoding type. Introduced in CAPICOM 2.0.
 * @var int ENCODE_BASE64 Data is saved as a base64-encoded string.
 * @var int ENCODE_BINARY Data is saved as a pure binary sequence.
 */
/*const ENCODE_ANY = 0xffffffff;
const ENCODE_BASE64 = 0;
const ENCODE_BINARY = 1;*/

/**
 * Глобальные константы тип проверки подписи, например для CPSignedData::Verify
 * @see https://msdn.microsoft.com/en-us/library/aa375740.aspx
 * @var int VERIFY_SIGNATURE_ONLY Проверка только подписи.
 * @var int VERIFY_SIGNATURE_AND_CERTIFICATE Проверка подписи и сертификата на основании которого создана подпись.
 */
const VERIFY_SIGNATURE_ONLY = 0;
const VERIFY_SIGNATURE_AND_CERTIFICATE = 1;

/**
 * Только описание! Для автокомплитов IDE и справки
 * CPSigners класс плагина предоставляет свойства и методы для работы с подписанным сообщением.
 * Расширяет интерфейс объекта CAPICOM.SignedData .
 *
 * @see http://cpdn.cryptopro.ru/default.asp?url=content/cades/interface_c_ad_e_s_c_o_m_1_1_i_c_p_signed_data.html
 * @see http://cpdn.cryptopro.ru/default.asp?url=content/cades/interface_c_ad_e_s_c_o_m_1_1_i_c_p_signed_data2.html
 * @see http://cpdn.cryptopro.ru/default.asp?url=content/cades/interface_c_ad_e_s_c_o_m_1_1_i_c_p_signed_data3.html
 * @see http://cpdn.cryptopro.ru/default.asp?url=content/cades/interface_c_ad_e_s_c_o_m_1_1_i_c_p_signed_data4.html
 * @see http://cpdn.cryptopro.ru/default.asp?url=content/cades/interface_c_ad_e_s_c_o_m_1_1_i_c_p_signed_data5.html
 *
 * @package CProCSP
 */
class CPSignedData
{
    public function SignCades() {}

    /**
     * Добавляет к сообщению усовершенствованную подпись.
     */
    public function SignHash() {}

    /**
     *
     */
    public function CoSignHash() {}

    /**
     * Создает подпись
     * Creates a digital signature on the content to be signed.
     * @see https://msdn.microsoft.com/en-us/library/aa387726.aspx
     *
     * @param CPSigner $signer
     * @param boolean $bDetached If True, the data to be signed is detached;
     * @param int $encodingType A value of the ENCODING_TYPE
     */
    public function Sign ($signer = null, $bDetached = false, $encodingType = ENCODE_BASE64) {}

    /**
     * Cosigns an already signed message.
     * @see https://msdn.microsoft.com/en-us/library/aa387725.aspx
     *
     * @param CPSigner $signer
     * @param int $encodingType A value of the CAPICOM_ENCODING_TYPE
     */
    public function CoSign ($signer = null, $encodingType = ENCODE_BASE64) {}

    /**
     *
     */
    public function CoSignCades() {}

    /**
     *
     */
    public function EnhanceCades() {}

    /**
     * Determines the validity of a signature or signatures.
     * @param string $signedMessage A string that contains the signed message to be verified.
     * @param boolean $bDetached If True, the data to be signed is detached
     * @param int $verifyFlag A value of the CAPICOM_SIGNED_DATA_VERIFY_FLAG
     */
    public function Verify($signedMessage, $bDetached = false, $verifyFlag) {}

    /**
     *
     */
    public function VerifyCades() {}

    /**
     * Проверяет усовершенствованную подпись на основе переданного хэш-значения.
     *
     * Метод VerifyHash позволяет проверить усовершенствованную подпись,
     * в том числе и на соответствие заданому типу подписи.
     * В отличие от метода VerifyCades, данный метод не проверяет соответствие хэш-значения каким-либо данным.
     * Если в переданном сообщении присутствует подписанный атрибут messageDigest, то данный метод проверяет только
     * соответствие хэш-значения в параметре Hash тому хэш-значению, которое содержится в атрибуте messageDigest.
     *
     * @param CPHashedData $hash
     * @param string $signedMessage Проверяемое подписанное сообщение.
     * @param int $cadesType Тип усовершенствованной подписи
     *
     * @throws \Exception Генерирует Ошибки в любом случае кроме успешной проверки
     */
    public function VerifyHash($hash, $signedMessage, $cadesType = CADES_DEFAULT) {}

    /**
     *
     */
    public function set_ContentEncoding() {}

    /**
     * Данные которые будем подписывать
     * Data to be signed. This property must be initialized before the Sign method is called.
     * When the value of this property is reset, directly or indirectly, the whole state of the object is reset,
     * and any signature that was associated with the object before the property was changed is lost.
     *
     * @param string $content данные
     */
    public function set_Content ($content) {}

    /**
     * Возвращает коллекцию подписантов подписи
     * Retrieves the Signers collection that represents the signature creators of the data.
     *
     * @return \CProCSP\CPSigners
     */
    public function get_Signers() {}

    /**
     * Retrieves the Certificates collection of the signed data.
     */
    public function get_Certificates() {}

}