<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
// ------------------------------------------------------------------------- //
// Author: Tobias Liegl (AKA CHAPI)                                          //
// Site: http://www.chapi.de                                                 //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/class_field.php");
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/config.php");

//get module configuration
$module_handler =& xoops_gethandler('module');
$module         =& $module_handler->getByDirname("pedigree");
$config_handler =& xoops_gethandler('config');
$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

/**
 * @param $columncount
 *
 * @return string
 */
function sorttable($columncount)
{
    $ttemp = "";
    if ($columncount > 1) {
        for ($t = 1; $t < $columncount; ++$t) {
            $ttemp .= "'S',";
        }
        $tsarray = "initSortTable('Result',Array(" . $ttemp . "'S'));";
    } else {
        $tsarray = "initSortTable('Result',Array('S'));";
    }

    return $tsarray;
}

/**
 * @param $num
 *
 * @return string
 */
function uploadedpict($num)
{
    global $xoopsModule;
    global $xoopsModule, $xoopsModuleConfig;
    $max_imgsize       = $xoopsModuleConfig['maxfilesize']; //1024000;
    $max_imgwidth      = $xoopsModuleConfig['maximgwidth']; //1500;
    $max_imgheight     = $xoopsModuleConfig['maximgheight']; //1000;
    $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
//    $img_dir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/images" ;
    $img_dir = $xoopsModuleConfig['uploaddir'] . '/images';
    include_once(XOOPS_ROOT_PATH . "/class/uploader.php");
    $field = $_POST["xoops_upload_file"][$num];
    if (!empty($field) || $field != "") {
        $uploader = new XoopsMediaUploader($img_dir, $allowed_mimetypes, $max_imgsize, $max_imgwidth, $max_imgheight);
        $uploader->setPrefix('img');
        if ($uploader->fetchMedia($field) && $uploader->upload()) {
            $photo = $uploader->getSavedFileName();
        } else {
            echo $uploader->getErrors();
        }
        makethumbs($photo);

        return $photo;
    }
}

/**
 * @param $filename
 *
 * @return bool
 */
function makethumbs($filename)
{/*
    require_once('phpthumb/phpthumb.class.php');
    $thumbnail_widths = array(150, 400);
    foreach ($thumbnail_widths as $thumbnail_width) {
        $phpThumb = new phpThumb();
        // set data
        $phpThumb->setSourceFilename('images/' . $filename);
        $phpThumb->w                    = $thumbnail_width;
        $phpThumb->config_output_format = 'jpeg';
        // generate & output thumbnail
        $output_filename = 'images/thumbnails/' . basename($filename) . '_' . $thumbnail_width . '.' . $phpThumb->config_output_format;
        if ($phpThumb->GenerateThumbnail()) { // this line is VERY important, do not remove it!
            if ($output_filename) {
                if ($phpThumb->RenderToFile($output_filename)) {
                    // do something on success
                    //echo 'Successfully rendered:<br><img src="'.$output_filename.'">';
                } else {
                    echo 'Failed (size=' . $thumbnail_width . '):<pre>' . implode("\n\n", $phpThumb->debugmessages) . '</pre>';
                }
            }
        } else {
            echo 'Failed (size=' . $thumbnail_width . '):<pre>' . implode("\n\n", $phpThumb->debugmessages) . '</pre>';
        }
        unset($phpThumb);
    }

    return true;

    */

    // load the image
    global $xoopsModule;
    require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . '/library/Zebra_Image.php');
    $thumbnail_widths = array(150, 400);

    // indicate a target image
    // note that there's no extra property to set in order to specify the target
    // image's type -simply by writing '.jpg' as extension will instruct the script
    // to create a 'jpg' file
    $config_output_format = 'jpeg';

