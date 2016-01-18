<p><b><{$block.lang.block_title}></b></p>
<table border="3">

	<{foreach item=link from=$block.links}>
	<tr>
		<td><a href="<{$link.url}>"><{$link.site}></a></td>
	</tr>
	<{/foreach}>
</table>
