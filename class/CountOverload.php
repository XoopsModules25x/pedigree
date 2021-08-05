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
 *
 * @package         XoopsModules\Pedigree
 * @author          XOOPS Development Team - <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.32
 */

/**
 * Class Trait to overload XoopsObject getCount() & getCounts() methods because XOOPS
 * doesn't return int as expected in XOOPS <= 2.5.10, need to check in later versions
 *
 * To use: add 'use CountOverload' in the class that extends \XoopsPersistableObjectHandler
 *
 **/
trait CountOverload
{
    /**
     * count objects matching a condition
     *
     * Overload of XoopsObject getCount() because XOOPS doesn't return int as expected
     * in XOOPS <= 2.5.10, need to check in later versions
     *
     * @param  \CriteriaElement|null $criteria {@link \CriteriaElement} to match
     * @return int count of objects
     */
    public function getCount(?\CriteriaElement $criteria = null): int
    {
        return (int) parent::getCount($criteria = null);
    }

    /**
     * Get counts of objects matching a condition
     *
     * @param  \CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return int[] of counts
     */
    public function getCounts(?\CriteriaElement $criteria = null): array
    {
        $countArray = parent::getCounts($criteria = null);
        \array_walk($countArray, 'self::castType', 'int');

        return $countArray;
    }

    /**
     * Cast a variable to a known type
     *
     * @param mixed $var variable to cast (by reference)
     * @param string $type new type
     * @return bool true if type set was successful, false otherwise
     */
    private static function castType(&$var, $key,  ?string $type = 'int'): bool
    {
        $validTypes = ['bool', 'boolean', 'int', 'integer', 'float', 'double', 'string', 'array', 'object', 'null'];
        $success    = false;
        if (\in_array($type, $validTypes)) {
            $success = \settype($var, $type);
        }
        return $success;
    }
}