// create a new instance of the class
    $image = new Zebra_Image();
    // indicate a source image (a GIF, PNG or JPEG file)
    $image->source_path = PEDIGREE_UPLOAD_PATH . '/images/' . $filename;

    foreach ($thumbnail_widths as $thumbnail_width) {

        // generate & output thumbnail
        $output_filename    = PEDIGREE_UPLOAD_PATH . '/images/thumbnails/' . basename($filename) . '_' . $thumbnail_width . '.' . $config_output_format;
        $image->target_path = $output_filename;
        // since in this example we're going to have a jpeg file, let's set the output
        // image's quality
        $image->jpeg_quality = 100;
        // some additional properties that can be set
        // read about them in the documentation
        $image->preserve_aspect_ratio  = true;
        $image->enlarge_smaller_images = true;
        $image->preserve_time          = true;

        // resize the image to exactly 100x100 pixels by using the "crop from center" method
        // (read more in the overview section or in the documentation)
        //  and if there is an error, check what the error is about
        if (!$image->resize($thumbnail_width, 0)) {
            // if there was an error, let's see what the error is about
            switch ($image->error) {

                case 1:
                    echo 'Source file could not be found!';
                    break;
                case 2:
                    echo 'Source file is not readable!';
                    break;
                case 3:
                    echo 'Could not write target file!';
                    break;
                case 4:
                    echo 'Unsupported source file format!';
                    break;
                case 5:
                    echo 'Unsupported target file format!';
                    break;
                case 6:
                    echo 'GD library version does not support target file format!';
                    break;
                case 7:
                    echo 'GD library is not installed!';
                    break;
                case 8:
                    echo '"chmod" command is disabled via configuration!';
                    break;
            }

            // if no errors
        } else {
            echo 'Success!';
        }

        /*
                if ($phpThumb->GenerateThumbnail()) { // this line is VERY important, do not remove it!
                    if ($output_filename) {
                        if ($phpThumb->RenderToFile($output_filename)) {
                            // do something on success
                            //echo 'Successfully rendered:<br><img src="'.$output_filename.'">';
                        } else {
                            echo 'Failed (size='.$thumbnail_width.'):<pre>'.implode("\n\n", $phpThumb->debugmessages).'</pre>';
                        }
                    }
                } else {
                    echo 'Failed (size='.$thumbnail_width.'):<pre>'.implode("\n\n", $phpThumb->debugmessages).'</pre>';
                }
 */

    }

    unset($image);

}

/**
 * @param $string
 *
 * @return string
 */
function unhtmlentities($string)
{
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);

    return strtr($string, $trans_tbl);
}

/**
 * @param $oid
 * @param $gender
 *
 * @return null
 */
function pups($oid, $gender)
{
    global $xoopsDB, $numofcolumns, $nummatch, $pages, $columns, $dogs;
    $content = "";
    if ($gender == 0) {
        $sqlquery = "SELECT d.id as d_id, d.naam as d_naam, d.roft as d_roft, d.* FROM " . $xoopsDB->prefix("pedigree_tree") . " d LEFT JOIN " . $xoopsDB->prefix("pedigree_tree")
            . " f ON d.father = f.id LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " m ON d.mother = m.id where d.father=" . $oid . " order by d.naam";
    } else {
        $sqlquery = "SELECT d.id as d_id, d.naam as d_naam, d.roft as d_roft, d.* FROM " . $xoopsDB->prefix("pedigree_tree") . " d LEFT JOIN " . $xoopsDB->prefix("pedigree_tree")
            . " f ON d.father = f.id LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " m ON d.mother = m.id where d.mother=" . $oid . " order by d.naam";
    }
    $queryresult = $xoopsDB->query($sqlquery);
    $nummatch    = $xoopsDB->getRowsNum($queryresult);

    $animal = new Animal();
    //test to find out how many user fields there are...
    $fields       = $animal->numoffields();
    $numofcolumns = 1;
    $columns[]    = array('columnname' => "Name");
    for ($i = 0; $i < count($fields); ++$i) {
        $userfield   = new Field($fields[$i], $animal->getconfig());
        $fieldType   = $userfield->getSetting("FieldType");
        $fieldobject = new $fieldType($userfield, $animal);
        //create empty string
        $lookupvalues = "";
        if ($userfield->active() && $userfield->inlist()) {
            if ($userfield->haslookup()) {
                $lookupvalues = $userfield->lookup($fields[$i]);
                //debug information
                //print_r($lookupvalues);
            }
            $columns[] = array('columnname' => $fieldobject->fieldname, 'columnnumber' => $userfield->getID(), 'lookupval' => $lookupvalues);
            ++$numofcolumns;
            unset($lookupvalues);
        }
    }

    while ($rowres = $xoopsDB->fetchArray($queryresult)) {
        if ($rowres['d_roft'] == "0") {
            $gender = "<img src=\"assets/images/male.gif\">";
        } else {
            $gender = "<img src=\"assets/images/female.gif\">";
        }
        $name = stripslashes($rowres['d_naam']);;
        //empty array
        unset($columnvalue);
        //fill array
        for ($i = 1; $i < ($numofcolumns); ++$i) {
            $x = $columns[$i]['columnnumber'];
            if (is_array($columns[$i]['lookupval'])) {
                foreach ($columns[$i]['lookupval'] as $key => $keyvalue) {
                    if ($keyvalue['id'] == $rowres['user' . $x]) {
                        $value = $keyvalue['value'];
                    }
                }
                //debug information
                ///echo $columns[$i]['columnname']."is an array !";
            } //format value - cant use object because of query count
            elseif (substr($rowres['user' . $x], 0, 7) == 'http://') {
                $value = "<a href=\"" . $rowres['user' . $x] . "\">" . $rowres['user' . $x] . "</a>";
            } else {
                $value = $rowres['user' . $x];
            }
            $columnvalue[] = array('value' => $value);
        }
        $dogs[] = array(
            'id'          => $rowres['d_id'],
            'name'        => $name,
            'gender'      => $gender,
            'link'        => "<a href=\"dog.php?id=" . $rowres['d_id'] . "\">" . $name . "</a>",
            'colour'      => "",
            'number'      => "",
            'usercolumns' => $columnvalue
        );
    }

    return null;
}

