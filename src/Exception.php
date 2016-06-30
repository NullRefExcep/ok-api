<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\okapi;


class Exception extends \Exception
{
    protected $data;

    public function __construct($message = '', $code = 0, Exception $previous = null, $data)
    {
        $this->data = $data;
        \Exception::__construct($message, $code, $previous);
    }

    public function getData()
    {
        return $this->data;
    }

}