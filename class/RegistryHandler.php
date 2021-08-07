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
use XoopsDatabase;
use XoopsModules\Pedigree;
use XoopsPersistableObjectHandler;

$moduleDirName = \basename(\dirname(__DIR__));

$permHelper = new Permission();

/**
 * Class RegistryHandler
 */
class RegistryHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     * @param null|\XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db = null)
    {
        parent::__construct($db, 'pedigree_registry', Registry::class, 'id', 'pname');
    }
}
