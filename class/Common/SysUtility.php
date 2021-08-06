<?php

namespace XoopsModules\Pedigree\Common;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @package      \XoopsModules\Pedigree
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      GNU GPL2orlater(https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team <https://xoops.org>
 * @author       ZySpec <zyspec@yahoo.com>
 * @author       Mamba <mambax7@gmail.com>
 */

use Xmf\Request;
use XoopsFormEditor;
use XoopsModules\Pedigree\{
    Helper
};

/**
 * Class SysUtility
 */
class SysUtility
{
    use VersionChecks; //checkVerXoops, checkVerPhp Traits
    use ServerStats; // getServerStats Trait
    use FilesManagement; // Files Management Trait

    //--------------- Common module methods -----------------------------

    /**
     * Access the only instance of this class
     *
     * @return SysUtility
     *
     */
    public static function getInstance(): SysUtility
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * @param $text
     * @param $form_sort
     * @return string
     */
    public static function selectSorting($text, $form_sort): string
    {
        global $start, $order, $sort;

        $select_view   = '';
        $moduleDirName = \basename(\dirname(__DIR__));
        $helper = Helper::getInstance();

        //$pathModIcon16 = XOOPS_URL . '/modules/' . $moduleDirName . '/' . $helper->getConfig('modicons16');
        $pathModIcon16 = $helper->url($helper->getModule()->getInfo('modicons16'));

        $select_view = '<form name="form_switch" id="form_switch" action="' . Request::getString('REQUEST_URI', '', 'SERVER') . '" method="post"><span style="font-weight: bold;">' . $text . '</span>';
        //$sorts =  $sort ==  'asc' ? 'desc' : 'asc';
        if ($form_sort == $sort) {
            $sel1 = 'asc' === $order ? 'selasc.png' : 'asc.png';
            $sel2 = 'desc' === $order ? 'seldesc.png' : 'desc.png';
        } else {
            $sel1 = 'asc.png';
            $sel2 = 'desc.png';
        }
        $select_view .= '  <a href="' . Request::getString('SCRIPT_NAME', '', 'SERVER') . '?start=' . $start . '&sort=' . $form_sort . '&order=asc"><img src="' . $pathModIcon16 . '/' . $sel1 . '" title="ASC" alt="ASC"></a>';
        $select_view .= '<a href="' . Request::getString('SCRIPT_NAME', '', 'SERVER') . '?start=' . $start . '&sort=' . $form_sort . '&order=desc"><img src="' . $pathModIcon16 . '/' . $sel2 . '" title="DESC" alt="DESC"></a>';
        $select_view .= '</form>';

        return $select_view;
    }

    /***************Blocks***************/
    /**
     * @param array $cats
     * @return string
     */
    public static function blockAddCatSelect(array $cats): string
    {
        $cat_sql = '';
        if (\is_array($cats) && !empty($cats)) {
            $cat_sql = '(' . \current($cats);
            \array_shift($cats);
            foreach ($cats as $cat) {
                $cat_sql .= ',' . $cat;
            }
            $cat_sql .= ')';
        }

        return $cat_sql;
    }

    /**
     * @param $content
     */
    public static function metaKeywords($content): void
    {
        global $xoopsTpl, $xoTheme;
        $myts    = \MyTextSanitizer::getInstance();
        $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
        if (\is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'keywords', \strip_tags($content));
        } else {    // Compatibility for old Xoops versions
            $xoopsTpl->assign('xoops_metaKeywords', \strip_tags($content));
        }
    }

    /**
     * @param $content
     */
    public static function metaDescription($content): void
    {
        global $xoopsTpl, $xoTheme;
        $myts    = \MyTextSanitizer::getInstance();
        $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
        if (\is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'description', \strip_tags($content));
        } else {    // Compatibility for old Xoops versions
            $xoopsTpl->assign('xoops_metaDescription', \strip_tags($content));
        }
    }

    /**
     * @param string $tableName
     * @param string $columnName
     *
     * @return array|false
     */
    public static function enumerate(string $tableName, string $columnName)
    {
        $table = $GLOBALS['xoopsDB']->prefix($tableName);

        //    $result = $GLOBALS['xoopsDB']->query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        //        WHERE TABLE_NAME = '" . $table . "' AND COLUMN_NAME = '" . $columnName . "'")
        //    || exit ($GLOBALS['xoopsDB']->error());

        $sql    = 'SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "' . $table . '" AND COLUMN_NAME = "' . $columnName . '"';
        $result = $GLOBALS['xoopsDB']->query($sql);
        if (!$result) {
//            exit($GLOBALS['xoopsDB']->error());
            $logger     = \XoopsLogger::getInstance();
            $logger->handleError(\E_USER_WARNING, $sql, __FILE__, __LINE__);
            return false;
        }

        $row      = $GLOBALS['xoopsDB']->fetchBoth($result);
        $enumList = \explode(',', \str_replace("'", '', \mb_substr($row['COLUMN_TYPE'], 5, - 6)));
        return $enumList;
    }


    /**
     * Clone a record in a dB
     *
     * @TODO need to exit more gracefully on error. Should throw/trigger error and then return false
     *
     * @param string $tableName name of dB table (without prefix)
     * @param string $idField   name of field (column) in dB table
     * @param int    $id        item id to clone
     *
     * @return mixed
     */
    public static function cloneRecord(string $tableName, string $idField, int $id)
    {
        $newId = false;
        $table = $GLOBALS['xoopsDB']->prefix($tableName);
        // copy content of the record you wish to clone
        $sql       = "SELECT * FROM $table WHERE $idField='" . (int)$id . "' ";
        $tempTable = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql), \MYSQLI_ASSOC);
        if (!$tempTable) {
            exit($GLOBALS['xoopsDB']->error());
        }
        // set the auto-incremented id's value to blank.
        unset($tempTable[$idField]);
        // insert cloned copy of the original  record
        $sql    = "INSERT INTO $table (" . \implode(', ', \array_keys($tempTable)) . ") VALUES ('" . \implode("', '", \array_values($tempTable)) . "')";
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) {
            exit($GLOBALS['xoopsDB']->error());
        }
        // Return the new id
        return $GLOBALS['xoopsDB']->getInsertId();
    }
    /**
     * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
     * www.gsdesign.ro/blog/cut-html-string-without-breaking-the-tags
     * www.cakephp.org
     *
     * @TODO: Refactor to consider HTML5 & void (self-closing) elements
     * @TODO: Consider using https://github.com/jlgrall/truncateHTML/blob/master/truncateHTML.php
     *
     * @param string $text         String to truncate.
     * @param int    $length       Length of returned string, including ellipsis.
     * @param string $ending       Ending to be appended to the trimmed string.
     * @param bool   $exact        If false, $text will not be cut mid-word
     * @param bool   $considerHtml If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    public static function truncateHtml(
        string $text,
        ?int $length = 100,
        ?string $ending = '...',
        ?bool $exact = false,
        ?bool $considerHtml = true
    ): string {
        $openTags = [];
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (\mb_strlen(\preg_replace('/<.*?' . '>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            \preg_match_all('/(<.+?' . '>)?([^<>]*)/s', $text, $lines, \PREG_SET_ORDER);
            $total_length = \mb_strlen($ending);
            //$openTags    = [];
            $truncate     = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (\preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } elseif (\preg_match('/^<\s*\/(\S+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $openTags list
                        $pos = \array_search($tag_matchings[1], $openTags, true);
                        if (false !== $pos) {
                            unset($openTags[$pos]);
                        }
                        // if tag is an opening tag
                    } elseif (\preg_match('/^<\s*([^\s>!]+).*?' . '>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $openTags list
                        \array_unshift($openTags, \mb_strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = \mb_strlen(\preg_replace('/&[0-9a-z]{2,8};|&#\d{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length + $content_length > $length) {
                    // the number of characters which are left
                    $left            = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (\preg_match_all('/&[0-9a-z]{2,8};|&#\d{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, \PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($left >= $entity[1] + 1 - $entities_length) {
                                $left--;
                                $entities_length += \mb_strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= \mb_substr($line_matchings[2], 0, $left + $entities_length);
                    // maximum length is reached, so get off the loop
                    break;
                }
                $truncate     .= $line_matchings[2];
                $total_length += $content_length;

                // if the maximum length is reached, get off the loop
                if ($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (\mb_strlen($text) <= $length) {
                return $text;
            }
            $truncate = \mb_substr($text, 0, $length - \mb_strlen($ending));
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = \mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = \mb_substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if ($considerHtml) {
            // close all unclosed html-tags
            foreach ($openTags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    /**
     * Get correct text editor based on user rights
     *
     * @param \Xmf\Module\Helper $helper
     * @param array|null         $options
     *
     * @return \XoopsFormDhtmlTextArea|\XoopsFormEditor
     */
    public static function getEditor(?\Xmf\Module\Helper $helper = null, ?array $options = null)
    {
        /** @var Helper $helper */
        if (null === $options) {
            $options           = [];
            $options['name']   = 'Editor';
            $options['value']  = 'Editor';
            $options['rows']   = 10;
            $options['cols']   = '100%';
            $options['width']  = '100%';
            $options['height'] = '400px';
        }

        if (null === $helper) {
            $helper = Helper::getInstance();
        }

        $isAdmin = $helper->isUserAdmin();

        if (\class_exists('XoopsFormEditor')) {
            if ($isAdmin) {
                $descEditor = new XoopsFormEditor(\ucfirst($options['name']), $helper->getConfig('editorAdmin'), $options, $nohtml = false, $onfailure = 'textarea');
            } else {
                $descEditor = new XoopsFormEditor(\ucfirst($options['name']), $helper->getConfig('editorUser'), $options, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $descEditor = new \XoopsFormDhtmlTextArea(\ucfirst($options['name']), $options['name'], $options['value'], '100%', '100%');
        }

        //        $form->addElement($descEditor);

        return $descEditor;
    }

    /**
     * Check if column in dB table exists
     *
     * @param string $fieldname name of dB table field
     * @param string $table     name of dB table (including prefix)
     *
     * @return bool true if table exists
     * @deprecated
     */
    public static function fieldExists(string $fieldname, string $table): bool
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        \trigger_error(__METHOD__ . " is deprecated, use Xmf\Database\Tables instead - instantiated from {$trace[0]['file']} line {$trace[0]['line']},");

        $result = $GLOBALS['xoopsDB']->queryF("SHOW COLUMNS FROM   $table LIKE '$fieldname'");
        return ($GLOBALS['xoopsDB']->getRowsNum($result) > 0);
    }


    /**
     * Function responsible for checking if a directory exists, we can also write in and create an index.html file
     *
     * @param string $folder The full path of the directory to check
     */
    public static function prepareFolder(string $folder): void
    {
        try {
            if (!@\mkdir($folder) && !\is_dir($folder)) {
                throw new \RuntimeException(\sprintf('Unable to create the %s directory', $folder));
            }
            file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
        } catch (\Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n", '<br>';
        }
    }

    /**
     * Check if dB table table exists
     *
     * @param string $tablename dB tablename with prefix
     * @return bool true if table exists
     */
    public static function tableExists(string $tablename): bool
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        \trigger_error(__FUNCTION__ . " is deprecated, called from {$trace[0]['file']} line {$trace[0]['line']}");
        $GLOBALS['xoopsLogger']->addDeprecated(
            \basename(\dirname(__DIR__, 2)) . ' Module: ' . __FUNCTION__ . ' function is deprecated, please use Xmf\Database\Tables method(s) instead.' . " Called from {$trace[0]['file']}line {$trace[0]['line']}"
        );
        $result = $GLOBALS['xoopsDB']->queryF("SHOW TABLES LIKE '$tablename'");

        return $GLOBALS['xoopsDB']->getRowsNum($result) > 0    ;
    }

    /**
     * Add a field to a mysql table
     *
     * @param $field
     * @param $table
     * @return bool|\mysqli_result
     */
    public static function addField($field, $table)
    {
        global $xoopsDB;
        return $xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field;");
    }
}
