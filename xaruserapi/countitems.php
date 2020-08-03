<?php
/**
 * Utility function to count the number of items held by this module
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
 * Utility function to count the number of items held by this module
 *
 * @author the Example module development team
 * @return integer number of items held by this module
 * @throws DATABASE_ERROR
 */
function example_userapi_countitems()
{
    /* Get database setup - note that both xarDBGetConn() and xarDBGetTables()
     * return arrays but we handle them differently. For xarDBGetConn() we
     * currently just want the first item, which is the official database
     * handle. For xarDBGetTables() we want to keep the entire tables array
     * together for easy reference later on
     */
    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    /* It's good practice to name the table and column definitions you are
     * getting - $table and $column don't cut it in more complex modules
     */
    $exampletable = $xartable['example'];
    /* Get item - the formatting here is not mandatory, but it does make the
     * SQL statement relatively easy to read. Also, separating out the sql
     * statement from the Execute() command allows for simpler debug operation
     * if it is ever needed
     */
    $query = "SELECT COUNT(1)
            FROM $exampletable";
    /* If there are no variables you can pass in an empty array for bind variables
     * or no parameter.
     */
    $result = $dbconn->Execute($query,array());
    /* Check for an error with the database code, adodb has already raised
     * the exception so we just return
     */
    if (!$result) return;
    /* Obtain the number of items */
    list($numitems) = $result->fields;
    /* All successful database queries produce a result set, and that result
     * set should be closed when it has been finished with
     */
    $result->Close();
    /* Return the number of items */
    return $numitems;
}
?>