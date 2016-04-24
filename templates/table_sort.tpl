<style type="text/css">
    table thead td {
        font-weight: bold;
        cursor: pointer;
    }
</style>
<script type="text/javascript">
    /************************************************************************************************************
     (C) www.dhtmlgoodies.com, November 2005

     This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.

     Terms of use:
     You are free to use this script as long as the copyright message is kept intact. However, you may not
     redistribute, sell or repost it without our permission.

     Thank you!

     www.dhtmlgoodies.com
     Alf Magne Kalleland

     ************************************************************************************************************/

    var tableWidget_okToSort = true;
    var tableWidget_arraySort = new Array();
    tableWidget_tableCounter = 1;
    var activeColumn = new Array();

    function sortNumeric(a, b) {

        a = a.replace(/,/, '.');
        b = b.replace(/,/, '.');
        a = a.replace(/[^\d\.\/]/g, '');
        b = b.replace(/[^\d\.\/]/g, '');
        if (a.indexOf('/') >= 0)a = eval(a);
        if (b.indexOf('/') >= 0)b = eval(b);
        return a / 1 - b / 1;
    }


    function sortString(a, b) {

        if (a.toUpperCase() < b.toUpperCase()) return -1;
        if (a.toUpperCase() > b.toUpperCase()) return 1;
        return 0;
    }

    function sortTable() {
        if (!tableWidget_okToSort)return;
        tableWidget_okToSort = false;
        /* Getting index of current column */
        var obj = this;
        var indexThis = 0;
        while (obj.previousSibling) {
            obj = obj.previousSibling;
            if (obj.tagName == 'TD')indexThis++;
        }

        if (this.getAttribute('direction') || this.direction) {
            direction = this.getAttribute('direction');
            if (navigator.userAgent.indexOf('Opera') >= 0)direction = this.direction;
            if (direction == 'ascending') {
                direction = 'descending';
                this.setAttribute('direction', 'descending');
                this.direction = 'descending';
            } else {
                direction = 'ascending';
                this.setAttribute('direction', 'ascending');
                this.direction = 'ascending';
            }
        } else {
            direction = 'ascending';
            this.setAttribute('direction', 'ascending');
            this.direction = 'ascending';
        }

        var tableObj = this.parentNode.parentNode.parentNode;
        var tBody = tableObj.getElementsByTagName('TBODY')[0];

        var widgetIndex = tableObj.getAttribute('tableIndex');
        if (!widgetIndex)widgetIndex = tableObj.tableIndex;

        var sortMethod = tableWidget_arraySort[widgetIndex][indexThis]; // N = numeric, S = String
        if (activeColumn[widgetIndex] && activeColumn[widgetIndex] != this) {
            if (activeColumn[widgetIndex])activeColumn[widgetIndex].removeAttribute('direction');
        }

        activeColumn[widgetIndex] = this;

        var cellArray = new Array();
        var cellObjArray = new Array();
        for (var no = 1; no < tableObj.rows.length; no++) {
            var content = tableObj.rows[no].cells[indexThis].innerHTML + '';
            cellArray.push(content);
            cellObjArray.push(tableObj.rows[no].cells[indexThis]);
        }

        if (sortMethod == 'N') {
            cellArray = cellArray.sort(sortNumeric);
        } else {
            cellArray = cellArray.sort(sortString);
        }

        if (direction == 'descending') {
            for (var no = cellArray.length; no >= 0; no--) {
                for (var no2 = 0; no2 < cellObjArray.length; no2++) {
                    if (cellObjArray[no2].innerHTML == cellArray[no] && !cellObjArray[no2].getAttribute('allreadySorted')) {
                        cellObjArray[no2].setAttribute('allreadySorted', '1');
                        tBody.appendChild(cellObjArray[no2].parentNode);
                    }
                }
            }
        } else {
            for (var no = 0; no < cellArray.length; no++) {
                for (var no2 = 0; no2 < cellObjArray.length; no2++) {
                    if (cellObjArray[no2].innerHTML == cellArray[no] && !cellObjArray[no2].getAttribute('allreadySorted')) {
                        cellObjArray[no2].setAttribute('allreadySorted', '1');
                        tBody.appendChild(cellObjArray[no2].parentNode);
                    }
                }
            }
        }

        for (var no2 = 0; no2 < cellObjArray.length; no2++) {
            cellObjArray[no2].removeAttribute('allreadySorted');
        }

        tableWidget_okToSort = true;


    }
    function initSortTable(objId, sortArray) {
        var obj = document.getElementById(objId);
        obj.setAttribute('tableIndex', tableWidget_tableCounter);
        obj.tableIndex = tableWidget_tableCounter;
        tableWidget_arraySort[tableWidget_tableCounter] = sortArray;
        var tHead = obj.getElementsByTagName('THEAD')[0];
        var cells = tHead.getElementsByTagName('TD');
        for (var no = 0; no < cells.length; no++) {
            if (sortArray[no]) {
                cells[no].onclick = sortTable;
            } else {
                cells[no].style.cursor = 'default';
            }
        }
        for (var no2 = 0; no2 < sortArray.length; no2++) {  /* Right align numeric cells */
            if (sortArray[no2] && sortArray[no2] == 'N')obj.rows[0].cells[no2].style.textAlign = 'right';
        }

        tableWidget_tableCounter++;
    }

</script>
