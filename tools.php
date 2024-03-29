<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * animal module for xoops
 *
 * @copyright   {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @package     pedigree
 * @subpackage  class
 * @author      XOOPS Module Dev Team
 */

use Xmf\Request;
use XoopsModules\Pedigree\{
    Helper
};

/** @var XoopsModules\Pedigree\Helper $helper */
require_once __DIR__ . '/header.php';
$helper->load('main');

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_tools.tpl';
include $GLOBALS['xoops']->path('/header.php');

//check for access
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('index.php', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
//$xoTheme->addScript(PEDIGREE_URL . '/assets/js/jquery.ThickBox/thickbox-compressed.js');

$xoTheme->addScript($helper->url('assets/js/jquery.magnific-popup.min.js'));
$xoTheme->addScript($helper->url('assets/js/colpick.js'));

$xoTheme->addStylesheet($helper->url('assets/css/colpick.css'));
$xoTheme->addStylesheet($helper->url('assets/css/magnific-popup.css'));
$xoTheme->addStylesheet($helper->url('assets/css/style.css'));

global $field;
//add JS routines
echo "<script type=\"text/javascript\" src=\"assets/js/picker.js\"></script>\n";

//set form to be empty
$form = '';
/*
//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/
$op = Request::getCmd('op', 'none', 'GET');

//always check to see if a certain field was referenced
$field = Request::getInt('field', null, 'GET');

switch ($op) {
    case 'lang':
        require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new \XoopsThemeForm(_MA_PEDIGREE_BLOCK_NAME, 'language', $helper->url('tools.php?op=langsave'), 'post', true);
        $form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
        $form->addElement(new \XoopsFormText(_MA_PEDIGREE_TYPE_AN, 'animalType', $size = 50, $maxsize = 255, $value = $helper->getConfig('animalType')));
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FIELD_EXPLAIN . $helper->getConfig('animalType') . _MA_PEDIGREE_SEARCH_FORM . $helper->getConfig('animalType') . '</b>.'));
        $form->addElement(new \XoopsFormText(_MA_PEDIGREE_TYPE_AN, 'animalTypes', $size = 50, $maxsize = 255, $value = $value = $helper->getConfig('animalTypes')));
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FIELD_EXPLAIN2 . $helper->getConfig('animalTypes') . _MA_PEDIGREE_FIELD_EXPLAIN3));
        $form->addElement(new \XoopsFormText(_MA_PEDIGREE_MALE, 'male', $size = 50, $maxsize = 255, $value = $helper->getConfig('male')));
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_MALE_EXPLAIN));
        $form->addElement(new \XoopsFormText(_MA_PEDIGREE_FEMALE, 'female', $size = 50, $maxsize = 255, $value = $helper->getConfig('female')));
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FEMALE_EXPLAIN));
        $form->addElement(new \XoopsFormText(_MA_PEDIGREE_CHILDREN, 'children', $size = 50, $maxsize = 255, $value = $helper->getConfig('children')));
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_KITTEN_EXPLAIN1 . $helper->getConfig('animalTypes') . _MA_PEDIGREE_KITTEN_EXPLAIN2));
        $form->addElement(new \XoopsFormText(_MA_PEDIGREE_MOTHER, 'mother', $size = 50, $maxsize = 255, $value = $helper->getConfig('mother')));
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_MOTHER1 . $helper->getConfig('animalTypes') . _MA_PEDIGREE_MOTHER2));
        $form->addElement(new \XoopsFormText(_MA_PEDIGREE_FATHER, 'father', $size = 50, $maxsize = 255, $value = $helper->getConfig('father')));
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FATHER1 . $helper->getConfig('animalTypes') . _MA_PEDIGREE_FATHER2));
        $form->addElement(new \XoopsFormText(_MA_PEDIGREE_LITTER, 'litter', $size = 50, $maxsize = 255, $value = $helper->getConfig('litter')));
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_LITTER1));
        $form->addElement(new \XoopsFormTextArea(_MA_PEDIGREE_WELC_TEXT, 'welcome', $value = $helper->getConfig('welcome'), $rows = 15, $cols = 50));

        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_WELC_TXT_EXPLAIN . $helper->getConfig('animalType') . '<br>[animalTypes] = ' . $helper->getConfig('animalTypes') . _MA_PEDIGREE_WELC_TXT_EXPLAIN2));
        $form->addElement(new \XoopsFormButton('', 'button_id', 'Submit', 'submit'));
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'langsave':
        $form     = '';
        $settings = [
            'animalType',
            'animalTypes',
            'male',
            'female',
            'children',
            'mother',
            'father',
            'litter',
            'welcome',
        ];
        foreach ($_POST as $key => $values) {
            if (in_array($key, $settings)) {
                $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value = '{$values}' WHERE conf_name = '{$key}'";
                $GLOBALS['xoopsDB']->query($query);
            }
        }
        $form .= 'Your settings have been saved.<hr>';
        $GLOBALS['xoopsTpl']->assign('form', $form);
        break;
    case 'colours':
        //colour variables
        [$actlink, $even, $odd, $text, $hovlink, $head, $body, $title] = Pedigree\Utility::getColourScheme();
        /*
        $actlink = $colors[0];
        $even    = $colors[1];
        $odd     = $colors[2];
        $text    = $colors[3];
        $hovlink = $colors[4];
        $head    = $colors[5];
        $body    = $colors[6];
        $title   = $colors[7];
        */
        echo "<script type=\"text/javascript\">\n"
             . "  $('.color-box').colpick({\n"
             . "    colorScheme: 'dark',\n"
             . "    layout: 'rgbhex',\n"
             . "    color: 'ff8800',\n"
             . "    onSubmit: function (hsb, hex, rgb, el) {\n"
             . "      $(el).css('background-color', '#' + hex);\n"
             . "      $(el).colpickHide();\n"
             . "    }\n"
             . "  })\n"
             . "  .css('background-color', '#ff8800');\n"
             . "  $('#picker').colpick({\n"
             . "    layout: 'hex',\n"
             . "    submit: 0,\n"
             . "    colorScheme: 'dark',\n"
             . "    onChange: function (hsb, hex, rgb, el, bySetColor) {\n"
             . "      $(el).css('border-color', '#' + hex);\n"
             // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
             . "      if (!bySetColor) $(el).val(hex);\n"
             . "    }\n"
             . "  }).keyup(function () {\n"
             . "  $(this).colpickSetColor(this.value);\n"
             . "  });\n"
             . "</script>\n"
             . "<script type=\"text/javascript\" src=\"assets/js/jscolor/jscolor.js\"></script>\n"
             . "<script type=\"text/javascript\">\n"
             . "  function changeBackgroundColor(objDivID, colorvalue) {\n"
             . "    document.getElementById(objDivID).style.backgroundColor = colorvalue;\n"
             . "  }\n"
             . "  function changeTextColor(objDivID, colorvalue) {\n"
             . "    document.getElementById(objDivID).style.color = colorvalue;\n"
             . "  }\n"
             . "</script>\n";

        $form = _MA_PEDIGREE_BGCOLOR . '<br><br>';
        $form .= "<form name=\"myForm\" action=\"tools.php?op=savecolours\" method=\"POST\">\n"
                 . $GLOBALS['xoopsSecurity']->getTokenHTML()
                 . "\n"
                 . "<table>\n"
                 . "<!--\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_TXT_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  <input type=\"text\" id=\"text\" name=\"text\" value=\"{$text}\" size=\"11\" maxlength=\"7\">\n"
                 . "  <a href=\"TCP.popup(document.forms['myForm'].elements['text'])\">\n"
                 . "  <img width=\"15\" height=\"13\" border=\"0\" alt=\"Click Here to pick the color\" src=\"assets/images/sel.gif\"></a>\n"
                 . "</td></tr>\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_LINK_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  <input type=\"text\" id=\"actlink\" name=\"actlink\" value=\"{$actlink}\" size=\"11\" maxlength=\"7\">\n"
                 . "  <a href=\"TCP.popup(document.forms['myForm'].elements['actlink'])\">\n"
                 . "  <img width=\15\ height=\13\ border=\0\ alt=\"Click Here to Pick the color\" src=\"assets/images/sel.gif\"></a>\n"
                 . "</td></tr>\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_BACK1_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  <input type=\"text\" id=\"even\" name=\"even\" value=\"{$even}\" size=\"11\" maxlength=\"7\">\n"
                 . "  <a href=\"TCP.popup(document.forms['myForm'].elements['even'])\">\n"
                 . "  <img width=\"15\" height=\"13\" border=\"0\" alt=\"Click Here to Pick the color\" src=\"assets/images/sel.gif\"></a>\n"
                 . "</td></tr>\n"
                 . "<tr><td>\n"
                 . _MA_PEDIGREE_BACK2_COLOR
                 . "</td>\n"
                 . " <td>\n"
                 . "  <input type=\"text\" id=\"body\" name=\"body\" value=\"{$body}\" size=\"11\" maxlength=\"7\">\n"
                 . "  <a href=\"TCP.popup(document.forms['myForm'].elements['body'])\">\n"
                 . "  <img width=\"15\" height=\"13\" border=\"0\" alt=\"Click Here to Pick the color\" src=\"assets/images/sel.gif\"></a>\n"
                 . "</td></tr>\n"
                 . "-->\n"
                 . "<!--\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_TXT_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  #<input type=\"text\" id=\"picker\" name=\"text\" value=\"{$text}\" size=\"11\" maxlength=\"7\">\n"
                 . "</td></tr>\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_LINK_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  #<input type=\"text\" id=\"picker\" name=\"actlink\" value=\"{$actlink}\" size=\"11\" maxlength=\"7\">\n"
                 . "</td></tr>\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_BACK1_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  #<input type=\"text\" id=\"picker\" name=\"even\" value=\"{$even}\" size=\"11\" maxlength=\"7\">\n"
                 . "</td></tr>\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_BACK2_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  #<input type=\"text\" id=\"picker\" name=\"body\" value=\"{$body}\" size=\"11\" maxlength=\"7\">\n"
                 . "</td></tr>\n"
                 . "-->\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_TXT_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  <input class=\"color {hash:true,caps:false}\" onMouseOver=\"changeTextColor('back4', this.value)\" type=\"text\" name=\"text\" maxlength=\"7\" size=\"7\" id=\"colorpickerField1\" value=\"{$text}\">\n"
                 . "</td></tr>\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_LINK_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  <input class=\"color {hash:true,caps:false}\" onMouseOver=\"changeTextColor('back4', this.value)\" type=\"text\" name=\"actlink\" maxlength=\"7\" size=\"7\" id=\"colorpickerField1\" value=\"{$actlink}\">\n"
                 . "</td></tr>\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_BACK1_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  <input class=\"color {hash:true,caps:false}\" onMouseOver=\"changeTextColor('back4', this.value)\" type=\"text\" name=\"even\" maxlength=\"7\" size=\"7\" id=\"colorpickerField1\" value=\"{$even}\">\n"
                 . "</td></tr>\n"
                 . '<tr><td>'
                 . _MA_PEDIGREE_BACK2_COLOR
                 . "</td>\n"
                 . "<td>\n"
                 . "  <input class=\"color {hash:true,caps:false}\" onMouseOver=\"changeTextColor('back4', this.value)\" type=\"text\" name=\"body\" maxlength=\"7\" size=\"7\" id=\"colorpickerField1\" value=\"{$body}\">\n"
                 . "</td></tr>\n"
                 . "<tr><td>\n<input type=\"submit\" value=\""
                 . _MA_PEDIGREE_SUBMIT_BUTTON
                 . "\"></td><td>&nbsp;</td></tr>\n"
                 . "</table>\n"
                 . "</form>\n";

        $GLOBALS['xoopsTpl']->assign('form', $form);
        break;
    case 'savecolours': // save the colours
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($pHeader->url('tools.php'), 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $color = new \XoopsModules\Pedigree\ImageColor();
        //create darker link hover colour
        $actLink = Request::getString('actlink', '', 'POST');  //active link color in hex
        $even    = Request::getString('even', '', 'POST');     // even color in hex
        $text    = Request::getString('text', '', 'POST');
        $body    = Request::getString('body', '', 'POST');

        $color->setColors($actLink, $actLink);
        $color->changeLightness(-100);
        $dark = $color->rgb2hex($color->color1);
        //create darker 'head' colour
        $color->setColors($even, $even);
        $color->changeLightness(-25);
        $head = $color->rgb2hex($color->color1);
        //create lighter female colour
        $color->setColors($even, $even);
        $color->changeLightness(25);
        $female = $color->rgb2hex($color->color1);

        $col = "{$actLink};{$even};#{$female};{$text};#{$dark};#{$head};{$body};{$actLink}";

        //  $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value = '{$col}' WHERE conf_name = 'colourscheme'";
        $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value = '" . $GLOBALS['xoopsDB']->escape($col) . "' WHERE conf_name = 'colourscheme'";
        $GLOBALS['xoopsDB']->query($query);
        //@todo move hard coded language string to language file(s)
        redirect_header($helper->url('tools.php?op=colours'), 1, 'Your settings have been saved.');
        break;
    case 'settings':
        settings();
        break;
    case 'settingssave':
        $settings = ['perpage', 'ownerbreeder', 'brothers', 'uselitter', 'pups', 'showwelcome'];
        foreach ($_POST as $key => $values) {
            if (in_array($key, $settings)) {
                //            $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value = '" . $values . "' WHERE conf_name = '" . $key . "'";
                $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value = '" . $GLOBALS['xoopsDB']->escape($values) . "' WHERE conf_name = '" . $GLOBALS['xoopsDB']->escape($key) . "'";
                $GLOBALS['xoopsDB']->query($query);
            }
        }
        redirect_header($helper->url('tools.php?op=settings'), 1, 'Your settings have been saved.');
        break;
    case 'pro':
        $form = 'Pro version settings go here.<hr>';
        $GLOBALS['xoopsTpl']->assign('form', $form);
        break;
    case 'userfields':
        userfields($field);
        $uf = true;
        break;
    case 'listuserfields': // Create/Display HTML to display fields
        $form = listuserfields();
        $GLOBALS['xoopsTpl']->assign('form', $form);
        $uf = true;
        break;
    case 'togglelocked':
        //find current status
        $id     = Request::getInt('id', 0);
        $sql    = 'SELECT locked FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " WHERE id = '{$id}'";
        $result = $GLOBALS['xoopsDB']->query($sql);

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            if (0 == $row['locked']) { //not locked, so lock it
                $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " SET locked = '1' WHERE id = '{$field}'";
                $GLOBALS['xoopsDB']->queryF($sql);
            } else { //locked, so unlock it
                $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " SET locked = '0' WHERE id = '{$field}'";
                $GLOBALS['xoopsDB']->queryF($sql);
            }
        }
        $form = listuserfields();
        $GLOBALS['xoopsTpl']->assign('form', $form);
        break;
    case 'fieldmove':
        $move = Request::getCmd('move', '', 'get');
        fieldmove($field, $move);
        break;
    case 'deluserfield':
        $id  = Request::getInt('id', 0, 'get');
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " SET isActive = '0' WHERE id = {$id}";
        $GLOBALS['xoopsDB']->queryF($sql);
        $form = listuserfields();
        $GLOBALS['xoopsTpl']->assign('form', $form);
        break;
    case 'restoreuserfield':
        $id  = Request::getInt('id', 0, 'get');
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " SET isActive = '1' WHERE id = {$id}";
        $GLOBALS['xoopsDB']->queryF($sql);
        $form = listuserfields();
        $GLOBALS['xoopsTpl']->assign('form', $form);
        break;
    case 'editlookup':
        $id = Request::getInt('id', 0, 'get');
        editlookup($id);
        break;
    case 'lookupmove':
        $id   = Request::getInt('id', 0, 'get');
        $move = Request::getCmd('move', '', 'get');
        lookupmove($field, $id, $move);
        break;
    case 'dellookupvalue':
        $id = Request::getInt('id', 0, 'get');
        dellookupvalue($field, $id);
        break;
    case 'addlookupvalue':
        addlookupvalue($field);
        break;
    case 'editlookupvalue':
        $id     = Request::getInt('id', 0, 'get');
        $sql    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $field) . ' WHERE id =' . $id;
        $result = $GLOBALS['xoopsDB']->query($sql);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $form .= '<form method="post" action="' . $helper->url("tools.php?op=savelookupvalue&field={$field}&id={$id}") . "\">\n" . "<input type=\"text\" name=\"value\" value=\"{$row['value']}\" style=\"width: 140px;\">&nbsp;\n" . "<input type=\"submit\" value=\"Save value\">\n";
        }
        $GLOBALS['xoopsTpl']->assign('form', $form);
        break;
    case 'savelookupvalue':
        $id    = Request::getInt('id', 0, 'get');
        $value = Request::getString('value', '', 'POST');
        $SQL   = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $field) . " SET value = '{$value}' WHERE id = {$id}";
        $GLOBALS['xoopsDB']->queryF($SQL);
        redirect_header($helper->url("tools.php?op=editlookup&id={$field}"), 2, 'The value has been saved.');
        break;
    case 'deleted':
        deleted();
        break;
    case 'delperm':
        $id  = Request::getInt('id', 0);
        $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . " WHERE id = {$id}";
        $GLOBALS['xoopsDB']->queryF($sql);
        deleted();
        break;
    case 'delall':
        $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash');
        $GLOBALS['xoopsDB']->queryF($sql);
        deleted();
        break;
    case 'restore':
        $id          = Request::getInt('id', 0, 'get');
        $queryvalues = '';
        $sql         = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' WHERE id = ' . $id;
        $result      = $GLOBALS['xoopsDB']->query($sql);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            foreach ($row as $key => $values) {
                $queryvalues .= "'" . $values . "',";
            }
            $outgoing = substr_replace($queryvalues, '', -1);
            $query    = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' VALUES (' . $outgoing . ')';
            $GLOBALS['xoopsDB']->queryF($query);
            $delquery = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' WHERE id = ' . $id;
            $GLOBALS['xoopsDB']->queryF($delquery);
            $form = '<li><a href="pedigree.php?pedid=' . $row['id'] . '">' . $row['pname'] . '</a> has been restored into the database.<hr>';
        }
        if (isset($form)) {
            $GLOBALS['xoopsTpl']->assign('form', $form);
        }
        break;
    case 'database':
        $form = _MA_PEDIGREE_QUERY_EXPLAN;
        $GLOBALS['xoopsTpl']->assign('form', $form);
        $db = true;
        break;
    case 'userq':
        $form = _MA_PEDIGREE_QUERIE_EXPLAN;
        $d    = $GLOBALS['xoops']->path('modules/' . $GLOBALS['xoopsModule']->dirname() . '/userqueries/');
        $dir  = opendir($d);
        while (false !== ($f = readdir($dir))) {
            if (!preg_match("/\.jpg$/", $f) && ('.' !== $f) && ('..' !== $f)) {
                $form .= "<li><a href='tools.php?op=userqrun&f={$f}'>{$f}</a>";
            }
        }
        $GLOBALS['xoopsTpl']->assign('form', $form);

        $db = true;
        break;
    case 'userqrun':
        $f = Request::getString('f', '', 'get');
        include $GLOBALS['xoops']->path('modules/' . $GLOBALS['xoopsModule']->dirname() . "/userqueries/{$f}");
        $GLOBALS['xoopsTpl']->assign('form', $form);
        $db = true;
        break;
    case 'dbanc':
        $form   = _MA_PEDIGREE_ANCEST_EXPLAN;
        $sql    = 'SELECT d.id AS d_id, d.pname AS d_pname
            FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' d
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' m ON m.id = d.mother
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' f ON f.id = d.father
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mm ON mm.id = m.mother
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mf ON mf.id = m.father
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fm ON fm.id = f.mother
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' ff ON ff.id = f.father
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
            ';
        $result = $GLOBALS['xoopsDB']->query($sql);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $form .= "<li><a href='pedigree.php?pedid={$row['d_id']}'>{$row['d_pname']}</a> [own parent or grandparent]<br>";
        }
        $GLOBALS['xoopsTpl']->assign('form', $form);
        $db = true;
        break;
    case 'fltypar':
        $form   = _MA_PEDIGREE_GENDER_EXPLAN;
        $sql    = 'SELECT d.id AS d_id, d.pname AS d_pname, d.mother AS d_mother, m.roft AS m_roft
            FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' d
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " m ON m.id = d.mother
            WHERE
            d.mother = m.id
            AND m.roft = '0' ";
        $result = $GLOBALS['xoopsDB']->query($sql);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $form .= "<li><a href='dog.php?id={$row['d_id']}'>{$row['d_pname']}</a> [mother seems to be male]<br>";
        }
        $sql    = 'SELECT d.id AS d_id, d.pname AS d_pname, d.father AS d_father, f.roft AS f_roft
            FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' d
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " f ON f.id = d.father
            WHERE
            d.father = f.id
            AND f.roft = '1' ";
        $result = $GLOBALS['xoopsDB']->query($sql);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $form .= '<li><a href="dog.php?id=' . $row['d_id'] . '">' . $row['d_pname'] . '</a> [father seems to be female]<br>';
        }
        $GLOBALS['xoopsTpl']->assign('form', $form);
        $db = true;
        break;
    case 'credits':
        $form = 'Pedigree database module<br><br><li>Programming : James Cotton<br><li>Design & Layout : Ton van der Hagen<br><li>Version : '
                . round($helper->getModule()->getVar('version') / 100, 2)
                . ' '
                . $helper->getModule()->getVar('module_status')
                . '<br><br>Technical support :<br><li><a href="https://xoops.org">www.xoops.org</a><hr>';
        $GLOBALS['xoopsTpl']->assign('form', $form);
        break;
    case 'index':
        $form = '';
        break;
    default:
        userfields();
        $uf = true;
        break;
}

