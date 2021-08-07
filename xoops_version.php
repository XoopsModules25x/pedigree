<?php
/* ------------------------------------------------------------------------
      pedigree
          Copyright 2004, James Cotton
           http://www.dobermannvereniging.nl
      Template
          Copyright 2004 Thomas Hill
          <a href="http://www.worldware.com">worldware.com</a>
   ------------------------------------------------------------------------
   ------------------------------------------------------------------------
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   You may not change or alter any portion of this comment or credits
   of supporting developers from this source code or any supporting
   source code which is considered copyrighted (c) material of the
   original comment or credit authors.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
   ------------------------------------------------------------------------
*/

/**
 * @package         XoopsModules\Pedigree
 * @copyright       Copyright 2004, James Cotton <https://www.dobermannvereniging.nl>
 * @copyright       Copyright 2004, Thomas Hill <https://www.worldware.com>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author          XOOPS Module Dev Team
 */

use XoopsModules\Pedigree\{
    Constants
};

require __DIR__ . '/preloads/autoloader.php';

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

xoops_loadLanguage('common', $moduleDirName);

$modversion = [
    'version'             => 1.32,
    'module_status'       => 'Alpha 2',
    'release_date'        => '2021/08/05',
    'name'                => _MI_PEDIGREE_NAME,
    'description'         => _MI_PEDIGREE_DESC,
    'release'             => '2021/08/05',
    'author'              => 'James Cotton, Zyspec, Mamba, Geekwright',
    'author_mail'         => 'info@xoops . org',
    'author_website_url'  => 'https://xoops.org',
    'author_website_name' => 'XOOPS Project',
    'credits'             => 'http://tech.groups.yahoo.com/group/animalpedigree/, XOOPS Development Team',
    'help'                => 'page=help',
    'license'             => 'GPL 2.0 or later',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html',
    'release_info'        => 'release_info',
    'release_file'        => XOOPS_URL . "/modules/{$moduleDirName}/docs/release_info file",
    'manual'              => 'Installation.txt',
    'manual_file'         => XOOPS_URL . "/modules/{$moduleDirName}/docs/link to manual file",
    'min_php'             => '7.3',
    'min_xoops'           => '2.5.10',
    'min_admin'           => '1.2',
    'min_db'              => ['mysql' => '5.5'],
    'image'               => 'assets/images/logoModule.png',
    'dirname'             => $moduleDirName,
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
    //About
    'demo_site_url'       => 'https://xoops.org',
    'demo_site_name'      => 'XOOPS Demo Site',
    'support_url'         => 'https://xoops.org/modules/newbb',
    'support_name'        => 'Support Forum',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'XOOPS Project',
    // Admin system menu
    'system_menu'         => 1,
    // Admin things
    'hasAdmin'            => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // Menu
    'hasMain'             => 1,
    // Scripts to run upon installation or update
    //'onInstall'           => 'include/install_function.php',
    //'onUpdate'            => 'include/update_function.php',
    'onInstall'           => 'include/oninstall.php',
    'onUpdate'            => 'include/onupdate.php',
    'onUninstall'         => 'include/onuninstall.php',
    // ------------------- Mysql -----------------------------
    'sqlfile'             => ['mysql' => 'sql/mysql.sql'],
    // ------------------- Tables ----------------------------
];

//$modversion['help'] = 'page=pedigree_admin';

// Tables created by sql file (without prefix!)
$modversion['tables'] = [
    'pedigree_registry',
    'pedigree_fields',
    'pedigree_temp',
    'pedigree_trash',
    'pedigree_owner',
];

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';
//admin settings

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_PEDIGREE_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_PEDIGREE_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_PEDIGREE_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_PEDIGREE_SUPPORT, 'link' => 'page=support'],
];

$modversion['config'][] = [
    'name'        => 'proversion',
    'title'       => '_MI_PEDIGREE_PROVERSION',
    'description' => '_MI_PEDIGREE_PROVERSION_DESC', //'is this the pro version ?',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'ownerbreeder',
    'title'       => '_MI_PEDIGREE_OWNERBREEDER',
    'description' => '_MI_PEDIGREE_OWNERBREEDER_DESC', //'should the owner/breeder fields be used ?',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'brothers',
    'title'       => '_MI_PEDIGREE_BROTHERS',
    'description' => '_MI_PEDIGREE_BROTHERS_DESC', //'should the brothers & sisters field be shown ?',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'pups',
    'title'       => '_MI_PEDIGREE_PUPS',
    'description' => '_MI_PEDIGREE_PUPS_DESC', //'should the pups/children field be shown ?',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'perpage',
    'title'       => '_MI_PEDIGREE_MENU_PERP',
    'description' => '_MI_PEDIGREE_MENU_PERP_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 100,
    'options'     => ['10' => 10, '50' => 50, '100' => 100, '250' => 250, '500' => 500, '1000' => 1000],
];

