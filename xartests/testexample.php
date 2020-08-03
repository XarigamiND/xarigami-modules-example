<?php
/**
 * Example Module QA Test example
 *
 * @package modules
 * @copyright (C) 2002-2005 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Example module
 * @copyright (C) 2007-2011 2skies.com
 * @link http://xarigami.com/projects/xarigami_core
 * @author Xarigami Team
 */

/* a suite to add the tests to */
$tmp = new xarTestSuite('Example Suite');

/**
 * Example test class.
 *
 * @todo <1> Extend the documentation of this file
 *       <2> replace link to bitkeeper suite
 */
class testExample extends xarTestCase
{
    /**
     * Initialize the Xarigami core.
     */
    function setup()
    {
        $GLOBALS['xarDebug'] = false;

        /* these must point to the correct location of the core */
        sys::import('xarigami.xarCore');
        sys::import('xarigami.xarLog');
        sys::import('xarigami.xarVar');
        sys::import('xarigami.xarException');
    }

    /**
     * Here is an example of a test which is expected to pass. As well as
     * assertSame, we also have:
     *    assertEquals($actual, $expected, $delta, $msg)
     *    assertNonNull($object, $msg)
     *    assertNull($object, $msg)
     *    assertSame($actual, $expected, $msg)
     *    assertNotSame($actual, $expected, $msg)
     *    assertTrue($condition, $msg)
     *    assertFalse($condition, $msg)
     *    assertRegExp($actual, $expected, $msg)
     *    assertEmpty($actual, $msg)  // for arrays only
     *
     * @see BitKeeper/custom/unittest/xarUnitTest.php
     */
    function testSame()
    {
        $in = "EXAMPLE";
        $expected = "example";
        $out = strtolower($in);
        return $this->assertSame($out,$expected,"Testing using assertSame");
    }
}

/* add the tests to the suite */
$tmp->AddTestCase('testExample', 'Tests for the example module');

/* add this suite to the list */
$suites[] = $tmp;

?>