<?php
// -------------------------------------------------------------------------

use Xmf\Request;
use XoopsModules\Pedigree;

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';

/** @var XoopsModules\Pedigree\Helper $helper */
$helper->loadLanguage('main');

// Include any common code for this module.
require_once $helper->path('include/common.php');

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
//extract($_GET, EXTR_PREFIX_ALL, 'param');
//extract($_POST, EXTR_PREFIX_ALL, 'param');

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_owner.tpl';

include $GLOBALS['xoops']->path('/header.php');

$GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/jquery.js');
$GLOBALS['xoTheme']->addScript('browse.php?' . $helper->url('assets/js/jquery.magnific-popup.min.js'));
$GLOBALS['xoTheme']->addStylesheet('browse.php?' . $helper->url('assets/css/style.css'));
$GLOBALS['xoTheme']->addStylesheet('browse.php?' . $helper->url('assets/css/magnific-popup.css'));

//@todo this js script doesn't exist - should it use XOOPS spectrum.js instead?
// Commented out in v1.32 Alpha 1 since it's not used in the template
/*
if (isset($GLOBALS['xoTheme'])) {
    $GLOBALS['xoTheme']->addScript('include/color-picker.js');
} else {
    echo '<script type="text/javascript" src="' . XOOPS_URL . '/include/color-picker.js"></script>';
}
*/
$GLOBALS['xoopsTpl']->assign('page_title', _MA_PEDIGREE_OWNER_PAGETITLE);

$pathIcon16 = \Xmf\Module\Admin::iconUrl('', 16);

xoops_load('XoopsUserUtility');

$ownId = Request::getInt('ownid', 0, 'GET');
$items = [];

//query
/** @var \XoopsModules\Pedigree\OwnerHandler $ownerHandler */
$ownerHandler = $helper->getHandler('Owner');
$criteria = new \Criteria('id', $ownId);
$ownObjArray = $ownerHandler->getAll($criteria);

