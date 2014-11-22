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
$modversion['image']       = "images/pedigree.png";
$modversion['dirname']     = basename(__DIR__);

$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';

$modversion['onInstall'] = 'include/install_function.php';
$modversion['onUpdate']  = 'include/update_function.php';

//about
$modversion['release_date']        = '2014/11/08';
$modversion["module_website_url"]  = "www.xoops.org";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"]       = "Alpha 4";
$modversion['min_php']             = '5.3.7';
$modversion['min_xoops']           = "2.5.7";
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);

// SQL file - All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql (without prefix!)
$modversion['tables'][] = "pedigree_tree";
$modversion['tables'][] = "pedigree_fields";
$modversion['tables'][] = "pedigree_temp";
$modversion['tables'][] = "pedigree_trash";
$modversion['tables'][] = "pedigree_owner";

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = "admin/index.php";
$modversion['adminmenu']   = "admin/menu.php";
//admin settings
$modversion['config'][0]['name']        = 'proversion';
$modversion['config'][0]['title']       = '_MI_PEDIGREE_PROVERSION';
$modversion['config'][0]['description'] = 'is this the pro version ?';
$modversion['config'][0]['formtype']    = 'yesno';
$modversion['config'][0]['valuetype']   = 'int';
$modversion['config'][0]['default']     = 1;

$modversion['config'][1]['name']        = 'ownerbreeder';
$modversion['config'][1]['title']       = '_MI_PEDIGREE_OWNERBREEDER';
$modversion['config'][1]['description'] = 'should the owner/breeder fields be used ?';
$modversion['config'][1]['formtype']    = 'yesno';
$modversion['config'][1]['valuetype']   = 'int';
$modversion['config'][1]['default']     = 1;

$modversion['config'][2]['name']        = 'brothers';
$modversion['config'][2]['title']       = '_MI_PEDIGREE_BROTHERS';
$modversion['config'][2]['description'] = 'should the brothers & sisters field be shown ?';
$modversion['config'][2]['formtype']    = 'yesno';
$modversion['config'][2]['valuetype']   = 'int';
$modversion['config'][2]['default']     = 1;

$modversion['config'][3]['name']        = 'pups';
$modversion['config'][3]['title']       = '_MI_PEDIGREE_PUPS';
$modversion['config'][3]['description'] = 'should the pups/children field be shown ?';
$modversion['config'][3]['formtype']    = 'yesno';
$modversion['config'][3]['valuetype']   = 'int';
$modversion['config'][3]['default']     = 1;

$modversion['config'][4]['name']        = 'perpage';
$modversion['config'][4]['title']       = '_MI_PEDIGREE_MENU_PERP';
$modversion['config'][4]['description'] = '_MI_PEDIGREE_MENU_PERP_DESC';
$modversion['config'][4]['formtype']    = 'select';
$modversion['config'][4]['valuetype']   = 'int';
$modversion['config'][4]['default']     = 100;
$modversion['config'][4]['options']     = array('50' => 50, '100' => 100, '250' => 250, '500' => 500, '1000' => 1000);

$modversion['config'][6]['name']        = 'animalType';
$modversion['config'][6]['title']       = '_MI_PEDIGREE_ANIMALTYPE';
$modversion['config'][6]['description'] = '_MI_PEDIGREE_ANIMALTYPE_DESC';
$modversion['config'][6]['formtype']    = 'textbox';
$modversion['config'][6]['valuetype']   = 'textarea';
$modversion['config'][6]['default']     = 'dog';

$modversion['config'][7]['name']        = 'animalTypes';
$modversion['config'][7]['title']       = '_MI_PEDIGREE_ANIMALTYPES';
$modversion['config'][7]['description'] = '_MI_PEDIGREE_ANIMALTYPES_DESC';
$modversion['config'][7]['formtype']    = 'textbox';
$modversion['config'][7]['valuetype']   = 'textarea';
$modversion['config'][7]['default']     = 'dogs';

$modversion['config'][8]['name']        = 'lastimage';
$modversion['config'][8]['title']       = '_MI_PEDIGREE_LASTIMAGE';
$modversion['config'][8]['description'] = '_MI_PEDIGREE_LASTIMAGE_DESC';
$modversion['config'][8]['formtype']    = 'yesno';
$modversion['config'][8]['valuetype']   = 'int';
$modversion['config'][8]['default']     = 0;

$modversion['config'][9]['name']        = 'children';
$modversion['config'][9]['title']       = 'language option children';
$modversion['config'][9]['description'] = 'language option children';
$modversion['config'][9]['formtype']    = 'textbox';
$modversion['config'][9]['valuetype']   = 'textarea';
$modversion['config'][9]['default']     = 'children';

$modversion['config'][10]['name']        = 'welcome';
$modversion['config'][10]['title']       = '_MI_PEDIGREE_WELCOME';
$modversion['config'][10]['description'] = 'language option children';
$modversion['config'][10]['formtype']    = 'textarea';
$modversion['config'][10]['valuetype']   = 'text';
$modversion['config'][10]['default']
                                         = ' Welcome to the online pedigree database.

