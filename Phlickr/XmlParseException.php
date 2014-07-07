<?php

/**
 * Exception thrown when XML cannot be parsed.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 */
class Phlickr_XmlParseException extends Phlickr_Exception {
    /**
     *
     * @var string
     */
    protected $_xml;

    /**
     * Constructor
     *
     * @param string $message
     * @param string $xml
     */
    public function __construct($message = null, $xml = null) {
        parent::__construct($message);
        $this->_xml = (string) $xml;
    }

    public function __toString() {
        $s = "exception '" . __CLASS__ . "' {$this->message}\n";
        if (isset($this->_xml)) {
            $s .= "XML: '{$this->_xml}'\n";
        }
        $s .= "Stack trace:\n" . $this->getTraceAsString();
        return $s;
    }

    /**
     * Return the un-parseable XML.
     *
     * @return string
     */
    public function getXml() {
        return $this->_xml;
    }
}