/**
 * @param $oid
 * @param $pa
 * @param $ma
 *
 * @return null
 */
function bas($oid, $pa, $ma)
{
    global $xoopsDB, $numofcolumns1, $nummatch1, $pages1, $columns1, $dogs1;
    if ($pa == "0" && $ma == "0") {
        $sqlquery
            = "SELECT * FROM " . $xoopsDB->prefix("pedigree_tree") . " where father = " . $pa . " and mother = " . $ma . " and ID != " . $oid . " and father != '0' and mother !='0' order by NAAM";
    } else {
        $sqlquery = "SELECT * FROM " . $xoopsDB->prefix("pedigree_tree") . " where father = " . $pa . " and mother = " . $ma . " and ID != " . $oid . " order by NAAM";
    }
    $queryresult = $xoopsDB->query($sqlquery);
    $nummatch1   = $xoopsDB->getRowsNum($queryresult);

    $animal = new Animal();
    //test to find out how many user fields there are...
    $fields        = $animal->numoffields();
    $numofcolumns1 = 1;
    $columns1[]    = array('columnname' => "Name");
    for ($i = 0; $i < count($fields); ++$i) {
        $userfield   = new Field($fields[$i], $animal->getconfig());
        $fieldType   = $userfield->getSetting("FieldType");
        $fieldobject = new $fieldType($userfield, $animal);
        //create empty string
        $lookupvalues = "";
        if ($userfield->active() && $userfield->inlist()) {
            if ($userfield->haslookup()) {
                $lookupvalues = $userfield->lookup($fields[$i]);
                //debug information
                //print_r($lookupvalues);
            }
            $columns1[] = array('columnname' => $fieldobject->fieldname, 'columnnumber' => $userfield->getID(), 'lookupval' => $lookupvalues);
            ++$numofcolumns1;
            unset($lookupvalues);
        }
    }

    while ($rowres = $xoopsDB->fetchArray($queryresult)) {
        if ($rowres['roft'] == "0") {
            $gender = "<img src=\"assets/images/male.gif\">";
        } else {
            $gender = "<img src=\"assets/images/female.gif\">";
        }
        $name = stripslashes($rowres['NAAM']);;
        //empty array
        unset($columnvalue1);
        //fill array
        for ($i = 1; $i < ($numofcolumns1); ++$i) {
            $x = $columns1[$i]['columnnumber'];
            if (is_array($columns1[$i]['lookupval'])) {
                foreach ($columns1[$i]['lookupval'] as $key => $keyvalue) {
                    if ($keyvalue['id'] == $rowres['user' . $x]) {
                        $value = $keyvalue['value'];
                    }
                }
                //debug information
                ///echo $columns[$i]['columnname']."is an array !";
            } //format value - cant use object because of query count
            elseif (substr($rowres['user' . $x], 0, 7) == 'http://') {
                $value = "<a href=\"" . $rowres['user' . $x] . "\">" . $rowres['user' . $x] . "</a>";
            } else {
                $value = $rowres['user' . $x];
            }
            $columnvalue1[] = array('value' => $value);
        }
        $dogs1[] = array(
            'id'          => $rowres['ID'],
            'name'        => $name,
            'gender'      => $gender,
            'link'        => "<a href=\"dog.php?id=" . $rowres['ID'] . "\">" . $name . "</a>",
            'colour'      => "",
            'number'      => "",
            'usercolumns' => $columnvalue1
        );
    }

    return null;
}

