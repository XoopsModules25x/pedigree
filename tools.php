<?php
// -------------------------------------------------------------------------

include __DIR__ . '/header.php';

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

/*
if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
} else {
    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
}
*/

xoops_loadLanguage('main', basename(dirname(__DIR__)));

$xoopsOption['template_main'] = "pedigree_tools.tpl";

include XOOPS_ROOT_PATH . '/header.php';

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/class_field.php");
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");

//check for access
$xoopsModule = XoopsModule::getByDirname("pedigree");
if (empty($xoopsUser)) {
    redirect_header("index.php", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
    exit();
}

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
//$xoTheme->addScript(PEDIGREE_URL . '/assets/js/jquery.ThickBox/thickbox-compressed.js');

$xoTheme->addScript(PEDIGREE_URL . '/assets/js/jquery.magnific-popup.min.js');
$xoTheme->addScript(PEDIGREE_URL . '/assets/js/colpick.js');

$xoTheme->addStylesheet(PEDIGREE_URL . '/assets/css/colpick.css');
$xoTheme->addStylesheet(PEDIGREE_URL . '/assets/css/magnific-popup.css');

global $field;
//add JS routines
echo '<script language="JavaScript" src="picker.js"></script>';

//set form to be empty
$form = "";

//get module configuration
$module_handler = xoops_getHandler('module');
$module         = $module_handler->getByDirname("pedigree");
$config_handler = xoops_getHandler('config');
$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "none";
}

//always check to see if a certain field was refferenced.
if (isset($_GET['field'])) {
    $field = $_GET['field'];
}

switch ($op) {
    case "lang":
        lang();
        break;
    case "langsave":
        langsave();
        break;
    case "colours":
        colours();
        break;
    case "savecolours":
        savecolours();
        break;
    case "settings":
        settings();
        break;
    case "settingssave":
        settingssave();
        break;
    case "pro":
        pro();
        break;
    case "userfields":
        userfields($field);
        $uf = true;
        break;
    case "listuserfields":
        listuserfields();
        $uf = true;
        break;
    case "togglelocked":
        togglelocked($field);
        break;
    case "fieldmove":
        fieldmove($field, $_GET['move']);
        break;
    case "deluserfield":
        deluserfield($_GET['id']);
        break;
    case "restoreuserfield":
        restoreuserfield($_GET['id']);
        break;
    case "editlookup":
        editlookup($_GET['id']);
        break;
    case "lookupmove":
        lookupmove($field, $_GET['id'], $_GET['move']);
        break;
    case "dellookupvalue":
        dellookupvalue($field, $_GET['id']);
        break;
    case "addlookupvalue":
        addlookupvalue($field);
        break;
    case "editlookupvalue":
        editlookupvalue($field, $_GET['id']);
        break;
    case "savelookupvalue":
        savelookupvalue($field, $_GET['id']);
        break;
    case "deleted":
        deleted();
        break;
    case "delperm":
        delperm($_GET['id']);
        break;
    case "delall":
        delall();
        break;
    case "restore":
        restore($_GET['id']);
        break;
    case "database":
        database();
        $db = true;
        break;
    case "userq":
        userq();
        $db = true;
        break;
    case "userqrun":
        userqrun($_GET['f']);
        $db = true;
        break;
    case "dbanc":
        database_oa();
        $db = true;
        break;
    case "fltypar":
        database_fp();
        $db = true;
        break;
    case "credits":
        credits();
        break;
    case "index":
        index();
        break;
    default :
        userfields();
        $uf = true;
        break;
}

//create tools array
$tools[] = array('title' => _MA_PEDIGREE_GENSTTINGS, 'link' => "tools.php?op=settings", 'main' => "1");
//if ($moduleConfig['proversion'] == '1')
//{
//	$tools[] = array ( 'title' => "Pro-version settings", 'link' => "tools.php?op=pro", 'main' => "1" );
//}
$tools[] = array('title' => _MA_PEDIGREE_LANG_OPTIONS, 'link' => "tools.php?op=lang", 'main' => "1");
$tools[] = array('title' => _MA_PEDIGREE_CREATE_USER_FIELD, 'link' => "tools.php?op=userfields", 'main' => "1");
$tools[] = array('title' => _MA_PEDIGREE_LIST_USER_FIELD, 'link' => "tools.php?op=listuserfields", 'main' => "1");
$tools[] = array('title' => _MA_PEDIGREE_DEFINE_COLOR, 'link' => "tools.php?op=colours", 'main' => "1");
$tools[] = array('title' => _MA_PEDIGREE_DELETE_PED, 'link' => "tools.php?op=deleted", 'main' => "1");
$tools[] = array('title' => _MA_PEDIGREE_DAT_TOOLS, 'link' => "tools.php?op=database", 'main' => "1");
if (isset($db)) {
    //create database submenu
    $tools[] = array('title' => _MA_PEDIGREE_ANCESTORS, 'link' => "tools.php?op=dbanc", 'main' => "0");
    $tools[] = array('title' => _MA_PEDIGREE_NOGENDER, 'link' => "tools.php?op=fltypar", 'main' => "0");
    $tools[] = array('title' => _MA_PEDIGREE_USERQUERIES, 'link' => "tools.php?op=userq", 'main' => "0");
}
$tools[] = array('title' => _MA_PEDIGREE_CREDITS, 'link' => "tools.php?op=credits", 'main' => "1");
$tools[] = array('title' => _MA_PEDIGREE_USER_LOGOUT, 'link' => "../../user.php?op=logout", 'main' => "1");
//add data (form) to smarty template

$xoopsTpl->assign("tools", $tools);

//footer
include XOOPS_ROOT_PATH . "/footer.php";

function index()
{
    $form = "";
}

