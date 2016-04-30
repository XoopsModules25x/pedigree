<?php

$form        = 'The following animals have been found in your database with a slah. Any escape characters have been removed.<hr>';
$queryString = 'select * from ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " where NAAM LIKE '%\'%'";
$result      = $GLOBALS['xoopsDB']->query($queryString);
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $form .= "<a href=\"pedigree.php?pedid=" . $row['Id'] . "\">" . $row['NAAM'] . '</a><br />';
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET NAAM = \"" . stripslashes($row['NAAM']) . "\" WHERE Id = '" . $row['Id'] . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
}
