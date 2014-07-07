<?php

/**
 * Runner for all tests.
 *
 * To run the online test suites (assuming the Phlickr installation is in the
 * include path) run:
 *      phpunit Phlickr_Tests_Online_AllTests
 *
 * @version $Id: AllTests.php 537 2008-12-09 23:32:59Z edwardotis $
 * @copyright 2005
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Phlickr_Tests_Online_AllTests::main');
}


class Phlickr_Tests_Online_AllTests {
    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Phlickr Online Tests');

        // core
        $suite->addTestSuite('Phlickr_Tests_Online_Api');
        $suite->addTestSuite('Phlickr_Tests_Online_Request');
        $suite->addTestSuite('Phlickr_Tests_Online_Uploader');

        // wrappers
        $suite->addTestSuite('Phlickr_Tests_Online_AuthedGroup');
        $suite->addTestSuite('Phlickr_Tests_Online_AuthedPhoto');
        $suite->addTestSuite('Phlickr_Tests_Online_AuthedPhotoset');
        $suite->addTestSuite('Phlickr_Tests_Online_AuthedPhotosetList');
        $suite->addTestSuite('Phlickr_Tests_Online_AuthedUser');
        $suite->addTestSuite('Phlickr_Tests_Online_Group');
        $suite->addTestSuite('Phlickr_Tests_Online_Photo');
        $suite->addTestSuite('Phlickr_Tests_Online_PhotoList');
        $suite->addTestSuite('Phlickr_Tests_Online_Photoset');
        $suite->addTestSuite('Phlickr_Tests_Online_PhotosetList');
        $suite->addTestSuite('Phlickr_Tests_Online_User');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Phlickr_Tests_Online_AllTests::main') {
    Phlickr_Tests_Online_AllTests::main();
}


?>
