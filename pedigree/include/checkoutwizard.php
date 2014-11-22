<?php
require_once 'wizard.php';

/**
 * Class CheckoutWizard
 */
class CheckoutWizard extends ZervWizard
{
    function CheckoutWizard()
    {
        global $field;
        // start the session and initialize the wizard
        if (!isset($_SESSION)) {
            session_start();
        }
        parent::ZervWizard($_SESSION, __CLASS__);

        $this->addStep('Fieldname', _MA_PEDIGREE_ENTER_FIELD);
        if ($this->getValue('field') == 0) { //only for a new field
            $this->addStep('Fieldtype', _MA_PEDIGREE_FIELD_TYP_SEL);
            if ($this->getValue('fieldtype') == 'selectbox' || $this->getValue('fieldtype') == 'radiobutton') {
                $this->addStep('lookup', _MA_PEDIGREE_FIELD_ADD_VALUE);
            }
        }

        $this->addStep('Settings', _MA_PEDIGREE_FIELD_PARAM);
        if ($this->getValue('hassearch') == 'hassearch') {
            $this->addStep('search', _MA_PEDIGREE_SEARCH_PARAMFIELD);
        }
        if ($this->getValue('fieldtype') != 'picture') {
            $this->addStep('defaultvalue', _MA_PEDIGREE_FIELD_DEFAUT);
        }
        $this->addStep('confirm', _MA_PEDIGREE_FIELDCONFIRM);
    }

    function prepare_Fieldname()
    {
        global $xoopsDB, $field;
        if (!$field == 0) // field allready exists (editing mode)
        {
            $sql    = "SELECT * from " . $xoopsDB->prefix("pedigree_fields") . " WHERE ID=" . $field;
            $result = $xoopsDB->query($sql);
            while ($row = $xoopsDB->fetchArray($result)) {
                $name             = $row['FieldName'];
                $fieldexplenation = $row['FieldExplenation'];
                $fieldtype        = $row['FieldType'];
            }
            $this->setValue('name', $name);
            $this->setValue('explain', $fieldexplenation);
            //set the fieldtype because we wont allow it to be edited
            $this->setValue('fieldtype', $fieldtype);

        }
        $this->setValue('field', $field); //is it a new field or are we editing a field
    }

    /**
     * @param $form
     *
     * @return bool
     */
    function process_Fieldname(&$form)
    {
        $name = $this->coalesce($form['name']);
        if (strlen($name) > 0) {
            $this->setValue('name', $name);
        } else {
            $this->addError('name', _MA_PEDIGREE_FIELD_NAM);
        }

        $fieldexplenation = $this->coalesce($form['explain']);
        if (strlen($fieldexplenation) > 0) {
            $this->setValue('explain', $fieldexplenation);
        } else {
            $this->addError('explain', _MA_PEDIGREE_FIELD_EXPLAN1);
        }

        return !$this->isError();
    }

    function prepare_Fieldtype()
    {
        $this->fieldtype[] = array('value' => "radiobutton", 'description' => "Radiobutton");
        $this->fieldtype[] = array('value' => "selectbox", 'description' => _MA_PEDIGREE_DROPDOWNFIELD);
        $this->fieldtype[] = array('value' => "textbox", 'description' => _MA_PEDIGREE_TEXTBOXFIELD);
        $this->fieldtype[] = array('value' => "textarea", 'description' => _MA_PEDIGREE_TEXTAREAFIELD);
        $this->fieldtype[] = array('value' => "dateselect", 'description' => _MA_PEDIGREE_DATEFIELD);
        $this->fieldtype[] = array('value' => "urlfield", 'description' => _MA_PEDIGREE_URLFIELD);
    }

    /**
     * @param $form
     *
     * @return bool
     */
    function process_Fieldtype(&$form)
    {
        $this->prepare_Fieldtype();
        $fieldtype = $this->coalesce($form['fieldtype']);
        $this->setValue('fieldtype', $fieldtype);

        return !$this->isError();
    }

