<?php
namespace LZCompressor;

class LZContext
{
    /**
     * @var array
     */
    public $dictionary = array();

    /**
     * @var array
     */
    public $dictionaryToCreate = array();

    /**
     * @var string
     */
    public $c = '';

    /**
     * @var string
     */
    public $wc = '';

    /**
     * @var string
     */
    public $w = '';

    /**
     * @var int
     */
    public $enlargeIn = 2;

    /**
     * @var int
     */
    public $dictSize = 3;

    /**
     * @var int
     */
    public $numBits = 2;

    /**
     * @var LZData
     */
    public $data;

    function __construct()
    {
        $this->data = new LZData;
    }

    // Helper

    /**
     * @param string $val
     * @return bool
     */
    public function dictionaryContains($val) {
        return array_key_exists($val, $this->dictionary);
    }

    /**
     * @param $val
     */
    public function addToDictionary($val) {
        $this->dictionary[$val] = $this->dictSize++;
    }

    /**
     * @param string $val
     * @return bool
     */
    public function dictionaryToCreateContains($val) {
        return array_key_exists($val, $this->dictionaryToCreate);
    }

    /**
     * decrements enlargeIn and extends numbits in case enlargeIn drops to 0
     */
    public function enlargeIn() {
        $this->enlargeIn--;
        if($this->enlargeIn==0) {
            $this->enlargeIn = pow(2, $this->numBits);
            $this->numBits++;
        }
    }
}
