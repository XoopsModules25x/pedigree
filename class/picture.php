<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * PedigreeBreadcrumb Class
 *
 * @copyright   {@link http://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      lucio <lucio.rota@gmail.com>
 * @package     Pedigree
 * @since       1.31
 *
 */
require_once __DIR__ . '/htmlinput.abstract.php';

/**
 * Class Picture
 */
class PedigreePicture extends PedigreeHtmlInputAbstract
{
    /**
     * @param Field $parentObject
     * @param PedigreeAnimal $animalObject
     */
    public function __construct($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        $this->lookuptable  = $parentObject->hasLookup();
        if ($this->lookuptable) {
            xoops_error('No lookuptable may be specified for userfield ' . $this->fieldnumber);
        }
        if ($parentObject->InAdvanced()) {
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
     * @return XoopsFormFile
     */
    public function editField()
    {
        $picturefield = new XoopsFormFile($this->fieldname, 'user' . $this->fieldnumber, 1024000);
        $picturefield->setExtra("size ='50'");

        return $picturefield;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormFile
     */
    public function newField($name = '')
    {
        $picturefield = new XoopsFormFile($this->fieldname, $name . 'user' . $this->fieldnumber, 1024000);
        $picturefield->setExtra("size ='50'");

        return $picturefield;
    }

    /**
     * @return XoopsFormLabel
     */
    public function viewField()
    {
        $view = new XoopsFormLabel($this->fieldname, "<img src=\"assets/images/thumbnails/" . $this->value . "_400.jpeg\">");

        return $view;
    }

    /**
     * @return string
     */
    public function showField()
    {
        return "<img src=\"assets/images/thumbnails/" . $this->value . "_150.jpeg\">";
    }

    /**
     * @return string
     */
    public function showValue()
    {
        return "<img src=\"assets/images/thumbnails/" . $this->value . "_400.jpeg\">";
    }
}
