<?php

/**
 * Runner for all offline tests.
 *
 * To run the offline test suites (assuming the Phlickr installation is in the
 * include path) run:
 *      phpunit Phlickr_Tests_Offline_AllTests
 *
 * @version $Id$
 * @copyright 2005
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Phlickr_Tests_Offline_AllTests::main');
}

class Phlickr_Tests_Offline_AllTests {
    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Phlickr Offline Tests');

        // sub-directories
        $suite->addTest(Phlickr_Tests_Offline_Import_AllTests::suite());
        $suite->addTest(Phlickr_Tests_Offline_PhotoSortStrategy_AllTests::suite());
        $suite->addTest(Phlickr_Tests_Offline_TextUi_AllTests::suite());

        // core
        $suite->addTestSuite('Phlickr_Tests_Offline_Api');
        $suite->addTestSuite('Phlickr_Tests_Offline_Cache');
        $suite->addTestSuite('Phlickr_Tests_Offline_Request');
        $suite->addTestSuite('Phlickr_Tests_Offline_Response');
        $suite->addTestSuite('Phlickr_Tests_Offline_Uploader');

        // wrappers
        $suite->addTestSuite('Phlickr_Tests_Offline_AuthedGroup');
        $suite->addTestSuite('Phlickr_Tests_Offline_AuthedPhoto');
        $suite->addTestSuite('Phlickr_Tests_Offline_AuthedPhotoset');
#        $suite->addTestSuite('Phlickr_Tests_Offline_AuthedPhotosetList');
        $suite->addTestSuite('Phlickr_Tests_Offline_AuthedUser');
        $suite->addTestSuite('Phlickr_Tests_Offline_Group');
        $suite->addTestSuite('Phlickr_Tests_Offline_GroupList');
        $suite->addTestSuite('Phlickr_Tests_Offline_Note');
        $suite->addTestSuite('Phlickr_Tests_Offline_Photo');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoList');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoListIterator');
        $suite->addTestSuite('Phlickr_Tests_Offline_Photoset');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotosetPhotoList');
#        $suite->addTestSuite('Phlickr_Tests_Offline_PhotosetList');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoSorter');
        $suite->addTestSuite('Phlickr_Tests_Offline_User');
        $suite->addTestSuite('Phlickr_Tests_Offline_UserList');
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Phlickr_Tests_Offline_AllTests::main') {
    Phlickr_Tests_Offline_AllTests::main();
}
