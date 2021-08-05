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
 * Pedigree module for XOOPS
 *
 * @copyright       {@link https://xoops.org/  XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */

use Xmf\Module\Admin;

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

return (object)[
    'name'           => $moduleDirNameUpper . ' Module Configurator',
    'paths'          => [
        'dirname'    => $moduleDirName,
        'admin'      => XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/admin',
        'modPath'    => XOOPS_ROOT_PATH . '/modules/' . $moduleDirName,
        'modUrl'     => XOOPS_URL . '/modules/' . $moduleDirName,
        'uploadPath' => XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        'uploadUrl'  => XOOPS_UPLOAD_URL . '/' . $moduleDirName,
    ],
    'uploadFolders'  => [
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images/thumbnails',
        //XOOPS_UPLOAD_PATH . '/flags'
    ],
    'copyBlankFiles' => [
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images/thumbnails',
        //XOOPS_UPLOAD_PATH . '/flags'
    ],

    'copyTestFolders' => [
        //[
        //    constant($moduleDirNameUpper . '_PATH') . '/testdata/images',
        //    XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images',
        //]
    ],

    'templateFolders' => [
        '/templates/',
        '/templates/blocks/',
        '/templates/admin/',
    ],
    'oldFiles'        => [
        '/class/request.php',
        '/class/registry.php',
        '/class/utilities.php',
        '/class/util.php',
        //            '/include/constants.php',
        //            '/include/functions.php',
        '/ajaxrating.txt',
    ],
    'oldFolders'      => [
        '/images',
        '/css',
        '/js',
        '/tcpdf',
        '/images',
    ],
    'renameTables'    => [
        'eigenaar'            => $moduleDirName . '_owner',
        'stamboom'            => $moduleDirName . '_registry',
        'stamboom_config'     => $moduleDirName . '_fields',
        'stamboom_temp'       => $moduleDirName . '_temp',
        'stamboom_trash'      => $moduleDirName . '_trash',
        'stamboom_lookup1'    => $moduleDirName . '_lookup1',
        'mod_pedigree_owner'  => $moduleDirName . '_owner',
        'mod_pedigree_tree'   => $moduleDirName . '_registry',
        'mod_pedigree_fields' => $moduleDirName . '_fields',
        'mod_pedigree_temp'   => $moduleDirName . '_temp',
        'mod_pedigree_trash'  => $moduleDirName . '_trash',
        'pedigree_tree'       => $moduleDirName . '_registry',
        //            'XX_archive'          => 'ZZZZ_archive',
        //            'XX_archive'          => 'ZZZZ_archive',
        //            'XX_archive'          => 'ZZZZ_archive',
        //            'XX_archive'          => 'ZZZZ_archive',
        //            'XX_archive'          => 'ZZZZ_archive',
        //            'XX_archive'          => 'ZZZZ_archive',
    ],
    'renameColumns'   => [
        [$moduleDirName . '_owner', 'ID', 'int(11) NOT NULL auto_increment', 'id'],
        [$moduleDirName . '_owner', 'woonplaats', "VARCHAR(50) NOT NULL DEFAULT ''", 'city'],

        [$moduleDirName . '_registry', 'ID', 'MEDIUMINT(7) UNSIGNED NOT NULL AUTO_INCREMENT', 'id'],
        [$moduleDirName . '_registry', 'NAAM', 'TEXT NOT NULL', 'pname'],
        [$moduleDirName . '_registry', "id_eigenaar', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'id_owner'],
        [$moduleDirName . '_registry', "id_fokker', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'id_breeder'],
        [$moduleDirName . '_registry', "moeder', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'mother'],
        [$moduleDirName . '_registry', "vader', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'father'],

        [$moduleDirName . '_fields', 'ID', 'TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT', 'id'],
        [$moduleDirName . '_fields', 'isActive', 'TINYINT(1) UNSIGNED NOT NULL AUTO_INCREMENT', 'isactive'],
        [$moduleDirName . '_fields', 'FieldName', "VARCHAR(50) NOT NULL DEFAULT ''", 'fieldname'],
        [$moduleDirName . '_fields', 'FieldType', "ENUM ('DateSelect', 'TextBox', 'SelectBox', 'RadioButton', 'TextArea', 'UrlField', 'Picture') NOT NULL DEFAULT 'DateSelect'", 'fieldtype'],
        [$moduleDirName . '_fields', 'LookupTable', "TINYINT(1) NOT NULL DEFAULT '0'", 'lookuptable'],
        [$moduleDirName . '_fields', 'DefaultValue', "VARCHAR(50) NOT NULL DEFAULT ''", 'defaultvalue'],
        [$moduleDirName . '_fields', 'FieldExplenation', 'TINYTEXT NOT NULL', 'fieldexplanation'],
        [$moduleDirName . '_fields', 'HasSearch', "TINYINT(1) NOT NULL DEFAULT '0'", 'hassearch'],
        [$moduleDirName . '_fields', 'SearchName', "VARCHAR(50) NOT NULL DEFAULT ''", 'searchname'],
        [$moduleDirName . '_fields', 'SearchExplenation', 'TINYTEXT NOT NULL', 'searchexplanation'],
        [$moduleDirName . '_fields', 'ViewInPedigree', "TINYINT(1) NOT NULL DEFAULT '0'", 'viewinpedigree'],
        [$moduleDirName . '_fields', 'ViewInAdvanced', "TINYINT(1) NOT NULL DEFAULT '0'", 'viewinadvanced'],
        [$moduleDirName . '_fields', 'ViewInPie', "TINYINT(1) NOT NULL DEFAULT '0'", 'viewinpie'],
        [$moduleDirName . '_fields', 'ViewInList', "TINYINT(1) NOT NULL DEFAULT '0'", 'viewinlist'],

        [$moduleDirName . '_temp', 'ID', 'MEDIUMINT(7) UNSIGNED NOT NULL AUTO_INCREMENT', 'id'],
        [$moduleDirName . '_temp', 'NAAM', 'TEXT NOT NULL', 'pname'],
        [$moduleDirName . '_temp', "id_eigenaar', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'id_owner'],
        [$moduleDirName . '_temp', "id_fokker', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'id_breeder'],
        [$moduleDirName . '_temp', "moeder', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'mother'],
        [$moduleDirName . '_temp', "vader', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'father'],

        [$moduleDirName . '_trash', 'ID', 'MEDIUMINT(7) UNSIGNED NOT NULL AUTO_INCREMENT', 'id'],
        [$moduleDirName . '_trash', 'NAAM', 'TEXT NOT NULL', 'pname'],
        [$moduleDirName . '_trash', "id_eigenaar', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'id_owner'],
        [$moduleDirName . '_trash', "id_fokker', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'id_breeder'],
        [$moduleDirName . '_trash', "moeder', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'mother'],
        [$moduleDirName . '_trash', "vader', 'SMALLINT(5) NOT NULL DEFAULT '0'", 'father'],
    ],
    'moduleStats'     => [
        //            'totalcategories' => $helper->getHandler('Category')->getCategoriesCount(-1),
        //            'totalitems'      => $helper->getHandler('Item')->getItemsCount(),
        //            'totalsubmitted'  => $helper->getHandler('Item')->getItemsCount(-1, [Constants::PUBLISHER_STATUS_SUBMITTED]),
    ],
    'modCopyright'    => "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . Admin::iconUrl('xoopsmicrobutton.gif') . "' alt='XOOPS Project'></a>",
];
