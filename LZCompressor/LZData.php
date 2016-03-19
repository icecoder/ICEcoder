<?php
namespace LZCompressor;

class LZData
{
    /**
     * @var
     */
    public $str = '';

    /**
     * @var
     */
    public $val;

    /**
     * @var int
     */
    public $position = 0;

    /**
     * @var int
     */
    public $index = 1;

    public function append($str) {
        $this->str .= $str;
    }
}
