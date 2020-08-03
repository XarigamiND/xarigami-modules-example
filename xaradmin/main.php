<?php
/**
 * The main administration function
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
 * The main administration function
 *
 * This function is the default function, and is called whenever the
 * module is initiated without defining arguments. As such it can
 * be used for a number of things, but most commonly it either just
 * shows the module menu and returns or calls whatever the module
 * designer feels should be the default function (often this is the
 * view() function)
 *
 * @author Example Module Development Team
 * @access public
 * @return Specify your return type here
 */
function example_admin_main()
{
    /* Security check - important to do this as early as possible to avoid
     * potential security holes or just too much wasted processing. For the
     * main function we want to check that the user has at least edit privilege
     * for some item within this component, or else they won't be able to do
     * anything and so we refuse access altogether. The lowest level of access
     * for administration depends on the particular module, but it is generally
     * either 'edit' or 'delete'
     */
    if (!xarSecurityCheck('EditExample')) return;
       /* If you want to go directly to some default function, instead of
         * having a separate main function, you can simply call it here, and
         * use the same template for admin-main.xd as for admin-view.xd
         * return xarModFunc('example','admin','view');
         */

        /* Initialise the $data variable that will hold the data to be used in
         * the blocklayout template, and get the common menu configuration - it
         * helps if all of the module pages have a standard menu at the top to
         * support easy navigation
         */
        //common menu
        $data['menulinks'] = xarModAPIFunc('base','admin','getmenulinks');

        /* You could specify some other variables to use in the blocklayout template
         *$data['welcome'] = xarML('Welcome to the administration part of this Example module...');
         * Return the template variables defined in this function
         */

        //return $data;

        /* Note : instead of using the $data variable, you could also specify
         * the different template variables directly in your return statement :
         */

        /* return array('menutitle' => ...,
         * 'welcome' => ...,
         * ... => ...);
         */
        /* If no main function as such, just return the view page,
         * or whatever function seems to be the most fitting for this module.
         */
        xarResponseRedirect(xarModURL('example', 'admin', 'view'));

    /* success so return true */
    return true;
}
?>