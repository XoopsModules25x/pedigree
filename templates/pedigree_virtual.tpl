<table>
    <tr>
        <th colspan="2">
            <{$virtualtitle}>
        </th>
    </tr>
    <tr>
        <td class="even" colspan="2">
            <{$virtualstory}>
        </td>
    </tr>
    <{if $nextaction}>
        <tr>
            <td class="odd" colspan="2">
                <{$nextaction}>
            </td>
        </tr>
        <tr>
            <td class="odd">
                <{if $fatherArray.letters}>
                    <div class="pedigree_head_catletters" align="center"><{$fatherArray.letters}></div>
                    <br>
                <{/if}>
            </td>
        </tr>
    <{/if}>
    <{if $virtualsire}>
        <tr>


            <td class="odd">
                <{$virtualsiretitle}>
            </td>
            <td class="even">
                <img src="assets/images/male.gif"><{$virtualsire}>
            </td>
        </tr>
    <{/if}>
    <{if $virtualdam}>
        <tr>
            <td class="odd">
                <{$virtualdamtitle}>
            </td>

            <td class="odd">
                <{if $motherArray.letters}>
                    <div class="pedigree_head_catletters" align="center"><{$motherArray.letters}></div>
                    <br>
                <{/if}>
            </td>


            <td class="even">
                <img src="assets/images/female.gif"><{$virtualdam}>
            </td>
        </tr>
    <{/if}>
</table>

<br><br>

<{if $form}>
    <{$form}>
<{/if}>

<{if $sire}>
    <{include file="db:pedigree_result.tpl" numofcolumns=$numofcolumns nummatch=$nummatch pages=$pages columns=$columns dogs=$dogs}>
<{/if}>
