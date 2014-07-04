<?php namespace Lukaskorl\Commander\Commands;

use Lukaskorl\Commander\Command;

abstract class DataTransferCommand implements Command {

    /** @var array */
    public $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($property)
    {
        if(isset($this->data[$property])) {
            return $this->data[$property];
        }
        return null;
    }

} 