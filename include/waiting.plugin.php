<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * animal module for xoops
 *
 * @copyright       The TXMod XOOPS Project http://sourceforge.net/projects/thmod/
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GPL 2.0 or later
 * @package         animal
 * @since           2.5.x
 * @author          XOOPS Development Team ( name@site.com ) - ( https://xoops.org )
 */
function b_waiting_animal()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = [];

    // waiting pedigree_trash
    $block = [];

    $result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' WHERE pedigree_trash_waiting=1');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/pedigree_trash.php?op=listWaiting';
        [$block['pendingnum']] = $GLOBALS['xoopsDB']->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // waiting mod_owner
    $block = [];

    $result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE owner_waiting=1');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/owner.php?op=listWaiting';
        [$block['pendingnum']] = $GLOBALS['xoopsDB']->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // waiting pedigree_temp
    $block = [];

    $result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' WHERE pedigree_temp_waiting=1');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/pedigree_temp.php?op=listWaiting';
        [$block['pendingnum']] = $GLOBALS['xoopsDB']->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // waiting pedigree
    $block = [];

    $result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE pedigree_waiting=1');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/pedigree.php?op=listWaiting';
        [$block['pendingnum']] = $GLOBALS['xoopsDB']->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // waiting pedigree_fields
    $block = [];

    $result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . ' WHERE pedigree_fields_waiting=1');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/pedigree_fields.php?op=listWaiting';
        [$block['pendingnum']] = $GLOBALS['xoopsDB']->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    return $ret;
}
