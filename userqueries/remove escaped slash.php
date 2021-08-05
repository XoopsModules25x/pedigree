<?php

$form   = 'The following animals have been found in your database with a slah. Any escape characters have been removed.<hr>';
$sql    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE pname LIKE '%\'%'";
$result = $GLOBALS['xoopsDB']->query($sql);
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $form .= '<a href="pedigree.php?pedid=' . $row['id'] . '">' . $row['pname'] . '</a><br>';
    $sql  = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' SET pname = "' . stripslashes($row['pname']) . "\" WHERE id = '" . $row['id'] . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
}
