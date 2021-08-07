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
 * @package         XoopsModules\Pedigree
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author          XOOPS Module Dev Team
 */


/**
 * Class Pedigree\SelectBox
 */
class UrlField extends HtmlInputAbstract
{
    // Define class variables
    private $fieldnumber;
    private $fieldname;
    private $value;
    private $defaultvalue;
    private $lookuptable;
    private $size    = 50;
    private $maxsize = 255;

    /**
     * Constructor
     *
     * @param Field           $parentObject
     * @param Pedigree\Animal $animalObject
     * @todo move hard coded language strings to language file
     */
    public function __construct($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        $this->lookuptable  = $parentObject->hasLookup();
        if ($this->lookuptable) {
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
     * @return \XoopsFormText
     */
    public function editField()
    {
        $textbox = new \XoopsFormText('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $this->size, $this->maxsize, $value = $this->value);

        return $textbox;
    }

    /**
     * @param string $name
     *
     * @return \XoopsFormText
     */
    public function newField($name = '')
    {
        $textbox = new \XoopsFormText('<b>' . $this->fieldname . '</b>', $name . 'user' . $this->fieldnumber, $this->size, $this->maxsize, $value = $this->defaultvalue);

        return $textbox;
    }

    /**
     * @return \XoopsFormLabel
     */
    public function viewField()
    {
        $view = new \XoopsFormLabel($this->fieldname, '<a href="' . $this->value . '" target=\"_new\">' . $this->value . '</a>');

        return $view;
    }

    /**
     * @return string
     */
    public function showField()
    {
        return $this->fieldname . ' : <a href="' . $this->value . '" target="_new">' . $this->value . '</a>';
    }

    /**
     * @return string
     */
    public function showValue()
    {
        return '<a href="' . $this->value . '" target="_new">' . $this->value . '</a>';
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return '&amp;o=pname&amp;l=1';
    }
}
