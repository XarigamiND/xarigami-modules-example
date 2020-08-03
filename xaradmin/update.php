<?php
/**
 * Standard function to update a current item
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
 * Standard function to update a current item
 *
 * This function is called with the results of the
 * form supplied by xarModFunc('example','admin','modify') to update a current item
 *
 * @author Example module development team
 * @param  int    $args['exid']  the id of the item to be updated
 * @param  string $args['name']  the name of the item to be updated
 * @param  int    $args['number'] the number of the item to be updated
 */
function example_admin_update($args)
{
    /* Admin functions of this type can be called by other modules. If this
     * happens then the calling module will be able to pass in arguments to
     * this function through the $args parameter. Hence we extract these
     * arguments *before* we have obtained any form-based input through
     * xarVarFetch(), so that parameters passed by the modules can also be
     * checked by a certain validation.
     */
    extract($args);

    /* Get parameters from whatever input we need. All arguments to this
     * function should be obtained from xarVarFetch(). xarVarFetch allows
     * the checking of the input variables as well as setting default
     * values if needed. Getting vars from other places such as the
     * environment is not allowed, as that makes assumptions that will
     * not hold in future versions of Xaraya
     */
    if (!xarVarFetch('exid',     'id',     $exid,     $exid,     XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('objectid', 'id',     $objectid, $objectid, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('invalid',  'array',  $invalid,  $invalid,        XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('number',   'int:1:', $number,   $number,   XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('name',     'str:1:', $name,     $name,     XARVAR_NOT_REQUIRED)) return;

    /* At this stage we check to see if we have been passed $objectid, the
     * generic item identifier. This could have been passed in by a hook or
     * through some other function calling this as part of a larger module, but
     * if it exists it overrides $exid
     *
     * Note that this module could just use $objectid everywhere to avoid all
     * of this munging of variables, but then the resultant code is less
     * descriptive, especially where multiple objects are being used. The
     * decision of which of these ways to go is up to the module developer
     */
    if (!empty($objectid)) {
        $exid = $objectid;
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
     */

    $invalid = array();
    if (empty($number) || !is_numeric($number)) {
        //We ensure this invalid element is not empty, and set a default error message
        $invalid['number'] = xarML('INVALID - Example Number must be numeric and not empty.');
        $number = '';
    }
    if (empty($name) || !is_string($name)) {
        //We ensure this invalid element is not empty, and set a default error message
        $invalid['name'] =  xarML('INVALID - Example Name must not be empty.');
        $name = '';
    }

    /* check if we have any errors */
    if (count($invalid) > 0) {
        /* call the admin_new function and return the template vars
         * (you need to copy admin-new.xd to admin-create.xd here)
         */
        return xarModFunc('example', 'admin', 'modify',
                          array('name'     => $name,
                                'number'   => $number,
                                'invalid'  => $invalid));
    }

    /* The API function is called. Note that the name of the API function and
     * the name of this function are identical, this helps a lot when
     * programming more complex modules. The arguments to the function are
     * passed in as their own arguments array.
     *
     * The return value of the function is checked here, and if the function
     * suceeded then an appropriate message is posted. Note that if the
     * function did not succeed then the API function should have already
     * posted a failure message so no action is required
     */
    if (!xarModAPIFunc('example', 'admin', 'update',
                       array('exid'   => $exid,
                             'name'   => $name,
                             'number' => $number))) {
        return; /* throw back */
    }
    $msg = xarML('The example item was successfully updated');
    xarTplSetMessage($msg,'status');
    /* This function generated no output, and so now it is complete we redirect
     * the user to an appropriate page for them to carry on their work
     */
    xarResponseRedirect(xarModURL('example', 'admin', 'view'));
    /* Return */
    return true;
}
?>