<?php namespace XoopsModules\Pedigree;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Pedigree\Breadcrumb Class
 *
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      lucio <lucio.rota@gmail.com>
 * @package     Pedigree
 * @since       1.31
 *
 */

use XoopsModules\Pedigree;


/**
 * Class Field
 */
class Field
{
    protected $id;

    /**
     * @param $fieldnumber
     * @param $config
     */
    public function __construct($fieldnumber, $config)
    {
        //find key where id = $fieldnumber;
        $configCount = count($config);
        foreach ($config as $x => $xValue) {
            //@todo - figure out if this is suppose to be an assignment or just a compare ('=' or '==')
            if ($config[$x]['id'] = $fieldnumber) {
                foreach ($config[$x] as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
        $this->id = $fieldnumber;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return '1' == $this->getSetting('isactive');
    }

    /**
     * @return bool
     */
    public function inAdvanced()
    {
        return '1' == $this->getSetting('viewinadvanced');
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return '1' == $this->getSetting('locked');
    }

    /**
     * @return bool
     */
    public function hasSearch()
    {
        return '1' == $this->getSetting('hassearch');
    }

    /**
     * @return bool
     */
    public function addLitter()
    {
        return '1' == $this->getSetting('litter');
    }

    /**
     * @return bool
     */
    public function generalLitter()
    {
        return ('1' == $this->getSetting('generallitter'));
    }

    /**
     * @return bool
     */
    public function hasLookup()
    {
        return ('1' == $this->getSetting('lookuptable'));
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return '&amp;o=naam&amp;p';
    }

    /**
     * @return bool
     */
    public function inPie()
    {
        return ('1' == $this->getSetting('viewinpie'));
    }

    /**
     * @return bool
     */
    public function inPedigree()
    {
        return ('1' == $this->getSetting('viewinpedigree'));
    }

    /**
     * @return bool
     */
    public function inList()
    {
        return '1' == $this->getSetting('viewinlist');
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $setting
     *
     * @return mixed
     */
    public function getSetting($setting)
    {
        return $this->{$setting};
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
        $SQL    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix("pedigree_lookup{$fieldnumber}") . " ORDER BY 'order'";
        $result = $GLOBALS['xoopsDB']->query($SQL);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $ret[] = ['id' => $row['id'], 'value' => $row['value']];
        }

        //array_multisort($ret,SORT_ASC);
        return $ret;
    }

    /**
     * @return \XoopsFormLabel
     */
    public function viewField()
    {
        $view = new \XoopsFormLabel($this->fieldname, $this->value);

        return $view;
    }

    /**
     * @return string
     */
    public function showField()
    {
        return $this->fieldname . ' : ' . $this->value;
    }

    /**
     * @return mixed|string
     */
    public function showValue()
    {
        global $myts;

        return $myts->displayTarea($this->value);
        //return $this->value;
    }

    /**
     * @return string
     */
    public function searchField()
    {
        return '<input type="text" name="query" size="20">';
    }
}