This project that now has [numanimals] [animalType] pedigrees has been made to give a better picture of the [animalType] race.
By connecting these [numanimals] pedigrees together one giant pedigree is created with [animalTypes] from around the world.

Because all the information is only added once to the database it becomes very easy to find what you are looking for.

When the correct [animalType] has been found you are able to view its pedigree. This shows you the selected [animalType], the parents, the grandparents and the great-grandparents. You can click on any of these [animalTypes] to view their pedigree. This way you can "walk" through a [animalType] pedigree and go back many generations.

Because so many pedigrees have been merged into one big one lots of interesting data can be shown. It is possible to calculate the coefficients of Kinship, Relationship and Inbreeding of any [animalType] very accurately. Using such tools has shown this pedigree database to be an extremely valuable resource of information used by breeders and enthusiasts.

To keep a little control over the [animalTypes] entered into the database only registered members of the website are allowed to enter information into the database. Registration is free and will give you full access to all the elements of this website.';

$modversion['config'][11]['name']        = 'mother';
$modversion['config'][11]['title']       = '_MI_PEDIGREE_MOTHER';
$modversion['config'][11]['description'] = 'language option mother';
$modversion['config'][11]['formtype']    = 'textbox';
$modversion['config'][11]['valuetype']   = 'textarea';
$modversion['config'][11]['default']     = 'mother';

$modversion['config'][12]['name']        = 'father';
$modversion['config'][12]['title']       = '_MI_PEDIGREE_FATHER';
$modversion['config'][12]['description'] = 'language option father';
$modversion['config'][12]['formtype']    = 'textbox';
$modversion['config'][12]['valuetype']   = 'textarea';
$modversion['config'][12]['default']     = 'father';

$modversion['config'][13]['name']        = 'female';
$modversion['config'][13]['title']       = '_MI_PEDIGREE_FEMALE';
$modversion['config'][13]['description'] = 'language option female';
$modversion['config'][13]['formtype']    = 'textbox';
$modversion['config'][13]['valuetype']   = 'textarea';
$modversion['config'][13]['default']     = 'female';

$modversion['config'][14]['name']        = 'male';
$modversion['config'][14]['title']       = '_MI_PEDIGREE_MALE';
$modversion['config'][14]['description'] = 'language option male';
$modversion['config'][14]['formtype']    = 'textbox';
$modversion['config'][14]['valuetype']   = 'textarea';
$modversion['config'][14]['default']     = 'male';

$modversion['config'][15]['name']        = 'litter';
$modversion['config'][15]['title']       = '_MI_PEDIGREE_LITTER';
$modversion['config'][15]['description'] = 'language option litter';
$modversion['config'][15]['formtype']    = 'textbox';
$modversion['config'][15]['valuetype']   = 'textbox';
$modversion['config'][15]['default']     = 'litter';

$modversion['config'][16]['name']        = 'uselitter';
$modversion['config'][16]['title']       = '_MI_PEDIGREE_USELITTER';
$modversion['config'][16]['description'] = 'should the litter feature be used ?';
$modversion['config'][16]['formtype']    = 'yesno';
$modversion['config'][16]['valuetype']   = 'int';
$modversion['config'][16]['default']     = 1;

$modversion['config'][17]['name']        = 'colourscheme';
$modversion['config'][17]['title']       = '_MI_PEDIGREE_COLOR';
$modversion['config'][17]['description'] = 'The colour scheme to be used';
$modversion['config'][17]['formtype']    = 'textbox';
$modversion['config'][17]['valuetype']   = 'textbox';
$modversion['config'][17]['default']     = '#663300;#999966;#B2B27F;#333333;#020000;#80804D;#999999;#663300';

$modversion['config'][18]['name']        = 'showwelcome';
$modversion['config'][18]['title']       = '_MI_PEDIGREE_SHOWELCOME';
$modversion['config'][18]['description'] = 'Show the welcome screen';
$modversion['config'][18]['formtype']    = 'yesno';
$modversion['config'][18]['valuetype']   = 'int';
$modversion['config'][18]['default']     = 1;

