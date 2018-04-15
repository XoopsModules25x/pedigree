<?php

require_once  dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
xoops_loadLanguage('main', basename(dirname(dirname(__DIR__))));

require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/menu.php';

xoops_cp_header();

if (!isset($_GET['c'])) {
    $SQL    = 'SELECT conf_value FROM ' . $GLOBALS['xoopsDB']->prefix('config') . " WHERE conf_name = 'pedigreeColours'";
    $result = $GLOBALS['xoopsDB']->query($SQL);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $c = $row['conf_value'];
    }
} else {
    $c = $_GET['c'];
}
$colors = explode(';', $c);
//define text-string makeup
$mainBackColour     = $colors[0];
$selectedBackColour = $colors[1];
$selectedTextColour = $colors[2];
$selectedTextFont   = $colors[3];
$selectedTextSize   = $colors[4];
$selectedTextStyle  = $colors[5];
$maleBackColour     = $colors[6];
$maleTextColour     = $colors[7];
$maleTextFont       = $colors[8];
$maleTextSize       = $colors[9];
$maleTextStyle      = $colors[10];
$femaleBackColour   = $colors[11];
$femaleTextColour   = $colors[12];
$femaleTextFont     = $colors[13];
$femaleTextSize     = $colors[14];
$femaleTextStyle    = $colors[15];
$borderStyle        = $colors[16];
$borderWidth        = $colors[17];
$borderColour       = $colors[18];

echo '<script language="JavaScript" src="picker.js"></script>
<script language="JavaScript" src="colors.js"></script>';
echo '
<table width="90%" id="background" cellspacing="0" style=" background-color: #'
     . $mainBackColour
     . ';">
<tr>
<td align="center">
<br>

<table id="spacer" width="100%">
<tr>
<td>
&nbsp;
</td>
<td>

