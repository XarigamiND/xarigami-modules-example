<?php
/**
 * Update an example item
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
 * Update an example item
 *
 * This function takes in the data from admin_update and
 * saves the data it the appriopriate table
 *
 * @author the Example module development team
 * @param  $args ['exid'] the ID of the item
 * @param  $args ['name'] the new name of the item
 * @param  $args ['number'] the new number of the item
 * @return bool true on success of update
 * @throws BAD_PARAM, NO_PERMISSION, DATABASE_ERROR
 */
function example_adminapi_update($args)
{
    /* Get arguments from argument array - all arguments to this function
     * should be obtained from the $args array, getting them from other
     * places such as the environment is not allowed, as that makes
     * assumptions that will not hold in future versions of Xaraya
     */
    extract($args);
    /* Note the absence of a xarVarFetch function here. Remember that xarVarFetch
     * gets environmental variables, and therefore can fetch variables that you do not want in here.
     * This function can be called from others than just the admin_update one
     */
    /* Argument check - make sure that all required arguments are present
     * and in the right format, if not then set an appropriate error
     * message and return
     * Note : since we have several arguments we want to check here, we'll
     * report all those that are invalid at the same time...
     */
    $invalid = array();
    if (!isset($exid) || !is_numeric($exid)) {
        $invalid[] = 'item ID';
    }
    if (!isset($name) || !is_string($name)) {
        $invalid[] = 'name';
    }
    if (!isset($number) || !is_numeric($number)) {
        $invalid[] = 'number';
    }
    if (count($invalid) > 0) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
            join(', ', $invalid), 'admin', 'update', 'Example');
        xarErrorSet(XAR_SYSTEM_EXCEPTION, 'BAD_PARAM',
            new SystemException($msg));
        return;
    }
    /* The user API function is called. This takes the item ID which
     * we obtained from the input and gets us the information on the
     * appropriate item. If the item does not exist we post an appropriate
     * message and return
     */
    $item = xarModAPIFunc('example',
        'user',
        'get',
        array('exid' => $exid));
    /*Check for exceptions */
    if (!isset($item) && xarCurrentErrorType() != XAR_NO_EXCEPTION) return; // throw back

    /* Security check - important to do this as early on as possible to
     * avoid potential security holes or just too much wasted processing.
     * However, in this case we had to wait until we could obtain the item
     * name to complete the instance information so this is the first
     * chance we get to do the check
     * Note that at this stage we have two sets of item information, the
     * pre-modification and the post-modification. We need to check against
     * both of these to ensure that whoever is doing the modification has
     * suitable permissions to edit the item otherwise people can potentially
     * edit areas to which they do not have suitable access
     */
    if (!xarSecurityCheck('EditExample', 1, 'Item', "$item[name]:All:$exid")) {
        return;
    }
    if (!xarSecurityCheck('EditExample', 1, 'Item', "$name:All:$exid")) {
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
    /* Update the item - the formatting here is not mandatory, but it does
     * make the SQL statement relatively easy to read. Also, separating
     * out the sql statement from the Execute() command allows for simpler
     * debug operation if it is ever needed
     */
    $query = "UPDATE $exampletable
            SET xar_name =?, xar_number = ?
            WHERE xar_exid = ?";
    /* We use the $bindvars method here to increase the security and
     * to make sure that the data we enter is clean. It lets the database layer
     * take care of -a part of- the datachecking
     */
    $bindvars = array($name, $number, $exid);
    $result = $dbconn->Execute($query,$bindvars);
    /* Check for an error with the database code, adodb has already raised
     * the exception so we just return
     */
    if (!$result) return;
    /* Let any hooks know that we have updated an item. As this is an
     * update hook we're passing the updated $item array as the extra info
     */
    $item['module'] = 'example';
    $item['itemid'] = $exid;
    /* We set the itemtype to NULL here, as we do not use it. When your module does use itemtypes,
     * then add the appropriate one here
     */
    $item['itemtype'] = NULL;
    $item['name'] = $name;
    $item['number'] = $number;
    xarModCallHooks('item', 'update', $exid, $item);

    /* Let the calling process know that we have finished successfully */
    return true;
}
?>