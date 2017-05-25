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
 * @copyright    {@link http://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      pedigree
 * @since
 * @author       XOOPS Module Dev Team
 */

//require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once __DIR__ . '/admin_header.php';
if (!class_exists('PedigreeUtilities')) {
    xoops_load('utilities', $moduleDirName);
}
xoops_cp_header();
$indexAdmin = new ModuleAdmin();

foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
    PedigreeUtilities::prepareFolder($uploadFolders[$i]);
    $indexAdmin->addConfigBoxLine($uploadFolders[$i], 'folder');
    //    $indexAdmin->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
}

echo $indexAdmin->addNavigation(basename(__FILE__));
echo $indexAdmin->renderIndex();

include_once __DIR__ . '/admin_footer.php';