/**
 * @param $oid
 * @param $breeder
 *
 * @return string
 */
function breederof($oid, $breeder)
{
    global $xoopsDB;
    $content = "";

    if ($breeder == 0) {
        $sqlquery = "SELECT ID, NAAM, roft from " . $xoopsDB->prefix("pedigree_tree") . " WHERE id_owner = '" . $oid . "' order by NAAM";
    } else {
        $sqlquery = "SELECT ID, NAAM, roft from " . $xoopsDB->prefix("pedigree_tree") . " WHERE id_breeder = '" . $oid . "' order by NAAM";
    }
    $queryresult = $xoopsDB->query($sqlquery);
    while ($rowres = $xoopsDB->fetchArray($queryresult)) {
        if ($rowres['roft'] == "0") {
            $gender = "<img src=\"assets/images/male.gif\">";
        } else {
            $gender = "<img src=\"assets/images/female.gif\">";
        }
        $link = "<a href=\"dog.php?id=" . $rowres['ID'] . "\">" . stripslashes($rowres['NAAM']) . "</a>";
        $content .= $gender . " " . $link . "<br />";
    }

    return $content;
}

/**
 * @param $oid
 *
 * @return string
 */
function getname($oid)
{
    global $xoopsDB;
    $sqlquery    = "SELECT NAAM from " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID = '" . $oid . "'";
    $queryresult = $xoopsDB->query($sqlquery);
    while ($rowres = $xoopsDB->fetchArray($queryresult)) {
        $an = stripslashes($rowres['NAAM']);
    }

    return $an;
}

/**
 * @param $PA
 */
function showparent($PA)
{
    global $xoopsDB;
    $sqlquery    = "SELECT NAAM from " . $xoopsDB->prefix("pedigree_tree") . " where ID='" . $PA . "'";
    $queryresult = $xoopsDB->query($sqlquery);
    while ($rowres = $xoopsDB->fetchArray($queryresult)) {
        $result = $rowres['NAAM'];
    }
    if (isset($result)) {
        return $result;
    } else {
        return;
    }
}

/**
 * @param $naam_hond
 *
 * @return mixed
 */
function findid($naam_hond)
{
    global $xoopsDB;
    $sqlquery    = "SELECT ID from " . $xoopsDB->prefix("pedigree_tree") . " where NAAM= '$naam_hond'";
    $queryresult = $xoopsDB->query($sqlquery);
    while ($rowres = $xoopsDB->fetchArray($queryresult)) {
        $result = $rowres['ID'];
    }

    return $result;
}

/**
 * @param $result
 * @param $prefix
 * @param $link
 * @param $element
 */
