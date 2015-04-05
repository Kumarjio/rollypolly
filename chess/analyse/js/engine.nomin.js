function Engine(b) {
    if (!$("#" + b)) {
        return false
    }
    var q = b;
    var h = "_engine_";
    var t = '<div class="loader"><div>loading</div><div class="loaderbar"><div class="sphere"></div><div class="sphere"></div><div class="sphere"></div><div class="sphere"></div></div></div>';
    var s = false;
    var r = new Array();
    var j = 0;
    var m = "";
    var f = "";
    this.Create = function() {
        var u = "";
        u = u + '<div class="engine_panel">';
        u = u + '<fieldset><legend>Engine</legend><select id="' + q + h + 'engine"><option value="garbochess">GarboChess JS</option><option value="stockfish" selected>Stockfish</option><option value="lozza">lozza</option><option value="houdini">Houdini</option><option value="fruit">Fruit</option><option value="toga2">Toga2</option><option value="critter">Critter</option></select>';
        u = u + '<select id="' + q + h + 'searchtime"><option value="5">Search time: 5s</option><option value="10">Search time: 10s</option><option value="15">Search time: 15s</option><option value="30">Search time: 30s</option><option value="45" selected>Search time: 45s</option></select>';
        u = u + '<select id="' + q + h + 'mode"><option value="sleep">Mode: sleep</option><option value="analyse" selected>Mode: just analyze</option><option value="white">Mode: play white</option><option value="black">Mode: play black</option></select>';
        u = u + '<button id="' + q + h + 'resume">Resume analysis</button></fieldset>';
        u = u + '<div id="' + q + h + 'output">';
        u = u + '<fieldset><legend>Preview</legend><div id="' + q + h + 'board"></div><div id="' + q + h + 'info" class="engine_info"></div><div id="' + q + h + 'status" class="engine_status engine_status_ready">ready.</div><div id="' + q + h + 'movelist" class="engine_movelist"></div></fieldset>';
        u = u + '<div id="' + q + h + 'ads" class="engine_ads"></iframe></div>';
        u = u + "</div>";
        $("#" + q).append(u);
        this.Refresh = function() {
            d()
        };
        d(objMainboard.GetFen());
        $("#" + q + h + "info").html($("#" + q + h + "engine option:selected").text() + " (" + $("#" + q + h + "searchtime").val() + "s)");
        $("#" + q + h + "engine").change(function() {
            i();
            $("#" + q + h + "info").html($("#" + q + h + "engine option:selected").text() + " (" + $("#" + q + h + "searchtime").val() + "s)")
        });
        $("#" + q + h + "searchtime").change(function() {
            i();
            $("#" + q + h + "info").html($("#" + q + h + "engine option:selected").text() + " (" + $("#" + q + h + "searchtime").val() + "s)")
        });
        $("#" + q + h + "resume").click(function() {
            o(0)
        });
        $("#" + q + h + "bestmove_1").click(function() {
            var v = $(this).attr("id").replace(/.*_([0-9]+)$/, "$1") * 1;
            if ($(this).hasClass("engine_bgactive")) {
                $(this).removeClass("engine_bgactive");
                $("#" + q + h + "board").html(c(objMainboard.GetFen()))
            } else {
                if (arrEngines[v][2]) {
                    $(".engine_bestmove").removeClass("engine_bgactive");
                    $(this).addClass("engine_bgactive");
                    $("#" + q + h + "board").html(c(arrEngines[v][1], arrEngines[v][7], 0))
                }
            }
        });
        $("#" + q + h + "board").live("click", function() {
            if (f && m && f.length > 0 && m.length > 0 && j < (f.split(" ").length - 1)) {
                $("#" + q + h + "board").html(c(m, f, ++j))
            }
            return false
        });
        $("#" + q + h + "moveback").live("click", function() {
            if (f && m && f.length > 0 && m.length > 0 && j > 0) {
                $("#" + q + h + "board").html(c(m, f, --j))
            }
            return false
        });
        $("#" + q + h + "moveforward").live("click", function() {
            if (f && m && f.length > 0 && m.length > 0 && j < (f.split(" ").length - 1)) {
                $("#" + q + h + "board").html(c(m, f, ++j))
            }
            return false
        })
    };
    this.SetMode = function(u) {
        $("#" + q + h + "mode").val(u)
    };
    this.Analyze = function(u) {
        var w = $("#" + q + h + "mode").val();
        var v = u || ($("#" + q + h + "engine").val() == "garbochess" || $("#" + q + h + "searchtime").val() <= "10") ? 200 : 2000;
        if (w != "sleep") {
            o(v)
        } else {
            i()
        }
    };
    var e = false;
    var l = false;

    function o(u) {
        if (e) {
            clearTimeout(e)
        }
        i();
        e = setTimeout(function() {
            e = false;
            l = new Date().getTime();
            if ($("#usertab").tabs("option", "selected") == 0) {
                k(0, l)
            }
        }, u)
    }

    function g(z, w) {
        var x = z.split(" ");
        var u = (x[1] != "b") ? "white" : "black";
        var y = $("#" + q + h + "mode").val();
        if (y == u) {
            var v = w.split(" ");
            objMainboard.PlayMove(v[0])
        }
    }

    function d(C, H, y, x, A, D, z, B, v) {
        var w = "";
        $("#" + q + h + "board").html(c(C, H, 0));
        var E = "-";
        var F = "-";
        if (H && H.match(/[a-h0-8qrnb ]+/)) {
            var u = H.split(" ");
            var G = C.replace(/^.* ([0-9]+)$/, "$1");
            G = G + ((C.match(/ w /)) ? ". " : ". ... ");
            E = G + objMainboard.ConvertNotation(C, u[0]);
            var I = objMainboard.GetNewFen(C, u[0]);
            if (u[1]) {
                G = I.replace(/^.* ([0-9]+)$/, "$1");
                G = G + ((I.match(/ w /)) ? ". " : ". ... ");
                F = G + objMainboard.ConvertNotation(I, u[1])
            }
        }
        y = (typeof y != "undefined" && y.toString().match(/^(\-|)[0-9]+$/)) ? y / 100 : "-";
        x = (x && x.toString().match(/^[0-9]+$/)) ? (x / 1000).toFixed(1) + "s" : "-";
        A = (A && A.toString().match(/^[0-9]+$/)) ? A : "-";
        B = (B && B.toString().match(/^(ces)|(srv).*/)) ? B : "-";
        z = (z && z.toString().match(/^[0-9]+$/)) ? " #" + z : "";
        D = (typeof D != "undefined" && D.toString().match(/^[0-9\.]+$/)) ? Math.round(D * 100) + "%" : "-";
        v = (v) ? v : "-";
        movelist = (H) ? n(C, H) : "";
        if (H && H.match(/[a-h0-8qrnb ]+/)) {
            $("#" + q + h + "movelist").html("Score: " + y + "<br />Best: " + E + "&nbsp;&nbsp; Ponder: " + F + "<br />Time: " + x + "&nbsp;&nbsp; Depth: " + A + "<br/>Server: " + B + "&nbsp;&nbsp; CPU" + z + ": " + D + "<br />Engine: " + v + "<br /><br /> " + movelist)
        } else {
            $("#" + q + h + "movelist").html("")
        }
    }

    function n(A, v) {
        var z = "";
        var y = v.split(" ");
        var x;
        var u = A;
        for (var w = 0; w < y.length; w++) {
            if (y[w].match(/^[abcdefgh]{1}[12345678]{1}[abcdefgh]{1}[12345678]{1}[BNRQbnrq]{0,1}$/)) {
                x = u.replace(/^.* ([0-9]+)$/, "$1");
                if (w == 0 && u.match(/ b /)) {
                    x = x + ". ... "
                } else {
                    x = ((u.match(/ w /)) ? " " + x + ". " : " ")
                }
                z = z + x;
                z = z + objMainboard.ConvertNotation(u, y[w]);
                u = objMainboard.GetNewFen(u, y[w])
            }
        }
        return z
    }

    function i() {
        l = false;
        $("#" + q + h + "status").removeClass("engine_status_loading");
        $("#" + q + h + "status").removeClass("engine_status_error");
        $("#" + q + h + "status").html("ready.");
        $("#" + q + h + "status").addClass("engine_status_ready");
        d(objMainboard.GetFen())
    }

    function getVal(data, sel)
    {
      for (var j = 0; j <= data.length; j++) {
        if (data[j] == sel) {
          return data[j+1];
        }
      }
    }

    function k(x, B, A) {
        if (!A) {
            A = objMainboard.GetPosID()
        }
        var w = objMainboard.GetFen();
        var u = w.replace(/\//g, ",");
        var v = $("#" + q + h + "engine").val();
        var y = $("#" + q + h + "searchtime").val() * 1;
        if (!y) {
            y = 15
        }
        /*try {
            piwikTracker.setCustomUrl("api," + v + "," + y);
            piwikTracker.setDocumentTitle("Engine API | " + v);
            piwikTracker.trackPageView();
            piwikTracker.enableLinkTracking()
        } catch (z) {}*/
        if (w != false) {
            if (v == "lozza") {
                r[B] = new Worker("/scripts/chess/lozza.js");
                r[B].onmessage = function(C) {
                  if (l == B) {
                      var tokens = C.data.split(' ');
                      if (tokens[0] == "info" && tokens.length >= 14) {
                        var str = '';
                        var initial_i = 15;
                        for (var i = initial_i; i < tokens.length; i++) {
                          str += tokens[i] + ' ';
                        }
                        //console.log(str);
                        engdepth = getVal(tokens, 'depth');
                        engtime = getVal(tokens, 'time');
                        engcp = getVal(tokens, 'cp');
                        engcp = Math.round(engcp / 10).toString();
                        console.log(w);
                        if (w.match(/ b /)) {
                            engcp = engcp * -1
                        }
                        console.log(str+"/"+engcp+"/"+engtime+"/"+engdepth);
                        //d(w, str, engcp, engtime, engdepth, "-", "-", "Client", "lozza");
                      } else if (tokens[0] == "bestmove") {
                        $('#engine_engine_movelist').html('Best Move: ' + tokens[1]);
                        g(w, tokens[1]);
                        if (!$("#" + q + h + "status").hasClass("engine_status_ready")) {
                            $("#" + q + h + "status").removeClass("engine_status_error");
                            $("#" + q + h + "status").removeClass("engine_status_loading");
                            $("#" + q + h + "status").html("ready.");
                            $("#" + q + h + "status").addClass("engine_status_ready");
                        }
                        r[B].terminate();
                      } else {
                        //console.log("else");
                      }
                  } else {
                      r[B].terminate();
                  }
                };
                r[B].error = function(C) {
                    console.log("Error from background worker:" + C.message);
                    r[B].terminate()
                };
                if (!$("#" + q + h + "status").hasClass("engine_status_loading")) {
                    $("#" + q + h + "status").removeClass("engine_status_ready");
                    $("#" + q + h + "status").removeClass("engine_status_error");
                    $("#" + q + h + "status").html(t);
                    $("#" + q + h + "status").addClass("engine_status_loading")
                }
                r[B].postMessage("position fen " + w);
                r[B].postMessage('go movetime ' + (y * 1000));
            } else if (v == "stockfish") {
                r[B] = new Worker("/scripts/chess/stockfish.js");
                r[B].onmessage = function(C) {
                  if (l == B) {
                      var tokens = C.data.split(' ');
                      if (tokens[0] == "info" && tokens[3] != "currmove" && tokens[1] != "nodes") {
                        var str = '';
                        var initial_i = 17;
                        if (tokens[17] == "pv") {
                          initial_i = 18;
                        }
                        for (var i = initial_i; i < tokens.length; i++) {
                          str += tokens[i] + ' ';
                        }
                        //console.log(str);
                        engdepth = tokens[2];
                        engtime = getVal(tokens, 'time');
                        engcp = getVal(tokens, 'cp');
                        engcp = Math.round(engcp / 10).toString();
                        if (w.match(/ b /)) {
                            engcp = engcp * -1
                        }
                        d(w, str, engcp, engtime, engdepth, "-", "-", "Client", "Stockfish 5");
                      } else if (tokens[0] == "bestmove") {
                        console.log(tokens);
                        $('#engine_engine_movelist').html('Best Move: ' + tokens[1]);
                        if (doMe) {
                          console.log(objMainboard);
                          doMeFunc(tokens[1]);
                        }
                        g(w, tokens[1]);
                        if (!$("#" + q + h + "status").hasClass("engine_status_ready")) {
                            $("#" + q + h + "status").removeClass("engine_status_error");
                            $("#" + q + h + "status").removeClass("engine_status_loading");
                            $("#" + q + h + "status").html("ready.");
                            $("#" + q + h + "status").addClass("engine_status_ready");
                        }
                        r[B].terminate();
                      } else {
                        console.log("else");
                      }
                  } else {
                      r[B].terminate();
                  }
                };
                r[B].error = function(C) {
                    console.log("Error from background worker:" + C.message);
                    r[B].terminate()
                };
                if (!$("#" + q + h + "status").hasClass("engine_status_loading")) {
                    $("#" + q + h + "status").removeClass("engine_status_ready");
                    $("#" + q + h + "status").removeClass("engine_status_error");
                    $("#" + q + h + "status").html(t);
                    $("#" + q + h + "status").addClass("engine_status_loading")
                }
                if (bookBin) {
                  r[B].postMessage({book: bookBin});
                }
                r[B].postMessage("position fen " + w);
                r[B].postMessage('go movetime ' + (y * 1000))
            } else if (v == "garbochess") {
                r[B] = new Worker("/chess/analyse/js/garbochess.js");
                r[B].onmessage = function(C) {
                    if (l == B) {
                        if (C.data.match("^pv") == "pv") {
                            data = C.data.substr(3, C.data.length - 3).split("  ");
                            engdepth = data[0].replace(/^Ply:([0-9]+) .*$/g, "$1");
                            engcp = data[0].replace(/^.*Score:([\-0-9]+) .*$/g, "$1");
                            engcp = Math.round(engcp / 10).toString();
                            engtime = data[0].replace(/^.*Time:([0-9]+).*$/g, "$1");
                            if (w.match(/ b /)) {
                                engcp = engcp * -1
                            }
                            d(w, data[1], engcp, engtime, engdepth, "-", "-", "Client", "GarboChess JS 6.0")
                        } else {
                            if (C.data.match("^message") == "message") {
                                console.log(C.data.substr(8, C.data.length - 8))
                            } else {
                                if (objMainboard.GetPosID() == A) {
                                    g(w, C.data)
                                }
                                if (!$("#" + q + h + "status").hasClass("engine_status_ready")) {
                                    $("#" + q + h + "status").removeClass("engine_status_error");
                                    $("#" + q + h + "status").removeClass("engine_status_loading");
                                    $("#" + q + h + "status").html("ready.");
                                    $("#" + q + h + "status").addClass("engine_status_ready")
                                }
                                r[B].terminate()
                            }
                        }
                    } else {
                        r[B].terminate()
                    }
                };
                r[B].error = function(C) {
                    console.log("Error from background worker:" + C.message);
                    r[B].terminate()
                };
                if (!$("#" + q + h + "status").hasClass("engine_status_loading")) {
                    $("#" + q + h + "status").removeClass("engine_status_ready");
                    $("#" + q + h + "status").removeClass("engine_status_error");
                    $("#" + q + h + "status").html(t);
                    $("#" + q + h + "status").addClass("engine_status_loading")
                }
                r[B].postMessage("position " + w);
                r[B].postMessage("search " + (y * 1000))
            } else {
                /*checkJSON = setTimeout(function() {
                    $.getJSON("/api/" + v + "/" + y + "/" + u, function(C) {
                        if (l == B) {
                            if (C && C.result == "success") {
                                if (!$("#" + q + h + "status").hasClass("engine_status_loading")) {
                                    $("#" + q + h + "status").removeClass("engine_status_ready");
                                    $("#" + q + h + "status").removeClass("engine_status_error");
                                    $("#" + q + h + "status").html(t);
                                    $("#" + q + h + "status").addClass("engine_status_loading")
                                }
                                if (C.data["status"] != "init") {
                                    d(C.data["fen"], C.data["pv"], C.data["cp"], C.data["time"], C.data["depth"], C.data["cpuusage"], C.data["cpunr"], C.data["server"], C.data["idname"])
                                }
                                if (C.data["status"] == "init") {
                                    d(C.data["fen"])
                                }
                                if (C.data["status"] != "ready") {
                                    delaytime = 200 * y;
                                    k(delaytime, B, A)
                                } else {
                                    if (objMainboard.GetPosID() == A) {
                                        g(C.data["fen"], C.data["pv"])
                                    }
                                    if (!$("#" + q + h + "status").hasClass("engine_status_ready")) {
                                        $("#" + q + h + "status").removeClass("engine_status_error");
                                        $("#" + q + h + "status").removeClass("engine_status_loading");
                                        $("#" + q + h + "status").html("ready.");
                                        $("#" + q + h + "status").addClass("engine_status_ready")
                                    }
                                }
                            } else {
                                if (!$("#" + q + h + "status").hasClass("engine_status_error")) {
                                    $("#" + q + h + "status").removeClass("engine_status_ready");
                                    $("#" + q + h + "status").removeClass("engine_status_loading");
                                    $("#" + q + h + "status").html("error.");
                                    $("#" + q + h + "status").addClass("engine_status_error")
                                }
                            }
                        }
                    })
                }, x)*/
            }
        } else {
            $("#" + q + h + "crafty").html("<pre> ready.</pre>")
        }
    }

    function a(v, z, y) {
        var B = new Array();
        B.R = "&#9814;";
        B.N = "&#9816;";
        B.B = "&#9815;";
        B.Q = "&#9813;";
        B.r = "&#9820;";
        B.n = "&#9822;";
        B.b = "&#9821;";
        B.q = "&#9819;";
        var F = new Array("a", "b", "c", "d", "e", "f", "g", "h");
        var C = new Array(1, 2, 3, 4, 5, 6, 7, 8);
        var w = z.split(" ");
        var x = "";
        var D = v;
        var G = 0;
        var E = 0;
        var H = "";
        var u = 0;
        for (var A = 0; A < w.length; A++) {
            H = w[A];
            x = (H.match(/[bnrq]{1}$/)) ? H[4] : "";
            if (y == "w") {
                x = x.toUpperCase()
            }
            H = H.replace(/a/g, "1");
            H = H.replace(/b/g, "2");
            H = H.replace(/c/g, "3");
            H = H.replace(/d/g, "4");
            H = H.replace(/e/g, "5");
            H = H.replace(/f/g, "6");
            H = H.replace(/g/g, "7");
            H = H.replace(/h/g, "8");
            intSrcKey = (8 - H[1] * 1) * 8 + H[0] * 1 - 1;
            E = (8 - H[3] * 1) * 8 + H[2] * 1 - 1;
            if (D[intSrcKey] == "p" && D[E] == " " && intSrcKey - E != -8 && intSrcKey - E != -16) {
                D = p(D, (E - 8), " ")
            } else {
                if (D[intSrcKey] == "P" && D[E] == " " && intSrcKey - E != 8 && intSrcKey - E != 16) {
                    D = p(D, (E + 8), " ")
                }
            }
            D = p(D, E, D[intSrcKey]);
            D = p(D, intSrcKey, " ");
            if (x != "") {
                D = p(D, E, x)
            }
            if (D[E] == "k" && intSrcKey == 4 && E == 2) {
                D = p(D, 0, " ");
                D = p(D, 3, "r")
            } else {
                if (D[E] == "k" && intSrcKey == 4 && E == 6) {
                    D = p(D, 7, " ");
                    D = p(D, 5, "r")
                } else {
                    if (D[E] == "K" && intSrcKey == 60 && E == 58) {
                        D = p(D, 56, " ");
                        D = p(D, 59, "R")
                    } else {
                        if (D[E] == "K" && intSrcKey == 60 && E == 62) {
                            D = p(D, 63, " ");
                            D = p(D, 61, "R")
                        }
                    }
                }
            }
            if (y == "b") {
                u++
            }
            y = (y == "b") ? "w" : "b"
        }
        return new Array(D, y, u)
    }

    function c(J, H, u) {
        H = $.trim(H);
        j = (u) ? u : 0;
        m = J;
        f = (H) ? H : "";
        var I;
        if (!J) {
            J = "8/8/8/8/8/8/8/8 w - - 0 0"
        }
        var A = new Array();
        A.P = "&#9817;";
        A.R = "&#9814;";
        A.N = "&#9816;";
        A.B = "&#9815;";
        A.K = "&#9812;";
        A.Q = "&#9813;";
        A.p = "&#9823;";
        A.r = "&#9820;";
        A.n = "&#9822;";
        A.b = "&#9821;";
        A.k = "&#9818;";
        A.q = "&#9819;";
        A[" "] = "";
        var v = J.split(" ");
        var G = v[0];
        while (G.match(/\/\//g)) {
            G = G.replace(/\/\//g, "/8/")
        }
        G = G.replace(/^\//g, "8/");
        G = G.replace(/\/$/g, "/8");
        G = G.replace(/\//g, "");
        G = G.replace(/1/g, " ");
        G = G.replace(/2/g, "  ");
        G = G.replace(/3/g, "   ");
        G = G.replace(/4/g, "    ");
        G = G.replace(/5/g, "     ");
        G = G.replace(/6/g, "      ");
        G = G.replace(/7/g, "       ");
        G = G.replace(/8/g, "        ");
        if (H) {
            var z = H.split(" ");
            var C = new Array();
            for (var y = 0; y < z.length && y <= u; y++) {
                C.push(z[y])
            }
            var F = a(G, C.join(" "), v[1]);
            G = F[0];
            v[1] = F[1];
            v[5] = v[5] * 1 + F[2];
            I = ((v[1] != "w") ? v[5] + ". " : (v[5] - 1) + "... ") + objMainboard.ConvertNotation(v.join(" "), z[y - 1]);
            H = z[y - 1].replace(/a/g, "1");
            H = H.replace(/b/g, "2");
            H = H.replace(/c/g, "3");
            H = H.replace(/d/g, "4");
            H = H.replace(/e/g, "5");
            H = H.replace(/f/g, "6");
            H = H.replace(/g/g, "7");
            H = H.replace(/h/g, "8");
            intSrcKey = (8 - H[1] * 1) * 8 + H[0] * 1 - 1;
            intDstKey = (8 - H[3] * 1) * 8 + H[2] * 1 - 1
        }
        var E = '<div class="engine_boardwrap">';
        var D = "";
        if (G.match(/^[ pbnrqkPBNRQK]{64}/)) {
            E = E + '<div class="engine_board">';
            var B = objMainboard.isFlipped();
            var w = "";
            for (var y = 7; y >= 0; y--) {
                for (var x = 0; x <= 7; x++) {
                    w = (7 - y) * 8 + x;
                    if (B) {
                        w = 63 - w
                    }
                    D = ((y + x) % 2 == 0) ? "engine_blacksquare" : "engine_whitesquare";
                    if (H && (w == intSrcKey || w == intDstKey)) {
                        D = D + " engine_markersquare"
                    }
                    E = E + '<div class="engine_square ' + D + '">' + A[G[w]] + "</div>"
                }
            }
            E = E + "</div>"
        }
        E += '<div class="engine_historymove">' + (I ? I : "&nbsp;") + "</div>";
        E = E + '<div class="engine_sidetomove">' + ((v[1] != "b") ? "White" : "Black") + " to move</div>";
        E = E + '<div class="engine_movepanel"><span id="' + q + h + 'moveback">' + ((j > 0) ? "\u25C0" : "\u25C1") + '</span><span id="' + q + h + 'moveforward">' + (((j + 1) < f.split(" ").length) ? "\u25B6" : "\u25B7") + "</span></div>";
        E = E + "</div>";
        return E
    }

    function p(w, u, v) {
        if (u > w.length - 1) {
            return w
        }
        return w.substr(0, u) + v + w.substr(u + 1)
    }
};