<table width="100%" id="maintable" cellspacing="0" style=" background-color: #'
     . $mainBackColour
     . ';">
    <!-- header (dog name) -->
    <tr>
        <th colspan="4" style="text-align:center; background-color: #'
     . $selectedBackColour
     . ';" id="selected1">
            Pedigree Database
        </th>
    </tr>
    <tr>
        <!-- selected dog -->
        <td width="25%" rowspan="8" style=" background-color: #'
     . $selectedBackColour
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px; color: #'
     . $selectedTextColour
     . '; font-family: '
     . $selectedTextFont
     . '; font-size: '
     . $selectedTextSize
     . '; font-style: '
     . $selectedTextStyle
     . ';" id="selected2">
            Selected animal
        </td>
        <!-- father -->
        <td width="25%" rowspan="4" style=" background-color: #'
     . $maleBackColour
     . '; color: #'
     . $maleTextColour
     . '; font-family: '
     . $maleTextFont
     . '; font-size: '
     . $maleTextSize
     . '; font-style: '
     . $maleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="fathercell1">
            Father
        </td>
        <!-- father father -->
        <td width="25%" rowspan="2" style=" background-color: #'
     . $maleBackColour
     . '; color: #'
     . $maleTextColour
     . '; font-family: '
     . $maleTextFont
     . '; font-size: '
     . $maleTextSize
     . '; font-style: '
     . $maleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="fathercell2">
            Father Father
        </td>
        <!-- father father father -->
        <td width="25%" style=" background-color: #'
     . $maleBackColour
     . '; color: #'
     . $maleTextColour
     . '; font-family: '
     . $maleTextFont
     . '; font-size: '
     . $maleTextSize
     . '; font-style: '
     . $maleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="fathercell3">
            Father Father Father
        </td>
    </tr>
    <tr>
        <!-- father father mother -->
        <td width="25%" style=" background-color: #'
     . $femaleBackColour
     . '; color: #'
     . $femaleTextColour
     . '; font-family: '
     . $femaleTextFont
     . '; font-size: '
     . $femaleTextSize
     . '; font-style: '
     . $femaleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="mothercell1">
            Father Father Mother
        </td>
    </tr>
    <tr>
        <!-- father mother -->
        <td width="25%" rowspan="2" style=" background-color: #'
     . $femaleBackColour
     . '; color: #'
     . $femaleTextColour
     . ';  font-family: '
     . $femaleTextFont
     . '; font-size: '
     . $femaleTextSize
     . '; font-style: '
     . $femaleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="mothercell2">
            Father Mother
        </td>
        <!-- father mother father -->
        <td width="25%" style=" background-color: #'
     . $maleBackColour
     . '; color: #'
     . $maleTextColour
     . '; font-family: '
     . $maleTextFont
     . '; font-size: '
     . $maleTextSize
     . '; font-style: '
     . $maleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="fathercell4">
            Father Mother Father
        </td>
    </tr>
    <tr>
        <!-- father mother mother -->
        <td width="25%" style=" background-color: #'
     . $femaleBackColour
     . '; color: #'
     . $femaleTextColour
     . ';  font-family: '
     . $femaleTextFont
     . '; font-size: '
     . $femaleTextSize
     . '; font-style: '
     . $femaleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="mothercell3">
            Father Mother Mother
        </td>
    </tr>
    <tr>
        <!-- mother -->
        <td width="25%" rowspan="4" style=" background-color: #'
     . $femaleBackColour
     . '; color: #'
     . $femaleTextColour
     . '; font-family: '
     . $femaleTextFont
     . '; font-size: '
     . $femaleTextSize
     . '; font-style: '
     . $femaleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="mothercell4">
            Mother
        </td>
        <!-- mother father -->
        <td width="25%" rowspan="2" style=" background-color: #'
     . $maleBackColour
     . '; color: #'
     . $maleTextColour
     . '; font-family: '
     . $maleTextFont
     . '; font-size: '
     . $maleTextSize
     . '; font-style: '
     . $maleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="fathercell5">
            Mother Father
        </td>
        <!-- mother father father -->
        <td width="25%" style=" background-color: #'
     . $maleBackColour
     . '; color: #'
     . $maleTextColour
     . '; font-family: '
     . $maleTextFont
     . '; font-size: '
     . $maleTextSize
     . '; font-style: '
     . $maleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="fathercell6">
            Mother Father Father
        </td>
    </tr>
    <tr>
        <!-- mother father mother -->
        <td width="25%" style=" background-color: #'
     . $femaleBackColour
     . '; color: #'
     . $femaleTextColour
     . ';  font-family: '
     . $femaleTextFont
     . '; font-size: '
     . $femaleTextSize
     . '; font-style: '
     . $femaleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="mothercell5">
            Mother Father Mother
        </td>
    </tr>
    <tr>
        <!-- mother mother -->
        <td width="25%" rowspan="2" style=" background-color: #'
     . $femaleBackColour
     . '; color: #'
     . $femaleTextColour
     . '; font-family: '
     . $femaleTextFont
     . '; font-size: '
     . $femaleTextSize
     . '; font-style: '
     . $femaleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="mothercell6">
            Mother Mother
        </td>
        <!-- mother mother father -->
        <td width="25%" style=" background-color: #'
     . $maleBackColour
     . '; color: #'
     . $maleTextColour
     . '; font-family: '
     . $maleTextFont
     . '; font-size: '
     . $maleTextSize
     . '; font-style: '
     . $maleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="fathercell7">
            Mother Mother Father
        </td>
    </tr>
    <tr>
        <!-- mother mother mother -->
        <td width="25%" style=" background-color: #'
     . $femaleBackColour
     . '; color: #'
     . $femaleTextColour
     . '; font-family: '
     . $femaleTextFont
     . '; font-size: '
     . $femaleTextSize
     . '; font-style: '
     . $femaleTextStyle
     . '; border-style: '
     . $borderStyle
     . '; border-color: #'
     . $borderColour
     . '; border-width: '
     . $borderWidth
     . 'px;" id="mothercell7">
            Mother Mother Mother
        </td>
    </tr>
</table>

</td>
<td>
&nbsp;
</td>
</tr>
</table>

<br>
</td>
</tr>
</table>


