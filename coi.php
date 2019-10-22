<?php

use Xmf\Request;
use XoopsModules\Pedigree;

ini_set('memory_limit', '32M');

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_coi.tpl';
include XOOPS_ROOT_PATH . '/header.php';

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

global $xoopsTpl, $xoopsDB, $moduleConfig;

//start kinship.php code -- help !!
/* ************************************************************************************* */
/*
     This program calculates the coefficient of inbreeding (IC, or COI, or F)
     for the offspring of a couple of animals, given by their IDs (s=sire_ID&d=dam_ID),
     or for a given animal given by its ID (a=animal_ID).

     By default, all known ascendants are used.
     However, maximum count of distinct ascendants is limited to $nb_maxi (default=600)
              [higher values for $nb_maxi could lead to calculations ending in timeout],
              or depth of tree can be limited to $nb_gen generations (default = 8).
*/
/* ************************************************************************************* */

if (!isset($verbose)) {
    $verbose = 0;
} // don't display different steps of ICs calculation
if (!isset($detail)) {
    $detail = 1;
} // don't display detail results [faster]
if (!isset($nb_maxi)) {
    $nb_maxi = 600;
} // maximum count of distinct ascendants
if (!isset($nb_gen)) {
    $nb_gen = 8;
} // maximum count of generations of ascendants
if (!isset($pedigree)) {
    $pedigree = 0;
} // dont't display sketch pedigree [faster]
if (!isset($max_dist)) { // maximum length of implex loops
    if ($nb_gen > 9) {
        $max_dist = 14;
    } else {
        if (9 == $nb_gen) {
            $max_dist = 17;
        } else {
            if (8 == $nb_gen) {
                $max_dist = 18;
            } else {
                $max_dist = 99;
            }
        }
    }
}

$empty = []; // an empty array
$sql1 = 'SELECT id, father, mother, roft FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE id ';

// input data arrays:
$IDs = $empty;
$fathers = $empty;
$mothers = $empty;

// working arrays:
$inds = $empty;
$marked = $empty;
$ICknown = $empty;
$deltaf = $empty;
$pater = $empty;
$mater = $empty;
$chrono = $empty;

// Coefficients of Inbreeding array (result):
$COIs = $empty;

/* ******************************  FUNCTIONS  ********************************* */

/**
 * @return int
 */
function chrono_sort()
{
    global $IDs, $inds, $fathers, $mothers, $chrono, $nl, $detail;
    $impr = 0;
    $modif = 1;
    $nloop = 0;
    $nba = count($IDs);
    // print_r ($IDs) ;
    // echo "<b>231 : $IDs[231] $fathers[231] $mothers[231] $chrono[231] $inds[231] </b><br>\n" ;
    foreach ($IDs as $i => $v) {
        $chrono[$i] = 1;
    } // initialize all chronological ranks to 1
    $chrono[0] = 0; // except animal #0 (at 0 rank).
    while ($modif && $nloop < 40) {
        $modif = 0;
        ++$nloop;
        for ($i = 1; $i < $nba; ++$i) {
            $s = $fathers[$i];
            if ($s) {
                $s = $inds[$s];
            }
            $d = $mothers[$i];
            if ($d) {
                $d = $inds[$d];
            }
            if ($s && $chrono[$s] <= $chrono[$i]) {
                $chrono[$s] = $chrono[$i] + 1;
                $modif = 1;
            }
            if ($d && $chrono[$d] <= $chrono[$i]) {
                $chrono[$d] = $chrono[$i] + 1;
                $modif = 1;
            }
        }
    }
    if (40 == $nloop) {
        die('Endless loop detected. Stopped.');
    }
    array_multisort($chrono, $IDs, $fathers, $mothers);
    $depth = $chrono[$nba - 1];
    //commentes out by JC
    //if ($detail) echo "<br>Chronological ranking done : Pedigree stretched over <b>$depth</b> generations.<br>$nl" ;
    if ($impr) {
        echo "</center><pre>$nl $nl";
        foreach ($chrono as $i => $val) {
            echo "<b>$i</b> : $val $IDs[$i] $fathers[$i] $mothers[$i] $nl";
        }
        echo "</pre>$nl";
        die('</html>');
    }
    $inds = array_flip($IDs);

    return 0;
}

/**
 * @param $s
 *
 * @return array
 */
function fetch_record($s)
{
    global $database;
    $r = $GLOBALS['xoopsDB']->queryF($database, $s);
    $n = 0;
    if ($r) {
        $n = $GLOBALS['xoopsDB']->getRowsNum($r);
    }
    if (0 == $n) {
        $record = ['0'];
    } else {
        $record = $GLOBALS['xoopsDB']->fetchBoth($r);
    }

    return $record;
}

/**
 * @param $ind
 * @param $gen
 *
 * @return int
 */
function count_all($ind, $gen)
{
    global $inds, $nb_gen, $nb_all, $fathers, $mothers;
    if ($ind) {
        ++$nb_all;
    }
    $s = $fathers[$ind];
    $d = $mothers[$ind];
    if ($s && $gen < $nb_gen) {
        count_all($s, $gen + 1);
    }
    if ($d && $gen < $nb_gen) {
        count_all($d, $gen + 1);
    }

    return 0;
}

