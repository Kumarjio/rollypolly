/*
        Written by Alexander Mainzer w3EDV <info@w3edv.de>
        (c)2012 Alexander Mainzer, All Rights Reserved
*/
function Editor(h) {
    if (!$("#" + h)) {
        return false
    }
    var g = h;
    var d = "_editor_";
    var e = {
        P: "&#9817;",
        p: "&#9823;",
        R: "&#9814;",
        r: "&#9820;",
        N: "&#9816;",
        n: "&#9822;",
        B: "&#9815;",
        b: "&#9821;",
        Q: "&#9813;",
        q: "&#9819;",
        K: "&#9812;",
        k: "&#9818;",
        " ": ""
    };
    var a = {
        9817: "P",
        9823: "p",
        9814: "R",
        9820: "r",
        9816: "N",
        9822: "n",
        9815: "B",
        9821: "b",
        9813: "Q",
        9819: "q",
        9812: "K",
        9818: "k"
    };
    this.Create = function() {
        c();
        if (objMainboard.CheckFen($("#editor_fen").val())) {
            $("#editor_fen").addClass("editor_box_ok")
        } else {
            $("#editor_fen").addClass("editor_box_error");
            $("#editor_loadfen").addClass("editor_box_error");
            $("#editor_copyanalysis").addClass("editor_box_error")
        }
        $("#editor_sidetomove").live("change", function() {
            f()
        });
        $("#editor_castlingwk").live("change", function() {
            f()
        });
        $("#editor_castlingwq").live("change", function() {
            f()
        });
        $("#editor_castlingbk").live("change", function() {
            f()
        });
        $("#editor_castlingbq").live("change", function() {
            f()
        });
        $("#editor_ep").live("change", function() {
            f()
        });
        $("#editor_halfmove").live("change", function() {
            f()
        });
        $("#editor_fullmove").live("change", function() {
            f()
        });
        $("#editor_halfmove").live("keyup", function() {
            f()
        });
        $("#editor_fullmove").live("keyup", function() {
            f()
        });
        $("#editor_makefen").live("click", function() {
            f()
        });
        $("#editor_loadfen").live("click", function() {
            b()
        });
        $("#editor_fen").live("change", function() {
            b()
        });
        $("#editor_fen").live("keyup", function() {
            b()
        });
        $("#editor_initfen").live("click", function() {
            b("rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1")
        });
        $("#editor_resetfen").live("click", function() {
            b("8/8/8/8/8/8/8/8 w - - 0 1")
        });
        $("#editor_readanalysis").live("click", function() {
            b(objMainboard.GetFen())
        });
        $("#editor_copyanalysis").live("click", function() {
            objMainboard.LoadFen(f(true))
        });
        $(".editor_piece").live("mouseover", function() {
            if (!$(this).data("init")) {
                $(this).data("init", true);
                $(this).draggable({
                    revert: true,
                    revertDuration: 0,
                    stop: function(k, l) {
                        $(this).html("");
                        f()
                    }
                })
            }
        });
        $(".editor_piecerepo").draggable({
            revert: true,
            revertDuration: 0,
            stop: function(k, l) {
                f()
            }
        });
        $(".editor_square_drop").droppable({
            drop: function(k, l) {
                if (l.draggable.hasClass("editor_piecerepo") || l.draggable.hasClass("editor_piece")) {
                    $(this).html('<div class="editor_piece">' + l.draggable.html() + "</div>")
                }
            }
        })
    };
    this.FlipBoard = function() {
        if ((objMainboard.isFlipped() && $("#editor_square10").find("div").html() == "1") || (!objMainboard.isFlipped() && $("#editor_square10").find("div").html() != "1")) {
            var k = "";
            for (var m = 9; m >= 5; m--) {
                for (var l = 0; l <= 9; l++) {
                    k = $("#editor_square" + m + l).find("div").html();
                    $("#editor_square" + m + l).find("div").html($("#editor_square" + (9 - m) + "" + (9 - l)).find("div").html());
                    $("#editor_square" + (9 - m) + "" + (9 - l)).find("div").html(k)
                }
            }
        }
    };

    function c() {
        strOut = '<div class="editor_feneditor">';
        strOut += '<button class="button" id="editor_readanalysis">Get position from the main chessboard</button></h3>';
        strOut += "<fieldset><legend>Edit board</legend>";
        arrCols = new Array("a", "b", "c", "d", "e", "f", "g", "h");
        strOut += '<div class="editor_editorboard">';
        for (i = 9; i >= 0; i--) {
            for (j = 0; j <= 9; j++) {
                if (i != 0 && i != 9 && (j == 0 || j == 9)) {
                    strCssclass = "editor_square_rank";
                    strHTML = "<div>" + i + "</div>"
                } else {
                    if (j != 0 && j != 9 && (i == 0 || i == 9)) {
                        strCssclass = "editor_square_col";
                        strHTML = "<div>" + arrCols[j - 1] + "</div>"
                    } else {
                        if (i != 0 && i != 9 && j != 0 && j != 9 && (i + j) % 2 == 0) {
                            strCssclass = "editor_square_drop editor_square_dark";
                            strHTML = ""
                        } else {
                            if (i != 0 && i != 9 && j != 0 && j != 9) {
                                strCssclass = "editor_square_drop editor_square_light";
                                strHTML = ""
                            } else {
                                strCssclass = "editor_square_corner";
                                strHTML = ""
                            }
                        }
                    }
                }
                if (i != 0 && i != 9 && (j == 1)) {
                    strCssclass += " editor_square_left"
                }
                if (i != 0 && i != 9 && (j == 8)) {
                    strCssclass += " editor_square_right"
                }
                if (j != 0 && j != 9 && (i == 1)) {
                    strCssclass += " editor_square_bottom"
                }
                if (j != 0 && j != 9 && (i == 8)) {
                    strCssclass += " editor_square_top"
                }
                if (i == 8 && j == 1) {
                    strCssclass += " editor_square_topleft"
                }
                if (i == 8 && j == 8) {
                    strCssclass += " editor_square_topright"
                }
                if (i == 1 && j == 1) {
                    strCssclass += " editor_square_bottomleft"
                }
                if (i == 1 && j == 8) {
                    strCssclass += " editor_square_bottomright"
                }
                if (i != 0 && i != 9 && j != 0 && j != 9) {
                    strHTML = '<div class="editor_piece">' + strHTML + "</div>"
                }
                strOut += '<div id="editor_square' + i + j + '" class="' + strCssclass + '">' + strHTML + "</div>"
            }
        }
        strOut += "</div>";
        arrPieces = {
            P: "&#9817;",
            p: "&#9823;",
            R: "&#9814;",
            r: "&#9820;",
            N: "&#9816;",
            n: "&#9822;",
            B: "&#9815;",
            b: "&#9821;",
            Q: "&#9813;",
            q: "&#9819;",
            K: "&#9812;",
            k: "&#9818;"
        };
        strOut += '<div class="editor_repo">';
        $.each(arrPieces, function(k, l) {
            strOut += '<div class="editor_dragrepo"><div class="editor_piecerepo">' + l + "</div></div>"
        });
        strOut += "</div>";
        strOut += '<span class="editor_field">EP target:</span><span class="editor_field"><select id="editor_ep"><option value="-">none</option>';
        $.each(new Array("a3", "b3", "c3", "d3", "e3", "f3", "g3", "h3", "a6", "b6", "c6", "d6", "e6", "f6", "g6", "h6"), function(k, l) {
            strOut += '<option value="' + l + '">' + l + "</option>"
        });
        strOut += "</select></span>";
        strOut += '<div class="editor_fieldwrap">';
        strOut += '<span class="editor_field"><select id="editor_sidetomove"><option value="w">white to move</option><option value="b">black to move</option></select></span>';
        strOut += '<span class="editor_field">Halfmove clock:</span><span class="editor_field"><input type="text" size="3" maxlength="3" id="editor_halfmove" value="0"></span>';
        strOut += '<span class="editor_field">Fullmove number:</span><span class="editor_field"><input type="text" size="3" maxlength="3" id="editor_fullmove" value="1"></span>';
        strOut += '<span class="editor_field">White O-O:</span><span class="editor_field"><select id="editor_castlingwk"><option value="-">not allowed</option><option value="K">allowed</option></select></span>';
        strOut += '<span class="editor_field">White O-O-O:</span><span class="editor_field"><select id="editor_castlingwq"><option value="-">not allowed</option><option value="Q">allowed</option></select></span>';
        strOut += '<span class="editor_field editor_clear">Black O-O:</span><span class="editor_field"><select id="editor_castlingbk"><option value="-">not allowed</option><option value="k">allowed</option></select></span>';
        strOut += '<span class="editor_field">Black O-O-O:</span><span class="editor_field"><select id="editor_castlingbq"><option value="-">not allowed</option><option value="q">allowed</option></select></span>';
        strOut += "</div>";
        strOut += '<div class="editor_fenoutput">';
        strOut += 'Fen:<input type="text" id="editor_fen" value="8/8/8/8/8/8/8/8 w - - 0 1">';
        strOut += '<button class="button" id="editor_loadfen">FEN &rArr; board</button>';
        strOut += '<button class="button" id="editor_makefen">Board &rArr; FEN</button>';
        strOut += '<button class="button" id="editor_resetfen">Clear</button>';
        strOut += '<button class="button" id="editor_initfen">Starting position</button>';
        strOut += "</div>";
        strOut += "</fieldset>";
        strOut += '<div class="editor_dialog">';
        strOut += '<button id="editor_copyanalysis">Copy position to the main chessboard</button>';
        strOut += "</div>";
        strOut += "</div>";
        $("#" + g).append(strOut)
    }

    function f(q) {
        var k = "";
        var o = "";
        for (var n = 8; n >= 1; n--) {
            for (var l = 1; l <= 8; l++) {
                if ($("#editor_square" + n + l).find("div").html() != "") {
                    o = a[$("#editor_square" + n + l).find("div").html().charCodeAt(0)]
                } else {
                    o = " "
                }
                k = k + o
            }
            if (n != 1) {
                k = k + "/"
            }
        }
        k = k.replace(/        /g, "8");
        k = k.replace(/       /g, "7");
        k = k.replace(/      /g, "6");
        k = k.replace(/     /g, "5");
        k = k.replace(/    /g, "4");
        k = k.replace(/   /g, "3");
        k = k.replace(/  /g, "2");
        k = k.replace(/ /g, "1");
        if (objMainboard.isFlipped()) {
            k = k.split("").reverse().join("")
        }
        k = k + " " + $("#editor_sidetomove").val();
        var p = "";
        if ($("#editor_castlingwk").val() != "-") {
            p = p + "K"
        }
        if ($("#editor_castlingwq").val() != "-") {
            p = p + "Q"
        }
        if ($("#editor_castlingbk").val() != "-") {
            p = p + "k"
        }
        if ($("#editor_castlingbq").val() != "-") {
            p = p + "q"
        }
        if (p == "") {
            p = "-"
        }
        k = k + " " + p;
        k = k + " " + $("#editor_ep").val();
        var m = Math.min(999, Math.max(0, ($("#editor_halfmove").val().replace(/[^0-9]*/g, "") * 1)));
        k = k + " " + m;
        $("#editor_halfmove").editor_Val(m);
        var r = Math.min(999, Math.max(1, ($("#editor_fullmove").val().replace(/[^0-9]*/g, "") * 1)));
        k = k + " " + r;
        $("#editor_fullmove").editor_Val(r);
        if (!q) {
            $("#editor_fen").val(k);
            if (!objMainboard.CheckFen(k)) {
                $("#editor_fen").removeClass("editor_box_ok").addClass("editor_box_error");
                $("#editor_loadfen").addClass("editor_box_error");
                $("#editor_copyanalysis").addClass("editor_box_error")
            } else {
                $("#editor_fen").addClass("editor_box_ok").removeClass("editor_box_error");
                $("#editor_loadfen").removeClass("editor_box_error");
                $("#editor_copyanalysis").removeClass("editor_box_error")
            }
        }
        return k
    }

    function b(k) {
        if (!k) {
            k = $("#editor_fen").val()
        }
        k = k.replace(/^\s+/, "").replace(/\s[\s]+/g, " ");
        $("#editor_fen").editor_Val(k);
        if (!objMainboard.CheckFen(k)) {
            $("#editor_fen").removeClass("editor_box_ok").addClass("editor_box_error");
            $("#editor_loadfen").addClass("editor_box_error");
            $("#editor_copyanalysis").addClass("editor_box_error");
            if (k != "8/8/8/8/8/8/8/8 w - - 0 1") {
                return false
            }
        }
        k = k.replace(/[\s]+$/, " ");
        $("#editor_fen").editor_Val(k);
        var o = k.split(" ");
        eFen = o[0].replace(/8/g, "        ");
        eFen = eFen.replace(/7/g, "       ");
        eFen = eFen.replace(/6/g, "      ");
        eFen = eFen.replace(/5/g, "     ");
        eFen = eFen.replace(/4/g, "    ");
        eFen = eFen.replace(/3/g, "   ");
        eFen = eFen.replace(/2/g, "  ");
        eFen = eFen.replace(/1/g, " ");
        eFen = eFen.replace(/\//g, "");
        if (objMainboard.isFlipped()) {
            eFen = eFen.split("").reverse().join("")
        }
        var n = "";
        for (var m = 8; m >= 1; m--) {
            for (var l = 1; l <= 8; l++) {
                n = eFen[(8 - m) * 8 + l - 1];
                $("#editor_square" + m + l).find("div").html(e[n])
            }
        }
        $("#editor_sidetomove").val(o[1]);
        $("#editor_castlingwk").val(o[2].match(/K/) ? "K" : "-");
        $("#editor_castlingwq").val(o[2].match(/Q/) ? "Q" : "-");
        $("#editor_castlingbk").val(o[2].match(/k/) ? "k" : "-");
        $("#editor_castlingbq").val(o[2].match(/q/) ? "q" : "-");
        $("#editor_ep").val(o[3]);
        $("#editor_halfmove").val(o[4] * 1);
        $("#editor_fullmove").val(o[5] * 1);
        if (k != "8/8/8/8/8/8/8/8 w - - 0 1") {
            $("#editor_fen").addClass("editor_box_ok").removeClass("editor_box_error");
            $("#editor_loadfen").removeClass("editor_box_error");
            $("#editor_copyanalysis").removeClass("editor_box_error")
        }
    }
    $.fn.extend({
        editor_GetCaret: function() {
            var l = (typeof $(this).get(0).name != "undefined") ? l = $(this).get(0) : l;
            if ($.browser.msie) {
                l.focus();
                var k = document.selection.createRange();
                k.moveStart("character", -l.value.toString().length);
                return k.text.length
            } else {
                if ($.browser.mozilla || $.browser.webkit) {
                    return l.selectionStart
                }
            }
            return false
        },
        editor_SetCaret: function(m) {
            var l = (typeof $(this).get(0).name != "undefined") ? l = $(this).get(0) : l;
            if ($.browser.msie) {
                var k = l.createTextRange();
                k.collapse(true);
                k.moveEnd("character", m);
                k.moveStart("character", m);
                k.select()
            } else {
                if ($.browser.mozilla || $.browser.webkit) {
                    l.setSelectionRange(m, m)
                }
            }
        },
        editor_Val: function(n) {
            n = n + "";
            var l = $(this).val().toString();
            if (n !== l) {
                var k = document.activeElement;
                var m = $(this).editor_GetCaret();
                if (m !== false) {
                    m = Math.max(0, m - ((l).toString().length - (n).toString().length));
                    $(this).val(n.toString());
                    $(this).editor_SetCaret(m)
                } else {
                    $(this).val(n.toString())
                }
                if (k.tagName == "INPUT" || k.tagName == "TEXTAREA") {
                    k.focus()
                }
            }
        },
        editor_LoadFEN: function(k) {
            b(k)
        }
    })
};