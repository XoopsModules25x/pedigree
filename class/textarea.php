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
 * Pedigree module for XOOPS
 *
 * @copyright   {@link http://sourceforge.net/projects/thmod/ The TXMod XOOPS Project}
 * @copyright   {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license     GPL 2.0 or later
 * @package     pedigree
 * @subpackage  class
 * @author      XOOPS Mod Development Team
 */

require_once __DIR__ . '/htmlinput.abstract.php';

/**
 * Class PedigreeSelectBox
 */
class PedigreeTextArea extends PedigreeHtmlInputAbstract
{
    // Define class variables
    private $fieldnumber;
    private $fieldname;
    private $value;
    private $defaultvalue;
    private $lookuptable;

    /**
     * Constructor
     *
     * @param Field $parentObject
     * @param PedigreeAnimal $animalObject
     */
    public function __construct($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        if ($parentObject->hasLookup()) {
            xoops_error('No lookuptable may be specified for userfield ' . $this->fieldnumber, get_class($this));
        }
        if ($parentObject->inAdvanced()) {
            xoops_error('userfield ' . $this->fieldnumber . ' cannot be shown in advanced info', get_class($this));
        }
        if ($parentObject->inPie()) {
            xoops_error('A Pie-chart cannot be specified for userfield ' . $this->fieldnumber, get_class($this));
        }
    }

    /**
     * @return XoopsFormTextArea
     */
    public function editField()
    {
        $textarea = new XoopsFormTextArea('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $value = $this->value, $rows = 5, $cols = 50);

        return $textarea;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormTextArea
     */
    public function newField($name = '')
    {
        $textarea = new XoopsFormTextArea('<b>' . $this->fieldname . '</b>', $name . 'user' . $this->fieldnumber, $value = $this->defaultvalue, $rows = 5, $cols = 50);

        return $textarea;
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return '&amp;o=naam&amp;l=1';
    }
}
