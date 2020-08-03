<?php
/**
 * Delete an example item
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
 * Delete an example item
 *
 * Standard function to delete a module item
 *
 * @author the Example module development team
 * @param  $args ['exid'] ID of the item
 * @return bool true on success, false on failure
 * @throws BAD_PARAM, NO_PERMISSION, DATABASE_ERROR
 */
function example_adminapi_delete($args)
{
    /* Get arguments from argument array - all arguments to this function
     * should be obtained from the $args array, getting them from other
     * places such as the environment is not allowed, as that makes
     * assumptions that will not hold in future versions of Xaraya
     */
    extract($args);
    /* Argument check - make sure that all required arguments are present and
     * in the right format, if not then set an appropriate error message
     * and return
     */
    if (!isset($exid) || !is_numeric($exid)) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
            'item ID', 'admin', 'delete', 'Example');
        throw new BadParameterException(null,$msg);
    }
    /* The user API function is called. This takes the item ID which
     * we obtained from the input and gets us the information on the
     * appropriate item. If the item does not exist we post an appropriate
     * message and return
     */
    $item = xarModAPIFunc('example', 'user','get',
        array('exid' => $exid));

    /* Security check - important to do this as early on as possible to
     * avoid potential security holes or just too much wasted processing.
     * However, in this case we had to wait until we could obtain the item
     * name to complete the instance information so this is the first
     * chance we get to do the check
     */
    if (!xarSecurityCheck('DeleteExample', 1, 'Item', "$item[name]:All:$exid")) {
        return;
    }
    /* Get database setup - note that both xarDBGetConn() and xarDBGetTables()
     * return arrays but we handle them differently. For xarDBGetConn()
     * we currently just want the first item, which is the official
     * database handle. For xarDBGetTables() we want to keep the entire
     * tables array together for easy reference later on
     */
    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    /* It's good practice to name the table and column definitions you
     * are getting - $table and $column don't cut it in more complex
     * modules
     */
    $exampletable = $xartable['example'];
    /* Delete the item - the formatting here is not mandatory, but it does
     * make the SQL statement relatively easy to read. Also, separating
     * out the sql statement from the Execute() command allows for simpler
     * debug operation if it is ever needed
     */
    $query = "DELETE FROM $exampletable WHERE xar_exid = ?";

    /* The bind variable $exid is directly put in as a parameter. */
    $result = $dbconn->Execute($query,array($exid));

    /* Check for an error with the database code, adodb has already raised
     * the exception so we just return
     */
    if (!$result) return;
    /* Let any hooks know that we have deleted an item. As this is a
     * delete hook we're not passing any extra info
     * xarModCallHooks('item', 'delete', $exid, '');
     */
    $item['module'] = 'example';
    $item['itemid'] = $exid;
    xarModCallHooks('item', 'delete', $exid, $item);

    /* Let the calling process know that we have finished successfully */
    return true;
}
?>