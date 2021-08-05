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
 * Class Pedigree\SelectBox
 */
class TextArea extends Pedigree\HtmlInputAbstract
{
    // Define class variables
    private $fieldnumber;
    private $fieldname;
    private $value;
    private $defaultvalue;
    private $lookuptable;
    private $rows = 5;
    private $cols = 50;

    /**
     * Constructor
     *
     * @todo move hard coded language strings to language file
     * @param Field                         $parentObject
     * @param \XoopsModules\Pedigree\Animal $animalObject
     */
    public function __construct(Pedigree\Field $parentObject, Pedigree\Animal $animalObject)
    {
        $this->fieldnumber = $parentObject->getId();
        $this->fieldname = $parentObject->fieldname;
        $this->value = $animalObject->{'user' . $this->fieldnumber};
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
     * @return \XoopsFormTextArea
     */
    public function editField()
    {
        $textarea = new \XoopsFormTextArea('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $value = $this->value, $this->rows, $this->cols);

        return $textarea;
    }

    /**
     * @param string $name
     *
     * @return \XoopsFormTextArea
     */
    public function newField($name = '')
    {
        $textarea = new \XoopsFormTextArea('<b>' . $this->fieldname . '</b>', $name . 'user' . $this->fieldnumber, $value = $this->defaultvalue, $this->rows, $this->cols);

        return $textarea;
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return '&amp;o=naam&amp;l=1';
    }

    /**
     * @return mixed|void
     */
    public function showValue()
    {
        return null;
    }
}
