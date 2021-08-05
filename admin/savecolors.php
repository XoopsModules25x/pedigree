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
 * @since     1.32
 */

use Xmf\Request;
use XoopsModules\Pedigree;
use XoopsModules\Pedigree\Constants;

/** @var XoopsModules\Pedigree\Helper $helper */
require_once dirname(__DIR__, 3) . '/include/cp_header.php';
$helper->loadLanguage('modinfo');

require_once $helper->path('admin/menu.php');

// check referrer
if (!$GLOBALS['xoopsSecurity']->check()) {
    $helper->redirect('admin/index.php', Constants::REDIRECT_DELAY_MEDIUM, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
}

xoops_cp_header();

/** internal - following use of Request::getCmd() to get colors counts on the fact that a beginning '#' will be stripped from incoming var.
 * Then a preg_replace is used to restrict chars to HEX
 */

$settings = []; // initialize array
$mainBgColor = Request::getCmd('mainbgcolor', 0, 'POST');
$settings['mainBgColor'] = (string) preg_replace('/[^A-F0-9]/i', '', $mainBgColor); // restrict to HEX chars

$sBgColor = Request::getCmd('sbgcolor', 0, 'POST');
$settings['sBgColor'] = (string) preg_replace('/[^A-F0-9]/i', '', $sBgColor);

$sTxtColor = Request::getCmd('stxtcolor', 0, 'POST');
$settings['sTxtColor'] = (string) preg_replace('/[^A-F0-9]/i', '', $sTxtColor);

$settings['sFont'] = Request::getWord('sfont', '', 'POST');
$settings['sFontSize'] = Request::getCmd('sfontsize', '', 'POST');
$settings['sFontStyle'] = Request::getWord('sfontstyle', '', 'POST');

$mBgColor = Request::getCmd('mbgcolor', 0, 'POST');
$settings['mBgColor'] = (string) preg_replace('/[^A-F0-9]/i', '', $mBgColor);

$mTxtColor = Request::getCmd('mtxtcolor', 0, 'POST');
$settings['mTxtColor'] = (string) preg_replace('/[^A-F0-9]/i', '', $mTxtColor);

$settings['mFont'] = Request::getWord('mfont', '', 'POST');
$settings['mFontSize'] = Request::getCmd('mfontsize', '', 'POST');
$settings['mFontStyle'] = Request::getWord('mfontstyle', '', 'POST');

$fBgColor = Request::getCmd('fbgcolor', 0, 'POST');
$settings['fBgColor'] = (string) preg_replace('/[^A-F0-9]/i', '', $fBgColor);

$fTxtColor = Request::getCmd('ftxtcolor', 0, 'POST');
$settings['fTxtColor'] = (string) preg_replace('/[^A-F0-9]/i', '', $fTxtColor);

$settings['fFont'] = Request::getWord('ffont', '', 'POST');
$settings['fFontSize'] = Request::getCmd('ffontsize', '', 'POST');
$settings['fFontStyle'] = Request::getWord('ffontstyle', '', 'POST');

$settings['bStyle'] = Request::getWord('bstyle', '', 'POST');
$settings['bWidth'] = Request::getString('bwidth', 0, 'POST');
$settings['bWidth'] = mb_substr($bWidth, 0, 1);

$bColor = Request::getCmd('bcolor', 0, 'POST');
$settings['bColor'] = (string) preg_replace('/[^A-F0-9]/i', '', $bColor);

$colourString = implode(';', $settings);
/*
$colourString = mb_substr($_POST['mainbgcolor'], 1)
                . ';'
                . mb_substr($_POST['sbgcolor'], 1)
                . ';'
                . mb_substr($_POST['stxtcolor'], 1)
                . ';'
                . $_POST['sfont']
                . ';'
                . $_POST['sfontsize']
                . ';'
                . $_POST['sfontstyle']
                . ';'
                . mb_substr($_POST['mbgcolor'], 1)
                . ';'
                . mb_substr($_POST['mtxtcolor'], 1)
                . ';'
                . $_POST['mfont']
                . ';'
                . $_POST['mfontsize']
                . ';'
                . $_POST['mfontstyle']
                . ';'
                . mb_substr($_POST['fbgcolor'], 1)
                . ';'
                . mb_substr($_POST['ftxtcolor'], 1)
                . ';'
                . $_POST['ffont']
                . ';'
                . $_POST['ffontsize']
                . ';'
                . $_POST['ffontstyle']
                . ';'
                . $_POST['bstyle']
                . ';'
                . mb_substr($_POST['bwidth'], 0, 1)
                . ';'
                . mb_substr($_POST['bcolor'], 1);
*/

//$sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value='" . $colourString . "' WHERE conf_name = 'pedigreeColours'";
//$GLOBALS['xoopsDB']->queryf($sql);
//@todo refactor to use class object CRUD access
$sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value='" . $GLOBALS['xoopsDB']->escape($colourString) . "' WHERE conf_name = 'pedigreeColours'";
$GLOBALS['xoopsDB']->queryF($sql);

$helper->redirect('admin/colors.php', 3, _MI_PEDIGREE_SAVE_SETTINGS);

xoops_cp_footer();