function colours()
{
    global $xoopsTpl, $moduleConfig;

    $colors  = explode(";", $moduleConfig['colourscheme']);
    $actlink = $colors[0];
    $even    = $colors[1];
    $odd     = $colors[2];
    $text    = $colors[3];
    $hovlink = $colors[4];
    $head    = $colors[5];
    $body    = $colors[6];
    $title   = $colors[7];
    ?>
    <script type="text/javascript">

        $('.color-box').colpick({
            colorScheme: 'dark',
            layout     : 'rgbhex',
            color      : 'ff8800',
            onSubmit   : function (hsb, hex, rgb, el) {
                $(el).css('background-color', '#' + hex);
                $(el).colpickHide();
            }
        })
            .css('background-color', '#ff8800');

        $('#picker').colpick({
            layout     : 'hex',
            submit     : 0,
            colorScheme: 'dark',
            onChange   : function (hsb, hex, rgb, el, bySetColor) {
                $(el).css('border-color', '#' + hex);
                // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
                if (!bySetColor) $(el).val(hex);
            }
        }).keyup(function () {
            $(this).colpickSetColor(this.value);
        });


    </script>
    <?php


    echo '
   <script type="text/javascript" src="assets/js/jscolor/jscolor.js"></script>
   ';

    echo '
   <script language="javascript" type="text/javascript">
      function changeBackgroundColor(objDivID, colorvalue)
      {
           document.getElementById(objDivID).style.backgroundColor = colorvalue;
       }
       function changeTextColor(objDivID, colorvalue)
      {
             document.getElementById(objDivID).style.color =  colorvalue;
       }
   </script>
   ';

    $form = _MA_PEDIGREE_BGCOLOR . "<br /><br />";
    $form
        .= '<FORM NAME="myForm" action=\'tools.php?op=savecolours\' method=\'POST\'>
<table>

<!--
    <tr><td>' . _MA_PEDIGREE_TXT_COLOR . '</td><td><INPUT TYPE="text" id="text" name="text" value="' . $text . '" size="11" maxlength="7">
    <a href="TCP.popup(document.forms[\'myForm\'].elements[\'text\'])">
    <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="assets/images/sel.gif"></a></td></tr>


    <tr><td>' . _MA_PEDIGREE_LINK_COLOR . '</td><td><INPUT TYPE="text" id="actlink" name="actlink" value="' . $actlink . '" size="11" maxlength="7">
    <a href="TCP.popup(document.forms[\'myForm\'].elements[\'actlink\'])">
    <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="assets/images/sel.gif"></a></td></tr>


    <tr><td>' . _MA_PEDIGREE_BACK1_COLOR . '</td><td><INPUT TYPE="text" id="even" name="even" value="' . $even . '" size="11" maxlength="7">
    <a href="TCP.popup(document.forms[\'myForm\'].elements[\'even\'])">
    <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="assets/images/sel.gif"></a></td></tr>

    <tr><td>' . _MA_PEDIGREE_BACK2_COLOR . '</td><td><INPUT TYPE="text" id="body" name="body" value="' . $body . '" size="11" maxlength="7">
    <a href="TCP.popup(document.forms[\'myForm\'].elements[\'body\'])">
    <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="assets/images/sel.gif"></a></td></tr>

-->

<!--

    <tr><td>' . _MA_PEDIGREE_TXT_COLOR . '</td><td>#<input type="text" id="picker" name="text" value="' . $text . '" size="11" maxlength="7">
    </td></tr>

    <tr><td>' . _MA_PEDIGREE_LINK_COLOR . '</td><td>#<input type="text" id="picker" name="actlink" value="' . $actlink . '" size="11" maxlength="7">
    </td></tr>

    <tr><td>' . _MA_PEDIGREE_BACK1_COLOR . '</td><td>#<input type="text" id="picker" name="even" value="' . $even . '" size="11" maxlength="7">
    </td></tr>

        <tr><td>' . _MA_PEDIGREE_BACK2_COLOR . '</td><td>#<input type="text" id="picker" name="body" value="' . $body . '" size="11" maxlength="7">
    </td></tr>

-->

<tr><td>' . _MA_PEDIGREE_TXT_COLOR
        . '</td><td><input class="color {hash:true,caps:false}" onMouseOver="changeTextColor(\'back4\', this.value)" type="text" name="text" maxlength="7" size="7" id="colorpickerField1" value="'
        . $text . '" />
</td></tr>


<tr><td>' . _MA_PEDIGREE_LINK_COLOR
        . '</td><td><input class="color {hash:true,caps:false}" onMouseOver="changeTextColor(\'back4\', this.value)" type="text" name="actlink" maxlength="7" size="7" id="colorpickerField1" value="'
        . $actlink . '" />
</td></tr>


<tr><td>' . _MA_PEDIGREE_BACK1_COLOR
        . '</td><td><input class="color {hash:true,caps:false}" onMouseOver="changeTextColor(\'back4\', this.value)" type="text" name="even" maxlength="7" size="7" id="colorpickerField1" value="'
        . $even . '" />
</td></tr>


<tr><td>' . _MA_PEDIGREE_BACK2_COLOR
        . '</td><td><input class="color {hash:true,caps:false}" onMouseOver="changeTextColor(\'back4\', this.value)" type="text" name="body" maxlength="7" size="7" id="colorpickerField1" value="'
        . $body . '" />
</td></tr>

    <tr><td><INPUT TYPE="submit" value="' . _MA_PEDIGREE_SUBMIT_BUTTON . '"></td><td>&nbsp;</td></tr>

    </table>


    </form>';

    $xoopsTpl->assign("form", $form);
}

function savecolours()
{

    global $xoopsDB;
    require_once 'include/color.php';
    $color = new Image_Color();
    //create darker link hover colour
    $color->setColors($_POST['actlink'], $_POST['actlink']);
    $color->changeLightness(-100);
    $dark = $color->rgb2hex($color->color1);
    //create darker 'head' colour
    $color->setColors($_POST['even'], $_POST['even']);
    $color->changeLightness(-25);
    $head = $color->rgb2hex($color->color1);
    //create lighter female colour
    $color->setColors($_POST['even'], $_POST['even']);
    $color->changeLightness(25);
    $female = $color->rgb2hex($color->color1);

    $col = $_POST['actlink'] . ";" . $_POST['even'] . ";#" . $female . ";" . $_POST['text'] . ";#" . $dark . ";#" . $head . ";" . $_POST['body'] . ";" . $_POST['actlink'];

    $query = "UPDATE " . $xoopsDB->prefix("config") . " SET conf_value = '"
        . $xoopsDB->escape($col) . "' WHERE conf_name = 'colourscheme'";
    $xoopsDB->query($query);
    redirect_header("tools.php?op=colours", 1, "Your settings have been saved.");
}

