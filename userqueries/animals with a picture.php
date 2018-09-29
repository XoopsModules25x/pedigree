<?php

$form     = 'This is an example of a userquery.<br><br>Shown below are the animals in your database that have a picture.<hr>';
$sql      = 'SELECT id, pname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE foto != ''";
$result   = $GLOBALS['xoopsDB']->query($sql);
$countpic = 0;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $form .= '<a href="pedigree.php?pedid=' . $row['id'] . '">' . $row['pname'] . '</a><br>';
    ++$countpic;
}
$form .= '<hr>There are a total of ' . $countpic . ' animals with a picture';
