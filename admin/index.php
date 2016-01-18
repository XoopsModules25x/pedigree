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
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 * @version      $Id $
 */

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once __DIR__ . '/admin_header.php';
include_once dirname(__DIR__) . '/class/pedigreeUtilities.php';

//xoops_cp_header();

$indexAdmin = new ModuleAdmin();

foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
    pedigreeUtilities::prepareFolder($uploadFolders[$i]);
    $indexAdmin->addConfigBoxLine($uploadFolders[$i], 'folder');
//    $indexAdmin->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
}

echo $indexAdmin->addNavigation('index.php');
echo $indexAdmin->renderIndex();

include_once __DIR__ . '/admin_footer.php';
