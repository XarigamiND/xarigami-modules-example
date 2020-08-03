<?php
/**
 * Standard function to view items
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
 * Standard function to view items
 *
 * @author Example module development team
 * @return array
 */
function example_admin_view()
{
    /* Get parameters from whatever input we need. All arguments to this
     * function should be obtained from xarVarFetch(). xarVarFetch allows
     * the checking of the input variables as well as setting default
     * values if needed. Getting vars from other places such as the
     * environment is not allowed, as that makes assumptions that
     * will not hold in future versions of Xaraya
     */
    if (!xarVarFetch('startnum', 'int:1:', $startnum, 1, XARVAR_NOT_REQUIRED)) return;
    /* Initialise the $data variable that will hold the data to be used in
     * the blocklayout template, and get the common menu configuration - it
     * helps if all of the module pages have a standard menu at the top to
     * support easy navigation
     */
    //common menu
    $data['menulinks'] = xarModAPIFunc('example','admin','getmenulinks');
    /* Initialise the variable that will hold the items, so that the template
     * doesn't need to be adapted in case of errors
     */
    $data['items'] = array();

    /* Call the xarTPL helper function to produce a pager in case of there
     * being many items to display.
     *
     *
     * Note that this function includes another user API function. The
     * function returns a simple count of the total number of items in the item
     * table so that the pager function can do its job properly
     */
    $data['pager'] = xarTplGetPager($startnum,
        xarModAPIFunc('example', 'user', 'countitems'),
        xarModURL('example', 'admin', 'view', array('startnum' => '%%')),
        xarModGetVar('example', 'itemsperpage'));
    /* Security check - important to do this as early as possible to avoid
     * potential security holes or just too much wasted processing
     */
    if (!xarSecurityCheck('EditExample')) return;
    /* The user API function is called. This takes the number of items
     * required and the first number in the list of all items, which we
     * obtained from the input and gets us the information on the appropriate
     * items.
     */
    $items = xarModAPIFunc('example',
                           'user',
                           'getall',
                            array('startnum' => $startnum,
                                  'numitems' => xarModGetVar('example','itemsperpage')));
    /* Check for exceptions */
    if (!isset($items) && xarCurrentErrorType() != XAR_NO_EXCEPTION) return; /* throw back */

    /* Check individual permissions for Edit / Delete
     * Note : we could use a foreach ($items as $item) here as well, as
     * shown in xaruser.php, but as an example, we'll adapt the $items array
     * 'in place', and *then* pass the complete items array to $data
     */

    /* Create an authid for use in delete links */
    $authid = xarSecGenAuthKey();

    for ($i = 0; $i < count($items); $i++) {
        $item = $items[$i];
        if (xarSecurityCheck('EditExample', 0, 'Item', "$item[name]:All:$item[exid]")) {
            $items[$i]['editurl'] = xarModURL('example', 'admin', 'modify',
                array('exid' => $item['exid']));
        } else {
            $items[$i]['editurl'] = '';
        }
        if (xarSecurityCheck('DeleteExample', 0, 'Item', "$item[name]:All:$item[exid]")) {
            $items[$i]['deleteurl'] = xarModURL('example', 'admin', 'delete',
                array('exid' => $item['exid'], 'authid' => $authid));
        } else {
            $items[$i]['deleteurl'] = '';
        }
    }
    /* Add the array of items to the template variables */
    $data['items'] = $items;

    /* Return the template variables defined in this function */
    return $data;
    /* Note : instead of using the $data variable, you could also specify
     * the different template variables directly in your return statement :
     *
     * return array('items' => ...,
     * 'namelabel' => ...,
     *... => ...);
     */
}
?>