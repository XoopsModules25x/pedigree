<?php

namespace XoopsModules\Pedigree;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Pedigree\Field Class
 *
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      lucio <lucio.rota@gmail.com>
 * @package     Pedigree
 * @since       1.31
 */
use XoopsModules\Pedigree;

/**
 * Class Field
 */
class Field
{
    protected $id;

    /**
     * @param int   $fieldnumber
     * @param array $config
     */
    public function __construct(int $fieldnumber, array $config)
    {
        //find key where id = $fieldnumber;
        $configCount = count($config);
        foreach ($config as $x => $xValue) {
            /**
             * @TODO - figure out if this is suppose to be an assignment or just a compare ('=' or '=='), set to compare in v1.32
             */
            if ($config[$x]['id'] == $fieldnumber) {
                foreach ($config[$x] as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
        $this->id = $fieldnumber;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return '1' == $this->getSetting('isactive');
    }

    /**
     * @return bool
     */
    public function inAdvanced(): bool
    {
        return (1 == $this->getSetting('viewinadvanced'));
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return (1 == $this->getSetting('locked'));
    }

    /**
     * @return bool
     */
    public function hasSearch(): bool
    {
        return (1 == $this->getSetting('hassearch'));
    }

    /**
     * @return bool
     */
    public function addLitter(): bool
    {
        return (1 == $this->getSetting('litter'));
    }

    /**
     * @return bool
     */
    public function generalLitter(): bool
    {
        return (1 == $this->getSetting('generallitter'));
    }

    /**
     * @return bool
     */
    public function hasLookup(): bool
    {
        return (1 == $this->getSetting('lookuptable'));
    }

    /**
     * @return string
     */
    public function getSearchString(): string
    {
        return '&amp;o=naam&amp;p';
    }

    /**
     * @return bool
     */
    public function inPie(): bool
    {
        return (1 == $this->getSetting('viewinpie'));
    }

    /**
     * @return bool
     */
    public function inPedigree(): bool
    {
        return (1 == $this->getSetting('viewinpedigree'));
    }

    /**
     * @return bool
     */
    public function inList(): bool
    {
        return (1 == $this->getSetting('viewinlist'));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $setting
     *
     * @return mixed
     */
    public function getSetting(string $setting)
    {
        return $this->{$setting};
    }

    /**
     * @param $fieldnumber
     *
     * @return array
     */
    public function lookupField(int $fieldnumber): array
    {
        $ret = [];
        /** @var \XoopsMySQLDatabase $GLOBALS['xoopsDB'] */
        $tableName = $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . (string) $fieldnumber);
        $query     =  "SELECT * FROM `{$tableName}` ORDER BY 'order'";
        $result    = $GLOBALS['xoopsDB']->query($query);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            /** @TODO investigate using $GLOBALS['xoopsDB']->getFieldType() to type cast 'value' */
            $ret[] = ['id' => (int) $row['id'], 'value' => $row['value']];
        }

        /** @var \Xmf\Database\Tables $pTables */
/*
        $pTables = new \Xmf\Database\Tables();
        $exists = $pTables->useTable('pedigree_lookup' . $fieldnumber);
        if ($exists) {
            $tableName = $pTables->name('pedigree_lookup' . $fieldnumber);
            $SQL = "SELECT * FROM `{$tableName}` ORDER BY 'order'";
            //$SQL    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix("pedigree_lookup{$fieldnumber}") . " ORDER BY 'order'";
            $result = $GLOBALS['xoopsDB']->query($SQL);
            while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
                $ret[] = ['id' => $row['id'], 'value' => $row['value']];
            }
        }
*/
        //array_multisort($ret,SORT_ASC);
        return $ret;
    }

    /**
     * @return \XoopsFormLabel
     */
    public function viewField(): \XoopsFormLabel
    {
        $view = new \XoopsFormLabel($this->fieldname, $this->value);

        return $view;
    }

    /**
     * @return string
     */
    public function showField(): string
    {
        return $this->fieldname . ' : ' . $this->value;
    }

    /**
     * @return string
     */
    public function showValue(): string
    {
        $myts = \MyTextSanitizer::getInstance();

        return $myts->displayTarea($this->value);
    }

    /**
     * @return string
     */
    public function searchField(): string
    {
        /** @TODO look at possibly using HTML5 type='search' here */
        return '<input type="text" name="query" size="20">';
    }
}
