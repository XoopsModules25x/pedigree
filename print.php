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
 * pedigree module for XOOPS
 *
 * @copyright   {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @package     pedigree
 * @author      XOOPS Module Dev Team
 */

use Xmf\Request;

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
require_once __DIR__ . '/include/config.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';
$dogid = Request::getInt('dogid', 0, 'GET');

//create data and variables
$queryString = '
SELECT d.Id as d_id,
d.NAAM as d_naam,
d.roft as d_roft,
d.foto as d_foto,
f.Id as f_id,
f.NAAM as f_naam,
f.foto as f_foto,
m.Id as m_id,
m.NAAM as m_naam,
m.foto as m_foto,
ff.Id as ff_id,
ff.NAAM as ff_naam,
ff.foto as ff_foto,
mf.Id as mf_id,
mf.NAAM as mf_naam,
mf.foto as mf_foto,
fm.Id as fm_id,
fm.NAAM as fm_naam,
fm.foto as fm_foto,
mm.Id as mm_id,
mm.NAAM as mm_naam,
mm.foto as mm_foto,
fff.Id as fff_id,
fff.NAAM as fff_naam,
ffm.Id as ffm_id,
ffm.NAAM as ffm_naam,
fmf.Id as fmf_id,
fmf.NAAM as fmf_naam,
fmm.Id as fmm_id,
fmm.NAAM as fmm_naam,
mmf.Id as mmf_id,
mmf.NAAM as mmf_naam,
mff.Id as mff_id,
mff.NAAM as mff_naam,
mfm.Id as mfm_id,
mfm.NAAM as mfm_naam,
mmm.Id as mmm_id,
mmm.NAAM as mmm_naam
FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' f ON d.father = f.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON d.mother = m.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ff ON f.father = ff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fff ON ff.father = fff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffm ON ff.mother = ffm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mf ON m.father = mf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mff ON mf.father = mff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfm ON mf.mother = mfm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fm ON f.mother = fm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmf ON fm.father = fmf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmm ON fm.mother = fmm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mm ON m.mother = mm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmf ON mm.father = mmf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " mmm ON mm.mother = mmm.Id
where d.Id=$dogid";

$result = $GLOBALS['xoopsDB']->query($queryString);
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html><head>
    <meta http-equiv="Content-Type" content="text/html">
    <meta name="AUTHOR" content="' . $GLOBALS['xoopsConfig']['sitename'] . '">
    <meta name="COPYRIGHT" content="Copyright (c) 2016 by ' . $GLOBALS['xoopsConfig']['sitename'] . '">
    <meta name="GENERATOR" content="XOOPS Pedigree database">
    </head>
    <body bgcolor="#ffffff" text="#000000" onload="window.print()">
    <table border="0" width="640">
        <tr>
            <td>';
    $male   = '<img src="assets/images/male.gif">';
    $female = '<img src="assets/images/female.gif">';
    if (0 == $row['d_roft']) {
        $gender = $male;
    } else {
        $gender = $female;
    }

    echo "    <table width='100%' cellspacing='2' border='2'>\n"
         . "      <!-- header (dog name) -->\n"
         . "      <tr>\n"
         . "          <th colspan='4' style='text-align:center;'>\n"
         . '              '
         . stripslashes($row['d_naam'])
         . "\n"
         . "          </th>\n"
         . "      </tr>\n"
         . "      <tr>\n"
         . "          <!-- selected dog -->\n"
         . "          <td width='25%' rowspan='8'>\n"
         . "              {$gender}"
         . stripslashes($row['d_naam'])
         . "\n";
    if ('' != $row['d_foto']) {
        echo "              <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['d_foto'] . "_150.jpeg' width='150px;'>";
    }
    echo "          </td>\n" . "             <!-- father -->\n" . "             <td width='25%' rowspan='4'>\n" . "                 {$male}" . stripslashes($row['f_naam']) . "\n";
    if ('' != $row['f_foto']) {
        echo "                 <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['f_foto'] . "_150.jpeg' width='150px;'>\n";
    }
    echo "          </td>\n" . "             <!-- father father -->\n" . "             <td width='25%' rowspan='2'>\n" . "                 {$male}" . stripslashes($row['ff_naam']) . "\n";
    if ('' != $row['ff_foto']) {
        echo "                 <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['ff_foto'] . "_150.jpeg' width='150px;'>\n";
    }
    echo "          </td>\n"
         . "             <!-- father father father -->\n"
         . "             <td width='25%'>\n"
         . "                 {$male}"
         . stripslashes($row['fff_naam'])
         . "\n"
         . "             </td>\n"
         . "         </tr>\n"
         . "         <tr>\n"
         . "             <!-- father father mother -->\n"
         . "             <td width='25%'>\n"
         . "                 {$female}"
         . stripslashes($row['ffm_naam'])
         . "\n"
         . "             </td>\n"
         . "         </tr>\n"
         . "         <tr>\n"
         . "         <!-- father mother -->\n"
         . "             <td width='25%' rowspan='2'>\n"
         . "                 {$female}"
         . stripslashes($row['fm_naam'])
         . "\n";
    if ('' != $row['fm_foto']) {
        echo "                 <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['fm_foto'] . "_150.jpeg' width='150px;'>\n";
    }
    echo "             </td>\n"
         . "                <!-- father mother father -->\n"
         . "                <td width='25%'>\n"
         . "                    {$male}"
         . stripslashes($row['fmf_naam'])
         . "\n"
         . "                </td>\n"
         . "            </tr>\n"
         . "            <tr>\n"
         . "                <!-- father mother mother -->\n"
         . "                <td width='25%'>\n"
         . "                    {$female}"
         . stripslashes($row['fmm_naam'])
         . "\n"
         . "                </td>\n"
         . "            </tr>\n"
         . "            <tr>\n"
         . "                <!-- mother -->\n"
         . "                <td width='25%' rowspan='4'>\n"
         . "                    {$female}"
         . stripslashes($row['m_naam'])
         . "\n";
    if ('' != $row['m_foto']) {
        echo "                 <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['m_foto'] . "_150.jpeg' width='150px;'>\n";
    }
    echo "             </td>\n" . "                <!- mother father -->\n" . "                <td width='25%' rowspan='2'>\n" . "                    {$male}" . stripslashes($row['mf_naam']) . "\n";
    if ('' != $row['mf_foto']) {
        echo "                 <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['mf_foto'] . "_150.jpeg' width='150px;'>\n";
    }
    echo "                    </td>\n"
         . "                    <!-- mother father father -->\n"
         . "                    <td width='25%'>\n"
         . "                      {$male}"
         . stripslashes($row['mff_naam'])
         . "\n"
         . "                    </td>\n"
         . "                </tr>\n"
         . "                <tr>\n"
         . "                    <!-- mother father mother -->\n"
         . "                    <td width='25%'>\n"
         . "                        {$female}"
         . stripslashes($row['mfm_naam'])
         . "\n"
         . "                    </td>\n"
         . "                </tr>\n"
         . "                <tr>\n"
         . "                    <!-- mother mother -->\n"
         . "                    <td width='25%' rowspan='2'>\n"
         . "                        {$female}"
         . stripslashes($row['mm_naam'])
         . "\n"
         . "                    </td>\n"
         . "                    <!-- mother mother father -->\n"
         . "                    <td width='25%'>\n"
         . "                        {$male}"
         . stripslashes($row['mmf_naam'])
         . "\n"
         . "                    </td>\n"
         . "                </tr>\n"
         . "                <tr>\n"
         . "                    <!-- mother mother mother -->\n"
         . "                    <td width='25%'>\n"
         . "                        {$female}"
         . stripslashes($row['mmm_naam'])
         . "\n"
         . "                    </td>\n"
         . "                </tr>\n"
         . "                <!-- footer (dog url) -->\n"
         . "                <tr>\n"
         . "                    <th colspan='4' style='text-align:center;'>\n"
         . "                        <a href='"
         . $GLOBALS['xoops']->url("www/modules/pedigree/pedigree.php?pedid={$dogid}")
         . "'>"
         . $GLOBALS['xoops']->url("www/modules/pedigree/pedigree.php?pedid={$dogid}")
         . "</a>\n"
         . "                    </th>\n"
         . "                </tr>\n"
         . "            </table>\n"
         . "            </td>\n"
         . "        </tr>\n"
         . "    </table>\n"
         . "    </body>\n"
         . "    </html>\n";
}
