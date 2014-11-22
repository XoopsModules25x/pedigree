<?php

/**
 * Class Systemmessage
 */
class Systemmessage
{
    /**
     * @param $message
     */
    function Systemmessage($message)
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
     * @param int $animalnumber
     */
    function Animal($animalnumber = 0)
    {
        global $xoopsDB;
        if ($animalnumber == 0) {
            $SQL = "SELECT * from " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID = '1'";
        } else {
            $SQL = "SELECT * from " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID = " . $animalnumber;
        }
        $result    = $xoopsDB->query($SQL);
        $row       = $xoopsDB->fetchRow($result);
        $numfields = mysql_num_fields($result);
        for ($i = 0; $i < $numfields; ++$i) {
            $key        = mysql_field_name($result, $i);
            $this->$key = $row[$i];
        }
    }

    /**
     * @return array
     */
    function numoffields()
    {
        global $xoopsDB;
        $SQL    = "SELECT * from " . $xoopsDB->prefix("pedigree_fields") . " ORDER BY `order`";
        $fields = array();
        $result = $xoopsDB->query($SQL);
        $count  = 0;
        while ($row = $xoopsDB->fetchArray($result)) {
            $fields[] = $row['ID'];
            ++$count;
            $configvalues[] = $row;

        }
        $this->configvalues = isset($configvalues) ? $configvalues : '';
        //print_r ($this->configvalues); die();
        return $fields;
    }

