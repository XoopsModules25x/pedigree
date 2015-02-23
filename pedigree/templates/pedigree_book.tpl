<table width="100%">
	<tr>
		<th>
			<{$bookintro}>
		</th>
	</tr>
</table>
<br />
<table width="100%">
	<tr>
		<!-- first column -->
		<td width="50%" valign="top">

			<!-- total number of males and females -->
			<table width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$tnmftitle}>
					</th>
				</tr>
				<tr>
					<td class="odd">
						<{$countmales}>
					</td>
				</tr>
				<tr>
					<td class="even">
						<{$countfemales}>
					</td>
				</tr>
			</table>
			<br />
			<!-- total number of dogs per colour -->
			<table width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$dpctitle}>
					</th>
				</tr>
				<{foreach item=link from=$colours}>
				<tr class="<{cycle values="even,odd"}>">
					<td>
						<{$link.col}>
					</td>
				</tr>
				<{/foreach}>
			</table>
			<br />
		</td>
		<td>&nbsp;</td>
		<!-- second column -->
		<td width="50%" valign="top">
			<!-- total number of dogs per hd value -->
			<table width="100%" class="outer" cellspacing="1">
				<tr>
					<th colspan = "3">
						<{$hdtitle}>
					</th>
				</tr>
				<{foreach item=link from=$hd}>
				<tr class="<{cycle values="even,odd"}>">
					<td>
						<{$link.status}>
					</td>
				</tr>
				<{/foreach}>
			</table>
			<br />
			<!-- total number of dogs per phtvl value -->
			<table width="100%" class="outer" cellspacing="1">
				<tr>
					<th colspan = "3">
						<{$phtitle}>
					</th>
				</tr>
				<{foreach item=link from=$phtvl}>
				<tr class="<{cycle values="even,odd"}>">
					<td>
						<{$link.status}>
					</td>
				</tr>
				<{/foreach}>
			</table>
			<br />
			<!-- total number of dogs per "von willebrand" value -->
			<table width="100%" class="outer" cellspacing="1">
				<tr>
					<th colspan = "3">
						<{$willetitle}>
					</th>
				</tr>
				<{foreach item=link from=$wille}>
				<tr class="<{cycle values="even,odd"}>">
					<td>
						<{$link.status}>
					</td>
				</tr>
				<{/foreach}>
			</table>
			<br />
			<!-- total number of dogs per wobbler value -->
			<table width="100%" class="outer" cellspacing="1">
				<tr>
					<th colspan = "3">
						<{$wobblertitle}>
					</th>
				</tr>
				<{foreach item=link from=$wobbler}>
				<tr class="<{cycle values="even,odd"}>">
					<td>
						<{$link.status}>
					</td>
				</tr>
				<{/foreach}>
			</table>
			<br />
			<!-- total number of dogs per cardio value -->
			<table width="100%" class="outer" cellspacing="1">
				<tr>
					<th colspan = "3">
						<{$cardiotitle}>
					</th>
				</tr>
				<{foreach item=link from=$cardio}>
				<tr class="<{cycle values="even,odd"}>">
					<td>
						<{$link.status}>
					</td>
				</tr>
				<{/foreach}>
			</table>
			<br />
		</td>
	</tr>
</table>