// Menu contents
$i                             = 0;
$modversion['hasMain']         = 1;
$modversion['sub'][$i]['name'] = _MI_PEDIGREE_VIEW_SEARCH;
$modversion['sub'][$i]['url']  = "index.php";
++$i;
$modversion['sub'][$i]['name'] = _MI_PEDIGREE_ADD_ANIMAL;
$modversion['sub'][$i]['url']  = "add_dog.php";
++$i;
$modversion['sub'][$i]['name'] = _MI_PEDIGREE_ADD_LITTER;
$modversion['sub'][$i]['url']  = "add_litter.php";
++$i;
$modversion['sub'][$i]['name'] = _MI_PEDIGREE_VIEW_OWNERS;
$modversion['sub'][$i]['url']  = "breeder.php";
++$i;
$modversion['sub'][$i]['name'] = _MI_PEDIGREE_ADD_OWNER;
$modversion['sub'][$i]['url']  = "add_breeder.php";
++$i;
$modversion['sub'][$i]['name'] = _MI_PEDIGREE_ADVANCED_INFO;
$modversion['sub'][$i]['url']  = "advanced.php";
++$i;
$modversion['sub'][$i]['name'] = _MI_PEDIGREE_VIRTUAL_MATING;
$modversion['sub'][$i]['url']  = "virtual.php";
++$i;
$modversion['sub'][$i]['name'] = _MI_PEDIGREE_LATEST_ADDITIONS;
$modversion['sub'][$i]['url']  = "latest.php";
++$i;
$modversion['sub'][$i]['name'] = _MI_PEDIGREE_WEBMASTER_TOOLS;
$modversion['sub'][$i]['url']  = "tools.php?op=index";

// Templates
$modversion['templates'][0]['file']         = 'pedigree_index.html';
$modversion['templates'][0]['description']  = 'Pedigree Index Template';
$modversion['templates'][1]['file']         = 'pedigree_pedigree.html';
$modversion['templates'][1]['description']  = 'Pedigree-tree Template';
$modversion['templates'][2]['file']         = 'pedigree_result.html';
$modversion['templates'][2]['description']  = 'Pedigree results Template';
$modversion['templates'][3]['file']         = 'pedigree_latest.html';
$modversion['templates'][3]['description']  = 'Latest Additions Template';
$modversion['templates'][4]['file']         = 'pedigree_breeder.html';
$modversion['templates'][4]['description']  = 'View Owner/Breeder Template';
$modversion['templates'][5]['file']         = 'pedigree_dog.html';
$modversion['templates'][5]['description']  = 'View Dog details Template';
$modversion['templates'][6]['file']         = 'pedigree_owner.html';
$modversion['templates'][6]['description']  = 'View Owner details Template';
$modversion['templates'][7]['file']         = 'pedigree_update.html';
$modversion['templates'][7]['description']  = 'Update details Template';
$modversion['templates'][8]['file']         = 'pedigree_sel.html';
$modversion['templates'][8]['description']  = 'select dog Template';
$modversion['templates'][9]['file']         = 'pedigree_coi.html';
$modversion['templates'][9]['description']  = 'Coefficient of Inbreeding Template';
$modversion['templates'][10]['file']        = 'pedigree_members.html';
$modversion['templates'][10]['description'] = 'Members top 50 Template';
$modversion['templates'][11]['file']        = 'pedigree_advanced.html';
$modversion['templates'][11]['description'] = 'Advanced info Template';
$modversion['templates'][12]['file']        = 'pedigree_adddog.html';
$modversion['templates'][12]['description'] = 'Add a dog Template';
$modversion['templates'][13]['file']        = 'pedigree_addlitter.html';
$modversion['templates'][13]['description'] = 'Add litter Template';
$modversion['templates'][14]['file']        = 'pedigree_delete.html';
$modversion['templates'][14]['description'] = 'Deletion conformation Template';
$modversion['templates'][15]['file']        = 'pedigree_welcome.html';
$modversion['templates'][15]['description'] = 'Welcome Template';
$modversion['templates'][16]['file']        = 'pedigree_virtual.html';
$modversion['templates'][16]['description'] = 'Virtual Mating Template';
$modversion['templates'][17]['file']        = 'pedigree_mpedigree.html';
$modversion['templates'][17]['description'] = 'Megapedigree Template';
$modversion['templates'][18]['file']        = 'pedigree_book.html';
$modversion['templates'][18]['description'] = 'Pedigreebook Template';
$modversion['templates'][19]['file']        = 'pedigree_tools.html';
$modversion['templates'][19]['description'] = 'Tools Template';
$modversion['templates'][20]['file']        = 'pedigree_edit.html';
$modversion['templates'][20]['description'] = 'Edit page Template';
$modversion['templates'][21]['file']        = 'table_sort.html';
$modversion['templates'][21]['description'] = 'Template for javascript table sort';
$modversion['templates'][22]['file']        = 'pedigree_common_breadcrumb.html';
$modversion['templates'][22]['description'] = 'Breadcrumb';
// Blocks (Start indexes with 1, not 0!)

//this block shows the random pedigrees
$modversion['blocks'][1]['file']        = "menu_block.php";
$modversion['blocks'][1]['name']        = _MI_PEDIGREE_BLOCK_MENU_TITLE;
$modversion['blocks'][1]['description'] = _MI_PEDIGREE_BLOCK_MENU_DESC;
$modversion['blocks'][1]['show_func']   = "menu_block";
$modversion['blocks'][1]['template']    = 'pedigree_menu.html';

// Search function
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "pedigree_search";

//comments function
$modversion['hasComments']          = 1;
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['pageName'] = 'dog.php';

//notifications function
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'lookup';

//notify of changes in the dog's data

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
