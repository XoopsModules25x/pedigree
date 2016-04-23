<?php

$form     = "This is an example of a userquery.<br /><br />Shown below are the animals in your database that have a picture.<hr>";
$sql      = "SELECT ID, NAAM FROM " . $xoopsDB->prefix("pedigree_tree") . " WHERE foto != ''";
$result   = $xoopsDB->query($sql);
$countpic = 0;
while ($row = $xoopsDB->fetchArray($result)) {
    $form .= "<a href=\"pedigree.php?pedid=" . $row['ID'] . "\">" . $row['NAAM'] . "</a><br />";
    ++$countpic;
}
$form .= "<hr />There are a total of " . $countpic . " animals with a picture";
