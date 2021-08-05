<script type="text/javascript">
    window.onload = function () {
        $("#piechart").CanvasJSChart({
            axisY: {
                title: "Products in %"
            },
            legend: {
                verticalAlign: "center",
                horizontalAlign: "right"
            },
            data: [
                {
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label} <br> {y} %",
                    indexLabel: "{y} %",
                    dataPoints: [
                        {label: "Male", y: <{$maledogs}>, legendText: "Male"},
                        {label: "Female", y: <{$femaledogs}>, legendText: "Female"}
                    ]
                }
            ]
        });
    }
</script>

<table class="width100">
    <tr>
        <!-- first column -->
        <td class="width50 top">
            <{if $pro}>
                <!-- top males and females -->
                <table class="width100 outer" cellspacing="1">
                    <tr>
                        <th>
                            <{$title}>
                        </th>
                    </tr>
                    <tr>
                        <td class="odd">
                            <{$topmales}>
                        </td>
                    </tr>
                    <tr>
                        <td class="even">
                            <{$topfemales}>
                        </td>
                    </tr>
                </table>
                <br>
            <{/if}>
            <!-- total number of males and females -->
            <table class="width100 outer" cellspacing="1">
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
                <tr>
                    <!-- pie chart -->
                    <td class="odd center">
                        <div id="piechart" class="width100" style="height: 300px;"></div>
                    </td>
                </tr>
            </table>
            <br>
            <{if $pro}>
                <!-- view orphans -->
                <table class="width100 outer" cellspacing="1">
                    <tr>
                        <th>
                            <{$orptitle}>
                        </th>
                    </tr>
                    <tr>
                        <td class="odd">
                            <{$orpall}>
                        </td>
                    </tr>
                    <tr>
                        <td class="even">
                            <{$orpdad}>
                        </td>
                    </tr>
                    <tr>
                        <td class="odd">
                            <{$orpmum}>
                        </td>
                    </tr>
                </table>
            <{/if}>
        </td>
        <td>&nbsp;</td>
        <!-- second column - only shown if content exists -->
        <{*<td class="width50 top">}>
            <!-- total number of dogs per pedigreebook -->
            <{foreach item=chapter from=$totpl name=ch}>
                <{if $smarty.foreach.ch.first}><td class="width50 top"><{/if}>
                <table class="width100 outer" cellspacing="1">
                    <tr>
                        <th colspan="3">
                            <{$chapter.title}>
                        </th>
                    </tr>
                    <{foreach item=link from=$chapter.content}>
                        <tr class="<{cycle values="even,odd"}>">
                            <td>
                                <{$link.book}>
                            </td>
                            <td>
                                <{$link.country}>
                            </td>
                        </tr>
                    <{/foreach}>
                </table>
                <br>
        <{if $smarty.foreach.ch.last}></td><{/if}>
            <{/foreach}>
        <{*</td>}>
    </tr>
</table>