/**
 * @param $ch
 * @param $niv
 *
 * @return int
 */
function add_multi($ch, $niv)
{
    global $implx, $couls, $nl;
    reset($implx);
    $first = 1;
    foreach ($implx as $im => $impl) {
        if ($impl[0] == $ch || $impl[1] == $ch) {
            if ($niv > 1 && $first) {
                echo "<br>$nl";
            } else {
                echo '&nbsp;&nbsp;&nbsp;';
            }
            $i = $im + 1;
            $j = min($im, 6);
            $c = $couls[$j];
            $first = 0;
            echo '<span color=' . $c . ' size="+2"><b>*' . $i . '*</b></span>';
        }
    }

    return 0;
}

/**
 * @param $ind
 * @param $gen
 * @param $class
 *
 * @return int
 */
function output_animal($ind, $gen, $class)
{
    global $depth, $IDs, $fathers, $mothers, $nl;
    if ($gen > $depth) {
        return 0;
    }
    $cell_content = '&Oslash;';
    if ($ind || 0 == $gen) {
        $ID = $IDs[$ind];
        $ani = set_name($ID);
        $name = $ani[1];
        $name = $ID;
        $cell_content = Pedigree\Utility::showParent($name) . $nl;
    }
    $rowspan = 1 << ($depth - $gen);
    echo '<td rowspan=' . $rowspan . ' align="center" class="' . $class . '">' . $cell_content . "</td>$nl";
    if ($gen < $depth) {
        $sire = 0;
        if ($ind || 0 == $gen) {
            $sire = $fathers[$ind];
        }
        output_animal($sire, $gen + 1, '0');
        $dam = 0;
        if ($ind || 0 == $gen) {
            $dam = $mothers[$ind];
        }
        output_animal($dam, $gen + 1, '1');
    } else {
        echo "</tr><tr>$nl";
    }

    return 0;
}

/**
 * @return int
 */
function SKETCH_PEDIGREE()
{
    global $nl, $detail, $depth, $IDs;
    // print_r ($IDs) ;
    echo $nl
         . '<br>'
         . $nl
         . '<table border="3" cellpadding="4" width="85%"" cellpadding="0" cellspacing="0">'
         . $nl
         . '<tr><th colspan="10" align="center">SKETCH &nbsp; PEDIGREE &nbsp; OF COMMON PROGENY</th></tr>'
         . $nl
         . '<tr align="center" valign="middle"><th>Progeny</th><th>'
         . _('Sire / Dam')
         . '</th>';
    if ($depth >= 2) {
        echo '<th>' . _('Grandparents') . '</th>' . $nl;
    }
    if ($depth >= 3) {
        echo '<th>' . _('Great-Grandparents') . '</th>' . $nl;
    }
    if ($depth >= 4) {
        echo '<th>3xGr. P.</th>' . $nl;
    }
    if ($depth >= 5) {
        echo '<th>4xGr. P.</th>' . $nl;
    }
    if ($depth >= 6) {
        echo '<th>5xGr. P.</th>' . $nl;
    }
    if ($depth >= 7) {
        echo '<th>6xGr. P.</th>' . $nl;
    }
    echo '</tr><tr>';
    output_animal(0, 0, '0'); /* output the sketch pedigree */
    echo $nl . '</tr></table>' . $nl . '<p>' . $nl;

    return 0;
}

/**
 * @return int
 */
function GENEALOGY()
{
    global $IDs, $fathers, $mothers, $inds, $nb_gen, $nb_maxi, $nbani, $nl, $sql1;
    $impr = 0;
    $fathers[0] = $IDs[1];
    $mothers[0] = $IDs[2];
    $fathers[1] = 0;
    $mothers[1] = 0;
    $fathers[2] = 0;
    $mothers[2] = 0;
    $last = 2;
    if ($impr) {
        echo "<!-- genealogy 'de cujus' (gener. 0) : $IDs[0] = $IDs[1] x $IDs[2] -->$nl";
    }
    $generation = [$IDs[1], $IDs[2]]; // starting with first generation (sire and dam)
    $nbtot = 0; // count of total known ascendants within $nb_gen generations
    for ($nloop = 1, $tot = 2; $last <= $nb_maxi && $nloop <= $nb_gen; ++$nloop) {
        $nbtot += $tot; // count of total known ascendants within $nb_gen generations
        $nbani = $last; // count of    distinct ascendants within $nb_gen generations
        $list = implode(',', array_unique($generation));
        $generation = [];
        $tot = 0;
        if ($impr) {
            echo "    [$list]$nl";
        }

        // HERE IS FETCHED EACH TRIPLET [ID, sire_ID, dam_ID] :
        $r = $GLOBALS['xoopsDB']->queryF("$sql1 IN ($list)");
        while (false !== ($rec = $GLOBALS['xoopsDB']->fetchBoth($r))) {
            $a = $rec[0] + 0;
            $s = $rec[1] + 0;
            $d = $rec[2] + 0;
            if (!isset($a)) {
                echo "ERROR : $a = $s x $d for list = '$list'<br>\n";
            }
            if ($s) {
                ++$tot;
            }
            if ($d) {
                ++$tot;
            }
            $j = array_keys($IDs, $a);
            $j = $j[0];
            $fathers[$j] = $s;
            $mothers[$j] = $d;
            if ($s && !in_array($s, $IDs)) {
                $i = ++$last;
                $IDs[$i] = $s;
                $fathers[$i] = 0;
                $mothers[$i] = 0;
                if ($s) {
                    $generation[] = $s;
                }
            }
            if ($d && !in_array($d, $IDs)) {
                $i = ++$last;
                $IDs[$i] = $d;
                $fathers[$i] = 0;
                $mothers[$i] = 0;
                if ($s) {
                    $generation[] = $d;
                }
            }
            if ($impr) {
                echo "<pre>genealogy ascendant (gener. $nloop) : $a = $s x $d  [tot = $tot]$nl</pre>";
            }
        }
        if (!count($generation)) {
            break;
        }
    }

    if ($nloop <= $nb_gen) {
        $nb_gen = $nloop;
    } // tree cut by $nb_maxi !

    reset($IDs);
    $inds = array_flip($IDs);

    chrono_sort();

    return $nbtot;
}

