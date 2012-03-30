<?php

/**
 * @version $Id: CommentList.php 515 2006-12-28 00:29:20Z drewish $
 * @author  Martin Legris <mlegris@newcommerce.ca>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * Phlickr_PhotoList represents paged list of photos.
 *
 * <b>WATCH OUT</b>: there's still some problems with the caching in the class.
 * if you call refresh() it'll force and update only to the current page. If
 * you want the whole thing refreshed you'll need to call it on each page.
 *
 * @package Phlickr
 * @author  Martin Legris <mlegris@newcommerce.ca>
 * @see     Phlickr_PhotoListIterator
 * @since   0.5.1
 */
class Phlickr_CommentList implements Phlickr_Framework_ICommentList {
    /**
     * The name of the XML element in the response that defines the object.
     *
     * @var string
     */
    const XML_RESPONSE_LIST_ELEMENT = 'comments';
    /**
     * The name of the XML element in the response that defines a member of the
     * list.
     *
     * @var string
     */
    const XML_RESPONSE_ELEMENT = 'comment';
    /**
     * The name of the Flickr API method that provides the info on this object.
     *
     * @var string
     */
     const XML_METHOD_NAME = 'flickr.photos.comments.getList';

    /**
     * Request the CommentList is based on.
     *
     * @var object Phlickr_Request
     */
    protected $_request = null;
    /**
     * XML from Flickr.
     *
     * @var object SimpleXMLElement
     */
    protected $_cachedXml = array();

    /**
     * Constructor.
     *
     * @param   object Phlickr_Request $request
     * @param   integer $photosPerPage Number of photos on each page.
     * @throws  Phlickr_Exception, Phlickr_ConnectionException,
     *          Phlickr_XmlParseException
     */
    function __construct(Phlickr_Request $request) {
        $this->_request = $request;
        $this->load();
    }

    static protected function getResponseListElement() {
        return self::XML_RESPONSE_LIST_ELEMENT;
    }

    static protected function getResponseElement() {
        return self::XML_RESPONSE_ELEMENT;
    }

    static public function getCommentList(Phlickr_Api $api, $photo_id) {
        $request = $api->createRequest(
            self::XML_METHOD_NAME,
            array("photo_id" => $photo_id)
        );

        if (is_null($request)) {
            throw new Phlickr_Exception('Could not create a Request flickr.photos.comments.getList.');
        } else {
            return new Phlickr_CommentList($request);
        }
    }

    /**
     * Return a reference to this object's Phlickr_Api.
     *
     * @return  object Plickr_Api
     */
    public function getApi() {
        return $this->_request->getApi();
    }

    /**
    * Return the Phlickr_Request the CommentList is based on.
    *
    * @return object Phlickr_Request
    */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * Connect to Flickr and retreive a page of photos.
     *
     * @param   boolean $allowCached If a cached result exists, should it be
     *          returned?
     * @param   integer $page The page number to request.
     * @return  object SimpleXMLElement
     * @throws  Phlickr_ConnectionException, Phlickr_XmlParseException
     * @see     load(), refresh()
     */
    protected function requestXml($allowCached = false) {
        $response = $this->_request->execute($allowCached);
        $xml = $response->xml->{self::getResponseListElement()};
        if (is_null($xml)) {
            throw new Exception(
                sprintf(
                    "Could not load object with request: '%s'.",
                    $request->getMethod()
                )
            );
        }
        return $xml;
    }

    /**
     * Load the complete information on object.
     *
     * @param   integer $page The page number to load. Defaults to the current
     *          page.
     * @return  void
     * @see     refresh(), requestXml()
     */
    public function load($page = null) {
        // allow cached results
        $this->_cachedXml = $this->requestXml(true);
    }

    /**
     * Connect to Flickr and get the current, complete information on this
     * object.
     *
     * @return  void
     * @see     load(), requestXml()
     */
    public function refresh() {
        // force a non-cached update
        $this->_cachedXml = $this->requestXml(false);
    }

    /**
     * Return the total number of photos in the photolist.
     *
     * @return  integer
     */
    public function getCount() {
        if (!isset($this->_cachedXml)) {
            $this->load();
        }
        return (integer) count($this->_cachedXml->comment);
    }

    /**
     * Return an array of the comment ids on this page of the list.
     *
     * @return  array of string ids
     */
    public function getIds(){
        $ids = array();
        $comments = $this->getComments(true);
        foreach($comments as $comment) {
          $ids[] = $comment->getId();
        }
        return $ids;
    }

    /**
     * Return an array of photos on a given page.
     *
     * This function is designed to allow iterators access into the class.
     *
     * @param   boolean $allowCached Should cached data be allowed?
     * @return  array object Phlickr_Comment objects.
     */
    public function getComments($allowCached = true) {
        if ($allowCached) {
            $this->load();
        } else {
            $this->refresh();
        }

        $ret = array();
        foreach ($this->_cachedXml->{self::getResponseElement()} as $xmlComment) {
            $ret[] = new Phlickr_Comment($this->getApi(), $xmlComment);
        }
        return $ret;
    }
}