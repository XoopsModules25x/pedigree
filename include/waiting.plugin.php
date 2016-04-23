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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GPL 2.0 or later
 * @package         animal
 * @since           2.5.x
 * @author          XOOPS Development Team ( name@site.com ) - ( http://xoops.org )
 * @version         $Id: const_entete.php 9860 2012-07-13 10:41:41Z txmodxoops $
 */

function b_waiting_animal()
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = array();

    // waiting pedigree_trash
    $block = array();

    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("pedigree_trash") . " WHERE pedigree_trash_waiting=1");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/pedigree_trash.php?op=listWaiting";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // waiting mod_owner
    $block = array();

    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("pedigree_owner") . " WHERE owner_waiting=1");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/owner.php?op=listWaiting";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // waiting pedigree_temp
    $block = array();

    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("pedigree_temp") . " WHERE pedigree_temp_waiting=1");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/pedigree_temp.php?op=listWaiting";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // waiting pedigree
    $block = array();

    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("pedigree_tree") . " WHERE pedigree_waiting=1");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/pedigree.php?op=listWaiting";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // waiting pedigree_config
    $block = array();

    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("pedigree_fields") . " WHERE pedigree_config_waiting=1");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/pedigree_config.php?op=listWaiting";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    return $ret;
}

;
