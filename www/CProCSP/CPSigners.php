<?php

namespace CProCSP;

/**
 * Только описание! Для автокомплитов IDE и справки
 * CPSigners класс плагина коллекция объектов \CProCSP\CPSigner
 * @see http://cpdn.cryptopro.ru/default.asp?url=content/cades/class_c_ad_e_s_c_o_m_1_1_c_p_signers.html
 * @package CProCSP
 */
class CPSigners
{
    /**
     * Возвращает количество подписантов
     * Number of Signer objects in the collection.
     *
     * @return integer
     */
    public function get_Count () {}

    /**
     * Возвращает объект подписанта
     * Retrieves the Signer object that represents the indexed signer. This is the default property.
     *
     * @param integer $index индекс, начиная с 1
     * @return \CProCSP\CPSigner
     */
    public function get_Item ($index) {}

}