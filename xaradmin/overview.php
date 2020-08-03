<?php
/**
 * Displays standard Overview page
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
 * Overview function that displays the standard Overview page
 *
 * This function shows the overview template, currently admin-main.xd.
 * The template contains overview and help texts
 *
 * @author the Example module development team
 * @return array xarTplModule with $data containing template data
 * @since 3 Sept 2005
 */
function example_admin_overview()
{
   /* Security Check */
    if (!xarSecurityCheck('AdminExample',0)) return;

    $data=array();
    //common menu
    $data['menulinks'] = xarModAPIFunc('example','admin','getmenulinks');

    // If you want to return this data directly to the template with the same name ('overview')
    return $data;

    // Otherwise return the data to the template of your choice
    // - in this case the 'main' template as it doesn't have it's own function
    //return xarTplModule('example', 'admin', 'main', $data,'main');
}

?>