function listuserfields()
{
    global $xoopsTpl, $xoopsDB, $form;
    $form .= _MA_PEDIGREE_FIELD_EXPLAIN4;
    $sql     = "SELECT * FROM " . $xoopsDB->prefix("pedigree_fields") . " WHERE isActive = '1' ORDER BY `order`";
    $result  = $xoopsDB->query($sql);
    $numrows = $xoopsDB->getRowsNum($result);
    $count   = 0;
    $form .= _MA_PEDIGREE_FIELD_FORM1;
    $form .= "<tr ><td colspan=\"7\"><hr /></td></tr>";
    $mark = '<td><span style="font-weight: bold;">X</span></td>';
    while ($row = $xoopsDB->fetchArray($result)) {
        $form .= "<tr>";
        //display locked fields
        if ($row['locked'] == '1') {
            $form .= "<td><a href=\"tools.php?op=togglelocked&field=" . $row['ID'] . "\"><img src=\"assets/images/locked.gif\" alt=\"click to open this field\" /></a></td>";
        } else {
            $form .= "<td><a href=\"tools.php?op=togglelocked&field=" . $row['ID'] . "\"><img src=\"assets/images/open.gif\" alt=\"click to lock this field\" /></a></td>";
        }

        if ($count == 0) { //first row
            $form .= "<td style=\"width: 15px;\">&nbsp;</td><td style=\"width: 15px;\"><a href=\"tools.php?op=fieldmove&field=" . $row['ID']
                . "&move=down\"><img src=\"assets/images/down.gif\" alt=\"move field down\" /></a></td>";

        } elseif ($count == $numrows - 1) { //last row
            $form .= "<td><a href=\"tools.php?op=fieldmove&field=" . $row['ID'] . "&move=up\"><img src=\"assets/images/up.gif\" alt=\"move field up\" /></a></td><td>&nbsp;</td>";
        } else { //other rows
            $form
                .=
                "<td><a href=\"tools.php?op=fieldmove&field=" . $row['ID'] . "&move=up\"><img src=\"assets/images/up.gif\" alt=\"move field up\" /></a></td><td><a href=\"tools.php?op=fieldmove&field="
                . $row['ID'] . "&move=down\"><img src=\"assets/images/down.gif\" alt=\"move field down\" /></a></td>";
        }
        $form
            .= "<td><a href=\"tools.php?op=deluserfield&id=" . $row['ID'] . "\"><img src=\"images/delete.gif\" alt=\"delete field\" /></a>&nbsp;<a href=\"tools.php?op=userfields&field=" . $row['ID']
            . "\">" . $row['FieldName'] . "</a></td>";
        //can the filed be shown in a list
        if ($row['ViewInList'] == '1') {
            $form .= $mark;
        } else {
            $form .= "<td>&nbsp;</td>";
        }
        //is searchable ?
        if ($row['HasSearch'] == '1') {
            $form .= $mark;
        } else {
            $form .= "<td>&nbsp;</td>";
        }
        //has lookuptable ?
        if ($row['LookupTable'] == '1') {
            $form .= "<td><a href=\"tools.php?op=editlookup&id=" . $row['ID'] . "\">Edit</a></td>";
        } else {
            $form .= "<td>&nbsp;</td>";
        }

        $form .= "</tr>";
        ++$count;
    }
    $form .= "</table>";
    $sql    = "SELECT * FROM " . $xoopsDB->prefix("pedigree_fields") . " WHERE isActive = '0' ORDER BY 'ID'";
    $result = $xoopsDB->query($sql);
    if ($xoopsDB->getRowsNum($result) > 0) {
        $form .= _MA_PEDIGREE_FIELD_EXPLAIN5;
        while ($row = $xoopsDB->fetchArray($result)) {
            $form .= "<li><a href=\"tools.php?op=restoreuserfield&id=" . $row['ID'] . "\">" . $row['FieldName'] . "</a>";
        }
    }
    $xoopsTpl->assign("form", $form);
}

/**
 * @param $field
 */
function togglelocked($field)
{
    global $xoopsDB;
    //find current status
    $sql    = "SELECT locked from " . $xoopsDB->prefix("pedigree_fields") . " WHERE ID = '" . $field . "'";
    $result = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($result)) {
        if ($row['locked'] == '0') { //not locked
            lock($field);
        } else {
            unlock($field);
        }
    }
    listuserfields();
}

/**
 * @param $field
 */
function lock($field)
{
    global $xoopsDB;
    $field = (int) $field;
    $sql = "UPDATE " . $xoopsDB->prefix("pedigree_fields") . " SET locked = '1' WHERE ID = '" . $field . "'";
    $xoopsDB->queryF($sql);

    return;
}

/**
 * @param $field
 */
function unlock($field)
{
    global $xoopsDB;
    $field = (int) $field;
    $sql = "UPDATE " . $xoopsDB->prefix("pedigree_fields") . " SET locked = '0' WHERE ID = '" . $field . "'";
    $xoopsDB->queryF($sql);

    return;
}

/**
 * @param $field
 * @param $move
 */
function fieldmove($field, $move)
{
    global $xoopsDB;
    //find next id
    $sql    = "SELECT * FROM " . $xoopsDB->prefix("pedigree_fields") . " WHERE isActive = '1' ORDER BY `order`";
    $result = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($result)) {
        $valorder[] = $row['order'];
        $valid[]    = $row['ID'];
    }
    foreach ($valid as $key => $value) {
        //find current ID location
        if ($value == $field) {
            $x = $key;
        }
    }
    //currentorder
    $currentorder = $valorder[$x];
    $currentid    = $valid[$x];

    if ($move == "down") {
        $nextorder = $valorder[$x + 1];
    } else {
        $nextorder = $valorder[$x - 1];
    }
    //move value with ID=nextid to original location
    $sql = "UPDATE " . $xoopsDB->prefix("pedigree_fields") . " SET `order` = '127' WHERE `order` = '" . $nextorder . "'";
    $xoopsDB->queryF($sql);
    $sql = "UPDATE " . $xoopsDB->prefix("pedigree_fields") . " SET `order` = '" . $nextorder . "' WHERE `order` = '" . $currentorder . "'";
    $xoopsDB->queryF($sql);
    //move current value into nextvalue's spot
    $sql = "UPDATE " . $xoopsDB->prefix("pedigree_fields") . " SET `order` = '" . $currentorder . "' WHERE `order` = '127'";
    $xoopsDB->queryF($sql);
    listuserfields();
}

/**
 * @param $field
 */
function deluserfield($field)
{
    global $xoopsDB;
    $field = (int) $field;
    $sql = "UPDATE " . $xoopsDB->prefix("pedigree_fields") . " SET isActive = '0' WHERE ID = " . $field;
    $xoopsDB->queryF($sql);
    listuserfields();
}

/**
 * @param $field
 */
function restoreuserfield($field)
{
    global $xoopsDB;
    $field = (int) $field;
    $sql = "UPDATE " . $xoopsDB->prefix("pedigree_fields") . " SET isActive = '1' WHERE ID = " . $field;
    $xoopsDB->queryF($sql);
    listuserfields();
}

/**
 * @param $field
 */
function editlookup($field)
{
    global $xoopsDB, $xoopsTpl;
    $form    = _MA_PEDIGREE_LOOKUPFIELD;
    $sql     = "SELECT * FROM " . $xoopsDB->prefix("pedigree_lookup" . $field) . " ORDER BY `order`";
    $result  = $xoopsDB->query($sql);
    $numrows = $xoopsDB->getRowsNum($result);
    $count   = 0;
    $form .= "<table>";
    while ($row = $xoopsDB->fetchArray($result)) {
        $form .= "<tr>";
        if ($count == 0) { //first row
            $form .= "<td style=\"width: 15px;\">&nbsp;</td><td style=\"width: 15px;\"><a href=\"tools.php?op=lookupmove&field=" . $field . "&id=" . $row['ID']
                . "&move=down\"><img src=\"assets/images/down.gif\"></a></td><td><a href=\"tools.php?op=dellookupvalue&field=" . $field . "&id=" . $row['ID']
                . "\"><img src=\"images/delete.gif\" /></a>&nbsp;<a href=\"tools.php?op=editlookupvalue&field=" . $field . "&id=" . $row['ID'] . "\">" . $row['value'] . "</a></td>";
        } elseif ($count == $numrows - 1) { //last row
            $form .= "<td><a href=\"tools.php?op=lookupmove&field=" . $field . "&id=" . $row['ID']
                . "&move=up\"><img src=\"assets/images/up.gif\"></a></td><td>&nbsp;</td><td><a href=\"tools.php?op=dellookupvalue&field=" . $field . "&id=" . $row['ID']
                . "\"><img src=\"assets/images/delete.gif\" /></a>&nbsp;<a href=\"tools.php?op=editlookupvalue&field=" . $field . "&id=" . $row['ID'] . "\">" . $row['value'] . "</a></td>";
        } else { //other rows
            $form
                .= "<td><a href=\"tools.php?op=lookupmove&field=" . $field . "&id=" . $row['ID'] . "&move=up\"><img src=\"assets/images/up.gif\"></a></td><td><a href=\"tools.php?op=lookupmove&field="
                . $field . "&id=" . $row['ID'] . "&move=down\"><img src=\"assets/images/down.gif\"></a></td><td><a href=\"tools.php?op=dellookupvalue&field=" . $field . "&id=" . $row['ID']
                . "\"><img src=\"images/delete.gif\" /></a>&nbsp;<a href=\"tools.php?op=editlookupvalue&field=" . $field . "&id=" . $row['ID'] . "\">" . $row['value'] . "</a></td>";
        }
        $form .= "</tr>";
        ++$count;
    }
    $form .= "</table>";
    $form .= "<form method=\"post\" action=\"tools.php?op=addlookupvalue&field=" . $field . "\">";
    $form .= "<input type=\"text\" name=\"value\" style=\"width: 140px;\">&nbsp;";
    $form .= "<input type=\"submit\" value=\"Add value\" />";
    $form .= _MA_PEDIGREE_DELVALUE;
//    $form .= '<br/><br/><input type="submit" name="reset" value=Exit />&nbsp;';
    $xoopsTpl->assign("form", $form);
}

