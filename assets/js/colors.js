var DHTML = (document.getElementById || document.all || document.layers);

function getObj(name) {
    if (document.getElementById) {
        this.obj = document.getElementById(name);
        this.style = document.getElementById(name).style;
    }
    else if (document.all) {
        this.obj = document.all[name];
        this.style = document.all[name].style;
    }
    else if (document.layers) {
        this.obj = document.layers[name];
        this.style = document.layers[name];
    }
}

function changeCol() {
    if (!DHTML) return;

    var y = new getObj('mainbgcolor');
    var x = new getObj('background');
    x.style.backgroundColor = y.obj.value;

    var y = new getObj('mbgcolor');
    var s = new getObj('mtxtcolor');
    var f = new getObj('mfont');
    var fs = new getObj('mfontsize');
    var fst = new getObj('mfontstyle');

    for (i = 1; i < 8; i++) {
        var q = 'fathercell' + i;
        var x = new getObj(q);
        x.style.backgroundColor = y.obj.value;
        x.style.color = s.obj.value;
        x.style.fontFamily = f.obj.value;
        x.style.fontSize = fs.obj.value;
        x.style.fontStyle = fst.obj.value;

    }

    var y = new getObj('fbgcolor');
    var s = new getObj('ftxtcolor');
    var f = new getObj('ffont');
    var fs = new getObj('ffontsize');
    var fst = new getObj('ffontstyle');

    for (i = 1; i < 8; i++) {
        var q = 'mothercell' + i;
        var x = new getObj(q);
        x.style.backgroundColor = y.obj.value;
        x.style.color = s.obj.value;
        x.style.fontFamily = f.obj.value;
        x.style.fontSize = fs.obj.value;
        x.style.fontStyle = fst.obj.value;
    }

    var y = new getObj('sbgcolor');

    for (i = 1; i < 3; i++) {
        var q = 'selected' + i;
        var x = new getObj(q);
        x.style.backgroundColor = y.obj.value;
    }

    var bst = new getObj('bstyle');
    var bw = new getObj('bwidth');
    var bc = new getObj('bcolor');

    var s = new getObj('selected2');
    var st = new getObj('stxtcolor');
    var f = new getObj('sfont');
    var fs = new getObj('sfontsize');
    var fst = new getObj('sfontstyle');
    s.style.borderStyle = bst.obj.value;
    s.style.borderWidth = bw.obj.value;
    s.style.borderColor = bc.obj.value;
    s.style.color = st.obj.value;
    s.style.fontFamily = f.obj.value;
    s.style.fontSize = fs.obj.value;
    s.style.fontStyle = fst.obj.value;

    for (i = 1; i < 8; i++) {
        var q = 'fathercell' + i;
        var x = new getObj(q);
        x.style.borderStyle = bst.obj.value;
        x.style.borderWidth = bw.obj.value;
        x.style.borderColor = bc.obj.value;
        var q = 'mothercell' + i;
        var x = new getObj(q);
        x.style.borderStyle = bst.obj.value;
        x.style.borderWidth = bw.obj.value;
        x.style.borderColor = bc.obj.value;
    }
}
