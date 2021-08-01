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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GPL 2.0 or later
 * @package         \XoopsModules\Pedigree\Class
 * @since
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */
use XoopsModules\Pedigree;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Pedigree\OwnerHandler
 */
class OwnerHandler extends \XoopsPersistableObjectHandler
{
    use CountOverload;  // changed getCount() and getCounts() return values to integers

    /**
     * @param null|object|\XoopsDatabase $db
     */
    public function __construct(?\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'pedigree_owner', Owner::class, 'id', 'firstname');
    }

    /**
     * Get criteria for owners sorted by lastname, firstname
     *
     * @return \CriteriaCompo
     */
    public function getActiveCriteria(): \CriteriaCompo
    {
        //$grouppermHandler = xoops_getHandler('groupperm');
        //$criteria = new \CriteriaCompo(new \Criteria('offline', false));
        //$criteria->add(new \Criteria('published', 0, '>'));
        //$criteria->add(new \Criteria('published', time(), '<='));
        //$expiredCriteria = new \CriteriaCompo(new \Criteria('expired', 0));
        //$expiredCriteria->add(new \Criteria('expired', time(), '>='), 'OR');
        //$criteria->add($expiredCriteria);
        // add criteria for categories that the user has permissions for
        //$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
        //$allowedDownCategoriesIds = $grouppermHandler->getItemIds('WFDownCatPerm', $groups, $this->wfdownloads->getModule()->mid());
        //$criteria->add(new \Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'));

        $criteria = new \CriteriaCompo();
        $criteria->setSort('lastname ASC, firstname');
        $criteria->setOrder('ASC');

        return $criteria;
    }
}
