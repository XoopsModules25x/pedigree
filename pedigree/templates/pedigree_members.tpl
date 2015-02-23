<table width="100%" class="outer" cellspacing="1">
	<tr>
		<th colspan="3">
			<{$title}>
		</th>
	</tr>
	<tr>
		<td class="head">
			<{$position}>
		</td>
		<td class="head">
			&nbsp;
		</td>
		<td class="head">
			<{$numdogs}>
		</td>
	</tr>
	<!-- Start results loop -->
  <{foreach item=link from=$members}>
  <tr class="<{cycle values="even,odd"}>">
		<td>
			<{$link.position}>. <{$link.user}>
		</td>
		<td>
			<{$link.stars}>
		</td>
		<td>
			<{$link.nument}>
		</td>
  </tr>
  <{/foreach}>
</table>



