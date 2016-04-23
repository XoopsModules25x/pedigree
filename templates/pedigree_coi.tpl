<script language="JavaScript" type="text/javascript">
<!--
function showHide (id)
{
var style = document.getElementById(id).style
if (style.display == "none")
style.display = "inline";
else
style.display = "none";
}
// -->
</script>
<{if $COIerror}>
	<{$COIerror}>
<{else}>


<table width="100%">
	<tr>
		<td valign="top">
			<table border="0" width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$ptitle}>
					</th>
				</tr>
				<tr>
					<td class="odd">
						<{$pcontent}>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td valign="top">
			<table border="0" width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$SADtitle}>
					</th>
				</tr>
				<tr>
					<td class="odd">
						<table border="0" width="100%">
							<tr>
								<td class="even">
									<{$Name}>
								</td>
								<td class="even" width="10%">
									<{$Gender}>
								</td>
								<td class="even" width="20%">
									<{$Children}>
								</td>
							</tr>
							<tr>
								<td class="odd">
									<{$SADcontent}>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="odd">
						<li><a href="#" OnClick="showHide('SADexplain');return false"><{$explain}></a>
					</td>
				</tr>
			</table>
			<div id="SADexplain" style="display: none;">
				<{$SADexplain}>
			</div>
		</td>
	</tr>
		<tr><td>&nbsp;</td></tr>
	<tr>
		<td valign="top">
			<table border="0" width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$COMtitle}>
					</th>
				</tr>
				<tr>
					<td class="odd">
						<table border="0" width="100%">
							<tr>
								<td class="even">
									<{$Name}>
								</td>
								<td class="even" width="10%">
									<{$Gender}>
								</td>
								<td class="even" width="20%">
									<{$Children}>
								</td>
							</tr>
							<tr>
								<td class="odd">
									<{$COMcontent}>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="odd">
						<li><a href="#" OnClick="showHide('COMexplain');return false"><{$explain}></a>
					</td>
				</tr>
			</table>
			<div id="COMexplain" style="display: none;">
				<{$COMexplain}>
			</div>
		</td>
	</tr>
		<tr><td>&nbsp;</td></tr>
	<tr>
		<td valign="top">
			<table border="0" width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$ASCtitle}>
					</th>
				</tr>
				<tr>
					<td class="odd">
						<table border="0" width="100%">
							<tr>
								<td class="odd">
									<{$ASCtc}>
								</td>
								<td class="odd" width="25%">
									<{$ASCall}>
								</td>
							</tr>
							</tr>
								<td class="even">
									<{$ASCuni}>
								</td>
								<td class="even" width="25%">
									<{$ASCani}>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="odd">
						<li><a href="#" OnClick="showHide('ASCexplain');return false"><{$explain}></a>
					</td>
				</tr>
			</table>
			<div id="ASCexplain" style="display: none;">
				<{$ASCexplain}>
			</div>
		</td>
	</tr>
		<tr><td>&nbsp;</td></tr>
	<tr>
		<td valign="top">
			<table border="0" width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$COItitle}>
					</th>
				</tr>
				<tr>
					<td class="odd">
						<table border="0" width="100%">
							<tr>
								<td class="even">
									<a href="http://www.somali.asso.fr/eros/armstrong.htm#IC"><{$COIcoi}></a> (<{$COIperc}>)
								</td>
								<td class="even" width="25%">
									<{$COIval}>%
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="odd">
						<li><a href="#" OnClick="showHide('COIexplain');return false"><{$explain}></a>
					</td>
				</tr>
			</table>
			<div id="COIexplain" style="display: none;">
				<{$COIexplain}>
			</div>
		</td>
	</tr>
			<tr><td>&nbsp;</td></tr>
	<tr>
		<td valign="top">
			<table border="0" width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$TCAtitle}>
					</th>
				</tr>
				<tr>
					<td class="odd">
						<table border="0" width="100%">
							<{foreach item=link from=$dogs}>
								<tr class="<{cycle values="even,odd"}>">

									<td>
										<{$TCApib}><a href="dog.php?id=<{$link.id}>"><{$link.name}></a>
									</td>
									<td width="25%">
										<{$link.coi}>%
									</td>

								</tr>
							<{/foreach}>
						</table>
					</td>
				</tr>
				<tr>
					<td class="odd">
						<li><a href="#" OnClick="showHide('TCAexplain');return false"><{$explain}></a>
					</td>
				</tr>
			</table>
			<div id="TCAexplain" style="display: none;">
				<{$TCAexplain}>
			</div>
		</td>
	</tr>
				<tr><td>&nbsp;</td></tr>
	<tr>
		<td valign="top">
			<table border="0" width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$MIAtitle}>
					</th>
				</tr>
				<tr>
					<td class="odd">
						<table border="0" width="100%">
							<{foreach item=vals from=$mia}>
								<tr class="<{cycle values="even,odd"}>">

									<td>
										<a href="dog.php?id=<{$vals.id}>"><{$vals.name}></a>
									</td>
									<td width="25%">
										<{$vals.coi}>%
									</td>

								</tr>
							<{/foreach}>
						</table>
					</td>
				</tr>
				<tr>
					<td class="odd">
						<li><a href="#" OnClick="showHide('MIAexplain');return false"><{$explain}></a>
					</td>
				</tr>
			</table>
			<div id="MIAexplain" style="display: none;">
				<{$MIAexplain}>
			</div>
		</td>
	</tr>
					<tr><td>&nbsp;</td></tr>>
	<tr>
		<td valign="top">
			<table border="0" width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$SSDtitle}>
					</th>
				</tr>
				<tr>
					<td class="odd">
						<table border="0" width="100%">
								<tr class="even">
									<td>
										<a href="http://www.somali.asso.fr/eros/armstrong.htm#RC"><{$SSDcortit}></a><{$SSDbsd}>
									</td>
									<td width="25%">
										<{$SSDcor}>%
									</td>
								</tr>
								<tr class=odd">
									<td>
										<{$SSDS}>
									</td>
									<td width="25%">
										<{$SSDsire}>%
									</td>
								</tr>
								<tr class=even">
									<td>
										<{$SSDM}>
									</td>
									<td width="25%">
										<{$SSDdam}>%
									</td>
								</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="odd">
						<li><a href="#" OnClick="showHide('SSDexplain');return false"><{$explain}></a>
					</td>
				</tr>
			</table>
			<div id="SSDexplain" style="display: none;">
				<{$SSDexplain}>
			</div>
		</td>
	</tr>
						<tr><td>&nbsp;</td></tr>
	<tr>
		<td valign="top">
			<table border="0" width="100%" class="outer" cellspacing="1">
				<tr>
					<th>
						<{$TNXtitle}>
					</th>
				</tr>
				<tr>
					<td class="odd">
						<{$TNXcontent}>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<{/if}>

