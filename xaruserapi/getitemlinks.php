<?php
/**
 * Utility function to pass individual item links to whoever
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
 * Utility function to pass individual item links to whoever
 *
 * @author the Example module development team
 * @param  $args ['itemtype'] item type (optional)
 * @param  $args ['itemids'] array of item ids to get
 * @return array containing the itemlink(s) for the item(s).
 */
function example_userapi_getitemlinks($args)
{
    $itemlinks = array();
    if (!xarSecurityCheck('ViewExample', 0)) {
        return $itemlinks;
    }

    foreach ($args['itemids'] as $itemid) {
        $item = xarModAPIFunc('example', 'user', 'get',
            array('exid' => $itemid));
        if (!isset($item)) return;
        $itemlinks[$itemid] = array('url' => xarModURL('example', 'user', 'display',
                array('exid' => $itemid)),
            'title' => xarML('Display Example Item'),
            'label' => xarVarPrepForDisplay($item['name']));
    }
    return $itemlinks;
}
?>