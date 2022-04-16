<?php
namespace App\Services;

use Exception;

class AES
{

    protected $key;
    protected $data;
    protected $method;
    private $iv;
    private $padding;
    /**
     * Available OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING
     *
     * @var string $options
     */
    protected $options = OPENSSL_RAW_DATA;
    /**
     *
     * @param string $data
     * @param string $key
     * @param string $blockSize
     * @param string $mode
     * @param string $padding
     */
    public function __construct($data = null, $key = null, $blockSize = null, $mode = 'CBC', $iv = null, $padding = true)
    {
        $this->setData($data);
        $this->setKey($key);
        $this->setMethode($blockSize, $mode);
        $this->iv = $iv;
        $this->padding = $padding;
    }
    /**
     *
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
    /**
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }
    /**
     * CBC 128 192 256
     * CBC-HMAC-SHA1 128 256
     * CBC-HMAC-SHA256 128 256
     * CFB 128 192 256
     * CFB1 128 192 256
     * CFB8 128 192 256
     * CTR 128 192 256
     * ECB 128 192 256
     * OFB 128 192 256
     * XTS 128 256
     * @param string $blockSize
     * @param string $mode
     */
    public function setMethode($blockSize, $mode = 'CBC')
    {
        if ($blockSize == 192 && in_array($mode, ['CBC-HMAC-SHA1', 'CBC-HMAC-SHA256', 'XTS'])) {
            $this->method = null;
            throw new Exception('Invlid block size and mode combination!');
        }
        $this->method = 'AES-' . $blockSize . '-' . $mode;
    }
    /**
     *
     * @return boolean
     */
    public function validateParams()
    {
        if ($this->data != null &&
            $this->method != null) {
            return true;
        } else {
            return false;
        }
    }
//it must be the same when you encrypt and decrypt
    protected function getIV()
    {
        //$secure = '6543210987654321';
        //return mcrypt_create_iv(mcrypt_get_iv_size($this->cipher, $this->mode), MCRYPT_RAND);
        //return openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method));
        return $this->iv ?: openssl_random_pseudo_bytes(16);
    }
    /**
     * @return string
     * @throws Exception
     */
    public function encrypt(): string
    {
        if ($this->validateParams()) {
            $iv = $this->getIV();
            return ($this->padding ? $iv : '') . trim(openssl_encrypt($this->data, $this->method, $this->key, $this->options, $iv));
        } else {
            throw new Exception('Invalid params!');
        }
    }
    /**
     *
     * @return string
     * @throws Exception
     */
    public function decrypt()
    {
        if ($this->validateParams()) {
            $iv = $this->iv ?: substr($this->data, 0, 16);
            $data = $this->padding ? substr($this->data, 16) : $this->data;
            $ret = openssl_decrypt($data, $this->method, $this->key, $this->options, $iv);
            $ret = str_replace($iv, '', $ret);
            return trim($ret);
        } else {
            throw new Exception('Invalid params!');
        }
    }
}