<?php
// -------------------------------------------------------------------------
//	pedigree
//		Copyright 2004, James Cotton
// 		http://www.dobermannvereniging.nl
//	Template
//		Copyright 2004 Thomas Hill
//		<a href="http://www.worldware.com">worldware.com</a>
// -------------------------------------------------------------------------
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
$modversion['name']        = _MI_PEDIGREE_NAME;
$modversion['version']     = 1.31;
$modversion['description'] = _MI_PEDIGREE_DESC;
$modversion['credits']     = "http://tech.groups.yahoo.com/group/animalpedigree/";
$modversion['author']      = "James Cotton";
$modversion['help']        = "page=pedigree_admin";
//$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html";
$modversion['official']    = 0;
$modversion['image']       = "assets/images/logo.png";
$modversion['dirname']     = basename(__DIR__);

$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';

$modversion['onInstall'] = 'include/install_function.php';
$modversion['onUpdate']  = 'include/update_function.php';

//about
$modversion['release_date']        = '2014/11/22';
$modversion["module_status"]       = "Alpha 6";
$modversion["module_website_url"]  = "www.xoops.org";
$modversion["module_website_name"] = "XOOPS";
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = "2.5.7.2";
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);

// SQL file - All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'] = array(
    "pedigree_tree",
    "pedigree_fields",
    "pedigree_temp",
    "pedigree_trash",
    "pedigree_owner"
);

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = "admin/index.php";
$modversion['adminmenu']   = "admin/menu.php";
//admin settings

$modversion['config'][] = array(
    'name'        => 'proversion',
    'title'       => '_MI_PEDIGREE_PROVERSION',
    'description' => '_MI_PEDIGREE_PROVERSION_DESC',//'is this the pro version ?',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
);

$modversion['config'][] = array(
    'name'        => 'ownerbreeder',
    'title'       => '_MI_PEDIGREE_OWNERBREEDER',
    'description' => '_MI_PEDIGREE_OWNERBREEDER_DESC',//'should the owner/breeder fields be used ?',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
);

$modversion['config'][] = array(
    'name'        => 'brothers',
    'title'       => '_MI_PEDIGREE_BROTHERS',
    'description' => '_MI_PEDIGREE_BROTHERS_DESC',//'should the brothers & sisters field be shown ?',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
);

$modversion['config'][] = array(
    'name'        => 'pups',
    'title'       => '_MI_PEDIGREE_PUPS',
    'description' => '_MI_PEDIGREE_PUPS_DESC',//'should the pups/children field be shown ?',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
);

$modversion['config'][] = array(
    'name'        => 'perpage',
    'title'       => '_MI_PEDIGREE_MENU_PERP',
    'description' => '_MI_PEDIGREE_MENU_PERP_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 100,
    'options'     => array('10' => 10, '50' => 50, '100' => 100, '250' => 250, '500' => 500, '1000' => 1000)
);

$modversion['config'][] = array(
    'name'        => 'animalType',
    'title'       => '_MI_PEDIGREE_ANIMALTYPE',
    'description' => '_MI_PEDIGREE_ANIMALTYPE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'dog'
);

$modversion['config'][] = array(
    'name'        => 'animalTypes',
    'title'       => '_MI_PEDIGREE_ANIMALTYPES',
    'description' => '_MI_PEDIGREE_ANIMALTYPES_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'dogs'
);

$modversion['config'][] = array(
    'name'        => 'lastimage',
    'title'       => '_MI_PEDIGREE_LASTIMAGE',
    'description' => '_MI_PEDIGREE_LASTIMAGE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
);

$modversion['config'][] = array(
    'name'        => 'children',
    'title'       => '_MI_PEDIGREE_CHILDREN',//'language option children',
    'description' => '_MI_PEDIGREE_CHILDREN_DESC',//'language option children',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'children'
);