//create tools array
$tools[] = ['title' => _MA_PEDIGREE_GENSTTINGS, 'link' => 'tools.php?op=settings', 'main' => '1'];
/*
if (1 == $helper->getConfig('proversion')) {
  $tools[] = array ( 'title' => "Pro-version settings", 'link' => "tools.php?op=pro", 'main' => "1" );
}
*/
$tools[] = ['title' => _MA_PEDIGREE_LANG_OPTIONS, 'link' => 'tools.php?op=lang', 'main' => '1'];
$tools[] = ['title' => _MA_PEDIGREE_CREATE_USER_FIELD, 'link' => 'tools.php?op=userfields', 'main' => '1'];
$tools[] = ['title' => _MA_PEDIGREE_LIST_USER_FIELD, 'link' => 'tools.php?op=listuserfields', 'main' => '1'];
$tools[] = ['title' => _MA_PEDIGREE_DEFINE_COLOR, 'link' => 'tools.php?op=colours', 'main' => '1'];
$tools[] = ['title' => _MA_PEDIGREE_DELETE_PED, 'link' => 'tools.php?op=deleted', 'main' => '1'];
$tools[] = ['title' => _MA_PEDIGREE_DAT_TOOLS, 'link' => 'tools.php?op=database', 'main' => '1'];
if (isset($db)) {
    //create database submenu
    $tools[] = ['title' => _MA_PEDIGREE_ANCESTORS, 'link' => 'tools.php?op=dbanc', 'main' => '0'];
    $tools[] = ['title' => _MA_PEDIGREE_NOGENDER, 'link' => 'tools.php?op=fltypar', 'main' => '0'];
    $tools[] = ['title' => _MA_PEDIGREE_USERQUERIES, 'link' => 'tools.php?op=userq', 'main' => '0'];
}
$tools[] = ['title' => _MA_PEDIGREE_CREDITS, 'link' => 'tools.php?op=credits', 'main' => '1'];
$tools[] = ['title' => _MA_PEDIGREE_USER_LOGOUT, 'link' => '../../user.php?op=logout', 'main' => '1'];
//add data (form) to smarty template

