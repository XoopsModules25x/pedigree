<?php
// -------------------------------------------------------------------------

use XoopsModules\Pedigree;

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
require_once XOOPS_ROOT_PATH . '/header.php';
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

global $xoopsTpl, $xoopsDB;

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

echo '<form method="post" action="convert.php">convert:<input type="text" name="van">';
echo 'to:<input type="text" name="naar">';
echo '<input type="submit"></form>';

if ('' != $_POST['naar']) {
    $query = 'update ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " set user4 = '" . $_POST['naar'] . "' where user4 = '" . $_POST['van'] . "'";
    echo $query . '<br>';
    $GLOBALS['xoopsDB']->query($query);
}

$result = $GLOBALS['xoopsDB']->query("SELECT user4, count('user4') AS X FROM " . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " GROUP BY 'user4'");
$count  = 0;
$total  = 0;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    ++$count;
    echo $row['user4'] . ' - ' . $row['X'] . '<br>';
    $total += $row['X'];
}
echo '<hr>' . $count . '-' . $total;

//comments and footer
require_once XOOPS_ROOT_PATH . '/footer.php';
