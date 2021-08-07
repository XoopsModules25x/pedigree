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
     * DO NOT Hide YAML Sample Button in Admin
     */
    const DO_NOT_DISP_SAMPLE_BTN = 0;
    /**
     * Hide YAML Sample Button in Admin
     */
    const HIDE_SAMPLE_BTN = 1;
    /**
     * Display YAML Sample Button in Admin
     */
    const DISP_SAMPLE_BTN = 1;
    /**
     * Male animal value
     */
    const MALE = 0;
    /**
     * Female animal value
     */
    const FEMALE = 1;
    /**
     * Default gender
     */
    const DEFAULT_ROFT = Constants::MALE;
    /**
     * Default tree ID
     */
    const DEFAULT_TREE_ID = 1;
    /**
     * default items to show per page in lists
     */
    const DEFAULT_PER_PAGE = 10;
    /**
     * default order
     */
    const DEFAULT_ORDER = 0;
    /**
     * Percent precision - digits after decimal point
     */
    const PCT_PRECISION = 2;
    /**
     * use to test if owner
     */
    const IS_OWNER = 0;
    /**
     * use to test if breeder
     */
    const IS_BREEDER = 1;
    /**
     * do not use (false)
     */
    const DO_NOT_USE = 0;
    /**
     * use (true)
     */
    const OK_TO_USE = 1;
    /**
     * item is not active
     */
    const IS_NOT_ACTIVE = 0;
    /**
     * item is active
     */
    const IS_ACTIVE = 1;
    /**
     * is not locked
     */
    const IS_NOT_LOCKED = 0;
    /**
     * is locked
     */
    const IS_LOCKED = 1;
    /**
     * item does not have search
     */
    const DOES_NOT_HAVE_SEARCH = 0;
    /**
     * item has search
     */
    const HAS_SEARCH = 1;
    /**
     * view item false
     */
    const DO_NOT_VIEW_IN = 0;
    /**
     * view item true
     */
    const VIEW_IN = 1;
    /**
     * item is not locked
     */
    const UNLOCKED = 0;
    /**
     * item is locked
     */
    const LOCKED = 1;
    /**
     * is not a litter
     */
    const NOT_LITTER = 0;
    /**
     * is a litter
     */
    const LITTER = 1;
    /**
     * is not general litter
     */
    const NOT_GENERAL_LITTER = 0;
    /**
     * is general litter
     */
    const GENERAL_LITTER = 1;
    // Navigation
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
