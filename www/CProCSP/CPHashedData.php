<?php

namespace CProCSP;

/**
 * Class CPHashedData Объект HashedData предоставляет свойства и методы для вычисления хэш-значения данных.
 * Объект HashedData предоставляет интерфейс, аналогичный интерфейсу объекта CAPICOM.HashedData .
 * В отличие от объекта CAPICOM.HashedData , объект HashedData поддерживает алгоритм хэширования ГОСТ Р 34.11-94 и ГОСТ Р 34.11-2012
 *
 * @see http://cpdn.cryptopro.ru/default.asp?url=content/cades/class_c_ad_e_s_c_o_m_1_1_c_p_hashed_data.html
 * @package CProCSP
 */
class CPHashedData
{
    /**
     * Константы для установки типов алгоритма хеша, например для self::set_Algorithm
     */

    /** @var int SHA1 hashing algorithm. */
    const HASH_ALGORITHM_SHA1 = 0;
    /** /@var int MD2 hashing algorithm. */
    const HASH_ALGORITHM_MD2 = 1;
    /** @var int MD4 hashing algorithm. */
    const HASH_ALGORITHM_MD4 = 2;
    /** @var int MD5 hashing algorithm. */
    const HASH_ALGORITHM_MD5 = 3;
    /** @var int SHA-256 hash algorithm. CAPICOM 2.0.0.3 and earlier:  This value is not supported. */
    const HASH_ALGORITHM_SHA_256 = 4;
    /** @var int SHA-384 hash algorithm. CAPICOM 2.0.0.3 and earlier:  This value is not supported. */
    const HASH_ALGORITHM_SHA_384 = 5;
    /** @var int SHA-512 hash algorithm. CAPICOM 2.0.0.3 and earlier:  This value is not supported. */
    const HASH_ALGORITHM_SHA_512 = 6;
    /** @var int ГОСТ 3411 */
    const HASH_ALGORITHM_GOSTR_3411 = 100;

    /**
     * Константы для установки кодировки хеша, например для self::set_DataEncoding
     */
    const ENCODE_BASE64 = 0;
    const ENCODE_BINARY = 1;
    const ENCODE_ANY = 0xffffffff;

    /**
     * Создает хеш
     * Creates a hash of the specified string.
     * @param string $source данные по которым вычисляется хеш
     */
    public function Hash ($source) {}

    /**
     * Устанавливает уже созданный хеш в value объекта
     * @param string $hash хеш
     */
    public function SetHashValue ($hash) {}

    /**
     * Возвращает хеш полученный при вызове self::Hash
     * Retrieves the hashed data after successful calls to the Hash method.
     * The hash is returned in hexadecimal format.
     */
    public function get_Value () {}

    /**
     * Устанавливает алгоритм вычисления хеша
     * Sets or retrieves the type of hashing algorithm used.
     * @param int $algorithm
     */
    public function set_Algorithm ($algorithm) {}

    /**
     * Возвращает алгоритм вычисления хеша
     * @return int
     */
    public function get_Algorithm () {}

    /**
     * Устанавливает кодировку хеша base64 или бинари
     * @param int $encodeType
     */
    public function set_DataEncoding ($encodeType) {}

    /**
     * Устанавливает кодировку хеша base64 или бинари
     * @return int
     */
    public function get_DataEncoding () {}

}