/**
 * @param $p
 *
 * @return int
 */
function dist_p($p)
{
    global $IDs, $fathers, $mothers, $pater, $nb_gen, $detail, $nl;
    // Anim #P is the sire
    $listall = [$p];
    $listnew = [$p];
    $pater = [];
    $pater[$p] = 1;
    for ($nloop = 2; $nloop < ($nb_gen + 1); ++$nloop) {
        $liste = [];
        foreach ($listnew as $i) {
            $s = $fathers[$i];
            $d = $mothers[$i];
            if ($s && !$pater[$s]) {
                $pater[$s] = $nloop;
            } // least distance from $s to sire's progeny
            if ($d && !$pater[$d]) {
                $pater[$d] = $nloop;
            } // least distance from $d to sire's progeny
            if ($s) {
                $liste[] = $s;
            }
            if ($d) {
                $liste[] = $d;
            }
        }
        if (!count($liste)) {
            break;
        }
        //commented pout by jc
        //if (in_array ($IDs[2], $liste) && !$detail)
        //{ echo "<p>DAM is an ascendant (at $nloop generations) of SIRE.  Stopped." ;
        // die ("</body></html>$nl") ; }
        $listnew = array_diff(array_unique($liste), $listall);
        /* $list1 = join (' ', $listall) ; $list2 = join ('+', $listnew) ;
             echo "<!-- P ($nloop) $list1/$list2 -->$nl" ; */
//        $listall = array_merge($listall, $listnew);
        $listall[] = $listnew;
    }
    //    $listall = call_user_func_array('array_merge', $listall);
    $listall = array_merge(...$listall);

    // Here $pater array contains list of all distinct ascendants of #P (including P himself)
    // Values of $pater are minimum distances to #P (in generations) +1
    return 0;
}

/**
 * @param $m
 *
 * @return int
 */
function dist_m($m)
{
    global $IDs, $fathers, $mothers, $mater, $nb_gen, $detail, $nl;
    // Anim #M is the dam
    $listall = [$m];
    $listnew = [$m];
    $mater = [];
    $mater[$m] = 1;
    for ($nloop = 2; $nloop <= ($nb_gen + 1); ++$nloop) {
        $liste = [];
        foreach ($listnew as $i) {
            $s = $fathers[$i];
            $d = $mothers[$i];
            if ($s && !isset($mater[$s])) {
                $mater[$s] = $nloop;
            } // least distance from $s to dam's progeny
            if ($d && !isset($mater[$d])) {
                $mater[$d] = $nloop;
            } // least distance from $d to dam's progeny
            // echo "I=" . $i . " MATER(I)=" . $mater[$i] . " NLOOP=" . $nloop . "<br>$nl" ;
            if ($s) {
                $liste[] = $s;
            }
            if ($d) {
                $liste[] = $d;
            }
        }
        if (!count($liste)) {
            break;
        }
        //commented out by jc
        //if (in_array ($IDs[1], $liste) && !$detail)
        // { echo "<p>SIRE is an ascendant (at $nloop generations) of DAM.  Stopped." ;
        //  die ("</body></html>$nl") ; }
        $listnew = array_diff(array_unique($liste), $listall);
        // $list1 = join (' ', $listall) ; $list2 = join ('+', $listnew) ; echo "M ($nloop) $list1/$list2 $nl" ;
//        $listall = array_merge($listall, $listnew);
        $listall[] = $listnew;
    }
//    $listall = call_user_func_array('array_merge', $listall);
    $listall = array_merge(...$listall);

    // Here $mater array contains list of all distinct ascendants of #M (including M herself)
    // Values of $mater are minimum distances to #M (in generations) +1
    return 0;
}

/**
 * @return array
 */
