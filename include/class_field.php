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
 * Class SystemMessage
 */
class SystemMessage
{
    /**
     * @param $message
     */
    public function __construct($message)
    {
        echo '<span style="color: red;"><h3>' . $message . '</h3></span>';
    }
}

/**
 * Class Animal
 */
class Animal
{
    /**
     * @deprecated
     * @param int $animalnumber * @internal param int $id animal ID
     */
    public function __construct($animalnumber = 0)
    {
        global $xoopsDB;
        if ($animalnumber == 0) {
            $SQL = 'SELECT * from ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE ID = '1'";
        } else {
            $SQL = 'SELECT * from ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE ID = ' . $animalnumber;
        }
        $result    = $GLOBALS['xoopsDB']->query($SQL);
        $row       = $GLOBALS['xoopsDB']->fetchRow($result);
        $numfields = $GLOBALS['xoopsDB']->getFieldsNum($result);
        for ($i = 0; $i < $numfields; ++$i) {
            $key        = mysqli_fetch_field_direct($result, $i)->name;
            $this->$key = $row[$i];
        }
    }

    /**
     * @return array
     */
    public function getNumOfFields()
    {
        global $xoopsDB;
        $SQL    = 'SELECT * from ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . ' ORDER BY `order`';
        $fields = array();
        $result = $GLOBALS['xoopsDB']->query($SQL);
        $count  = 0;
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $fields[] = $row['Id'];
            ++$count;
            $configValues[] = $row;
        }
        $this->configValues = isset($configValues) ? $configValues : '';
        //print_r ($this->configValues); die();
        return $fields;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->configValues;
    }
}

/**
 * Class Field
 */