/**
 * @param $field
 * @param $id
 * @param $move
 */
function lookupmove($field, $id, $move)
{
    global $xoopsDB;
    //find next id
    $sql    = "SELECT * FROM " . $xoopsDB->prefix("pedigree_lookup" . $field) . " ORDER BY `order`";
    $result = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($result)) {
        $values[] = array('id' => $row['ID'], 'content' => $row['value'], 'orderof' => $row['order']);
    }
    $arraycount = 0;
    foreach ($values as $key => $value) {
        //find current ID location
        if ($value['id'] == $id) {
            $arraylocation = $arraycount;
        }
        ++$arraycount;
    }
    unset($arraycount);

    $currentorder = $values[$arraylocation]['orderof'];
    $currentid    = $values[$arraylocation]['id'];

    if ($move == "down") {
        $nextid    = $values[$arraylocation + 1]['id'];
        $nextorder = $values[$arraylocation + 1]['orderof'];
    } else {
        $nextid    = $values[$arraylocation - 1]['id'];
        $nextorder = $values[$arraylocation - 1]['orderof'];
    }
    $sql = "UPDATE `draaf_pedigree_lookup" . $field . "` SET `order` = '" . $nextorder . "' WHERE `ID` = '" . (int) $id . "'";
    $xoopsDB->queryF($sql);
    $sql = "UPDATE `draaf_pedigree_lookup" . $field . "` SET `order` = '" . $currentorder . "' WHERE `ID` = '" . (int) $nextid . "'";
    $xoopsDB->queryF($sql);
    editlookup($field);
}

/**
 * @param $field
 * @param $id
 */
function editlookupvalue($field, $id)
{
    global $xoopsDB, $xoopsTpl;
    $sql    = "SELECT * FROM " . $xoopsDB->prefix("pedigree_lookup" . $field) . " WHERE ID =" . $id;
    $result = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($result)) {
        $form = "<form method=\"post\" action=\"tools.php?op=savelookupvalue&field=" . $field . "&id=" . $id . "\">";
        $form .= "<input type=\"text\" name=\"value\" value=\"" . $row['value'] . "\" style=\"width: 140px;\">&nbsp;";
        $form .= "<input type=\"submit\" value=\"Save value\" />";
    }
    $xoopsTpl->assign("form", $form);
}

/**
 * @param $field
 * @param $id
 */
function savelookupvalue($field, $id)
{
    global $xoopsDB;
    $value = $xoopsDB->escape(XoopsRequest::getString('value', '', 'post'));
    $SQL = "UPDATE " . $xoopsDB->prefix("pedigree_lookup" . $field) . " SET value = '" . $value . "' WHERE ID = " . $id;
    $xoopsDB->queryF($SQL);
    redirect_header("tools.php?op=editlookup&id=" . $field, 2, "The value has been saved.");
}

/**
 * @param $field
 * @param $id
 */
function dellookupvalue($field, $id)
{
    global $xoopsDB;
    $animal      = new Animal();
    $fields      = $animal->numoffields();
    $userfield   = new Field($field, $animal->getconfig());
    $fieldType   = $userfield->getSetting("FieldType");
    $fieldobject = new $fieldType($userfield, $animal);
    $default     = $xoopsDB->escape($fieldobject->defaultvalue);
    if ($default == $id) {
        redirect_header("tools.php?op=editlookup&id=" . $field, 3, _MA_PEDIGREE_NO_DELETE . $fieldobject->fieldname);
    }
    $sql = "DELETE FROM " . $xoopsDB->prefix("pedigree_lookup" . $field) . " WHERE ID = " . $id;
    $xoopsDB->queryF($sql);
    //change current values to default for deleted value
    $sql
        = "UPDATE " . $xoopsDB->prefix("pedigree_tree") . " SET user" . $field . " = '" . $default . "' WHERE user" . $field . " = '" . $id . "'";
    $xoopsDB->queryF($sql);
    $sql
        = "UPDATE " . $xoopsDB->prefix("pedigree_temp") . " SET user" . $field . " = '" . $default . "' WHERE user" . $field . " = '" . $id . "'";
    $xoopsDB->queryF($sql);
    $sql
        = "UPDATE " . $xoopsDB->prefix("pedigree_trash") . " SET user" . $field . " = '" . $default . "' WHERE user" . $field . " = '" . $id . "'";
    $xoopsDB->queryF($sql);
    editlookup($field);
}

/**
 * @param $field
 */
function addlookupvalue($field)
{
    global $xoopsDB;
    $SQL    = "SELECT ID FROM " . $xoopsDB->prefix("pedigree_lookup" . $field) . " ORDER BY ID DESC LIMIT 1";
    $result = $xoopsDB->query($SQL);
    while ($row = $xoopsDB->fetchArray($result)) {
        $count = $row['ID'];
        ++$count;
        $sql = "INSERT INTO " . $xoopsDB->prefix("pedigree_lookup" . $field) . " VALUES ('" . $count . "', '" . $_POST['value'] . "', '" . $count . "')";
        $xoopsDB->queryF($sql);
    }
    redirect_header("tools.php?op=editlookup&id=" . $field, 2, "The value has been added.");
}

/**
 * @param int $field
 */