function makelist($result, $prefix, $link, $element)
{
    global $xoopsDB, $xoopsTpl;
    $animal = new Animal();
    //test to find out how many user fields there are...
    $fields       = $animal->numoffields();
    $numofcolumns = 1;
    $columns[]    = array('columnname' => "Name");
    for ($i = 0; $i < count($fields); ++$i) {
        $userfield   = new Field($fields[$i], $animal->getconfig());
        $fieldType   = $userfield->getSetting("FieldType");
        $fieldobject = new $fieldType($userfield, $animal);
        if ($userfield->active() && $userfield->inlist()) {
            if ($userfield->haslookup()) {
                $id = $userfield->getid();
                $q  = $userfield->lookup($id);
            } else {
                $q = "";
            }
            $columns[] = array('columnname' => $fieldobject->fieldname, 'columnnumber' => $userfield->getID(), 'lookuparray' => $q);
            ++$numofcolumns;
        }
    }

    //add prelimenairy row to array if passed
    if (is_array($prefix)) {
        $dogs[] = $prefix;
    }

    while ($row = $xoopsDB->fetchArray($result)) {
        //reset $gender
        $gender = "";
        if (!empty($xoopsUser)) {
            if ($row['user'] == $xoopsUser->getVar("uid") || $modadmin == true) {
                $gender = "<a href=\"dog.php?id=" . $row['ID'] . "\"><img src=\"images/edit.gif\" alt=" . _MA_PEDIGREE_BTN_EDIT . "></a><a href=\"delete.php?id=" . $row['ID']
                    . "\"><img src=\"images/delete.gif\" alt=" . _MA_PEDIGREE_BTN_DELE . "></a>";
            } else {
                $gender = "";
            }
        }
        if ($row['roft'] == 0) {
            $gender .= "<img src=\"assets/images/male.gif\">";
        } else {
            $gender .= "<img src=\"assets/images/female.gif\">";
        }
        if ($row['foto'] != '') {
            $camera = " <img src=\"assets/images/camera.png\">";
        } else {
            $camera = "";
        }
        $name = stripslashes($row['NAAM']) . $camera;
        //empty array
        unset($columnvalue);
        //fill array
        for ($i = 1; $i < ($numofcolumns); ++$i) {
            $x           = $columns[$i]['columnnumber'];
            $lookuparray = $columns[$i]['lookuparray'];
            if (is_array($lookuparray)) {
                for ($index = 0; $index < count($lookuparray); ++$index) {
                    if ($lookuparray[$index]['id'] == $row['user' . $x]) {
                        //echo "<h1>".$lookuparray[$index]['id']."</h1>";
                        $value = $lookuparray[$index]['value'];
                    }
                }
            } //format value - cant use object because of query count
            elseif (substr($row['user' . $x], 0, 7) == 'http://') {
                $value = "<a href=\"" . $row['user' . $x] . "\">" . $row['user' . $x] . "</a>";
            } else {
                $value = $row['user' . $x];
            }
            $columnvalue[] = array('value' => $value);
            unset($value);
        }

        $linkto = "<a href=\"" . $link . $row[$element] . "\">" . $name . "</a>";
        //create array
        $dogs[] = array('id' => $row['ID'], 'name' => $name, 'gender' => $gender, 'link' => $linkto, 'colour' => "", 'number' => "", 'usercolumns' => $columnvalue);
    }

    //add data to smarty template
    //assign dog
    $xoopsTpl->assign("dogs", $dogs);
    $xoopsTpl->assign("columns", $columns);
    $xoopsTpl->assign("numofcolumns", $numofcolumns);
    $xoopsTpl->assign("tsarray", sorttable($numofcolumns));

}

/***************Blocks**************
 *
 * @param $cats
 *
 * @return string
 */
function animal_block_addCatSelect($cats)
{
    if (is_array($cats)) {
        $cat_sql = "(" . current($cats);
        array_shift($cats);
        foreach ($cats as $cat) {
            $cat_sql .= "," . $cat;
        }
        $cat_sql .= ")";
    }

    return $cat_sql;
}

/**
 * @param        $global
 * @param        $key
 * @param string $default
 * @param string $type
 *
 * @return mixed|string
 */
function animal_CleanVars(&$global, $key, $default = '', $type = 'int')
{
    switch ($type) {
        case 'string':
            $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_MAGIC_QUOTES) : $default;
            break;
        case 'int':
        default:
            $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_NUMBER_INT) : $default;
            break;
    }
    if ($ret === false) {
        return $default;
    }

    return $ret;
}

/**
 * @param $content
 */
