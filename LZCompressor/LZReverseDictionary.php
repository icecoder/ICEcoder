<?php
/**
 * Created by PhpStorm.
 * User: sics
 * Date: 28.02.2016
 * Time: 12:53
 */

namespace LZCompressor;


class LZReverseDictionary
{

    public $entries = array(0, 1 ,2);

    public function size() {
        return count($this->entries);
    }

    public function hasEntry($index) {
        return array_key_exists($index, $this->entries);
    }

    public function getEntry($index) {
        return $this->entries[$index];
    }

    public function addEntry($char) {
        $this->entries[] = $char;
    }

}