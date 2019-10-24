<?php
require_once __DIR__ . '/ZervWizard.php';

/**
 * Class CheckoutWizard
 */
class CheckoutWizard extends ZervWizard
{
    /**
     * CheckoutWizard constructor.
     */
    public function __construct()
    {
        global $field;
        // start the session and initialize the wizard
        if (null === $_SESSION) {
            session_start();
        }
        parent::__construct($_SESSION, __CLASS__);

        $this->addStep('fieldname', _MA_PEDIGREE_ENTER_FIELD);
        if (0 == $this->getValue('field')) { //only for a new field
            $this->addStep('fieldtype', _MA_PEDIGREE_FIELD_TYP_SEL);
            if (('selectbox' === $this->getValue('fieldtype')) || ('radiobutton' === $this->getValue('fieldtype'))) {
                $this->addStep('lookup', _MA_PEDIGREE_FIELD_ADD_VALUE);
            }
        }

        $this->addStep('Settings', _MA_PEDIGREE_FIELD_PARAM);
        if ('hassearch' === $this->getValue('hassearch')) {
            $this->addStep('search', _MA_PEDIGREE_SEARCH_PARAMFIELD);
        }
        if ('picture' !== $this->getValue('fieldtype')) {
            $this->addStep('defaultvalue', _MA_PEDIGREE_FIELD_DEFAUT);
        }
        $this->addStep('confirm', _MA_PEDIGREE_FIELDCONFIRM);
    }

    /**
     * @todo change access to fields using Pedigree\Fields
     */
    public function prepareFieldname()
    {
        global $field;
        if (0 == !$field) {
            // field already exists (editing mode)

            $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . ' WHERE id=' . $field;
            $result = $GLOBALS['xoopsDB']->query($sql);
            while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
                $name = $row['fieldname'];
                $fieldexplanation = $row['fieldexplanation'];
                $fieldtype = $row['fieldtype'];
            }
            $this->setValue('name', $name);
            $this->setValue('explain', $fieldexplanation);
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
    public function processFieldname(&$form)
    {
        $name = $this->coalesce($form['name']);
        if ('' != $name) {
            $this->setValue('name', $name);
        } else {
            $this->addError('name', _MA_PEDIGREE_FIELD_NAM);
        }

        $fieldexplanation = $this->coalesce($form['explain']);
        if ('' != $fieldexplanation) {
            $this->setValue('explain', $fieldexplanation);
        } else {
            $this->addError('explain', _MA_PEDIGREE_FIELD_EXPLAN1);
        }

        return !$this->isError();
    }

    /**
     * Setup this class' fieldtype array
     */
    public function prepareFieldtype()
    {
        $this->fieldtype[] = ['value' => 'radiobutton', 'description' => _MA_PEDIGREE_RADIOBUTTONFIELD];
        $this->fieldtype[] = ['value' => 'selectbox', 'description' => _MA_PEDIGREE_DROPDOWNFIELD];
        $this->fieldtype[] = ['value' => 'textbox', 'description' => _MA_PEDIGREE_TEXTBOXFIELD];
        $this->fieldtype[] = ['value' => 'textarea', 'description' => _MA_PEDIGREE_TEXTAREAFIELD];
        $this->fieldtype[] = ['value' => 'DateSelect', 'description' => _MA_PEDIGREE_DATEFIELD];
        $this->fieldtype[] = ['value' => 'urlfield', 'description' => _MA_PEDIGREE_URLFIELD];
    }

    /**
     * @param $form
     *
     * @return bool
     */
    public function processFieldtype($form)
    {
        $this->prepareFieldtype();
        $fieldtype = $this->coalesce($form['fieldtype']);
        $this->setValue('fieldtype', $fieldtype);

        return !$this->isError();
    }

    /**
     * @param $form
     *
     * @return bool
     */
    public function processLookup($form)
    {
        $fc = $this->coalesce($form['fc']);
        $this->setValue('fc', $fc);
        $lookup = $this->coalesce($form['lookup' . $fc]);
        $lookupid = $this->coalesce($form['id' . $fc]);
        if ('' != $lookup) {
            $this->setValue('lookup' . $fc, $lookup);
            $this->setValue('id' . $fc, $lookupid);
        }
        $lastlookup = $this->getValue('lookup' . $fc);
        if ('' == $lastlookup) {
            $this->setValue('fc', $fc - 1);
        }

        for ($i = 0; $i < $fc; ++$i) {
            $radioarray[] = [
                'id' => $this->getValue('id' . ($i + 1)),
                'value' => $this->getValue('lookup' . ($i + 1)),
            ];
        }
        //print_r($radioarray); die();
        $this->setValue('radioarray', $radioarray);

        return !$this->isError();
    }

    public function prepareSettings()
    {
        if (0 == !$this->getValue('field')) {
            // field allready exists (editing mode)

            {
                $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " WHERE id='" . $this->getValue('field') . "'";
            }
            $result = $GLOBALS['xoopsDB']->query($sql);
            while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
                $hs = $row['hassearch'];
                if ('1' == $hs) {
                    $this->setValue('hassearch', 'hassearch');
                }
                $vip = $row['viewinpedigree'];
                if ('1' == $vip) {
                    $this->setValue('viewinpedigree', 'viewinpedigree');
                }
                $via = $row['viewinadvanced'];
                if ('1' == $via) {
                    $this->setValue('viewinadvanced', 'viewinadvanced');
                }
                $vipie = $row['viewinpie'];
                if ('1' == $vipie) {
                    $this->setValue('viewinpie', 'viewinpie');
                }
                $vil = $row['viewinlist'];
                if ('1' == $vil) {
                    $this->setValue('viewinlist', 'viewinlist');
                }
                $lit = $row['litter'];
                if ('1' == $lit) {
                    $this->setValue('litter', 'litter');
                }
                $glit = $row['generallitter'];
                if ('1' == $glit) {
                    $this->setValue('generallitter', 'generallitter');
                }
            }
        }
    }

