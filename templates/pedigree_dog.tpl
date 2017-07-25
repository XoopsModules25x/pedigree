<script language="JavaScript" type="text/javascript">
    <!--
    function showHide(id) {
        var style = document.getElementById(id).style;
        if (style.display == "none")
            style.display = "inline";
        else
            style.display = "none";
    }
    // -->
</SCRIPT>

<table width="100%">
    <!-- header; dog name -->
    <tr>
        <th colspan="3" style="text-align:center;">
            <{$name}>
        </th>
    </tr>
    <!-- main table -->
    <tr>
        <!-- intro fields + content fields -->
        <td valign="top">
            <table>
                <{foreach item=link from=$items}>
                    <tr class="<{cycle values="odd,even"}>">
                        <td>
                            <{$link.header}>
                        </td>
                        <td>
                            <{if $link.data == "pups"}>
                                <{include file="db:pedigree_result.tpl" numofcolumns=$numofcolumns nummatch=$nummatch pages=$pages columns=$columns dogs=$dogs width=$width}>
                            <{elseif $link.data == "bas"}>
                                <{include file="db:pedigree_result.tpl" numofcolumns=$numofcolumns1 nummatch=$nummatch1 pages=$pages1 columns=$columns1 dogs=$dogs1 width=$width}>
                            <{else}>
                                <{$link.data}>
                            <{/if}>
                        </td>
                        <{if $access}>
                            <td>
                                <{$link.edit}>
                            </td>
                        <{/if}>
                    </tr>
                <{/foreach}>

            </table>
        </td>
    </tr>
</table>
<br><br>
<{if $access}>
    <table width="100%">
        <tr>
            <th>
                Edit and Delete
            </th>
        </tr>
        <tr>
            <td valign="top">
                <table>
                    <tr class="even">
                        <td align="left">
                            <a href="edit.php?id=<{$id}>"> <img src="<{xoModuleIcons16 edit.png}>"
                                                                alt="<{$smarty.const._EDIT}>"
                                                                title="<{$smarty.const._EDIT}>"></a>

                            <a href="delete.php?id=<{$id}>"> <img src="<{xoModuleIcons16 delete.png}>"
                                                                  alt="<{$smarty.const._DELETE}>"
                                                                  title="<{$smarty.const._DELETE}>"></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
<{/if}>

<!-- comments system -->
<div style="text-align: center; padding: 3px; margin: 3px;">
    <{$commentsnav}>
    <{$lang_notice}>
</div>

<div style="margin: 3px; padding: 3px;">
    <!-- start comments loop -->
    <{if $comment_mode == "flat"}>
        <{include file="db:system_comments_flat.tpl"}>
    <{elseif $comment_mode == "thread"}>
        <{include file="db:system_comments_thread.tpl"}>
    <{elseif $comment_mode == "nest"}>
        <{include file="db:system_comments_nest.tpl"}>
    <{/if}>
    <!-- end comments loop -->
</div>
<!-- notification options -->
<{include file='db:system_notification_select.tpl'}>

