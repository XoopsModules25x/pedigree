<table width="100%" class="outer" cellspacing="1">
	<tr>
		<{foreach item=link from=$menuarray}>
		<td width="33%" class="<{cycle values="even,odd"}>">
			<a href="<{$xoops_url}>/modules/<{$modulename}>/<{$link.link}>"><{$link.title}></a>
		</td>
		<{if $link.counter == 3}>
			</tr>
			<tr>
		<{/if}>
		<{/foreach}>
	</tr>
</table>