    /**
     * @param $form
     *
     * @return bool
     */
    function process_lookup(&$form)
    {

        $fc = $this->coalesce($form['fc']);
        $this->setValue('fc', $fc);
        $lookup   = $this->coalesce($form['lookup' . $fc]);
        $lookupid = $this->coalesce($form['id' . $fc]);
        if (strlen($lookup) > 0) {
            $this->setValue('lookup' . $fc, $lookup);
            $this->setValue('id' . $fc, $lookupid);
        }
        $lastlookup = $this->getValue('lookup' . $fc);
        if ($lastlookup == "") {
            $this->setValue('fc', $fc - 1);
        }

        for ($i = 0; $i < $fc; ++$i) {
            $radioarray[] = array('id' => $this->getValue('id' . ($i+1)), 'value' => $this->getValue('lookup' . ($i+1)));
        }
        //print_r($radioarray); die();
        $this->setValue('radioarray', $radioarray);

        return !$this->isError();
        //
    }

    function prepare_Settings()
    {
        global $xoopsDB;
        if (!$this->getValue('field') == 0) // field allready exists (editing mode)
        {
            {
                $sql = "SELECT * from " . $xoopsDB->prefix("pedigree_fields") . " WHERE ID='" . $this->getValue('field') . "'";
            }
            $result = $xoopsDB->query($sql);
            while ($row = $xoopsDB->fetchArray($result)) {
                $hs = $row['HasSearch'];
                if ($hs == "1") {
                    $this->setValue('hassearch', "hassearch");
                }
                $vip = $row['ViewInPedigree'];
                if ($vip == "1") {
                    $this->setValue('viewinpedigree', "viewinpedigree");
                }
                $via = $row['ViewInAdvanced'];
                if ($via == "1") {
                    $this->setValue('viewinadvanced', "viewinadvanced");
                }
                $vipie = $row['ViewInPie'];
                if ($vipie == "1") {
                    $this->setValue('viewinpie', "viewinpie");
                }
                $vil = $row['ViewInList'];
                if ($vil == "1") {
                    $this->setValue('viewinlist', "viewinlist");
                }
                $lit = $row['Litter'];
                if ($lit == "1") {
                    $this->setValue('Litter', "Litter");
                }
                $Glit = $row['Generallitter'];
                if ($Glit == "1") {
                    $this->setValue('Generallitter', "Generallitter");
                }
            }
        }
    }

    /**
     * @param $form
     *
     * @return bool
     */
    function process_Settings(&$form)
    {
        $hassearch = $this->coalesce($form['hassearch']);
        $this->setValue('hassearch', $hassearch);
        $viewinpedigree = $this->coalesce($form['viewinpedigree']);
        $this->setValue('viewinpedigree', $viewinpedigree);
        $viewinadvanced = $this->coalesce($form['viewinadvanced']);
        $this->setValue('viewinadvanced', $viewinadvanced);
        $viewinpie = $this->coalesce($form['viewinpie']);
        $this->setValue('viewinpie', $viewinpie);
        $viewinlist = $this->coalesce($form['viewinlist']);
        $this->setValue('viewinlist', $viewinlist);
        $Litter = $this->coalesce($form['Litter']);
        $this->setValue('Litter', $Litter);
        $Generallitter = $this->coalesce($form['Generallitter']);
        $this->setValue('Generallitter', $Generallitter);

        //if both litter and general litter are set; unset one of them
        if ($this->getValue('Litter') == "Litter" && $this->getValue('Generallitter') == "Generallitter") {
            $this->setValue('Generallitter', 0);
        }

        return !$this->isError();
    }

    function prepare_search()
    {
        global $xoopsDB;
        if (!$this->getValue('field') == 0) // field allready exists (editing mode)
        {
            $sql    = "SELECT * from " . $xoopsDB->prefix("pedigree_fields") . " WHERE ID=" . $this->getValue('field');
            $result = $xoopsDB->query($sql);
            while ($row = $xoopsDB->fetchArray($result)) {
                if ($this->getValue('hassearch') == "hassearch") {
                    $searchname = $row['SearchName'];
                    $this->setValue('searchname', $searchname);
                    $searchexplain = $row['SearchExplenation'];
                    $this->setValue('searchexplain', $searchexplain);
                }
            }
        }
    }

