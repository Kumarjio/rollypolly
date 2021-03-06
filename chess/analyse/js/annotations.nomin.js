function Annotations(b) {
    if (!$("#" + b)) {
        return false
    }
    var t = b;
    var h = "_annotations_";
    var e = new Array();
    var k = new Array();
    var q = new Array();
    var i = 0;
    var s = false;
    var y = false;
    var c = false;
    var o = 15.5;
    var f = 70.5;
    var r = 4;
    var n = new Array();
    this.Create = function() {
        strHTML = '<div class="annotations_panel">';
        strHTML = strHTML + '<fieldset><legend>Annotations</legend><canvas id="' + t + h + 'canvas" width="450" height="150" style="border:0px solid #d3d3d3;">Your browser does not support the HTML5 canvas tag.</canvas></fieldset>';
        strHTML = strHTML + "</div>";
        $("#" + t).append(strHTML);
        d();
        z()
    };

    function z() {
        var B = document.getElementById(t + h + "canvas");
        B.addEventListener("mousedown", w, false);
        B.addEventListener("mouseover", l, false);
        B.addEventListener("mouseout", m, false);
        B.addEventListener("mousemove", p, false)
    }

    function w(C) {
        if (s) {
            var D = document.getElementById(t + h + "canvas");
            var B = D.getBoundingClientRect();
            c_x = C.clientX - B.left;
            c_y = C.clientY - B.top;
            c = Math.round((c_x - o) / r);
            c = Math.max(0, c);
            c = Math.min(c, k.length - 1);
            s = c;
            objMainboard.GotoMove(c + i);
            A();
            objEngine.Analyze(true)
        }
    }

    function l(B) {
        if (k.length > 1) {
            s = objMainboard.GetHistIndex()
        }
    }

    function p(C) {
        if (s) {
            var D = document.getElementById(t + h + "canvas");
            var B = D.getBoundingClientRect();
            c_x = C.clientX - B.left;
            c_y = C.clientY - B.top;
            c = Math.round((c_x - o) / r);
            c = Math.max(0, c);
            c = Math.min(c, k.length - 1);
            if (c >= 0 && c < k.length) {
                if (c != y) {
                    y = c;
                    objMainboard.GotoMove(c + i);
                    A()
                }
            } else {
                objMainboard.GotoMove(s + i)
            }
        }
    }

    function m(B) {
        if (s) {
            objMainboard.GotoMove(s + i);
            s = false;
            newmove = -1;
            oldmove = -1;
            A()
        }
    }
    this.Update = function() {
        a();
        A()
    };

    function A() {
        var J = 0;
        var C = 0;
        r = Math.floor(Math.min(420 / k.length, 4));
        var D = new Array();
        for (var B = 0; B < k.length; B++) {
            if (typeof e[k[B]] != "undefined" && e[k[B]] % 1 === 0) {
                J = Math.max(e[k[B]], J);
                C = Math.min(e[k[B]], C)
            }
        }
        var E = 60 / Math.max(J, C * -1);
        E = Math.min(0.35, E);
        var G = document.getElementById(t + h + "canvas");
        G.width = G.width;
        var I = G.getContext("2d");
        var F = Math.floor(Math.max(J, C * -1) / 500);
        F = Math.max(F, 1);
        for (var B = F; B * E * 100 < 60; B = B + F) {
            I.fillStyle = "#000000";
            I.strokeStyle = "#000000";
            I.font = "6pt Arial";
            I.textBaseline = "middle";
            I.textAlign = "end";
            I.fillText("+" + B, 13, f - E * 100 * B);
            I.fillText("-" + B, 13, f + E * 100 * B);
            I.beginPath();
            I.moveTo(o, f - E * 100 * B);
            I.lineTo(450, f - E * 100 * B);
            I.moveTo(o, f + E * 100 * B);
            I.lineTo(450, f + E * 100 * B);
            I.strokeStyle = "#d5d5d5";
            I.lineWidth = 1;
            I.stroke()
        }
        for (var B = 10; B * r + o < 450; B = B + 10) {
            offst = (i % 2 == 0) ? 0 : -1;
            I.fillStyle = "#000000";
            I.strokeStyle = "#000000";
            I.font = "6pt Arial";
            I.textBaseline = "bottom";
            I.textAlign = "center";
            I.fillText(Math.floor(i / 2 + (B / 2)), (B - 1 + offst) * r + o, 148);
            I.beginPath();
            I.moveTo((B - 1 + offst) * r + o, f - 60);
            I.lineTo((B - 1 + offst) * r + o, f + 65);
            I.strokeStyle = "#d5d5d5";
            I.lineWidth = 1;
            I.stroke()
        }
        I.fillText("S", o, 148);
        I.beginPath();
        I.moveTo(o, f - 60);
        I.lineTo(o, f + 65);
        I.strokeStyle = "#d5d5d5";
        I.lineWidth = 1;
        I.stroke();
        I.beginPath();
        I.moveTo(o, f);
        I.lineTo(450, f);
        I.strokeStyle = "#c0c0c0";
        I.lineWidth = 1;
        I.stroke();
        I.fillStyle = "#000000";
        I.strokeStyle = "#000000";
        I.font = "6pt Arial";
        I.textBaseline = "middle";
        I.textAlign = "end";
        I.fillText("0", 13, f);
        var H = 0;
        for (var B = 1; B < k.length; B++) {
            if (typeof e[k[B]] != "undefined" && e[k[B]] % 1 === 0) {
                I.beginPath();
                I.lineWidth = 1;
                I.moveTo(o + ((H) * r), f - (e[k[H]] * E));
                I.lineTo(o + (((B) * r)), f - (e[k[B]] * E));
                I.lineCap = "round";
                if (Math.abs(e[k[B]] - e[k[H]]) < 80) {
                    I.strokeStyle = "#00c000"
                } else {
                    I.strokeStyle = "#c00000"
                }
                I.stroke();
                H = B
            } else {
                D[B] = 1
            }
        }
        for (var B = 1; B < k.length; B++) {
            if (typeof D[B] != "undefined") {
                I.beginPath();
                I.lineWidth = 0;
                I.fillStyle = "#c7cefa";
                I.strokeStyle = "#c7cefa";
                I.fillRect(o + (((B - 1) * r)), f - 60, r, 120)
            }
        }
        if (s && c >= 0) {
            I.beginPath();
            I.moveTo((c) * r + o, f - 60);
            I.lineTo((c) * r + o, f + 65);
            I.strokeStyle = "#0000ff";
            I.lineWidth = 1;
            I.stroke()
        }
    }

    function d() {
        u();
        A();
        setTimeout(function() {
            d()
        }, 5000)
    }

    function a() {
        q = objMainboard.GetFenList();
        k = new Array();
        var D = q[0];
        var C = D.split(" ");
        i = C[5] * 2;
        if (C[1] == "w") {
            i = i - 2
        } else {
            i--
        }
        for (var B = 0; B < q.length; B++) {
            var F = q[B];
            var E = CryptoJS.MD5(F);
            k[B] = E
        }
    }

    function g() {
        a();
        for (var C = 0; C < q.length; C++) {
            var E = q[C];
            var D = CryptoJS.MD5(E);
            k[C] = D;
            if (typeof e[D] == "undefined" || e[D] % 1 !== 0) {
                var B = E.replace(/\//g, ",");
                $.getJSON("/api/stockfish/10/" + B, function(F) {
                    if (F && F.result == "success" && F.data["status"] == "ready") {
                        tmphash = CryptoJS.MD5(F.data["fen"]);
                        e[tmphash] = F.data["cp"]
                    }
                })
            }
        }
    }

    function j() {
        var B = 0;
        for (x in n) {
            if (typeof x != "undefined") {
                B++
            }
        }
        return B
    }

    function v(B) {
        var C = CryptoJS.MD5(B);
        n[C] = new Worker("/chess/analyse/js/garbochess.js");
        n[C].onmessage = function(D) {
            if (D.data.match("^pv") == "pv") {
                engcp = D.data.replace(/^.*Score:([\-0-9]+) .*$/g, "$1");
                engcp = Math.round(engcp / 10);
                if (B.match(/ b /)) {
                    engcp = engcp * -1
                }
                tmphash = CryptoJS.MD5(B);
                e[tmphash] = engcp
            } else {
                if (D.data.match("^message") == "message") {
                    console.log(D.data.substr(8, D.data.length - 8))
                } else {
                    n[C].terminate();
                    delete n[C]
                }
            }
        };
        n[C].error = function(D) {
            console.log("Error from background worker:" + D.message);
            n[C].terminate();
            delete n[C]
        };
        n[C].postMessage("position " + B);
        n[C].postMessage("search 20000")
    }

    function u() {
        a();
        for (var B = 0; B < q.length; B++) {
            var D = q[B];
            var C = CryptoJS.MD5(D);
            k[B] = C;
            if ((typeof e[C] == "undefined" || e[C] % 1 !== 0) && j() <= 3) {
                v(D)
            }
        }
    }
};