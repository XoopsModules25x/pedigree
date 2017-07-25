<?php

$form        = 'The following animals have been found in your database with a slah. Any escape characters have been removed.<hr>';
$queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE naam LIKE '%\'%'";
$result      = $GLOBALS['xoopsDB']->query($queryString);
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $form .= '<a href="pedigree.php?pedid=' . $row['id'] . '">' . $row['naam'] . '</a><br>';
    $sql  = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' SET naam = "' . stripslashes($row['naam']) . "\" WHERE id = '" . $row['id'] . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
}
