<?php
/**
 * Utility function pass individual menu items to the main menu
 *
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Example Module
 * @copyright (C) 2007-2011 2skies.com
 * @link http://xarigami.com/projects
 * @author Xarigami Team
 */

/**
 * Utility function pass individual menu items to the main menu
 *
 * @author the Example module development team
 * @return array containing the menulinks for the main menu items.
 */
function example_userapi_getmenulinks()
{
    /* First we need to do a security check to ensure that we only return menu items
     * that we are suppose to see.
     */
    if (xarSecurityCheck('ViewExample', 0)) {
        /* The main menu will look for this array and return it for a tree view of the module
         * We are just looking for three items in the array, the url, which we need to use the
         * xarModURL function, the title of the link, which will display a tool tip for the
         * module url, in order to keep the label short, and finally the exact label for the
         * function that we are displaying.
         */
        $menulinks[] = array('url' => xarModURL('example','user','view'),
            /* In order to display the tool tips and label in any language,
             * we must encapsulate the calls in the xarML in the API.
             */
            'title' => xarML('View a listing of all example items for view'),
            'label' => xarML('View All'),
            'active' => array('view','display'),
            'activelabels' => array('', xarML('Display item'))

            );
    }
    /* If we return nothing, then we need to tell PHP this, in order to avoid an ugly
     * E_ALL error.
     */
    if (empty($menulinks)) {
        $menulinks = '';
    }
    /* The final thing that we need to do in this function is return the values back
     * to the main menu for display.
     */
    return $menulinks;
}
?>