<?php

/**
 * Runner for all offline TextUi tests.
 *
 * To run the offline test suites (assuming the Phlickr installation is in the
 * include path) run:
 *      phpunit Phlickr_Tests_Offline_TextUi_AllTests
 *
 * @version $Id$
 * @copyright 2005
 */


if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Phlickr_Tests_Offline_TextUi_AllTests::main');
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';

require_once 'Phlickr/Tests/Offline/TextUi/UploadBatchViewer.php';
require_once 'Phlickr/Tests/Offline/TextUi/UploadListener.php';

class Phlickr_Tests_Offline_TextUi_AllTests {
    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Phlickr Offline Import Tests');

        $suite->addTestSuite('Phlickr_Tests_Offline_TextUi_UploadBatchViewer');
        $suite->addTestSuite('Phlickr_Tests_Offline_TextUi_UploadListener');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Phlickr_Tests_Offline_TextUi_AllTests::main') {
    Phlickr_Tests_Offline_TextUi_AllTests::main();
}
