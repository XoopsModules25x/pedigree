<table width="100%">
	<tr>
		<!-- first column -->
		<td width="33%" valign="top">
			<!-- webmaster tools -->
			<{foreach item=tool from=$tools}>
				<{if $tool.main == 1}>
					<li><a href="<{$tool.link}>"><{$tool.title}></a>
					<br />
				<{else}>
				        ----&nbsp;<a href="<{$tool.link}>"><{$tool.title}></a>
					<br />
				<{/if}>
			<{/foreach}>
		</td>
		<td>&nbsp;</td>
		<!-- second column -->
		<td width="66%" valign="top">
			<!-- form for tools -->
			<{$form}>
		</td>
	</tr>
</table>



