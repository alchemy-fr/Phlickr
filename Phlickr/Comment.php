<?php

/**
 * @version $Id: Comment.php 510 2006-12-28 03:44:39Z drewish $
 * @author  Martin Legris <mlegris@newcommerce.ca>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * Phlickr_Group access to the photos in a group.
 *
 * @package Phlickr
 * @author  Martin Legris <mlegris@newcommerce.ca>
 * @since   0.5.1
 */
class Phlickr_Comment extends Phlickr_Framework_ObjectBase {
    /**
     * The name of the XML element in the response that defines the object.
     *
     * @var string
     */
    const XML_RESPONSE_ELEMENT = 'comment';

    /**
     * Constructor.
     *
     * You can construct a group from an Id or XML.
     *
     * @param   object Phlickr_API $api
     * @param   mixed $source string Id, object SimpleXMLElement
     * @throws  Phlickr_Exception, Phlickr_ConnectionException, Phlickr_XmlParseException
     */
    function __construct(Phlickr_Api $api, $source) {
        $this->_cachedXml = $source;
        $this->_api = $api;
    }

    public function __toString() {
        return $this->getName() . ' (' . $this->getId() . ')';
    }

    static function getRequestMethodName() {
        return self::XML_METHOD_NAME;
    }

    static function getRequestMethodParams($id) {
        return array('comment_id' => (string) $id);
    }

    /**
     * Build a URL to access the comment
     *
     * @return  string
     */
    public function getUrl() {
        return (string) $this->_cachedXML['permalink'];
    }

    public function buildUrl() {
        return $this->getUrl();
    }

    public function getId() {
        return (string) $this->_cachedXml["id"];
    }

    public function getAuthorId() {
        return (string) $this->_cachedXml['author'];
    }

    public function getAuthorName() {
        return (string) $this->_cachedXml['authorname'];
    }

    public function getCreationDate() {
        return (integer) $this->_cachedXml['datecreate'];
    }

    public function getComment() {
        return (string) $this->_cachedXml;
    }
}