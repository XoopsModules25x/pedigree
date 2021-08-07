<{if $registryRows > 0}>
    <div class="outer">
         <form name="select" action="registry.php?op=" method="POST"
              onsubmit="if(window.document.select.op.value =='') {return false;} else if (window.document.select.op.value =='delete') {return deleteSubmitValid('registryId[]');} else if (isOneChecked('registryId[]')) {return true;} else {alert('<{$smarty.const.AM_REGISTRY_SELECTED_ERROR}>'); return false;}">
            <input type="hidden" name="confirm" value="1">
            <div class="floatleft">
                   <select name="op">
                       <option value=""><{$smarty.const.AM_PEDIGREE_SELECT}></option>
                       <option value="delete"><{$smarty.const.AM_PEDIGREE_SELECTED_DELETE}></option>
                   </select>
                   <input id="submitUp" class="formButton" type="submit" name="submitselect" value="<{$smarty.const._SUBMIT}>" title="<{$smarty.const._SUBMIT}>"  >
               </div>
            <div class="floatcenter0">
                <div id="pagenav"><{$pagenav}></div>
            </div>



          <table class="$registry" cellpadding="0" cellspacing="0" width="100%">
            <tr><th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All"  value="Check All" ></th>  <th class="center"><{$selectorid}></th>  <th class="center"><{$selectorpname}></th>  <th class="center"><{$selectorid_owner}></th>  <th class="center"><{$selectorid_breeder}></th>  <th class="center"><{$selectoruser}></th>  <th class="center"><{$selectorroft}></th>  <th class="center"><{$selectormother}></th>  <th class="center"><{$selectorfather}></th>  <th class="center"><{$selectorfoto}></th>  <th class="center"><{$selectorcoi}></th>

<th class="center width5"><{$smarty.const.AM_PEDIGREE_FORM_ACTION}></th>
</tr>
<{foreach item=registryArray from=$registryArrays}>
<tr class="<{cycle values="odd,even"}>">

<td align="center" style="vertical-align:middle;"><input type="checkbox" name="registry_id[]"  title ="registry_id[]" id="registry_id[]" value="<{$registryArray.registry_id}>" ></td>
<td class='center'><{$registryArray.id}></td>
<td class='center'><{$registryArray.pname}></td>
<td class='center'><{$registryArray.id_owner}></td>
<td class='center'><{$registryArray.id_breeder}></td>
<td class='center'><{$registryArray.user}></td>
<td class='center'><{$registryArray.roft}></td>
<td class='center'><{$registryArray.mother}></td>
<td class='center'><{$registryArray.father}></td>
<td class='center'><{$registryArray.foto}></td>
<td class='center'><{$registryArray.coi}></td>


<td class="center width5"><{$registryArray.edit_delete}></td>
</tr>
<{/foreach}>
</table>
<br>
<br>
<{else}>
<table width="100%" cellspacing="1" class="outer">
<tr>

<th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All"  value="Check All" ></th>  <th class="center"><{$selectorid}></th>  <th class="center"><{$selectorpname}></th>  <th class="center"><{$selectorid_owner}></th>  <th class="center"><{$selectorid_breeder}></th>  <th class="center"><{$selectoruser}></th>  <th class="center"><{$selectorroft}></th>  <th class="center"><{$selectormother}></th>  <th class="center"><{$selectorfather}></th>  <th class="center"><{$selectorfoto}></th>  <th class="center"><{$selectorcoi}></th>

<th class="center width5"><{$smarty.const.AM_PEDIGREE_FORM_ACTION}></th>
</tr>
<tr>
<td class="errorMsg" colspan="11">There are no $registry</td>
</tr>
</table>
</div>
<br>
<br>
<{/if}>
