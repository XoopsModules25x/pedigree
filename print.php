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

//require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
require_once __DIR__ . '/include/config.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';
$dogid = Request::getInt('dogid', 0, 'GET');

//create data and variables
$queryString = '
SELECT d.id as d_id,
d.naam as d_naam,
d.roft as d_roft,
d.foto as d_foto,
f.id as f_id,
f.naam as f_naam,
f.foto as f_foto,
m.id as m_id,
m.naam as m_naam,
m.foto as m_foto,
ff.id as ff_id,
ff.naam as ff_naam,
ff.foto as ff_foto,
mf.id as mf_id,
mf.naam as mf_naam,
mf.foto as mf_foto,
fm.id as fm_id,
fm.naam as fm_naam,
fm.foto as fm_foto,
mm.id as mm_id,
mm.naam as mm_naam,
mm.foto as mm_foto,
fff.id as fff_id,
fff.naam as fff_naam,
ffm.id as ffm_id,
ffm.naam as ffm_naam,
fmf.id as fmf_id,
fmf.naam as fmf_naam,
fmm.id as fmm_id,
fmm.naam as fmm_naam,
mmf.id as mmf_id,
mmf.naam as mmf_naam,
mff.id as mff_id,
mff.naam as mff_naam,
mfm.id as mfm_id,
mfm.naam as mfm_naam,
mmm.id as mmm_id,
mmm.naam as mmm_naam
FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' f ON d.father = f.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON d.mother = m.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ff ON f.father = ff.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fff ON ff.father = fff.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffm ON ff.mother = ffm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mf ON m.father = mf.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mff ON mf.father = mff.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfm ON mf.mother = mfm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fm ON f.mother = fm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmf ON fm.father = fmf.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmm ON fm.mother = fmm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mm ON m.mother = mm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmf ON mm.father = mmf.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " mmm ON mm.mother = mmm.id
where d.id=$dogid";

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
