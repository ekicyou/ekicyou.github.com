<?php
//
// +------------------------------------------------------------------------+
// | PEAR :: PHPUnit2                                                       |
// +------------------------------------------------------------------------+
// | Copyright (c) 2002-2004 Sebastian Bergmann <sb@sebastian-bergmann.de>. |
// +------------------------------------------------------------------------+
// | This source file is subject to version 3.00 of the PHP License,        |
// | that is available at http://www.php.net/license/3_0.txt.               |
// | If you did not receive a copy of the PHP license and are unable to     |
// | obtain it through the world-wide-web, please send a note to            |
// | license@php.net so we can mail you a copy immediately.                 |
// +------------------------------------------------------------------------+
//
// $Id: AllTests.php,v 1.1 2004/11/15 10:31:58 cvs Exp $
//

if (!defined('PHPUnit2_MAIN_METHOD')) {
    define('PHPUnit2_MAIN_METHOD', 'PHPUnit2_Tests_AllTests::main');
}

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';

require_once 'PHPUnit2/Tests/Framework/AllTests.php';
require_once 'PHPUnit2/Tests/Extensions/AllTests.php';
require_once 'PHPUnit2/Tests/Runner/AllTests.php';

/**
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2002-2004 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.php.net/license/3_0.txt The PHP License, Version 3.0
 * @category    Testing
 * @package     PHPUnit2
 * @subpackage  Tests
 */
class PHPUnit2_Tests_AllTests {
    public static function main() {
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite('PHPUnit');

        $suite->addTest(PHPUnit2_Tests_Framework_AllTests::suite());
        $suite->addTest(PHPUnit2_Tests_Extensions_AllTests::suite());
        $suite->addTest(PHPUnit2_Tests_Runner_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit2_MAIN_METHOD == 'PHPUnit2_Tests_AllTests::main') {
    PHPUnit2_Tests_AllTests::main();
}

/*
 * vim600:  et sw=2 ts=2 fdm=marker
 * vim<600: et sw=2 ts=2
 */
?>
