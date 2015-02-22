<table width="100%" class="outer" cellspacing="1">
	<tr>
		<th colspan="3">
			<{$nummatch}>
		</th>
	</tr>
	<tr>
		<td colspan="3" class="head" style="text-align: center">
			<{$pages}>
		</td>
	</tr>
	<tr>
		<td class="head">
			<{$name}>
		</td>
		<td class="head">
			<{$parents}>
		</td>
		<td class="head">
			<{$addedby}>
		</td>
	</tr>
	<!-- Start results loop -->
  <{foreach item=link from=$dogs}>
  <tr class="<{cycle values="even,odd"}>">
		<td>
			<{$link.gender}><a href="pedigree.php?pedid=<{$link.id}>"><{$link.name}></a>
		</td>
		<td>
			<{$link.parents}>
		</td>
		<td>
			<{$link.addedby}>
		</td>
  </tr>
  <{/foreach}>
  <tr>
		<td colspan="3" class="head" style="text-align: center">
			<{$pages}>
		</td>
  </tr>
</table>