function calc_dist() /* Common Ascendants and their distances */
{
    global $IDs, $fathers, $mothers, $nbanims, $pater, $mater, $empty, $nb_gen, $nl;
    global $dmax, $detail, $nb_gen;
    $distan = [];
    // dist_m (2) ;   has already been called
    dist_p($fathers[0]);
    $dmax = 0;
    $impr = 0;
    $dmx = 7;
    if ($detail) {
        $dmx += 2;
    }
    // ksort ($pater) ; print_r ($pater) ; echo "<br>$nl" ; ksort ($mater) ; print_r ($mater) ; echo "<br>$nl" ;
    foreach ($pater as $i => $p) {
        if ($p) {
            $m = $mater[$i];
            if ($m) {
                $di = $p + $m;
                if ($impr) {
                    echo " $i : $p + $m = $di <br>$nl";
                }
                if (!$dmax) {
                    $dmax = $dmx + $di - ceil($di / 2.);
                }
                if ($di > ($dmax + 2)) {
                    continue;
                }
                $distan[$i] = $di;
            }
        }
    }
    if (!$dmax) {
        $dmax = 2 * $nb_gen - 2;
    }

    return $distan;
}

/**
 * @param $p
 * @param $m
 * @param $a
 * @param $ndist
 *
 * @return int
 */
function mater_side($p, $m, $a, $ndist)
{
    global $fathers, $mothers, $marked, $COIs, $deltaf, $ICknown, $verbose, $nl, $chrono, $paternal_rank, $max_dist;
    if (!$m || $ndist > $max_dist) {
        return 0;
    }
    if ($p == $m) {
        /* IMPLEX FOUND (node of consanguinity) { for Anim #A */
        $already_known = $ICknown[$p];
    }
    if (!$already_known) {
        CONSANG($p);
    } // MAIN RECURSION:
    $ICp = $COIs[$p]; // we need to know the IC of Parent for Wright's formula
    if ($verbose && !$already_known && $ICp > 0.001 * $verbose) {
        echo "IC of Animal $p is $ICp$nl";
    }

    $incr = 1.0 / (1 << $ndist) * (1. + $ICp); // ******** applying WRIGHT's formula ********

    // [Note:  1 << $ndist is equal to 2 power $ndist]
    $COIs[$a] += $incr; // incrementing the IC of AnimC
    if (0 == $a) {
        $deltaf[$p] += $incr;
    /* contribution of Anim #P to IC of Anim #0 */
    // if ($verbose && $a == 0 && $incr > 0.0001*$verbose)
        //    echo "Animal $p is contributing for " . substr ($deltaf[$p], 0, 10) . " to the IC of Animal $a$nl" ;
    } else {
        if (!$marked[$m] && $chrono[$m] < $paternal_rank) {
            mater_side($p, $fathers[$m], $a, $ndist + 1);
            mater_side($p, $mothers[$m], $a, $ndist + 1);
        }
    }

    return 0;
}

/**
 * @param $p
 * @param $m
 * @param $a
 * @param $pdist
 * @return int
 */
function pater_side($p, $m, $a, $pdist)
{
    global $mater, $fathers, $mothers, $marked, $chrono, $paternal_rank;
    if (!$p) {
        return 0;
    }
    $paternal_rank = $chrono[$p];
    $marked[$p] = 1; /* cut paternal side */
    if ($a || isset($mater[$p])) {
        mater_side($p, $m, $a, $pdist);
    }
    pater_side($fathers[$p], $m, $a, $pdist + 1);
    pater_side($mothers[$p], $m, $a, $pdist + 1);
    $marked[$p] = 0; /* free paternal side */

    return 0;
}

/**
 * @param $a
 *
 * @return int
 */
function CONSANG($a)
{
    global $fathers, $mothers, $ICknown, $COIs, $nl;
    if (!$a || $ICknown[$a]) {
        return 0;
    }
    if (-1 == $a) {
        $a = 0;
    } // particular case : a= -1 means Anim #0 (to bypass above test)
    $IC_if_deadend = 0.0; // 0.0 means taht deadends are deemed to be total outcrosses...
    // if IC was already stored in the database for Aminal #A, it should be used here instead of 0.0
    $p = $fathers[$a];
    $m = $mothers[$a];
    if (!$p || !$m) {
        $COIs[$a] = $IC_if_deadend;
        $ICknown[$a] = 2;

        return 0;
    }

    if ($verbose) {
        echo "</center><pre>$nl";
    }
    pater_side($p, $m, $a, 1); // launch tree exploration
    if ($verbose) {
        echo "</pre><div style=\"text-align: center;\">$nl";
    }

    $ICknown[$a] = 1;
    $p = $fathers[$a];
    $m = $mothers[$a];
    foreach ($fathers as $i => $pere) {/* siblings share the same COI value */
        if ($i != $a && $pere == $p && $mothers[$i] == $m) {
            $COIs[$i] = $COIs[$a];
            $ICknown[$i] = 1;
        }
    }

    // echo "<!-- COI($a) = $COIs[$a] $IDs[$a] ($fathers[$a] x $mothers[$a])-->$nl" ;
    return 0;
}

/**
 * @param $nb_gen
 * @param $nloop
 *
 * @return int
 */
