<?php

namespace XoopsModules\Pedigree;

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
 * @copyright   {@link http://sourceforge.net/projects/thmod/ The TXMod XOOPS Project}
 * @copyright   {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license     GPL 2.0 or later
 * @package     pedigree
 * @subpackage  class
 * @author      XOOPS Mod Development Team
 */

use XoopsFormTextDateSelect;
use XoopsModules\Pedigree;

/**
 * Class Pedigree\DateSelectBox
 */
class DateSelect extends Pedigree\HtmlInputAbstract
{
    // Define class variables
    private $fieldnumber;
    private $fieldname;
    private $value;
    private $defaultvalue;
    private $lookuptable;
    private $errs;

    /**
     * Constructor
     *
     * @param Pedigree\Fields $parentObject
     * @param                 $animalObject
     */
    public function __construct($parentObject, $animalObject)
    {
        //@todo move language strings to language file
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        if ($parentObject->hasLookup()) {
            \xoops_error('No lookuptable may be specified for userfield ' . $this->fieldnumber, \get_class($this));
        }
        if ($parentObject->inAdvanced()) {
            \xoops_error('userfield ' . $this->fieldnumber . ' cannot be shown in advanced info', \get_class($this));
        }
        if ($parentObject->inPie()) {
            \xoops_error('A Pie-chart cannot be specified for userfield ' . $this->fieldnumber, \get_class($this));
        }
    }

    /**
     * @return \XoopsFormTextDateSelect
     */
    public function editField()
    {
        $textarea = new \XoopsFormTextDateSelect('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $size = 15, $this->value);

        return $textarea;
    }

    /**
     * @param string $name
     *
     * @return \XoopsFormTextDateSelect
     */
    public function newField($name = '')
    {
        $textarea = new \XoopsFormTextDateSelect('<b>' . $this->fieldname . '</b>', $name . 'user' . $this->fieldnumber, $size = 15, $this->defaultvalue);

        return $textarea;
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return '&amp;o=pname&amp;l=1';
    }

    /**
     * @return mixed|void
     */
    public function searchField()
    {
        return null;
    }

    /**
     * @return mixed|void
     */
    public function showField()
    {
        return null;
    }

    /**
     * @return mixed|void
     */
    public function viewField()
    {
        return null;
    }

    /**
     * @return mixed|void
     */
    public function showValue()
    {
        return null;
    }
}
