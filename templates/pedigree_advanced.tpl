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
                    toolTipContent: "{label} <br/> {y} %",
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

<table width="100%">
    <tr>
        <!-- first column -->
        <td width="50%" valign="top">
            <{if $pro}>
                <!-- top males and females -->
                <table width="100%" class="outer" cellspacing="1">
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
                <br/>
            <{/if}>
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
                <tr>
                    <!-- pie chart -->
                    <td class="odd" align="center">
                        <div id="piechart" style="width: 100%; height: 300px"></div>
                    </td>
                </tr>
            </table>
            <br/>
            <{if $pro}>
                <!-- view orphans -->
                <table width="100%" class="outer" cellspacing="1">
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
        <!-- second column -->
        <td width="50%" valign="top">
            <!-- total number of dogs per pedigreebook -->
            <{foreach item=chapter from=$totpl}>
                <table width="100%" class="outer" cellspacing="1">
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
                <br/>
            <{/foreach}>
        </td>
    </tr>
</table>