foreach ($ownObjArray as $ownObj) {
    $naamf = $ownObj->getVar('firstname');   //first name
    $naaml = $ownObj->getVar('lastname');    // last name
    $naam  = ucwords($naamf . ' ' . $naaml); // whole name
    $email = $ownObj->getVar('emailadres');  // email address
    //homepage - changed to be regular expression check for http or https (case insensitive)
    $homepage = $ownObj->getVar('website');  //website home page
    if (!empty($homepage) && !preg_match('/^(https?:\/\/)/i', $homepage)) {
        $homepage = "https://{$homepage}"; //defaults to use https:
    }

    //check for edit rights
    $access = 0;
    if ((!empty($GLOBALS['xoopsUser']) && ($GLOBALS['xoopsUser'] instanceof \XoopsUser))
        && ($helper->isUserAdmin() || ($ownObj->getVar('user')== $GLOBALS['xoopsUser']->getVar('uid'))))
    {
        $access = 1;
    }

    //lastname
    $items[] = [
        'header' => _MA_PEDIGREE_OWN_LNAME,
        'data'   => "<a href=\"owner.php?ownid={$ownId}\">{$naaml}</a>",
        'edit'   => '<a href="' . $helper->url("updateowner.php?id={$ownId}&fld=nl") . "\"><img src=\"{$pathIcon16}/edit.png\" border=\"0\" alt=\"_EDIT\" title=\"_EDIT\"></a>"
    ];
    //firstname
    $items[] = [
        'header' => _MA_PEDIGREE_OWN_FNAME,
        'data'   => '<a href="' . $helper->url("owner.php?ownid={$ownId}") . "\">{$naamf}</a>",
        'edit'   => '<a href="' . $helper->url("updateowner.php?id={$ownId}&fld=nf") . "\"><img src=\"{$pathIcon16}/edit.png\" border=\"0\" alt=\"_EDIT\" title=\"_EDIT\"></a>"
    ];
    //email
    $items[] = [
        'header' => _MA_PEDIGREE_FLD_OWN_EMAIL,
        'data'   => "<a href=\"mailto:{$email}\">{$email}</a>",
        'edit'   => '<a href="' . $helper->url("updateowner.php?id={$ownId}&fld=em") . "\"><img src=\"{$pathIcon16}/edit.png\" border=\"0\" alt=\"_EDIT\" title=\"_EDIT\"></a>"
    ];
    //homepage
    $items[] = [
        'header' => _MA_PEDIGREE_FLD_OWN_WEB,
        'data'   => "<a href=\"{$homepage}\" target=\"_blank\">{$homepage}</a>",
        'edit'   => '<a href="' . $helper->url("updateowner.php?id={$ownId}&fld=we") . "\"><img src=\"{$pathIcon16}/edit.png\" border=\"0\" alt=\"_EDIT\" title=\"_EDIT\"></a>"
    ];
    //owner of
    $items[] = [
        'header' => _MA_PEDIGREE_OWN_OWN,
        'data'   => Pedigree\Utility::breederof($ownId, 0),
        'edit'   => ''
    ];
    //breeder of
    $items[] = [
        'header' => _MA_PEDIGREE_OWN_BRE,
        'data'   => Pedigree\Utility::breederof($ownId, 1),
        'edit'   => ''
    ];
    //database user who entered the data into the dB
    $items[] = [
        'header' => _MA_PEDIGREE_FLD_DBUS,
        'data'   => \XoopsUserUtility::getUnameFromId($ownObj->getVar('user')),
        'edit'   => ''
    ];
/*
$queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE id=' . $ownId;
$result      = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //id
    $ownId = $row['id'];

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
        $homepage = "https://{$homepage}"; //defaults to use https:
    }

    global $xoopsTpl;

    $check    = substr($homepage, 0, 7);
    if ('http://' !== $check) {
        $homepage = 'http://' . $homepage;
    }

    //Owner of
    $owner = Pedigree\Utility::breederof($row['id'], 0);

    //Breeder of
    $breeder = Pedigree\Utility::breederof($row['id'], 1);

    //entered into the database by
    $dbuser = \XoopsUserUtility::getUnameFromId($row['user']);

    //check for edit rights
    $access = 0;
    if ((!empty($GLOBALS['xoopsUser']) && ($GLOBALS['xoopsUser'] instanceof \XoopsUser))
        && ($helper->isUserAdmin() || ($row['user'] == $GLOBALS['xoopsUser']->getVar('uid'))))
    {
        $access = 1;
    }

    //lastname
    $items[] = [
        'header' => _MA_PEDIGREE_OWN_LNAME,
        'data'   => "<a href=\"owner.php?ownid={$row['id']}\">{$naaml}</a>",
        'edit'   => "<a href=\"" . $helper->url("updateowner.php?id={$row['id']}&fld=nl") . "\"><img src=\"{$pathIcon16}/edit.png\" border=\"0\" alt=\"_EDIT\" title=\"_EDIT\"></a>"
    ];

    //firstname
    $items[] = [
        'header' => _MA_PEDIGREE_OWN_FNAME,
        'data'   => "<a href=\"" . $helper->url("owner.php?ownid={$row['id']}") . "\">{$naamf}</a>",
        'edit'   => "<a href=\"" . $helper->url("updateowner.php?id={$row['id']}&fld=nf") . "\"><img src=\"{$pathIcon16}/edit.png\" border=\"0\" alt=\"_EDIT\" title=\"_EDIT\"></a>"
    ];

    //email
    $items[] = [
        'header' => _MA_PEDIGREE_FLD_OWN_EMAIL,
        'data'   => "<a href=\"mailto:{$email}\">{$email}</a>",
        'edit'   => "<a href=\"" . $helper->url("updateowner.php?id={$row['id']}&fld=em") . "\"><img src=\"{$pathIcon16}/edit.png\" border=\"0\" alt=\"_EDIT\" title=\"_EDIT\"></a>"
    ];
    //homepage
    $items[] = [
        'header' => _MA_PEDIGREE_FLD_OWN_WEB,
        'data'   => "<a href=\"{$homepage}\" target=\"_blank\">{$homepage}</a>",
        'edit'   => "<a href=\"" . $helper->url("updateowner.php?id={$row['id']}&fld=we") . "\"><img src=\"{$pathIcon16}/edit.png\" border=\"0\" alt=\"_EDIT\" title=\"_EDIT\"></a>"
    ];
    //owner of
    $items[] = [
        'header' => _MA_PEDIGREE_OWN_OWN,
        'data'   => $owner,
        'edit'   => ''
    ];
    //breeder of
    $items[] = [
        'header' => _MA_PEDIGREE_OWN_BRE,
        'data'   => $breeder,
        'edit'   => ''
    ];
    //database user
    $items[] = [
        'header' => _MA_PEDIGREE_FLD_DBUS,
        'data'   => $dbuser,
        'edit'   => ''
    ];
*/
    //add dog/owner/breeder to smarty template
    $GLOBALS['xoopsTpl']->assign(['access' => $access, 'dogs' =>$items, 'name' => $naam, 'id' => $ownId]);
}

//add data to smarty template
$GLOBALS['xoopsTpl']->assign(['delete' => "<img src=\"{$pathIcon16}/delete.png\" border=\"0\" alt=\"_DELETE\" title=\"_DELETE\">"]);

//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';