function userfields($field = 0)
{
    global $xoopsTpl, $field;
    require_once 'include/checkoutwizard.php';

    $wizard = new CheckoutWizard();
    $action = $wizard->coalesce($_GET['action']);

    $wizard->process($action, $_POST, $_SERVER['REQUEST_METHOD'] == 'POST');
    // only processes the form if it was posted. this way, we
    // can allow people to refresh the page without resubmitting
    // form data

    if ($wizard->isComplete()) {
        if (!$wizard->getValue('field') == 0) // field allready exists (editing mode) {
        {
            $form = _MA_PEDIGREE_FIELPROP;
        } else {
            $form = _MA_PEDIGREE_FIELDPROP_ELSE;
        }
        $form .= "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "?op=userfields&action=" . $wizard->resetAction . "\">";
        $form .= '<input type="submit" value="' . _MA_PEDIGREE_FINISH_BUTTON . '" /></form>';
    } else {
        $form = '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?op=userfields&action=' . $wizard->getStepName() . '">';
        if (!$field == 0) {
            $form .= '<input type="hidden" name="field" value="' . $field . '">';
        }
        if (!$wizard->getValue('field') == 0) // field allready exists (editing mode)
        {
            $form .= '<h2>' . $wizard->getStepProperty('title') . ' - ' . $wizard->getValue('name') . ' - step ' . $wizard->getStepNumber() . '</h2>';
        } else {
            $form .= '<h2>' . $wizard->getStepProperty('title') . ' - step ' . $wizard->getStepNumber() . '</h2>';
        }

        if ($wizard->getStepName() == 'Fieldname') {
            $form .= _MA_PEDIGREE_FIELD_FORM2;
            $form .= '<input type="text" name="name" value="' . htmlSpecialChars($wizard->getValue('name')) . '" />';
            $form .= "</td><td width=25%>";
            if ($wizard->isError('name')) {
                $form .= $wizard->getError('name');
            }
            $form .= "</td></tr>";
            $form .= _MA_PEDIGREE_FIELD_FORM3;
            $form .= '<textarea name="explain" rows="5" cols="15">' . htmlSpecialChars($wizard->getValue('explain')) . '</textarea>';
            $form .= "</td><td width=25%>";
            if ($wizard->isError('explain')) {
                $form .= $wizard->getError('explain');
            }
            $form .= "</td></tr></table>";
            $form .= _MA_PEDIGREE_FIELDNAME;
        } elseif ($wizard->getStepName() == 'Fieldtype') {
            $form .= "<table><tr><td>";
            if ($wizard->getValue('fieldtype') == "") {
                $wizard->setValue('fieldtype', 'textbox');
            }
            foreach ($wizard->fieldtype as $v) {
                $form .= '<input name="fieldtype" type="radio" value="' . $v['value'] . '"';
                if ($wizard->getValue('fieldtype') == $v['value']) {
                    $form .= ' checked =_MA_PEDIGREE_CHECKED';
                }
                $form .= ">" . $v['description'] . "<br />";
            }
            $form .= "</td><td></table>";
        } elseif ($wizard->getStepName() == 'lookup') {
            $count = $wizard->getValue('fc');
            if ($count == "") {
                $i = 1;
            } else {
                $i = $count + 1;
            }

            $form .= '<input type="hidden" name="fc" value="' . $i . '">';
            $form .= "<table>";
            for ($x = 1; $x < $i; ++$x) {
                $form .= "<tr><td>Value : (" . $x . ")</td><td>" . htmlSpecialChars($wizard->getValue('lookup' . $x)) . "</td></tr>";
            }
            $form .= "<tr><td>Value : (" . $i . ")</td><td>";
            $form .= '<input type="text" name="lookup' . $i . '" value="' . htmlSpecialChars($wizard->getValue('lookup' . $i)) . '" />';
            $form .= '<input type="hidden" name="id' . $i . '" value="' . $i . '" />';
            if ($wizard->isError('lookup')) {
                $form .= $wizard->getError('lookup');
            }
            $form .= "</td></tr></table>";
            $form .= "<input type=\"submit\" name=\"addvalue\" value=\"Add Value\">";
        } else {
            if ($wizard->getStepName() == 'Settings') {
                $fieldtype = $wizard->getValue('fieldtype');
                //hassearch
                if ($fieldtype == "textbox" || $fieldtype == "textarea" || $fieldtype == "dateselect" || $fieldtype == "urlfield"
                    || $fieldtype == "radiobutton"
                    || $fieldtype == "selectbox"
                ) {
                    $form .= '<input type="checkbox" name="hassearch" value="hassearch"';
                    if ($wizard->getValue('hassearch') == "hassearch") {
                        $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                    }
                    $form .= '/>' . _MA_PEDIGREE_FIELDSEARCH . ' <br />';
                } else {
                    $form .= '<input type="checkbox" name="hassearch" disabled="true" value="hassearch" />' . _MA_PEDIGREE_FIELDSEARCH . '<br />';
                }
                //viewinpedigree
                $form .= '<input type="checkbox" name="viewinpedigree" value="viewinpedigree"';
                if ($wizard->getValue('viewinpedigree') == "viewinpedigree") {
                    $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                }
                $form .= ' />' . _MA_PEDIGREE_SHOWFIELD . '<br />';
                //viewinadvanced
                if ($fieldtype == "radiobutton" || $fieldtype == "selectbox") {
                    $form .= '<input type="checkbox" name="viewinadvanced" value="viewinadvanced"';
                    if ($wizard->getValue('viewinadvanced') == "viewinadvanced") {
                        $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                    }
                    $form .= ' />' . _MA_PEDIGREE_SHOWFIELD_ADVANCE . '<br />';
                } else {
                    $form
                        .= '<input type="checkbox" name="viewinadvanced" disabled="true" value="viewinadvanced" />' . _MA_PEDIGREE_SHOWFIELD_ADVANCE . '<br />';
                }
                //viewinpie
                if ($fieldtype == "radiobutton" || $fieldtype == "selectbox") {
                    $form .= '<input type="checkbox" name="viewinpie" value="viewinpie"';
                    if ($wizard->getValue('viewinpie') == "viewinpie") {
                        $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                    }
                    $form .= ' />' . _MA_PEDIGREE_SHOWFIELD_PIECHART . '<br />';
                } else {
                    $form
                        .= '<input type="checkbox" name="viewinpie" disabled="true" value="viewinpie" />' . _MA_PEDIGREE_SHOWFIELD_PIECHART . '<br />';
                }
                //viewinlist
                if ($fieldtype == "textbox" || $fieldtype == "dateselect" || $fieldtype == "urlfield" || $fieldtype == "radiobutton"
                    || $fieldtype == "selectbox"
                ) {
                    $form .= '<input type="checkbox" name="viewinlist" value="viewinlist"';
                    if ($wizard->getValue('viewinlist') == "viewinlist") {
                        $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                    }
                    $form .= ' />' . _MA_PEDIGREE_SHOWFIELD_RESULT . '<br />';
                } else {
                    $form
                        .= '<input type="checkbox" name="viewinlist" disabled="true" value="viewinlist" />' . _MA_PEDIGREE_SHOWFIELD_RESULT . '<br />';
                }
                //add a litter
                $form .= '<input type="checkbox" name="Litter" value="Litter"';
                if ($wizard->getValue('Litter') == "Litter") {
                    $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                } elseif ($wizard->getValue('Generallitter') == "Generallitter") {
                    //disable if generallitter is active (you cant have both)
                    $form .= ' disabled="true" ';
                }
                $form .= ' />' . _MA_PEDIGREE_SHOWFIELD_ADDLITTER . '<br />';

                //add a litter general
                $form .= '<input type="checkbox" name="Generallitter" value="Generallitter"';
                if ($wizard->getValue('Generallitter') == "Generallitter") {
                    $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                } elseif ($wizard->getValue('Litter') == "Litter") {
                    //disable if add a litter is active (you cant have both)
                    $form .= ' disabled="true" ';
                }
                $form .= ' />' . _MA_PEDIGREE_SHOWFIELD_ADDLITTGLOBAL . '<br />';
            } else {
                if ($wizard->getStepName() == 'search') {
                    $form .= _MA_PEDIGREE_SEARCH_NAME;
                    $currentsearchname = $wizard->getValue('searchname');
                    if ($currentsearchname == "") {
                        $currentsearchname = htmlSpecialChars($wizard->getValue('name'));
                    } else {
                        $currentsearchname = htmlSpecialChars($wizard->getValue('searchname'));
                    }
                    $form .= '<input type="text" name="searchname" value="' . $currentsearchname . '" />';
                    $form .= "</td><td width=25%>";
                    if ($wizard->isError('searchname')) {
                        $form .= $wizard->getError('searchname');
                    }
                    $form .= "</td></tr>";
                    $form .= _MA_PEDIGREE_FIELDEXPLANSEARCH;
                    $form
                        .= '<textarea name="searchexplain" rows="5" cols="15">' . htmlSpecialChars($wizard->getValue('searchexplain')) . '</textarea>';
                    $form .= "</td><td width=25%>";
                    if ($wizard->isError('searchexplain')) {
                        $form .= $wizard->getError('searchexplain');
                    }
                    $form .= "</td></tr></table>";
                    $form .= _MA_PEDIGREE_SEARCHNAME_EXPLAIN;
                } elseif ($wizard->getStepName() == 'defaultvalue') {
                    if ($wizard->getValue('fieldtype') == 'selectbox' || $wizard->getValue('fieldtype') == 'radiobutton') {
                        $count = $wizard->getValue('fc');
                        $form .= "Default value : <select size=\"1\" name=\"defaultvalue\">";
                        $radioarray = $wizard->getValue('radioarray');
                        for ($x = 0; $x < $count; ++$x) {
                            $form .= "<option value=\"" . $radioarray[$x]['id'] . "\"";
                            if ($wizard->getValue('defaultvalue') == $radioarray[$x]['id']) {
                                $form .= " selected=\"selected\" ";
                            }
                            $form .= ">" . $radioarray[$x]['value'] . "</option>";
                        }
                        $form .= "</select>";
                    } else {
                        $form .= '' . _MA_PEDIGREE_FIELDVALUE . ' <input type="text" name="defaultvalue" value="' . htmlSpecialChars(
                                $wizard->getValue('defaultvalue')
                            ) . '" />';
                    }
                    $form .= _MA_PEDIGREE_DEFAUTVALUE_EXPLAIN;

                } elseif ($wizard->getStepName() == 'confirm') {
                    if (!$wizard->getValue('field') == 0) // field allready exists (editing mode) { {
                    {
                        $form .= _MA_PEDIGREE_FIELDCONTROL1;
                    }
                } else {
                    $form .= _MA_PEDIGREE_FIELDCONTROL2;
                }
//            }
//        }
                $form .= _MA_PEDIGREE_FIELDCONTROL4 . $wizard->getValue('name') . "</b><br />";
                $form .= _MA_PEDIGREE_FIELDCONTROL5 . stripslashes($wizard->getValue('explain')) . "</b><br />";
                $form .= _MA_PEDIGREE_FIELDCONTROL6 . $wizard->getValue('fieldtype') . "</b><br />";
                if ($wizard->getValue('fieldtype') == 'selectbox' || $wizard->getValue('fieldtype') == 'radiobutton') {
                    $count = $wizard->getValue('fc');
                    for ($x = 1; $x < $count + 1; ++$x) {
                        $radioarray[] = array('id' => $wizard->getValue('id' . $x), 'value' => $wizard->getValue('lookup' . $x));
                    }
                    $val = $wizard->getValue('defaultvalue');
                    $form .= _MA_PEDIGREE_FIELDCONTROL7 . $wizard->getValue('lookup' . $val) . "</b><br />";
                } else {
                    $form .= _MA_PEDIGREE_FIELDCONTROL7 . $wizard->getValue('defaultvalue') . "</b><br />";
                }
                if ($wizard->getValue('hassearch') == "hassearch") {
                    $form .= 'Field can be searched : <span style="font-weight: bold;">  Yes</span><br />';
                    $form .= _MA_PEDIGREE_FIELDSEARCH . $wizard->getValue('searchname') . "</b><br />";
                    $form .= _MA_PEDIGREE_FIELDCONTROL5 . $wizard->getValue('searchexplain') . "</b><br />";
                }
                if ($wizard->getValue('viewinpedigree') == "viewinpedigree") {
                    $form .= _MA_PEDIGREE_SYNTH1;
                }
                if ($wizard->getValue('viewinadvanced') == "viewinadvanced") {
                    $form .= _MA_PEDIGREE_SYNTH2;
                }
                if ($wizard->getValue('viewinpie') == "viewinpie") {
                    $form .= _MA_PEDIGREE_SYNTH3;
                }
                if ($wizard->getValue('viewinlist') == "viewinlist") {
                    $form .= _MA_PEDIGREE_SYNTH4;
                }
                if ($wizard->getValue('Litter') == "Litter") {
                    $form .= _MA_PEDIGREE_SYNTH5;
                }
                if ($wizard->getValue('Generallitter') == "Generallitter") {
                    $form .= _MA_PEDIGREE_SYNTH6;
                }
                $form .= _MA_PEDIGREE_FIELDCONTROL3 . '<br /><br />';
            }
        }
    }

    if (!$wizard->isFirstStep()) {
        $form .= '<p><input type="submit" name="previous" value="&lt;&lt; ' . _MA_PEDIGREE_PREVIOUS_BUTTON . '">&nbsp;';
    }
    $form .= '<input type="submit" name="reset" value=Exit />&nbsp;';
    $last = $wizard->isLastStep() ? _MA_PEDIGREE_FINISH_BUTTON : _MA_PEDIGREE_NEXT_BUTTON;
    $form .= '<input type="submit" value="' . $last . '&gt;&gt;" />';
    $form .= "</p></form>";

    $xoopsTpl->assign("form", $form);
}

