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
function tableExists($tablename)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * @return bool
 */
function xoops_module_update_animal()
{
    global $xoopsDB;


    if (tableExists($xoopsDB->prefix('owner'))) {
        $sql    = sprintf(
            'ALTER TABLE ' . $xoopsDB->prefix('mod_pedigree_owner') . ' RENAME ' . $xoopsDB->prefix('pedigree_owner')
        );
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (tableExists($xoopsDB->prefix('stamboom'))) {
        $sql    = sprintf(
            'ALTER TABLE ' . $xoopsDB->prefix('mod_pedigree_tree') . ' RENAME ' . $xoopsDB->prefix('pedigree_tree')
        );
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (tableExists($xoopsDB->prefix('pedigree_config'))) {
        $sql    = sprintf(
            'ALTER TABLE ' . $xoopsDB->prefix('mod_pedigree_fields') . ' RENAME ' . $xoopsDB->prefix('pedigree_fields')
        );
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (tableExists($xoopsDB->prefix('pedigree_temp'))) {
        $sql    = sprintf(
            'ALTER TABLE ' . $xoopsDB->prefix('mod_pedigree_temp') . ' RENAME ' . $xoopsDB->prefix('pedigree_temp')
        );
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (tableExists($xoopsDB->prefix('pedigree_trash'))) {
        $sql    = sprintf(
            'ALTER TABLE ' . $xoopsDB->prefix('mod_pedigree_trash') . ' RENAME ' . $xoopsDB->prefix('pedigree_trash')
        );
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }


//----------------  OLD --------------------------





    if (tableExists($xoopsDB->prefix('owner'))) {
        $sql    = sprintf(
            'ALTER TABLE ' . $xoopsDB->prefix('owner') . ' RENAME ' . $xoopsDB->prefix('pedigree_owner')
        );
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (tableExists($xoopsDB->prefix('stamboom'))) {
        $sql    = sprintf(
            'ALTER TABLE ' . $xoopsDB->prefix('stamboom') . ' RENAME ' . $xoopsDB->prefix('pedigree_tree')
        );
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (tableExists($xoopsDB->prefix('pedigree_config'))) {
        $sql    = sprintf(
            'ALTER TABLE ' . $xoopsDB->prefix('pedigree_config') . ' RENAME ' . $xoopsDB->prefix('pedigree_fields')
        );
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_PEDIGREE_UPGRADEFAILED . ' ' . _AM_PEDIGREE_UPGRADEFAILED2;
            ++$errors;
        }
    }



    return true;

}
