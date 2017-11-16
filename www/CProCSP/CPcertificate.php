<?php

namespace CProCSP;

/**
 * Глобальные константы плагина используемые методом CPcertificate::GetInfo
 * @var int CERT_INFO_SUBJECT_SIMPLE_NAME Краткое имя владельца сертификата Returns the display name from the certificate subject.
 * @var int CERT_INFO_ISSUER_SIMPLE_NAME Краткое имя издятеля сертификата Returns the display name of the issuer of the certificate.
 * @var int CERT_INFO_SUBJECT_EMAIL_NAME Returns the email address of the certificate subject.
 * @var int CERT_INFO_ISSUER_EMAIL_NAME Returns the email address of the issuer of the certificate.
 * @var int CERT_INFO_SUBJECT_UPN Returns the UPN of the certificate subject. Introduced in CAPICOM 2.0.
 * @var int CERT_INFO_ISSUER_UPN Returns the UPN of the issuer of the certificate. Introduced in CAPICOM 2.0.
 * @var int CERT_INFO_SUBJECT_DNS_NAME Returns the DNS name of the certificate subject. Introduced in CAPICOM 2.0.
 * @var int CERT_INFO_ISSUER_DNS_NAME Returns the DNS name of the issuer of the certificate. Introduced in CAPICOM 2.0.
 */
const CERT_INFO_SUBJECT_SIMPLE_NAME = 0;
const CERT_INFO_ISSUER_SIMPLE_NAME = 1;
const CERT_INFO_SUBJECT_EMAIL_NAME = 2;
const CERT_INFO_ISSUER_EMAIL_NAME = 3;
const CERT_INFO_SUBJECT_UPN = 4;
const CERT_INFO_ISSUER_UPN = 5;
const CERT_INFO_SUBJECT_DNS_NAME = 6;
const CERT_INFO_ISSUER_DNS_NAME = 7;

/**
 * Только описание! Для автокомплитов IDE и справки
 * CPSigners класс плагина сертификат открытого ключа. Реализует интерфейс,
 * аналогичный интерфейсу объекта CAPICOM.Certificate .
 *
 * В отличие от объекта Microsoft CAPICOM.Certificate , для данного объекта реализованы только следующие методы и свойства:
 * Export, Import, GetInfo, HasPrivateKey, IsValid, IssuerName, SerialNumber, SubjectName, Thumbprint, ValidFromDate,
 * ValidToDate, Version, ExtendedKeyUsage, KeyUsage, PublicKey, PrivateKey, BasicConstraints.
 *
 * @see http://cpdn.cryptopro.ru/default.asp?url=content/cades/class_c_ad_e_s_c_o_m_1_1_c_p_certificate.html
 * @package CProCSP
 */
class CPcertificate
{

    /**
     * Описывает сертификат открытого ключа.
     * Retrieves information from the certificate.
     *
     * @param int $InfoType Тип данных которые необходимо получить CERT_INFO_*
     * @return string Строки в кодировке cp1251
     */
    public function GetInfo ($InfoType) {}

    /**
     * Вызвала signfault
     */
    public function FindPrivateKey () {}

    /**
     * Наличие приватного ключа в сертификате
     * Determines whether the certificate has a private key associated with it.
     *
     * @return boolean
     */
    public function HasPrivateKey () {}

    /**
     * Собирает цепочку доверия сертификатов
     * и возвращает объект CertificateStatus содержащий статус валидации
     * Builds a certificate verification chain for a certificate and returns a CertificateStatus object
     * that contains the validity status of the certificate.
     *
     * @return CPCertificateStatus
     */
    public function IsValid () {}


    /**
     * Возвращает объект CPKeyUsage показывающий валидность ключа сертификата
     * Returns a KeyUsage object that indicates the valid key usage of the certificate.
     *
     * @return CPKeyUsage
     */
    public function KeyUsage () {}

    /**
     * Возвращает объект CPExtendedKeyUsage показывающий валидность расширенного ключа сертификата
     * Returns an CPExtendedKeyUsage object that indicates the valid extended key uses of the certificate.
     * @return CPExtendedKeyUsage
     */
    public function ExtendedKeyUsage () {}

    /**
     * Кодирует сертификат в строку и возвращает
     * Copies a certificate to an encoded string. The encoded string can be written to a file or imported into a new Certificate object.
     * @return string
     */
    public function Export () {}

    /**
     * Imports a previously encoded certificate from a string into the Certificate object.
     */
    public function Import () {}

    /**
     * Возвращает серийный номер сертификата
     * Retrieves a string that contains the certificate serial number.
     *
     * @return string (9E1C3...)
     */
    public function get_SerialNumber () {}

    /**
     * Возвращает SHA-1 хеш сертификата (hexadecimal string)
     * Retrieves a hexadecimal string that contains the SHA-1 hash of the certificate.
     *
     * @return string (9E1C31DB2...)
     */
    public function get_Thumbprint () {}

    /**
     * Возвращает имя владельца сертификата
     * Retrieves a string that contains the name of the certificate subject.
     *
     * Может содержать
     * OGRN=1050000004050,
     * SNILS=07309000000,
     * INN=002000000000,
     * E=mail@mail.ru,
     * UnstructuredName="INN=002000000000/KPP=000000000/OGRN=1050000004050",
     * O="ООО ""Рога и копыта+""",
     * OU=0,
     * T=Директор,
     * CN=Иванов Иван Иванович,
     * SN=Иванов,
     * G=Иван Иванович,
     * C=RU,
     * L=Город,
     * S=24 Красноярский край,
     * STREET="адрес"
     *
     * @return string
     */
    public function get_SubjectName () {}

    /**
     * Информация о издателе сертификата
     * Retrieves a string that contains the name of the certificate issuer.
     *
     * Может содержать
     * CN=TENSORCA5,
     * O=ООО Компания Тензор,
     * OU=Удостоверяющий центр,
     * STREET=Московский проспект д.12,
     * L=Ярославль,
     * S=76 Ярославская область,
     * C=RU,
     * INN=007605016030,
     * OGRN=1027600787994,
     * E=ca_tensor@tensor.ru
     *
     * @return string
     */
    public function get_IssuerName () {}

    /**
     * Возвращает номер версии сертификата
     * Retrieves the version number of the certificate.
     *
     * @return int
     */
    public function get_Version () {}

    /**
     * Возвращает дату окончания валидности сертификата
     * Retrieves the ending date for the validity of the certificate.
     *
     * @return string 'd.m.Y H:i:s' 24.01.2018 08:30:33
     */
    public function get_ValidToDate () {}

    /**
     * Возвращает дату начала валидности сертификата
     * Retrieves the beginning date for the validity of the certificate.

     * @return string 'd.m.Y H:i:s' 24.01.2018 08:30:33
     */
    public function get_ValidFromDate () {}

    /**
     * Sets or retrieves the private key associated with the certificate.
     */
    public function PrivateKey () {}

    /**
     *  Returns a PublicKey object.
     */
    public function PublicKey () {}

    /**
     * Returns a BasicConstraints object that represents the basic constraints extension of the certificate.
     */
    public function BasicConstraints () {}

}