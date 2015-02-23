<table width="100%">
	<!-- main table -->
	<tr>
		<!-- intro fields + content fields -->
		<td valign="top">
			<table>
				<{foreach item=link from=$dogs}>
					<tr class="<{cycle values="odd,even"}>">
						<td>
							<{$link.header}>
						</td>
						<td>
							<{$link.data}>
						</td>
					</tr>
				<{/foreach}>
			</table>
		</td>	
	</tr>
</table>
<p>
<{$form}>