function credits()
{
    global $xoopsTpl;
    $module_handler = xoops_getHandler('module');
    $module         = $module_handler->getByDirname("pedigree");
    $config_handler = xoops_getHandler('config');
    $moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));
    $form
                    = "Pedigree database module<br /><br /><li>Programming : James Cotton<br/><li>Design & Layout : Ton van der Hagen<br /><li>Version : " . round(
            $module->getVar('version') / 100,
            2
        ) . " " . $module->getVar('module_status') . "<br /><br />Technical support :<br /><li><a href=\"http://www.xoops.org\">www.xoops.org</a><hr>";

    $xoopsTpl->assign("form", $form);
}

function database()
{
    global $xoopsTpl;
    $form = _MA_PEDIGREE_QUERY_EXPLAN;
    $xoopsTpl->assign("form", $form);
}

function userq()
{
    global $xoopsTpl, $xoopsModule;
    $form = _MA_PEDIGREE_QUERIE_EXPLAN;
    $d    = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/userqueries/";

    $dir = opendir($d);
    while ($f = readdir($dir)) {
        if (!preg_match("/\.jpg/", $f) && $f !== '.' && $f !== '..') {
            $form .= "<li><a href=\"tools.php?op=userqrun&f=" . $f . "\">" . $f . "</a>";
        }
    }
    $xoopsTpl->assign("form", $form);
}

