<?php
/**
 * Example Module - documented module template
 *
 * @package modules
 * @copyright (C) 2002-2008 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Example Module
 * @copyright (C) 2007-2011 2skies.com
 * @link http://xarigami.com/projects
 * @author Xarigami Team
 */
$modversion['name']           = 'example'; /* lowercase, no spaces or special chars */
$modversion['directory']      = 'example'; /* lowercase, no spaces or special chars */
$modversion['id']             = '36';
$modversion['version']        = '1.5.2'; /* three point version number */
$modversion['displayname']    = 'Example';
$modversion['description']    = 'Documented example and template for new modules';
$modversion['credits']        = 'xardocs/credits.txt';
$modversion['help']           = 'xardocs/help.txt';
$modversion['changelog']      = 'xardocs/changelog.txt';
$modversion['license']        = 'xardocs/license.txt';
$modversion['official']       = 1;
$modversion['author']         = 'Xarigami Example Module Development Team';
$modversion['contact']        = 'http://xarigami.com/';
$modversion['admin']          = 1;
$modversion['user']           = 1;
$modversion['xar_version']    = '1.0.0';
$modversion['class']          = 'Complete';     /* Complete|Utility|Miscellaneous|Authentication are available options for non-core */
$modversion['category']       = 'Tools';        /* Global|Content|User & Group|Security|Appearance|Utilities|Tools available for non-core */
$modversion['category']       = 'Content';      /* Global|Content|User & Group|Miscellaneous available for non-core */
//$modversion['extensions']     = array('curl');/* Array of non default PHP extensions required for module functioning */
$modversion['dependency']     = array();
/* Add dependency info var if applicable or remove.
 * The keys available for dependenyinfoare:
 *   version_eq: dependency version must equal the given value
 *   version_ge: dependency version must greater or equal to the given value
 *   version_le: dependency version must lesser or equal to the given value
 *
 */
$modversion['dependencyinfo']   = array(
                                    0 => array(
                                            'name' => 'core',
                                            'version_ge' => '1.4.0'
                                         ),
                                    //182 => array(
                                   //         'name' =>'dynamicdata',
                                   //         'version_ge' => '1.2.0'
                                   //     )
                                );

if (false) { //Load and translate once
    xarML('Example');
    xarML('Documented example and template for new modules');
}
?>