$GLOBALS['xoopsTpl']->assign('tools', $tools);

//footer
require XOOPS_ROOT_PATH . '/footer.php';

/**
 * @return string HTML code to be appended to form for display
 */
function listuserfields()
{
    $sql     = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " WHERE isActive = '1' ORDER BY `order`";
    $result  = $GLOBALS['xoopsDB']->query($sql);
    $numrows = $GLOBALS['xoopsDB']->getRowsNum($result);
    $count   = 0;
    $form    = _MA_PEDIGREE_FIELD_EXPLAIN4 . _MA_PEDIGREE_FIELD_FORM1 . "\n" . "<tr><td colspan=\"7\"><hr></td></tr>\n";
    $mark    = "<td><span style='font-weight: bold;'>X</span></td>\n";
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $form .= "<tr>\n";
        //@todo move hard coded language strings to language file
        //display locked fields
        if (Constants::IS_LOCKED == $row['locked']) {
            $form .= "<td><a href=\"" . $helper->url("tools.php?op=togglelocked&field={$row['id']}") . "\"><img src=\"" . PEDIGREE_IMAGE_URL . "/locked.gif\" alt=\"click to open this field\"></a></td>\n";
        } else {
            $form .= "<td><a href=\"" . $helper->url("tools.php?op=togglelocked&field={$row['id']}") . "\"><img src=\"" . PEDIGREE_IMAGE_URL . "/open.gif\" alt=\"click to lock this field\"></a></td>\n";
        }

        if (0 == $count) { //first row
            $form .= '<td style="width: 15px;">&nbsp;</td><td style="width: 15px;"><a href="' . $helper->url("tools.php?op=fieldmove&field={$row['id']}&move=down") . '">' . "<img src=\"" . PEDIGREE_IMAGE_URL . "/down.gif\" alt=\"move field down\"></a></td>\n";
        } elseif ($count == $numrows - 1) { //last row
            $form .= '<td><a href="' . $helper->url("tools.php?op=fieldmove&field={$row['id']}&move=up") . '">' . "<img src=\"" . PEDIGREE_IMAGE_URL . "/up.gif\" alt=\"move field up\"></a></td>\n" . "<td>&nbsp;</td>\n";
        } else { //other rows
            $form .= "<td><a href=\""
                     . $helper->url("tools.php?op=fieldmove&field={$row['id']}&move=up")
                     . "\>"
                     . "<img src=\""
                     . PEDIGREE_IMAGE_URL
                     . "/up.gif\" alt=\"move field up\"></a></td>\n"
                     . "<td><a href=\""
                     . $helper->url("tools.php?op=fieldmove&field={$row['id']}&move=down")
                     . "\">\n"
                     . "<img src=\""
                     . PEDIGREE_IMAGE_URL
                     . "/down.gif\" alt=\"move field down\"></a></td>\n";
        }
        $form .= "<td><a href=\"" . $helper->url("tools.php?op=deluserfield&id={$row['id']}") . "\">{$icons['delete']}</a>&nbsp;<a href=\"" . $helper->url("tools.php?op=userfields&field={$row['id']}") . "\">{$row['fieldName']}</a></td>\n";
        //can the filed be shown in a list
        $form .= (1 == $row['ViewInList']) ? $mark : "<td>&nbsp;</td>\n";
        //is searchable ?
        $form .= (1 == $row['HasSearch']) ? $mark : "<td>&nbsp;</td>\n";
        //has lookuptable ?
        $form .= (1 == $row['LookupTable']) ? '<td><a href="' . $helper->url("tools.php?op=editlookup&id={$row['id']}") . '">' . _EDIT . "</a></td>\n" : "<td>&nbsp;</td>\n";
        $form .= "</tr>\n";
        ++$count;
    }
    $form   .= "</table>\n";
    $sql    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " WHERE isActive = '0' ORDER BY 'id'";
    $result = $GLOBALS['xoopsDB']->query($sql);
    if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        $form .= _MA_PEDIGREE_FIELD_EXPLAIN5;
        $form .= "<ul>\n";
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $form .= '<li><a href="' . $helper->url("tools.php?op=restoreuserfield&id={$row['id']}") . "\">{$row['fieldName']}</a></li>\n";
        }
        $form .= "</ul>\n";
    }

    return $form;
}

