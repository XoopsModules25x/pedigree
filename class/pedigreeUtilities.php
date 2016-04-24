<?php

/**
 * Created by PhpStorm.
 * User: Mamba
 * Date: 2014-11-19
 * Time: 3:05
 */

$moduleDirName = basename(dirname(__DIR__));
require_once $GLOBALS['xoops']->path('modules/' . $GLOBALS['xoopsModule']->dirname() . '/include/class_field.php');
require_once $GLOBALS['xoops']->path('modules/' . $GLOBALS['xoopsModule']->dirname() . '/include/config.php');
xoops_load('PedigreeAnimal', $moduleDirName);

//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

/**
 * Class PedigreeUtilities
 */
class PedigreeUtilities
{

    /**
     * Function responsible for checking if a directory exists, we can also write in and create an index.html file
     *
     * @param string $folder The full path of the directory to check
     *
     * @return void
     */
    public static function prepareFolder($folder)
    {
        //        $filteredFolder = XoopsFilterInput::clean($folder, 'PATH');
        if (!is_dir($folder)) {
            mkdir($folder);
            file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
        }
        //        chmod($filteredFolder, 0777);
    }

    /**
     * @param $columncount
     *
     * @return string
     */
    public static function sortTable($columncount)
    {
        $ttemp = '';
        if ($columncount > 1) {
            for ($t = 1; $t < $columncount; ++$t) {
                $ttemp .= "'S',";
            }
            $tsarray = "initSortTable('Result', Array({$ttemp}'S'));";
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
    public static function uploadPicture($num)
    {
        $max_imgsize       = $GLOBALS['xoopsModuleConfig']['maxfilesize']; //1024000;
        $max_imgwidth      = $GLOBALS['xoopsModuleConfig']['maximgwidth']; //1500;
        $max_imgheight     = $GLOBALS['xoopsModuleConfig']['maximgheight']; //1000;
        $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
        //    $img_dir = XOOPS_ROOT_PATH . "/modules/" . $GLOBALS['xoopsModule']->dirname() . "/images" ;
        $img_dir = $GLOBALS['xoopsModuleConfig']['uploaddir'] . '/images';
        include_once $GLOBALS['xoops']->path('class/uploader.php');
        $field = $_POST['xoops_upload_file'][$num];
        if (!empty($field) || $field != '') {
            $uploader = new XoopsMediaUploader($img_dir, $allowed_mimetypes, $max_imgsize, $max_imgwidth, $max_imgheight);
            $uploader->setPrefix('img');
            if ($uploader->fetchMedia($field) && $uploader->upload()) {
                $photo = $uploader->getSavedFileName();
            } else {
                echo $uploader->getErrors();
            }
            createThumbs($photo);

            return $photo;
        }
    }

    /**
     * @param $filename
     *
     * @return bool
     */
    public static function createThumbs($filename)
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
        require_once $GLOBALS['xoops']->path('modules/' . $GLOBALS['xoopsModule']->dirname() . '/library/Zebra_Image.php');
        $thumbnail_widths = array(150, 400);

        // indicate a target image
        // note that there's no extra property to set in order to specify the target
        // image's type -simply by writing '.jpg' as extension will instruct the script
        // to create a 'jpg' file
        $config_output_format = 'jpeg';

        // create a new instance of the class
        $image = new Zebra_Image();
        // indicate a source image (a GIF, PNG or JPEG file)
        $image->source_path = PEDIGREE_UPLOAD_PATH . "/images/{$filename}";

        foreach ($thumbnail_widths as $thumbnail_width) {

            // generate & output thumbnail
            $output_filename    = PEDIGREE_UPLOAD_PATH . '/images/thumbnails/' . basename($filename) . "_{$thumbnail_width}.{$config_output_format}";
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
    public static function unHtmlEntities($string)
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
    public static function pups($oid, $gender)
    {
        global $numofcolumns, $nummatch, $pages, $columns, $dogs;
        $content = '';
        if ($gender == 0) {
            $sqlquery = 'SELECT d.id as d_id, d.naam as d_naam, d.roft as d_roft, d.* FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' f ON d.father = f.id LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON d.mother = m.id where d.father=' . $oid . ' order by d.naam';
        } else {
            $sqlquery = 'SELECT d.id as d_id, d.naam as d_naam, d.roft as d_roft, d.* FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' f ON d.father = f.id LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON d.mother = m.id where d.mother=' . $oid . ' order by d.naam';
        }
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        $nummatch    = $GLOBALS['xoopsDB']->getRowsNum($queryresult);

        $animal = new PedigreeAnimal();
        //test to find out how many user fields there are...
        $fields       = $animal->getNumOfFields();
        $numofcolumns = 1;
        $columns[]    = array('columnname' => 'Name');
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            //create empty string
            $lookupvalues = '';
            if ($userField->isActive() && $userField->inList()) {
                if ($userField->hasLookup()) {
                    $lookupvalues = $userField->lookupField($fields[$i]);
                    //debug information
                    //print_r($lookupvalues);
                }
                $columns[] = array('columnname' => $fieldObject->fieldname, 'columnnumber' => $userField->getId(), 'lookupval' => $lookupvalues);
                ++$numofcolumns;
                unset($lookupvalues);
            }
        }

        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
            if ($rowres['d_roft'] == '0') {
                $gender = "<img src=\"assets/images/male.gif\">";
            } else {
                $gender = "<img src=\"assets/images/female.gif\">";
            }
            $name = stripslashes($rowres['d_naam']);
            //empty array
            unset($columnvalue);
            //fill array
            for ($i = 1; $i < $numofcolumns; ++$i) {
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
                elseif (0 === strpos($rowres['user' . $x], 'http://')) {
                    $value = "<a href=\"" . $rowres['user' . $x] . "\">" . $rowres['user' . $x] . '</a>';
                } else {
                    $value = $rowres['user' . $x];
                }
                $columnvalue[] = array('value' => $value);
            }
            $dogs[] = array(
                'id'          => $rowres['d_id'],
                'name'        => $name,
                'gender'      => $gender,
                'link'        => "<a href=\"dog.php?id=" . $rowres['d_id'] . "\">" . $name . '</a>',
                'colour'      => '',
                'number'      => '',
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
    public static function bas($oid, $pa, $ma)
    {
        global $numofcolumns1, $nummatch1, $pages1, $columns1, $dogs1;
        if ($pa == '0' && $ma == '0') {
            $sqlquery = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' where father = ' . $pa . ' and mother = ' . $ma . ' and ID != ' . $oid . " and father != '0' and mother !='0' order by NAAM";
        } else {
            $sqlquery = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' where father = ' . $pa . ' and mother = ' . $ma . ' and ID != ' . $oid . ' order by NAAM';
        }
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        $nummatch1   = $GLOBALS['xoopsDB']->getRowsNum($queryresult);

        $animal = new PedigreeAnimal();
        //test to find out how many user fields there are...
        $fields        = $animal->getNumOfFields();
        $numofcolumns1 = 1;
        $columns1[]    = array('columnname' => 'Name');
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            //create empty string
            $lookupvalues = '';
            if ($userField->isActive() && $userField->inList()) {
                if ($userField->hasLookup()) {
                    $lookupvalues = $userField->lookupField($fields[$i]);
                    //debug information
                    //print_r($lookupvalues);
                }
                $columns1[] = array('columnname' => $fieldObject->fieldname, 'columnnumber' => $userField->getId(), 'lookupval' => $lookupvalues);
                ++$numofcolumns1;
                unset($lookupvalues);
            }
        }

        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
            if (0 == $rowres['roft']) {
                $gender = "<img src='assets/images/male.gif'>";
            } else {
                $gender = "<img src='assets/images/female.gif'>";
            }
            $name = stripslashes($rowres['NAAM']);
            //empty array
            //        unset($columnvalue1);
            $columnvalue1 = array();
            //fill array
            for ($i = 1; $i < $numofcolumns1; ++$i) {
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
                elseif (0 === strpos($rowres['user' . $x], 'http://')) {
                    $value = "<a href=\"" . $rowres['user' . $x] . "\">" . $rowres['user' . $x] . '</a>';
                } else {
                    $value = $rowres['user' . $x];
                }
                $columnvalue1[] = array('value' => $value);
            }
            $dogs1[] = array(
                'id'          => $rowres['Id'],
                'name'        => $name,
                'gender'      => $gender,
                'link'        => "<a href=\"dog.php?id=" . $rowres['Id'] . "\">" . $name . '</a>',
                'colour'      => '',
                'number'      => '',
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
    public static function breederof($oid, $breeder)
    {
        $content = '';

        if (0 == $breeder) {
            $sqlquery = 'SELECT ID, NAAM, roft from ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE id_owner = '" . $oid . "' order by NAAM";
        } else {
            $sqlquery = 'SELECT ID, NAAM, roft from ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE id_breeder = '" . $oid . "' order by NAAM";
        }
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
            if ($rowres['roft'] == '0') {
                $gender = "<img src=\"assets/images/male.gif\">";
            } else {
                $gender = "<img src=\"assets/images/female.gif\">";
            }
            $link = "<a href=\"dog.php?id=" . $rowres['Id'] . "\">" . stripslashes($rowres['NAAM']) . '</a>';
            $content .= $gender . ' ' . $link . '<br />';
        }

        return $content;
    }

    /**
     * @param $oid
     *
     * @return string
     */
    public static function getName($oid)
    {
        $oid         = (int)$oid;
        $sqlquery    = 'SELECT NAAM FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE ID = '{$oid}'";
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
            $an = stripslashes($rowres['NAAM']);
        }

        return $an;
    }

    /**
     * @param $PA
     */
    public static function showParent($PA)
    {
        $sqlquery    = 'SELECT NAAM from ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " where ID='" . $PA . "'";
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
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
    public static function findId($naam_hond)
    {
        $sqlquery    = 'SELECT ID from ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " where NAAM= '$naam_hond'";
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
            $result = $rowres['Id'];
        }

        return $result;
    }

    /**
     * @param $result
     * @param $prefix
     * @param $link
     * @param $element
     */
    public static function createList($result, $prefix, $link, $element)
    {
        global $xoopsTpl;
        $animal = new PedigreeAnimal();
        //test to find out how many user fields there are...
        $fields       = $animal->getNumOfFields();
        $numofcolumns = 1;
        $columns[]    = array('columnname' => 'Name');
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            if ($userField->isActive() && $userField->inList()) {
                if ($userField->hasLookup()) {
                    $id = $userField->getId();
                    $q  = $userField->lookupField($id);
                } else {
                    $q = '';
                }
                $columns[] = array('columnname' => $fieldObject->fieldname, 'columnnumber' => $userField->getId(), 'lookuparray' => $q);
                ++$numofcolumns;
            }
        }

        //add preliminary row to array if passed
        if (is_array($prefix)) {
            $dogs[] = $prefix;
        }

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //reset $gender
            $gender = '';
            if ((!empty($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser'] instanceof XoopsUser) && ($row['user'] == $GLOBALS['xoopsUser']->getVar('uid') || true === $modadmin)) {
                $gender = "<a href='dog.php?id={$row['Id']}'><img src='images/edit.gif' alt='" . _EDIT . "'></a>
                     . <a href='delete.php?id={$row['Id']}'><img src='images/delete.gif' alt='" . _DELETE . "'></a>";
            }

            $genImg = (0 == $row['roft']) ? 'male.gif' : 'female.gif';
            $gender .= "<img src='assets/images/{$genImg}'>";

            if ('' != $row['foto']) {
                $camera = " <img src=\"assets/images/dog-icon25.png\">";
            } else {
                $camera = '';
            }
            $name = stripslashes($row['NAAM']) . $camera;
            unset($columnvalue);

            //fill array
            for ($i = 1; $i < $numofcolumns; ++$i) {
                $x           = $columns[$i]['columnnumber'];
                $lookuparray = $columns[$i]['lookuparray'];
                if (is_array($lookuparray)) {
                    for ($index = 0, $indexMax = count($lookuparray); $index < $indexMax; ++$index) {
                        if ($lookuparray[$index]['id'] == $row['user' . $x]) {
                            //echo "<h1>".$lookuparray[$index]['id']."</h1>";
                            $value = $lookuparray[$index]['value'];
                        }
                    }
                } //format value - cant use object because of query count
                elseif (0 === strpos($row['user' . $x], 'http://')) {
                    $value = "<a href=\"" . $row['user' . $x] . "\">" . $row['user' . $x] . '</a>';
                } else {
                    $value = $row['user' . $x];
                }
                $columnvalue[] = array('value' => $value);
                unset($value);
            }

            $linkto = "<a href=\"" . $link . $row[$element] . "\">" . $name . '</a>';
            //create array
            $dogs[] = array('id' => $row['Id'], 'name' => $name, 'gender' => $gender, 'link' => $linkto, 'colour' => '', 'number' => '', 'usercolumns' => $columnvalue);
        }

        //add data to smarty template
        //assign dog
        $xoopsTpl->assign('dogs', $dogs);
        $xoopsTpl->assign('columns', $columns);
        $xoopsTpl->assign('numofcolumns', $numofcolumns);
        $xoopsTpl->assign('tsarray', PedigreeUtilities::sortTable($numofcolumns));
    }

    /***************Blocks**************
     *
     * @param $cats
     *
     * @return string
     */
    public static function animal_block_addCatSelect($cats)
    {
        if (is_array($cats)) {
            $cat_sql = '(' . current($cats);
            array_shift($cats);
            foreach ($cats as $cat) {
                $cat_sql .= ',' . $cat;
            }
            $cat_sql .= ')';
        }

        return $cat_sql;
    }

    /**
     * @deprecated
     * @param        $global
     * @param        $key
     * @param string $default
     * @param string $type
     *
     * @return mixed|string
     */
    public static function animal_CleanVars(&$global, $key, $default = '', $type = 'int')
    {
        switch ($type) {
            case 'string':
                $ret = isset($global[$key]) ? filter_var($global[$key], FILTER_SANITIZE_MAGIC_QUOTES) : $default;
                break;
            case 'int':
            default:
                $ret = isset($global[$key]) ? filter_var($global[$key], FILTER_SANITIZE_NUMBER_INT) : $default;
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
    public static function animal_meta_keywords($content)
    {
        global $xoopsTpl, $xoTheme;
        $myts    = MyTextSanitizer::getInstance();
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
    public static function animal_meta_description($content)
    {
        global $xoopsTpl, $xoTheme;
        $myts    = MyTextSanitizer::getInstance();
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
     * @package       pedigree
     * @author        Hervé Thouzard (http://www.herve-thouzard.com)
     * @copyright (c) Hervé Thouzard
     */
    //function tableExists($tablename)
    //{
    //
    //  $result=$GLOBALS['xoopsDB']->queryF("SHOW TABLES LIKE '$tablename'");
    //  return($GLOBALS['xoopsDB']->getRowsNum($result) > 0);
    //}

    /**
     * Create download by letter choice bar/menu
     * updated starting from this idea http://xoops.org/modules/news/article.php?storyid=6497
     *
     * @param PedigreePedigree $myObject
     * @param $activeObject
     * @param $criteria
     * @param $name
     * @param $file
     * @param $file2
     * @return string html
     *
     * @access  public
     * @author  luciorota
     */
    public static function lettersChoice(PedigreePedigree $myObject, $activeObject, $criteria, $name, $file, $file2)
    {
        /*
        $pedigree = PedigreePedigree::getInstance();
        xoops_load('XoopsLocal');

        $criteria = $pedigree->getHandler('tree')->getActiveCriteria();
        $criteria->setGroupby('UPPER(LEFT(NAAM,1))');
        $countsByLetters = $pedigree->getHandler('tree')->getCounts($criteria);
        // Fill alphabet array
        $alphabet       = XoopsLocal::getAlphabet();
        $alphabet_array = array();
        foreach ($alphabet as $letter) {
            $letter_array = array();
            if (isset($countsByLetters[$letter])) {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = $countsByLetters[$letter];
                //            $letter_array['url']    = "" . XOOPS_URL . "/modules/" . $pedigree->getModule()->dirname() . "/viewcat.php?list={$letter}";
                $letter_array['url'] = '' . XOOPS_URL . '/modules/' . $pedigree->getModule()->dirname() . "/result.php?f=NAAM&amp;l=1&amp;w={$letter}%25&amp;o=NAAM";
            } else {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = 0;
                $letter_array['url']    = '';
            }
            $alphabet_array[$letter] = $letter_array;
            unset($letter_array);
        }
        // Render output
        if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
            include_once $GLOBALS['xoops']->path('class/theme.php');
            $GLOBALS['xoTheme'] = new xos_opal_Theme();
        }
        require_once $GLOBALS['xoops']->path('class/template.php');
        $letterschoiceTpl          = new XoopsTpl();
        $letterschoiceTpl->caching = false; // Disable cache
        $letterschoiceTpl->assign('alphabet', $alphabet_array);
        $html = $letterschoiceTpl->fetch('db:' . $pedigree->getModule()->dirname() . '_common_letterschoice.tpl');
        unset($letterschoiceTpl);
        return $html;
*/

        //        $pedigree = PedigreePedigree::getInstance();
        xoops_load('XoopsLocal');

        $criteria = $myObject->getHandler($activeObject)->getActiveCriteria();
        $criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');
        $countsByLetters = $myObject->getHandler($activeObject)->getCounts($criteria);
        // Fill alphabet array
        $alphabet       = XoopsLocal::getAlphabet();
        $alphabet_array = array();
        foreach ($alphabet as $letter) {
            $letter_array = array();
            if (isset($countsByLetters[$letter])) {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = $countsByLetters[$letter];
                //            $letter_array['url']    = "" . XOOPS_URL . "/modules/" . $pedigree->getModule()->dirname() . "/viewcat.php?list={$letter}";
                //                $letter_array['url'] = '' . XOOPS_URL . '/modules/' . $myObject->getModule()->dirname() . '/'.$file.'?f='.$name."&amp;l=1&amp;w={$letter}%25&amp;o=".$name;
                $letter_array['url'] = '' . XOOPS_URL . '/modules/' . $myObject->getModule()->dirname() . '/' . $file2;
            } else {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = 0;
                $letter_array['url']    = '';
            }
            $alphabet_array[$letter] = $letter_array;
            unset($letter_array);
        }
        // Render output
        if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
            include_once $GLOBALS['xoops']->path('class/theme.php');
            $GLOBALS['xoTheme'] = new xos_opal_Theme();
        }
        require_once $GLOBALS['xoops']->path('class/template.php');
        $letterschoiceTpl          = new XoopsTpl();
        $letterschoiceTpl->caching = false; // Disable cache
        $letterschoiceTpl->assign('alphabet', $alphabet_array);
        $html = $letterschoiceTpl->fetch('db:' . $myObject->getModule()->dirname() . '_common_letterschoice.tpl');
        unset($letterschoiceTpl);
        return $html;
    }

    /**
     * @return bool
     */
    public static function userIsAdmin()
    {
        $pedigree = PedigreePedigree::getInstance();

        static $pedigree_isAdmin;

        if (isset($pedigree_isAdmin)) {
            return $pedigree_isAdmin;
        }

        if (!$GLOBALS['xoopsUser']) {
            $pedigree_isAdmin = false;
        } else {
            $pedigree_isAdmin = $GLOBALS['xoopsUser']->isAdmin($pedigree->getModule()->getVar('mid'));
        }

        return $pedigree_isAdmin;
    }

    public static function getXoopsCpHeader()
    {
        xoops_cp_header();
    }

    /**
     * @param bool $withLink
     *
     * @return string
     */
    public static function getModuleName($withLink = true)
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
    public static function hasTable($table)
    {
        $bRetVal = false;
        //Verifies that a MySQL table exists
        $GLOBALS['xoopsDB'] = XoopsDatabaseFactory::getDatabaseConnection();
        $realName           = $GLOBALS['xoopsDB']->prefix($table);

        $sql = 'SHOW TABLES FROM ' . XOOPS_DB_NAME;
        $ret = $GLOBALS['xoopsDB']->queryF($sql);

        while (false !== (list($m_table) = $GLOBALS['xoopsDB']->fetchRow($ret))) {
            if ($m_table == $realName) {
                $bRetVal = true;
                break;
            }
        }
        $GLOBALS['xoopsDB']->freeRecordSet($ret);

        return $bRetVal;
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
    public static function getMeta($key)
    {
        $GLOBALS['xoopsDB'] = XoopsDatabaseFactory::getDatabaseConnection();
        $sql                = sprintf('SELECT metavalue FROM %s WHERE metakey=%s', $GLOBALS['xoopsDB']->prefix('pedigree_meta'), $GLOBALS['xoopsDB']->quoteString($key));
        $ret                = $GLOBALS['xoopsDB']->query($sql);
        if (!$ret) {
            $value = false;
        } else {
            list($value) = $GLOBALS['xoopsDB']->fetchRow($ret);
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
    public static function setMeta($key, $value)
    {
        $GLOBALS['xoopsDB'] = XoopsDatabaseFactory::getDatabaseConnection();
        if (false !== ($ret = PedigreeUtilities::getMeta($key))) {
            $sql = sprintf('UPDATE %s SET metavalue = %s WHERE metakey = %s', $GLOBALS['xoopsDB']->prefix('pedigree_meta'), $GLOBALS['xoopsDB']->quoteString($value), $GLOBALS['xoopsDB']->quoteString($key));
        } else {
            $sql = sprintf('INSERT INTO %s (metakey, metavalue) VALUES (%s, %s)', $GLOBALS['xoopsDB']->prefix('pedigree_meta'), $GLOBALS['xoopsDB']->quoteString($key), $GLOBALS['xoopsDB']->quoteString($value));
        }
        $ret = $GLOBALS['xoopsDB']->queryF($sql);
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
    public static function setCookieVar($name, $value, $time = 0)
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
    public static function getCookieVar($name, $default = '')
    {
        if (isset($_COOKIE[$name]) && ($_COOKIE[$name] > '')) {
            return $_COOKIE[$name];
        } else {
            return $default;
        }
    }

    /**
     * @return array
     */
    public static function getCurrentUrls()
    {
        $http        = (strpos(XOOPS_URL, 'https://') === false) ? 'http://' : 'https://';
        $phpSelf     = $_SERVER['PHP_SELF'];
        $httpHost    = $_SERVER['HTTP_HOST'];
        $queryString = $_SERVER['QUERY_STRING'];

        if ($queryString != '') {
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

    /**
     * @return mixed
     */
    public static function getCurrentPage()
    {
        $urls = PedigreeUtilities::getCurrentUrls();

        return $urls['full'];
    }

    /**
     * @param array $errors
     *
     * @return string
     */
    public static function formatErrors($errors = array())
    {
        $ret = '';
        foreach ($errors as $key => $value) {
            $ret .= "<br /> - {$value}";
        }

        return $ret;
    }

}
