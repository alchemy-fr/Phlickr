<?php

/**
 * Photoset Online Tests
 *
 * @version $Id$
 * @copyright 2005
 */

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/Photoset.php';

class Phlickr_Tests_Online_Photoset extends PHPUnit_Framework_TestCase {
    var $api;
    var $photoset;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->photoset = new Phlickr_Photoset($this->api, TESTING_REAL_PHOTOSET_ID1);
    }
    function tearDown() {
        unset($this->photoset);
        unset($this->api);
    }

    function testGetPhotoList() {
        $result = $this->photoset->getPhotoList();
        $this->assertType('Phlickr_PhotosetPhotoList', $result);
    }
}
