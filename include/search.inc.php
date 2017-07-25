<?php

// the parameters  "$queryarray, $andor, $limit, $offset, $userid" to this function are, I believe dictated by the core search function

/**
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 *
 * @return array
 */
function pedigree_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    // Start creating a sql string that will be used to retrieve the fields in the table
    // that your module is making available to search

    $sql = 'SELECT id,naam,nhsb,user FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ';

    // because count() returns 1 even if a supplied variable
    // is not an array, we must check if $querryarray is really an array

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " WHERE ((naam LIKE '%$queryarray[0]%' OR nhsb LIKE '%$queryarray[0]%')";
        for ($i = 1; $i < $count; ++$i) {
            $sql .= " $andor ";
            $sql .= "(naam LIKE '%$queryarray[$i]%' OR nhsb LIKE '%$queryarray[$i]%')";
        }
        $sql .= ') ';
    } // end if
    if ($userid != 0) {
        $sql .= ' WHERE user=' . $userid . ' ';
    }
    $sql .= 'ORDER BY naam ASC';

    $result = $GLOBALS['xoopsDB']->query($sql, $limit, $offset);
    $ret    = array();
    $i      = 0;
    // with the search results, build the links to the hits the search query made
    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $ret[$i]['image'] = 'assets/images/pedigree.gif';
        $ret[$i]['link']  = 'pedigree.php?pedid=' . $myrow['id'];
        $ret[$i]['title'] = stripslashes($myrow['naam']);
        // do we need this ? (no time is set in the db for dog entry
        // time should be in a unix timestamp format.
        $ret[$i]['time'] = '';
        $ret[$i]['uid']  = $myrow['user'];
        ++$i;
    }

    return $ret;
}