<table>
    <tr>
        <td width="25%" valign="top">
            <div align="center">Selected properties</div>
            <FORM NAME="myForm" action=\'savecolors.php\' method=\'POST\'>
            <hr width="90%">
            <table>
                <tr>
                    <td width="50%">Background colour</td>
                    <td>
                        <INPUT TYPE="text" id="sbgcolor" name="sbgcolor" value="#'
     . $selectedBackColour
     . '" size="11" maxlength="7">
                        <!-- Color Picker initialization and ancor icon to call a picker -->
                        <a href="javascript:TCP.popup(document.forms[\'myForm\'].elements[\'sbgcolor\'])">
                        <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="img/sel.gif"></a>
                    </td>
                </tr>
                <tr>
                    <td>Text colour</td>
                    <td>
                        <INPUT TYPE="text" id="stxtcolor" name="stxtcolor" value="#'
     . $selectedTextColour
     . '" size="11" maxlength="7">
                        <!-- Color Picker initialization and ancor icon to call a picker -->
                        <a href="javascript:TCP.popup(document.forms[\'myForm\'].elements[\'stxtcolor\'])">
                        <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="img/sel.gif"></a>
                    </td>
                </tr>
                <tr>
                    <td>Font</td>
                    <td>
                        <select id=\'sfont\' name="sfont" onchange="changeCol()">
                        <option value=\''
     . $selectedTextFont
     . '\' selected>'
     . $selectedTextFont
     . '</option>
                        <option value=\'Arial\'>Arial</option>
                        <option value=\'Courier\'>Courier</option>
                        <option value=\'Georgia\'>Georgia</option>
                        <option value=\'Helvetica\'>Helvetica</option>
                        <option value=\'Impact\'>Impact</option>
                        <option value=\'Verdana\'>Verdana</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Size</td>
                    <td>
                        <select id=\'sfontsize\' name="sfontsize" onchange="changeCol()">
                        <option value=\''
     . $selectedTextSize
     . '\' selected>'
     . $selectedTextSize
     . '</option>
                        <option value=\'xx-small\'>xx-small</option>
                        <option value=\'x-small\'>x-small</option>
                        <option value=\'small\'>small</option>
                        <option value=\'medium\'>medium</option>
                        <option value=\'large\'>large</option>
                        <option value=\'x-large\'>x-large</option>
                        <option value=\'xx-large\'>xx-large</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Style</td>
                    <td>
                        <select id=\'sfontstyle\' name="sfontstyle" onchange="changeCol()">
                        <option value=\''
     . $selectedTextStyle
     . '\' selected>'
     . $selectedTextStyle
     . '</option>
                        <option value=\'italic\'>Italic</option>
                        <option value=\'normal\'>Normal</option>
                        </select>
                    </td>
                </tr>
            </table>
        </td>
        <td width="25%">
            <div align="center" valign="top">Male properties</div>
            <hr width="90%">
            <table width="100%">
                <tr>
                    <td width="50%">Background colour</td>
                    <td>
                        <INPUT TYPE="text" id="mbgcolor" name="mbgcolor" value="#'
     . $maleBackColour
     . '" size="11" maxlength="7">
                        <!-- Color Picker initialization and ancor icon to call a picker -->
                        <a href="javascript:TCP.popup(document.forms[\'myForm\'].elements[\'mbgcolor\'])">
                        <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="img/sel.gif"></a>
                    </td>
                </tr>
                <tr>
                    <td>Text colour</td>
                    <td>
                        <INPUT TYPE="text" id="mtxtcolor" name="mtxtcolor" value="#'
     . $maleTextColour
     . '" size="11" maxlength="7">
                        <!-- Color Picker initialization and ancor icon to call a picker -->
                        <a href="javascript:TCP.popup(document.forms[\'myForm\'].elements[\'mtxtcolor\'])">
                        <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="img/sel.gif"></a>
                    </td>
                </tr>
                <tr>
                    <td>Font</td>
                    <td>
                        <select id=\'mfont\' name="mfont" onchange="changeCol()">
                        <option value=\''
     . $maleTextFont
     . '\' selected>'
     . $maleTextFont
     . '</option>
                        <option value=\'Arial\'>Arial</option>
                        <option value=\'Courier\'>Courier</option>
                        <option value=\'Georgia\'>Georgia</option>
                        <option value=\'Helvetica\'>Helvetica</option>
                        <option value=\'Impact\'>Impact</option>
                        <option value=\'Verdana\'>Verdana</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Size</td>
                    <td>
                        <select id=\'mfontsize\' name="mfontsize" onchange="changeCol()">
                        <option value=\''
     . $maleTextSize
     . '\' selected>'
     . $maleTextSize
     . '</option>
                        <option value=\'xx-small\'>xx-small</option>
                        <option value=\'x-small\'>x-small</option>
                        <option value=\'small\'>small</option>
                        <option value=\'medium\'>medium</option>
                        <option value=\'large\'>large</option>
                        <option value=\'x-large\'>x-large</option>
                        <option value=\'xx-large\'>xx-large</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Style</td>
                    <td>
                        <select id=\'mfontstyle\' name="mfontstyle" onchange="changeCol()">
                        <option value=\''
     . $maleTextStyle
     . '\' selected>'
     . $maleTextStyle
     . '</option>
                        <option value=\'italic\'>Italic</option>
                        <option value=\'normal\'>Normal</option>
                        </select>
                    </td>
                </tr>
            </table>
        </td>
        <td width="25%" valign="top">
            <div align="center">Female properties</div>
            <hr width="90%">
            <table width="100%">
                <tr>
                    <td width="50%">Background colour</td>
                    <td>
                        <INPUT TYPE="text" id="fbgcolor" name="fbgcolor" value="#'
     . $femaleBackColour
     . '" size="11" maxlength="7">
                        <!-- Color Picker initialization and ancor icon to call a picker -->
                        <a href="javascript:TCP.popup(document.forms[\'myForm\'].elements[\'fbgcolor\'])">
                        <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="img/sel.gif"></a>
                    </td>
                </tr>
                <tr>
                    <td>Text colour</td>
                    <td>
                        <INPUT TYPE="text" id="ftxtcolor" name="ftxtcolor" value="#'
     . $femaleTextColour
     . '" size="11" maxlength="7">
                        <!-- Color Picker initialization and ancor icon to call a picker -->
                        <a href="javascript:TCP.popup(document.forms[\'myForm\'].elements[\'ftxtcolor\'])">
                        <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="img/sel.gif"></a>
                    </td>
                </tr>
                <tr>
                    <td>Font</td>
                    <td>
                        <select id=\'ffont\' name="ffont" onchange="changeCol()">
                        <option value=\''
     . $femaleTextFont
     . '\' selected>'
     . $femaleTextFont
     . '</option>
                        <option value=\'Arial\'>Arial</option>
                        <option value=\'Courier\'>Courier</option>
                        <option value=\'Georgia\'>Georgia</option>
                        <option value=\'Helvetica\'>Helvetica</option>
                        <option value=\'Impact\'>Impact</option>
                        <option value=\'Verdana\'>Verdana</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Size</td>
                    <td>
                        <select id=\'ffontsize\' name="ffontsize" onchange="changeCol()">
                        <option value=\''
     . $femaleTextSize
     . '\' selected>'
     . $femaleTextSize
     . '</option>
                        <option value=\'xx-small\'>xx-small</option>
                        <option value=\'x-small\'>x-small</option>
                        <option value=\'small\'>small</option>
                        <option value=\'medium\'>medium</option>
                        <option value=\'large\'>large</option>
                        <option value=\'x-large\'>x-large</option>
                        <option value=\'xx-large\'>xx-large</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Style</td>
                    <td>
                        <select id=\'ffontstyle\' name="ffontstyle" onchange="changeCol()">
                        <option value=\''
     . $femaleTextStyle
     . '\' selected>'
     . $femaleTextStyle
     . '</option>
                        <option value=\'italic\'>Italic</option>
                        <option value=\'normal\'>Normal</option>
                        </select>
                    </td>
                </tr>
            </table>

        </td>
        <td width="25%" valign="top">
            <div align="center">Border properties</div>
            <hr width="90%">
            <table width="100%">
                <tr>
                    <td width="50%">Style</td>
                    <td>
                        <select id=\'bstyle\' name="bstyle" onchange="changeCol()">
                        <option value=\''
     . $borderStyle
     . '\' selected>'
     . $borderStyle
     . '</option>
                        <option value=\'dotted\'>Dotted</option>
                        <option value=\'solid\'>Solid</option>
                        <option value=\'dashed\'>Dashed</option>
                        <option value=\'none\'>None</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Width</td>
                    <td>
                        <INPUT TYPE="text" id="bwidth" name="bwidth" value="'
     . $borderWidth
     . 'px" size="11" maxlength="7">
                    </td>
                </tr>
                <tr>
                    <td>Colour</td>
                    <td>
                        <INPUT TYPE="text" id="bcolor" name="bcolor" value="#'
     . $borderColour
     . '" size="11" maxlength="7">
                        <!-- Color Picker initialization and ancor icon to call a picker -->
                        <a href="javascript:TCP.popup(document.forms[\'myForm\'].elements[\'bcolor\'])">
                        <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="img/sel.gif"></a>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
