<?php

namespace CProCSP;

/**
 * Только описание! Для автокомплитов IDE и справки
 * CPSigner класс плагина предоставляет интерфейсы ICPSigner6, ICPSigner5, ICPSigner4, ICPSigner3,
 * ICPSigner2, ICPSigner и интерфейс, аналогичный CAPICOM.Signer .
 * @see http://cpdn.cryptopro.ru/default.asp?url=content/cades/class_c_ad_e_s_c_o_m_1_1_c_p_signer.html
 * @package CProCSP
 */
class CPSigner
{
    /**
     * Возвращает сертификат подписанта
     * The Certificate object that represents the certificate of a signer of the data.
     *
     * @return \CProCSP\CPcertificate
     */
    public function get_Certificate () {}

    /**
     *
     */
    public function set_Certificate () {}

    /**
     *
     */
    public function get_Options () {}

    /**
     *
     */
    public function set_Options () {}

    /**
     * Коллекция подписанных атрибутов.
     * The collection of authenticated attributes.
     */
    public function get_AuthenticatedAttributes () {}

    /**
     * Коллекция неподписанных атрибутов.
     */
    public function get_UnauthenticatedAttributes () {}

    /**
     * Возвращает адрес службы штампов времени.
     */
    public function get_TSAAddress () {}

    /**
     * Устанавливает адрес службы штампов времени.
     */
    public function set_TSAAddress () {}

    /**
     * Возвращает коллекцию СОС.
     */
    public function get_CRLs () {}

    /**
     * Возвращает коллекция ответов служб актуальных статусов.
     */
    public function get_OCSPResponses () {}


    /**
     * Время подписи из атрибута signingTime.
     * @return string 'd.m.Y H:i:s' 06.07.2017 10:42:42
     */
    public function get_SigningTime () {}

    /**
     * Возвращает время, содержащееся в штампе времени на значение подписи (атрибут signatureTimeStamp).
     * Если указанный атрибут отсутствует, свойство вернет ошибку. (Cannot find object or property. (0x80092004))
     * @return string 'd.m.Y H:i:s' 06.07.2017 10:42:42
     */
    public function get_SignatureTimeStampTime () {}

    /**
     * Пин-код для доступа к закрытому ключу.
     * @param string $pinKey пинкод
     */
    public function set_KeyPin ($pinKey) {}
}