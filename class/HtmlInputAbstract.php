<?php namespace XoopsModules\Pedigree;

/**
 *  pedigree HTML Input Interface Class Elements
 *
 * @copyright  ZySpec Incorporated
 * @license    {@link http://www.gnu.org/licenses/gpl-2.0.html GNU Public License}
 * @package    pedigree
 * @subpackage class
 * @author     zyspec <owners@zyspec.com>
 * @since      1.3.1
 */

use XoopsModules\Pedigree;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Pedigree\HtmlInputAbstract
 *
 * @package   pedigree
 * @author    zyspec <owners@zyspec.com>
 * @copyright Copyright (c) 2014 ZySpec Incorporated
 * @access    public
 */


/**
 * Class Pedigree\HtmlInputAbstract
 */
abstract class HtmlInputAbstract //extends Pedigree\Field
{
    /**
     * @return mixed
     */
    abstract public function editField();

    /**
     * @param $name
     * @return mixed
     */
    abstract public function newField($name);

    /**
     * @return mixed
     */
    abstract public function viewField();

    /**
     * @return mixed
     */
    abstract public function showField();

    /**
     * @return mixed
     */
    abstract public function showValue();

    /**
     * @return mixed
     */
    abstract public function searchField();

    /**
     * @return mixed
     */
    abstract public function getSearchString();

    /**
     * @param string $message
     *
     * @return void
     */
    public function echoMsg($message)
    {
        echo "<span style='color: red;'><h3>{$message}</h3></span>";
    }

    /**
     * @param $fieldnumber
     *
     * @return array
     */
    public function lookupField($fieldnumber)
    {
        $ret = [];
        global $xoopsDB;
        $SQL    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $fieldnumber) . " ORDER BY 'order'";
        $result = $GLOBALS['xoopsDB']->query($SQL);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $ret[] = ['id' => $row['id'], 'value' => $row['value']];
        }

        //array_multisort($ret,SORT_ASC);
        return $ret;
    }
}