function animal_meta_keywords($content)
{
    global $xoopsTpl, $xoTheme;
    $myts    =& MyTextSanitizer::getInstance();
    $content = $myts->undoHtmlSpecialChars($myts->sanitizeForDisplay($content));
    if (isset($xoTheme) && is_object($xoTheme)) {
        $xoTheme->addMeta('meta', 'keywords', strip_tags($content));
    } else {    // Compatibility for old Xoops versions
        $xoopsTpl->assign('xoops_meta_keywords', strip_tags($content));
    }
}

/**
 * @param $content
 */
function animal_meta_description($content)
{
    global $xoopsTpl, $xoTheme;
    $myts    =& MyTextSanitizer::getInstance();
    $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
    if (isset($xoTheme) && is_object($xoTheme)) {
        $xoTheme->addMeta('meta', 'description', strip_tags($content));
    } else {    // Compatibility for old Xoops versions
        $xoopsTpl->assign('xoops_meta_description', strip_tags($content));
    }
}

/**
 * Verify that a mysql table exists
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 */
//function tableExists($tablename)
//{
//	global $xoopsDB;
//	$result=$xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");
//	return($xoopsDB->getRowsNum($result) > 0);
//}

/**
 * Create download by letter choice bar/menu
 * updated starting from this idea http://xoops.org/modules/news/article.php?storyid=6497
 *
 * @return  string   html
 *
 * @access  public
 * @author  luciorota
 */
function pedigree_lettersChoice()
{
    $pedigree = PedigreePedigree::getInstance();

    $criteria = $pedigree->getHandler('download')->getActiveCriteria();
    $criteria->setGroupby('UPPER(LEFT(title,1))');
    $countsByLetters = $pedigree->getHandler('download')->getCounts($criteria);
    // Fill alphabet array
    $alphabet       = pedigree_alphabet();
    $alphabet_array = array();
    foreach ($alphabet as $letter) {
        $letter_array = array();
        if (isset($countsByLetters[$letter])) {
            $letter_array['letter'] = $letter;
            $letter_array['count']  = $countsByLetters[$letter];
            $letter_array['url']    = "" . XOOPS_URL . "/modules/" . $pedigree->getModule()->dirname() . "/viewcat.php?list={$letter}";
        } else {
            $letter_array['letter'] = $letter;
            $letter_array['count']  = 0;
            $letter_array['url']    = "";
        }
        $alphabet_array[$letter] = $letter_array;
        unset($letter_array);
    }
    // Render output
    if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
        include_once $GLOBALS['xoops']->path("/class/theme.php");
        $GLOBALS['xoTheme'] = new xos_opal_Theme();
    }
    require_once $GLOBALS['xoops']->path('class/template.php');
    $letterschoiceTpl          = new XoopsTpl();
    $letterschoiceTpl->caching = false; // Disable cache
    $letterschoiceTpl->assign('alphabet', $alphabet_array);
    $html = $letterschoiceTpl->fetch("db:" . $pedigree->getModule()->dirname() . "_common_letterschoice.tpl");
    unset($letterschoiceTpl);

    return $html;
}

/**
 * @return bool
 */
function pedigree_userIsAdmin()
{
    global $xoopsUser;
    $pedigree = PedigreePedigree::getInstance();

    static $pedigree_isAdmin;

    if (isset($pedigree_isAdmin)) {
        return $pedigree_isAdmin;
    }

    if (!$xoopsUser) {
        $pedigree_isAdmin = false;
    } else {
        $pedigree_isAdmin = $xoopsUser->isAdmin($pedigree->getModule()->getVar('mid'));
    }

    return $pedigree_isAdmin;
}

function pedigree_xoops_cp_header()
{
    xoops_cp_header();
}

/**
 * @param bool $withLink
 *
 * @return string
 */
function pedigree_module_home($withLink = true)
{
    $pedigree = PedigreePedigree::getInstance();

    $pedigreeModuleName = $pedigree->getModule()->getVar('name');
    if (!$withLink) {
        return $pedigreeModuleName;
    } else {
        return '<a href="' . PEDIGREE_URL . '/">{$pedigreeModuleName}</a>';
    }
}

/**
 * Detemines if a table exists in the current db
 *
 * @param string $table the table name (without XOOPS prefix)
 *
 * @return bool True if table exists, false if not
 *
 * @access public
 * @author xhelp development team
 */
