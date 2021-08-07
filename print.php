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

//require_once  \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
require_once __DIR__ . '/config/config.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';
$dogid = Request::getInt('dogid', 0, 'GET');

//create data and variables
$sql = '
SELECT d.Id as d_id,
d.pname as d_pname,
d.roft as d_roft,
d.foto as d_foto,
f.Id as f_id,
f.pname as f_pname,
f.foto as f_foto,
m.Id as m_id,
m.pname as m_pname,
m.foto as m_foto,
ff.Id as ff_id,
ff.pname as ff_pname,
ff.foto as ff_foto,
mf.Id as mf_id,
mf.pname as mf_pname,
mf.foto as mf_foto,
fm.Id as fm_id,
fm.pname as fm_pname,
fm.foto as fm_foto,
mm.Id as mm_id,
mm.pname as mm_pname,
mm.foto as mm_foto,
fff.Id as fff_id,
fff.pname as fff_pname,
ffm.Id as ffm_id,
ffm.pname as ffm_pname,
fmf.Id as fmf_id,
fmf.pname as fmf_pname,
fmm.Id as fmm_id,
fmm.pname as fmm_pname,
mmf.Id as mmf_id,
mmf.pname as mmf_pname,
mff.Id as mff_id,
mff.pname as mff_pname,
mfm.Id as mfm_id,
mfm.pname as mfm_pname,
mmm.Id as mmm_id,
mmm.pname as mmm_pname
FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' d
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' f ON d.father = f.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' m ON d.mother = m.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' ff ON f.father = ff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fff ON ff.father = fff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' ffm ON ff.mother = ffm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mf ON m.father = mf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mff ON mf.father = mff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mfm ON mf.mother = mfm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fm ON f.mother = fm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fmf ON fm.father = fmf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fmm ON fm.mother = fmm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mm ON m.mother = mm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mmf ON mm.father = mmf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " mmm ON mm.mother = mmm.Id
where d.Id=$dogid";

$result = $GLOBALS['xoopsDB']->query($sql);
$male   = "<img src=\"" . PEDIGREE_IMAGE_URL . "/male.gif\">";
$female = "<img src=\"" . PEDIGREE_IMAGE_URL . "/female.gif\">";
$gender = '';
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $gender = (Constants::MALE == $row['d_roft']) ? $male : $female;
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html><head>
    <meta http-equiv="Content-Type" content="text/html">
    <meta name="AUTHOR" content="' . $GLOBALS['xoopsConfig']['sitename'] . '">
    <meta name="COPYRIGHT" content="Copyright (c) 2019 by ' . $GLOBALS['xoopsConfig']['sitename'] . '">
    <meta name="GENERATOR" content="XOOPS Pedigree database">
    </head>
    <body bgcolor="#ffffff" text="#000000" onload="window.print()">
    <table border="0" width="640">
        <tr>
            <td>';

    echo "    <table width='100%' cellspacing='2' border='2'>\n"
         . "      <!-- header (dog name) -->\n"
         . "      <tr>\n"
         . "          <th colspan='4' style='text-align:center;'>\n"
         . '              '
         . stripslashes($row['d_pname'])
         . "\n"
         . "          </th>\n"
         . "      </tr>\n"
         . "      <tr>\n"
         . "          <!-- selected dog -->\n"
         . "          <td width='25%' rowspan='8'>\n"
         . "              {$gender}"
         . stripslashes($row['d_pname'])
         . "\n";
    if ('' != $row['d_foto']) {
        echo "              <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['d_foto'] . "_150.jpeg' width='150px;'>";
    }
    echo "          </td>\n" . "             <!-- father -->\n" . "             <td width='25%' rowspan='4'>\n" . "                 {$male}" . stripslashes($row['f_pname']) . "\n";
    if ('' != $row['f_foto']) {
        echo "                 <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['f_foto'] . "_150.jpeg' width='150px;'>\n";
    }
    echo "          </td>\n" . "             <!-- father father -->\n" . "             <td width='25%' rowspan='2'>\n" . "                 {$male}" . stripslashes($row['ff_pname']) . "\n";
    if ('' != $row['ff_foto']) {
        echo "                 <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['ff_foto'] . "_150.jpeg' width='150px;'>\n";
    }
    echo "          </td>\n"
         . "             <!-- father father father -->\n"
         . "             <td width='25%'>\n"
         . "                 {$male}"
         . stripslashes($row['fff_pname'])
         . "\n"
         . "             </td>\n"
         . "         </tr>\n"
         . "         <tr>\n"
         . "             <!-- father father mother -->\n"
         . "             <td width='25%'>\n"
         . "                 {$female}"
         . stripslashes($row['ffm_pname'])
         . "\n"
         . "             </td>\n"
         . "         </tr>\n"
         . "         <tr>\n"
         . "         <!-- father mother -->\n"
         . "             <td width='25%' rowspan='2'>\n"
         . "                 {$female}"
         . stripslashes($row['fm_pname'])
         . "\n";
    if ('' != $row['fm_foto']) {
        echo "                 <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['fm_foto'] . "_150.jpeg' width='150px;'>\n";
    }
    echo "             </td>\n"
         . "                <!-- father mother father -->\n"
         . "                <td width='25%'>\n"
         . "                    {$male}"
         . stripslashes($row['fmf_pname'])
         . "\n"
         . "                </td>\n"
         . "            </tr>\n"
         . "            <tr>\n"
         . "                <!-- father mother mother -->\n"
         . "                <td width='25%'>\n"
         . "                    {$female}"
         . stripslashes($row['fmm_pname'])
         . "\n"
         . "                </td>\n"
         . "            </tr>\n"
         . "            <tr>\n"
         . "                <!-- mother -->\n"
         . "                <td width='25%' rowspan='4'>\n"
         . "                    {$female}"
         . stripslashes($row['m_pname'])
         . "\n";
    if ('' != $row['m_foto']) {
        echo "                 <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['m_foto'] . "_150.jpeg' width='150px;'>\n";
    }
    echo "             </td>\n" . "                <!- mother father -->\n" . "                <td width='25%' rowspan='2'>\n" . "                    {$male}" . stripslashes($row['mf_pname']) . "\n";
    if ('' != $row['mf_foto']) {
        echo "                 <br><img src='" . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['mf_foto'] . "_150.jpeg' width='150px;'>\n";
    }
    echo "                    </td>\n"
         . "                    <!-- mother father father -->\n"
         . "                    <td width='25%'>\n"
         . "                      {$male}"
         . stripslashes($row['mff_pname'])
         . "\n"
         . "                    </td>\n"
         . "                </tr>\n"
         . "                <tr>\n"
         . "                    <!-- mother father mother -->\n"
         . "                    <td width='25%'>\n"
         . "                        {$female}"
         . stripslashes($row['mfm_pname'])
         . "\n"
         . "                    </td>\n"
         . "                </tr>\n"
         . "                <tr>\n"
         . "                    <!-- mother mother -->\n"
         . "                    <td width='25%' rowspan='2'>\n"
         . "                        {$female}"
         . stripslashes($row['mm_pname'])
         . "\n"
         . "                    </td>\n"
         . "                    <!-- mother mother father -->\n"
         . "                    <td width='25%'>\n"
         . "                        {$male}"
         . stripslashes($row['mmf_pname'])
         . "\n"
         . "                    </td>\n"
         . "                </tr>\n"
         . "                <tr>\n"
         . "                    <!-- mother mother mother -->\n"
         . "                    <td width='25%'>\n"
         . "                        {$female}"
         . stripslashes($row['mmm_pname'])
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