$modversion['config'][] = array(
    'name'        => 'welcome',
    'title'       => '_MI_PEDIGREE_WELCOME',
    'description' => '_MI_PEDIGREE_WELCOME_DESC',//'language option children',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => ' Welcome to the online pedigree database.

This project that now has [numanimals] [animalType] pedigrees has been made to give a better picture of the [animalType] race.
By connecting these [numanimals] pedigrees together one giant pedigree is created with [animalTypes] from around the world.

Because all the information is only added once to the database it becomes very easy to find what you are looking for.

When the correct [animalType] has been found you are able to view its pedigree. This shows you the selected [animalType], the parents, the grandparents and the great-grandparents. You can click on any of these [animalTypes] to view their pedigree. This way you can "walk" through a [animalType] pedigree and go back many generations.

Because so many pedigrees have been merged into one big one lots of interesting data can be shown. It is possible to calculate the coefficients of Kinship, Relationship and Inbreeding of any [animalType] very accurately. Using such tools has shown this pedigree database to be an extremely valuable resource of information used by breeders and enthusiasts.

To keep a little controle over the [animalTypes] entered into the database only registered members of the website are allowed to enter information into the database. Registration is free and will give you full access to all the elements of this website.'
);

$modversion['config'][] = array(
    'name'        => 'mother',
    'title'       => '_MI_PEDIGREE_MOTHER',
    'description' => '_MI_PEDIGREE_MOTHER_DESC',//'language option mother',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'mother'
);

$modversion['config'][] = array(
    'name'        => 'father',
    'title'       => '_MI_PEDIGREE_FATHER',
    'description' => '_MI_PEDIGREE_FATHER_DESC',//'language option father',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'father'
);

$modversion['config'][] = array(
    'name'        => 'female',
    'title'       => '_MI_PEDIGREE_FEMALE',
    'description' => '_MI_PEDIGREE_FEMALE_DESC',//'language option female',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'female'
);

$modversion['config'][] = array(
    'name'        => 'male',
    'title'       => '_MI_PEDIGREE_MALE',
    'description' => '_MI_PEDIGREE_MALE_DESC',//'language option male',
    'formtype'    => 'textbox',
    'valuetype'   => 'textarea',
    'default'     => 'male'
);

$modversion['config'][] = array(
    'name'        => 'litter',
    'title'       => '_MI_PEDIGREE_LITTER',
    'description' => '_MI_PEDIGREE_LITTER_DESC',//'language option litter',
    'formtype'    => 'textbox',
    'valuetype'   => 'textbox',
    'default'     => 'litter'
);

$modversion['config'][] = array(
    'name'        => 'uselitter',
    'title'       => '_MI_PEDIGREE_USELITTER',
    'description' => '_MI_PEDIGREE_USELITTER_DESC',//'should the litter feature be used ?',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
);

$modversion['config'][] = array(
    'name'        => 'colourscheme',
    'title'       => '_MI_PEDIGREE_COLOR',
    'description' => '_MI_PEDIGREE_COLOR_DESC',//'The colour scheme to be used',
    'formtype'    => 'textbox',
    'valuetype'   => 'textbox',
    'default'     => '#663300,#999966,#B2B27F,#333333,#020000,#80804D,#999999,#663300'
);

$modversion['config'][] = array(
    'name'        => 'showwelcome',
    'title'       => '_MI_PEDIGREE_SHOWELCOME',
    'description' => '_MI_PEDIGREE_SHOWELCOME_DESC',//'Show the welcome screen',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
);

// Files configs
$modversion['config'][] = array(
    'name'        => 'filesuploads_configs',
    'title'       => '_MI_PEDIGREE_FILESUPLOADS_CONFIGS',
    'description' => '_MI_PEDIGREE_FILESUPLOADS_CONFIGSDSC',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd'
);

$modversion['config'][] = array(
    'name'        => 'uploaddir',
    'title'       => '_MI_PEDIGREE_UPLOADDIR',
    'description' => '_MI_PEDIGREE_UPLOADDIRDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => XOOPS_ROOT_PATH . '/uploads/' . $modversion['dirname']
);

$modversion['config'][] = array(
    'name'        => 'maxfilesize',
    'title'       => '_MI_PEDIGREE_MAXFILESIZE',
    'description' => '_MI_PEDIGREE_MAXFILESIZEDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 2097152
); // 2MB

