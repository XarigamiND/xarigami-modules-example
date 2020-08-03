<?php
/**
 * Create a new example item
 *
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Example module
 * @copyright (C) 2007,2008,2009 2skies.com
 * @link http://xarigami.com/projects/xarigami_core
 * @author Xarigami Team
 */

/**
 * Create a new example item
 *
 * This is a standard adminapi function to create a module item
 *
 * @author the Example module development team
 * @param  string $args['name'] name of the item
 * @param  int    $args['number'] number of the item
 * @return int example item ID on success, false on failure
 * @throws BAD_PARAM, NO_PERMISSION, DATABASE_ERROR
 */
function example_adminapi_create($args)
{
    /* Get arguments from argument array - all arguments to this function
     * should be obtained from the $args array, getting them from other
     * places such as the environment is not allowed, as that makes
     * assumptions that will not hold in future versions of Xaraya
     */
    extract($args);
    /* Argument check - make sure that all required arguments are present
     * and in the right format, if not then set an appropriate error
     * message and return
     * Note : since we have several arguments we want to check here, we'll
     * report all those that are invalid at the same time...
     */
    $invalid = array();
    if (!isset($name) || !is_string($name)) {
        $invalid[] = 'name';
    }
    if (!isset($number) || !is_numeric($number)) {
        $invalid[] = 'number';
    }
    if (count($invalid) > 0) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
            join(', ', $invalid), 'admin', 'create', 'Example');
        xarErrorSet(XAR_SYSTEM_EXCEPTION, 'BAD_PARAM',
            new SystemException($msg));
        return;
    }
    /* Security check - important to do this as early on as possible to
     * avoid potential security holes or just too much wasted processing
     */
    if (!xarSecurityCheck('AddExample', 1, 'Item', "$name:All:All")) {
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
    /* Get next ID in table - this is required prior to any insert that
     * uses a unique ID, and ensures that the ID generation is carried
     * out in a database-portable fashion
     */
    $nextId = $dbconn->GenId($exampletable);
    /* Add item - the formatting here is not mandatory, but it does make
     * the SQL statement relatively easy to read. Also, separating out
     * the sql statement from the Execute() command allows for simpler
     * debug operation if it is ever needed
     */
    $query = "INSERT INTO $exampletable (
              xar_exid,
              xar_name,
              xar_number)
            VALUES (?,?,?)";
    /* Create an array of values which correspond to the order of the
     * Question marks  in the statement above. The database layer will then
     * figure out what to do with these variables before actually sending them
     * to the database. (such as quoting, escaping or other operations specific to
     * the backend)
     * In some cases you need to explicitly state the type of the variable like
     *in the $name variable below (not needed here, just for educational purposes)
     */
    $bindvars = array($nextId, (string) $name, $number);
    $result = $dbconn->Execute($query,$bindvars);

    /* Check for an error with the database code, adodb has already raised
     * the exception so we just return
     */
    if (!$result) return;

    /* Get the ID of the item that we inserted. It is possible, depending
     * on your database, that this is different from $nextId as obtained
     * above, so it is better to be safe than sorry in this situation
     */
    $exid = $dbconn->PO_Insert_ID($exampletable, 'xar_exid');

    /* Let any hooks know that we have created a new item. As this is a
     * create hook we're passing 'exid' as the extra info, which is the
     * argument that all of the other functions use to reference this
     * item
     * TODO: evaluate
     * xarModCallHooks('item', 'create', $exid, 'exid');
     */
    $item = $args;
    $item['module'] = 'example';
    $item['itemid'] = $exid;
    xarModCallHooks('item', 'create', $exid, $item);
    /* Return the id of the newly created item to the calling process */
    return $exid;
}
?>