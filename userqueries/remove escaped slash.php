<?php

$form        = "The following animals have been found in your database with a slah. Any escape characters have been removed.<hr>";
$queryString = "select * from " . $xoopsDB->prefix("pedigree_tree") . " where NAAM LIKE '%\'%'";
$result      = $xoopsDB->query($queryString);
while ($row = $xoopsDB->fetchArray($result)) {
    $form .= "<a href=\"pedigree.php?pedid=" . $row['ID'] . "\">" . $row['NAAM'] . "</a><br />";
    $sql = "UPDATE " . $xoopsDB->prefix("pedigree_tree") . " SET NAAM = \"" . stripslashes($row['NAAM']) . "\" WHERE ID = '" . $row['ID'] . "'";
    mysql_query($sql);
}