    /**
     * @param $form
     *
     * @return bool
     */
    function process_search(&$form)
    {
        $searchname = $this->coalesce($form['searchname']);
        if (strlen($searchname) > 0) {
            $this->setValue('searchname', $searchname);
        } else {
            $this->addError('searchname', 'Please enter the searchname');
        }

        $fieldexplenation = $this->coalesce($form['searchexplain']);
        if (strlen($fieldexplenation) > 0) {
            $this->setValue('searchexplain', $fieldexplenation);
        } else {
            $this->addError('searchexplain', 'Please enter the search explanation for this field');
        }

        return !$this->isError();
    }

    function prepare_defaultvalue()
    {
        global $xoopsDB;
        if (!$this->getValue('field') == 0) // field allready exists (editing mode)
        {
            $sql    = "SELECT * from " . $xoopsDB->prefix("pedigree_fields") . " WHERE ID=" . $this->getValue('field');
            $result = $xoopsDB->query($sql);
            while ($row = $xoopsDB->fetchArray($result)) {
                $def = $row['DefaultValue'];
                $this->setValue('defaultvalue', $def);
                if ($row['LookupTable'] == "1") { //we have a lookup table; load values
                    $sql    = "SELECT * from " . $xoopsDB->prefix("pedigree_lookup" . $this->getValue('field')) . " order by 'order'";
                    $fc     = 0;
                    $result = $xoopsDB->query($sql);
                    while ($row = $xoopsDB->fetchArray($result)) {
                        $radioarray[] = array('id' => $row['ID'], 'value' => $row['value']);
                        ++$fc;
                    }
                    $this->setValue('radioarray', $radioarray);
                    $this->setValue('fc', $fc);
                }
            }

        }
    }

    /**
     * @param $form
     *
     * @return bool
     */
    function process_defaultvalue(&$form)
    {
        $defaultvalue = $this->coalesce($form['defaultvalue']);
        if (strlen($defaultvalue) >= 0) {
            $this->setValue('defaultvalue', $defaultvalue);
        } else {
            $this->addError('defaultvalue', 'Please enter a defaultvalue');
        }

        return !$this->isError();
    }

    /**
     * @param $form
     *
     * @return bool
     */
    function process_confirm(&$form)
    {
        return !$this->isError();
    }

