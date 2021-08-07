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
 * @package         XoopsModules\Pedigree
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GPL 2.0 or later
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */

\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Pedigree\TrashHandler
 */
class TrashHandler extends \XoopsPersistableObjectHandler
{
    use CountOverload;

    /**
     * @param null|object|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'pedigree_trash', Trash::class, 'id', 'pname');
    }
}
