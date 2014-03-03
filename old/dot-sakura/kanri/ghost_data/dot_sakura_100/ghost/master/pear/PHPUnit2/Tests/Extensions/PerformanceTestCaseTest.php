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
// $Id: PerformanceTestCaseTest.php,v 1.1 2004/11/15 10:31:58 cvs Exp $
//

require_once 'PHPUnit2/Framework/AssertionFailedError.php';
require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'PHPUnit2/Framework/TestResult.php';

require_once 'PHPUnit2/Tests/Sleep.php';

/**
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2002-2004 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.php.net/license/3_0.txt The PHP License, Version 3.0
 * @category    Testing
 * @package     PHPUnit2
 * @subpackage  Tests
 */
class PHPUnit2_Tests_Extensions_PerformanceTestCaseTest extends PHPUnit2_Framework_TestCase {
    public function testDoesNotExceedMaxRunningTime() {
        $test   = new PHPUnit2_Tests_Sleep('testSleepTwoSeconds', 3);
        $result = $test->run();

        $this->assertEquals(0, $result->failureCount());
    }

    public function testExceedsMaxRunningTime() {
        $test   = new PHPUnit2_Tests_Sleep('testSleepTwoSeconds', 1);
        $result = $test->run();

        $this->assertEquals(1, $result->failureCount());
    }
}

/*
 * vim600:  et sw=2 ts=2 fdm=marker
 * vim<600: et sw=2 ts=2
 */
?>