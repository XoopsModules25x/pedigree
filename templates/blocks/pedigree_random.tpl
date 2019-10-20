<table cellspacing="0">
    <tr>
        <td id="mainmenu">
            <!-- Start results loop -->
            <{foreach item=link from=$dogs}>
                <a href="modules/<{$modulename}>/pedigree.php?pedid=<{$link.id}>"><{$link.name}></a>
            <{/foreach}>
        </td>
    </tr>
</table>
<{$numdogs}>
