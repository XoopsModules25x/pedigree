<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module: Pedigree
 *
 * @package   XoopsModules\Pedigree
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 */

use Xmf\Request;

//require_once  \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
/*
// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, "param");
extract($_POST, EXTR_PREFIX_ALL, "param");
*/
$GLOBALS['xoopsOption']['template_main'] = 'pedigree_members.tpl';

require $GLOBALS['xoops']->path('/header.php');

$sql = 'SELECT COUNT(d.user) AS X, d.user AS d_user, u.uname AS u_uname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' d LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('users') . ' u ON d.user = u.uid GROUP  BY user    ORDER BY X DESC LIMIT 50';
$result      = $GLOBALS['xoopsDB']->query($sql);
$numpos      = 1;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $content = '';
    $star    = $row['X'];
    if ($star > 10000) {
        $sterretje = floor($star / 10000);
        for ($c = 0; $c < $sterretje; ++$c) {
            $content .= "<img src=\"" . PEDIGREE_IMAGE_URL . "/star.png\" border=\"0\">";
            $star    -= 10000;
        }
    }
    if ($star > 1000) {
        $sterretje = floor($star / 1000);
        for ($c = 0; $c < $sterretje; ++$c) {
            $content .= "<img src=\"" . PEDIGREE_IMAGE_URL . "/star3.gif\" border=\"0\">";
            $star    -= 1000;
        }
    }
    if ($star > 100) {
        $sterretje = floor($star / 100);
        for ($c = 0; $c < $sterretje; ++$c) {
            $content .= "<img src=\"" . PEDIGREE_IMAGE_URL . "/star2.gif\" border=\"0\">";
        }
    }

    $members[] = [
        'position' => $numpos,
        'user'     => '<a href="../../userinfo.php?uid=' . $row['d_user'] . '">' . $row['u_uname'] . '</a>',
        'stars'    => $content,
        'nument'   => '<a href="result.php?f=user&l=0&w=' . $row['d_user'] . '&o=pname">' . $row['X'] . '</a>',
    ];
    ++$numpos;
}
$GLOBALS['xoopsTpl']->assign('members', $members);
$GLOBALS['xoopsTpl']->assign('title', _MA_PEDIGREE_M50_TIT);
$GLOBALS['xoopsTpl']->assign('position', _MA_PEDIGREE_M50_POS);
$GLOBALS['xoopsTpl']->assign('numdogs', _MA_PEDIGREE_M50_NUMD);
//comments and footer
require $GLOBALS['xoops']->path('footer.php');
