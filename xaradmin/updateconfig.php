<?php
/**
 * Standard function to update module configuration parameters
 *
 * @package Xaraya
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Example module
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/projects/xarigami_core
 * @author Xarigami Team
 */

/**
 * Standard function to update module configuration parameters
 *
 * This is a standard function to update the configuration parameters of the
 * module given the information passed back by the modification form
 * @author Example module development team
 */
function example_admin_updateconfig()
{
    /* Get parameters from whatever input we need. All arguments to this
     * function should be obtained from xarVarFetch(). xarVarFetch allows
     * the checking of the input variables as well as setting default
     * values if needed. Getting vars from other places such as the
     * environment is not allowed, as that makes assumptions that will
     * not hold in future versions of Xaraya
     */
    if (!xarVarFetch('bold',         'checkbox', $bold, false, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('itemsperpage', 'int',      $itemsperpage, 10, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('shorturls',    'checkbox', $shorturls, false, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('aliasname',    'str:1:',   $aliasname, '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('modulealias',  'checkbox', $modulealias,false,XARVAR_NOT_REQUIRED)) return;

    /* Confirm authorisation code. This checks that the form had a valid
     * authorisation code attached to it. If it did not then the function will
     * proceed no further as it is possible that this is an attempt at sending
     * in false data to the system
     */

    if (!xarSecConfirmAuthKey()) return;
    /* Update module variables. Note that the default values are set in
     * xarVarFetch when recieving the incoming values, so no extra processing
     * is needed when setting the variables here.
     */
    xarModSetVar('example', 'bold', $bold);
    xarModSetVar('example', 'itemsperpage', $itemsperpage);
    xarModSetVar('example', 'SupportShortURLs', $shorturls);

    if (isset($aliasname) && trim($aliasname)<>'') {
        xarModSetVar('example', 'useModuleAlias', $modulealias);
    } else{
         xarModSetVar('example', 'useModuleAlias', 0);
    }
    $currentalias = xarModGetVar('example','aliasname');
    $newalias = trim($aliasname);
    /* Get rid of the spaces if any, it's easier here and use that as the alias*/
    if ( strpos($newalias,'_') === FALSE )
    {
        $newalias = str_replace(' ','_',$newalias);
    }
    $hasalias= xarModGetAlias($currentalias);
    $useAliasName= xarModGetVar('example','useModuleAlias');
    //<jojodee> we need to ensure any old aliases are deleted
    /*
    1. Use mod alias chosen - has a valid alias given
       - if it the same as existing alias - do nothing else
       - remove any existing alias, set the mod vars to true and new alias
    2. Use mod alias chosen - no valid alias
       - remove any existing alias, set the mod vars to false and empty
    3. Do not use module alias
       - remove any existing alias, set the mod vars to false and empty
    */
    // if a new one is set or if there is an old one there and we don't want to use alias anymore
    if ($useAliasName && !empty($newalias)) {
         if ($aliasname != $currentalias)
         /* First check for old alias and delete it */
            if (isset($hasalias) && ($hasalias =='example')){
                xarModDelAlias($currentalias,'example');
            }
            /* now set the new alias if it's a new one */
            $newalias = xarModSetAlias($newalias,'example');
            if (!$newalias) { //name already taken so unset
                 xarModSetVar('example', 'aliasname', '');
                 xarModSetVar('example', 'useModuleAlias', false);
            } else { //it's ok to set the new alias name
                xarModSetVar('example', 'aliasname', $aliasname);
                xarModSetVar('example', 'useModuleAlias', $modulealias);
            }
    } else {
       //remove any existing alias and set the vars to none and false
            if (isset($hasalias) && ($hasalias =='example')){
                xarModDelAlias($currentalias,'example');
            }
            xarModSetVar('example', 'aliasname', '');
            xarModSetVar('example', 'useModuleAlias', false);
    }


    xarModCallHooks('module','updateconfig','example',
                   array('module' => 'example'));

    $msg = xarML('Configuration settings were successfully updated.');
        xarTplSetMessage($msg,'status');
    /* This function generated no output, and so now it is complete we redirect
     * the user to an appropriate page for them to carry on their work
     */
    xarResponseRedirect(xarModURL('example', 'admin', 'modifyconfig'));

    /* Return */
    return true;
}
?>