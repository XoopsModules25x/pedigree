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
 * @license         {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author          XOOPS Module Dev Team
 */

use XoopsModules\Pedigree\{
    Animal,
    Field,
    Helper
};

/**
 * Class Picture
 */
class Picture extends HtmlInputAbstract
{
    /** @TODO: make file size a module Preferences config setting */
    private const MAXFILESIZE = 1024000;

    protected $fieldnumber  = 0;
    protected $fieldname    = '';
    protected $value        = null;
    protected $defaultvalue = '';
    protected $lookuptable  = 0;
    private $helper = null;

/**
     * @param Field  $parentObject
     * @param Animal $animalObject
     */
    public function __construct(Field $parentObject, Animal $animalObject)
    {
        $this->helper       = Helper::getInstance();
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->getSetting('fieldname');
        $this->value        = $animalObject->{'user' . (string) $this->fieldnumber};
        $this->defaultvalue = $parentObject->getSetting('defaultvalue');
        $this->lookuptable  = $parentObject->hasLookup();

        /** @TODO move hard coded language strings to language files */
        if ($this->lookuptable) {
            xoops_error('No lookuptable may be specified for userfield ' . $this->fieldnumber);
        }
        if ($parentObject->inAdvanced()) {
            xoops_error('userfield ' . $this->fieldnumber . ' cannot be shown in advanced info', get_class($this));
        }
        if ($parentObject->inPie()) {
            xoops_error('A Pie-chart cannot be specified for userfield ' . $this->fieldnumber, get_class($this));
        }
        if ('1' == $parentObject->viewinlist) {
            xoops_error('userfield ' . $this->fieldnumber . ' cannot be included in listview', get_class($this));
        }
        if ('1' == $parentObject->hassearch) {
            xoops_error('Search cannot be defined for userfield ' . $this->fieldnumber, get_class($this));
        }
    }

    /**
     * @return \XoopsFormFile
     */
    public function editField(): \XoopsFormFile
    {
        $picturefield = new \XoopsFormFile($this->fieldname, 'user' . $this->fieldnumber, self::MAXFILESIZE);
        $picturefield->setExtra("size ='50'");

        return $picturefield;
    }

    /**
     * @param null|string $name
     *
     * @return \XoopsFormFile
     */
    public function newField($name = ''): \XoopsFormFile
    {
        $picturefield = new \XoopsFormFile($this->fieldname, $name . 'user' . $this->fieldnumber, self::MAXFILESIZE);
        $picturefield->setExtra("size ='50'");

        return $picturefield;
    }

    /**
     * @return \XoopsFormLabel
     */
    public function viewField(): \XoopsFormLabel
    {
        $view = new \XoopsFormLabel($this->fieldname, '<img src="' . $this->helper->uploadUrl('images/thumbnails/' . $this->value . '_400.jpeg') . '">');

        return $view;
    }

    /**
     * @return string
     */
    public function showField(): string
    {
        return '<img src="' . $this->helper->uploadUrl('images/thumbnails/' . $this->value . '_150.jpeg') . '">';
    }

    /**
     * @return string
     */
    public function showValue(): string
    {
        return '<img src="' . $this->helper->uploadUrl('images/thumbnails/' . $this->value . '_400.jpeg') . '">';
    }
}