$modversion['config'][] = array(
    'name'        => 'maximgwidth',
    'title'       => '_MI_PEDIGREE_IMGWIDTH',
    'description' => '_MI_PEDIGREE_IMGWIDTHDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 1500
);

$modversion['config'][] = array(
    'name'        => 'maximgheight',
    'title'       => '_MI_PEDIGREE_IMGHEIGHT',
    'description' => '_MI_PEDIGREE_IMGHEIGHTDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 1000
);

// Menu contents
$i                     = 0;
$modversion['hasMain'] = 1;

$modversion['sub'][] = array(
    'name' => _MI_PEDIGREE_VIEW_SEARCH,
    'url'  => "index.php"
);
$modversion['sub'][] = array(
    'name' => _MI_PEDIGREE_ADD_ANIMAL,
    'url'  => "add_dog.php"
);
$modversion['sub'][] = array(
    'name' => _MI_PEDIGREE_ADD_LITTER,
    'url'  => "add_litter.php"
);
$modversion['sub'][] = array(
    'name' => _MI_PEDIGREE_VIEW_OWNERS,
    'url'  => "breeder.php"
);
$modversion['sub'][] = array(
    'name' => _MI_PEDIGREE_ADD_OWNER,
    'url'  => "add_breeder.php"
);
$modversion['sub'][] = array(
    'name' => _MI_PEDIGREE_ADVANCED_INFO,
    'url'  => "advanced.php"
);
$modversion['sub'][] = array(
    'name' => _MI_PEDIGREE_VIRTUAL_MATING,
    'url'  => "virtual.php"
);
$modversion['sub'][] = array(
    'name' => _MI_PEDIGREE_LATEST_ADDITIONS,
    'url'  => "latest.php"
);

global $xoopsUser;
if (is_object($xoopsUser)) {
    if ($xoopsUser->isAdmin()) {
        $modversion['sub'][] = array(
            'name' => _MI_PEDIGREE_WEBMASTER_TOOLS,
            'url'  => "tools.php?op=index"
        );
    }
}

// Templates
$modversion['templates'][] = array(
    'file'        => 'pedigree_index.tpl',
    'description' => _MI_PEDIGREE_TEMPL_INDEX
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_pedigree.tpl',
    'description' => _MI_PEDIGREE_TEMPL_TREE
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_result.tpl',
    'description' => _MI_PEDIGREE_TEMPL_RESULTS
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_latest.tpl',
    'description' => _MI_PEDIGREE_TEMPL_LATEST
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_breeder.tpl',
    'description' => _MI_PEDIGREE_TEMPL_OWNER
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_dog.tpl',
    'description' => _MI_PEDIGREE_TEMPL_ANIMAL
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_owner.tpl',
    'description' => _MI_PEDIGREE_TEMPL_OWNER_DETAILS
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_update.tpl',
    'description' => _MI_PEDIGREE_TEMPL_UPDATE
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_sel.tpl',
    'description' => _MI_PEDIGREE_TEMPL_SELECT
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_coi.tpl',
    'description' => _MI_PEDIGREE_TEMPL_COI
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_members.tpl',
    'description' => _MI_PEDIGREE_TEMPL_TOP50
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_advanced.tpl',
    'description' => _MI_PEDIGREE_TEMPL_ADVANCED_INFO
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_adddog.tpl',
    'description' => _MI_PEDIGREE_TEMPL_ANIMAL_ADD
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_addlitter.tpl',
    'description' => _MI_PEDIGREE_TEMPL_LITTER_ADD
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_delete.tpl',
    'description' => _MI_PEDIGREE_TEMPL_DELETE_CONFIRM
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_welcome.tpl',
    'description' => _MI_PEDIGREE_TEMPL_WELCOME
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_virtual.tpl',
    'description' => _MI_PEDIGREE_TEMPL_VIRTUAL_MATING
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_mpedigree.tpl',
    'description' => _MI_PEDIGREE_TEMPL_MEGAPEDIGREE
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_book.tpl',
    'description' => _MI_PEDIGREE_TEMPL_BOOK
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_tools.tpl',
    'description' => _MI_PEDIGREE_TEMPL_TOOLS
);
$modversion['templates'][] = array(
    'file'        => 'pedigree_edit.tpl',
    'description' => _MI_PEDIGREE_TEMPL_PAGE_EDIT
);
$modversion['templates'][] = array(
    'file'        => 'table_sort.tpl',
    'description' => _MI_PEDIGREE_TEMPL_TABLE_SORT
);

