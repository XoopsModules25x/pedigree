<{if $sire}>
	<{include file="db:pedigree_result.tpl" numofcolumns=$numofcolumns nummatch=$nummatch pages=$pages columns=$columns dogs=$dogs}>
<{/if}>

<form name="<{$searchform.name}>" id="<{$searchform.name}>" action="<{$searchform.action}>" method="<{$searchform.method}>" <{$searchform.extra}> >
    <table>
    <!-- start of visible form elements loop -->
    <{foreach item=element from=$searchform.elements}>
            <tr valign="top">
            	<{if $element.caption == ""}>
            		<td class="even"><{$element.caption}></td>
            	<{else}>
                	<td class="head"><{$element.caption}></td>
                <{/if}>
                <td class="<{cycle values="odd,even"}>" style="white-space: nowrap;"><{$element.body}></td>
            </tr>
    <{/foreach}>
    <!-- end of visible form elements loop -->
    </table>
</form>
