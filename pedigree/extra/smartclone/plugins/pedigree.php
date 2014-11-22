<?php

/**
 * $Id: pedigree.php,v 1.1 2006/11/02 17:25:05 marcan Exp $
 * Module: SmartClone
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

/**
 * Usage of SmartClone plugin system
 *
 * A plugin need to be named by the dirname of the module.
 * The plugin needs to defined an array called $pluginPatterns in which
 * each item will also be an array.
 *
 * The 'key' will be the string to replace.
 * The 'replacement' will be one of these values :
 *
 * - ModuleName : Clone name when 2 words can be capitalized
 * - Modulename : Clone name with only the first letter capitalized
 * - modulename : Clone name all lowercase
 * - MODULENAME : Clone name all uppercase
 * - CONSTANT    : Used in the language constants. This is the 8 last characters of MODULENAME.
 *                  Please note that the CONSTANT must be used with a prefixed and suffixed "_".
 *                  For example : '_SSECTION_'
 * - CUSTOM    : If CUSTOM if used, then the array will need to have another item called
 *                  'function', which needs to be a function defined in the plugin and returing
 *                  the string by which will be replaced the 'key'
 *
 * The 'prefix' will be appended at the begining of the replacement string
 * The 'suffix' will be appended at the end of the replacement string
 *
 * Here is an examle :
 *
 * <code>
 * $i = 0;
 *
 * $pluginPatterns[$i]['key'] = '_SSECTION_';
 * $pluginPatterns[$i]['replacement'] = 'CONSTANT';
 * $pluginPatterns[$i]['prefix'] = '_';
 * $pluginPatterns[$i]['suffix'] = '_';
 * ++$i;
 *
 * $pluginPatterns[$i]['key'] = 'SmartSection';
 * $pluginPatterns[$i]['replacement'] = 'ModuleName';
 * ++$i;
 *
 * $pluginPatterns[$i]['key'] = '_SDU_';
 * $pluginPatterns[$i]['replacement'] = 'CONSTANT';
 * $pluginPatterns[$i]['prefix'] = '_';
 * $pluginPatterns[$i]['suffix'] = '_';
 * ++$i;
 *
 * $pluginPatterns[$i]['key'] = 'SOME_SPECIAL_STRING';
 * $pluginPatterns[$i]['replacement'] = 'CUSTOM';
 * $pluginPatterns[$i]['function'] = 'specialString';
 * ++$i;
 *
 * function specialString($toModule) {
 *    return $toModule . "-somethingSpecial";
 * }
 * </code>
 */

/**
 * SmartClone plugin for SmartClient
 */
$i = 0;

$pluginPatterns[$i]['key']         = '_PEDIGREE_';
$pluginPatterns[$i]['replacement'] = 'CONSTANT';
$pluginPatterns[$i]['prefix']      = '_';
$pluginPatterns[$i]['suffix']      = '_';

++$i;
$pluginPatterns[$i]['key']         = 'PEDIGREE_';
$pluginPatterns[$i]['replacement'] = 'CONSTANT';
//$pluginPatterns[$i]['prefix'] = '_';
$pluginPatterns[$i]['suffix'] = '_';

++$i;
$pluginPatterns[$i]['key']         = 'Pedigree';
$pluginPatterns[$i]['replacement'] = 'ModuleName';

//++$i;
//$pluginPatterns[$i]['key'] = 'WF-Downloads';
//$pluginPatterns[$i]['replacement'] = 'ModuleName';