    function completeCallback()
    {
        global $xoopsDB;
        //can this field be searched
        $search = $this->getValue('hassearch');
        if ($search == "hassearch") {
            $search        = "1";
            $searchname    = $this->getValue('searchname');
            $searchexplain = $this->getValue('searchexplain');
        } else {
            $search        = "0";
            $searchname    = "";
            $searchexplain = "";
        }
        //show in pedigree
        $viewinpedigree = $this->getValue('viewinpedigree');
        if ($viewinpedigree == "viewinpedigree") {
            $viewinpedigree = "1";
        } else {
            $viewinpedigree = "0";
        }
        //show in advanced
        $viewinadvanced = $this->getValue('viewinadvanced');
        if ($viewinadvanced == "viewinadvanced") {
            $viewinadvanced = "1";
        } else {
            $viewinadvanced = "0";
        }
        //show in pie
        $viewinpie = $this->getValue('viewinpie');
        if ($viewinpie == "viewinpie") {
            $viewinpie = "1";
        } else {
            $viewinpie = "0";
        }
        //view in list
        $viewinlist = $this->getValue('viewinlist');
        if ($viewinlist == "viewinlist") {
            $viewinlist = "1";
        } else {
            $viewinlist = "0";
        }
        //add a litter
        $Litter = $this->getValue('Litter');
        if ($Litter == "Litter") {
            $Litter = "1";
        } else {
            $Litter = "0";
        }
        //general litter
        $Generallitter = $this->getValue('Generallitter');
        if ($Generallitter == "Generallitter") {
            $Generallitter = "1";
        } else {
            $Generallitter = "0";
        }

        if (!$this->getValue('field') == 0) // field allready exists (editing mode)
        {
            $sql = "UPDATE " . $xoopsDB->prefix("pedigree_fields") . " SET FieldName = '" . htmlSpecialChars($this->getValue('name')) . "', FieldType = '" . $this->getValue('fieldtype')
                . "', DefaultValue = '" . $this->getValue('defaultvalue') . "', FieldExplenation = '" . $this->getValue('explain') . "', HasSearch = '" . $search . "', Litter = '" . $Litter
                . "', Generallitter = '" . $Generallitter . "', SearchName = '" . $searchname . "', SearchExplenation = '" . $searchexplain . "', ViewInPedigree = '" . $viewinpedigree
                . "', ViewInAdvanced = '" . $viewinadvanced . "', ViewInPie = '" . $viewinpie . "', ViewInList = '" . $viewinlist . "' WHERE ID ='" . $this->getValue('field') . "'";
            mysql_query($sql);
            //possible change defaultvalue for userfield
            $sql
                = "ALTER TABLE " . $xoopsDB->prefix("pedigree_tree") . " CHANGE `user" . $this->getValue('field') . "` `user" . $this->getValue(
                    'field'
                ) . "` VARCHAR( 255 ) NOT NULL DEFAULT '" . $this->getValue('defaultvalue') . "'";
            mysql_query($sql);
            $sql
                = "ALTER TABLE " . $xoopsDB->prefix("pedigree_temp") . " CHANGE `user" . $this->getValue('field') . "` `user" . $this->getValue(
                    'field'
                ) . "` VARCHAR( 1024 ) NOT NULL DEFAULT '" . $this->getValue('defaultvalue') . "'";
            mysql_query($sql);
            $sql = "ALTER TABLE " . $xoopsDB->prefix("pedigree_trash") . " CHANGE `user" . $this->getValue('field') . "` `user" . $this->getValue(
                    'field'
                ) . "` VARCHAR( 255 ) NOT NULL DEFAULT '" . $this->getValue('defaultvalue') . "'";
            mysql_query($sql);
        } else { //this is a new field
            $sql    = "SELECT MAX(ID) AS lid from " . $xoopsDB->prefix("pedigree_fields") . " LIMIT 1";
            $result = $xoopsDB->query($sql);
            while ($row = $xoopsDB->fetchArray($result)) {
                $nextfieldnum = $row['lid'] + 1;
            }
            //add userfield to various tables as a new field.
            //allways add at the end of the table
            $tables = array('pedigree_tree', 'pedigree_temp', 'pedigree_trash');
            foreach ($tables as $table) {
                $SQL = "ALTER TABLE " . $xoopsDB->prefix($table) . " ADD `user" . $nextfieldnum . "` VARCHAR( 255 ) NOT NULL DEFAULT '" . $this->getValue(
                        'defaultvalue'
                    ) . "'";
                mysql_query($SQL);
            }
            //is a lookup table present
            $lookup = $this->getValue('lookup1');
            if ($lookup == "") {
                $lookup = "0";
            } else {
                $lookup = "1";
                //create table for lookupfield
                $createtable = "CREATE TABLE " . $xoopsDB->prefix("pedigree_lookup" . $nextfieldnum) . " (`ID` INT( 10 ) NOT NULL ,`value` VARCHAR( 255 ) NOT NULL, `order` INT( 10 )) ENGINE = MyISAM";
                mysql_query($createtable);
                //fill table
                $count = $this->getValue('fc');
                for ($x = 1; $x < $count + 1; ++$x) {
                    $y = $x - 1;
                    $sql = "INSERT INTO " . $xoopsDB->prefix("pedigree_lookup" . $nextfieldnum) . " ( `ID` , `value`, `order`) VALUES ('" . $y . "', '" . $this->getValue('lookup' . $x) . "','" . $y
                        . "')";
                    mysql_query($sql);
                }

            }

            //Insert new record into pedigree_config
            $sql = "INSERT INTO " . $xoopsDB->prefix("pedigree_fields") . " VALUES ('" . $nextfieldnum . "', '1', '" . htmlSpecialChars(
                    $this->getValue('name')
                ) . "', '" . $this->getValue('fieldtype') . "', '" . $lookup . "', '" . $this->getValue('defaultvalue') . "', '" . $this->getValue(
                    'explain'
                ) . "', '" . $search . "', '" . $Litter . "', '" . $Generallitter . "', '" . $searchname . "', '" . $searchexplain . "', '" . $viewinpedigree . "', '" . $viewinadvanced . "', '"
                . $viewinpie . "', '" . $viewinlist . "','','" . $nextfieldnum . "')";
            mysql_query($sql);
        }
    }

    /**
     * Miscellaneous utility functions
     *
     * @param $email
     *
     * @return int
     */

    function isValidEmail($email)
    {
        return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i', $email);
    }
}
