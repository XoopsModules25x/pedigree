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
 * Module: Pedigree
 *
 * @category        Module
 * @package         pedigree
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use Xmf\Module\Helper\Permission;
use XoopsModules\Pedigree\{
    Helper
};


//$permHelper = new \Xmf\Module\Helper\Permission();

/**
 * Class Registry
 */
class Registry extends \XoopsObject
{
    public $helper;
    public $permHelper;

    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        /** @var Pedigree\Helper $helper */
        $this->helper     = Helper::getInstance();
        $this->permHelper = new Permission();

        $this->initVar('id', \XOBJ_DTYPE_INT);
        $this->initVar('pname', \XOBJ_DTYPE_OTHER);
        $this->initVar('id_owner', \XOBJ_DTYPE_INT);
        $this->initVar('id_breeder', \XOBJ_DTYPE_INT);
        $this->initVar('user', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('roft', \XOBJ_DTYPE_ENUM);
        $this->initVar('mother', \XOBJ_DTYPE_INT);
        $this->initVar('father', \XOBJ_DTYPE_INT);
        $this->initVar('foto', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('coi', \XOBJ_DTYPE_TXTBOX);
    }

    /**
     * Get form
     *
     * @param null
     * @return Pedigree\Form\RegistryForm
     */
    public function getForm()
    {
        $form = new Form\RegistryForm($this);

        return $form;
    }

    /**
     * @return array|null
     */
    public function getGroupsRead()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem('sbcolumns_read', $this->getVar('id'));
    }

    /**
     * @return array|null
     */
    public function getGroupsSubmit()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem('sbcolumns_submit', $this->getVar('id'));
    }

    /**
     * @return array|null
     */
    public function getGroupsModeration()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem('sbcolumns_moderation', $this->getVar('id'));
    }
}
