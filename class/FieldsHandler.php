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
 * Pedigree module for XOOPS
 *
 * @copyright       {@link https://xoops.org/ The XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @author          XOOPS Module Dev Team
 */
use XoopsModules\Pedigree;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Pedigree\FieldsHandler
 *
 * @param object $db reference to the {@link XoopsDatabase} object
 */
class FieldsHandler extends \XoopsPersistableObjectHandler
{
    use CountOverload;  // changed getCount() and getCounts() return values to integers

    /**
     * @param null|object|\XoopsDatabase $db
     */
    public function __construct(?\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'pedigree_fields', Fields::class, 'id', 'fieldname');
    }

    /**
     * @param int $fieldnumber
     *
     * @return array
     */
    public function lookupField(int $fieldnumber): array
    {
        $ret = [];
        $query = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $fieldnumber) . " ORDER BY 'order'";
        $result = $GLOBALS['xoopsDB']->query($query);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $ret[] = ['id' => $row['id'], 'value' => $row['value']];
        }

        //array_multisort($ret,SORT_ASC);
        return $ret;
    }
}
