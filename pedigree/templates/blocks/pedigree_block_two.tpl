<p><b><{$block.lang.sample}></b></p>

<{* Display error messages, if any. If no error, do not show the table *}>
<{if $block.error.msg}>
<table border="0" cellpadding="10px" cellspacing="0" ID="Table1">
	<tr>
		<td>
			<{$block.lang.error}>:
		</td>
		<td>
			<{$block.error.msg}>
		</td>
	</tr>
	<tr>
		<td>
			SQL:
		</td>
		<td>
			<{$block.error.data}>
		</td>
	</tr>
</table>
<{/if}>

<{* Display the data *}>
<table cellspacing="0" ID="Table2">
	<{foreach item=link from=$block.data}>
	<tr>
		<td><{$link.table_one_char}></td>
		<td><{$link.table_one_text}></td>
	</tr>
	<{/foreach}>
</table>