$modversion['templates'][] = array(
    'file'        => 'pedigree_common_breadcrumb.tpl',
    'description' => _MI_PEDIGREE_TEMPL_BREADCRUMB
);

// Blocks (Start indexes with 1, not 0!)

//this block shows the random pedigrees
$modversion['blocks'][] = array(
    'file'        => "menu_block.php",
    'name'        => _MI_PEDIGREE_BLOCK_MENU_TITLE,
    'description' => _MI_PEDIGREE_BLOCK_MENU_DESC,
    'show_func'   => "menu_block",
    'template'    => 'pedigree_menu.tpl'
);

// Search function
$modversion['hasSearch'] = 1;
$modversion['search'][]  = array(
    'file' => "include/search.inc.php",
    'func' => "pedigree_search"
);

//comments function
//$modversion['hasComments'] = 1;
//$modversion['comments'][]  = array(
//    'itemName' => "id",
//    'pageName' => "dog.php"
//);

//notifications function
$modversion['hasNotification'] = 1;
$modversion['notification'][]  = array(
    'lookup_file' => 'include/notification.inc.php',
    'lookup_func' => 'lookup'
);

//notify of changes in the dog's data

$modversion['notification']['category'][] = array(
    'name'           => 'dog',
    'title'          => _MI_PEDIGREE_DOG_NOTIFY,
    'description'    => _MI_PEDIGREE_DOG_NOTIFY_DSC,
    'subscribe_from' => array('dog.php', 'pedigree.php'),
    'item_name'      => "id",
    'allow_bookmark' => 1
);
$modversion['notification']['event'][]    = array(
    'name'          => 'change_data',
    'category'      => 'dog',
    'title'         => _MI_PEDIGREE_DATA_NOTIFY,
    'caption'       => _MI_PEDIGREE_DATA_NOTIFYCAP,
    'description'   => _MI_PEDIGREE_DATA_NOTIFYDSC,
    'mail_template' => "dog_data_notify",
    'mail_subject'  => _MI_PEDIGREE_DATA_NOTIFYSBJ
);

//comments function
$modversion['hasComments']          = 1;
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['pageName'] = 'dog.php';
//
////notifications function
//$modversion['hasNotification']             = 1;
//$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
//$modversion['notification']['lookup_func'] = 'lookup';
//
////notify of changes in the dog's data
//
//$modversion['notification']['category'][1]['name']           = 'dog';
//$modversion['notification']['category'][1]['title']          = _MI_PEDIGREE_DOG_NOTIFY;
//$modversion['notification']['category'][1]['description']    = _MI_PEDIGREE_DOG_NOTIFY_DSC;
//$modversion['notification']['category'][1]['subscribe_from'] = array('dog.php', 'pedigree.php');
//$modversion['notification']['category'][1]['item_name']      = 'id';
//$modversion['notification']['category'][1]['allow_bookmark'] = 1;
//
//$modversion['notification']['event'][1]['name']          = 'change_data';
//$modversion['notification']['event'][1]['category']      = 'dog';
//$modversion['notification']['event'][1]['title']         = _MI_PEDIGREE_DATA_NOTIFY;
//$modversion['notification']['event'][1]['caption']       = _MI_PEDIGREE_DATA_NOTIFYCAP;
//$modversion['notification']['event'][1]['description']   = _MI_PEDIGREE_DATA_NOTIFYDSC;
//$modversion['notification']['event'][1]['mail_template'] = 'dog_data_notify';
//$modversion['notification']['event'][1]['mail_subject']  = _MI_PEDIGREE_DATA_NOTIFYSBJ;
