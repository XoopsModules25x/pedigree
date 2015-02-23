<table width="100%" class="outer" cellspacing="1">
	<tr>
		<th colspan="2">
			<{$nummatch}>
		</th>
	</tr>
	<tr>
		<td colspan="2" class="head" style="text-align: center">
			<{$pages}>
		</td>
	</tr>
	<tr>
		<td class="head">
			<{$namelink}>
		</td>
		<td class="head">
			<{$colourlink}>
		</td>
	</tr>
	<!-- Start results loop -->
  <{foreach item=link from=$dogs}>
  <tr class="<{cycle values="even,odd"}>">
		<td>
			<a href="owner.php?ownid=<{$link.id}>"><{$link.name}></a>
		</td>
		<td>
			<{$link.city}>
		</td>
		
  </tr>
  <{/foreach}>

  <tr>
		<td colspan="3" class="head" style="text-align: center">
			<{$pages}>
		</td>
  </tr>
</table>



