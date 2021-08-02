<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @package     XoopsModules\Pedigree
 * @copyright   XOOPS Project https://xoops.org/
 * @license     GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use \Xmf\Database\Tables;

/**
 * Make updates to module tables, files, configs, etc. during module update
 *
 * @param \XoopsModule $module
 * @param string $prev_ver
 *
 * @return bool
 */
function xoops_module_update_pedigree(\XoopsModule $module, string $prev_ver)
{
    /**
     * {@internal Both success and failure messages can be sent back to the calling
     * routine via the $module->setErrors() method. }}
     */

    $tableMap = [
        //from tablename    =>  to tablename
        ['from' => 'owner',           'to' => 'pedigree_owner'],
        ['from' => 'stamboom',        'to' => 'pedigree_tree'],
        ['from' => 'pedigree_config', 'to' => 'pedigree_fields'],
    ];

    $success = true;

    $tables = new Tables();
    foreach ($tableMap as $map) {
        $success = $success && $tables->renameTable($map['from'], $map['to']);
        /** @TODO move hard coded language string to language file */
        $msg = $success ? sprintf('Successfully renamed %s table', $map['to']) : sprintf('Failed to rename table %s to %s', $map['from'], $map['to']);
        $module->setErrors($msg);
    }

    return $success;
}
