var objMainboard;
var objEngine;
var objRepository;
$(document).ready(function() {
    $("#usertab").tabs();
    $("#usertab").show();
    var b = false;
    if ($.getUrlVar("fen")) {
        b = $.getUrlVar("fen")
    }
    var a = false;
    if ($.getUrlVar("lmove")) {
        a = $.getUrlVar("lmove")
    }
    objMainboard = new Chessboard("mainboard", b, a);
    objMainboard.Create();
    objEditor = new Editor("editor");
    objEditor.Create();
    objEngine = new Engine("engine");
    objEngine.Create();
    var c = false;
    if ($.getUrlVar("play")) {
        c = $.getUrlVar("play");
        if (c == "1") {
            arrFen = b.split(" ");
            c = (arrFen[1] != "b") ? "black" : "white"
        }
        objEngine.SetMode(c)
    }
    objAnnotations = new Annotations("annotations");
    objAnnotations.Create();
    objRepository = new Repository("repository");
    objRepository.Create();
    objPGN = new PGN("pgn");
    objPGN.Create()
});
$.ajaxSetup({
    beforeSend: function(a) {
        a.setRequestHeader("If-Modified-Since", "0")
    }
});
$.extend({
    getUrlVars: function() {
        var d = [],
            c;
        var a = window.location.href.slice(window.location.href.indexOf("?") + 1).split("&");
        for (var b = 0; b < a.length; b++) {
            c = a[b].split("=");
            d.push(c[0]);
            d[c[0]] = decodeURIComponent(c[1])
        }
        return d
    },
    getUrlVar: function(a) {
        return $.getUrlVars()[a]
    }
});