<hr>
main background color :
<INPUT TYPE="text" id="mainbgcolor" name="mainbgcolor" value="#'
     . $mainBackColour
     . '">
<!-- Color Picker initialization and ancor icon to call a picker -->
<a href="javascript:TCP.popup(document.forms[\'myForm\'].elements[\'mainbgcolor\'])">
<img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="img/sel.gif"></a>
<hr>
<INPUT TYPE="button" NAME="myButton" onClick="changeCol()" value="preview"> Press \'preview\' to view the pedigree with the changes you have made<br><br>
<INPUT TYPE="submit" value="submit"> Press submit to store the settings. You can change these at any time.
</FORM>
<hr>
Or you may select any of the following predefined styles : <br>
<a href="colors.php?c=d6e7f5;C3E085;000000;arial;small;normal;d7EAAE;000000;arial;small;normal;ebf5D6;000000;arial;small;normal;solid;1;FFFFFF">Spring</a> -- <a href="colors.php?c=FFFFFF;FBFF66;000000;arial;small;normal;FEFFCC;000000;arial;small;normal;FCFF99;000000;arial;small;normal;solid;1;FFD200">Summer</a> -- <a href="colors.php?c=FFFFFF;8F8F8F;000000;arial;small;normal;CCCCCC;000000;arial;small;normal;E6E6E6;000000;arial;small;normal;solid;1;FFFFFF">Winter</a>
';

xoops_cp_footer();