    /**
     * @param $form
     *
     * @return bool
     */
    public function processSettings($form)
    {
        $this->setValue('hassearch', $this->coalesce($form['hasSearch']));
        $this->setValue('viewinpedigree', $this->coalesce($form['viewinpedigree']));
        $this->setValue('viewinadvanced', $this->coalesce($form['viewinadvanced']));
        $this->setValue('viewinpie', $this->coalesce($form['viewinpie']));
        $this->setValue('viewinlist', $this->coalesce($form['viewinlist']));
        $this->setValue('litter', $this->coalesce($form['litter']));
        $this->setValue('generallitter', $this->coalesce($form['generallitter']));

        //if both litter and general litter are set; unset generallitter
        if (('litter' === $this->getValue('litter')) && ('generallitter' === $this->getValue('generallitter'))) {
            $this->setValue('generallitter', 0);
        }

        return !$this->isError();
    }

    public function prepareSearch()
    {
        if (0 == !$this->getValue('field')) {
            // field allready exists (editing mode)

            $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . ' WHERE id=' . $this->getValue('field');
            $result = $GLOBALS['xoopsDB']->query($sql);
            while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
                if ('hasearch' === $this->getValue('hassearch')) {
                    $searchname = $row['searchname'];
                    $this->setValue('searchname', $searchname);
                    $searchexplain = $row['searchexplanation'];
                    $this->setValue('searchexplain', $searchexplain);
                }
            }
        }
    }

    /**
     * @param $form
     *
     * @return bool
     * @todo move language strings to language files
     */
    public function processSearch($form)
    {
        $searchname = $this->coalesce($form['searchname']);
        if ('' != $searchname) {
            $this->setValue('searchname', $searchname);
        } else {
            $this->addError('searchname', 'Please enter the searchname');
        }

        $fieldexplanation = $this->coalesce($form['searchexplain']);
        if ('' != $fieldexplanation) {
            $this->setValue('searchexplain', $fieldexplanation);
        } else {
            $this->addError('searchexplain', 'Please enter the search explanation for this field');
        }

        return !$this->isError();
    }

    public function prepareDefaultvalue()
    {
        if (0 == !$this->getValue('field')) {
            // field allready exists (editing mode)

            $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . ' WHERE id=' . $this->getValue('field');
            $result = $GLOBALS['xoopsDB']->query($sql);
            while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
                $def = $row['DefaultValue'];
                $this->setValue('defaultvalue', $def);
                if ('1' == $row['LookupTable']) { //we have a lookup table; load values
                    $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $this->getValue('field')) . " ORDER BY 'order'";
                    $fc = 0;
                    $result = $GLOBALS['xoopsDB']->query($sql);
                    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
                        $radioarray[] = ['id' => $row['id'], 'value' => $row['value']];
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
     * @todo move language string to language file
     */
    public function processDefaultValue($form)
    {
        $defaultvalue = $this->coalesce($form['defaultvalue']);
        if (mb_strlen($defaultvalue) >= 0) {
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
    public function processConfirm($form)
    {
        return !$this->isError();
    }

    public function completeCallback()
    {
        //can this field be searched
        $search = $this->getValue('hassearch');
        if ('hassearch' === $search) {
            $search = '1';
            $searchname = $this->getValue('searchname');
            $searchexplain = $this->getValue('searchexplain');
        } else {
            $search = '0';
            $searchname = '';
            $searchexplain = '';
        }
        //show in pedigree
        $viewinpedigree = $this->getValue('viewinpedigree');
        if ('viewinpedigree' === $viewinpedigree) {
            $viewinpedigree = '1';
        } else {
            $viewinpedigree = '0';
        }
        //show in advanced
        $viewinadvanced = $this->getValue('viewinadvanced');
        if ('viewinadvanced' === $viewinadvanced) {
            $viewinadvanced = '1';
        } else {
            $viewinadvanced = '0';
        }
        //show in pie
        $viewinpie = $this->getValue('viewinpie');
        if ('viewinpie' === $viewinpie) {
            $viewinpie = '1';
        } else {
            $viewinpie = '0';
        }
        //view in list
        $viewinlist = $this->getValue('viewinlist');
        if ('viewinlist' === $viewinlist) {
            $viewinlist = '1';
        } else {
            $viewinlist = '0';
        }
        //add a litter?
        $litter = ('litter' === $this->getValue('litter')) ? '1' : '0';

        //general litter
        $generallitter = ('generallitter' === $this->getValue('generalLitter')) ? '1' : '0';

        if (0 == !$this->getValue('field')) {
            // field allready exists (editing mode)

            //@todo refactor using class methods
            $sql = 'UPDATE '
                   . $GLOBALS['xoopsDB']->prefix('pedigree_fields')
                   . " SET fieldname = '"
                   . htmlspecialchars($this->getValue('name'), ENT_QUOTES | ENT_HTML5)
                   . "', fieldtype = '"
                   . $this->getValue('fieldtype')
                   . "', defaultvalue = '"
                   . $this->getValue('defaultvalue')
                   . "', fieldexplanation = '"
                   . $this->getValue('explain')
                   . "', hassearch = '"
                   . $search
                   . "', litter = '"
                   . $litter
                   . "', generallitter = '"
                   . $generallitter
                   . "', searchname = '"
                   . $searchname
                   . "', searchexplanation = '"
                   . $searchexplain
                   . "', viewinpedigree = '"
                   . $viewinpedigree
                   . "', viewinadvanced = '"
                   . $viewinadvanced
                   . "', viewinpie = '"
                   . $viewinpie
                   . "', viewinlist = '"
                   . $viewinlist
                   . "' WHERE id ='"
                   . $this->getValue('field')
                   . "'";
            $GLOBALS['xoopsDB']->queryF($sql);
            //possible change defaultvalue for userfield
            $sql = 'ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' CHANGE `user' . $this->getValue('field') . '` `user' . $this->getValue('field') . "` VARCHAR( 255 ) NOT NULL DEFAULT '" . $this->getValue('defaultvalue') . "'";
            $GLOBALS['xoopsDB']->queryF($sql);
            $sql = 'ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' CHANGE `user' . $this->getValue('field') . '` `user' . $this->getValue('field') . "` VARCHAR( 1024 ) NOT NULL DEFAULT '" . $this->getValue('defaultvalue') . "'";
            $GLOBALS['xoopsDB']->queryF($sql);
            $sql = 'ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' CHANGE `user' . $this->getValue('field') . '` `user' . $this->getValue('field') . "` VARCHAR( 255 ) NOT NULL DEFAULT '" . $this->getValue('defaultvalue') . "'";
            $GLOBALS['xoopsDB']->queryF($sql);
        } else { //this is a new field
            $sql = 'SELECT MAX(id) AS lid FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . ' LIMIT 1';
            $result = $GLOBALS['xoopsDB']->query($sql);
            while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
                $nextfieldnum = $row['lid'] + 1;
            }
            //add userfield to various tables as a new field.
            //always add at the end of the table
            $tables = ['pedigree_tree', 'pedigree_temp', 'pedigree_trash'];
            foreach ($tables as $table) {
                $SQL = 'ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix($table) . ' ADD `user' . $nextfieldnum . "` VARCHAR( 255 ) NOT NULL DEFAULT '" . $this->getValue('defaultvalue') . "'";
                $GLOBALS['xoopsDB']->queryF($SQL);
            }
            //is a lookup table present
            $lookup = $this->getValue('lookup1');
            if ('' == $lookup) {
                $lookup = '0';
            } else {
                $lookup = '1';
                //create table for lookupfield
                $createtable = 'CREATE TABLE ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $nextfieldnum) . ' (`id` INT( 10 ) NOT NULL ,`value` VARCHAR( 255 ) NOT NULL, `order` INT( 10 )) ENGINE = MyISAM';
                $GLOBALS['xoopsDB']->queryF($createtable);
                //fill table
                $count = $this->getValue('fc');
                for ($x = 1; $x < $count + 1; ++$x) {
                    $y = $x - 1;
                    $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $nextfieldnum) . " ( `id` , `value`, `order`) VALUES ('" . $y . "', '" . $this->getValue('lookup' . $x) . "','" . $y . "')";
                    $GLOBALS['xoopsDB']->queryF($sql);
                }
            }

            //Insert new record into pedigree_fields db table
            //            $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " VALUES ('" . $nextfieldnum . "', '1', '" . htmlspecialchars($this->getValue('name')) . "', '" . $this->getValue('fieldtype') . "', '" . $lookup . "', '" . $this->getValue('defaultvalue') . "', '" . $this->getValue('explain') . "', '" . $search . "', '" . $Litter . "', '" . $generalLitter . "', '" . $searchname . "', '" . $searchexplain . "', '" . $viewinpedigree . "', '" . $viewinadvanced . "', '" . $viewinpie . "', '" . $viewinlist . "','','" . $nextfieldnum . "')";
            $sql = 'INSERT INTO '
                   . $GLOBALS['xoopsDB']->prefix('pedigree_fields')
                   . " VALUES ('"
                   . $nextfieldnum
                   . "', '1', '"
                   . $GLOBALS['xoopsDB']->escape(htmlspecialchars($this->getValue('name'), ENT_QUOTES | ENT_HTML5))
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($this->getValue('fieldtype'))
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($lookup)
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($this->getValue('defaultvalue'))
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($this->getValue('explain'))
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($search)
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($Litter)
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($generallitter)
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($searchname)
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($searchexplain)
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($viewinpedigree)
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($viewinadvanced)
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($viewinpie)
                   . "', '"
                   . $GLOBALS['xoopsDB']->escape($viewinlist)
                   . "','','"
                   . $GLOBALS['xoopsDB']->escape($nextfieldnum)
                   . "')";
            $GLOBALS['xoopsDB']->queryF($sql);
        }
    }

    /**
     * Miscellaneous utility functions
     *
     * @param $email
     *
     * @return int
     */
    public function isValidEmail($email)
    {
        return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i', $email);
    }
}