function boucle($nb_gen, $nloop)
{
    global $fathers, $mothers, $nbanims, $listing, $nl, $IDs;
    $nbtot = 0;
    $listing = '';
    if ($nloop < ($nb_gen + 20)) {
        $nloop = $nb_gen + 20;
    }
    $list = [0 => 1];
    //print_r($list);
    /* initialize list with Anim0 (rank = 1) */
    for ($j = 1; $j < $nloop; ++$j) {
        $new = 0;
        foreach ($list as $i => $rank) {
            if (false !== ($s = $fathers[$i])) {
                if (!$list[$s]) {
                    $new = 1;
                    if ($j < $nb_gen) {
                        ++$nbtot;
                    }
                }
                $list[$s] = $rank + 1;
                if ($j < $nb_gen) {
                    ++$nbtot;
                }
                if ($j > $nloop - 10) {
                    $listing .= "Loop $j: Animal #$s " . $IDs[$s] . $nl;
                }
            }
            if (false !== ($d = $mothers[$i])) {
                if (!$list[$d]) {
                    $new = 1;
                    if ($j < $nb_gen) {
                        ++$nbtot;
                    }
                }
                $list[$d] = $rank + 1;
                if ($j < $nb_gen) {
                    ++$nbtot;
                }
                if ($j > $nloop - 10) {
                    $listing .= "Loop $j: Animal #$d " . $IDs[$d] . $nl;
                }
            }
        }
        if (!$new) {
            break;
        }
    }
    if ($new) {
        $nbtot = 0;
    } /* Endless loop detected (see listing) */

    return $nbtot;
}

if (!function_exists('html_accents')) {
    /**
     * @param $string
     * @return mixed
     */
    function html_accents($string)
    {
        return $string;
    }
}

/**
 * @param $ID
 *
 * @return array
 */
function set_name($ID)
{
    //    global $sql2, $sql2bis, $xoopsDB;
    $name = ' ';
    $ID = (int)$ID;
    $ani = [];
    if ($ID) {
        $sqlQuery = 'SELECT id, naam, roft FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE id = '$ID'";
        $queryResult = $GLOBALS['xoopsDB']->query($sqlQuery);
        $ani = $GLOBALS['xoopsDB']->fetchBoth($queryResult);
        /*
        $name        = $ani[1];
        if ($sql2bis) { // true for E.R.o'S. only
            $name = html_accents($name);
            //$affx = $ani[5] ;  // affix-ID
            if ($affx) {
                $affix  = fetch_record("$sql2bis '$affx'");
                $type   = $affix[1];
                $affixe = html_accents($affix[0]);
                if ($type[0] === 'P') {
                    $name = '<i>' . $affixe . '</i>&nbsp;' . $name;
                }
                if ($type[0] === 'S') {
                    $name = $name . '&nbsp;<i>' . $affixe . '</i>';
                }
            }
            $ani[1] = $name;
        }
               */
    }

    return $ani;
}

/**
 * @param $ems
 *
 * @return string
 */
function Ems_($ems)
{
    if (function_exists('Ems')) {
        return Ems($ems);
    }
    if (!$ems) {
        return '&nbsp;';
    }
    $e = str_replace(' ', '+', $ems);
    $res = '<a href="#" style="text-decoration:none;" onClick="' . "window.open('http://www.somali.asso.fr/eros/decode_ems.php?$e'," . "'', 'resizable=no,width=570,height=370')" . '"' . "><b>$ems</b></a>";

    return $res;
}

/**
 * @param $ID
 *
 * @return string
 */
function one_animal($ID)
{
    global $xoopsDB;
    global $sex, $val, $sosa, $detail, $sql3;
    $content = '';
    $sosa = 12;
    // echo '<div style="position:relative;float:right;width=2.0em;color=white;">' . $sosa . '</div>' ;
    $animal = set_name($ID);

    if (is_array($animal)) {
        list($ID, $name, $sex, $hd, $ems) = $animal;
    }
    $sqlQuery = 'SELECT SQL_CACHE COUNT(id) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " where father = '$ID' or mother = '$ID'";
    $queryResult = $GLOBALS['xoopsDB']->queryF($sqlQuery);
    $nb = $GLOBALS['xoopsDB']->fetchBoth($queryResult);
    $nb_children = $nb[0];
    if (0 == $nb_children) {
        $nb_children = _MA_PEDIGREE_COI_NO;
    }
    //$dogid = $animal[0];
    $content .= '<tr><td><a href="dog.php?id=' . $ID . '">' . stripslashes($name) . '</a>';
    // if ($nb_enf == 0) echo ' &oslash;' ;
    if ($val) {
        $content .= $val;
    }
    if (1 == $sex) {
        $geslacht = '<img src="assets/images/female.gif">';
    }
    if (0 == $sex) {
        $geslacht = '<img src="assets/images/male.gif">';
    }
    $content .= '</td><td>' . $geslacht . '</td><td>' . $nb_children . _MA_PEDIGREE_COI_OFF . '</td></tr>';

    return $content;
}

/* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  MAIN  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

$nl = "\n"; // the newline character

//edit by jc
//$link = @mysqli_pconnect ($host, $database, $password)
//   or   die ("<html><body>Connection to database failed.</body></html>") ;

//$a = '';
$s = Request::getInt('s', 0, 'GET'); //_GET['s'];
$d = Request::getInt('d', 0, 'GET'); //$_GET['d'];
$detail = Request::getString('detail', '', 'GET'); //$_GET['detail'];

if (isset($si)) {
    $s = Pedigree\Utility::findId($si);
}
if (isset($da)) {
    $d = Pedigree\Utility::findId($da);
}
//test for variables
//echo "si=".$si." da=".$da." s=".$s." d=".$d;
$utils = $GLOBALS['xoopsDB']->queryF("SELECT user(), date_format(now(),'%d-%b-%Y')");
list($who, $jourj) = $GLOBALS['xoopsDB']->fetchBoth($utils);

if (isset($IC)) {
    $detail = -1;
    $a = $IC;
}

if (!isset($detail)) {
    $detail = 0;
}

if (!isset($a)) {
    if (isset($s) && !isset($d)) {
        $a = $s;
        $s = '';
    }
    if (isset($d) && !isset($s)) {
        $a = $d;
        $d = '';
    }
}

if (isset($a)) {
    $sqlQuery = 'SELECT id, father, mother, roft FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE id  = '$a'";
    $queryResult = $GLOBALS['xoopsDB']->query($sqlQuery);
    $rowhond = $GLOBALS['xoopsDB']->fetchBoth($queryResult);
    $a = $rowhond['id'];
    $s = $rowhond['father'];
    $d = $rowhond['mother'];
}
$a += 0;
$s += 0;
$d += 0; // [IDs are numbers]

$xoopsTpl->assign('ptitle', _MA_PEDIGREE_COI_CKRI);
$xoopsTpl->assign('pcontent', strtr(_MA_PEDIGREE_COI_CKRI_CT, ['[animalType]' => $moduleConfig['animalType']]));

if (!$s && !$d) {
    $error = _MA_PEDIGREE_COI_SPANF1 . $a . _MA_PEDIGREE_COI_SPANF2;
    $xoopsTpl->assign('COIerror', $error);
}

$maxn_ = 1000;
$maxr_ = 9;

$maxn = $maxn_;
$maxr = $maxr_;
$cinnamon = 0;
$chocolat = 0;
$dilution = 0;
$sexlred = 0;

$nivomin = -$maxr; /* Maximal depth of recursion (-10) */
$codec = 0;
$gens = 4; /* 4 gens. for both pedigrees of couple */
$litter = 0;

// echo "s:".$s."<br>";
// echo "d:".$d."<br>";

$codec1 = $d;
$codec2 = $s;
$val = '';

if (!$s && $d) {
    $codec1 = $d;
    $codec2 = 0;
}
if ($codec1 == $codec2) {
    $codec2 = 0;
}

$sqlQuery = 'SELECT father, mother, roft FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE id  = '$codec1'";
$queryResult = $GLOBALS['xoopsDB']->query($sqlQuery);
$rowhond = $GLOBALS['xoopsDB']->fetchBoth($queryResult);
$a1 = $rowhond['id'];
$s1 = $rowhond['father'];
$d1 = $rowhond['mother'];
$sex1 = $rowhond['roft'];

// echo "sqlquery:".$sqlQuery."<br>";

$sqlQuery = 'SELECT father, mother, roft FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE id  = '$codec2'";
$queryResult = $GLOBALS['xoopsDB']->query($sqlQuery);
$rowhond = $GLOBALS['xoopsDB']->fetchBoth($queryResult);
$a2 = $rowhond['id'];
$s2 = $rowhond['father'];
$d2 = $rowhond['mother'];
$sex2 = $rowhond['roft'];

// echo "sqlquery:".$sqlQuery."<br>";

//if ($sex1 == '0' && $sex2 == '1') { $a3 = $a1 ; $a1 = $a2 ; $a2 = $a3 ; }   /* permute dam and sire */
$codec1 = $a1;
$codec2 = $a2;
if (!isset($s1) || !isset($d1) || !isset($s2) || !isset($d2)) {
    $xoopsTpl->assign('COIerror', _MA_PEDIGREE_COI_SGPU);
}

$title = strtr(_MA_PEDIGREE_FLD_FATH, ['[father]' => $moduleConfig['father']])
           . ' ('
           . stripslashes(Pedigree\Utility::showParent($codec2))
           . ')'
           . _MA_PEDIGREE_COI_AND
           . strtr(_MA_PEDIGREE_FLD_MOTH, ['[mother]' => $moduleConfig['mother']])
           . ' ('
           . stripslashes(Pedigree\Utility::showParent($codec1))
           . ')';
$content = stripslashes(one_animal($codec2));
$content .= stripslashes(one_animal($codec1));
$val = '';
$xoopsTpl->assign('SADtitle', $title);
$xoopsTpl->assign('SADcontent', $content);
$xoopsTpl->assign('SADexplain', strtr(_MA_PEDIGREE_COI_SDEX, [
    '[animalType]' => $moduleConfig['animalType'],
    '[animalTypes]' => $moduleConfig['animalTypes'],
    '[children]' => $moduleConfig['children'],
]));

$de_cujus = 0;
$sire_ID = Request::getInt('s', 0, 'GET'); //$_GET['s'];
$dam_ID = Request::getInt('d', 0, 'GET'); //$_GET['d'];