class Field
{
    /**
     * @param $fieldnumber
     * @param $config
     */
    public function __construct($fieldnumber, $config)
    {
        //find key where ID = $fieldnumber;
        for ($x = 0, $xMax = count($config); $x < $xMax; ++$x) {
            if ($config[$x]['Id'] = $fieldnumber) {
                foreach ($config[$x] as $key => $values) {
                    $this->$key = $values;
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
        $active = $this->getSetting('isActive');
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function inAdvanced()
    {
        $active = $this->getSetting('ViewInAdvanced');
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        $active = $this->getSetting('locked');
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasSearch()
    {
        $active = $this->getSetting('HasSearch');
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function addLitter()
    {
        $active = $this->getSetting('Litter');
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function generalLitter()
    {
        $active = $this->getSetting('Generallitter');
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasLookup()
    {
        $active = $this->getSetting('LookupTable');
        if ($active == '1') {
            return true;
        }

        return false;
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
        $active = $this->getSetting('ViewInPie');
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function inPedigree()
    {
        $active = $this->getSetting('ViewInPedigree');
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function inList()
    {
        $active = $this->getSetting('ViewInList');
        if ($active == '1') {
            return true;
        }

        return false;
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
        $ret = array();
        global $xoopsDB;
        $SQL    = 'SELECT * from ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $fieldnumber) . " ORDER BY 'order'";
        $result = $GLOBALS['xoopsDB']->query($SQL);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $ret[] = array('id' => $row['Id'], 'value' => $row['value']);
        }
        //array_multisort($ret,SORT_ASC);
        return $ret;
    }

    /**
     * @return XoopsFormLabel
     */
    public function viewField()
    {
        $view = new XoopsFormLabel($this->fieldname, $this->value);

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

/**
 * Class RadioButton
 */
class RadioButton extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    public function __construct($parentObject, $animalObject)
    {
        $this->fieldnumber = $parentObject->getId();

        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        $this->lookuptable  = $parentObject->lookuptable;
        if ($this->lookuptable == '0') {
            new Systemmessage('A lookuptable must be specified for userfield' . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormRadio
     */
    public function editField()
    {
        $radio          = new XoopsFormRadio('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $value = $this->value);
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
            $radio->addOption($lookupcontents[$i]['id'], $name = ($lookupcontents[$i]['value'] . '<br />'));
        }

        return $radio;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormRadio
     */
    public function newField($name = '')
    {
        $radio          = new XoopsFormRadio('<b>' . $this->fieldname . '</b>', $name . 'user' . $this->fieldnumber, $value = $this->defaultvalue);
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
            $radio->addOption($lookupcontents[$i]['id'], $name = ($lookupcontents[$i]['value'] . '<br />'));
        }

        return $radio;
    }

    /**
     * @return XoopsFormLabel
     */
    public function viewField()
    {
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
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
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
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
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }

        return $choosenvalue;
    }

    /**
     * @return string
     */
    public function searchField()
    {
        $select         = '<select size="1" name="query" style="width: 140px;">';
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
            $select .= '<option value="' . $lookupcontents[$i]['id'] . '">' . $lookupcontents[$i]['value'] . '</option>';
        }
        $select .= '</select>';

        return $select;
    }
}

/**
 * Class SelectBox
 */
class SelectBox extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    public function __construct($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        $this->lookuptable  = $parentObject->lookuptable;
        if ($this->lookuptable == '0') {
            new Systemmessage('A lookuptable must be specified for userfield' . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormSelect
     */
    public function editField()
    {
        $select         = new XoopsFormSelect('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $value = $this->value, $size = 1, $multiple = false);
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
            $select->addOption($lookupcontents[$i]['id'], $name = ($lookupcontents[$i]['value'] . '<br />'));
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
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
            $select->addOption($lookupcontents[$i]['id'], $name = ($lookupcontents[$i]['value'] . '<br />'));
        }

        return $select;
    }

    /**
     * @return XoopsFormLabel
     */
    public function viewField()
    {
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
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
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
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
        $choosenvalue   = '';
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }

        return $choosenvalue;
    }

    /**
     * @return string
     */
    public function searchField()
    {
        $select         = '<select size="1" name="query" style="width: 140px;">';
        $lookupcontents = Field::lookupField($this->fieldnumber);
        for ($i = 0, $iMax = count($lookupcontents); $i < $iMax; ++$i) {
            $select .= '<option value="' . $lookupcontents[$i]['id'] . '">' . $lookupcontents[$i]['value'] . '</option>';
        }
        $select .= '</select>';

        return $select;
    }
}

/**
 * Class TextBox
 */
class TextBox extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    public function __construct($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        $this->lookuptable  = $parentObject->lookuptable;
        if ($this->lookuptable == '1') {
            new Systemmessage('No lookuptable may be specified for userfield' . $this->fieldnumber);
        }
        if ($parentObject->ViewInAdvanced == '1') {
            new Systemmessage('userfield' . $this->fieldnumber . ' cannot be shown in advanced info');
        }
        if ($parentObject->ViewInPie == '1') {
            new Systemmessage('A Pie-chart cannot be specified for userfield' . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormText
     */
    public function editField()
    {
        $textbox = new XoopsFormText('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $size = 50, $maxsize = 50, $value = $this->value);

        return $textbox;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormText
     */
    public function newField($name = '')
    {
        $textbox = new XoopsFormText('<b>' . $this->fieldname . '</b>', $name . 'user' . $this->fieldnumber, $size = 50, $maxsize = 50, $value = $this->defaultvalue);

        return $textbox;
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return '&amp;o=naam&amp;l=1';
    }
}

/**
 * Class TextArea
 */
class TextArea extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    public function __construct($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        if ($parentObject->LookupTable == '1') {
            new Systemmessage('No lookuptable may be specified for userfield' . $this->fieldnumber);
        }
        if ($parentObject->ViewInAdvanced == '1') {
            new Systemmessage('userfield' . $this->fieldnumber . ' cannot be shown in advanced info');
        }
        if ($parentObject->ViewInPie == '1') {
            new Systemmessage('A Pie-chart cannot be specified for userfield' . $this->fieldnumber);
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

/**
 * Class DataSelect
 */
class DataSelect extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    public function __construct($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        if ($parentObject->lookuptable == '1') {
            new Systemmessage('No lookuptable may be specified for userfield' . $this->fieldnumber);
        }
        if ($parentObject->ViewInAdvanced == '1') {
            new Systemmessage('userfield' . $this->fieldnumber . ' cannot be shown in advanced info');
        }
        if ($parentObject->ViewInPie == '1') {
            new Systemmessage('A Pie-chart cannot be specified for userfield' . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormTextDateSelect
     */
    public function editField()
    {
        //$textarea = new XoopsFormFile("<b>".$this->fieldname."</b>", $this->fieldname, $maxfilesize = 2000);
        $textarea = new XoopsFormTextDateSelect('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $size = 15, $this->value);

        return $textarea;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormTextDateSelect
     */
    public function newField($name = '')
    {
        $textarea = new XoopsFormTextDateSelect('<b>' . $this->fieldname . '</b>', $name . 'user' . $this->fieldnumber, $size = 15, $this->defaultvalue);

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

/**
 * Class UrlField
 */
class UrlField extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    public function __construct($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        $this->lookuptable  = $parentObject->lookuptable;
        if ($this->lookuptable == '1') {
            new Systemmessage('No lookuptable may be specified for userfield' . $this->fieldnumber);
        }
        if ($parentObject->ViewInAdvanced == '1') {
            new Systemmessage('userfield' . $this->fieldnumber . ' cannot be shown in advanced info');
        }
        if ($parentObject->ViewInPie == '1') {
            new Systemmessage('A Pie-chart cannot be specified for userfield' . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormText
     */
    public function editField()
    {
        $textbox = new XoopsFormText('<b>' . $this->fieldname . '</b>', 'user' . $this->fieldnumber, $size = 50, $maxsize = 255, $value = $this->value);

        return $textbox;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormText
     */
    public function newField($name = '')
    {
        $textbox = new XoopsFormText('<b>' . $this->fieldname . '</b>', $name . 'user' . $this->fieldnumber, $size = 50, $maxsize = 255, $value = $this->defaultvalue);

        return $textbox;
    }

    /**
     * @return XoopsFormLabel
     */
    public function viewField()
    {
        $view = new XoopsFormLabel($this->fieldname, '<a href="' . $this->value . '" target=\"_new\">' . $this->value . '</a>');

        return $view;
    }

    /**
     * @return string
     */
    public function showField()
    {
        return $this->fieldname . " : <a href=\"" . $this->value . "\" target=\"_new\">" . $this->value . '</a>';
    }

    /**
     * @return string
     */
    public function showValue()
    {
        return "<a href=\"" . $this->value . "\" target=\"_new\">" . $this->value . '</a>';
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return '&amp;o=naam&amp;l=1';
    }
}

/**
 * Class Picture
 */
class Picture extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    public function __construct($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->fieldname;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->defaultvalue;
        $this->lookuptable  = $parentObject->lookuptable;
        if ($this->lookuptable == '1') {
            new Systemmessage('No lookuptable may be specified for userfield' . $this->fieldnumber);
        }
        if ($parentObject->ViewInAdvanced == '1') {
            new Systemmessage('userfield' . $this->fieldnumber . ' cannot be shown in advanced info');
        }
        if ($parentObject->ViewInPie == '1') {
            new Systemmessage('A Pie-chart cannot be specified for userfield' . $this->fieldnumber);
        }
        if ($parentObject->ViewInList == '1') {
            new Systemmessage('userfield' . $this->fieldnumber . ' cannot be included in listview');
        }
        if ($parentObject->HasSearch == '1') {
            new Systemmessage('Search cannot be defined for userfield' . $this->fieldnumber);
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

/**
 * Class SISContext
 */
class SISContext
{
    private $contexts;
    private $depth;

    /**
     * SISContext constructor.
     */
    public function __construct()
    {
        $this->contexts = array();
        $this->depth    = 0;
    }

    /**
     * @param $url
     * @param $name
     */
    public function myGoto($url, $name)
    {
        $keys = array_keys($this->contexts);
        for ($i = 0; $i < $this->depth; ++$i) {
            if ($keys[$i] == $name) {
                $this->contexts[$name] = $url; // the url might be slightly different
                $this->depth           = $i + 1;

                for ($x = count($this->contexts); $x > $i + 1; $x--) {
                    array_pop($this->contexts);
                }

                return;
            }
        }

        $this->contexts[$name] = $url;
        $this->depth++;
    }

    /**
     * @return array
     */
    public function getAllContexts()
    {
        return $this->contexts;
    }

    /**
     * @return array
     */
    public function getAllContextNames()
    {
        return array_keys($this->contexts);
    }
}
