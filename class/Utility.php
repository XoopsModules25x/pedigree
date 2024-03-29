<?php

namespace XoopsModules\Pedigree;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @package      XoopsModules\Pedigree
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author       XOOPS Module Dev Team
 */

use XoopsModules;



/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks;    //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats;    // getServerStats Trait

    use Common\FilesManagement;    // Files Management Trait

    //--------------- Custom module methods -----------------------------

    /**
     * @param string $folder The full path of the directory to check
     * @deprecated - NOT USED : use Pedigree\Common\FilesManagement methods instead
     * Function responsible for checking if a directory exists, we can also write in and create an index.php file
     *
     */
    public static function prepareFolder($folder)
    {
        //        $filteredFolder = XoopsFilterInput::clean($folder, 'PATH');
        if (!\is_dir($folder)) {
            if (!\mkdir($folder) && !\is_dir($folder)) {
                throw new \RuntimeException(\sprintf('Directory "%s" was not created', $folder));
            }
            file_put_contents($folder . '/index.php', "<?php\n\nheader('HTTP/1.0 404 Not Found');\n");
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
     * @param string $haystack
     * @param string $needle
     * @param int    $offset
     *
     * @return bool|int
     */
    public static function myStrRpos($haystack, $needle, $offset = 0)
    {
        // same as strrpos, except $needle can be a string
        $strrpos = false;
        if (\is_string($haystack) && \is_string($needle) && \is_numeric($offset)) {
            $strlen = \mb_strlen($haystack);
            $strpos = \mb_strpos(\strrev(\mb_substr($haystack, $offset)), \strrev($needle));
            if (\is_numeric($strpos)) {
                $strrpos = $strlen - $strpos - \mb_strlen($needle);
            }
        }

        return $strrpos;
    }

    /**
     * @param int $num
     *
     * @return string
     */
    public static function uploadPicture($num)
    {
        require_once $GLOBALS['xoops']->path('class/uploader.php');

        $num = (int)$num;

        /** @var XoopsModules\Pedigree\Helper $helper */
        $helper           = XoopsModules\Pedigree\Helper::getInstance();
        $maxImgSize       = $helper->getConfig('maxfilesize');
        $maxImgWidth      = $helper->getConfig('maximgwidth');
        $maxImgHeight     = $helper->getConfig('maximgheight');
        $allowedMimetypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
        $imgDir           = $helper->getConfig('uploaddir') . '/images';

        $field = $_POST['xoops_upload_file'][$num];
        if (!empty($field)) {
            $uploader = new \XoopsMediaUploader($imgDir, $allowedMimetypes, $maxImgSize, $maxImgWidth, $maxImgHeight);
            $uploader->setPrefix('img');
            if ($uploader->fetchMedia($field) && $uploader->upload()) {
                $photo = $uploader->getSavedFileName();
            } else {
                echo $uploader->getErrors();
            }
            static::createThumbs($photo);

            return $photo;
        }

        $max_imgsize       = $GLOBALS['xoopsModuleConfig']['maxfilesize']; //1024000;
        $max_imgwidth      = $GLOBALS['xoopsModuleConfig']['maximgwidth']; //1500;
        $max_imgheight     = $GLOBALS['xoopsModuleConfig']['maximgheight']; //1000;
        $allowed_mimetypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
        //    $img_dir = XOOPS_ROOT_PATH . "/modules/" . $GLOBALS['xoopsModule']->dirname() . "/images" ;
        $img_dir = $GLOBALS['xoopsModuleConfig']['uploaddir'] . '/images';
        require_once $GLOBALS['xoops']->path('class/uploader.php');
        $field = $_POST['xoops_upload_file'][$num];
        if (!empty($field) || '' != $field) {
            $uploader = new \XoopsMediaUploader($img_dir, $allowed_mimetypes, $max_imgsize, $max_imgwidth, $max_imgheight);
            $uploader->setPrefix('img');
            if ($uploader->fetchMedia($field) && $uploader->upload()) {
                $photo = $uploader->getSavedFileName();
            } else {
                echo $uploader->getErrors();
            }
            static::createThumbs($photo);

            return $photo;
        }

        return '';
    }

    /**
     * @param $filename
     */
    public static function createThumbs($filename)
    {
        /*
            require_once __DIR__ . '/phpthumb/phpthumb.class.php';
            $thumbnail_widths = array(150, 400);
            foreach ($thumbnail_widths as $thumbnail_width) {
                $phpThumb = new phpThumb();
                // set data
                $phpThumb->setSourceFilename('images/' . $filename);
                $phpThumb->w                    = $thumbnail_width;
                $phpThumb->config_output_format = 'jpeg';
                // generate & output thumbnail
                $output_filename = PEDIGREE_UPLOAD_URL . '/thumbnails/' . basename($filename) . '_' . $thumbnail_width . '.' . $phpThumb->config_output_format;
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
        $thumbnail_widths = [150, 400];

        // indicate a target image
        // note that there's no extra property to set in order to specify the target
        // image's type -simply by writing '.jpg' as extension will instruct the script
        // to create a 'jpg' file
        $config_output_format = 'jpeg';

        // create a new instance of the class
        $image = new \Zebra_Image();
        // indicate a source image (a GIF, PNG or JPEG file)
        $image->source_path = PEDIGREE_UPLOAD_PATH . "/images/{$filename}";

        foreach ($thumbnail_widths as $thumbnail_width) {
            // generate & output thumbnail
            $output_filename    = PEDIGREE_UPLOAD_PATH . '/images/thumbnails/' . \basename($filename) . "_{$thumbnail_width}.{$config_output_format}";
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
        $trans_tbl = \array_flip(\get_html_translation_table(\HTML_ENTITIES));

        return strtr($string, $trans_tbl);
    }

    /**
     * @param $oid
     * @param $gender
     * @return null
     */
    public static function pups($oid, $gender)
    {
        global $numofcolumns, $nummatch, $pages, $columns, $dogs;
        $content = '';

        if (0 == $gender) {
            $sqlquery = 'SELECT d.id AS d_id, d.pname AS d_pname, d.roft AS d_roft, d.* FROM '
                        . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
                        . ' d LEFT JOIN '
                        . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
                        . ' f ON d.father = f.id LEFT JOIN '
                        . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
                        . ' m ON d.mother = m.id WHERE d.father='
                        . $oid
                        . ' ORDER BY d.pname';
        } else {
            $sqlquery = 'SELECT d.id AS d_id, d.pname AS d_pname, d.roft AS d_roft, d.* FROM '
                        . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
                        . ' d LEFT JOIN '
                        . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
                        . ' f ON d.father = f.id LEFT JOIN '
                        . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
                        . ' m ON d.mother = m.id WHERE d.mother='
                        . $oid
                        . ' ORDER BY d.pname';
        }
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        $nummatch    = $GLOBALS['xoopsDB']->getRowsNum($queryresult);

        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are...
        $fields       = $animal->getNumOfFields();
        $numofcolumns = 1;
        $columns[]    = ['columnname' => 'Name'];
        foreach ($fields as $i => $iValue) {
            $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('fieldtype');
            $fieldObject = new $fieldType($userField, $animal);
            //create empty string
            $lookupvalues = '';
            if ($userField->isActive() && $userField->inList()) {
                if ($userField->hasLookup()) {
                    $lookupvalues = $userField->lookupField($fields[$i]);
                    //debug information
                    //print_r($lookupvalues);
                }
                $columns[] = [
                    'columnname'   => $fieldObject->fieldname,
                    'columnnumber' => $userField->getId(),
                    'lookupval'    => $lookupvalues,
                ];
                ++$numofcolumns;
                unset($lookupvalues);
            }
        }
        $columnvalue = [];
        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
            if ('0' == $rowres['d_roft']) {
                $gender = '<img src="assets/images/male.gif">';
            } else {
                $gender = '<img src="assets/images/female.gif">';
            }
            $name = \stripslashes($rowres['d_pname']);
            //empty array
            unset($columnvalue);
            //fill array
            for ($i = 1; $i < $numofcolumns; ++$i) {
                $x = $columns[$i]['columnnumber'];
                if (\is_array($columns[$i]['lookupval'])) {
                    foreach ($columns[$i]['lookupval'] as $key => $keyvalue) {
                        if ($keyvalue['id'] == $rowres['user' . $x]) {
                            $value = $keyvalue['value'];
                        }
                    }
                    //debug information
                    ///echo $columns[$i]['columnname']."is an array !";
                } //format value - cant use object because of query count
                elseif (0 === \strncmp($rowres['user' . $x], 'http://', 7)) {
                    $value = '<a href="' . $rowres['user' . $x] . '">' . $rowres['user' . $x] . '</a>';
                } else {
                    $value = $rowres['user' . $x];
                }
                $columnvalue[] = ['value' => $value];
            }
            $columnvalue = isset($columnvalue) ? $columnvalue : null;
            $dogs[]      = [
                'id'          => $rowres['d_id'],
                'name'        => $name,
                'gender'      => $gender,
                'link'        => '<a href="dog.php?id=' . $rowres['d_id'] . '">' . $name . '</a>',
                'colour'      => '',
                'number'      => '',
                'usercolumns' => $columnvalue,
            ];
        }

        return null;
    }

    /**
     * @param $oid
     * @param $pa
     * @param $ma
     * @return null
     */
    public static function bas($oid, $pa, $ma)
    {
        global $numofcolumns1, $nummatch1, $pages1, $columns1, $dogs1;
        if ('0' == $pa && '0' == $ma) {
            $sqlquery = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE father = ' . $pa . ' AND mother = ' . $ma . ' AND id != ' . $oid . " AND father != '0' AND mother !='0' ORDER BY pname";
        } else {
            $sqlquery = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE father = ' . $pa . ' AND mother = ' . $ma . ' AND id != ' . $oid . ' ORDER BY pname';
        }
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        $nummatch1   = $GLOBALS['xoopsDB']->getRowsNum($queryresult);

        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are...
        $fields        = $animal->getNumOfFields();
        $numofcolumns1 = 1;
        $columns1[]    = ['columnname' => 'Name'];
        foreach ($fields as $i => $iValue) {
            $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('fieldtype');
            $fieldObject = new $fieldType($userField, $animal);
            //create empty string
            $lookupvalues = '';
            if ($userField->isActive() && $userField->inList()) {
                if ($userField->hasLookup()) {
                    $lookupvalues = $userField->lookupField($fields[$i]);
                    //debug information
                    //print_r($lookupvalues);
                }
                $columns1[] = [
                    'columnname'   => $fieldObject->fieldname,
                    'columnnumber' => $userField->getId(),
                    'lookupval'    => $lookupvalues,
                ];
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
            $name = \stripslashes($rowres['pname']);
            //empty array
            //        unset($columnvalue1);
            $columnvalue1 = [];
            //fill array
            for ($i = 1; $i < $numofcolumns1; ++$i) {
                $x = $columns1[$i]['columnnumber'];
                if (\is_array($columns1[$i]['lookupval'])) {
                    foreach ($columns1[$i]['lookupval'] as $key => $keyvalue) {
                        if ($keyvalue['id'] == $rowres['user' . $x]) {
                            $value = $keyvalue['value'];
                        }
                    }
                    //debug information
                    ///echo $columns[$i]['columnname']."is an array !";
                } //format value - cant use object because of query count
                elseif (0 === \strncmp($rowres['user' . $x], 'http://', 7)) {
                    $value = '<a href="' . $rowres['user' . $x] . '">' . $rowres['user' . $x] . '</a>';
                } else {
                    $value = $rowres['user' . $x];
                }
                $columnvalue1[] = ['value' => $value];
            }
            $dogs1[] = [
                'id'          => $rowres['id'],
                'name'        => $name,
                'gender'      => $gender,
                'link'        => '<a href="dog.php?id=' . $rowres['id'] . '">' . $name . '</a>',
                'colour'      => '',
                'number'      => '',
                'usercolumns' => $columnvalue1,
            ];
        }

        return null;
    }

    /**
     * @param int $oid     owner/breder ID
     * @param int $breeder Constants::IS_OWNER | Constants::IS_BREEDER
     *
     * @return string HTML code with image & link to animals owned
     */
    public static function breederof($oid, $breeder)
    {
        $content = '';
        /**
         * @var XoopsModules\Pedigree\Helper      $helper
         * @var XoopsModules\Pedigree\TreeHandler $treeHandler
         */
        //@todo TEST refactor code below using Pedigree\Tree class CRUD methods
        $helper      = XoopsModules\Pedigree\Helper::getInstance();
        $treeHandler = $helper->getHandler('Tree');
        $fieldsArray = ['id', 'pname', 'roft'];
        $dbField     = (Constants::IS_OWNER == $breeder) ? 'id_owner' : 'id_breeder';
        $criteria    = new \Criteria($dbField, (int)$oid);
        $criteria->setSort('pname');
        $treeObjArray = $treeHandler->getAll($criteria, $fieldsArray);
        foreach ($treeObjArray as $id => $treeObj) {
            $gender  = Constants::MALE == $treeObj->getVar('roft') ? "<img src=\"" . $helper->url("assets/images/male.gif") . "\" alt=\"" . $helper->getConfig('male') . "\' title=\"" . $helper->getConfig('male') . "\">" : "<img src=\""
                                                                                                                                                                                                                              . $helper->url("assets/images/female.gif")
                                                                                                                                                                                                                              . "\" alt=\""
                                                                                                                                                                                                                              . $helper->getConfig('female')
                                                                                                                                                                                                                              . "\' title=\""
                                                                                                                                                                                                                              . $helper->getConfig('female')
                                                                                                                                                                                                                              . "\">";
            $link    = "<a href=\"" . $helper->url("dog.php?id={$id}") . "\">" . $treeObj->getVar('pname') . "</a>";
            $content .= $gender . ' ' . $link . "<br>\n";
        }
        /*
        if (Constants::IS_OWNER == $breeder) { // get the owner
            $sqlquery = 'SELECT id, pname, roft FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE id_owner = '" . $oid . "' ORDER BY pname";
        } else { // get the breeder
            $sqlquery = 'SELECT id, pname, roft FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE id_breeder = '" . $oid . "' ORDER BY pname";
        }
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
            //@todo add alt and title to <img> elements below...
            if (Constants::MALE == $rowres['roft']) {
                $gender = '<img src="assets/images/male.gif">';
            } else {
                $gender = '<img src="assets/images/female.gif">';
            }
            $link = '<a href="dog.php?id=' . $rowres['id'] . '">' . stripslashes($rowres['pname']) . '</a>';
            $content .= $gender . ' ' . $link . '<br>';
        }
        */
        return $content;
    }

    /**
     * @param int $oid
     *
     * @return string
     */
    public static function getName($oid)
    {
        $oid         = (int)$oid;
        $an          = '';
        $treeHandler = XoopsModules\Pedigree\Helper::getInstance()->getHandler('Tree');
        $treeObj     = $treeHandler->get($oid);
        if ($treeObj instanceof XoopsModules\Pedigree\Tree) {
            $an = $treeObj->getVar('pname');
        }
        /*
        $sqlquery = 'SELECT pname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE id = '{$oid}'";
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
            $an = stripslashes($rowres['pname']);
        }
        */
        return $an;
    }

    /**
     * Get the parent's name
     *
     * @param int $pId
     * @return string parent's name or '' if not found
     */
    public static function showParent($pId)
    {
        $parentName  = '';
        $treeHandler = XoopsModules\Pedigree\Helper::getInstance()->getHandler('Tree');
        $parentObj   = $treeHandler->get($pId);
        if ($parentObj instanceof XoopsModules\Pedigree\Tree && !$parentObj->isNew()) {
            $parentName = $parentObj->getVar('pname');
        }
        /*
        $sqlquery = 'SELECT pname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE id='" . (int)$pId . "'";
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
            $result = $rowres['pname'];
        }
        if (isset($result)) {
            return $result;
        }

        return '';
        */
        return $parentName;
    }

    /**
     * @param $pname_hond
     *
     * @return int id of 'pname' object
     */
    public static function findId($pname_hond)
    {
        $id          = 0;
        $treeHandler = XoopsModules\Pedigree\Helper::getInstance()->getHandler('Tree');
        //@todo need to filter $pname_hond
        $criteria = new \Criteria('pname', \mb_strtolower($pname_hond), '=', null, "lower(%s)");
        $criteria->setLimit(1);
        $treeIdArray = $treeHandler->getIds($criteria);
        if (0 !== \count($treeIdArray)) {
            $id = (int)\key($treeObjArray);
        }
        return $id;
        /*
        $result = 0;
        $sqlquery = 'SELECT id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE pname= '$pname_hond'";
        $queryresult = $GLOBALS['xoopsDB']->query($sqlquery);
        while (false !== ($rowres = $GLOBALS['xoopsDB']->fetchArray($queryresult))) {
            $result = $rowres['id'];
        }

        return $result;
        */
    }

    /**
     * @param $result
     * @param $prefix
     * @param $link
     * @param $element
     */
    public static function createList($result, $prefix, $link, $element)
    {
        $helper = XoopsModules\Pedigree\Helper::getInstance();
        require_once $helper->path('include/common.php');

        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are...
        $fieldIdArray = $animal->getNumOfFields();
        $columns      = []; //init columns array
        $columns[]    = ['columnname' => 'Name'];
        foreach ($fieldIdArray as $i => $iValue) {
            $userField   = new Pedigree\Field($iValue, $animal->getConfig());
            $fieldType   = $userField->getSetting('fieldtype');
            $fieldObject = new $fieldType($userField, $animal);
            if ($userField->isActive() && $userField->inList()) {
                if ($userField->hasLookup()) {
                    $id = $userField->getId();
                    $q  = $userField->lookupField($id);
                } else {
                    $q = '';
                }
                $columns[] = [
                    'columnname'   => $fieldObject->fieldname,
                    'columnnumber' => $userField->getId(),
                    'lookuparray'  => $q,
                ];
            }
        }

        //add preliminary row to array if passed
        if (\is_array($prefix)) {
            $dogs[] = $prefix;
        }

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //reset $gender
            $gender = '';
            if ($helper->isUserAdmin()
                || $GLOBALS['xoopsUser'] instanceof \XoopsUser
                   && (($row['user'] == $GLOBALS['xoopsUser']->getVar('uid') || true === $modadmin))) {
                $gender = "<a href=\"" . $helper->url("dog.php?id={$row['id']}") . "\">{$icons['edit']}</a>
                        . <a href=\"" . $helper->url("delete.php?id={$row['id']}") . "\">{$icons['delete']}</a>\n";
            }

            $genImg = (Constants::MALE == $row['roft']) ? 'male.gif' : 'female.gif';
            $gender .= "<img src=\"" . $helper->url("assets/images/{$genImg}") . "\" alt=\"" . $helper->getConfig('male') . "\" title=\"" . $helper->getConfig('female') . "\">";

            if ('' != $row['foto']) {
                //@todo - figure out what dog-icon25.png is, it currently doesn't exist : also need to add alt title tags
                $camera = " <img src=\"" . $helper->url("assets/images/dog-icon25.png") . "\">";
            } else {
                $camera = '';
            }
            $name = \stripslashes($row['pname']) . $camera;
            unset($columnvalue);

            //fill array
            $columnCount = \count($columns);
            $columnvalue = []; // init
            foreach ($columns as $thisColumn) {
                $value       = ''; // init
                $x           = $thisColumn['columnnumber'];
                $lookupArray = $thisColumn['lookuparray'];
                if (\is_array($lookupArray)) {
                    foreach ($lookupArray as $key => $value) {
                        if ($value['id'] == $row['user' . $x]) {
                            $value = $value['value'];
                        }
                    }
                    //@todo need to refactor using preg_match to allow for http[s]
                } elseif (0 === \strncmp($row["user{$x}"], 'http://', 7)) { //format value - can't use object because of query count
                    $value = "<a href=\"" . $row["user{$x}"] . "\">" . $row["user{$x}"] . "</a>\n";
                } else {
                    $value = $row["user{$x}"];
                }
                $columnvalue[] = ['value' => $value];
                unset($value);
            }

            /*
            //fill array
            $columnCount = count($columns);
            for ($i = 1; $i < $columnCount; ++$i) {
                $x = $columns[$i]['columnnumber'];
                $lookuparray = $columns[$i]['lookuparray'];
                if (is_array($lookuparray)) {
                    foreach ($lookuparray as $index => $indexValue) {
                        if ($lookuparray[$index]['id'] == $row['user' . $x]) {
                            //echo "<h1>".$lookuparray[$index]['id']."</h1>";
                            $value = $lookuparray[$index]['value'];
                        }
                    }
                } //format value - can't use object because of query count
                elseif (0 === strncmp($row['user' . $x], 'http://', 7)) {
                    $value = '<a href="' . $row['user' . $x] . '">' . $row['user' . $x] . '</a>';
                } else {
                    $value = $row['user' . $x];
                }
                $columnvalue[] = ['value' => $value];
                unset($value);
            }
            */
            //create array
            $dogs[] = [
                'id'          => $row['id'],
                'name'        => $name,
                'gender'      => $gender,
                'link'        => "<a href=\"{$link}{$row[$element]}\">{$name}</a>\n",
                'colour'      => '',
                'number'      => '',
                'usercolumns' => $columnvalue,
            ];
        }

        //add data to smarty template
        //assign dog
        $GLOBALS['xoopsTpl']->assign([
                                         'dogs'         => $dogs,
                                         'columns'      => $columns,
                                         'numofcolumns' => $columnCount,
                                         'tsarray'      => self::sortTable($columnCount),
                                     ]);
    }

    /***************Blocks**************
     *
     * @param array|string $cats
     *
     * @return string (cat1, cat2, cat3, etc) for SQL statement
     * @deprecated - NOT USED
     */
    public static function animal_block_addCatSelect($cats)
    {
        $cat_sql = '';
        if (\is_array($cats)) {
            $cats    = \array_map('\intval', $cats); // make sure all cats are numbers
            $cat_sql = '(' . \implode(',', $cats) . ')';
            /*
                        $cat_sql = '(' . current($cats);
                        array_shift($cats);
                        foreach ($cats as $cat) {
                            $cat_sql .= ',' . $cat;
                        }
                        $cat_sql .= ')';
            */
        } else {
            $cat_sql = '(' . (int)$cats . ')'; // not efficient but at least creates valid SQL statement
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
     * @deprecated
     */
    public static function animal_CleanVars(&$global, $key, $default = '', $type = 'int')
    {
        switch ($type) {
            case 'string':
                $ret = isset($global[$key]) ? \filter_var($global[$key], \FILTER_SANITIZE_MAGIC_QUOTES) : $default;
                break;
            case 'int':
            default:
                $ret = isset($global[$key]) ? \filter_var($global[$key], \FILTER_SANITIZE_NUMBER_INT) : $default;
                break;
        }
        if (false === $ret) {
            return $default;
        }

        return $ret;
    }

    /**
     * @param $content
     * @deprecated - NOT USED
     */
    public static function animal_meta_keywords($content)
    {
        global $xoTheme;
        $myts    = \MyTextSanitizer::getInstance();
        $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
        if (isset($xoTheme) && \is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'keywords', \strip_tags($content));
        } else {    // Compatibility for old Xoops versions
            $GLOBALS['xoopsTpl']->assign('xoops_meta_keywords', \strip_tags($content));
        }
    }

    /**
     * @param $content
     * @deprecated - NOT USED
     */
    public static function animal_meta_description($content)
    {
        global $xoTheme;
        $myts    = \MyTextSanitizer::getInstance();
        $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
        if (isset($xoTheme) && \is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'description', \strip_tags($content));
        } else {    // Compatibility for old Xoops versions
            $GLOBALS['xoopsTpl']->assign('xoops_meta_description', \strip_tags($content));
        }
    }

    /**
     * Verify that a mysql table exists
     *
     * @param mixed      $myObject
     * @param mixed      $activeObject
     * @param mixed      $criteria
     * @param mixed      $name
     * @param mixed      $link
     * @param null|mixed $link2
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
     * updated starting from this idea https://xoops.org/modules/news/article.php?storyid=6497
     *
     * @param Pedigree\Helper  $myObject
     * @param                  $activeObject
     * @param                  $criteria
     * @param                  $name
     * @param                  $link
     * @param null             $link2
     * @return string html
     *
     * @internal param $file
     * @internal param $file2
     * @access   public
     * @author   luciorota
     */
    public static function lettersChoice($myObject, $activeObject, $criteria, $name, $link, $link2 = null)
    {
        /** @var \XoopsModules\Pedigree\Helper $helper */
        $helper = Helper::getInstance();
        $helper->loadLanguage('main');
        \xoops_load('XoopsLocal');
        /*

        $criteria = $helper->getHandler('tree')->getActiveCriteria();
        $criteria->setGroupby('UPPER(LEFT(pname,1))');
        $countsByLetters = $helper->getHandler('tree')->getCounts($criteria);
        // Fill alphabet array
        $alphabet       = XoopsLocal::getAlphabet();
        $alphabet_array = array();
        foreach ($alphabet as $letter) {
            $letter_array = array();
            if (isset($countsByLetters[$letter])) {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = $countsByLetters[$letter];
                //            $letter_array['url']    = "" . XOOPS_URL . "/modules/" . $helper->getModule()->dirname() . "/viewcat.php?list={$letter}";
                $letter_array['url'] = '' . XOOPS_URL . '/modules/' . $helper->getModule()->dirname() . "/result.php?f=pname&amp;l=1&amp;w={$letter}%25&amp;o=pname";
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
            require_once $GLOBALS['xoops']->path('class/theme.php');
            $GLOBALS['xoTheme'] = new \xos_opal_Theme();
        }
        require_once $GLOBALS['xoops']->path('class/template.php');
        $letterschoiceTpl          = new \XoopsTpl();
        $letterschoiceTpl->caching = false; // Disable cache
        $letterschoiceTpl->assign('alphabet', $alphabet_array);
        $html = $letterschoiceTpl->fetch('db:' . $helper->getModule()->dirname() . '_common_letterschoice.tpl');
        unset($letterschoiceTpl);
        return $html;
*/

        //        $pedigree = Helper::getInstance();
        //        xoops_load('XoopsLocal');

        //        $criteria = $myObject->getHandler($activeObject)->getActiveCriteria();
        $criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');
        $countsByLetters = $myObject->getHandler($activeObject)->getCounts($criteria);
        // Fill alphabet array

        //@todo getAlphabet method doesn't exist anywhere
        //$alphabet       = XoopsLocal::getAlphabet();

        //        xoops_load('XoopsLocal');
        //        $xLocale        = new \XoopsLocal;
        //        $alphabet       = $xLocale->getAlphabet();
        $alphabet = \explode(',', _MA_PEDIGREE_LTRCHARS);
        //$alphabet       = pedigreeGetAlphabet();
        $alphabet_array = [];
        foreach ($alphabet as $letter) {
            /*
                        if (isset($countsByLetters[$letter])) {
                            $letter_array['letter'] = $letter;
                            $letter_array['count']  = $countsByLetters[$letter];
                            //            $letter_array['url']    = "" . XOOPS_URL . "/modules/" . $helper->getModule()->dirname() . "/viewcat.php?list={$letter}";
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


                            $alphabet_array = array();
                            //        foreach ($alphabet as $letter) {
                            foreach (range('A', 'Z') as $letter) {
            */
            $letter_array = [];
            if (isset($countsByLetters[$letter])) {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = $countsByLetters[$letter];
                //            $letter_array['url']    = "" . XOOPS_URL . "/modules/" . $helper->getModule()->dirname() . "/viewcat.php?list={$letter}";
                //                $letter_array['url'] = '' . XOOPS_URL . '/modules/' . $myObject->getModule()->dirname() . '/'.$file.'?f='.$name."&amp;l=1&amp;w={$letter}%25&amp;o=".$name;
                $letter_array['url'] = '' . XOOPS_URL . '/modules/' . $myObject->getModule()->dirname() . '/' . $link . $letter . $link2;
            } else {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = 0;
                $letter_array['url']    = '';
            }
            $alphabet_array[$letter] = $letter_array;
            unset($letter_array);
        }

        // Render output
        if (!isset($GLOBALS['xoTheme']) || !\is_object($GLOBALS['xoTheme'])) {
            require_once $GLOBALS['xoops']->path('class/theme.php');
            $GLOBALS['xoTheme'] = new \xos_opal_Theme();
        }
        require_once $GLOBALS['xoops']->path('class/template.php');
        $letterschoiceTpl          = new \XoopsTpl();
        $letterschoiceTpl->caching = false; // Disable cache
        $letterschoiceTpl->assign('alphabet', $alphabet_array);
        $html = $letterschoiceTpl->fetch('db:' . $myObject->getModule()->dirname() . '_common_letterschoice.tpl');
        unset($letterschoiceTpl);

        return $html;
    }

    /**
     * Alias for Pedigree\Helper->isUserAdmin
     *
     * Makes sure \XoopsModules\Pedigree\Helper class is loaded
     *
     * @return bool true if user is admin, false if not
     */
    public static function isUserAdmin()
    {
        /** @var \XoopsModules\Pedigree\Helper $helper */
        $helper = Helper::getInstance();

        return $helper->isUserAdmin();
    }

    /**
     * Get the current colour scheme
     *
     * @return array colours for current colour scheme
     */
    public static function getColourScheme()
    {
        $helper       = Helper::getInstance();
        $colValues    = $helper->getConfig('colourscheme');
        $patterns     = ['\s', '\,'];
        $replacements = ['', ';'];
        $colValues    = \preg_replace($patterns, $replacements, $colValues); // remove spaces and commas - backward compatibility
        $colors       = \explode(';', $colValues);

        return $colors;
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
        $GLOBALS['xoopsDB'] = \XoopsDatabaseFactory::getDatabaseConnection();
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
        $GLOBALS['xoopsDB'] = \XoopsDatabaseFactory::getDatabaseConnection();
        $sql                = \sprintf('SELECT metavalue FROM `%s` WHERE metakey= `%s` ', $GLOBALS['xoopsDB']->prefix('pedigree_meta'), $GLOBALS['xoopsDB']->quoteString($key));
        $ret                = $GLOBALS['xoopsDB']->query($sql);
        if (!$ret) {
            $value = false;
        } else {
            [$value] = $GLOBALS['xoopsDB']->fetchRow($ret);
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
        $GLOBALS['xoopsDB'] = \XoopsDatabaseFactory::getDatabaseConnection();
        if (false !== ($ret = self::getMeta($key))) {
            $sql = \sprintf('UPDATE `%s` SET metavalue = `%s` WHERE metakey = `%s` ', $GLOBALS['xoopsDB']->prefix('pedigree_meta'), $GLOBALS['xoopsDB']->quoteString($value), $GLOBALS['xoopsDB']->quoteString($key));
        } else {
            $sql = \sprintf('INSERT INTO `%s` (metakey, metavalue) VALUES (`%s`, `%s` )', $GLOBALS['xoopsDB']->prefix('pedigree_meta'), $GLOBALS['xoopsDB']->quoteString($key), $GLOBALS['xoopsDB']->quoteString($value));
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
        if (0 == $time) {
            $time = \time() + 3600 * 24 * 365;
        }
        setcookie($name, $value, $time, '/', \ini_get('session.cookie_domain'), \ini_get('session.cookie_secure'), \ini_get('session.cookie_httponly'));
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
        }

        return $default;
    }

    /**
     * @return array
     */
    public static function getCurrentUrls()
    {
        $http        = (false === \mb_strpos(XOOPS_URL, 'https://')) ? 'http://' : 'https://';
        $phpSelf     = $_SERVER['SCRIPT_NAME'];
        $httpHost    = $_SERVER['HTTP_HOST'];
        $sql = $_SERVER['QUERY_STRING'];

        if ('' != $sql) {
            $sql = '?' . $sql;
        }

        $currentURL = $http . $httpHost . $phpSelf . $sql;

        $urls                = [];
        $urls['http']        = $http;
        $urls['httphost']    = $httpHost;
        $urls['phpself']     = $phpSelf;
        $urls['querystring'] = $sql;
        $urls['full']        = $currentURL;

        return $urls;
    }

    /**
     * @return mixed
     */
    public static function getCurrentPage()
    {
        $urls = self::getCurrentUrls();

        return $urls['full'];
    }

    /**
     * @param array $errors
     *
     * @return string
     */
    public static function formatErrors($errors = [])
    {
        $ret = '';
        foreach ($errors as $key => $value) {
            $ret .= "<br> - {$value}";
        }

        return $ret;
    }

    /**
     * @param $tableName
     * @param $columnName
     *
     * @return array
     */
    public static function enumerate($tableName, $columnName)
    {
        $table = $GLOBALS['xoopsDB']->prefix($tableName);

        //    $result = $GLOBALS['xoopsDB']->query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        //        WHERE TABLE_NAME = '" . $table . "' AND COLUMN_NAME = '" . $columnName . "'")
        //    || exit ($GLOBALS['xoopsDB']->error());

        $sql    = 'SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "' . $table . '" AND COLUMN_NAME = "' . $columnName . '"';
        $result = $GLOBALS['xoopsDB']->query($sql);
        if (!$result) {
            exit($GLOBALS['xoopsDB']->error());
        }

        $row      = $GLOBALS['xoopsDB']->fetchBoth($result);
        $enumList = \explode(',', \str_replace("'", '', \mb_substr($row['COLUMN_TYPE'], 5, -6)));

        return $enumList;
    }
}
