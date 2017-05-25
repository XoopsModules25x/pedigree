<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

//xoops_loadLanguage('main', basename(dirname(__DIR__)));
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

include_once __DIR__ . '/header.php';

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . "/modules/{$moduleDirName}/include/common.php";

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, 'param');
extract($_POST, EXTR_PREFIX_ALL, 'param');

$xoopsOption['template_main'] = 'pedigree_owner.tpl';

include $GLOBALS['xoops']->path('/header.php');

$GLOBALS['xoTheme']->addScript("browse.php?Frameworks/jquery/jquery.js");
$GLOBALS['xoTheme']->addScript("browse.php?modules/{$moduleDirName}/assets/js/jquery.magnific-popup.min.js");
$GLOBALS['xoTheme']->addStylesheet("browse.php?modules/{$moduleDirName}/assets/css/style.css");
$GLOBALS['xoTheme']->addStylesheet(PEDIGREE_URL . '/assets/css/magnific-popup.css');

if (isset($GLOBALS['xoTheme'])) {
    $GLOBALS['xoTheme']->addScript('include/color-picker.js');
} else {
    echo "<script type=\"text/javascript\" src=\"" . XOOPS_URL . "/include/color-picker.js\"></script>";
}

//@todo move language string to language file
$xoopsTpl->assign('page_title', 'Pedigree database - View Owner/Breeder details');

//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

global $xoopsTpl, $xoopsModuleConfig, $xoopsModule;

$pathIcon16 = $GLOBALS['xoopsModule']->getInfo('icons16');

xoops_load('XoopsUserUtility');

$ownid = XoopsRequest::getInt('ownid', 0, 'GET');

//query
$queryString = "SELECT * FROM " . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . " WHERE Id={$ownid}";
$result      = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //id
    $id = $row['Id'];

    //name
    $naam = stripslashes($row['firstname']) . ' ' . stripslashes($row['lastname']);

    //lastname
    $naaml = stripslashes($row['lastname']);

    //firstname
    $naamf = stripslashes($row['firstname']);

    //email
    $email = $row['emailadres'];

    //homepage - changed to be regular expression check for http or https (case insensitive)
    $homepage = $row['website'];
    if (!empty($homepage) && !preg_match('/^(https?:\/\/)/i', $homepage)) {
        $homepage = "http://{$homepage}";
    }
/*
    $check    = substr($homepage, 0, 7);
    if ($check !== 'http://') {
        $homepage = 'http://' . $homepage;
    }
*/
    //Owner of
    $owner = PedigreeUtilities::breederof($row['Id'], 0);

    //Breeder of
    $breeder = PedigreeUtilities::breederof($row['Id'], 1);

    //entered into the database by
    $dbuser = XoopsUserUtility::getUnameFromId($row['user']);

    //check for edit rights
    $xoopsModule = XoopsModule::getByDirname($moduleDirName);
    if (($GLOBALS['xoopsUser'] instanceof XoopsUser)
        && ($GLOBALS['xoopsUser']->isAdmin($xoopsModule->mid())
            || ($row['user'] == $GLOBALS['xoopsUser']->getVar('uid'))))
    {
        $access = 1;
    } else {
        $access = 0;
    }

    //lastname
    $items[] = array(
        'header' => _MA_PEDIGREE_OWN_LNAME,
        'data'   => "<a href=\"owner.php?ownid=" . $row['Id'] . "\">" . $naaml . '</a>',
        'edit'   => "<a href=\"updateowner.php?Id=" . $row['Id'] . "&fld=nl\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );

    //firstname
    $items[] = array(
        'header' => _MA_PEDIGREE_OWN_FNAME,
        'data'   => "<a href=\"owner.php?ownid=" . $row['Id'] . "\">" . $naamf . '</a>',
        'edit'   => "<a href=\"updateowner.php?Id=" . $row['Id'] . "&fld=nf\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );

    //email
    $items[] = array(
        'header' => _MA_PEDIGREE_FLD_OWN_EMAIL,
        'data'   => "<a href=\"mailto:" . $email . "\">" . $email . '</a>',
        'edit'   => "<a href=\"updateowner.php?Id=" . $row['Id'] . "&fld=em\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );
    //homepage
    $items[] = array(
        'header' => _MA_PEDIGREE_FLD_OWN_WEB,
        'data'   => "<a href=\"" . $homepage . "\" target=\"_blank\">" . $homepage . '</a>',
        'edit'   => "<a href=\"updateowner.php?Id=" . $row['Id'] . "&fld=we\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );
    //owner of
    $items[] = array(
        'header' => _MA_PEDIGREE_OWN_OWN,
        'data'   => $owner,
        'edit'   => ''
    );
    //breeder of
    $items[] = array(
        'header' => _MA_PEDIGREE_OWN_BRE,
        'data'   => $breeder,
        'edit'   => ''
    );

    //database user
    $items[] = array(
        'header' => _MA_PEDIGREE_FLD_DBUS,
        'data'   => $dbuser,
        'edit'   => ''
    );
}

//add data to smarty template
$xoopsTpl->assign('access', $access);
$xoopsTpl->assign('dogs', $items);
$xoopsTpl->assign('name', $naam);
$xoopsTpl->assign('id', $id);
//$xoopsTpl->assign("delete", _DELETE);
$xoopsTpl->assign('delete', "<img src=\"{$pathIcon16}/delete.png\" border=\"0\" alt=\"" . _DELETE . "\" title=\"" . _DELETE. "\" />");

//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';