    function getconfig()
    {
        return $this->configvalues;
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
    function Field($fieldnumber, $config)
    {
        //find key where ID = $fieldnumber;
        for ($x = 0; $x < count($config); ++$x) {
            if ($config[$x]['ID'] == $fieldnumber) {
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
    function active()
    {
        $active = $this->getSetting("isActive");
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    function inadvanced()
    {
        $active = $this->getSetting("ViewInAdvanced");
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    function islocked()
    {
        $active = $this->getSetting("locked");
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    function hassearch()
    {
        $active = $this->getSetting("HasSearch");
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    function addlitter()
    {
        $active = $this->getSetting("Litter");
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    function generallitter()
    {
        $active = $this->getSetting("Generallitter");
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    function haslookup()
    {
        $active = $this->getSetting("LookupTable");
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    function getsearchstring()
    {
        return "&amp;o=naam&amp;p";
    }

    /**
     * @return bool
     */
    function inpie()
    {
        $active = $this->getSetting("ViewInPie");
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    function inpedigree()
    {
        $active = $this->getSetting("ViewInPedigree");
        if ($active == '1') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    function inlist()
    {
        $active = $this->getSetting("ViewInList");
        if ($active == '1') {
            return true;
        }

        return false;
    }

    function getId()
    {
        return $this->id;
    }

    /**
     * @param $setting
     *
     * @return mixed
     */
    function getSetting($setting)
    {
        return $this->$setting;
    }

    /**
     * @param $fieldnumber
     *
     * @return array
     */
    function lookup($fieldnumber)
    {
        $ret = array();
        global $xoopsDB;
        $SQL    = "SELECT * from " . $xoopsDB->prefix("pedigree_lookup" . $fieldnumber) . " ORDER BY 'order'";
        $result = $xoopsDB->query($SQL);
        while ($row = $xoopsDB->fetchArray($result)) {
            $ret[] = array('id' => $row['ID'], 'value' => $row['value']);
        }
        //array_multisort($ret,SORT_ASC);
        return $ret;
    }

    /**
     * @return XoopsFormLabel
     */
    function viewField()
    {
        $view = new XoopsFormLabel($this->fieldname, $this->value);

        return $view;
    }

    /**
     * @return string
     */
    function showField()
    {
        return $this->fieldname . " : " . $this->value;
    }

    function showValue()
    {
        global $myts;

        return $myts->displayTarea($this->value);
        //return $this->value;
    }

    /**
     * @return string
     */
    function searchfield()
    {
        return '<input type="text" name="query" size="20">';
    }
}

/**
 * Class radiobutton
 */
class radiobutton extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    function radiobutton($parentObject, $animalObject)
    {
        $this->fieldnumber = $parentObject->getId();

        $this->fieldname    = $parentObject->FieldName;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->DefaultValue;
        $this->lookuptable  = $parentObject->LookupTable;
        if ($this->lookuptable == '0') {
            new Systemmessage("A lookuptable must be specified for userfield" . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormRadio
     */
    function editField()
    {
        $radio          = new XoopsFormRadio("<b>" . $this->fieldname . "</b>", 'user' . $this->fieldnumber, $value = $this->value);
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
            $radio->addOption($lookupcontents[$i]['id'], $name = ($lookupcontents[$i]['value'] . "<br />"), $disabled = false);
        }

        return $radio;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormRadio
     */
    function newField($name = "")
    {
        $radio          = new XoopsFormRadio("<b>" . $this->fieldname . "</b>", $name . 'user' . $this->fieldnumber, $value = $this->defaultvalue);
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
            $radio->addOption($lookupcontents[$i]['id'], $name = ($lookupcontents[$i]['value'] . "<br />"), $disabled = false);
        }

        return $radio;
    }

    /**
     * @return XoopsFormLabel
     */
    function viewField()
    {
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
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
    function showField()
    {
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }

        return $this->fieldname . " : " . $choosenvalue;
    }

    function showValue()
    {
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }

        return $choosenvalue;
    }

    /**
     * @return string
     */
    function searchfield()
    {
        $select         = '<select size="1" name="query" style="width: 140px;">';
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
            $select .= '<option value="' . $lookupcontents[$i]['id'] . '">' . $lookupcontents[$i]['value'] . '</option>';
        }
        $select .= '</select>';

        return $select;
    }
}

/**
 * Class selectbox
 */
class selectbox extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    function selectbox($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->FieldName;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->DefaultValue;
        $this->lookuptable  = $parentObject->LookupTable;
        if ($this->lookuptable == '0') {
            new Systemmessage("A lookuptable must be specified for userfield" . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormSelect
     */
    function editField()
    {
        $select         = new XoopsFormSelect("<b>" . $this->fieldname . "</b>", 'user' . $this->fieldnumber, $value = $this->value, $size = 1, $multiple = false);
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
            $select->addOption($lookupcontents[$i]['id'], $name = ($lookupcontents[$i]['value'] . "<br />"), $disabled = false);
        }

        return $select;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormSelect
     */
    function newField($name = "")
    {
        $select         = new XoopsFormSelect("<b>" . $this->fieldname . "</b>", $name . 'user' . $this->fieldnumber, $value = $this->defaultvalue, $size = 1, $multiple = false);
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
            $select->addOption($lookupcontents[$i]['id'], $name = ($lookupcontents[$i]['value'] . "<br />"), $disabled = false);
        }

        return $select;
    }

    /**
     * @return XoopsFormLabel
     */
    function viewField()
    {
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
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
    function showField()
    {
        $choosenvalue   = '';
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }

        return $this->fieldname . " : " . $choosenvalue;
    }

    /**
     * @return string
     */
    function showValue()
    {
        $choosenvalue   = '';
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
            if ($lookupcontents[$i]['id'] == $this->value) {
                $choosenvalue = $lookupcontents[$i]['value'];
            }
        }

        return $choosenvalue;
    }

    /**
     * @return string
     */
    function searchfield()
    {
        $select         = '<select size="1" name="query" style="width: 140px;">';
        $lookupcontents = Field::lookup($this->fieldnumber);
        for ($i = 0; $i < count($lookupcontents); ++$i) {
            $select .= '<option value="' . $lookupcontents[$i]['id'] . '">' . $lookupcontents[$i]['value'] . '</option>';
        }
        $select .= '</select>';

        return $select;
    }
}

/**
 * Class textbox
 */
class textbox extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    function textbox($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->FieldName;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->DefaultValue;
        $this->lookuptable  = $parentObject->LookupTable;
        if ($this->lookuptable == '1') {
            new Systemmessage("No lookuptable may be specified for userfield" . $this->fieldnumber);
        }
        if ($parentObject->ViewInAdvanced == '1') {
            new Systemmessage("userfield" . $this->fieldnumber . " cannot be shown in advanced info");
        }
        if ($parentObject->ViewInPie == '1') {
            new Systemmessage("A Pie-chart cannot be specified for userfield" . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormText
     */
    function editField()
    {
        $textbox = new XoopsFormText("<b>" . $this->fieldname . "</b>", 'user' . $this->fieldnumber, $size = 50, $maxsize = 50, $value = $this->value);

        return $textbox;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormText
     */
    function newField($name = "")
    {
        $textbox = new XoopsFormText("<b>" . $this->fieldname . "</b>", $name . 'user' . $this->fieldnumber, $size = 50, $maxsize = 50, $value = $this->defaultvalue);

        return $textbox;
    }

    /**
     * @return string
     */
    function getsearchstring()
    {
        return "&amp;o=naam&amp;l=1";
    }
}

/**
 * Class textarea
 */
class textarea extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    function textarea($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->FieldName;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->DefaultValue;
        if ($parentObject->LookupTable == '1') {
            new Systemmessage("No lookuptable may be specified for userfield" . $this->fieldnumber);
        }
        if ($parentObject->ViewInAdvanced == '1') {
            new Systemmessage("userfield" . $this->fieldnumber . " cannot be shown in advanced info");
        }
        if ($parentObject->ViewInPie == '1') {
            new Systemmessage("A Pie-chart cannot be specified for userfield" . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormTextArea
     */
    function editField()
    {
        $textarea = new XoopsFormTextArea("<b>" . $this->fieldname . "</b>", 'user' . $this->fieldnumber, $value = $this->value, $rows = 5, $cols = 50);

        return $textarea;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormTextArea
     */
    function newField($name = "")
    {
        $textarea = new XoopsFormTextArea("<b>" . $this->fieldname . "</b>", $name . 'user' . $this->fieldnumber, $value = $this->defaultvalue, $rows = 5, $cols = 50);

        return $textarea;
    }

    /**
     * @return string
     */
    function getsearchstring()
    {
        return "&amp;o=naam&amp;l=1";
    }
}

/**
 * Class dateselect
 */
class dateselect extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    function dateselect($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->FieldName;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->DefaultValue;
        if ($parentObject->LookupTable == '1') {
            new Systemmessage("No lookuptable may be specified for userfield" . $this->fieldnumber);
        }
        if ($parentObject->ViewInAdvanced == '1') {
            new Systemmessage("userfield" . $this->fieldnumber . " cannot be shown in advanced info");
        }
        if ($parentObject->ViewInPie == '1') {
            new Systemmessage("A Pie-chart cannot be specified for userfield" . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormTextDateSelect
     */
    function editField()
    {
        //$textarea = new XoopsFormFile("<b>".$this->fieldname."</b>", $this->fieldname, $maxfilesize = 2000);
        $textarea = new XoopsFormTextDateSelect("<b>" . $this->fieldname . "</b>", 'user' . $this->fieldnumber, $size = 15, $this->value);

        return $textarea;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormTextDateSelect
     */
    function newField($name = "")
    {
        $textarea = new XoopsFormTextDateSelect("<b>" . $this->fieldname . "</b>", $name . 'user' . $this->fieldnumber, $size = 15, $this->defaultvalue);

        return $textarea;
    }

    /**
     * @return string
     */
    function getsearchstring()
    {
        return "&amp;o=naam&amp;l=1";
    }
}

/**
 * Class urlfield
 */
class urlfield extends Field
{
    /**
     * @param $parentObject
     * @param $animalObject
     */
    function urlfield($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->FieldName;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->DefaultValue;
        $this->lookuptable  = $parentObject->LookupTable;
        if ($this->lookuptable == '1') {
            new Systemmessage("No lookuptable may be specified for userfield" . $this->fieldnumber);
        }
        if ($parentObject->ViewInAdvanced == '1') {
            new Systemmessage("userfield" . $this->fieldnumber . " cannot be shown in advanced info");
        }
        if ($parentObject->ViewInPie == '1') {
            new Systemmessage("A Pie-chart cannot be specified for userfield" . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormText
     */
    function editField()
    {
        $textbox = new XoopsFormText("<b>" . $this->fieldname . "</b>", 'user' . $this->fieldnumber, $size = 50, $maxsize = 255, $value = $this->value);

        return $textbox;
    }

    /**
     * @param string $name
     *
     * @return XoopsFormText
     */
    function newField($name = "")
    {
        $textbox = new XoopsFormText("<b>" . $this->fieldname . "</b>", $name . 'user' . $this->fieldnumber, $size = 50, $maxsize = 255, $value = $this->defaultvalue);

        return $textbox;
    }

    /**
     * @return XoopsFormLabel
     */
    function viewField()
    {
        $view = new XoopsFormLabel($this->fieldname, '<a href="' . $this->value . '" target=\"_new\">' . $this->value . '</a>');

        return $view;
    }

    /**
     * @return string
     */
    function showField()
    {
        return $this->fieldname . " : <a href=\"" . $this->value . "\" target=\"_new\">" . $this->value . "</a>";
    }

    /**
     * @return string
     */
    function showValue()
    {
        return "<a href=\"" . $this->value . "\" target=\"_new\">" . $this->value . "</a>";
    }

    /**
     * @return string
     */
    function getsearchstring()
    {
        return "&amp;o=naam&amp;l=1";
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
    function Picture($parentObject, $animalObject)
    {
        $this->fieldnumber  = $parentObject->getId();
        $this->fieldname    = $parentObject->FieldName;
        $this->value        = $animalObject->{'user' . $this->fieldnumber};
        $this->defaultvalue = $parentObject->DefaultValue;
        $this->lookuptable  = $parentObject->LookupTable;
        if ($this->lookuptable == '1') {
            new Systemmessage("No lookuptable may be specified for userfield" . $this->fieldnumber);
        }
        if ($parentObject->ViewInAdvanced == '1') {
            new Systemmessage("userfield" . $this->fieldnumber . " cannot be shown in advanced info");
        }
        if ($parentObject->ViewInPie == '1') {
            new Systemmessage("A Pie-chart cannot be specified for userfield" . $this->fieldnumber);
        }
        if ($parentObject->ViewInList == '1') {
            new Systemmessage("userfield" . $this->fieldnumber . " cannot be included in listview");
        }
        if ($parentObject->HasSearch == '1') {
            new Systemmessage("Search cannot be defined for userfield" . $this->fieldnumber);
        }
    }

    /**
     * @return XoopsFormFile
     */
    function editField()
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
    function newField($name = "")
    {
        $picturefield = new XoopsFormFile($this->fieldname, $name . 'user' . $this->fieldnumber, 1024000);
        $picturefield->setExtra("size ='50'");

        return $picturefield;
    }

    /**
     * @return XoopsFormLabel
     */
    function viewField()
    {
        $view = new XoopsFormLabel($this->fieldname, "<img src=\"" . PEDIGREE_UPLOAD_URL . "/images/thumbnails/" . $this->value . "_400.jpeg\">");

        return $view;
    }

    /**
     * @return string
     */
    function showField()
    {
        return "<img src=" . PEDIGREE_UPLOAD_URL . "\"/images/thumbnails/" . $this->value . "_150.jpeg\">";
    }

    /**
     * @return string
     */
    function showValue()
    {
        return "<img src=" . PEDIGREE_UPLOAD_URL . "\"/images/thumbnails/" . $this->value . "_400.jpeg\">";
    }
}

/**
 * Class SISContext
 */
class SISContext
{
    var $_contexts;
    var $_depth;

    function SISContext()
    {
        $this->_contexts = array();
        $this->_depth    = 0;
    }

    /**
     * @param $url
     * @param $name
     */
    function mygoto($url, $name)
    {
        $keys = array_keys($this->_contexts);
        for ($i = 0; $i < $this->_depth; ++$i) {
            if ($keys[$i] == $name) {
                $this->_contexts[$name] = $url; // the url might be slightly different
                $this->_depth           = $i + 1;
                for ($x = count($this->_contexts); $x > $i + 1; $x--) {
                    array_pop($this->_contexts);
                }

                return;
            }
        }

        $this->_contexts[$name] = $url;
        $this->_depth++;

    }

    /**
     * @return array
     */
    function getAllContexts()
    {
        return $this->_contexts;
    }

    /**
     * @return array
     */
    function getAllContextNames()
    {
        return array_keys($this->_contexts);
    }
}