$modversion['config'][] = [
    'name'        => 'animalType',
    'title'       => '_MI_PEDIGREE_ANIMALTYPE',
    'description' => '_MI_PEDIGREE_ANIMALTYPE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'dog',
];

$modversion['config'][] = [
    'name'        => 'animalTypes',
    'title'       => '_MI_PEDIGREE_ANIMALTYPES',
    'description' => '_MI_PEDIGREE_ANIMALTYPES_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'dogs',
];

$modversion['config'][] = [
    'name'        => 'lastimage',
    'title'       => '_MI_PEDIGREE_LASTIMAGE',
    'description' => '_MI_PEDIGREE_LASTIMAGE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

$modversion['config'][] = [
    'name'        => 'children',
    'title'       => '_MI_PEDIGREE_CHILDREN',
    'description' => '_MI_PEDIGREE_CHILDREN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'children',
];

$modversion['config'][] = [
    'name'        => 'welcome',
    'title'       => '_MI_PEDIGREE_WELCOME',
    'description' => '_MI_PEDIGREE_WELCOME_DESC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => ' Welcome to the online pedigree database.

This project that now has [numanimals] [animalType] pedigrees has been made to give a better picture of the [animalType] race.
By connecting these [numanimals] pedigrees together one giant pedigree is created with [animalTypes] from around the world.

Because all the information is only added once to the database it becomes very easy to find what you are looking for.

When the correct [animalType] has been found you are able to view its pedigree. This shows you the selected [animalType], the parents, the grandparents and the great-grandparents. You can click on any of these [animalTypes] to view their pedigree. This way you can "walk" through a [animalType] pedigree and go back many generations.

Because so many pedigrees have been merged into one big one lots of interesting data can be shown. It is possible to calculate the coefficients of Kinship, Relationship and Inbreeding of any [animalType] very accurately. Using such tools has shown this pedigree database to be an extremely valuable resource of information used by breeders and enthusiasts.

To keep a little controle over the [animalTypes] entered into the database only registered members of the website are allowed to enter information into the database. Registration is free and will give you full access to all the elements of this website.',
];

// Text to show for mother
$modversion['config'][] = [
    'name'        => 'mother',
    'title'       => '_MI_PEDIGREE_MOTHER',
    'description' => '_MI_PEDIGREE_MOTHER_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'mother',
];

// Text to show for father
$modversion['config'][] = [
    'name'        => 'father',
    'title'       => '_MI_PEDIGREE_FATHER',
    'description' => '_MI_PEDIGREE_FATHER_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'father',
];

// Text to show for female
$modversion['config'][] = [
    'name'        => 'female',
    'title'       => '_MI_PEDIGREE_FEMALE',
    'description' => '_MI_PEDIGREE_FEMALE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'female',
];

// Text to show for male
$modversion['config'][] = [
    'name'        => 'male',
    'title'       => '_MI_PEDIGREE_MALE',
    'description' => '_MI_PEDIGREE_MALE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'male',
];

// Text to show for litter
$modversion['config'][] = [
    'name'        => 'litter',
    'title'       => '_MI_PEDIGREE_LITTER',
    'description' => '_MI_PEDIGREE_LITTER_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textbox',
    'default'     => 'litter',
];

// Should 'litter' feature be enabled
$modversion['config'][] = [
    'name'        => 'uselitter',
    'title'       => '_MI_PEDIGREE_USELITTER',
    'description' => '_MI_PEDIGREE_USELITTER_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

// Colour scheme used
$modversion['config'][] = [
    'name'        => 'colourscheme',
    'title'       => '_MI_PEDIGREE_COLOR',
    'description' => '_MI_PEDIGREE_COLOR_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textbox',
    'default'     => '#663300;#999966;#B2B27F;#333333;#020000;#80804D;#999999;#663300',
];

// Should Welcome be shown?
$modversion['config'][] = [
    'name'        => 'showwelcome',
    'title'       => '_MI_PEDIGREE_SHOWELCOME',
    'description' => '_MI_PEDIGREE_SHOWELCOME_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'adminpager',
    'title'       => 'MI_PEDIGREE_ADMINPAGER',
    'description' => 'MI_PEDIGREE_ADMINPAGER_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    'name'        => 'userpager',
    'title'       => 'MI_PEDIGREE_USERPAGER',
    'description' => 'MI_PEDIGREE_USERPAGER_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

// Files configs
$modversion['config'][] = [
    'name'        => 'filesuploads_configs',
    'title'       => '_MI_PEDIGREE_FILESUPLOADS_CONFIGS',
    'description' => '_MI_PEDIGREE_FILESUPLOADS_CONFIGSDSC',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd',
];

// Path for uploads
$modversion['config'][] = [
    'name'        => 'uploaddir',
    'title'       => '_MI_PEDIGREE_UPLOADDIR',
    'description' => '_MI_PEDIGREE_UPLOADDIRDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => XOOPS_ROOT_PATH . '/uploads/' . $modversion['dirname'],
];

// Max file size in bytes
$modversion['config'][] = [
    'name'        => 'maxfilesize',
    'title'       => '_MI_PEDIGREE_MAXFILESIZE',
    'description' => '_MI_PEDIGREE_MAXFILESIZEDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 2097152,
]; // 2MB

// Max image width in px
$modversion['config'][] = [
    'name'        => 'maximgwidth',
    'title'       => '_MI_PEDIGREE_IMGWIDTH',
    'description' => '_MI_PEDIGREE_IMGWIDTHDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 1500,
];

// Max image height in px
$modversion['config'][] = [
    'name'        => 'maximgheight',
    'title'       => '_MI_PEDIGREE_IMGHEIGHT',
    'description' => '_MI_PEDIGREE_IMGHEIGHTDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 1000,
];

// Make Sample button visible?
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

// Show Developer Tools?
$modversion['config'][] = [
    'name'        => 'displayDeveloperTools',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

// Menu contents
$modversion['hasMain'] = 1;
$i                     = 0;
$modversion['sub'][]   = [
    'name' => _MI_PEDIGREE_VIEW_SEARCH,
    'url'  => 'index.php',
];
// only show options if user is registered
if ((!empty($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser'] instanceof \XoopsUser) || !$GLOBALS['xoopsUser']->isGuest()) {
    $modversion['sub'][] = [
        'name' => _MI_PEDIGREE_ADD_ANIMAL,
        'url'  => 'add_dog.php',
    ];
    $modversion['sub'][] = [
        'name' => _MI_PEDIGREE_ADD_LITTER,
        'url'  => 'add_litter.php',
    ];
    $modversion['sub'][] = [
        'name' => _MI_PEDIGREE_ADD_OWNER,
        'url'  => 'add_breeder.php',
    ];
}
$modversion['sub'][] = [
    'name' => _MI_PEDIGREE_VIEW_OWNERS,
    'url'  => 'breeder.php',
];
$modversion['sub'][] = [
    'name' => _MI_PEDIGREE_ADVANCED_INFO,
    'url'  => 'advanced.php',
];
$modversion['sub'][] = [
    'name' => _MI_PEDIGREE_VIRTUAL_MATING,
    'url'  => 'virtual.php',
];
$modversion['sub'][] = [
    'name' => _MI_PEDIGREE_LATEST_ADDITIONS,
    'url'  => 'latest.php',
];

if (!empty($GLOBALS['xoopsUser']) && ($GLOBALS['xoopsUser'] instanceof \XoopsUser) && $GLOBALS['xoopsUser']->isAdmin()) {
    $modversion['sub'][] = [
        'name' => _MI_PEDIGREE_WEBMASTER_TOOLS,
        'url'  => 'tools.php?op=index',
    ];
}

// ------------------- Templates ------------------- //
$modversion['templates'] = [
    ['file' => 'pedigree_index.tpl', 'description' => _MI_PEDIGREE_TEMPL_INDEX],
    ['file' => 'pedigree_header.tpl', 'description' => _MI_PEDIGREE_TEMPL_HEADER],
    ['file' => 'pedigree_pedigree.tpl', 'description' => _MI_PEDIGREE_TEMPL_TREE],
    ['file' => 'pedigree_result.tpl', 'description' => _MI_PEDIGREE_TEMPL_RESULTS],
    ['file' => 'pedigree_latest.tpl', 'description' => _MI_PEDIGREE_TEMPL_LATEST],
    ['file' => 'pedigree_breeder.tpl', 'description' => _MI_PEDIGREE_TEMPL_OWNER],
    ['file' => 'pedigree_dog.tpl', 'description' => _MI_PEDIGREE_TEMPL_ANIMAL],
    ['file' => 'pedigree_owner.tpl', 'description' => _MI_PEDIGREE_TEMPL_OWNER_DETAILS],
    ['file' => 'pedigree_update.tpl', 'description' => _MI_PEDIGREE_TEMPL_UPDATE],
    ['file' => 'pedigree_sel.tpl', 'description' => _MI_PEDIGREE_TEMPL_SELECT],
    ['file' => 'pedigree_coi.tpl', 'description' => _MI_PEDIGREE_TEMPL_COI],
    ['file' => 'pedigree_members.tpl', 'description' => _MI_PEDIGREE_TEMPL_TOP50],
    ['file' => 'pedigree_advanced.tpl', 'description' => _MI_PEDIGREE_TEMPL_ADVANCED_INFO],
    ['file' => 'pedigree_adddog.tpl', 'description' => _MI_PEDIGREE_TEMPL_ANIMAL_ADD],
    ['file' => 'pedigree_addlitter.tpl', 'description' => _MI_PEDIGREE_TEMPL_LITTER_ADD],
    ['file' => 'pedigree_delete.tpl', 'description' => _MI_PEDIGREE_TEMPL_DELETE_CONFIRM],
    ['file' => 'pedigree_welcome.tpl', 'description' => _MI_PEDIGREE_TEMPL_WELCOME],
    ['file' => 'pedigree_virtual.tpl', 'description' => _MI_PEDIGREE_TEMPL_VIRTUAL_MATING],
    ['file' => 'pedigree_mpedigree.tpl', 'description' => _MI_PEDIGREE_TEMPL_MEGAPEDIGREE],
    ['file' => 'pedigree_book.tpl', 'description' => _MI_PEDIGREE_TEMPL_BOOK],
    ['file' => 'pedigree_tools.tpl', 'description' => _MI_PEDIGREE_TEMPL_TOOLS],
    ['file' => 'pedigree_edit.tpl', 'description' => _MI_PEDIGREE_TEMPL_PAGE_EDIT],
    ['file' => 'table_sort.tpl', 'description' => _MI_PEDIGREE_TEMPL_TABLE_SORT],
    ['file' => 'pedigree_common_breadcrumb.tpl', 'description' => _MI_PEDIGREE_TEMPL_BREADCRUMB],
    ['file' => 'pedigree_common_letterschoice.tpl', 'description' => _MI_PEDIGREE_TEMPL_LETTERCHOICE],
];

// ------------------- Blocks ------------------- //
// (Start indexes with 1, not 0!)

//this block shows the Pedigree menu
$modversion['blocks'][] = [
    'file'        => 'menu_block.php',
    'name'        => _MI_PEDIGREE_BLOCK_MENU_TITLE,
    'description' => _MI_PEDIGREE_BLOCK_MENU_DESC,
    'show_func'   => 'menu_block',
    'template'    => 'pedigree_menu.tpl',
];

// Search function
$modversion['hasSearch'] = 1;
$modversion['search'][]  = [
    'file' => 'include/search.inc.php',
    'func' => 'pedigree_search',
];

//comments function
/** @TODO: implement comments */
/*
$modversion['hasComments'] = 1;
$modversion['comments'][]  = array(
    'itemName' => "id",
    'pageName' => "dog.php"
);
*/

// notifications function
$modversion['hasNotification'] = 1;
$modversion['notification'][]  = [
    'lookup_file' => 'include/notification.inc.php',
    'lookup_func' => 'lookup',
];

//notify of changes in the dog's data

$modversion['notification']['category'][] = [
    'name'           => 'dog',
    'title'          => _MI_PEDIGREE_DOG_NOTIFY,
    'description'    => _MI_PEDIGREE_DOG_NOTIFY_DSC,
    'subscribe_from' => ['dog.php', 'pedigree.php'],
    'item_name'      => 'id',
    'allow_bookmark' => 1,
];
$modversion['notification']['event'][]    = [
    'name'          => 'change_data',
    'category'      => 'dog',
    'title'         => _MI_PEDIGREE_DATA_NOTIFY,
    'caption'       => _MI_PEDIGREE_DATA_NOTIFYCAP,
    'description'   => _MI_PEDIGREE_DATA_NOTIFYDSC,
    'mail_template' => 'dog_data_notify',
    'mail_subject'  => _MI_PEDIGREE_DATA_NOTIFYSBJ,
];

//comments function
$modversion['hasComments']          = 1;
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['pageName'] = 'dog.php';

/** @TODO implement notifications */
// notifications function
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'lookup';

//notify of changes in the dog's data
/*
$modversion['notification']['category'][1]['name']           = 'dog';
$modversion['notification']['category'][1]['title']          = _MI_PEDIGREE_DOG_NOTIFY;
$modversion['notification']['category'][1]['description']    = _MI_PEDIGREE_DOG_NOTIFY_DSC;
$modversion['notification']['category'][1]['subscribe_from'] = array('dog.php', 'pedigree.php');
$modversion['notification']['category'][1]['item_name']      = 'id';
$modversion['notification']['category'][1]['allow_bookmark'] = 1;

$modversion['notification']['event'][1]['name']          = 'change_data';
$modversion['notification']['event'][1]['category']      = 'dog';
$modversion['notification']['event'][1]['title']         = _MI_PEDIGREE_DATA_NOTIFY;
$modversion['notification']['event'][1]['caption']       = _MI_PEDIGREE_DATA_NOTIFYCAP;
$modversion['notification']['event'][1]['description']   = _MI_PEDIGREE_DATA_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'dog_data_notify';
$modversion['notification']['event'][1]['mail_subject']  = _MI_PEDIGREE_DATA_NOTIFYSBJ;
*/
