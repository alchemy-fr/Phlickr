<?php

/**
 * Runner for all offline comparator tests.
 *
 * To run the offline test suites (assuming the Phlickr installation is in the
 * include path) run:
 *      phpunit Phlickr_Tests_Offline_Comparators_AllTests
 *
 * @version $Id$
 * @copyright 2005
 */


if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Phlickr_Tests_Offline_PhotoSortStrategy_AllTests::main');
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';


require_once 'Phlickr/Tests/Offline/PhotoSortStrategy/ByColor.php';
require_once 'Phlickr/Tests/Offline/PhotoSortStrategy/ById.php';
require_once 'Phlickr/Tests/Offline/PhotoSortStrategy/ByTakenDate.php';
require_once 'Phlickr/Tests/Offline/PhotoSortStrategy/ByTitle.php';


class Phlickr_Tests_Offline_PhotoSortStrategy_AllTests {
    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Phlickr Tests');

        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoSortStrategy_ByColor');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoSortStrategy_ById');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoSortStrategy_ByTakenDate');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoSortStrategy_ByTitle');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Phlickr_Tests_Offline_PhotoSortStrategy_AllTests::main') {
    Phlickr_Tests_Offline_PhotoSortStrategy_AllTests::main();
}

?>
