<?php
/**
 * Get all example items
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
 * Get all example items
 *
 * @author the Example module development team
 * @param int $args['numitems'] the number of items to retrieve (default -1 = all)
 * @param int $args['startnum'] start with this item number (default 1)
 * @return mixed array of items, or false on failure
 * @throws BAD_PARAM, DATABASE_ERROR, NO_PERMISSION
 */
function example_userapi_getall($args)
{
    /* Get arguments from argument array - all arguments to this function
     * should be obtained from the $args array, getting them from other places
     * such as the environment is not allowed, as that makes assumptions that
     * will not hold in future versions of Xaraya
     */
    extract($args);
    /* Optional arguments.
     * FIXME: (!isset($startnum)) was ignoring $startnum as it contained a null value
     * replaced it with ($startnum == "") (thanks for the talk through Jim S.) NukeGeek 9/3/02
     * if (!isset($startnum)) { */
    if (!isset($startnum)) {
        $startnum = 1;
    }
    if (!isset($numitems)) {
        $numitems = -1;
    }
    if (!isset($catid)) {
        $catid = 0;
    }
    /* Argument check - make sure that all required arguments are present and
     * in the right format, if not then set an appropriate error message
     * and return
     * Note : since we have several arguments we want to check here, we'll
     * report all those that are invalid at the same time...
     */
    $invalid = array();
    if (!isset($startnum) || !is_numeric($startnum)) {
        $invalid[] = 'startnum';
    }
    if (!isset($numitems) || !is_numeric($numitems)) {
        $invalid[] = 'numitems';
    }
    if (count($invalid) > 0) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
            join(', ', $invalid), 'user', 'getall', 'Example');
       throw new BadParameterException(null,$msg);
    }

    $items = array();
    /* Security check - important to do this as early on as possible to
     * avoid potential security holes or just too much wasted processing
     */
    if (!xarSecurityCheck('ViewExample')) return;
    /* Get database setup - note that both xarDBGetConn() and xarDBGetTables()
     * return arrays but we handle them differently. For xarDBGetConn() we
     * currently just want the first item, which is the official database
     * handle. For xarDBGetTables() we want to keep the entire tables array
     * together for easy reference later on
     */
    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    /* It's good practice to name the table definitions you are
     * using - $table doesn't cut it in more complex modules
     */
    $exampletable = $xartable['example'];

    /* Get items - the formatting here is not mandatory, but it does make the
     * SQL statement relatively easy to read. Also, separating out the sql
     * statement from the SelectLimit() command allows for simpler debug
     * operation if it is ever needed
     */
    $query = "SELECT xar_exid,
                     xar_name,
                     xar_number
              FROM $exampletable
              ORDER BY xar_name";
    /* We can also select on a part of the data. When the categories module is present,
     * you can use it to select a group of items based on the category they are in.
     * Then replace the query above with the query below, and make sure you pass the catid in your templates.
     */

    /* We do not use any itemtype in this module, so set the itemtype to NULL
    $thistype= NULL;
    if (xarModIsHooked('categories','example',$thistype)) {
       // Get the LEFT JOIN ... ON ...  and WHERE parts from categories
       $categoriesdef = xarModAPIFunc('categories','user','leftjoin',
                                       array('modid'    => xarModGetIDFromName('example'),
                                             'itemtype' => $thistype,
                                             'catid'    => $catid));
       $query = "SELECT xar_exid,
                        xar_name,
                        xar_number
                 FROM ( $exampletable
                 LEFT JOIN $categoriesdef[table]
                 ON $categoriesdef[field] = xar_exid )
                 $categoriesdef[more]
                 WHERE $categoriesdef[where]
                 ORDER BY xar_name";
    } else {
       $query = "SELECT xar_exid,
                        xar_name,
                        xar_number
                 FROM $exampletable
                 ORDER BY xar_name";
    }
    */
    /* SelectLimit also supports bind variable, they get to be put in
     * as the last parameter in the function below. In this case we have no
     * bind variables, so we left the parameter out. We could have passed in an
     * empty array though.
     */
    $result = $dbconn->SelectLimit($query, $numitems, $startnum-1);
    /* Check for an error with the database code, adodb has already raised
     * the exception so we just return
     */
    if (!$result) return;
    /* Put items into result array. Note that each item is checked
     * individually to ensure that the user is allowed *at least* OVERVIEW
     * access to it before it is added to the results array.
     * If more severe restrictions apply, e.g. for READ access to display
     * the details of the item, this *must* be verified by your function.
     */
    for (; !$result->EOF; $result->MoveNext()) {
        list($exid, $name, $number) = $result->fields;
        if (xarSecurityCheck('ViewExample', 0, 'Item', "$name:All:$exid")) {
            $items[] = array('exid'   => $exid,
                             'name'   => $name,
                             'number' => $number);
        }
    }
    /* All successful database queries produce a result set, and that result
     * set should be closed when it has been finished with
     */
    $result->Close();
    /* Return the items */
    return $items;
}
?>