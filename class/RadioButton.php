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
use XoopsModules\Pedigree;

/**
 * Class Pedigree\RadioButton
 */

/**
 * Class Pedigree\RadioButton
 */
class RadioButton extends Pedigree\HtmlInputAbstract
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
     * @param \XoopsModules\Pedigree\Field  $parentObject
     * @param \XoopsModules\Pedigree\Animal $animalObject
     * @todo move hard coded language string to language file
     */
    public function __construct(Pedigree\Field $parentObject, Pedigree\Animal $animalObject)
    {
        $this->fieldnumber = $parentObject->getId();
        $this->fieldname = $parentObject->fieldname;
        $this->value = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        $this->lookuptable = $parentObject->LookupTable;
        if (0 == $this->lookuptable) {
            echo "<span style='color: #ff0000;'><h3>A lookuptable must be specified for userfield" . $this->fieldnumber . '</h3></span>';
        }
    }

    /**
     * @return object {@see XoopsFormRadio}
     */
    public function editField()
    {
        $radio = new \XoopsFormRadio('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $value = $this->value);
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount = count($lookupcontents);
        foreach ($lookupcontents as $i => $iValue) {
            $radio->addOption($lookupcontents[$i]['id'], $lookupcontents[$i]['value']);
        }

        return $radio;
    }

    /**
     * @param string $name
     *
     * @return object {@see XoopsFormRadio)
     */
    public function newField($name = '')
    {
        $radio = new \XoopsFormRadio('<b>' . $this->fieldname . '</b>', "{$name}user" . $this->fieldnumber, $value = $this->defaultvalue);
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount = count($lookupcontents);
        foreach ($lookupcontents as $i => $iValue) {
            $radio->addOption($lookupcontents[$i]['id'], $lookupcontents[$i]['value']);
        }

        return $radio;
    }

    /**
     * @return object {@see XoopsFormLabel}
     */
    public function viewField()
    {
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount = count($lookupcontents);
        foreach ($lookupcontents as $i => $iValue) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }
        $view = new \XoopsFormLabel($this->fieldname, $choosenvalue);

        return $view;
    }

    /**
     * @todo error checking
     * @return string
     */
    public function showField()
    {
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount = count($lookupcontents);
        foreach ($lookupcontents as $i => $iValue) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }

        return $this->fieldname . ' : ' . $choosenvalue;
    }

    /**
     * @return mixed
     */
    public function showValue()
    {
        $lookupcontents = parent::lookupField($this->fieldnumber);
        foreach ($lookupcontents as $i => $iValue) {
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
        $select = "<select size='1' name='query' style='width: 140px;'>";
        $lookupcontents = parent::lookupField($this->fieldnumber);
        $lcCount = count($lookupcontents);
        foreach ($lookupcontents as $i => $iValue) {
            $select .= "<option value='" . $lookupcontents[$i]['id'] . "'>" . $lookupcontents[$i]['value'] . '</option>';
        }
        $select .= '</select>';

        return $select;
    }
}