/**
 * @param $field
 * @param $move
 *
 * @return string
 * @todo this code needs to be refactored.
 *       - It assumes there are active fields which may not be true.
 *       - It does not check $x as a valid index.
 *       - It assumes there's less than 127 fields
 */
function fieldmove($field, $move)
{
    //find next id
    $sql    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " WHERE isActive = '1' ORDER BY `order`";
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $valorder[] = $row['order'];
        $valid[]    = $row['id'];
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

    if ('down' === $move) {
        $nextorder = $valorder[$x + 1];
    } else {
        $nextorder = $valorder[$x - 1];
    }
    //move value with ID=nextid to original location
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " SET `order` = '127' WHERE `order` = '{$nextorder}'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " SET `order` = '{$nextorder}' WHERE `order` = '{$currentorder}'";
    $GLOBALS['xoopsDB']->queryF($sql);
    //move current value into nextvalue's spot
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . " SET `order` = '{$currentorder}' WHERE `order` = '127'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $form = listuserfields();
    $GLOBALS['xoopsTpl']->assign('form', $form);

    return ($form);
}

/**
 * @param $field
 * @todo: move hard coded language string to language file
 *
 */
function editlookup($field)
{
    $form    = _MA_PEDIGREE_LOOKUPFIELD;
    $sql     = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $field) . ' ORDER BY `order`';
    $result  = $GLOBALS['xoopsDB']->query($sql);
    $numrows = $GLOBALS['xoopsDB']->getRowsNum($result);
    $count   = 0;
    $form    .= "<table>\n";
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $form .= "  <tr>\n";
        if (0 == $count) { //first row
            $form .= "    <td style=\"width: 15px;\">&nbsp;</td>\n"
                     . "    <td style=\"width: 15px;\">\n"
                     . "      <a href=\"tools.php?op=lookupmove&field={$field}&id={$row['id']}&move=down\"><img src=\""
                     . PEDIGREE_IMAGE_URL
                     . "/down.gif\"></a>\n"
                     . "    </td>\n"
                     . "    <td>\n"
                     . "      <a href=\"tools.php?op=dellookupvalue&field={$field}&id={$row['id']}\">{$icons['delete']}</a>\n"
                     . "      &nbsp;<a href='tools.php?op=editlookupvalue&field={$field}&id={$row['id']}'>{$row['value']}</a>\n"
                     . "    </td>\n";
        } elseif ($count == $numrows - 1) { //last row
            $form .= "    <td>\n"
                     . "      <a href=\"tools.php?op=lookupmove&field={$field}&id={$row['id']}&move=up\"><img src=\""
                     . PEDIGRE_IMAGE_URL
                     . "/up.gif\"></a>\n"
                     . "    </td>\n"
                     . "    <td>&nbsp;</td>\n"
                     . "    <td>\n"
                     . "      <a href=\"tools.php?op=dellookupvalue&field={$field}&id={$row['id']}\">{$icons['delete']}</a>\n"
                     . "      &nbsp;<a href='tools.php?op=editlookupvalue&field={$field}&id={$row['id']}'>{$row['value']}</a>\n"
                     . "    </td>\n";
        } else { //other rows
            $form .= "    <td>\n"
                     . "      <a href=\"tools.php?op=lookupmove&field={$field}&id={$row['id']}&move=up\"><img src=\""
                     . PEDIGREE_IMAGE_URL
                     . "/up.gif\"></a>\n"
                     . "    </td>\n"
                     . "    <td>\n"
                     . "      <a href=\"tools.php?op=lookupmove&field={$field}&id={$row['id']}&move=down\"><img src=\""
                     . PEDIGREE_IMAGE_URL
                     . "/down.gif\"></a>\n"
                     . "    </td>\n"
                     . "    <td>\n"
                     . "      <a href=\"tools.php?op=dellookupvalue&field={$field}&id={$row['id']}\">{$icons['delete']}</a>\n"
                     . "      &nbsp;<a href=\"tools.php?op=editlookupvalue&field={$field}&id={$row['id']}\">{$row['value']}</a>\n"
                     . "    </td>\n";
        }
        $form .= "</tr>\n";
        ++$count;
    }
    $form .= "</table>\n" . "<form method='post' action='tools.php?op=addlookupvalue&field={$field}'>\n" . "<input type='text' name='value' style='width: 140px;'>&nbsp;\n" . "<input type='submit' value='Add value'>\n" . _MA_PEDIGREE_DELVALUE . "\n";
    //    $form .= '<br><br><input type="submit" name="reset" value=Exit>&nbsp;';
    $GLOBALS['xoopsTpl']->assign('form', $form);
}

