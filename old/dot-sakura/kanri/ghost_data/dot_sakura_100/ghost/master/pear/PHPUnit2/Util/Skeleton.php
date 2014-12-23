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
// $Id: Skeleton.php,v 1.1 2004/11/15 10:31:58 cvs Exp $
//

/**
 * Class for creating a PHPUnit2_Framework_TestCase skeleton file.
 *
 * This class will take a classname as a parameter on construction and will
 * create a PHP file that contains the skeleton of a PHPUnit2_Framework_TestCase
 * subclass.
 *
 * <code>
 * <?php
 * require_once 'PHPUnit2/Util/Skeleton.php';
 *
 * $skeleton = new PHPUnit2_Util_Skeleton(
 *   'PHPUnit2_Util_Skeleton',
 *   'PHPUnit2/Util/Skeleton.php'
 * );
 *
 * $skeleton->write();
 * ?>
 * </code>
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2002-2004 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.php.net/license/3_0.txt The PHP License, Version 3.0
 * @category    Testing
 * @package     PHPUnit2
 * @subpackage  Util
 * @since       2.1.0
 * @abstract
 */
class PHPUnit2_Util_Skeleton {
    // {{{ Constants

    const templateClassHeader =
'<?php
if (!defined("PHPUnit2_MAIN_METHOD")) {
    define("PHPUnit2_MAIN_METHOD", "%sTest::main");
}

require_once "PHPUnit2/Framework/IncompleteTestError.php";
require_once "PHPUnit2/Framework/TestCase.php";

require_once "%s.php";

/**
 * Test class for %s.
 * Generated by PHPUnit2_Util_Skeleton on %s at %s.
 */
class %sTest extends PHPUnit2_Framework_TestCase {
    public static function main() {
        require_once "PHPUnit2/Framework/TestSuite.php";
        require_once "PHPUnit2/TextUI/TestRunner.php";

        $suite  = new PHPUnit2_Framework_TestSuite("%sTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }
';

    const templateClassFooter =
'}

if (PHPUnit2_MAIN_METHOD == "%sTest::main") {
    %sTest::main();
}
?>
';

    const templateMethod =
'
    /**
    * @todo Implement test%s().
    */
    public function test%s() {
        throw new PHPUnit2_Framework_IncompleteTestError;
    }
';

    // }}}
    // {{{ Members

    /**
    * @var    string
    * @access protected
    */
    protected $className;

    /**
    * @var    string
    * @access protected
    */
    protected $classSourceFile;

    // }}}
    // {{{ public function __construct($className, $classSourceFile = '')

    /**
    * Constructor.
    *
    * @param  string  $className
    * @param  string  $classSourceFile
    * @access public
    */
    public function __construct($className, $classSourceFile = '') {
        if ($classSourceFile == '') {
            $classSourceFile = $className . '.php';
        }

        if (file_exists($classSourceFile)) {
            $this->classSourceFile = $classSourceFile;
        } else {
            throw new Exception('Could not open ' . $classSourceFile . '.');
        }

        @include_once $this->classSourceFile;

        if (class_exists($className)) {
            $this->className = $className;
        } else {
            throw new Exception('Could not find class "' . $className . '".');
        }
    }

    // }}}
    // {{{ public function generate()

    /**
    * Generates the test class' source.
    *
    * @return string
    * @access public
    */
    public function generate() {
        $testClassSource = $this->testClassHeader($this->className);

        $class = new ReflectionClass($this->className);

        foreach ($class->getMethods() as $method) {
            if (!$method->isConstructor() &&
                !$method->isAbstract() &&
                 $method->isUserDefined() &&
                 $method->isPublic() &&
                 $method->getDeclaringClass()->getName() == $this->className) {
                $testClassSource .= $this->testMethod($method->getName());
            }
        }

        $testClassSource .= $this->testClassFooter($this->className);

        return $testClassSource;
    }

    // }}}
    // {{{ public function write()

    /**
    * Generates the test class and writes it to a source file.
    *
    * @param  string  $file
    * @access public
    */
    public function write($file = '') {
        if ($file == '') {
            $file = $this->className . 'Test.php';
        }

        if ($fp = @fopen($file, 'w')) {
            @fputs($fp, $this->generate());
            @fclose($fp);
        }
    }

    // }}}
    // {{{ protected function testClassHeader($className)

    /**
    * @param  string  $className
    * @access protected
    */
    protected function testClassHeader($className) {
        return sprintf(
          self::templateClassHeader,
          $className,
          $className,
          $className,
          date('Y-m-d'),
          date('H:i:s'),
          $className,
          $className,
          $className
        );
    }

    // }}}
    // {{{ protected function testClassFooter($className)

    /**
    * @param  string  $className
    * @access protected
    */
    protected function testClassFooter($className) {
        return sprintf(
          self::templateClassFooter,
          $className,
          $className
        );
    }

    // }}}
    // {{{ protected function testMethod($methodName)

    /**
    * @param  string  $methodName
    * @access protected
    */
    protected function testMethod($methodName) {
        $methodName = ucfirst($methodName);

        return sprintf(
          self::templateMethod,
          $methodName,
          $methodName
        );
    }

    // }}}
}

/*
 * vim600:  et sw=2 ts=2 fdm=marker
 * vim<600: et sw=2 ts=2
 */
?>