/**
 * @param $file
 */
function userqrun($file)
{
    global $xoopsTpl, $xoopsDB, $xoopsModule;
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/userqueries/" . $file;
    $xoopsTpl->assign("form", $form);
}

function database_oa()
{
    global $xoopsTpl, $xoopsDB;
    $form = _MA_PEDIGREE_ANCEST_EXPLAN;
    $sql
            = "SELECT d.id AS d_id, d.naam AS d_naam
            FROM " . $xoopsDB->prefix("pedigree_tree") . " d
            LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " m ON m.id = d.mother
            LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " f ON f.id = d.father
            LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " mm ON mm.id = m.mother
            LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " mf ON mf.id = m.father
            LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " fm ON fm.id = f.mother
            LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " ff ON ff.id = f.father
            WHERE
            d.mother = d.id
            OR d.father = d.id
            OR m.mother = d.id
            OR m.father = d.id
            OR f.mother = d.id
            OR f.father = d.id
            OR mm.mother = d.id
            OR mm.father = d.id
            OR mf.mother = d.id
            OR mf.father = d.id
            OR fm.mother = d.id
            OR fm.father = d.id
            OR ff.mother = d.id
            OR ff.father = d.id
            ";
    $result = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($result)) {
        $form .= "<li><a href=\"pedigree.php?pedid=" . $row['d_id'] . "\">" . $row['d_naam'] . "</a> [own parent or grandparent]<br />";
    }
    $xoopsTpl->assign("form", $form);
}

function database_fp()
{
    global $xoopsTpl, $xoopsDB;
    $form = _MA_PEDIGREE_GENDER_EXPLAN;
    $sql
            = "SELECT d.id AS d_id, d.naam AS d_naam, d.mother as d_mother, m.roft as m_roft
            FROM " . $xoopsDB->prefix("pedigree_tree") . " d
            LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " m ON m.id = d.mother
            WHERE
            d.mother = m.id
            AND m.roft = '0' ";
    $result = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($result)) {
        $form .= "<li><a href=\"dog.php?id=" . $row['d_id'] . "\">" . $row['d_naam'] . "</a> [mother seems to be male]<br />";
    }
    $sql
            = "SELECT d.id AS d_id, d.naam AS d_naam, d.father as d_father, f.roft as f_roft
            FROM " . $xoopsDB->prefix("pedigree_tree") . " d
            LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " f ON f.id = d.father
            WHERE
            d.father = f.id
            AND f.roft = '1' ";
    $result = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($result)) {
        $form .= "<li><a href=\"dog.php?id=" . $row['d_id'] . "\">" . $row['d_naam'] . "</a> [father seems to be female]<br />";
    }
    $xoopsTpl->assign("form", $form);
}

function pro()
{
    global $xoopsTpl;
    $form = "Pro version settings go here.<hr>";
    $xoopsTpl->assign("form", $form);
}

function deleted()
{
    global $xoopsTpl, $xoopsDB, $moduleConfig;
    $form
            = "Below the line are the animals which have been deleted from your database.<br /><br />By clicking on the name you can reinsert them into the database.<br />By clicking on the 'X' in front of the name you can permanently delete the animal.<hr>";
    $sql    = "SELECT ID, NAAM	FROM " . $xoopsDB->prefix("pedigree_trash");
    $result = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($result)) {
        $form
            .= "<a href=\"tools.php?op=delperm&id=" . $row['ID'] . "\"><img src=\"images/delete.gif\" /></a>&nbsp;<a href=\"tools.php?op=restore&id=" . $row['ID'] . "\">" . $row['NAAM']
            . "</a><br />";
    }
    if ($xoopsDB->getRowsNum($result) > 0) {
        $form .= "<hr><a href=\"tools.php?op=delall\">Click here</a> to remove all these " . $moduleConfig['animalTypes'] . " permenantly ";
    }
    $xoopsTpl->assign("form", $form);
}

/**
 * @param $id
 */
function delperm($id)
{
    global $xoopsTpl, $xoopsDB;
    $sql = "DELETE FROM " . $xoopsDB->prefix("pedigree_trash") . " WHERE ID = " . $id;
    $xoopsDB->queryF($sql);
    deleted();
}

function delall()
{
    global $xoopsTpl, $xoopsDB;
    $sql = "DELETE FROM " . $xoopsDB->prefix("pedigree_trash");
    $xoopsDB->queryF($sql);
    deleted();
}

/**
 * @param $id
 */
function restore($id)
{
    global $xoopsTpl, $xoopsDB;
    $queryvalues = '';
    $sql         = "SELECT * from " . $xoopsDB->prefix("pedigree_trash") . " WHERE ID = " . $id;
    $result      = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($result)) {

        foreach ($row as $key => $values) {
            $queryvalues .= "'" . $xoopsDB->escape($values) . "',";
        }
        $outgoing = substr_replace($queryvalues, "", -1);
        $query    = "INSERT INTO " . $xoopsDB->prefix("pedigree_tree") . " VALUES (" . $outgoing . ")";
        $xoopsDB->queryF($query);
        $delquery = "DELETE FROM " . $xoopsDB->prefix("pedigree_trash") . " WHERE ID = " . $id;
        $xoopsDB->queryF($delquery);
        $form = "<li><a href=\"pedigree.php?pedid=" . $row['ID'] . "\">" . $row['NAAM'] . "</a> has been restored into the database.<hr>";
    }
    if (isset($form)) {
        $xoopsTpl->assign("form", $form);
    }

}