$rec = 'SELECT id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE father = '" . $sire_ID . "' AND mother = '" . $dam_ID . "' ORDER BY naam";
$result = $GLOBALS['xoopsDB']->query($rec);
$content = '';
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $content .= one_animal($row['id']);
}

$xoopsTpl->assign('COMtitle', strtr(_MA_PEDIGREE_COI_COMTIT, [
    '[father]' => $moduleConfig['father'],
    '[mother]' => $moduleConfig['mother'],
]));
$xoopsTpl->assign('COMexplain', strtr(_MA_PEDIGREE_COI_COMEX, [
    '[animalType]' => $moduleConfig['animalType'],
    '[children]' => $moduleConfig['children'],
]));
$xoopsTpl->assign('COMcontent', $content);

if (!isset($nb_gen)) {
    $nb_gen = 7;
    if ($detail) {
        $nb_gen = 9;
    }
} elseif ($nb_gen < $pedigree) {
    $nb_gen = $pedigree;
}

$IDs = [$de_cujus + 0, $codec1 + 0, $codec2 + 0]; /* Structuring animal IDs into memory */

$nbanims = GENEALOGY(); // ************************************************************* //

for ($i = 0; $i <= $nbanims; ++$i) {
    $empty[$i] = 0;
}

foreach ($fathers as $i => $a) {
    if ($a) {
        $fathers[$i] = $inds[$a];
    }
} /* Replace parents codes */
foreach ($mothers as $i => $a) {
    if ($a) {
        $mothers[$i] = $inds[$a];
    }
} /*   by  their  indices  */

dist_m($mothers[0]); // set "$mater" array (list of all maternal ascendants), for Anim #0

/* Calculating CONSANGUINITY by dual (paternal & maternal) path method */
$f = $empty;
$ICknown = $empty;
$deltaf = $empty;
$marked = $empty;
$SSDcor = $SSDsire = $SSDdam = '';

/******************  LAUNCHING ALL RECURSIONS  ********************/
/*                                                                */
CONSANG(-1); /* [-1 is standing for de_cujus]
/*                                                                */
/******************************************************************/

$nf = ceil(100 * $COIs[0]);
if ($nf >= 55) {
    $w = _MA_PEDIGREE_COI_HUGE;
} else {
    if ($nf >= 35) {
        $w = _MA_PEDIGREE_COI_VHIG;
    } else {
        if ($nf >= 20) {
            $w = _MA_PEDIGREE_COI_HIGH;
        } else {
            if ($nf >= 10) {
                $w = _MA_PEDIGREE_COI_MEDI;
            } else {
                if ($nf >= 05) {
                    $w = _MA_PEDIGREE_COI_LOW;
                } else {
                    if ($nf >= 02) {
                        $w = _MA_PEDIGREE_COI_VLOW;
                    } else {
                        if ($nf >= 01) {
                            $w = _MA_PEDIGREE_COI_VVLO;
                        } else {
                            $w = _MA_PEDIGREE_COI_TLTB;
                        }
                    }
                }
            }
        }
    }
}
$w = _MA_PEDIGREE_COI_TVI . ' ' . $w;

$nb_all = 0;
count_all(0, 0); // count all ascendants in flat tree

$nbmax = (2 << $nb_gen) - 2;
$asctc = _MA_PEDIGREE_COI_ASTC . $nb_gen . _MA_PEDIGREE_COI_ASTCGEN . $nbmax . ')';
$ascuni = _MA_PEDIGREE_COI_ASDKA . $nb_gen . _MA_PEDIGREE_COI_ASGEN;
$xoopsTpl->assign('ASCtitle', _MA_PEDIGREE_COI_ACTIT);
$xoopsTpl->assign('ASCtc', $asctc);
$xoopsTpl->assign('ASCuni', $ascuni);
$xoopsTpl->assign('ASCall', $nb_all);
$xoopsTpl->assign('ASCani', $nbani);
$xoopsTpl->assign('ASCexplain', _MA_PEDIGREE_COI_ACEX);

$f0 = mb_substr($COIs[0], 0, 8);
if (!$f0) {
    $f0 = 'n.a.';
}
$f1 = 100 * $f0;

$xoopsTpl->assign('COItitle', strtr(_MA_PEDIGREE_COI_COITIT, [
    '[father]' => $moduleConfig['father'],
    '[mother]' => $moduleConfig['mother'],
]));
$xoopsTpl->assign('COIperc', $w);
$xoopsTpl->assign('COIval', $f1);
$xoopsTpl->assign('COIexplain', strtr(_MA_PEDIGREE_COI_COIEX, [
    '[animalType]' => $moduleConfig['animalType'],
    '[animalTypes]' => $moduleConfig['animalTypes'],
    '[children]' => $moduleConfig['children'],
]));
$xoopsTpl->assign('COIcoi', _MA_PEDIGREE_COI_COI);
$dogid = Request::getInt('dogid', 0, 'GET');
$query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' SET coi=' . $f1 . ' WHERE id = ' . $dogid;
$GLOBALS['xoopsDB']->queryF($query);
arsort($deltaf);
$j = 1;
foreach ($deltaf as $i => $v) {
    if ($j > 12) {
        break;
    }
    ++$j;
    $code = $IDs[$i];
    $v = mb_substr($v, 0, 7);
    $animal = set_name($IDs[$i]);
    $name = $animal[1];
    if (!$name) {
        $name = $i . ' [' . $IDs[$i] . ']';
    }
    if ($v > 0.0001 && $v < 1.0) {
        $dogs[] = ['id' => $code, 'name' => stripslashes($name), 'coi' => 100 * $v];
    }
}

