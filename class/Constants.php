<?php
namespace XoopsModules\Pedigree;

/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module: Pedigree
 *
 * @package   XoopsModules\Pedigree
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since     1.32
 */

/**
 * Interface \XoopsModules\Pedigree\Constants
 */
interface Constants
{
    /**#@+
     * Constant definition
     */

    /**
     * XOOPSTOKEN Request Timeout value
     */
    const TOKEN_TIMEOUT = 360;
    /**
     * Male animal value
     */
    const MALE = 0;
    /**
     * Female animal value
     */
    const FEMALE = 1;
    /**
     * default forms to show per page in lists
     */
    const PER_PAGE_DEFAULT = 10;
    /**
     * Percent precision - digits after decimal point
     */
    const PCT_PRECISION = 2;
    /**
     * no delay XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_NONE = 0;
    /**
     * short XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_SHORT = 1;
    /**
     * medium XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_MEDIUM = 3;
    /**
     * long XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_LONG = 7;
    /**
     * confirm not ok to take action
     */
    const CONFIRM_NOT_OK = 0;
    /**
     * confirm ok to take action
     */
    const CONFIRM_OK = 1;
    /**#@-*/
}