function pedigree_tableExists($table)
{
    $bRetVal = false;
    //Verifies that a MySQL table exists
    $xoopsDB  =& XoopsDatabaseFactory::getDatabaseConnection();
    $realName = $xoopsDB->prefix($table);

    $sql = "SHOW TABLES FROM " . XOOPS_DB_NAME;
    $ret = $xoopsDB->queryF($sql);

    while (list($m_table) = $xoopsDB->fetchRow($ret)) {
        if ($m_table == $realName) {
            $bRetVal = true;
            break;
        }
    }
    $xoopsDB->freeRecordSet($ret);

    return ($bRetVal);
}

/**
 * Gets a value from a key in the xhelp_meta table
 *
 * @param string $key
 *
 * @return string $value
 *
 * @access public
 * @author xhelp development team
 */
function pedigree_getMeta($key)
{
    $xoopsDB =& XoopsDatabaseFactory::getDatabaseConnection();
    $sql     = sprintf("SELECT metavalue FROM %s WHERE metakey=%s", $xoopsDB->prefix('pedigree_meta'), $xoopsDB->quoteString($key));
    $ret     = $xoopsDB->query($sql);
    if (!$ret) {
        $value = false;
    } else {
        list($value) = $xoopsDB->fetchRow($ret);

    }

    return $value;
}

/**
 * Sets a value for a key in the xhelp_meta table
 *
 * @param string $key
 * @param string $value
 *
 * @return bool true if success, false if failure
 *
 * @access public
 * @author xhelp development team
 */
function pedigree_setMeta($key, $value)
{
    $xoopsDB =& XoopsDatabaseFactory::getDatabaseConnection();
    if ($ret = pedigree_getMeta($key)) {
        $sql = sprintf(
            "UPDATE %s SET metavalue = %s WHERE metakey = %s",
            $xoopsDB->prefix('pedigree_meta'),
            $xoopsDB->quoteString($value),
            $xoopsDB->quoteString($key)
        );
    } else {
        $sql = sprintf(
            "INSERT INTO %s (metakey, metavalue) VALUES (%s, %s)",
            $xoopsDB->prefix('pedigree_meta'),
            $xoopsDB->quoteString($key),
            $xoopsDB->quoteString($value)
        );
    }
    $ret = $xoopsDB->queryF($sql);
    if (!$ret) {
        return false;
    }

    return true;
}

/**
 * @param     $name
 * @param     $value
 * @param int $time
 */
function pedigree_setCookieVar($name, $value, $time = 0)
{
    if ($time == 0) {
        $time = time() + 3600 * 24 * 365;
        //$time = '';
    }
    setcookie($name, $value, $time, '/');
}

/**
 * @param        $name
 * @param string $default
 *
 * @return string
 */
function pedigree_getCookieVar($name, $default = '')
{
    if ((isset($_COOKIE[$name])) && ($_COOKIE[$name] > '')) {
        return $_COOKIE[$name];
    } else {
        return $default;
    }
}

/**
 * @return array
 */
function pedigree_getCurrentUrls()
{
    $http        = ((strpos(XOOPS_URL, "https://")) === false) ? ("http://") : ("https://");
    $phpSelf     = $_SERVER['PHP_SELF'];
    $httpHost    = $_SERVER['HTTP_HOST'];
    $queryString = $_SERVER['QUERY_STRING'];

    If ($queryString != '') {
        $queryString = '?' . $queryString;
    }

    $currentURL = $http . $httpHost . $phpSelf . $queryString;

    $urls                = array();
    $urls['http']        = $http;
    $urls['httphost']    = $httpHost;
    $urls['phpself']     = $phpSelf;
    $urls['querystring'] = $queryString;
    $urls['full']        = $currentURL;

    return $urls;
}

function pedigree_getCurrentPage()
{
    $urls = pedigree_getCurrentUrls();

    return $urls['full'];
}

/**
 * @param array $errors
 *
 * @return string
 */
function pedigree_formatErrors($errors = array())
{
    $ret = '';
    foreach ($errors as $key => $value) {
        $ret .= "<br /> - {$value}";
    }

    return $ret;
}
