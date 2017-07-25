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
class PedigreeSelectBox extends PedigreeHtmlInputAbstract
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
     * @param PedigreeField  $parentObject
     * @param PedigreeAnimal $animalObject
     */
    public function __construct(PedigreeField $parentObject, PedigreeAnimal $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        $this->lookuptable  = $parentObject->lookuptable;
        if (0 == $this->lookuptable) {
            echo "<span style='color: red;'><h3>A lookuptable must be specified for userfield" . $this->fieldnumber . '</h3></span>';
        }
    }

    /**
     * @return XoopsFormSelect
     */
    public function editField()
    {
        $select         = new XoopsFormSelect('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $value = $this->value, $size = 1, $multiple = false);
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount        = count($lookupcontents);
        for ($i = 0; $i < $lcCount; ++$i) {
            $select->addOption($lookupcontents[$i]['id'], $lookupcontents[$i]['value']);
        }

        return $select;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormSelect
     */
    public function newField($name = '')
    {
        $select         = new XoopsFormSelect('<b>' . $this->fieldname . '</b>', $name . 'user' . $this->fieldnumber, $value = $this->defaultvalue, $size = 1, $multiple = false);
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount        = count($lookupcontents);
        for ($i = 0; $i < $lcCount; ++$i) {
            $select->addOption($lookupcontents[$i]['id'], $lookupcontents[$i]['value']);
        }

        return $select;
    }

    /**
     * @return XoopsFormLabel
     */
    public function viewField()
    {
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount        = count($lookupcontents);
        for ($i = 0; $i < $lcCount; ++$i) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }
        $view = new XoopsFormLabel($this->fieldname, $choosenvalue);

        return $view;
    }

    /**
     * @return string
     */
    public function showField()
    {
        $choosenvalue   = '';
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount        = count($lookupcontents);
        for ($i = 0; $i < $lcCount; ++$i) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }

        return $this->fieldname . ' : ' . $choosenvalue;
    }

    /**
     * @return string
     */
    public function showValue()
    {
        $choosenvalue   = '';
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount        = count($lookupcontents);
        for ($i = 0; $i < $lcCount; ++$i) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }

        return $choosenvalue;
    }

    /**
     * @return string HTML <select> for this field
     */
    public function searchField()
    {
        $select         = "<select size='1' name='query' style='width: 140px;'>";
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount        = count($lookupcontents);
        for ($i = 0; $i < $lcCount; ++$i) {
            $select .= "<option value='" . $lookupcontents[$i]['id'] . "'>" . $lookupcontents[$i]['value'] . '</option>';
        }
        $select .= '</select>';

        return $select;
    }

    /**
     *
     */
    public function getSearchString()
    {
        return;
    }
}