/**
 * @param         $field
 * @param int     $id
 * @param string  $move up|down
 */
function lookupmove($field, $id, $move)
{
    //find next id
    $sql    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $field) . ' ORDER BY `order`';
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $values[] = ['id' => $row['id'], 'content' => $row['value'], 'orderof' => $row['order']];
    }
    $arraycount    = 0;
    $arraylocation = 0;
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

    if ('down' === $move) {
        $nextid    = $values[$arraylocation + 1]['id'];
        $nextorder = $values[$arraylocation + 1]['orderof'];
    } else {
        $nextid    = $values[$arraylocation - 1]['id'];
        $nextorder = $values[$arraylocation - 1]['orderof'];
    }
    $sql = 'UPDATE `draaf_pedigree_lookup' . $field . "` SET `order` = '" . $nextorder . "' WHERE `id` = '" . $id . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $sql = 'UPDATE `draaf_pedigree_lookup' . $field . "` SET `order` = '" . $currentorder . "' WHERE `id` = '" . $nextid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    editlookup($field);
}

/**
 * @param $field
 * @param $id
 */
function dellookupvalue($field, $id)
{
    $animal      = new Pedigree\Animal();
    $fields      = $animal->getNumOfFields();
    $userField   = new Pedigree\Field($field, $animal->getConfig());
    $fieldType   = $userField->getSetting('fieldtype');
    $fieldObject = new $fieldType($userField, $animal);
    //    $default     = $fieldObject->defaultvalue;
    $default = $GLOBALS['xoopsDB']->escape($fieldObject->defaultvalue);
    if ($default == $id) {
        redirect_header('tools.php?op=editlookup&id=' . $field, 3, _MA_PEDIGREE_NO_DELETE . $fieldObject->fieldname);
    }
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $field) . ' WHERE id = ' . $id;
    $GLOBALS['xoopsDB']->queryF($sql);
    //change current values to default for deleted value
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' SET user' . $field . " = '" . $default . "' WHERE user" . $field . " = '" . $id . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' SET user' . $field . " = '" . $default . "' WHERE user" . $field . " = '" . $id . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' SET user' . $field . " = '" . $default . "' WHERE user" . $field . " = '" . $id . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    editlookup($field);
}

