<?php

/**
 * Verify that a mysql table exists
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 *
 * @param $tablename
 *
 * @return bool
 */
{
    global $xoopsDB;
    $result = $GLOBALS['xoopsDB']->queryF("SHOW TABLES LIKE '$tablename'");

    return ($GLOBALS['xoopsDB']->getRowsNum($result) > 0);
}

/**
 * @return bool
 */
function xoops_module_update_animal()
{
    global $xoopsDB;

    if (tableExists($GLOBALS['xoopsDB']->prefix('owner'))) {
        $sql    = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('owner') . ' RENAME ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner'));
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) {
            echo '<br>' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (tableExists($GLOBALS['xoopsDB']->prefix('stamboom'))) {
        $sql    = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('stamboom') . ' RENAME ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry'));
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) {
            echo '<br>' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (tableExists($GLOBALS['xoopsDB']->prefix('pedigree_fields'))) {
        $sql    = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . ' RENAME ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields'));
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) {
            echo '<br>' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (tableExists($GLOBALS['xoopsDB']->prefix('pedigree_temp'))) {
        $sql    = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' RENAME ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp'));
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) {
            echo '<br>' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (tableExists($GLOBALS['xoopsDB']->prefix('pedigree_trash'))) {
        $sql    = sprintf('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' RENAME ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash'));
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) {
            echo '<br>' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    return true;
}
