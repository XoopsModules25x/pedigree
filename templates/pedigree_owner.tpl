<table class="width100">
    <!-- header; owner name -->
    <tr>
        <th colspan="2" class="center">
            <{$name}>
        </th>
    </tr>
    <!-- END header; owner name -->
    <!-- main table -->
    <tr>
        <!-- intro fields + content fields -->
        <td class="top">
            <table>
                <{foreach item=link from=$dogs}>
                    <tr class="<{cycle values="odd,even"}>">
                        <td>
                            <{$link.header}>
                        </td>
                        <td>
                            <{$link.data}>
                        </td>
                        <{if $access}>
                            <td>
                                <{$link.edit}>
                            </td>
                        <{/if}>
                    </tr>
                <{/foreach}>
                <{if $access}>
                    <tr class="odd">
                        <td>
                            <{$smarty.const._DELETE}>
                        </td>
                        <td>
                            <a href="deletebreeder.php?id=<{$id}>"><{$delete}></a>
                        </td>
                        <td>
                            &nbsp;
                        </td>
                    </tr>
                <{/if}>
            </table>
        </td>
        <!-- END intro fields + content fields -->
    </tr>
    <!-- END main table -->
</table>
