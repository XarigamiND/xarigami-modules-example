<?php
/**
 * Create a new item
 *
 * @package modules
 * @copyright (C) 2002-2007 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Example module
 * @copyright (C) 2007-2011 2skies.com
 * @link http://xarigami.com/projects/xarigami_core
 * @author Xarigami Team
 */
/**
 * Create a new item
 *
 * Standard function to create a new item
 * This is a standard function that is called with the results of the
 * form supplied by xarModFunc('example','admin','new') to create a new item
 *
 * @author Example module development team
 * @param  string $args['name']   the name of the item to be created
 * @param  int    $args['number'] the number of the item to be created
 */
function example_admin_create($args)
{
    /* Admin functions of this type can be called by other modules. If this
     * happens then the calling module will be able to pass in arguments to
     * this function through the $args parameter. Hence we extract these
     * arguments *before* we have obtained any form-based input through
     * xarVarFetch().
     */
    extract($args);

    /* Get parameters from whatever input we need. All arguments to this
     * function should be obtained from xarVarFetch(). xarVarFetch allows
     * the checking of the input variables as well as setting default
     * values if needed. Getting vars from other places such as the
     * environment is not allowed, as that makes assumptions that will
     * not hold in future versions of Xaraya
     */
    if (!xarVarFetch('exid',     'id',     $exid,     $exid, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('objectid', 'id',     $objectid, $objectid, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('invalid',  'str:1:', $invalid,  '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('number',   'int:1:', $number,   NULL, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('name',     'str:1:', $name,     '', XARVAR_NOT_REQUIRED)) return;
    /* Argument check - make sure that all required arguments are present
     * and in the right format, if not then return to the add form with the
     * values that are there and a message with a session var. If you perform
     * this check now, you could do away with the check in the API along with
     * the exception that comes with it.
     */
    $item = xarModAPIFunc('example', 'user', 'validateitem',
                          array('name' => $name));
    // Argument check
    $invalid = array();
    if (empty($number) || !is_numeric($number)) {
         //We ensure this invalid element is not empty, and set a default error message
        $invalid['number'] =  xarML('INVALID - Example Number must be numeric and not empty.');
        $number = '';
    }
    if (empty($name) || !is_string($name)) {
         //We ensure this invalid element is not empty, and set a default error message
        $invalid['name'] =  xarML('INVALID - Example Name must not be empty.');
        $name = '';
    }

    if (!empty($name) && $item['name'] == $name) {
        $invalid['name'] = xarML('INVALID - Example Name is a duplicate.');
    }
    // check if we have any errors
    if (count($invalid) > 0) {
        /* If we get here, we have encountered errors.
         * Send the user back to the admin_new form
         * call the admin_new function and return the template vars
         */
        return xarModFunc('example', 'admin', 'new',
                          array('name' => $name,
                                'number' => $number,
                                'invalid' => $invalid));
    }
    /* Confirm authorisation code. This checks that the form had a valid
     * authorisation code attached to it. If it did not then the function will
     * proceed no further as it is possible that this is an attempt at sending
     * in false data to the system
     */
    if (!xarSecConfirmAuthKey()) return;
    /* Notable by its absence there is no security check here. This is because
     * the security check is carried out within the API function and as such we
     * do not duplicate the work here
     * The API function is called. Note that the name of the API function and
     *the name of this function are identical, this helps a lot when
     * programming more complex modules. The arguments to the function are
     * passed in as their own arguments array
     */
    $exid = xarModAPIFunc('example', 'admin', 'create',
                          array('name' => $name,
                                'number' => $number));
    /* The return value of the function is checked here, and if the function
     * suceeded then an appropriate message is posted. Note that if the
     * function did not succeed then the API function should have already
     * posted a failure message so no action is required
     */
    if ($exid) {
        $msg = xarML('New item was successfully created.');
        xarTplSetMessage($msg,'status');
    } else {
        $msg = xarML('There was a problem when adding your item to the database and it was not created.');
        xarTplSetMessage($msg,'error');
    }
    /* This function generated no output, and so now it is complete we redirect
     * the user to an appropriate page for them to carry on their work.
     *
     * For that, we determine the itemposition and make sure we start at the position of
     * that item. This is obviously a lot more complicated usually than in this simple
     * situation where sorting (by name here) /filtering/where clauses are more complex.
     * In real modules, this needs to be *a lot smarter* than the example given here.
     *
     * A slightly better way would be to set view order on the exid, and use the recordcount
     * as starting position, or order descending on exid and use 1 as starting number.
     * The actual functionality of your module will determin what is best.
     */
    $allItems = xarModApiFunc('example','user','getall');
    $newItemPosition = 0;
    foreach($allItems as $pos => $info) {
        if($info['exid']==$exid) {  $newItemPosition = $pos; break; }
    }
    xarResponseRedirect(xarModURL('example', 'admin', 'view',array('startnum' => $newItemPosition)));

    /* Return true, in this case */
    return true;
}
?>