/**
 * @param $field
 */
function addlookupvalue($field)
{
    $SQL    = 'SELECT id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $field) . ' ORDER BY id DESC LIMIT 1';
    $result = $GLOBALS['xoopsDB']->query($SQL);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $count = $row['id'];
        ++$count;
        $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $field) . " VALUES ('" . $count . "', '" . $_POST['value'] . "', '" . $count . "')";
        $GLOBALS['xoopsDB']->queryF($sql);
    }
    redirect_header('tools.php?op=editlookup&id=' . $field, 2, 'The value has been added.');
}

/**
 * @param int $field
 */
function userfields($field = 0)
{
    global $field;
    require_once __DIR__ . '/include/checkoutwizard.php';

    $wizard = new CheckoutWizard();
    $action = $wizard->coalesce($_GET['action']);

    //$wizard->process($action, $_POST, 'POST' === $_SERVER['REQUEST_METHOD']);
    $wizard->process($action, $GLOBALS['POST'], 'POST' === $_SERVER['REQUEST_METHOD']);

    // only processes the form if it was posted. this way, we
    // can allow people to refresh the page without resubmitting
    // form data

    if ($wizard->isComplete()) {
        // check if field already exists (editing mode)
        $form = (0 == !$wizard->getValue('field')) ? _MA_PEDIGREE_FIELPROP : _MA_PEDIGREE_FIELDPROP_ELSE;
        $form .= "<form method='post' action='" . $_SERVER['SCRIPT_NAME'] . '?op=userfields&action=' . $wizard->resetAction() . "'>";
        $form .= "<input type='submit' value='" . _MA_PEDIGREE_FINISH_BUTTON . "'></form>";
    } else {
        $form = "<form method='post' action='" . $_SERVER['SCRIPT_NAME'] . '?op=userfields&action=' . $wizard->getStepName() . "'>";
        if (0 == !$field) {
            $form .= "<input type='hidden' name='field' value='{$field}'>";
        }
        if (0 == !$wizard->getValue('field')) { // field already exists (editing mode)
            $form .= '<h2>' . $wizard->getStepProperty('title') . ' - ' . $wizard->getValue('name') . ' - step ' . $wizard->getStepNumber() . '</h2>';
        } else {
            $form .= '<h2>' . $wizard->getStepProperty('title') . ' - step ' . $wizard->getStepNumber() . '</h2>';
        }

        if ('fieldname' === $wizard->getStepName()) {
            $form .= _MA_PEDIGREE_FIELD_FORM2;
            $form .= "<input type='text' name='name' value='" . htmlspecialchars($wizard->getValue('name'), ENT_QUOTES | ENT_HTML5) . "'>";
            $form .= "</td><td style='width: 25%;'>";
            if ($wizard->isError('name')) {
                $form .= $wizard->getError('name');
            }
            $form .= '</td></tr>';
            $form .= _MA_PEDIGREE_FIELD_FORM3;
            $form .= "<textarea name='explain' rows='5' cols='15'>" . htmlspecialchars($wizard->getValue('explain'), ENT_QUOTES | ENT_HTML5) . '</textarea>';
            $form .= "</td><td style='width: 25%;'>";
            if ($wizard->isError('explain')) {
                $form .= $wizard->getError('explain');
            }
            $form .= '</td></tr></table>';
            $form .= _MA_PEDIGREE_FIELDNAME;
        } elseif ('Fieldtype' === $wizard->getStepName()) {
            $form .= '<table><tr><td>';
            if ('' == $wizard->getValue('fieldtype')) {
                $wizard->setValue('fieldtype', 'textbox');
            }
            foreach ($wizard->fieldtype as $v) {
                $form .= "<input name='{fieldtype}' type='radio' value='{$v['value']}'";
                if ($wizard->getValue('fieldtype') == $v['value']) {
                    $form .= ' checked=checked';
                }
                $form .= ">{$v['description']}<br>";
            }
            $form .= '</td><td></table>';
        } elseif ('lookup' === $wizard->getStepName()) {
            $count = $wizard->getValue('fc');
            if ('' == $count) {
                $i = 1;
            } else {
                $i = $count + 1;
            }

            $form .= "<input type='hidden' name='fc' value='{$i}'>";
            $form .= '<table>';
            for ($x = 1; $x < $i; ++$x) {
                $form .= "<tr><td>Value : ({$x})</td><td>" . htmlspecialchars($wizard->getValue('lookup' . $x), ENT_QUOTES | ENT_HTML5) . '</td></tr>';
            }
            $form .= "<tr><td>Value : ({$i})</td><td>";
            $form .= "<input type='text' name='lookup{$i}' value='" . htmlspecialchars($wizard->getValue('lookup' . $i), ENT_QUOTES | ENT_HTML5) . "'>";
            $form .= "<input type='hidden' name='id{$i}' value='{$i}'>";
            if ($wizard->isError('lookup')) {
                $form .= $wizard->getError('lookup');
            }
            $form .= '</td></tr></table>';
            $form .= "<input type='submit' name='addvalue' value='Add Value'>";
        } else {
            if ('Settings' === $wizard->getStepName()) {
                $fieldtype = $wizard->getValue('fieldtype');
                //hassearch
                if (in_array($fieldtype, ['textbox', 'textarea', 'dateselect', 'urlfield', 'radiobutton', 'selectbox'])) {
                    $form .= "<input type='checkbox' name='hassearch' value='hassearch'";
                    if ('hassearch' === $wizard->getValue('hassearch')) {
                        $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                    }
                    $form .= '>' . _MA_PEDIGREE_FIELDSEARCH . '<br>';
                } else {
                    $form .= "<input type='checkbox' name='hassearch' disabled='true' value='hassearch'>" . _MA_PEDIGREE_FIELDSEARCH . '<br>';
                }
                //viewinpedigree
                $form .= "<input type='checkbox' name='viewinpedigree' value='viewinpedigree'";
                if ('viewinpedigree' === $wizard->getValue('viewinpedigree')) {
                    $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                }
                $form .= '>' . _MA_PEDIGREE_SHOWFIELD . '<br>';
                //viewinadvanced
                if ('radiobutton' === $fieldtype || 'selectbox' === $fieldtype) {
                    $form .= "<input type='checkbox' name='viewinadvanced' value='viewinadvanced'";
                    if ('viewinadvanced' === $wizard->getValue('viewinadvanced')) {
                        $form .= ' checked=checked';
                    }
                    $form .= '>' . _MA_PEDIGREE_SHOWFIELD_ADVANCE . '<br>';
                } else {
                    $form .= "<input type='checkbox' name='viewinadvanced' disabled='true' value='viewinadvanced'>" . _MA_PEDIGREE_SHOWFIELD_ADVANCE . '<br>';
                }
                //viewinpie
                if ('radiobutton' === $fieldtype || 'selectbox' === $fieldtype) {
                    $form .= "<input type='checkbox' name='viewinpie' value='viewinpie'";
                    if ('viewinpie' === $wizard->getValue('viewinpie')) {
                        $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                    }
                    $form .= '>' . _MA_PEDIGREE_SHOWFIELD_PIECHART . '<br>';
                } else {
                    $form .= "<input type='checkbox' name='viewinpie' disabled='true' value='viewinpie'>" . _MA_PEDIGREE_SHOWFIELD_PIECHART . '<br>';
                }
                //viewinlist
                if (in_array($fieldtype, ['textbox', 'dateselect', 'urlfield', 'radiobutton', 'selectbox'])) {
                    $form .= "<input type='checkbox' name='viewinlist' value='viewinlist'";
                    if ('viewinlist' === $wizard->getValue('viewinlist')) {
                        $form .= ' checked =_MA_PEDIGREE_CHECKED ';
                    }
                    $form .= '>' . _MA_PEDIGREE_SHOWFIELD_RESULT . '<br>';
                } else {
                    $form .= "<input type='checkbox' name='viewinlist' disabled='true' value='viewinlist'>" . _MA_PEDIGREE_SHOWFIELD_RESULT . '<br>';
                }
                //add a litter
                $form .= "<input type='checkbox' name='Litter' value='Litter'";
                if ('Litter' === $wizard->getValue('Litter')) {
                    $form .= ' checked =checked';
                } elseif ('Generallitter' === $wizard->getValue('Generallitter')) {
                    //disable if generallitter is active (you cant have both)
                    $form .= " disabled='true'";
                }
                $form .= '>' . _MA_PEDIGREE_SHOWFIELD_ADDLITTER . '<br>';

                //add a litter general
                $form .= "<input type='checkbox' name='Generallitter' value='Generallitter'";
                if ('Generallitter' === $wizard->getValue('Generallitter')) {
                    $form .= ' checked=checked';
                } elseif ('Litter' === $wizard->getValue('Litter')) {
                    //disable if add a litter is active (you cant have both)
                    $form .= " disabled='true'";
                }
                $form .= '>' . _MA_PEDIGREE_SHOWFIELD_ADDLITTGLOBAL . '<br>';
            } else {
                if ('search' === $wizard->getStepName()) {
                    $form              .= _MA_PEDIGREE_SEARCH_NAME;
                    $currentsearchname = $wizard->getValue('searchname');
                    if ('' == $currentsearchname) {
                        $currentsearchname = htmlspecialchars($wizard->getValue('name'), ENT_QUOTES | ENT_HTML5);
                    } else {
                        $currentsearchname = htmlspecialchars($wizard->getValue('searchname'), ENT_QUOTES | ENT_HTML5);
                    }
                    $form .= "<input type='text' name='searchname' value='{$currentsearchname}'>";
                    $form .= "</td><td style='width: 25%;'>";
                    if ($wizard->isError('searchname')) {
                        $form .= $wizard->getError('searchname');
                    }
                    $form .= '</td></tr>';
                    $form .= _MA_PEDIGREE_FIELDEXPLANSEARCH;
                    $form .= "<textarea name='searchexplain' rows='5' cols='15'>" . htmlspecialchars($wizard->getValue('searchexplain'), ENT_QUOTES | ENT_HTML5) . '</textarea>';
                    $form .= "</td><td style='width: 25%;'>";
                    if ($wizard->isError('searchexplain')) {
                        $form .= $wizard->getError('searchexplain');
                    }
                    $form .= '</td></tr></table>';
                    $form .= _MA_PEDIGREE_SEARCHNAME_EXPLAIN;
                } elseif ('defaultvalue' === $wizard->getStepName()) {
                    if ('selectbox' === $wizard->getValue('fieldtype')
                        || 'radiobutton' === $wizard->getValue('fieldtype')) {
                        $count      = $wizard->getValue('fc');
                        $form       .= "Default value : <select size='1' name='defaultvalue'>";
                        $radioarray = $wizard->getValue('radioarray');
                        foreach ($radioarray as $x => $xValue) {
                            $form .= "<option value='" . $radioarray[$x]['id'] . "'";
                            if ($wizard->getValue('defaultvalue') == $radioarray[$x]['id']) {
                                $form .= ' selected';
                            }
                            $form .= ' >' . $radioarray[$x]['value'] . '</option>';
                        }
                        $form .= '</select>';
                    } else {
                        $form .= '' . _MA_PEDIGREE_FIELDVALUE . " <input type='text' name='defaultvalue' value='" . htmlspecialchars($wizard->getValue('defaultvalue'), ENT_QUOTES | ENT_HTML5) . "'>";
                    }
                    $form .= _MA_PEDIGREE_DEFAUTVALUE_EXPLAIN;
                } elseif ('confirm' === $wizard->getStepName()) {
                    if (0 == !$wizard->getValue('field')) { // field allready exists (editing mode)
                        $form .= _MA_PEDIGREE_FIELDCONTROL1;
                    }
                } else {
                    $form .= _MA_PEDIGREE_FIELDCONTROL2;
                }
                //            }
                //        }
                $form .= '<b>' . _MA_PEDIGREE_FIELDCONTROL4 . $wizard->getValue('name') . '</b><br>';
                $form .= '<b>' . _MA_PEDIGREE_FIELDCONTROL5 . stripslashes($wizard->getValue('explain')) . '</b><br>';
                $form .= '<b>' . _MA_PEDIGREE_FIELDCONTROL6 . $wizard->getValue('fieldtype') . '</b><br>';
                if ('selectbox' === $wizard->getValue('fieldtype')
                    || 'radiobutton' === $wizard->getValue('fieldtype')) {
                    $count = $wizard->getValue('fc');
                    for ($x = 1; $x < $count + 1; ++$x) {
                        $radioarray[] = [
                            'id'    => $wizard->getValue('id' . $x),
                            'value' => $wizard->getValue('lookup' . $x),
                        ];
                    }
                    $val  = $wizard->getValue('defaultvalue');
                    $form .= '<b>' . _MA_PEDIGREE_FIELDCONTROL7 . $wizard->getValue('lookup' . $val) . '</b><br>';
                } else {
                    $form .= '<b>' . _MA_PEDIGREE_FIELDCONTROL7 . $wizard->getValue('defaultvalue') . '</b><br>';
                }
                if ('hasearch' === $wizard->getValue('hassearch')) {
                    $form .= _MA_PEDIGREE_FIELDSEARCH . ' : <b>  ' . _YES . '</b><br>';
                    $form .= '<b>' . _MA_PEDIGREE_FIELDSEARCH . $wizard->getValue('searchname') . '</b><br>';
                    $form .= '<b>' . _MA_PEDIGREE_FIELDCONTROL5 . $wizard->getValue('searhexplain') . '</b><br>';
                }
                if ('viewinpedigree' === $wizard->getValue('viewinpedigree')) {
                    $form .= _MA_PEDIGREE_SYNTH1;
                }
                if ('viewinadvanced' === $wizard->getValue('viewinadvanced')) {
                    $form .= _MA_PEDIGREE_SYNTH2;
                }
                if ('viewinpie' === $wizard->getValue('viewinpie')) {
                    $form .= _MA_PEDIGREE_SYNTH3;
                }
                if ('viewinlist' === $wizard->getValue('viewinlist')) {
                    $form .= _MA_PEDIGREE_SYNTH4;
                }
                if ('Litter' === $wizard->getValue('litter')) {
                    $form .= _MA_PEDIGREE_SYNTH5;
                }
                if ('Generallitter' === $wizard->getValue('generallitter')) {
                    $form .= _MA_PEDIGREE_SYNTH6;
                }
                $form .= _MA_PEDIGREE_FIELDCONTROL3 . '<br><br>';
            }
        }
    }

    if (!$wizard->isFirstStep()) {
        $form .= "<p><input type='submit' name='previous' value='&lt;&lt; " . _MA_PEDIGREE_PREVIOUS_BUTTON . "'>&nbsp;";
    }
    $form .= "<input type='submit' name='reset' value=Exit>&nbsp;";
    $last = $wizard->isLastStep() ? _MA_PEDIGREE_FINISH_BUTTON : _MA_PEDIGREE_NEXT_BUTTON;
    $form .= "<input type='submit' value='{$last}>>'>";
    $form .= '</p></form>';

    $GLOBALS['xoopsTpl']->assign('form', $form);
}