$xoopsTpl->assign('TCAtitle', _MA_PEDIGREE_COI_TCATIT);
$xoopsTpl->assign('TCApib', _MA_PEDIGREE_COI_TCApib);
$xoopsTpl->assign('dogs', $dogs);
$xoopsTpl->assign('TCAexplain', strtr(_MA_PEDIGREE_COI_TCAEX, [
    '[animalType]' => $moduleConfig['animalType'],
    '[animalTypes]' => $moduleConfig['animalTypes'],
    '[children]' => $moduleConfig['children'],
    '[mother]' => $moduleConfig['mother'],
    '[father]' => $moduleConfig['father'],
]));

if ($detail) {
    if ($verbose) {
        $verbose = 0;
    }
    if (count($COIs) > 1) {
        $ICs = $COIs;
        arsort($ICs);
        $j = 1;
        foreach ($ICs as $i => $ic) {
            if ($j > 12) {
                break;
            }
            ++$j;
            $ID = $IDs[$i];
            $ani = set_name($ID);
            $name = $ani[1];
            $ic = mb_substr($ic, 0, 6);
            if ($ic > 0.125 && $i) {
                $mia[] = ['id' => $ID, 'name' => stripslashes($name), 'coi' => 100 * $ic];
            }
        }
    }
    $xoopsTpl->assign('MIAtitle', _MA_PEDIGREE_COI_MIATIT);
    $xoopsTpl->assign('mia', $mia);
    $xoopsTpl->assign('MIAexplain', strtr(_MA_PEDIGREE_COI_MIAEX, ['[animalType]' => $moduleConfig['animalType']]));

    if (!$ICknown[1]) {
        $marked = $empty;
        CONSANG(1);
    } // Sire
    if (!$ICknown[2]) {
        $marked = $empty;
        CONSANG(2);
    } // Dam
    $COR = 2.0 * $COIs[0] / sqrt((1. + $COIs[1]) * (1. + $COIs[2]));
    $COR = mb_substr($COR, 0, 8);
    if (!$COR) {
        $COR = 'n.a.';
    }
    $f1 = mb_substr($COIs[1], 0, 8);
    $f2 = mb_substr($COIs[2], 0, 8);
    if (!$f1) {
        $f1 = 'n.a.';
    }
    if (!$f2) {
        $f2 = 'n.a.';
    }
    $SSDcor = (100 * $COR);
    $SSDsire = (100 * $f2);
    $SSDdam = (100 * $f1);
}

$xoopsTpl->assign('SSDtitle', strtr(_MA_PEDIGREE_COI_SSDTIT, [
    '[father]' => $moduleConfig['father'],
    '[mother]' => $moduleConfig['mother'],
]));
$xoopsTpl->assign('SSDcortit', _MA_PEDIGREE_COI_SSDcor);
$xoopsTpl->assign('SSDbsd', strtr(_MA_PEDIGREE_COI_SDDbsd, ['[father]' => $moduleConfig['father'], '[mother]' => $moduleConfig['mother']]));
$xoopsTpl->assign('SSDcor', $SSDcor);

$xoopsTpl->assign('SSDS', _MA_PEDIGREE_COI_COI . _MA_PEDIGREE_FROM . strtr(_MA_PEDIGREE_FLD_FATH, ['[father]' => $moduleConfig['father']]));
$xoopsTpl->assign('SSDsire', $SSDsire);
$xoopsTpl->assign('SSDM', _MA_PEDIGREE_COI_COI . _MA_PEDIGREE_FROM . strtr(_MA_PEDIGREE_FLD_MOTH, ['[mother]' => $moduleConfig['mother']]));
$xoopsTpl->assign('SSDdam', $SSDdam);

// echo "SSDsire : ".$SSDsire."<br>";
// echo "SSDdam : ".$SSDdam."<br>";
// print_r($COIs);

$xoopsTpl->assign('SSDexplain', strtr(_MA_PEDIGREE_COI_SSDEX, [
    '[father]' => $moduleConfig['father'],
    '[mother]' => $moduleConfig['mother'],
    '[animalType]' => $moduleConfig['animalTypes'],
]));
$xoopsTpl->assign('TNXtitle', _MA_PEDIGREE_COI_TNXTIT);
$xoopsTpl->assign('TNXcontent', _MA_PEDIGREE_COI_TNXCON);
$xoopsTpl->assign('Name', _MA_PEDIGREE_FLD_NAME);
$xoopsTpl->assign('Gender', _MA_PEDIGREE_FLD_GEND);
$xoopsTpl->assign('Children', strtr(_MA_PEDIGREE_FLD_PUPS, ['[children]' => $moduleConfig['children']]));

//add data to smarty template
$xoopsTpl->assign('explain', _MA_PEDIGREE_EXPLAIN);

//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';
