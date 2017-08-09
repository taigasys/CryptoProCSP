<?php

namespace CProCSP;

/**
 * Глобальные константы для установки типов алгоритма хеша, например для CPHashedData::set_Algorithm
 * @var int HASH_ALGORITHM_SHA1 SHA1 hashing algorithm.
 * @var int HASH_ALGORITHM_MD2 MD2 hashing algorithm.
 * @var int HASH_ALGORITHM_MD4 MD4 hashing algorithm.
 * @var int HASH_ALGORITHM_MD5 MD5 hashing algorithm.
 * @var int HASH_ALGORITHM_SHA_256 SHA-256 hash algorithm. CAPICOM 2.0.0.3 and earlier:  This value is not supported.
 * @var int HASH_ALGORITHM_SHA_384 SHA-384 hash algorithm. CAPICOM 2.0.0.3 and earlier:  This value is not supported.
 * @var int HASH_ALGORITHM_SHA_512 SHA-512 hash algorithm. CAPICOM 2.0.0.3 and earlier:  This value is not supported.
 * @var int HASH_ALGORITHM_GOSTR_3411 ГОСТ 3411
 */
const HASH_ALGORITHM_SHA1 = 0;
const HASH_ALGORITHM_MD2 = 1;
const HASH_ALGORITHM_MD4 = 2;
const HASH_ALGORITHM_MD5 = 3;
const HASH_ALGORITHM_SHA_256 = 4;
const HASH_ALGORITHM_SHA_384 = 5;
const HASH_ALGORITHM_SHA_512 = 6;
const HASH_ALGORITHM_GOSTR_3411 = 100;

/**
 * Глобальные константы для установки кодировки хеша, например для CPHashedData::set_DataEncoding
 * @var int ENCODE_ANY Data is saved as a base64-encoded string or a pure binary sequence.
 * This encoding type is used only for input data that has an unknown encoding type. Introduced in CAPICOM 2.0.
 * @var int ENCODE_BASE64 Data is saved as a base64-encoded string.
 * @var int ENCODE_BINARY Data is saved as a pure binary sequence.
 */
const ENCODE_ANY = 0xffffffff;
const ENCODE_BASE64 = 0;
const ENCODE_BINARY = 1;

/**
 * Только описание! Для автокомплитов IDE и справки
 * CPHashedData класс плагина HashedData предоставляет свойства и методы для вычисления хэш-значения данных.
 * Объект HashedData предоставляет интерфейс, аналогичный интерфейсу объекта CAPICOM.HashedData .
 * В отличие от объекта CAPICOM.HashedData , объект HashedData поддерживает алгоритм хэширования ГОСТ Р 34.11-94 и ГОСТ Р 34.11-2012
 *
 * @see http://cpdn.cryptopro.ru/default.asp?url=content/cades/class_c_ad_e_s_c_o_m_1_1_c_p_hashed_data.html
 * @package CProCSP
 */
class CPHashedData
{
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