function settings()
{
    global $xoopsUser, $xoopsTpl, $moduleConfig;
    include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    $form = new XoopsThemeForm(_MA_PEDIGREE_BLOCK_SETTING, 'settings', 'tools.php?op=settingssave', 'POST', 1);
    $form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
    $select  = new XoopsFormSelect(_MA_PEDIGREE_RESULT, 'perpage', $value = $moduleConfig['perpage'], $size = 1, $multiple = false);
    $options = array('50' => 50, '100' => 100, '250' => 250, '500' => 500, '1000' => 1000, '2000' => 2000, '5000' => 5000, '10000' => 10000);
    foreach ($options as $key => $values) {
        $select->addOption($key, $name = $values, $disabled = false);
    }
    unset($options);
    $form->addElement($select);
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_EXPLAIN_NUMB));
    $radiowel = new XoopsFormRadio(_MA_PEDIGREE_SHOW_WELC, 'showwelcome', $value = $moduleConfig['showwelcome']);
    $radiowel->addOption(1, $name = _MA_PEDIGREE_YES, $disabled = false);
    $radiowel->addOption(0, $name = _MA_PEDIGREE_NO, $disabled = false);
    $form->addElement($radiowel);
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_WELC_SCREEN));
    $radio = new XoopsFormRadio(_MA_PEDIGREE_BREED_FIELD, 'ownerbreeder', $value = $moduleConfig['ownerbreeder']);
    $radio->addOption(1, $name = _MA_PEDIGREE_YES, $disabled = false);
    $radio->addOption(0, $name = _MA_PEDIGREE_NO, $disabled = false);
    $form->addElement($radio);
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_OWN_EXPLAIN));
    $radiobr = new XoopsFormRadio(_MA_PEDIGREE_SHOW_BROT, 'brothers', $value = $moduleConfig['brothers']);
    $radiobr->addOption(1, $name = _MA_PEDIGREE_YES, $disabled = false);
    $radiobr->addOption(0, $name = _MA_PEDIGREE_NO, $disabled = false);
    $form->addElement($radiobr);
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_BROT_EXPLAIN));
    $radiolit = new XoopsFormRadio(_MA_PEDIGREE_USE_LITTER, 'uselitter', $value = $moduleConfig['uselitter']);
    $radiolit->addOption(1, $name = _MA_PEDIGREE_YES, $disabled = false);
    $radiolit->addOption(0, $name = _MA_PEDIGREE_NO, $disabled = false);
    $form->addElement($radiolit);
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_USE_LITTER_EXPLAIN));
    $radioch = new XoopsFormRadio(PED_SHOW_KITT_A . $moduleConfig['children'] . _MA_PEDIGREE_SHOW_KITT_B, 'pups', $value = $moduleConfig['pups']);
    $radioch->addOption(1, $name = _MA_PEDIGREE_YES, $disabled = false);
    $radioch->addOption(0, $name = _MA_PEDIGREE_NO, $disabled = false);
    $form->addElement($radioch);
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_KITT_EXPLAIN));
    $radiosoi = new XoopsFormRadio(_MA_PEDIGREE_SHOW_PICT, 'lastimage', $value = $moduleConfig['lastimage']);
    $radiosoi->addOption(1, $name = _MA_PEDIGREE_YES, $disabled = false);
    $radiosoi->addOption(0, $name = _MA_PEDIGREE_NO, $disabled = false);
    $form->addElement($radiosoi);
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_PICT_EXPLAIN));
    $form->addElement(new XoopsFormButton('', 'button_id', 'Submit', 'submit'));
    $xoopsTpl->assign("form", $form->render());
}

function settingssave()
{
    global $xoopsDB;
    $settings = array('perpage', 'ownerbreeder', 'brothers', 'uselitter', 'pups', 'showwelcome');
    foreach ($_POST as $key => $values) {
        if (in_array($key, $settings)) {
            $query = "UPDATE " . $xoopsDB->prefix("config") . " SET conf_value = '" 
                . $xoopsDB->escape($values) . "' WHERE conf_name = '" 
                . $xoopsDB->escape($key) . "'";
            $xoopsDB->query($query);
        }
    }
    redirect_header("tools.php?op=settings", 1, "Your settings have been saved.");
}

function lang()
{
    global $xoopsUser, $xoopsTpl, $moduleConfig;
    include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    $form = new XoopsThemeForm(_MA_PEDIGREE_BLOCK_NAME, 'language', 'tools.php?op=langsave', 'POST');
    $form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
    $form->addElement(new XoopsFormText(_MA_PEDIGREE_TYPE_AN, 'animalType', $size = 50, $maxsize = 255, $value = $moduleConfig['animalType']));
    $form->addElement(
        new XoopsFormLabel(
            _MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FIELD_EXPLAIN . $moduleConfig['animalType'] . _MA_PEDIGREE_SEARCH_FORM . $moduleConfig['animalType'] . '</b>.'
        )
    );
    $form->addElement(
        new XoopsFormText(_MA_PEDIGREE_TYPE_AN, 'animalTypes', $size = 50, $maxsize = 255, $value = $value = $moduleConfig['animalTypes'])
    );
    $form->addElement(
        new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FIELD_EXPLAIN2 . $moduleConfig['animalTypes'] . _MA_PEDIGREE_FIELD_EXPLAIN3)
    );
    $form->addElement(new XoopsFormText(_MA_PEDIGREE_MALE, 'male', $size = 50, $maxsize = 255, $value = $moduleConfig['male']));
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_MALE_EXPLAIN));
    $form->addElement(new XoopsFormText(_MA_PEDIGREE_FEMALE, 'female', $size = 50, $maxsize = 255, $value = $moduleConfig['female']));
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FEMALE_EXPLAIN));
    $form->addElement(new XoopsFormText(_MA_PEDIGREE_CHILDREN, 'children', $size = 50, $maxsize = 255, $value = $moduleConfig['children']));
    $form->addElement(
        new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_KITTEN_EXPLAIN1 . $moduleConfig['animalTypes'] . _MA_PEDIGREE_KITTEN_EXPLAIN2)
    );
    $form->addElement(new XoopsFormText(_MA_PEDIGREE_MOTHER, 'mother', $size = 50, $maxsize = 255, $value = $moduleConfig['mother']));
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_MOTHER1 . $moduleConfig['animalTypes'] . _MA_PEDIGREE_MOTHER2));
    $form->addElement(new XoopsFormText(_MA_PEDIGREE_FATHER, 'father', $size = 50, $maxsize = 255, $value = $moduleConfig['father']));
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FATHER1 . $moduleConfig['animalTypes'] . _MA_PEDIGREE_FATHER2));
    $form->addElement(new XoopsFormText(_MA_PEDIGREE_LITTER, 'litter', $size = 50, $maxsize = 255, $value = $moduleConfig['litter']));
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_LITTER1));
    $form->addElement(new XoopsFormTextArea(_MA_PEDIGREE_WELC_TEXT, 'welcome', $value = $moduleConfig['welcome'], $rows = 15, $cols = 50));

    $form->addElement(
        new XoopsFormLabel(
            _MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_WELC_TXT_EXPLAIN . $moduleConfig['animalType'] . '<br />[animalTypes] = ' . $moduleConfig['animalTypes'] . _MA_PEDIGREE_WELC_TXT_EXPLAIN2
        )
    );
    $form->addElement(new XoopsFormButton('', 'button_id', 'Submit', 'submit'));
    $xoopsTpl->assign("form", $form->render());
}

function langsave()
{
    global $xoopsDB, $xoopsUser, $xoopsTpl;
    $form     = "";
    $settings = array('animalType', 'animalTypes', 'male', 'female', 'children', 'mother', 'father', 'litter', 'welcome');
    foreach ($_POST as $key => $values) {
        if (in_array($key, $settings)) {
            $query = "UPDATE " . $xoopsDB->prefix("config") . " SET conf_value = '" . $values . "' WHERE conf_name = '" . $key . "'";
            $xoopsDB->query($query);
        }
    }
    $form .= "Your settings have been saved.<hr>";
    $xoopsTpl->assign("form", $form);
}