/**
 * @todo: move hard coded language strings to language file
 */
function deleted()
{
    $helper = Helper::getInstance();
    $form   = "Below the line are the animals which have been deleted from your database.<br><br>By clicking on the name you can reinsert them into the database.<br>By clicking on the 'X' in front of the name you can permanently delete the animal.<hr>";
    $sql    = 'SELECT id, pname  FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash');
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $form .= "<a href=\"tools.php?op=delperm&id={$row['id']}\">{$icons['delete']}</a>&nbsp;<a href=\"tools.php?op=restore&id={$row['id']}\">{$row['pname']}</a><br>";
    }
    if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        //@todo move hard coded language string to language file
        $form .= '<hr><a href="tools.php?op=delall">Click here</a> to remove all these ' . $helper->getConfig('animalTypes') . ' permenantly ';
    }
    $GLOBALS['xoopsTpl']->assign('form', $form);
}

function settings()
{
    $helper = Helper::getInstance();

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $form = new \XoopsThemeForm(_MA_PEDIGREE_BLOCK_SETTING, 'settings', $helper->url('tools.php?op=settingssave'), 'POST', 1);
    $form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
    $select  = new \XoopsFormSelect(_MA_PEDIGREE_RESULT, 'perpage', $value = $helper->getConfig('perpage', Constants::DEFAULT_PER_PAGE), $size = 1, $multiple = false);
    $options = [
        '10'    => 10,
        '50'    => 50,
        '100'   => 100,
        '250'   => 250,
        '500'   => 500,
        '1000'  => 1000,
        '2000'  => 2000,
        '5000'  => 5000,
        '10000' => 10000,
    ];
    foreach ($options as $key => $values) {
        $select->addOption($key, $name = $values);
    }
    unset($options);
    $form->addElement($select);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_EXPLAIN_NUMB));
    $radiowel = new \XoopsFormRadio(_MA_PEDIGREE_SHOW_WELC, 'showwelcome', $value = $helper->getConfig('showwelcome'));
    $radiowel->addOption(1, $name = _MA_PEDIGREE_YES);
    $radiowel->addOption(0, $name = _MA_PEDIGREE_NO);
    $form->addElement($radiowel);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_WELC_SCREEN));
    $radio = new \XoopsFormRadio(_MA_PEDIGREE_BREED_FIELD, 'ownerbreeder', $value = $helper->getConfig('ownerbreeder'));
    $radio->addOption(1, $name = _MA_PEDIGREE_YES);
    $radio->addOption(0, $name = _MA_PEDIGREE_NO);
    $form->addElement($radio);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_OWN_EXPLAIN));
    $radiobr = new \XoopsFormRadio(_MA_PEDIGREE_SHOW_BROT, 'brothers', $value = $helper->getConfig('brothers'));
    $radiobr->addOption(1, $name = _MA_PEDIGREE_YES);
    $radiobr->addOption(0, $name = _MA_PEDIGREE_NO);
    $form->addElement($radiobr);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_BROT_EXPLAIN));
    $radiolit = new \XoopsFormRadio(_MA_PEDIGREE_USE_LITTER, 'uselitter', $value = $helper->getConfig('uselitter'));
    $radiolit->addOption(1, $name = _MA_PEDIGREE_YES);
    $radiolit->addOption(0, $name = _MA_PEDIGREE_NO);
    $form->addElement($radiolit);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_USE_LITTER_EXPLAIN));
    $radioch = new \XoopsFormRadio(PED_SHOW_KITT_A . $helper->getConfig('children') . _MA_PEDIGREE_SHOW_KITT_B, 'pups', $value = $helper->getConfig('pups'));
    $radioch->addOption(1, $name = _MA_PEDIGREE_YES);
    $radioch->addOption(0, $name = _MA_PEDIGREE_NO);
    $form->addElement($radioch);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_KITT_EXPLAIN));
    $radiosoi = new \XoopsFormRadio(_MA_PEDIGREE_SHOW_PICT, 'lastimage', $value = $helper->getConfig('lastimage'));
    $radiosoi->addOption(1, $name = _MA_PEDIGREE_YES);
    $radiosoi->addOption(0, $name = _MA_PEDIGREE_NO);
    $form->addElement($radiosoi);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_PICT_EXPLAIN));
    $form->addElement(new \XoopsFormButton('', 'button_id', 'Submit', 'submit'));
    $GLOBALS['xoopsTpl']->assign('form', $form->render());
}
