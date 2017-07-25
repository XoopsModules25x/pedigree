<{if $tsarray}>
    <{include file="db:table_sort.tpl"}>
<{/if}>

<table width="100%" class="outer" cellspacing="1">
    <tr>
        <td class="odd">
            <{if $fatherArray.letters}>
                <div class="pedigree_head_catletters" align="center"><{$fatherArray.letters}></div>
                <br>
            <{/if}>
            <{if $motherArray.letters}>
                <div class="pedigree_head_catletters" align="center"><{$motherArray.letters}></div>
                <br>
            <{/if}>
        </td>
    </tr>

    <tr>
        <th colspan="<{$numofcolumns}>">
            <{$nummatch}>
        </th>
    </tr>
    <{if $pages}>
        <tr>
            <td colspan="<{$numofcolumns}>" class="head" style="text-align: center;">
                <{$pages}>
            </td>
        </tr>
    <{/if}>
</table>

<table width="100%" class="outer" cellspacing="1" id="Result">
    <thead>
    <tr>
        <{foreach item=column from=$columns}>
            <td class="head">
                <{$column.columnname}>
            </td>
        <{/foreach}>
    </tr>
    </thead>
    <!-- Start results loop -->
    <tbody>
    <{foreach item=link from=$dogs}>
        <tr class="<{cycle values="even,odd"}>">
            <{if $width}>
            <td width="<{$width}>%">
                <{else}>
            <td>
                <{/if}>
                <{$link.gender}><{$link.link}>
            </td>
            <{foreach item=content from=$link.usercolumns}>
                <{if $width}>
                    <td width="<{$width}>%">
                        <{else}>
                    <td>
                <{/if}>
                <{$content.value}>
                </td>
            <{/foreach}>
        </tr>
    <{/foreach}>
    </tbody>
</table>

<{if $pages}>
    <table width="100%" class="outer" cellspacing="1" id="Result">
        <tr>
            <td colspan="<{$numofcolumns}>" class="head" style="text-align: center;">
                <{$pages}>
            </td>
        </tr>
    </table>
<{/if}>

<{if $tsarray}>
    <script type="text/javascript">
        <{$tsarray}>
    </script>
<{/if}>
