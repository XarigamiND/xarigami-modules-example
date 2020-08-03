<?php
/**
 * Table definition functions
 *
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Example module
 * @copyright (C) 2007-2011 2skies.com
 * @link http://xarigami.com/projects/xarigami_core
 * @author Xarigami Team
 */
/**
 * Table definition functions
 *
 * Return Example module table names to xaraya
 *
 * This function is called internally by the core whenever the module is
 * loaded. It is loaded by xarMod__loadDbInfo().
 * @author Example Module development team
 * @access private
 * @return array
 */
function example_xartables()
{
    /* Initialise table array */
    $xarTables = array();
    /* Get the name for the example item table. This is not necessary
     * but helps in the following statements and keeps them readable
     */
    $exampleTable = xarDBGetSiteTablePrefix() . '_example';

    /* Set the table name */
    $xarTables['example'] = $exampleTable;
    /* Return the table information */
    return $xarTables;
}
?>