<script language="JavaScript" type="text/javascript">
    <!--
    function showHide(id) {
        var style = document.getElementById(id).style;
        if (style.display === "none")
            style.display = "inline";
        else
            style.display = "none";
    }
    // -->
</SCRIPT>

<{include file='db:pedigree_header.tpl'}>

<{if $showwelcome}>
    <table width="100%" class="outer" style="margin-bottom: 10px;">
        <tr>

            <{*<td class="even"><{$welcome}></td>*}>
            <{*<td class="even"><{$word}></td>*}>
        </tr>
        <{include file='db:pedigree_welcome.tpl'}>
    </table>
<{/if}>

<{if $catarray.letters}>

    <{$pageTitle}>
    <div class="pedigree_head_catletters" align="center"><{$catarray.letters}></div>
    <br>
<{/if}>
<{if $catarray.toolbar}>
    <div class="pedigree_head_cattoolbar" align="center"><{$catarray.toolbar}></div>
    <br>
<{/if}>

<table width="100%">
    <tr>
        <td valign="top">
            <!-- alphabet table -->
            <!--
            <table border="0" width="100%" class="outer" cellspacing="1">
                <tr>
                    <th colspan="6">
                        <{$sselect}>
                    </th>
                </tr>
                <tr>
                    <td width="16%" class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=a%25&amp;o=pname"><h2>A</h2></a>
                    </td>
                    <td width="16%" class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=b%25&amp;o=pname"><h2>B</h2></a>
                    </td>
                    <td width="16%" class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=c%25&amp;o=pname"><h2>C</h2></a>
                    </td>
                    <td width="16%" class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=d%25&amp;o=pname"><h2>D</h2></a>
                    </td>
                    <td width="16%" class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=e%25&amp;o=pname"><h2>E</h2></a>
                    </td>
                    <td width="16%" class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=f%25&amp;o=pname"><h2>F</h2></a>
                    </td>
                </tr>
                <tr>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=g%25&amp;o=pname"><h2>G</h2></a>
                    </td>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=h%25&amp;o=pname"><h2>H</h2></a>
                    </td>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=i%25&amp;o=pname"><h2>I</h2></a>
                    </td>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=j%25&amp;o=pname"><h2>J</h2></a>
                    </td>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=k%25&amp;o=pname"><h2>K</h2></a>
                    </td>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=l%25&amp;o=pname"><h2>L</h2></a>
                    </td>
                </tr>
                <tr>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=m%25&amp;o=pname"><h2>M</h2></a>
                    </td>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=n%25&amp;o=pname"><h2>N</h2></a>
                    </td>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=o%25&amp;o=pname"><h2>O</h2></a>
                    </td>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=p%25&amp;o=pname"><h2>P</h2></a>
                    </td>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=q%25&amp;o=pname"><h2>Q</h2></a>
                    </td>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=r%25&amp;o=pname"><h2>R</h2></a>
                    </td>
                </tr>
                <tr>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=s%25&amp;o=pname"><h2>S</h2></a>
                    </td>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=t%25&amp;o=pname"><h2>T</h2></a>
                    </td>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=u%25&amp;o=pname"><h2>U</h2></a>
                    </td>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=v%25&amp;o=pname"><h2>V</h2></a>
                    </td>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=w%25&amp;o=pname"><h2>W</h2></a>
                    </td>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=x%25&amp;o=pname"><h2>X</h2></a>
                    </td>
                </tr>
                <tr>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=y%25&amp;o=pname"><h2>Y</h2></a>
                    </td>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=z%25&amp;o=pname"><h2>Z</h2></a>
                    </td>
                    <td class="odd">
                        <li>
                    </td>
                    <td class="even">
                        <li>
                    </td>
                    <td class="odd">
                        <a href="result.php?f=pname&amp;l=1&amp;w=Å%25&amp;o=pname"><h2>Å</h2></a>
                    </td>
                    <td class="even">
                        <a href="result.php?f=pname&amp;l=1&amp;w=Ö%25&amp;o=pname"><h2>Ö</h2></a>
                    </td>
                </tr>
            </table>
-->
        </td>
        <td width="7">
        </td>
        <td valign="top">
            <!-- explanation starts here for search by name -->
            <table border="0" width="100%" class="outer" cellspacing="1">
                <tr>
                    <th>
                        <{$sname}>
                    </th>
                </tr>
                <tr>
                    <td class="odd">
                        <a href="#" OnClick="showHide('searchname');return false"><{$explain}></a>
                    </td>
                </tr>
                <tr>
                    <td class="even">
                        <form method="POST" action="result.php?&amp;l=1&amp;o=pname">
                            <input type="text" name="query" size="20">&nbsp;&nbsp;
                            <input type="submit" value="Search">
                        </form>
                    </td>
                </tr>
            </table>
            <div id="searchname" style="display: none;">
                <{$snameex}>
            </div>
            <!-- end explanation for search by name -->
            <br>


            <{foreach item=link from=$usersearch}>
                <{if $link != NULL}>
                    <!-- explanation starts here for usersearch -->
                    <table border="0" width="100%" class="outer" cellspacing="1">
                        <tr>
                            <th>
                                <{$link.title}>
                            </th>
                        </tr>
                        <tr>
                            <td class="odd">
                                <a href="#" OnClick="showHide('<{$link.searchid}>');return false"><{$explain}></a>
                            </td>
                        </tr>
                        <tr>
                            <td class="even">
                                <form method="POST" action="result.php?f=<{$link.function}>">
                                    <{$link.searchfield}>&nbsp;&nbsp;
                                    <input type="submit" value="Search">
                                </form>
                            </td>
                        </tr>
                    </table>
                    <div id="<{$link.searchid}>" style="display: none;">
                        <{$link.explanation}>
                    </div>
                    <!-- end explanation for usersearch -->
                    <br>
                <{/if}>
            <{/foreach}>

        </td>
    </tr>
</table>
