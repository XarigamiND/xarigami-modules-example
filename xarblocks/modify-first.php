<?php
/**
 * Modify block settings
 *
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
  *
 * @subpackage Xarigami Example Module
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/projects/
 */
/**
 * Example Block - Modify block settings
 *
 * @author Example Module development team
 * @param array $blockinfo The array with information for this block
 * @return array with (int numitems, id blockid)
 */
function example_firstblock_modify($blockinfo)
{
    /* Get current content */
    if (!is_array($blockinfo['content'])) {
        $vars = unserialize($blockinfo['content']);
    } else {
        $vars = $blockinfo['content'];
    }

    /* Defaults */
    if (empty($vars['numitems'])) {
        $vars['numitems'] = 5;
    }

    /* Send content to template */
    return array(
        'numitems' => $vars['numitems'],
        'blockid' => $blockinfo['bid']
    );
}

/**
 * Update block settings
 *
 * Update the block settings for the 'firstblock'
 *
 * @param array $blockinfo
 * @param int numitems
 * @return array $blockinfo
 */
function example_firstblock_update($blockinfo)
{
    $vars = array();
    if (!xarVarFetch('numitems', 'int:0', $vars['numitems'], 5, XARVAR_DONT_SET)) {return;}
    $blockinfo['content'] = $vars;
    return $blockinfo;
}
?>
