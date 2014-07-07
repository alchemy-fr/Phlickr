<?php

/**
* Exception thrown when there is a problem connecting to the service.
*
* @package Phlickr
* @author  Andrew Morton <drewish@katherinehouse.com>
*/
class Phlickr_ConnectionException extends Phlickr_Exception {
/**
* The URL that was being requested when the problem occured.
*
* @var string
*/
protected $_url;

/**
* Constructor
*
* @param string $message Error message
* @param integer $code Error code
* @param string $url URL accessed during failure
*/
public function __construct($message = null, $code = null, $url = null) {
parent::__construct($message, $code);
$this->_url = (string) $url;
}

public function __toString() {
$s = "exception '" . __CLASS__ . "' [{$this->code}]: {$this->message}\n";
if (isset($this->_url)) {
$s .= "URL: {$this->_url}\n";
}
$s .= "Stack trace:\n" . $this->getTraceAsString();
return $s;
}

/**
* Return the URL associated with the connection failure.
*
* @return string
*/
public function getUrl() {
return $this->_url;
}
}