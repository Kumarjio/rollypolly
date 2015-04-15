(function() {
    var a = Math.random;
    Math.random = function() {
        var b;
        try {
            if (window.crypto && window.crypto.getRandomValues) {
                var c = new Uint32Array(1);
                window.crypto.getRandomValues(c);
                b = c[0] / 4294967296
            } else {
                b = a.call()
            }
        } catch (d) {
            b = a.call()
        }
        return b
    }
})();
RegExp.escape = (function() {
    var a = ["/", ".", "*", "+", "?", "|", "(", ")", "[", "]", "{", "}", "\\"];
    var b = new RegExp("(\\" + a.join("|\\") + ")", "g");
    return function(c) {
        return c.replace(b, "\\$1")
    }
})();
if (!Array.prototype.shuffle) {
    Array.prototype.shuffle = function() {
        var a = new Hash();
        this.forEach(function(b) {
            a.put(b, Math.random())
        });
        this.sort(function(d, c) {
            return a.get(d) - a.get(c)
        });
        return this
    }
}
Array.prototype.fixedShuffle = function(g, f, d) {
    var a = g || 0;
    f = f || 3600;
    if (f > 0) {
        d = d || (new Date()).getTime() / 1000;
        a += Math.floor(d / f)
    }
    var b = new Random(a);
    var c = new Hash();
    this.forEach(function(h) {
        c.put(h, b.next())
    });
    this.sort(function(j, h) {
        return c.get(j) - c.get(h)
    });
    return this
};
if (!Array.prototype.sortByFreq) {
    Array.prototype.sortByFreq = function() {
        var b = {};
        this.forEach(function(a) {
            if (b[a]) {
                b[a]++
            } else {
                b[a] = 1
            }
        });
        var c = [];
        forEachKey(b, function(a) {
            c.push({
                k: a,
                v: b[a]
            })
        });
        return c.sort(function(f, d) {
            return f.v - d.v
        }).map(function(a) {
            return a.k
        })
    }
}
if (!Array.prototype.sortColumns) {
    Array.prototype.sortColumns = function(c) {
        var g = Math.ceil(this.length / c);
        var f = this.length % c;
        var a = [];
        var b = 0;
        for (var d = 0; d < c; d++) {
            for (var h = 0; h < g && b < this.length; h++) {
                a.push({
                    column: d,
                    row: h,
                    value: this[b++]
                })
            }
            if (--f === 0 && b < this.length) {
                g--
            }
        }
        return a.sort(function(k, j) {
            return (k.row - j.row) || (k.column - j.column)
        }).map(function(j) {
            return j.value
        })
    }
}
if (!Array.prototype.map) {
    Array.prototype.map = function(g, d) {
        var c = Event.wrapper(g, d);
        var a = [];
        for (var b = 0; b < this.length; ++b) {
            a.push(c(this[b], b, this))
        }
        return a
    }
}
if (!Array.max) {
    Array.max = function(a) {
        return Math.max.apply(Math, a)
    }
}
if (!Array.min) {
    Array.min = function(a) {
        return Math.min.apply(Math, a)
    }
}
if (!Array.prototype.forEachNonBlocking) {
    Array.prototype.forEachNonBlocking = function(b, f, c, h) {
        var d = 0;
        var g = this;
        var a = this.length;
        f = h ? Event.wrapper(f, h) : f;
        c = h ? Event.wrapper(c || noop, h) : (c || noop);
        loopNonBlocking(b, function() {
            if (d < a) {
                f(g[d++])
            } else {
                return true
            }
        }, c)
    }
}
if (!Array.prototype.filter) {
    Array.prototype.filter = function(h, g) {
        var d = Event.wrapper(h, g);
        var a = [];
        for (var b = 0; b < this.length; ++b) {
            var c = this[b];
            if (d(c)) {
                a.push(c)
            }
        }
        return a
    }
}
Array.prototype.uniq_by_key = function(b) {
    var a = {};
    return this.filter(function(d) {
        var c = d[b];
        if (c && a[c]) {
            return false
        }
        a[c] = true;
        return true
    })
};
if (!Array.prototype.uniq) {
    Array.prototype.uniq = function() {
        var a = [];
        var b = {};
        for (var c = 0; c < this.length; ++c) {
            var d = this[c];
            if (!Object.prototype.hasOwnProperty.call(b, d)) {
                b[d] = 1;
                a.push(d)
            }
        }
        return a
    }
}
if (!Array.prototype.forEach) {
    Array.prototype.forEach = function(g, d) {
        var c = Event.wrapper(g, d);
        var a = this.length;
        for (var b = 0; b < this.length; ++b) {
            if (a != this.length) {
                throw "Attempt to modify array in forEach"
            }
            c(this[b])
        }
    }
}
if (!Array.prototype.forEachReverse) {
    Array.prototype.forEachReverse = function(g, d) {
        var c = Event.wrapper(g, d);
        var a = this.length;
        for (var b = a - 1; b >= 0; --b) {
            if (a != this.length) {
                throw "Attempt to modify array in forEach"
            }
            if (c(this[b])) {
                break
            }
        }
    }
}
if (!Array.prototype.reduce) {
    Array.prototype.reduce = function(b) {
        var a = this.length;
        if (typeof(b) != "function") {
            throw new TypeError()
        }
        if (a === 0 && arguments.length == 1) {
            throw new TypeError()
        }
        var c = 0;
        var d;
        if (arguments.length >= 2) {
            d = arguments[1]
        } else {
            do {
                if (c in this) {
                    d = this[c++];
                    break
                }
                if (++c >= a) {
                    throw new TypeError()
                }
            } while (true)
        }
        for (; c < a; c++) {
            if (c in this) {
                d = b.call(null, d, this[c], c, this)
            }
        }
        return d
    }
}
Array.prototype.find = function(c, a) {
    if (typeof(c) == "function") {
        Beacon.log("typerror", {
            file: "builtin.js",
            "function": "find"
        }, true);
        throw new TypeError()
    }
    a = a || compare;
    var b;
    for (b = 0; b < this.length; b++) {
        if (a(c, this[b])) {
            return b
        }
    }
    return -1
};
if (!Array.prototype.contains) {
    Array.prototype.contains = function(b, a) {
        return this.find(b, a) >= 0
    }
}
if (!Array.prototype.some) {
    Array.prototype.some = function(b) {
        for (var a = 0; a < this.length; ++a) {
            if (b(this[a])) {
                return true
            }
        }
        return false
    }
}
if (!Array.prototype.remove) {
    Array.prototype.remove = function(c, a) {
        var b = this.find(c, a);
        if (b > -1) {
            return this.splice(b, 1)
        } else {
            return false
        }
    }
}
if (!Array.prototype.removeAll) {
    Array.prototype.removeAll = function(c, b) {
        var a = [];
        var d = false;
        do {
            d = this.remove(c, b);
            if (d !== false) {
                a.push(d)
            }
        } while (d);
        if (a.length > 0) {
            return a
        } else {
            return false
        }
    }
}
Array.prototype.swap = function(c, a) {
    var b = this[a];
    this[a] = this[c];
    this[c] = b
};
if (!Array.prototype.randomItem) {
    Array.prototype.randomItem = function() {
        var a = Math.random() * this.length;
        a = Math.floor(a);
        return this[a]
    }
}
if (!String.prototype.quote) {
    String.prototype.quote = function() {
        return JSON2.stringify(this + "")
    }
}
if (!String.prototype.ucFirst) {
    String.prototype.ucFirst = function() {
        if (this.length) {
            return this.charAt(0).toUpperCase() + this.substr(1)
        }
        return this
    }
}
if (!String.prototype.ucWords) {
    String.prototype.ucWords = function() {
        var a = this;
        if (this.length) {
            a = this.toLowerCase().replace(/\b[a-z]/g, function(b) {
                return b.toUpperCase()
            })
        }
        return a
    }
}
if (!Date.prototype.stdTimezoneOffset) {
    Date.prototype.stdTimezoneOffset = function() {
        var a = new Date(this.getFullYear(), 0, 1);
        var b = new Date(this.getFullYear(), 6, 1);
        return Math.max(a.getTimezoneOffset(), b.getTimezoneOffset())
    }
}
if (!Date.prototype.dst) {
    Date.prototype.dst = function() {
        return this.getTimezoneOffset() < this.stdTimezoneOffset()
    }
}
if (!Date.getDaysInMonth) {
    Date.getDaysInMonth = function(a, b) {
        return new Date(a, b + 1, 0).getDate()
    }
}
if (!String.prototype.trim) {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, "")
    }
}
if (!String.prototype.ltrim) {
    String.prototype.ltrim = function() {
        return this.replace(/^\s+/, "")
    }
}
if (!String.prototype.rtrim) {
    String.prototype.rtrim = function() {
        return this.replace(/\s+$/, "")
    }
}
String.prototype.patternCount = function(b) {
    if (!b) {
        return 0
    }
    if (typeof(b) == "object" && b.constructor == RegExp.prototype.constructor) {
        b = new RegExp(b.source, "g")
    }
    var a = 0;
    this.replace(b, function() {
        a++
    });
    return a
};
if ("abc".split(/(b)/).length < 3) {
    String.prototype._oldSplit = String.prototype.split;
    String.prototype.split = function(c, a) {
        if (typeof(c) == "object" && c.constructor == RegExp.prototype.constructor) {
            var b = c.source;
            if (b.patternCount("\\(") < b.patternCount("(")) {
                var d = "$";
                while (this.indexOf(d) > -1) {
                    d += "$"
                }
                c = new RegExp(b, "g");
                return this.replace(c, function(f, g) {
                    return d + g + d
                })._oldSplit(d, a)
            }
        }
        return this._oldSplit(c, a)
    }
}
var Conf = function() {
    var d = {
        dev: {
            cookieDomain: ".polyvore.net",
            oldImgHost: "www.polyvore.net",
            imgHosts: ["img1.polyvore.net", "img2.polyvore.net"],
            httpsImgHost: "www.polyvore.net",
            cdnImgHosts: {},
            blogUrl: "http://blog.polyvore.com",
            fbApiKey: "e006261993081197d9a617cbb0b6e7b6",
            isStaging: true,
            appEffectsCategories: [327, 329, 330, 331]
        },
        snapshot: {
            cookieDomain: ".polyvore.net",
            oldImgHost: "www.polyvore.com",
            imgHosts: ["img1.polyvoreimg.com", "img2.polyvoreimg.com"],
            httpsImgHost: "www.polyvore.com",
            cdnImgHosts: {
                akamai: ["ak1.polyvoreimg.com", "ak2.polyvoreimg.com"]
            },
            httpsCdnImgHost: "www.polyvore.com",
            rewriteImgBase: true,
            blogUrl: "http://blog.polyvore.com",
            fbApiKey: "e006261993081197d9a617cbb0b6e7b6",
            isStaging: true,
            appEffectsCategories: [327, 329, 330, 331]
        },
        testenv: (function() {
            var p = window._polyvoreDevName;
            var q = "testenv." + p + ".polyvore.net";
            return {
                cookieDomain: ".polyvore.net",
                oldImgHost: q,
                imgHosts: [q],
                httpsImgHost: q,
                cdnImgHosts: {},
                blogUrl: "http://blog.polyvore.com",
                fbApiKey: "e006261993081197d9a617cbb0b6e7b6",
                isStaging: true
            }
        })(),
        live: {
            cookieDomain: ".polyvore.net",
            oldImgHost: "www.polyvore.com",
            imgHosts: ["img1.polyvoreimg.com", "img2.polyvoreimg.com"],
            httpsImgHost: "www.polyvore.com",
            cdnImgHosts: {
                akamai: ["ak1.polyvoreimg.com", "ak2.polyvoreimg.com"]
            },
            httpsCdnImgHost: "www.polyvore.com",
            rewriteImgBase: true,
            blogUrl: "http://blog.polyvore.com",
            fbApiKey: "e006261993081197d9a617cbb0b6e7b6",
            isStaging: true,
            appEffectsCategories: [327, 329, 330, 331]
        },
        prod: {
            webHost: "www.polyvore.com",
            cookieDomain: ".polyvore.com",
            oldImgHost: "www.polyvore.com",
            imgHosts: ["img1.polyvoreimg.com", "img2.polyvoreimg.com"],
            httpsImgHost: "www.polyvore.com",
            cdnImgHosts: {
                akamai: ["ak1.polyvoreimg.com", "ak2.polyvoreimg.com"]
            },
            httpsCdnImgHost: "www.polyvore.com",
            rsrcUrlPrefix: {
                akamai: "http://akwww.polyvorecdn.com/rsrc/"
            },
            httpsRsrcUrlPrefix: "https://www.polyvore.com/rsrc/",
            rsrcExtUrlPrefix: "http://ext.polyvorecdn.com/rsrc/",
            noCachePrefix: "http://rsrc.polyvore.com/rsrc/",
            blogUrl: "http://blog.polyvore.com",
            fbApiKey: "3d1d18f72a710e20514cd62955686c8f",
            isStaging: false,
            appEffectsCategories: [327, 329, 330, 331]
        }
    };
    var o = window.polyvore_mode || window._polyvoreMode || "prod";
    var g = d[o];
    var h = null;
    if (window._polyvoreLocale && window._polyvoreLocale != "en") {
        h = window._polyvoreLocale
    }
    var b = "";
    try {
        var j = document.getElementsByTagName("script");
        for (var k = j.length - 1; k >= 0; k--) {
            var m = j[k];
            var a = m.src.toString();
            if (a && !/^(([a-z]+):\/\/)/.test(a)) {
                var c = document.createElement("div");
                a = a.replace('"', "%22");
                c.innerHTML = '<a href="' + a + '" style="display:none">x</a>';
                a = c.firstChild.href
            }
            if (a && /polyvore(cdn)?.(com|net|dev)/.test(a.match(/\/\/([^\/]*)/)[1])) {
                b = a.replace(/.*https?:\/\/[^\/]*\//, "/").replace(/(\/[^\/]*){2,2}$/, "");
                break
            }
        }
    } catch (l) {}
    var f;

    function n(s, v) {
        var u = [s];
        var q = [];
        forEachKey(v, function(w) {
            q.push(w)
        });
        q.sort();
        q.forEach(function(w) {
            u.push(v[w])
        });
        var t = 0;
        u = u.join("");
        for (var r = 0, p = u.length; r < p; r++) {
            t += u.charCodeAt(r)
        }
        return t
    }
    return {
        getDevName: function() {
            return window._polyvoreDevName
        },
        getFbApiKey: function() {
            return g.fbApiKey
        },
        getCookieDomain: function() {
            return g.cookieDomain
        },
        getWebHost: function() {
            return g.webHost || window._polyvoreHost || "www.polyvore.net"
        },
        getWebUrlPrefix: function() {
            return Conf.getWebHost() + b
        },
        getImgHost: function(p, r) {
            if (getProtocol() == "https") {
                return g.httpsImgHost
            }
            if (!r) {
                r = {}
            }
            if (r.size == "x" || r.size == "l" || r.size == "e") {
                return g.oldImgHost
            } else {
                var q = n(p, r);
                return g.imgHosts[q % g.imgHosts.length]
            }
        },
        getCDNImgHost: function(p, r, t) {
            if (getProtocol() == "https") {
                return g.httpsCdnImgHost
            }
            var q = g.cdnImgHosts[p];
            if (!q) {
                return ""
            }
            var s = n(r, t);
            return q[s % q.length]
        },
        getRsrcUrlPrefix: function(p, q) {
            if (f) {
                return f
            }
            if (getProtocol() == "https") {
                f = g.httpsRsrcUrlPrefix;
                return g.httpsRsrcUrlPrefix
            }
            if (q) {
                f = g.rsrcExtUrlPrefix
            } else {
                if (g.rsrcUrlPrefix) {
                    f = g.rsrcUrlPrefix[p]
                }
            } if (!f) {
                f = "http://" + Conf.getWebUrlPrefix() + "/rsrc/"
            }
            return f
        },
        getNoCachePrefix: function() {
            return g.noCachePrefix ? g.noCachePrefix : "http://" + Conf.getWebUrlPrefix() + "/rsrc/"
        },
        getBlogURL: function() {
            return g.blogUrl
        },
        isStaging: function() {
            return g.isStaging
        },
        setLocale: function(p) {
            h = p
        },
        getLocale: function() {
            return h
        },
        getModeName: function() {
            return o
        },
        getSetting: function(p) {
            return g[p]
        }
    }
}();

function noop() {}

function round(b, a) {
    a = a || 1;
    if (a > 1) {
        return Math.round(b / a) * a
    } else {
        a = 1 / a;
        return Math.round(b * a) / a
    }
}

function flatten(b, a) {
    a = a || [];
    if (b !== undefined && b !== null) {
        if (b.constructor == Array) {
            b.map(function(c) {
                flatten(c, a)
            })
        } else {
            a.push(b)
        }
    }
    return a
}

function forEachKey(c, d, b) {
    for (var a in c) {
        if (c.hasOwnProperty(a)) {
            if (d.call(b, a, c[a])) {
                break
            }
        }
    }
}

function yield(a, b) {
    return window.setTimeout(Event.wrapper(a, b), 0)
}

function tryThese() {
    var f;
    for (var d = 0; d < arguments.length; d++) {
        var c = arguments[d];
        try {
            f = c();
            break
        } catch (g) {
            var b = 1
        }
    }
    return f
}

function plural(b, a, c, d) {
    b = Number(b);
    if (d && !b) {
        return d
    }
    if (a == "time") {
        switch (b) {
            case 1:
                return loc("once");
            case 2:
                return loc("twice");
            case 3:
                return loc("three") + " " + c;
            default:
                return b + " " + c
        }
    } else {
        switch (b) {
            case 1:
                return loc("one") + " " + a;
            case 2:
                return loc("two") + " " + c;
            case 3:
                return loc("three") + " " + c;
            default:
                return b + " " + c
        }
    }
}

function shortNumber(a) {
    if (a == 1) {
        return loc("one")
    } else {
        if (a == 2) {
            return loc("two")
        } else {
            if (a == 3) {
                return loc("three")
            }
        }
    }
    return a.toString()
}

function nodeListToArray(c) {
    var d = [];
    var a = c.length;
    d.length = a;
    for (var b = 0; b < a; b++) {
        d[b] = c[b]
    }
    return d
}

function toArray(a) {
    if (a) {
        if (a.constructor == Array) {
            return a
        } else {
            if (a.constructor != String && a.nodeType === undefined && a.length !== undefined && !isNaN(Number(a.length))) {
                return nodeListToArray(a)
            } else {
                return [a]
            }
        }
    } else {
        return []
    }
}

function mergeObject(d, a, b) {
    for (var c in a) {
        if (a.hasOwnProperty(c) && (!b || !d.hasOwnProperty(c))) {
            d[c] = a[c]
        }
    }
    return d
}

function delayed(b, c) {
    var a;
    return function() {
        if (a) {
            clearTimeout(a)
        }
        var d = arguments;
        a = window.setTimeout(function() {
            b.apply(b, d)
        }, c)
    }
}

function splitWithMatches(h, j, c, b) {
    var f = [];
    var g = j.match(h) || [];
    var a = 0;
    var k = 0;
    while (a < g.length) {
        k = j.search(h);
        if (k < 0) {
            break
        }
        var l = j.substring(0, k);
        var d = g[a++];
        j = j.substring(k + d.length);
        if (l) {
            f.push(b ? b(l) : l)
        }
        if (d) {
            f.push(c ? c(d) : d)
        }
    }
    if (j) {
        f.push(b ? b(j) : j)
    }
    return f
}

function extend(b, c) {
    var a = function() {};
    a.prototype = c.prototype;
    b.prototype = new a();
    b.prototype.constructor = b;
    b.superclass = c.prototype;
    if (c.prototype.constructor == Object.prototype.constructor) {
        c.prototype.constructor = c
    }
}

function loopNonBlocking(a, f, c, d) {
    if (!f) {
        return
    }
    var b = function() {
        var j = true;
        var h = f;
        var g = new Date().getTime() + a;
        while (!h.apply(d)) {
            if (new Date().getTime() >= g) {
                window.setTimeout(b, Browser.isIE ? 80 : 0);
                j = false;
                break
            }
        }
        if (j && c) {
            c.apply(d)
        }
    };
    window.setTimeout(b, 0)
}

function countingSemaphore(a, f, c) {
    var d = Event.wrapper(f, c);
    var b = Event.wrapper(function() {
        if (--a === 0) {
            d()
        }
    });
    b.inc = function(g) {
        a += g || 1
    };
    b.clean = function() {
        d = noop
    };
    return b
}

function post(b, c) {
    if (!c) {
        c = {}
    }
    if (window._xsrfToken) {
        c[".xsrf"] = window._xsrfToken
    }
    var a = createNode("form");
    a.action = buildAbsURL(buildURL(b));
    a.method = "POST";
    a.appendChild(createNode("input", {
        type: "hidden",
        name: "request",
        value: JSON2.stringify(c)
    }));
    a.appendChild(createNode("input", {
        type: "hidden",
        name: ".in",
        value: "json"
    }));
    document.body.appendChild(a);
    a.submit()
}

function cloneObject(b, a) {
    if (!b) {
        return b
    }
    var c = new b.constructor();
    forEachKey(b, function(d, f) {
        if (a && typeof(f) == "object") {
            f = cloneObject(f, a)
        }
        c[d] = f
    });
    return c
}

function bucketName(a) {
    var b = window.polyvore_experiment_data && window.polyvore_experiment_data[a];
    b = b || {};
    return b.name || ""
}

function bucketIs(b, a) {
    return bucketName(b).toLowerCase() == a.toLowerCase()
}

function openWindow(b, c, n, j, m, l) {
    var f = n || 800;
    var o = j || 600;
    var d = screen.height;
    var a = screen.width;
    var g = m || Math.round((a / 2) - (f / 2));
    var k = l || Math.round((d / 2) - (o / 2));
    var p = "";
    p = "left=" + g + ",top=" + k + ",width=" + f + ",height=" + o;
    p += ",personalbar=0,toolbar=0,scrollbars=1,resizable=1,location=0,menubar=0";
    return window.open(c, "pv_" + b, p)
}

function returnFalse() {
    return false
}
try {
    document.execCommand("BackgroundImageCache", false, true)
} catch (e) {}

function cache_buster() {
    return 1
}
if (!window.console) {
    window.console = {
        log: noop,
        debug: noop,
        error: noop
    }
}
var JS_VOID = "javascript:void(0)";

function isValidEmail(a) {
    return a && a.match(/^.+@[\w\-]+(\.[\w\-]+)*\.[A-Za-z]{2,10}/)
}

function toList(a) {
    if (a !== undefined && a !== null) {
        return (a.constructor == Array) ? a : [a]
    } else {
        return []
    }
}

function teaser(c, b, a) {
    if (!c || c.length <= b) {
        return c
    }
    if (a === undefined) {
        a = "..."
    }
    if (b < 4) {
        b = 4
    }
    return c.substring(0, b - 3) + a
}

function pluralNumber(b, a, c) {
    b = Number(b);
    if (b === 1) {
        return b + " " + a
    }
    return b + " " + c
}

function Boolean(a) {
    a = Number(a);
    return !!a
}

function handleException(a) {
    ModalDialog.alert(a.message)
}


function hiddenPost(a) {
    a.method = "POST";
    a.target = "polyvore_hidden_iframe";
    a.appendChild(createNode("input", {
        type: "hidden",
        name: ".out",
        value: "json"
    }));
    a.submit()
}

function parseUnit(a) {
    if (a && a.match(/^([0-9]+)([a-z%]+)$/)) {
        return {
            value: parseInt(RegExp.$1, 10),
            unit: RegExp.$2
        }
    }
    return {
        value: parseInt(a, 10),
        unit: ""
    }
}

function normalizeCSV(c) {
    if (c) {
        var b = c.split(",");
        var a = [];
        b.forEach(function(d) {
            d = d.trim();
            if (d) {
                a.push(d)
            }
        });
        c = a.join(",")
    }
    return c
}

function mantissa(a) {
    return parseFloat((a < 0 ? "-0." : "0.") + (("" + a).split(".")[1] || 0))
}

function reloadPage() {
    window.location.reload(true)
}

function _timeUnits() {
    return [{
        singular: loc("year"),
        plural: loc("years"),
        seconds: 31536000
    }, {
        singular: loc("month"),
        plural: loc("months"),
        seconds: 2592000
    }, {
        singular: loc("day"),
        plural: loc("days"),
        seconds: 86400
    }, {
        singular: loc("hour"),
        plural: loc("hours"),
        seconds: 3600
    }, {
        singular: loc("min"),
        plural: loc("minutes"),
        seconds: 60
    }]
}

function duration(d) {
    var a = _timeUnits();
    for (var b = 0; b < a.length; ++b) {
        var c = a[b];
        if (d > c.seconds) {
            return plural(Math.round(d / c.seconds), c.singular, c.plural)
        }
    }
    return plural(Math.round(d), loc("second"), loc("seconds"))
}

function ts2age(a) {
    if (!a) {
        return 0
    }
    return new Date().getTime() / 1000 - a
}

function zSort(d, c) {
    return d.z - c.z
}

function mapRange(k, j, g) {
    var d = j.length;
    for (var c = 0; c < d; ++c) {
        var b = j[c];
        var a = j[c + 1];
        if (k >= b && k <= a) {
            var h = g[c];
            var f = g[c + 1];
            return (k - b) * (f - h) / (a - b) + h
        }
    }
    return
}

function range(c, a, f) {
    if (f > a) {
        return []
    }
    var d = [];
    for (var b = a; b <= f && b < c.length; b++) {
        d.push(c[b])
    }
    return d
}

function andWords(b) {
    var a = b[b.length - 1];
    var c = range(b, 0, b.length - 2).join(", ");
    return c ? (c + " " + loc("and") + " " + a) : a
}

function orWords(b) {
    var a = b[b.length - 1];
    var c = range(b, 0, b.length - 2).join(", ");
    return c ? (c + " " + loc("or") + " " + a) : a
}

function makeStatic(b) {
    for (var a in b) {
        func = b[a];
        if (typeof(func) == "function") {
            b[a] = Event.wrapper(func, b)
        }
    }
}

function consensusOrMediod(f, c) {
    var d = {};
    var a = null;
    var b = 1;
    c.forEach(function(g) {
        var h = Math.round(g / f) * f;
        if (d[h]) {
            d[h]++;
            if (d[h] > b) {
                b = d[h];
                a = g
            }
        } else {
            d[h] = 1
        }
    });
    if (b > 1) {
        return a
    }
    c = c.sort().uniq();
    return c[Math.round(c.length / 2)]
}

function stack() {
    var a;
    try {
        window()
    } catch (b) {
        a = b.stack
    }
    if (a) {
        a = a.split("\n");
        a.pop();
        return a.join("\n")
    }
    return ""
}

function checkMainImg(b) {
    if (!document.querySelector) {
        return
    }
    var a = document.querySelector(b);
    if (!a) {
        return
    }
    var c = a.src;
    if (!c) {
        return
    }
    getNaturalWidthHeight(c, function(d, f) {
        if (d > 1 && f > 1) {
            return
        }
        Beacon.log("1x1", {
            url: c
        })
    })
}

function future(h) {
    if (h <= 0) {
        return loc("right away")
    }
    var a = _timeUnits();
    for (var c = 0; c < a.length; c++) {
        var f = a[c];
        if (h >= f.seconds) {
            var g = Math.round(h / f.seconds);
            if (g === 1 && f.singular === loc("day")) {
                return loc("tomorrow")
            }
            return loc("in {duration}", {
                duration: plural(g, f.singular, f.plural)
            })
        }
    }
    var b = plural(h, loc("second"), loc("seconds"));
    return loc("in {duration}", {
        duration: b
    })
}

function handleScrollPositionPassbacks(b) {
    Event.addListener(document, "domready", function() {
        var f = function(g) {
            this.href = this.href + "#scroll_position=" + scrollXY().y
        };
        var c = getElementsByClassName({
            className: "pass_scroll_position",
            root: $(b)
        });
        for (var d = 0; d < c.length; d++) {
            Event.addListener(c[d], "click", f, c[d])
        }
    });
    var a = getHashValue("scroll_position") || 0;
    if (a) {
        window.scroll(0, a)
    }
}

function getHashValue(a) {
    var b = window.location.hash.match(new RegExp(a + "=([^&]*)"));
    if (b && b.length > 1) {
        return b[1]
    }
    return null
}

function formatPrice(d, a, f) {
    a = a || "$";
    f = f || ",";
    d = parseFloat(d).toString();
    if (d && d > 0) {
        var c = d.indexOf(".");
        if (c >= 0) {
            d = parseFloat(d).toFixed(2)
        } else {
            if (c < 0) {
                c = d.length
            }
        }
        var b = c % 3;
        while (b < c) {
            if (b) {
                d = d.substring(0, b) + f + d.substring(b);
                b += 1
            }
            b += 3
        }
        return a + d
    }
}
var BrowserDetect = {
    init: function() {
        this.browserInfo = this.searchInfo(this.dataBrowser) || null;
        this.browser = this.browserInfo ? this.browserInfo.identity : "An unknown browser";
        this.version = document.documentMode || this.searchVersion(navigator.userAgent, this.browserInfo) || this.searchVersion(navigator.appVersion, this.browserInfo) || "an unknown version";
        this.OSInfo = this.searchInfo(this.dataOS) || null;
        this.OS = this.OSInfo ? this.OSInfo.identity : "an unknown OS";
        this.layoutEngineInfo = this.searchInfo(this.dataLayoutEngine) || null;
        this.layoutEngine = this.layoutEngineInfo ? this.layoutEngineInfo.identity : "an unknown layout engine";
        this.layoutEngineVersion = this.searchVersion(navigator.userAgent, this.layoutEngineInfo) || this.searchVersion(navigator.appVersion) || "an unknown layout engine version"
    },
    searchInfo: function(d) {
        for (var a = 0; a < d.length; a++) {
            var b = d[a].string;
            var c = d[a].prop;
            if (b) {
                if (b.indexOf(d[a].subString) != -1) {
                    return d[a]
                }
            } else {
                if (c) {
                    return d[a]
                }
            }
        }
        return false
    },
    searchVersion: function(d, c) {
        var a = c ? c.versionSearch || c.identity : "";
        var b = d.indexOf(a);
        if (b == -1) {
            return false
        }
        return parseFloat(d.substring(b + a.length + 1))
    },
    dataBrowser: [{
        string: navigator.userAgent,
        subString: "Polyvore",
        identity: "Polyvore"
    }, {
        string: navigator.userAgent,
        subString: "MSIE",
        identity: "IE",
        versionSearch: "MSIE",
        upgradeURL: "http://www.microsoft.com/windows/Internet-explorer/default.aspx"
    }, {
        string: navigator.userAgent,
        subString: "Trident",
        identity: "IE",
        versionSearch: "rv",
        upgradeURL: "http://www.microsoft.com/windows/Internet-explorer/default.aspx"
    }, {
        string: navigator.userAgent,
        subString: "Firefox",
        identity: "Firefox",
        upgradeURL: "http://www.getfirefox.com"
    }, {
        string: navigator.vendor,
        subString: "Apple",
        identity: "Safari",
        upgradeURL: "http://www.apple.com/safari/download/"
    }, {
        string: navigator.userAgent,
        subString: "Chrome",
        identity: "Chrome",
        upgradeURL: "http://www.google.com/chrome"
    }, {
        prop: window.opera,
        identity: "Opera"
    }, {
        string: navigator.userAgent,
        subString: "Netscape",
        identity: "Netscape"
    }, {
        string: navigator.userAgent,
        subString: "Gecko",
        identity: "Mozilla",
        versionSearch: "rv"
    }, {
        string: navigator.userAgent,
        subString: "Mozilla",
        identity: "Netscape",
        versionSearch: "Mozilla"
    }, {
        string: navigator.vendor,
        subString: "Camino",
        identity: "Camino"
    }, {
        string: navigator.userAgent,
        subString: "OmniWeb",
        versionSearch: "OmniWeb/",
        identity: "OmniWeb"
    }, {
        string: navigator.vendor,
        subString: "iCab",
        identity: "iCab"
    }, {
        string: navigator.vendor,
        subString: "KDE",
        identity: "Konqueror"
    }],
    dataOS: [{
        string: navigator.platform,
        subString: "Win",
        identity: "Windows"
    }, {
        string: navigator.platform,
        subString: "iPad",
        identity: "iPad"
    }, {
        string: navigator.platform,
        subString: "Mac",
        identity: "Mac"
    }, {
        string: navigator.platform,
        subString: "Linux",
        identity: "Linux"
    }, {
        string: navigator.platform,
        subString: "iPhone",
        identity: "iPhone"
    }, {
        string: navigator.platform,
        subString: "iPod",
        identity: "iPod"
    }],
    dataLayoutEngine: [{
        string: navigator.userAgent,
        subString: "AppleWebKit",
        identity: "WebKit"
    }, {
        string: navigator.userAgent,
        subString: "Gecko",
        identity: "Gecko",
        versionSearch: "rv"
    }, {
        string: navigator.userAgent,
        subString: "Presto",
        identity: "Presto"
    }]
};
BrowserDetect.init();
var Browser = function() {
    var d = false;
    if (BrowserDetect.OS === "Linux") {
        var c = BrowserDetect.browserInfo.string;
        var b = c.match(/Android ([\d]+)[.\d]*;/) || [];
        var a = parseInt(b[1], 10);
        if (b.length && a >= 4) {
            d = true
        }
    }
    return {
        isIE: "IE" == BrowserDetect.browser,
        isSafari: "Safari" == BrowserDetect.browser,
        isChrome: "Chrome" == BrowserDetect.browser,
        isOpera: "Opera" == BrowserDetect.browser,
        isMac: "Mac" == BrowserDetect.OS,
        isIPad: "iPad" == BrowserDetect.OS,
        isIPhone: "iPhone" == BrowserDetect.OS,
        isIPod: "iPod" == BrowserDetect.OS,
        isIPhoneOrIPod: "iPhone" == BrowserDetect.OS || "iPod" == BrowserDetect.OS,
        isAndroid4: d,
        isWindows: "Windows" == BrowserDetect.OS,
        isFirefox: "Firefox" == BrowserDetect.browser,
        isMozilla: "Mozilla" == BrowserDetect.browser,
        isPolyvoreNativeApp: "Polyvore" == BrowserDetect.browser,
        type: function(g, f, h) {
            return g == BrowserDetect.browser && (!f || f <= BrowserDetect.version) && (!h || h >= BrowserDetect.version)
        },
        layoutEngine: function(g, f, h) {
            return g == BrowserDetect.layoutEngine && (!f || f <= BrowserDetect.layoutEngineVersion) && (!h || h >= BrowserDetect.layoutEngineVersion)
        }
    }
}();

function Set() {
    this.items = {};
    this._size = 0
}
Set.prototype.size = function() {
    return this._size
};
Set.prototype.forEach = function(b, a) {
    this.values().forEach(b, a)
};
Set.prototype.ncp = function(a) {
    if (this.contains(a)) {
        return false
    }
    this.put(a);
    return true
};
Set.prototype.contains = function(b) {
    var a = getHashKey(b);
    if (this.items[":" + a]) {
        return true
    } else {
        return false
    }
};
Set.prototype.get = function(a) {
    if (this.contains(a)) {
        return this.items[":" + getHashKey(a)]
    } else {
        return null
    }
};
Set.prototype.put = function(b) {
    var a = getHashKey(b);
    if (!this.items[":" + a]) {
        this.items[":" + a] = b;
        delete this._values;
        this._size++
    }
};
Set.prototype.remove = function(b) {
    var a = getHashKey(b);
    if (this.items[":" + a]) {
        delete this.items[":" + a];
        delete this._values;
        this._size--;
        return true
    }
    return false
};
Set.prototype.clear = function() {
    this.items = {};
    delete this._values;
    this._size = 0
};
Set.prototype.values = function() {
    if (this._values) {
        return this._values
    }
    var a = [];
    for (var b in this.items) {
        if (this.items.hasOwnProperty(b)) {
            a.push(this.items[b])
        }
    }
    return (this._values = a)
};
var getUID;
(function() {
    var a = 1;
    getUID = function(d) {
        var c = typeof(d);
        var b;
        if (c == "object" || c == "function") {
            b = d._uid;
            if (!b) {
                b = a++;
                try {
                    d._uid = b
                } catch (f) {}
            }
        } else {
            b = d
        }
        return b
    }
})();

function getHashKey(b) {
    switch (typeof(b)) {
        case "number":
        case "string":
            return b;
        case "boolean":
            return b ? 1 : 0;
        case "object":
            if (!b) {
                return b
            }
            var a;
            if (b.getHashKey && typeof(b.getHashKey) == "function") {
                return b.getHashKey()
            } else {
                a = getUID(b)
            }
            return a;
        default:
            return b
    }
}

function compare(f, d) {
    var c = typeof(f);
    var g = typeof(d);
    if (c != g) {
        return false
    }
    switch (c) {
        case "number":
        case "string":
        case "boolean":
            return f === d;
        default:
            return getHashKey(f) === getHashKey(d)
    }
}

function Hash() {
    this.items = {}
}
Hash._key = function(a) {
    return ":" + getHashKey(a)
};
Hash.prototype.merge = function(b) {
    for (var a in b) {
        if (Object.prototype.hasOwnProperty.call(b, a)) {
            this.put(a, b[a])
        }
    }
};
Hash.prototype.put = function(a, b) {
    var c;
    if (this.contains(a)) {
        c = this.get(a)
    }
    this.items[Hash._key(a)] = b;
    return c
};
Hash.prototype.get = function(a) {
    return this.items[Hash._key(a)]
};
Hash.prototype.remove = function(a) {
    a = Hash._key(a);
    if (this.items[a]) {
        delete this.items[a]
    }
};
Hash.prototype.clear = function() {
    this.items = {}
};
Hash.prototype.contains = function(a) {
    return this.items.hasOwnProperty([Hash._key(a)])
};

function Interval(a, c, b) {
    this.timerId = 0;
    this.interval = a;
    this.f = Event.wrapper(function() {
        try {
            c.apply(b)
        } catch (d) {
            console.log(d)
        }
        if (this.timerId !== undefined) {
            this.reschedule()
        }
    }, this);
    this.reschedule()
}
Interval.prototype.clear = function() {
    if (this.timerId) {
        window.clearTimeout(this.timerId);
        delete this.timerId
    }
};
Interval.prototype.reschedule = function(a) {
    this.clear();
    this.interval = a || this.interval;
    this.timerId = window.setTimeout(this.f, this.interval)
};

function Cleaner() {
    var a = [];
    return {
        push: function(b) {
            if (b) {
                a.push(b)
            }
        },
        clean: function() {
            a.forEach(function(b) {
                if (b.clean && typeof(b.clean) == "function") {
                    b.clean()
                } else {
                    if (typeof(b) == "function") {
                        b.call()
                    }
                }
            });
            a = []
        }
    }
}

function EventMap() {
    this.events = {}
}
EventMap.prototype.getOrInitEventObjects = function(c, a) {
    var b = getUID(c);
    if (c && c.getAttribute && !c.getAttribute("_uid")) {
        c.setAttribute("_uid", b)
    }
    if (!this.events[b]) {
        this.events[b] = {}
    }
    if (!this.events[b][a]) {
        this.events[b][a] = {}
    }
    if (!this.events[b][a].listeners) {
        this.events[b][a].listeners = []
    }
    return this.events[b][a]
};
EventMap.prototype.getListenedEvent = function(c, a) {
    var b = getUID(c);
    return this.events[b] && this.events[b][a]
};
EventMap.prototype.getPVListeners = function(c, a) {
    var b = getUID(c);
    return this.getListenedEvent(c, a) && this.events[b][a].listeners
};
EventMap.prototype.release = function(b) {
    var a = getUID(b);
    return this.releaseId(a, b)
};
EventMap.prototype.releaseAll = function(a) {
    if (!document.querySelectorAll) {
        console.log("ReleaseAll not supported by this browser");
        return
    }
    var b = document.querySelectorAll("[_uid]");
    b = nodeListToArray(b);
    b.push(window);
    b.push(document);
    b.forEach(function(d) {
        var c = d._uid;
        if (c) {
            this.releaseId(c, d);
            if (a) {
                a(c, d)
            }
        }
    }, this);
    this.getSourceIDs().forEach(function(c) {
        this.releaseId(c)
    }, this)
};
EventMap.prototype.releaseId = function(f, d) {
    if (!this.events[f]) {
        return
    }
    for (var c in this.events[f]) {
        if (!this.events[f].hasOwnProperty(c)) {
            continue
        }
        var g = this.events[f][c].scrollBottomDetector;
        if (g) {
            g.clear()
        }
        var b = this.events[f][c].listeners;
        if (b.constructor != Array) {
            continue
        }
        if (d) {
            for (var a = 0; a < b.length; ++a) {
                if (Event.isBuiltIn(d, c)) {
                    Event.removeDomListener(d, c, b[a])
                }
            }
        }
        delete this.events[f][c]
    }
    delete this.events[f]
};
EventMap.prototype.getSourceIDs = function() {
    var a = [];
    forEachKey(this.events, function(b) {
        a.push(b)
    });
    return a
};

function Listener(c, b, a) {
    this.src = c;
    this.event = b;
    this.handler = a
}
Listener.prototype.clean = function() {
    var a = this.event;
    var b = this.src;
    if (this.src && this.event) {
        Event.removeListener(b, a, this.handler)
    }
    this.src = this.event = this.handler = null
};
var Event = function() {
    var WRAPPERS = {};
    var BUILTINS = {
        filterchange: true,
        abort: true,
        blur: true,
        change: true,
        click: true,
        contextmenu: true,
        dblclick: true,
        error: true,
        focus: true,
        keydown: true,
        keypress: true,
        transitionend: true,
        webkitTransitionEnd: true,
        webkitAnimationEnd: true,
        keyup: true,
        load: true,
        message: true,
        mousedown: true,
        mousemove: true,
        mouseover: true,
        mouseout: true,
        mouseup: true,
        reset: true,
        resize: true,
        scroll: true,
        select: true,
        selectstart: true,
        submit: true,
        unload: true,
        beforeunload: true,
        copy: true,
        DOMMouseScroll: true,
        mousewheel: true,
        DOMContentLoaded: true,
        touchstart: true,
        touchmove: true,
        touchend: true,
        touchcancel: true,
        pageshow: true,
        pagehide: true,
        popstate: true,
        orientationchange: true,
        paste: true,
        input: true
    };
    var eventMap = new EventMap();
    var bubbleMap = {};
    var messageListener;
    var lastMsgTimeStamp = 0;
    var startHistoryLength = window.history.length - 1;
    var baseTime = new Date().getTime();
    var fireOnceHash = new Hash();
    return {
        getPageXY: function(event, tmp) {
            var x = event.pageX;
            tmp = tmp ? tmp : new Point(0, 0);
            if (!x && 0 !== x) {
                x = event.clientX || 0
            }
            var y = event.pageY;
            if (!y && 0 !== y) {
                y = event.clientY || 0
            }
            if (Browser.isIE) {
                var scroll = scrollXY();
                tmp.x = x + scroll.x;
                tmp.y = y + scroll.y
            } else {
                tmp.x = x;
                tmp.y = y
            }
            return tmp
        },
        getChar: function(event) {
            if (!event) {
                return ""
            }
            return String.fromCharCode(event.charCode || event.keyCode)
        },
        addDomListener: function(source, event, wrapper) {
            if (event == "dblclick" && Browser.isSafari) {
                source.ondblclick = wrapper
            } else {
                if (source.addEventListener) {
                    source.addEventListener(event, wrapper, false)
                } else {
                    if (source.attachEvent) {
                        source.attachEvent("on" + event, wrapper)
                    } else {
                        source["on" + event] = wrapper
                    }
                }
            }
        },
        removeDomListener: function(source, event, wrapper) {
            if (source.removeEventListener) {
                source.removeEventListener(event, wrapper, false)
            } else {
                if (source.detachEvent) {
                    source.detachEvent("on" + event, wrapper)
                } else {
                    source["on" + event] = null
                }
            }
        },
        postMessage: function(tgt, base, event, message) {
            if (!tgt) {
                tgt = window.parent
            }
            if (!tgt) {
                return
            }
            try {
                if (tgt.contentWindow) {
                    tgt = tgt.contentWindow
                }
            } catch (el) {}
            tgt.postMessage(JSON2.stringify({
                event: event,
                message: message
            }), base)
        },
        addListener: function(source, event, listener, object) {
            if (!source || !event) {
                var jslint = window._Debug && window._Debug.logStackTrace();
                console.log("ERROR: addListener called on invalid source or event:", source, event);
                return
            }
            if (Browser.layoutEngine("WebKit") && event == "transitionend") {
                event = "webkitTransitionEnd"
            }
            var wrapper;
            var fireOnce = Event.FIREONCE.get(source, event);
            if (fireOnce !== undefined) {
                if (fireOnce) {
                    window.setTimeout(function() {
                        listener.apply(object)
                    });
                    return null
                } else {
                    wrapper = function() {
                        Event.removeListener(source, event, wrapper);
                        listener.apply(object)
                    }
                }
            }
            if (event == "scrollbottom") {
                var eventObject = eventMap.getOrInitEventObjects(source, event);
                if (!eventObject.scrollBottomDetector) {
                    eventObject.scrollBottomDetector = new ScrollBottomDetector(source)
                }
                if (!eventObject.scrollBottomDetector.isAttached()) {
                    eventObject.scrollBottomDetector.attach(source)
                }
                if (eventObject.scrollBottomDetector.isAtBottom()) {
                    yield(listener, object)
                }
            } else {
                if (event == "mousewheel" && Browser.isFirefox) {
                    event = "DOMMouseScroll"
                }
            } if (source.tagName == "INPUT" && source.type) {
                var inputType = source.type.toUpperCase();
                if ((inputType == "CHECKBOX" || inputType == "RADIO") && event == "change" && Browser.isIE) {
                    event = "click";
                    wrapper = function() {
                        window.setTimeout(function() {
                            listener.apply(object)
                        }, 0)
                    }
                }
            }
            if (!wrapper) {
                wrapper = Event.wrapper(listener, object)
            }
            if (/mousepause([0-9]*)$/.test(event)) {
                var timer = new Timer();
                var delay = Number(RegExp.$1);
                if (isNaN(delay) || (!delay && delay !== 0)) {
                    delay = 500
                }
                Event.addListener(source, "mousemove", function(e) {
                    timer.replace(wrapper, delay)
                });
                Event.addListener(source, "mouseout", timer.reset, timer)
            }
            switch (event) {
                case "dragstart":
                    Event.addListener(source, "mousedown", DragDrop.onMouseDown, DragDrop);
                    Event.addDomListener(source, "dragstart", Event.stop);
                    break;
                case "drop":
                    DragDrop.addDropListener(source);
                    break;
                default:
                    if (source == Event.XFRAME) {
                        if (!messageListener) {
                            messageListener = Event.addListener(window, "message", function(event) {
                                try {
                                    var data = eval("(" + event.data + ")");
                                    if (data.event) {
                                        Event.trigger(Event.XFRAME, data.event, data.message)
                                    }
                                } catch (e) {}
                            })
                        }
                    } else {
                        if (source == Event.BACKEND) {
                            if (!Event.BACKEND.listening) {
                                Event.addListener(Cookie, "change", Event.checkForBackendEvent);
                                Event.BACKEND.listening = true
                            }
                        } else {
                            if (Event.isBuiltIn(source, event)) {
                                Event.addDomListener(source, event, wrapper)
                            }
                        }
                    }
            }
            var listeners = eventMap.getOrInitEventObjects(source, event).listeners;
            listeners.push(wrapper);
            return new Listener(source, event, wrapper)
        },
        addSingleUseListener: function(source, event, listener, object) {
            var listenerRemover = function() {
                var tmp = listener;
                listener = null;
                Event.removeListener(source, event, listenerRemover);
                if (tmp) {
                    tmp.apply(this, arguments)
                }
            };
            return Event.addListener(source, event, listenerRemover, object)
        },
        removeListener: window._Debug ? function(source, event, method, object) {
            var cacheKey = getUID(method) + ":" + getUID(object);
            var wrappers = WRAPPERS[cacheKey] || [];
            wrappers.forEach(function(wrapper) {
                Event._removeListener(source, event, wrapper)
            })
        } : function(source, event, method, object) {
            var wrapper = Event.wrapper(method, object);
            Event._removeListener(source, event, wrapper)
        },
        _removeListener: function(source, event, wrapper) {
            if (event == "drop") {
                DragDrop.removeDropListener(source)
            } else {
                if (source == Event.XFRAME) {} else {
                    if (Event.isBuiltIn(source, event)) {
                        Event.removeDomListener(source, event, wrapper)
                    }
                }
            }
            var listeners = eventMap.getPVListeners(source, event) || [];
            for (var i = 0; i < listeners.length; ++i) {
                if (listeners[i] == wrapper) {
                    listeners.splice(i, 1);
                    return
                }
            }
        },
        addCustomBubble: function(child, parent) {
            if (child == parent) {
                console.log("parent is the same as child");
                return
            }
            var childId = getUID(child);
            if (!bubbleMap[childId]) {
                bubbleMap[childId] = []
            }
            bubbleMap[childId].push(parent)
        },
        bubble: function() {
            var source = arguments[0];
            var event = arguments[1];
            var evt = arguments[2] || {};
            while (source) {
                var listeners;
                if ((listeners = eventMap.getPVListeners(source, event)) && listeners.length) {
                    arguments[0] = source;
                    Event.trigger.apply(Event, arguments);
                    if (evt.cancelBubble) {
                        break
                    }
                }
                source = source.parentNode
            }
        },
        trigger: function() {
            var source = arguments[0];
            var event = arguments[1];
            var i;
            var listenedEvent = eventMap.getListenedEvent(source, event);
            if (listenedEvent) {
                var fireOnce = Event.FIREONCE.get(source, event);
                if (fireOnce !== undefined) {
                    if (fireOnce) {
                        return
                    }
                }
                var args = Array.prototype.slice.apply(arguments, [2]);
                if (listenedEvent.shouldBundle) {
                    listenedEvent.triggered = args;
                    return
                }
                var listeners = listenedEvent.listeners.slice(0);
                var listener;
                var errs = [];
                if (Conf.isStaging()) {
                    for (i = 0; i < listeners.length; ++i) {
                        listener = listeners[i];
                        listener.apply(listener, args)
                    }
                } else {
                    for (i = 0; i < listeners.length; ++i) {
                        listener = listeners[i];
                        try {
                            listener.apply(listener, args)
                        } catch (e) {
                            errs.push(e)
                        }
                    }
                } if (errs.length) {
                    console.error("Handlers for event ", event, " had errors: ", errs);
                    throw errs[0]
                }
            } else {}
            var sourceId = getUID(source);
            if (bubbleMap[sourceId]) {
                var parents = bubbleMap[sourceId];
                for (i = 0; i < parents.length; i++) {
                    arguments[0] = parents[i];
                    Event.trigger.apply(Event, arguments)
                }
            }
        },
        release: function(source) {
            eventMap.release(source);
            var sourceId = getUID(source);
            if (bubbleMap[sourceId]) {
                delete bubbleMap[sourceId]
            }
            if (window.DragDrop !== undefined) {
                DragDrop.removeDropListener(source)
            }
        },
        releaseAll: function() {
            eventMap.releaseAll(function(sourceId) {
                if (bubbleMap[sourceId]) {
                    delete bubbleMap[sourceId]
                }
            });
            if (window.DragDrop !== undefined) {
                DragDrop.removeDropListener(source)
            }
        },
        rateLimit: function(method, delay) {
            if (!delay) {
                return method
            } else {
                var timer = new Timer();
                var timerIsSet = true;
                var hadCall = null;
                timer.replace(function() {
                    if (hadCall) {
                        method.apply(null, hadCall);
                        timerIsSet = true;
                        hadCall = null;
                        timer.reschedule(delay)
                    } else {
                        hadCall = null;
                        timerIsSet = false
                    }
                }, delay);
                return function() {
                    if (timerIsSet) {
                        hadCall = arguments;
                        return
                    }
                    method.apply(null, arguments);
                    timerIsSet = true;
                    timer.reschedule(delay)
                }
            }
        },
        wrapper: function(method, object) {
            if (!method) {
                var jslint = window._Debug && window._Debug.logStackTrace();
                console.log("Wrapper called with method = ", method);
                return noop
            }
            if (!method.apply) {
                var jslint2 = window._Debug && window._Debug.logStackTrace();
                var origFunc = method;
                method = function() {
                    window.__func = origFunc;
                    window.__obj = object;
                    window.__args = arguments;
                    var args = [];
                    for (var i = 0; i < arguments.length; ++i) {
                        args.push("__args[" + i + "]")
                    }
                    var rval = eval("(__obj || window).__func(" + args.join(",") + ")");
                    delete window.__args;
                    delete window.__obj;
                    delete window.__func;
                    return rval
                }
            }
            var cacheKey = getUID(method) + ":" + getUID(object);
            if (window._Debug && (Browser.isFirefox || Browser.isChrome)) {
                var stack = _Debug.getStackTrace();
                var func = function() {
                    try {
                        return method.apply(object, arguments)
                    } catch (e) {
                        console.error(e, {
                            exception: e,
                            method: method,
                            object: object,
                            wrappedBy: stack,
                            stack: e.stack.split(/\n/)
                        })
                    }
                };
                WRAPPERS[cacheKey] = WRAPPERS[cacheKey] || [];
                WRAPPERS[cacheKey].push(func);
                return func
            } else {
                if (!object) {
                    return method
                }
                return (WRAPPERS[cacheKey] = WRAPPERS[cacheKey] || function() {
                    return method.apply(object, arguments)
                })
            }
        },
        isBuiltIn: function(src, name) {
            if ((src.childNodes || src == window) && BUILTINS[name]) {
                return true
            } else {
                return false
            }
        },
        getSource: function(e) {
            return e.target || e.srcElement
        },
        getWheelDelta: function(e) {
            e = e || window.event;
            if (!e) {
                return 0
            } else {
                if (Browser.isIE) {
                    try {
                        return -e.wheelDelta / 120
                    } catch (err) {
                        return 0
                    }
                } else {
                    if (Browser.isFirefox) {
                        return e.detail
                    } else {
                        if (Browser.isSafari || Browser.isChrome) {
                            return -e.wheelDelta / 3
                        } else {
                            return 0
                        }
                    }
                }
            }
        },
        getRelatedTarget: function(e) {
            return e.relatedTarget || e.toElement || e.fromElement
        },
        stopBubble: function(event) {
            event.cancelBubble = true;
            if (event.stopPropagation) {
                event.stopPropagation()
            }
        },
        stopDefault: function(event) {
            if (event.preventDefault) {
                event.preventDefault()
            } else {
                event.returnValue = false
            }
            return false
        },
        defaultPrevented: function(e) {
            return (e.defaultPrevented || e.returnValue === false || !! (e.getPreventDefault && e.getPreventDefault()))
        },
        stop: function(event) {
            Event.stopBubble(event);
            return Event.stopDefault(event)
        },
        checkForBackendEvent: function() {
            var events = Cookie.get("e", true);
            if (!events || !events.uuid) {
                Event.trigger(Event, "backend_events_triggered");
                return
            }
            var now = new Date().getTime();
            if (!events._lts) {
                events._lts = now;
                Cookie.set("e", events)
            }
            if (baseTime - events._lts > 20000) {
                Cookie.clear("e");
                Event.trigger(Event, "backend_events_triggered");
                return
            }
            Event.triggerBackendEvents(events.list, events.uuid)
        },
        triggerBackendEvents: function(list, uuid) {
            Event.addListener(document, "modifiable", function() {
                yield(function() {
                    var seen = WindowSession.get("events") || {};
                    if (!seen[uuid]) {
                        seen[uuid] = new Date().getTime();
                        forEachKey(seen, function(k) {
                            if (baseTime - seen[k] > 30000) {
                                delete seen[k]
                            }
                        });
                        WindowSession.set("events", seen);
                        (list || []).forEach(function(event) {
                            event.unshift(Event.BACKEND);
                            Event.trigger.apply(Event, event)
                        })
                    }
                    Event.trigger(Event, "backend_events_triggered")
                })
            })
        },
        bundleEvents: function(source, event) {
            var listenedEvent = eventMap.getOrInitEventObjects(source, event);
            if (listenedEvent) {
                delete listenedEvent.triggered;
                listenedEvent.shouldBundle = (listenedEvent.shouldBundle || 0) + 1
            }
        },
        unbundleEvents: function(source, event, noTrigger) {
            var listenedEvent = eventMap.getListenedEvent(source, event);
            if (!listenedEvent) {
                return
            }
            listenedEvent.shouldBundle = (listenedEvent.shouldBundle || 0) - 1;
            if (listenedEvent.shouldBundle > 0) {
                return
            }
            var lastTriggeredWithArgs = listenedEvent.triggered;
            delete listenedEvent.triggered;
            if (lastTriggeredWithArgs === undefined) {
                return
            }
            if (noTrigger) {
                return
            }
            var args = [source, event];
            args = args.concat(lastTriggeredWithArgs || []);
            Event.trigger.apply(Event, args)
        },
        pauseEvents: function(source, event) {
            Event.bundleEvents(source, event)
        },
        unpauseEvents: function(source, event) {
            Event.unbundleEvents(source, event, true)
        },
        XFRAME: {},
        BACKEND: {},
        FIREONCE: {
            get: function(src, event) {
                var obj = fireOnceHash.get(src);
                if (!obj) {
                    return undefined
                }
                return obj[event]
            },
            declare: function(src, event) {
                var declaredEvents = fireOnceHash.get(src);
                if (!declaredEvents) {
                    declaredEvents = {};
                    fireOnceHash.put(src, declaredEvents)
                }
                if (declaredEvents[event] === undefined) {
                    declaredEvents[event] = false;
                    var listener = Event.addListener(src, event, function() {
                        listener.clean();
                        declaredEvents[event] = true
                    })
                }
            },
            reset: function(src, event) {
                var declaredEvents = fireOnceHash.get(src);
                if (!declaredEvents) {
                    console.log("WARNING: resetting an undeclared fireonce event");
                    return
                }
                declaredEvents[event] = false;
                var listener = Event.addListener(src, event, function() {
                    listener.clean();
                    declaredEvents[event] = true
                })
            }
        }
    }
}();
Event.FIREONCE.declare(window, "load");
Event.FIREONCE.declare(document, "domready");
Event.FIREONCE.declare(document, "modifiable");
Event.FIREONCE.declare(document, "available");
Event.FIREONCE.declare(Event, "backend_events_triggered");
if (!Browser.isIE) {
    Event._domModOnAvail = Event.addListener(document, "available", function(a) {
        Event.trigger(document, "modifiable", a);
        if (Event._domModOnAvail) {
            Event._domModOnAvail.clean();
            delete Event._domModOnAvail
        }
    })
}
Event._domModOnReady = Event.addListener(document, "domready", function(b) {
    var a = function() {
        Event.trigger(document, "modifiable", b);
        if (Event._domModOnReady) {
            Event._domModOnReady.clean();
            delete Event._domModOnReady
        }
    };
    if (Browser.isIE && (document.getElementsByTagName("embed") || []).length) {
        window.setTimeout(a, 1000)
    } else {
        a()
    }
});
Event.addListener(window, "load", function() {
    if (!Event.FIREONCE.get(document, "domready")) {
        if (Browser.isSafari) {
            if (Event._safariTimer) {
                Event._safariTimer.clear();
                Event._safariTimer = null
            }
        }
        Event.trigger(document, "domready")
    }
    document.write = function(a) {
        (document.write._buffer = document.write._buffer || []).push(a)
    };
    document.writeln = function(a) {
        (document.write._buffer = document.write._buffer || []).push(a + "\n")
    }
});
if (Browser.isIE) {
    if (document.location.protocol != "https:") {
        document.write('<script id="__ie_onload" defer src="javascript:void(0)"><\/script>');
        try {
            document.getElementById("__ie_onload").onreadystatechange = function() {
                if (this.readyState == "complete") {
                    Event.trigger(document, "domready")
                }
            }
        } catch (ignore) {}
    }
} else {
    if (Browser.isSafari) {
        Event._safariTimer = new Interval(10, function() {
            if (/loaded|complete/.test(document.readyState)) {
                if (Event._safariTimer) {
                    Event._safariTimer.clear();
                    Event._safariTimer = null
                }
                Event.trigger(document, "domready")
            }
        })
    } else {
        if (Browser.isFirefox || Browser.isMozilla || Browser.isOpera) {
            Event.addListener(document, "DOMContentLoaded", function() {
                Event.trigger(document, "domready")
            })
        } else {}
    }
}
Event.addListener(window, "beforeunload", function() {
    if (Event.BACKEND.listening) {
        Event.removeListener(Cookie, "change", Event.checkForBackendEvent);
        Event.BACKEND.listening = false
    }
});

function Monitor(b, a) {
    var c = b();
    if (!a) {
        a = 100
    }
    this.check = function() {
        var d = b();
        if (d != c) {
            c = d;
            Event.trigger(this, "change", c)
        }
        return d
    };
    this.timer = new Interval(a, this.check, this)
}
Monitor.prototype.stop = function() {
    this.timer.clear()
};

function isRightClick(a) {
    if (a.which) {
        return (a.which == 3)
    } else {
        if (a.button !== undefined) {
            return (a.button == 2)
        }
    }
    return false
}

function DataTransfer() {
    this.data = {};
    this.proxy = null;
    this.usingProxy = true
}
DataTransfer.prototype.setData = function(b, a) {
    this.data[b] = a
};
DataTransfer.prototype.getData = function(a) {
    return this.data[a]
};
var Cookie = function() {
    var _cookie = null;
    var _domain = null;
    var _interval = null;
    var _user = {};
    var _canNotSetCookies = false;
    Event.addListener(document, "modifiable", function() {
        _interval = new Interval(1000, function() {
            if (!_cookie || _cookie != document.cookie) {
                _cookie = document.cookie;
                Event.trigger(this, "change")
            }
        }, Cookie);
        Cookie.set("__test", "foo");
        if (Cookie.get("__test")) {
            Cookie.set("__test")
        } else {
            _canNotSetCookies = true
        }
    });
    return {
        clear: function(name) {
            var date = new Date();
            date.setTime(date.getTime() - 24 * 60 * 60 * 1000);
            var cookie = name + "=''; expires=" + date.toGMTString() + "; path=/; domain=.";
            _domain = _domain || Conf.getCookieDomain() || "";
            var parts = _domain.split(".");
            if (parts.length > 2) {
                parts.splice(0, parts.length - 2)
            }
            document.cookie = cookie + parts.join(".");
            return true
        },
        set: function(name, data, ttl, noEncode, path) {
            var value = "";
            if (data) {
                if (data.constructor == String) {
                    value = noEncode ? data : encodeURIComponent(data)
                } else {
                    value = encodeURIComponent(JSON2.stringify(data))
                }
            }
            var expires = "";
            if (value === "") {
                ttl = -1
            }
            if (ttl !== undefined) {
                var date = new Date();
                date.setTime(date.getTime() + (ttl * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString()
            }
            try {
                if (!_domain) {
                    _domain = Conf.getCookieDomain()
                }
                if (value !== "") {
                    var limit = 8192;
                    if (Browser.isIE) {
                        limit = 4096
                    }
                    var extra = 56 + _domain.length;
                    var oldLength = 0;
                    if (document.cookie) {
                        var cookies = document.cookie.split(/;\s*/);
                        oldLength = cookies.length * extra + document.cookie.length
                    }
                    var oldValue = Cookie.get(name, false);
                    var oldValueLength = oldValue ? oldValue.length : 0;
                    if ((oldLength + value.length - oldValueLength) > limit) {
                        var error = "exceeds " + limit + " bytes limit for cookie";
                        console.log(error);
                        throw (error)
                    }
                }
                if (!path) {
                    path = "/"
                }
                document.cookie = name + "=" + value + expires + "; path=" + path + "; domain=" + _domain;
                return true
            } catch (e) {
                _canNotSetCookies = true;
                return false
            }
        },
        get: function(name, parse) {
            var cookies = document.cookie.split(/;\s*/);
            for (var i = 0; i < cookies.length; ++i) {
                var bits = cookies[i].split("=", 2);
                if (bits[0] == name) {
                    if (parse) {
                        try {
                            return eval("(" + decodeURIComponent(bits[1]) + ")")
                        } catch (e) {}
                    } else {
                        return decodeURIComponent(bits[1])
                    }
                }
            }
            return null
        },
        canNotSetCookies: function() {
            return _canNotSetCookies
        }
    }
}();

function Timer() {
    this.timerID = null
}
Timer.prototype.reset = function() {
    if (this.timerID) {
        window.clearTimeout(this.timerID);
        this.timerID = null;
        this.cb = null
    }
};
Timer.prototype.replace = function(a, b) {
    this.reset();
    this.cb = a;
    this.timerID = window.setTimeout(a, b)
};
Timer.prototype.reschedule = function(a) {
    if (this.timerID) {
        window.clearTimeout(this.timerID)
    }
    if (this.cb) {
        this.timerID = window.setTimeout(this.cb, a)
    }
};

function Dim(a, b) {
    this.w = Math.abs(a);
    this.h = Math.abs(b);
    if (a === 0) {
        this.aspect = Math.INF
    } else {
        this.aspect = b / a
    }
}
Dim.fromNode = function(a) {
    return new Dim(parseInt(a.offsetWidth, 10), parseInt(a.offsetHeight, 10))
};
Dim.prototype.toString = function() {
    return "(" + this.w + "x" + this.h + ")"
};
Dim.prototype.scale = function(a) {
    this.w = this.w * a;
    this.h = this.h * a
};
Dim.prototype.clone = function() {
    return new Dim(this.w, this.h)
};
Dim.prototype.fit = function(a) {
    var b = Math.min(a.w / this.w, a.h / this.h);
    if (b < 1) {
        this.scale(b)
    }
};
Dim.prototype.equals = function(a) {
    return !(this.w != a.w || this.h != a.h)
};

function Rect(b, d, a, c) {
    this.x1 = b || 0;
    this.y1 = d || 0;
    this.x2 = a || 0;
    this.y2 = c || 0
}
Rect.fromNode = function(a) {
    var c = nodeXY(a);
    var b = Dim.fromNode(a);
    return new Rect(c.x, c.y, c.x + b.w, c.y + b.h)
};
Rect.prototype.toString = function() {
    return "(" + [this.x1, this.y1, this.x2, this.y2].join(", ") + ")"
};
Rect.prototype.equals = function(a) {
    return !(this.x1 != a.x1 || this.y1 != a.y1 || this.x2 != a.x2 || this.y2 != a.y2)
};
Rect.prototype.translate = function(b, a) {
    this.x1 += b;
    this.y1 += a;
    this.x2 += b;
    this.y2 += a;
    return this
};
Rect.prototype.getLocationXY = function(a) {
    switch (a) {
        case "n":
            return new Point(this.x1 + this.width() / 2, this.y1);
        case "ne":
            return new Point(this.x2, this.y1);
        case "e":
            return new Point(this.x2, this.y1 + this.height() / 2);
        case "se":
            return new Point(this.x2, this.y2);
        case "s":
            return new Point(this.x1 + this.width() / 2, this.y2);
        case "sw":
            return new Point(this.x1, this.y2);
        case "w":
            return new Point(this.x1, this.y1 + this.height() / 2);
        case "nw":
            return new Point(this.x1, this.y1)
    }
};
Rect.oppositeLocation = function(a) {
    switch (a) {
        case "n":
            return "s";
        case "ne":
            return "sw";
        case "e":
            return "w";
        case "se":
            return "nw";
        case "s":
            return "n";
        case "sw":
            return "ne";
        case "w":
            return "e";
        case "nw":
            return "se"
    }
    return "n"
};
Rect.prototype.move = function(c, b, a) {
    this.x1 = c.x1 + b;
    this.x2 = c.x2 + b;
    this.y1 = c.y1 + a;
    this.y2 = c.y2 + a;
    return this
};
Rect.prototype.scale = function(b, a) {
    if (a) {
        this.translate(-a.x, -a.y)
    }
    this.x1 *= b.x;
    this.y1 *= b.y;
    this.x2 *= b.x;
    this.y2 *= b.y;
    if (a) {
        this.translate(a.x, a.y)
    }
    return this
};
Rect.prototype.aspect = function() {
    return this.height() / this.width()
};
Rect.prototype.setAspect = function(a) {
    this.setHeight(this.width() * a, 0)
};
Rect.prototype.expand = function(a) {
    this.x1 = Math.min(this.x1, a.x1);
    this.y1 = Math.min(this.y1, a.y1);
    this.x2 = Math.max(this.x2, a.x2);
    this.y2 = Math.max(this.y2, a.y2);
    return this
};
Rect.prototype.clone = function() {
    return new Rect(this.x1, this.y1, this.x2, this.y2)
};
Rect.prototype.width = function() {
    return Math.abs(this.x2 - this.x1)
};
Rect.prototype.setWidth = function(a, c) {
    if (c || c === 0) {
        var f = a - this.width();
        c = Math.max(this.x1, c);
        c = Math.min(this.x2, c);
        var b = this.width() ? Math.abs(this.x1 - c) / this.width() : 0.5;
        var d = b * Math.abs(f);
        if (f < 0) {
            this.x1 += d
        } else {
            this.x1 -= d
        }
    }
    return (this.x2 = this.x1 + a)
};
Rect.prototype.height = function() {
    return Math.abs(this.y2 - this.y1)
};
Rect.prototype.setHeight = function(b, d) {
    if (d || d === 0) {
        var f = b - this.height();
        d = Math.max(this.y1, d);
        d = Math.min(this.y2, d);
        var c = this.height() ? Math.abs(this.y1 - d) / this.height() : 0.5;
        var a = c * Math.abs(f);
        if (f < 0) {
            this.y1 += a
        } else {
            this.y1 -= a
        }
    }
    return (this.y2 = this.y1 + b)
};
Rect.prototype.area = function() {
    return this.width() * this.height()
};
Rect.prototype.top = function() {
    return this.y1
};
Rect.prototype.bottom = function() {
    return this.y2
};
Rect.prototype.left = function() {
    return this.x1
};
Rect.prototype.right = function() {
    return this.x2
};
Rect.prototype.dim = function() {
    return new Dim(this.width(), this.height())
};
Rect.prototype.center = function() {
    return new Point((this.x2 + this.x1) / 2, (this.y2 + this.y1) / 2)
};
Rect.prototype.XYWH = function() {
    return {
        x: this.left(),
        y: this.top(),
        w: this.width(),
        h: this.height()
    }
};
Rect.prototype.getTransformedBounds = function(b, a) {
    var c = this.clone();
    if (a) {
        c.translate(-a.x, -a.y)
    }
    var d = [b.transform(c.x1, c.y1), b.transform(c.x2, c.y1), b.transform(c.x2, c.y2), b.transform(c.x1, c.y2)];
    c.x1 = Math.min(d[0].x, d[1].x, d[2].x, d[3].x);
    c.y1 = Math.min(d[0].y, d[1].y, d[2].y, d[3].y);
    c.x2 = Math.max(d[0].x, d[1].x, d[2].x, d[3].x);
    c.y2 = Math.max(d[0].y, d[1].y, d[2].y, d[3].y);
    if (a) {
        c.translate(a.x, a.y)
    }
    return c
};
Rect.prototype.isInside = function(a) {
    return this.x1 <= a.x && a.x <= this.x2 && this.y1 <= a.y && a.y <= this.y2
};
var JSON2;
if (!JSON2) {
    JSON2 = {}
}(function() {
    function f(n) {
        return n < 10 ? "0" + n : n
    }
    Date.prototype.toJSON = function(key) {
        return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z" : null
    };
    String.prototype.toJSON = Number.prototype.toJSON = function(key) {
        return this.valueOf()
    };
    Boolean.prototype.toJSON = function(key) {
        return this.valueOf() ? 1 : 0
    };
    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap, indent, meta = {
            "\b": "\\b",
            "\t": "\\t",
            "\n": "\\n",
            "\f": "\\f",
            "\r": "\\r",
            '"': '\\"',
            "\\": "\\\\"
        }, rep;

    function quote(string) {
        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function(a) {
            var c = meta[a];
            return typeof c === "string" ? c : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
        }) + '"' : '"' + string + '"'
    }

    function str(key, holder) {
        var i, k, v, length, mind = gap,
            partial, value = holder[key];
        if (typeof rep === "function") {
            value = rep.call(holder, key, value)
        }
        switch (typeof value) {
            case "string":
                return quote(value);
            case "number":
                return isFinite(value) ? String(value) : "null";
            case "boolean":
                return value ? 1 : 0;
            case "null":
                return String(value);
            case "object":
                if (!value) {
                    return "null"
                }
                gap += indent;
                partial = [];
                if (Object.prototype.toString.apply(value) === "[object Array]") {
                    length = value.length;
                    for (i = 0; i < length; i += 1) {
                        partial[i] = str(i, value) || "null"
                    }
                    v = partial.length === 0 ? "[]" : gap ? "[\n" + gap + partial.join(",\n" + gap) + "\n" + mind + "]" : "[" + partial.join(",") + "]";
                    gap = mind;
                    return v
                }
                if (rep && typeof rep === "object") {
                    length = rep.length;
                    for (i = 0; i < length; i += 1) {
                        k = rep[i];
                        if (typeof k === "string") {
                            v = str(k, value);
                            if (v) {
                                partial.push(quote(k) + (gap ? ": " : ":") + v)
                            }
                        }
                    }
                } else {
                    for (k in value) {
                        if (Object.hasOwnProperty.call(value, k)) {
                            v = str(k, value);
                            if (v) {
                                partial.push(quote(k) + (gap ? ": " : ":") + v)
                            }
                        }
                    }
                }
                v = partial.length === 0 ? "{}" : gap ? "{\n" + gap + partial.join(",\n" + gap) + "\n" + mind + "}" : "{" + partial.join(",") + "}";
                gap = mind;
                return v
        }
    }
    if (typeof JSON2.stringify !== "function") {
        JSON2.stringify = function(value, replacer, space) {
            var i;
            gap = "";
            indent = "";
            if (typeof space === "number") {
                for (i = 0; i < space; i += 1) {
                    indent += " "
                }
            } else {
                if (typeof space === "string") {
                    indent = space
                }
            }
            rep = replacer;
            if (replacer && typeof replacer !== "function" && (typeof replacer !== "object" || typeof replacer.length !== "number")) {
                throw new Error("JSON.stringify")
            }
            return str("", {
                "": value
            })
        }
    }
    if (typeof JSON2.parse !== "function") {
        JSON2.parse = function(text, reviver) {
            var j;

            function walk(holder, key) {
                var k, v, value = holder[key];
                if (value && typeof value === "object") {
                    for (k in value) {
                        if (Object.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v
                            } else {
                                delete value[k]
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value)
            }
            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function(a) {
                    return "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
                })
            }
            if (/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) {
                j = eval("(" + text + ")");
                return typeof reviver === "function" ? walk({
                    "": j
                }, "") : j
            }
            throw new SyntaxError("JSON.parse")
        }
    }
}());

function Point(a, b) {
    this.x = a;
    this.y = b
}
Point.prototype.distance = function(a) {
    return Math.sqrt(Math.pow(a.x - this.x, 2) + Math.pow(a.y - this.y, 2))
};
Point.prototype.equals = function(a) {
    return (this.x == a.x) && (this.y == a.y)
};
Point.origin = new Point(0, 0);
Point.prototype.toString = function() {
    return this.x + "," + this.y
};

function Random(a) {
    this.constant = Math.pow(2, 13) + 1;
    this.prime = 7321;
    this.maximum = 100000;
    this.seed = a || (new Date()).getTime()
}
Random.prototype.next = function() {
    this.seed *= this.constant;
    this.seed += this.prime;
    return this.seed % this.maximum / this.maximum
};

function escapeHTML(a) {
    return a.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/ {2}/g, "&nbsp;&nbsp;").replace(/\"/g, "&quot;")
}

function str2nodes(str, container) {
    var html = (str || "").split(/(<\/?scrip[t][^>]*>)/);
    var js = [];
    var inScript = false;
    html = html.filter(function(snippet) {
        snippet = (snippet || "").trim();
        if (!snippet) {
            return false
        } else {
            if (/^[<]script/.test(snippet)) {
                inScript = true;
                return false
            } else {
                if (snippet == "<\/script>") {
                    inScript = false;
                    return false
                } else {
                    if (inScript) {
                        js.push(snippet);
                        return false
                    } else {
                        return snippet
                    }
                }
            }
        }
    });
    var tmp = createNode("div", null, null, html.join(""));
    if (container) {
        while (tmp.childNodes.length) {
            container.appendChild(tmp.childNodes[0])
        }
        eval("(function(){" + js.join(";") + "}())")
    } else {
        return {
            nodes: toArray(tmp.childNodes),
            js: function() {
                eval("(function(){" + js.join(";") + "}())")
            }
        }
    }
}

function nodes2str(a) {
    if (a.length < 1) {
        return ""
    }
    var c = createNode("div");
    for (var b = 0; b < a.length; b++) {
        c.appendChild(a[b].cloneNode(true))
    }
    return c.innerHTML
}

function addList(d, b, a, c) {
    return d.appendChild(createNode("li", a, c, b))
}

function inputValue(a) {
    var b = "";
    if (window.InputHint && InputHint.isHint(a)) {
        return b
    }
    switch (a.tagName) {
        case "INPUT":
            switch (a.type) {
                case "text":
                case "email":
                case "submit":
                case "password":
                case "hidden":
                case "file":
                    b = a.value;
                    break;
                case "checkbox":
                case "radio":
                    if (a.checked) {
                        b = a.value
                    }
                    break
            }
            break;
        case "BUTTON":
        case "TEXTAREA":
        case "SELECT":
            b = a.value;
            break;
        default:
            throw "Not an input element"
    }
    return b
}

function decodeHtml(a) {
    if (!a) {
        return a
    }
    return a.replace(/&lt;/g, "<").replace(/&gt;/g, ">")
}

function createImg(a, b) {
    var c = createNode("img", a, b);
    if (a.width) {
        c.width = a.width
    }
    if (a.height) {
        c.height = a.height
    }
    return c
}

function addTextCtrl(d, b) {
    var a = d.parentNode;
    var f = createNode("input", b);
    var g = createNode("span", {
        className: "add_row",
        title: loc("Add another option")
    });
    Event.addListener(g, "click", function() {
        addTextCtrl(g, b)
    });
    var c = createNode("span", {
        className: "del_row",
        title: loc("Remove this option")
    });
    Event.addListener(c, "click", function() {
        delTextCtrl(c)
    });
    domInsertAfter(a, createNode("li", null, null, [f, g, c]))
}

function delTextCtrl(b) {
    var a = b.parentNode;
    if (domRealChildrenCount(a.parentNode) < 3) {
        ModalDialog.alert(loc("You need at least two options"));
        return
    }
    domRemoveNode(a)
}

function createCopyPaste(b, c) {
    if (!b) {
        b = {}
    }
    var a = createNode("textarea", {
        className: "copypaste",
        readOnly: true,
        name: b.name,
        id: b.id,
        wrap: "soft",
        rows: b.rows || 6,
        cols: 30
    }, null, "");
    a.value = decodeHtml(c);
    Event.addListener(a, "click", function(d) {
        a.focus();
        a.select()
    });
    return a
}

function createInput(d, a, c) {
    if (!a) {
        a = {}
    }
    var f;
    a.type = d;
    switch (d) {
        case "radio":
        case "checkbox":
            return createCheckboxOrRadio(d, a, c);
        default:
            if (Browser.type("IE", 6, 8)) {
                var b = ['<input type="', d, '"'];
                if (a.name) {
                    b.push(' name="');
                    b.push(a.name + '"')
                }
                if (a.id) {
                    b.push(' id="');
                    b.push(a.id + '"')
                }
                b.push(" />");
                f = document.createElement(b.join(""));
                delete a.name;
                delete a.id;
                delete a.type;
                setNode(f, a, c)
            } else {
                f = _createNode("input", a, c)
            }
            return f
    }
}

function createCheckboxOrRadio(c, a, b) {
    if (!a) {
        a = {}
    }
    a.checked = a.defaultChecked = a.checked ? true : null;
    var d;
    if (Browser.type("IE", 6, 8) && c.toLowerCase() == "radio") {
        d = document.createElement(['<input type="radio" name="', a.name, '" id="', a.id, '" />'].join(""));
        setNode(d, a, b)
    } else {
        d = _createNode("input", a, b)
    }
    setNode(d, {
        type: c
    });
    if (a.value !== undefined) {
        d.setAttribute("value", a.value)
    }
    return d
}

function createSelect(b, f, c, d) {
    var a = createNode("select", b, f);
    c.forEach(function(h) {
        var g = a.appendChild(createNode("option", {
            value: h.value
        }, null, h.label));
        if (d && h.value == d) {
            setNode(g, {
                selected: true
            })
        }
    });
    return a
}

function createLabel(a, f, b) {
    var c = _createNode("label", a, f, b);
    var d = createNode("span");
    d.innerHTML = outerHTML(c);
    return d.childNodes[0]
}

function createList(a, d, b) {
    var c = createNode("ul", a, d);
    if (b) {
        b.forEach(function(f) {
            addList(c, f)
        })
    }
    return c
}

function createHTML(k, d, a, j) {
    var c = ["<", k, " "];
    var b, f;
    if (a) {
        if (Browser.isIE && a.hasOwnProperty("opacity") && !a.filter) {
            f = a.opacity;
            if (f || f === 0) {
                a.filter = "alpha(opacity=" + f * 100 + ")";
                a.zoom = a.zoom || 1
            }
        }
        var h = [];
        for (b in a) {
            if (!a.hasOwnProperty(b)) {
                continue
            }
            f = a[b];
            if (!f) {
                continue
            }
            var g = b.replace(/([A-Z])/g, "-$1").toLowerCase();
            h.push(g, ":", f, ";")
        }
        d = d || {};
        if (d.style) {
            d.style += ";" + h.join("")
        } else {
            d.style = h.join("")
        }
    }
    if (d) {
        if (d.hasOwnProperty("className")) {
            d["class"] = d.className
        }
        for (b in d) {
            if (!d.hasOwnProperty(b)) {
                continue
            }
            f = d[b];
            if (f || f === "" || f === false || f === 0) {
                c.push(b);
                c.push('="');
                c.push(f.replace('"', "&quot;"));
                c.push('" ')
            }
        }
    }
    c.push(">");
    if (j) {
        if (typeof(j) == "string") {
            c.push(j)
        } else {
            if (j.constructor == Array) {
                for (b = 0; b < j.length; ++b) {
                    if (typeof(j[b]) == "string") {
                        c.push(j[b])
                    } else {
                        c.push(outerHTML(j[b]))
                    }
                }
            } else {
                c.push(outerHTML(j))
            }
        }
    }
    if (!/^(br|option|li)$/.test(k)) {
        c.push("</", k, ">")
    }
    return c.join("")
}

function outboundLink(b, c, a) {
    b = b || {};
    b.target = "_blank";
    b.className = b.className || "outbound";
    b.trackelement = b.trackelement || "site";
    return createNode("a", b, c, a)
}

function getTbody(a) {
    var b = a.getElementsByTagName("tbody");
    if (b && b.length > 0) {
        return b[0]
    } else {
        return a.appendChild(createNode("tbody"))
    }
}

function outerHTML(b) {
    var a = document.createElement("div");
    replaceChild(a, b);
    return a.innerHTML
}

function selectInputText(b, d, a) {
    if (b.setSelectionRange) {
        b.setSelectionRange(d, a)
    } else {
        if (b.createTextRange) {
            var c = b.createTextRange();
            c.moveStart("character", d);
            c.moveEnd("character", a - b.value.length);
            c.select()
        } else {
            b.select()
        }
    }
}

function getCaretPosition(a) {
    if (!a.value) {
        return 0
    }
    try {
        if (typeof(a.selectionStart) == "number") {
            if (document.activeElement != a) {
                return -1
            }
            return a.selectionStart
        } else {
            if (document.selection) {
                try {
                    var f = document.selection.createRange();
                    var c = a.createTextRange();
                    c.setEndPoint("StartToStart", f);
                    return inputValue(a).lastIndexOf(c.text)
                } catch (d) {
                    return -1
                }
            } else {
                return -1
            }
        }
    } catch (b) {
        if (b.message.indexOf("Component returned failure code: 0x80004005") === 0 && !isAttachedToDom(a)) {
            return -1
        }
        throw b
    }
}

function stripHtml(a) {
    a = a.replace(/<[^>]*>/g, "");
    a = a.replace(/[ \t]+/g, " ");
    return a
}

function getVisibleHtmlText(a) {
    a = a.replace(/<script[^>]*>.*?<\/script>/ig, "");
    a = a.replace(/<head[^>]*>.*?<\/head>/ig, "");
    a = a.replace(/<style[^>]*>.*?<\/style>/ig, "");
    a = stripHtml(a);
    a = a.replace(/([\s\u00a0]){2,}/g, "$1");
    return a
}

function insertScriptNode(f, d) {
    var a = document.getElementsByTagName("script");
    if (a) {
        var b = a.length;
        for (var c = 0; c < b; c++) {
            if (a[c].src == f) {
                return
            }
        }
    }
    Event.addListener(document, "modifiable", function() {
        var h = false;
        var g = createNode("script", {
            src: f
        });
        if (d) {
            g.onload = g.onreadystatechange = function() {
                if (h) {
                    return
                }
                var j = this.readyState;
                if (j && j != "complete" && j != "loaded") {
                    return
                }
                g.onreadystatechange = g.onload = null;
                h = true;
                d.call()
            }
        }
        document.body.appendChild(g)
    })
}

function appendScript(f, b, c, a) {
    f = f || document.body;
    a = a || noop;
    b = b || {};
    var g;
    if (!b.src && c && c.trim()) {
        g = createNode("script", b);
        g.text = c;
        g.type = "any";
        f.appendChild(g);
        if (b.type) {
            g.setAttribute("type", b.type)
        } else {
            g.removeAttribute("type")
        }
        window._lsn = g;
        (new Function(g.text))();
        window._lsn = null;
        yield(a)
    } else {
        g = createNode("script", b);
        if (a) {
            var d;
            g.onload = g.onerror = g.onreadystatechange = function() {
                if (d) {
                    return
                }
                var h = g.readyState;
                if (h && h != "complete" && h != "loaded") {
                    return
                }
                window._lsn = g._prevLSN;
                d = true;
                g.onreadystatechange = g.onload = g.onerror = null;
                yield(a)
            }
        }
        g._prevLSN = window._lsn || null;
        window._lsn = g;
        f.appendChild(g)
    }
    return g
}

function flushDocumentWriteBuffer(d, a) {
    d = d || document.body;
    a = a || noop;
    var b = document.write._buffer;
    if (!b) {
        yield(a);
        return
    }
    b = b.join("");
    document.write._buffer.length = 0;
    b = createNode("div", null, null, "<br/>" + b);
    b.removeChild(b.firstChild);
    var c = [];
    while (b.childNodes.length) {
        c.push(b.removeChild(b.lastChild))
    }
    _appendNodes(d, c, a)
}

function _appendNodes(f, c, b) {
    if (!c || !c.length) {
        yield(b);
        return
    }
    var g = c.pop();
    if (g.tagName == "SCRIPT") {
        var a = {};
        if (g.src) {
            a.src = g.src
        }
        if (g.type) {
            a.type = g.type
        }
        appendScript(f, a, g.text, function() {
            flushDocumentWriteBuffer(f, function() {
                _appendNodes(f, c, b)
            })
        })
    } else {
        var h = g.cloneNode(false);
        if (g.nodeType == 3) {
            f.appendChild(h);
            yield(function() {
                _appendNodes(f, c, b)
            })
        } else {
            f.appendChild(h);
            var d = [];
            while (g.childNodes.length) {
                d.push(g.removeChild(g.lastChild))
            }
            yield(function() {
                _appendNodes(h, d, function() {
                    _appendNodes(f, c, b)
                })
            })
        }
    }
}
var Dom = function() {
    var a = 0;
    return {
        uniqueId: function(b) {
            return "js_" + (b || "") + (++a)
        }
    }
}();

function $(a) {
    if (a && a.constructor == String) {
        return document.getElementById(a)
    }
    return a
}

function setNode(g, a, f, b) {
    if (!g) {
        return null
    }
    var c, h;
    if (f) {
        var d = g.style;
        if (Browser.isIE && f["float"]) {
            f.cssFloat = f["float"]
        }
        if (Browser.isIE && f.hasOwnProperty("opacity") && typeof(d.opacity) !== "string" && typeof(d.filter) == "string") {
            h = f.opacity;
            if (h || h === 0) {
                d.filter = "alpha(opacity=" + h * 100 + ")";
                if (!g.currentStyle || !g.currentStyle.hasLayout) {
                    d.zoom = 1
                }
            } else {
                d.filter = null
            }
            delete f.opacity
        }
        for (c in f) {
            if (f.hasOwnProperty(c)) {
                h = f[c];
                h = h === undefined ? null : h;
                if (d[c] != h) {
                    try {
                        d[c] = h
                    } catch (j) {
                        if (Browser.isIE && j.toString().match(/Invalid argument/)) {
                            d[c] = ""
                        } else {
                            throw j
                        }
                    }
                }
            }
        }
    }
    if (a) {
        for (c in a) {
            if (a.hasOwnProperty(c)) {
                h = a[c];
                if (c == "class" || c == "className") {
                    g.className = h
                } else {
                    if (h !== g.getAttribute(c)) {
                      console.log("custom: " + h + ", val: " + c);
                        if (h || h === "" || h === false || h === 0) {
                            g.setAttribute(c, h)
                        } else {
                            g.removeAttribute(c)
                        }
                    }
                }
            }
        }
    }
    if (b !== undefined) {
        replaceChild(g, b)
    }
    return g
}

function createNode(b, a, d, c) {
    return setNode(document.createElement(b), a, d, c)
}

function replaceChild(c, a) {
    if (a && typeof(a) == "string" && a.indexOf("<object") === 0) {
        c.innerHTML = a;
        return
    }
    var b = createNode("div");
    if (c.childNodes && c.childNodes.length) {
        clearNode(c)
    }
    a = flatten(a);
    a.forEach(function(f) {
        var d = typeof(f);
        switch (d) {
            case "string":
                f = f.replace(/^ /, "&nbsp;");
                b.innerHTML = f;
                while (b.childNodes.length) {
                    c.appendChild(b.childNodes[0])
                }
                b.innerHTML = "";
                break;
            case "number":
                c.appendChild(document.createTextNode(f));
                break;
            default:
                c.appendChild(f)
        }
    })
}

function clearNode(b, a) {
    purge(b, a);
    domRemoveDescendants(b, false)
}

function domRemoveDescendants(g, d) {
    if (!g) {
        return
    }
    var c, f, b;
    if ((c = g.childNodes)) {
        b = c.length;
        for (f = b - 1; f >= 0; --f) {
            domRemoveNode(c[f], d)
        }
    }
}

function domRemoveNode(c, a) {
    if (!c) {
        return
    }
    if (a || a === undefined) {
        purge(c, true)
    }
    if (!Browser.isIE || c.tagName == "SCRIPT") {
        if (c.parentNode) {
            c.parentNode.removeChild(c)
        }
        return
    }
    var g = $("IELeakGarbageBin");
    if (!g) {
        g = document.body.appendChild(createNode("div", {
            id: "IELeakGarbageBin"
        }, {
            display: "none"
        }))
    }
    var d = [c];
    while (d.length) {
        var f = d.pop();
        if (f.tagName == "SCRIPT") {
            f.parentNode.removeChild(f)
        } else {
            for (var b = 0; b < f.childNodes.length; ++b) {
                d.push(f.childNodes[b])
            }
        }
    }
    g.appendChild(c);
    g.innerHTML = ""
}
var textContent;
if (Browser.isIE) {
    textContent = function(a) {
        if (a.nodeType == 3) {
            return a.nodeValue
        } else {
            return a.innerText
        }
    }
} else {
    textContent = function(a) {
        return a.textContent
    }
}

function _nodeCleaner() {
    Event.release(this);
    var a = this.attributes;
    if (!a) {
        return
    }
    for (var b = 0; b < a.length;) {
        var c = a[b].name;
        if (typeof this[c] === "function") {
            this[c] = null
        } else {
            ++b
        }
    }
}

function purge(g, f) {
    if (!g || g.nodeName == "EMBED") {
        return
    }
    var c, d, b, h;
    if (f) {
        _nodeCleaner.call(g)
    }
    c = g.childNodes;
    if (c) {
        b = c.length;
        for (d = 0; d < b; d += 1) {
            purge(c[d], true)
        }
    }
}

function matchingAncestor(c, b, a) {
    if (b && typeof b.toUpperCase == "function") {
        b = b.toUpperCase()
    }
    while (c && c.tagName != "HTML" && c.tagName != "BODY" && c.getAttribute) {
        if (b) {
            if (c.tagName == b && c.getAttribute(a)) {
                return c
            }
            c = c.parentNode
        } else {
            if (c.getAttribute(a)) {
                return c
            }
            c = c.parentNode
        }
    }
    return null
}

function getElementsWithAttributes(q) {
    var o = q.root || document;
    var c = q.tagName || "*";
    var h = q.attributes || {};
    if (o.querySelectorAll) {
        var f = [c];
        forEachKey(h, function(r, j) {
            if (j) {
                f.push("[" + r + "=" + j.quote() + "]")
            } else {
                f.push("[" + r + "]")
            }
        });
        f = f.join("");
        try {
            return o.querySelectorAll(f)
        } catch (n) {}
    } else {
        var l = [];
        var a = o.getElementsByTagName(c);
        h = h.map(function(j) {
            var r = j.split("=");
            if (r.length != 2) {
                r = [j, null]
            } else {
                r[1] = r[1].replace(/^(\"|\')/, "").replace(/(\"|\')$/, "")
            }
            return {
                name: r[0],
                value: r[1]
            }
        });
        for (var k = 0; k < a.length; k++) {
            var d = a[k];
            var p = true;
            for (var g = 0; g < h.length; g++) {
                var m = h[g];
                var b = d.getAttribute(m.name);
                if (!b || (m.value && b !== m.value)) {
                    p = false;
                    break
                }
            }
            if (p) {
                l.push(d)
            }
        }
        return l
    }
}

function fullCreateNode(b, a, f, c) {
    var d = b.toLowerCase();
    switch (d) {
        case "input":
            return createInput(a.type, a, f);
        case "label":
            return createLabel(a, f, c)
    }
    return _createNode(b, a, f, c)
}
createNode = fullCreateNode;

function _createNode(b, a, d, c) {
    return setNode(document.createElement(b), a, d, c)
}

function createSprite(b, a) {
    return createNode("div", {
        className: "sprite " + (b || "")
    }, null, a)
}

function domInsertAfter(b, a) {
    return b.parentNode.insertBefore(a, b.nextSibling)
}

function domInsertAtTop(b, a) {
    if (b.childNodes && b.childNodes.length > 0) {
        b.insertBefore(a, b.childNodes[0])
    } else {
        b.appendChild(a)
    }
}

function domContainsChild(a, b) {
    while (b) {
        if (b == a) {
            return true
        }
        b = b.parentNode
    }
    return false
}

function delayedClearNode(j, d) {
    if (!j || j.nodeName == "EMBED") {
        return
    }
    var c, h, b, k;
    if (d) {
        _nodeCleaner.apply(j)
    }
    var f = [];
    var g = createNode("div", null, {
        display: "none"
    });
    c = j.childNodes;
    if (c) {
        while (c.length) {
            g.appendChild(c[0])
        }
    }
    window.setTimeout(function() {
        loopNonBlocking(20, function() {
            if (!f.length) {
                return true
            }
            var a = f.shift();
            ExecQueue.push(Event.wrapper(_nodeCleaner, a));
            c = a.childNodes;
            if (c) {
                b = c.length;
                for (h = 0; h < b; h += 1) {
                    f.push(c[h])
                }
            }
        })
    }, 200)
}

function domRealChildrenCount(d) {
    var b = d.childNodes;
    var a = 0;
    for (var c = 0; c < b.length; ++c) {
        if (b[c].nodeType == 1) {
            a++
        }
    }
    return a
}

function domPrev(a) {
    do {
        a = a.previousSibling
    } while (a && a.nodeType != 1);
    return a
}

function domNext(a) {
    do {
        a = a.nextSibling
    } while (a && a.nodeType != 1);
    return a
}

function isAttachedToDom(a) {
    while (a) {
        if (a == document.body) {
            return true
        }
        a = a.parentNode
    }
    return false
}

function domPoke(a, b) {
    if (b || Browser.type("IE", 0, 6)) {
        setNode(a, null, {
            zoom: 0
        });
        yield(function() {
            setNode(a, null, {
                zoom: 1
            })
        })
    }
}

function getElementsByClassName(c) {
    var a = c.root || document;
    var g = c.tagName || "*";
    var h = c.className;
    var f = [];
    var b = a.getElementsByTagName(g);
    for (var d = 0; d < b.length; d++) {
        if (hasClass(b[d], h)) {
            f.push(b[d])
        }
    }
    return f
}

function inOrderTraversal(b, c, a) {
    if (!b) {
        return a || null
    }
    if (!c) {
        c = document
    }
    if (b(c)) {
        if (!a) {
            return c
        }
        a.push(c)
    }
    for (var d = 0; d < c.childNodes.length; ++d) {
        var f = inOrderTraversal(b, c.childNodes[d], a);
        if (f && !a) {
            return f
        }
    }
    return a || null
}

function domGetContainer(a) {
    while (a && a.nodeType != 1) {
        a = a.parentNode
    }
    if (!a) {
        return null
    }
    if (a.nodeName.match(/INPUT|IMG/)) {
        return domGetContainer(a.parentNode)
    }
    return a
}

function lastScriptNode() {
    if (window._lsn) {
        return window._lsn
    }
    var a = document.getElementsByTagName("script");
    return a ? a[a.length - 1] : null
}

function setDefaultEmbedWMode(a) {
    a = a || "opaque";
    Event.addListener(document, "domready", Browser.isIE ? function() {
        var f = document.getElementsByTagName("object");
        var k;
        var m;
        var j;
        var g;
        var l;
        for (var d = 0; d < f.length; ++d) {
            var c = f[d];
            if (!/<embed\b/i.test(c.innerHTML)) {
                continue
            }
            j = (/\bwmode=.?([\w]+)/i.test(c.innerHTML) ? RegExp.$1 : "Window").toLowerCase();
            if (j != "window" || j == a) {
                continue
            }
            k = c.parentNode;
            m = c.nextSibling;
            g = outerHTML(c);
            if (/\bwmode=/i.test(g)) {
                g = g.replace(/\bwmode=(\'|\")?[\w]+(\'|\")?/ig, "wmode=" + a)
            } else {
                g = g.replace(/<embed\b/ig, "<embed wmode=" + a)
            }
            g = g.replace(/<param[^>]*\bwmode\b[^>]*>(<\/param>)?/i, "");
            g = g.replace(/<\/object>/ig, "<param name=wmode value=" + a + " /></object>");
            l = createNode("div", null, null, g).childNodes[0];
            k.insertBefore(l, m)
        }
        var b = document.getElementsByTagName("embed");
        for (d = 0; d < b.length; ++d) {
            var h = b[d];
            j = (h.getAttribute("wmode") || "Window").toLowerCase();
            if (j != "window" || j == a || !h.parentNode || h.parentNode.tagName.toLowerCase() == "object") {
                continue
            }
            k = h.parentNode;
            m = h.nextSibling;
            g = outerHTML(h);
            g = g.replace(/<embed\b/ig, "<embed wmode=" + a);
            l = createNode("div", null, null, g).childNodes[0];
            k.insertBefore(l, m)
        }
    } : function() {
        var g = document.getElementsByTagName("embed");
        for (var c = 0; c < g.length; ++c) {
            var h = g[c];
            var b = (h.getAttribute("wmode") || "Window").toLowerCase();
            if (b != "window" || b == a) {
                continue
            }
            setNode(h, {
                wmode: a
            });
            var f = h.parentNode;
            var d = h.nextSibling;
            f.removeChild(h);
            f.insertBefore(h, d)
        }
    })
}

function toggleClass(c, b) {
    if (!c || c.className === undefined || !b) {
        return null
    }
    var d = c.className.split(/\s+/);
    var a = b.split(/\s+/);
    a.forEach(function(f) {
        if (d.contains(f)) {
            d.removeAll(f)
        } else {
            d.push(f)
        }
    });
    d.removeAll("");
    return setNode(c, {
        className: d.join(" ")
    })
}

function toggleClassByClassName(g, d) {
    var f = document.getElementsByClassName(g);
    for (var b = 0, a = f.length; b < a; b++) {
        var c = f[b];
        toggleClass(c, d)
    }
}

function addClass(b, a) {
    if (!b || b.className === undefined || !a) {
        return null
    }
    var d = b.className.split(/\s+/);
    var c = a.split(/\s+/);
    c.forEach(function(f) {
        if (!d.contains(f)) {
            d.push(f)
        }
    });
    d.removeAll("");
    return setNode(b, {
        className: d.join(" ")
    })
}

function removeClass(c, b) {
    if (!c || c.className === undefined || !b) {
        return null
    }
    var d = c.className.split(/\s+/);
    var a = b.split(/\s+/);
    a.forEach(function(f) {
        d.removeAll(f)
    });
    d.removeAll("");
    return setNode(c, {
        className: d.join(" ")
    })
}

function hasClass(b, a) {
    if (!b || b.className === undefined || !a) {
        return null
    }
    var c = b.className.split(/\s+/);
    return c.contains(a)
}

function getStyle(b, c) {
    if (b.nodeType != 1) {
        return null
    }
    if (b.style[c]) {
        return b.style[c]
    } else {
        if (b.currentStyle) {
            return b.currentStyle[c]
        } else {
            if (document.defaultView && document.defaultView.getComputedStyle) {
                c = c.replace(/([A-Z])/g, "-$1");
                c = c.toLowerCase();
                var a = document.defaultView.getComputedStyle(b, "");
                return a && a.getPropertyValue(c)
            } else {
                return null
            }
        }
    }
}

function scrollXY(b, a) {
    if (!b) {
        b = new Point()
    }
    if (!a || a == window) {
        if (window.pageXOffset !== undefined) {
            b.x = window.pageXOffset;
            b.y = window.pageYOffset
        } else {
            if (document.documentElement) {
                b.x = document.documentElement.scrollLeft;
                b.y = document.documentElement.scrollTop
            } else {
                b.x = document.body.scrollLeft;
                b.y = document.body.scrollTop
            }
        }
    } else {
        b.x = a.scrollLeft;
        b.y = a.scrollTop
    }
    return b
}

function px(a) {
    return Math.round(a) + "px"
}

function fromPx(a) {
    if (!a.match(/\d+px/)) {
        return a
    }
    return parseInt(a.replace("px", ""), 10)
}

function hasDim(a) {
    return parseInt(a.offsetWidth, 10) > 0 && parseInt(a.offsetHeight, 10) > 0
}

function hide(a) {
    addClass(a, "hidden");
    addClass(a, "invisible");
    setNode(a, null, {
        display: "none",
        visibility: "hidden"
    })
}

function show(a) {
    removeClass(a, "hidden");
    removeClass(a, "invisible");
    setNode(a, null, {
        display: "block",
        visibility: "inherit"
    })
}

function getWindowSize() {
    var a = document.compatMode;
    if (a || Browser.isIE) {
        if (a == "CSS1Compat") {
            return new Dim(document.documentElement.clientWidth, document.documentElement.clientHeight)
        } else {
            return new Dim(document.body.clientWidth, document.body.clientHeight)
        }
    } else {
        return new Dim(window.innerWidth, window.innerHeight)
    }
}

function nodeXY(b) {
    var m;
    var g;
    if (b.getBoundingClientRect && !Browser.layoutEngine("WebKit") && !Browser.layoutEngine("Gecko", 12, 0)) {
        var l = window.document;
        var f;
        try {
            f = b.getBoundingClientRect()
        } catch (j) {
            return new Point(0, 0)
        }
        if (Browser.isIE) {
            f.left -= 2;
            f.top -= 2
        }
        m = new Point(f.left, f.top);
        var d = false;
        while (b && b.tagName != "HTML" && b.tagName != "BODY") {
            if (getStyle(b, "position") == "fixed") {
                d = true;
                break
            }
            b = b.parentNode
        }
        if (!d) {
            var a = Math.max(l.documentElement.scrollTop, l.body.scrollTop);
            var c = Math.max(l.documentElement.scrollLeft, l.body.scrollLeft);
            m.x += c;
            m.y += a
        }
        return m
    } else {
        m = new Point(b.offsetLeft, b.offsetTop);
        g = b.offsetParent;
        var k = Browser.isSafari;
        var h = getStyle(b, "position") == "absolute";
        if (g != b) {
            while (g) {
                m.x += g.offsetLeft;
                m.y += g.offsetTop;
                if (k && !h && getStyle(g, "position") == "absolute") {
                    h = true
                }
                g = g.offsetParent
            }
        }
        if (k && h) {
            m.x -= document.body.offsetLeft;
            m.y -= document.body.offsetTop
        }
        g = b.parentNode;
        while (g && g.tagName != "HTML" && g.tagName != "BODY") {
            if (getStyle(g, "display") != "inline") {
                m.x -= g.scrollLeft;
                m.y -= g.scrollTop
            }
            g = g.parentNode
        }
        return m
    }
}

function makeUnselectable(a) {
    Event.addListener(a, "selectstart", returnFalse);
    Event.addListener(a, "drag", returnFalse);
    addClass(a, "unselectable");
    setNode(a, {
        unselectable: "on"
    })
}

function showInline(a) {
    removeClass(a, "hidden");
    removeClass(a, "invisible");
    setNode(a, null, {
        display: "inline",
        visibility: "inherit"
    })
}

function disable(a) {
    setNode(a, {
        disabled: true
    })
}

function enable(a) {
    setNode(a, {
        disabled: null
    })
}

function getElementSize(a) {
    return new Dim(a.offsetWidth + depx(getStyle(a, "marginLeft")) + depx(getStyle(a, "marginRight")), a.offsetHeight + depx(getStyle(a, "marginTop")) + depx(getStyle(a, "marginBottom")))
}

function setScroll(f) {
    var c = document.documentElement;
    var a = document.body;
    c.scrollLeft = a.scrollLeft = f.x;
    c.scrollTop = a.scrollTop = f.y
}

function scrollToMiddle(b, a) {
    a = a || (b ? b.parentNode : null);
    if (!b || !a) {
        return
    }
    return b.offsetTop + b.clientHeight / 2 - a.clientHeight / 2
}

function scrollUp(a, b) {
    if (b.offsetTop < a.scrollTop) {
        a.scrollTop = b.offsetTop
    } else {
        if (b.offsetTop > a.scrollTop + a.offsetHeight) {
            a.scrollTop = b.offsetTop + b.offsetHeight - a.offsetHeight
        }
    }
}

function scrollDown(a, b) {
    if (b.offsetTop + b.offsetHeight > a.scrollTop + a.offsetHeight) {
        a.scrollTop = b.offsetTop + b.offsetHeight - a.offsetHeight
    } else {
        if (b.offsetTop + b.offsetHeight < a.scrollTop) {
            a.scrollTop = b.offsetTop
        }
    }
}

function isDescendantOfFixed(a) {
    while (a) {
        if (getStyle(a, "position") == "fixed") {
            return true
        }
        a = a.parentElement
    }
    return false
}
var _scrollbarWidth = -1;

function getScrollbarWidth() {
    if (_scrollbarWidth > 0) {
        return _scrollbarWidth
    }
    if (Browser.type("IE", 0, 6)) {
        return (_scrollbarWidth = 20)
    }
    if (_scrollbarWidth < 0) {
        _scrollbarWidth = 0;
        var b = createNode("div", null, {
            height: "50px",
            width: "50px",
            display: "block",
            overflowY: "scroll",
            visibility: "hidden",
            position: "absolute",
            top: "0"
        });
        var a = b.appendChild(createNode("div"));
        Event.addListener(document, "modifiable", function() {
            document.body.appendChild(b);
            yield(function() {
                _scrollbarWidth = Dim.fromNode(b).w - Dim.fromNode(a).w;
                domRemoveNode(b)
            })
        })
    }
    return 14
}
getScrollbarWidth();
var _modifiableStyleSheet;
var _cssRules = {};
var _toUpperCase = function(b, a) {
    return (a || "").toUpperCase()
};

function _getModifiableStyleSheet() {
    if (_modifiableStyleSheet) {
        return _modifiableStyleSheet
    }
    try {
        var a = document.getElementsByTagName("head")[0] || document.body;
        a.appendChild(createNode("style", {
            type: "text/css"
        }))
    } catch (b) {
        return (_modifiableStyleSheet = document.styleSheets[0])
    }
    return (_modifiableStyleSheet = document.styleSheets[document.styleSheets.length - 1])
}

function editCSSStyleText(g, c) {
    if (Browser.isIE) {
        return setNode(g, null, c)
    }
    var f = {};
    for (var b = 0; b < g.style.length; ++b) {
        var a = g.style[b].replace(/-([a-z])/, _toUpperCase);
        f[a] = g.style[a]
    }
    forEachKey(c, function(j, h) {
        h = h === undefined ? null : h;
        if (h || h === 0) {
            f[j] = h;
            if (j == "float") {
                f.cssFloat = h
            }
        } else {
            delete f[j]
        }
    });
    var d = [];
    forEachKey(f, function(j, h) {
        j = j.replace(/([A-Z])/g, "-$1").toLowerCase();
        d.push(j, ":", h, ";")
    });
    g.style.cssText = d.join("");
    return g
}

function editCSSRule(d, a) {
    a = a || {};
    var l;
    if ((l = _cssRules[d])) {
        return editCSSStyleText(l, a)
    }
    var h = noop;
    var c;
    if (document.baseURI != window.location.toString() && (Browser.layoutEngine("WebKit") || Browser.type("IE", 0, 7)) && (c = document.getElementsByTagName("base")).length) {
        var b;
        var n;
        for (var f = c.length - 1; f >= 0; --f) {
            if (c[f].href) {
                b = c[f];
                n = b.nextSibling
            }
        }
        if (b) {
            b.parentNode.removeChild(b)
        }
        var k = document.head;
        if (!k) {
            k = document.getElementsByTagName("head");
            k = k[k.length - 1]
        }
        var j = k.appendChild(createNode("base", {
            href: window.location.toString()
        }));
        h = function() {
            if (j) {
                j.parentNode.removeChild(j)
            }
            if (b) {
                k.insertBefore(b, n)
            }
        }
    }
    var m = _getModifiableStyleSheet();
    if (!_modifiableStyleSheet) {
        h();
        return null
    }
    if (_modifiableStyleSheet.addRule && _modifiableStyleSheet.rules) {
        _modifiableStyleSheet.addRule(d, "width:auto");
        l = _modifiableStyleSheet.rules[_modifiableStyleSheet.rules.length - 1]
    } else {
        if (_modifiableStyleSheet.insertRule && _modifiableStyleSheet.cssRules) {
            _modifiableStyleSheet.insertRule(d + "{}", _modifiableStyleSheet.cssRules.length);
            l = _modifiableStyleSheet.cssRules[_modifiableStyleSheet.cssRules.length - 1]
        }
    } if (!l) {
        h();
        return null
    }
    if (!a.width) {
        a.width = ""
    }
    _cssRules[d] = l;
    var g = editCSSStyleText(l, a);
    h();
    return g
}

function getNaturalWidthHeight(b, c) {
    if (!b) {
        return
    }
    var a = new Image();
    a.onload = function() {
        c(a.width, a.height)
    };
    a.src = b
}
var __offscreen;

function getNaturalRect(a, c, b) {
    c = Event.wrapper(c, b);
    __offscreen = __offscreen || document.body.appendChild(createNode("div", {
        className: "offscreen"
    }));
    __offscreen.appendChild(a);
    yield(function() {
        var d = Rect.fromNode(a);
        c(d)
    })
}

function getElementInnerDim(a) {
    return new Dim(a.offsetWidth - depx(getStyle(a, "paddingLeft")) - depx(getStyle(a, "borderLeftWidth")) - depx(getStyle(a, "paddingRight")) - depx(getStyle(a, "borderRightWidth")), a.offsetHeight - depx(getStyle(a, "paddingTop")) - depx(getStyle(a, "borderTopWidth")) - depx(getStyle(a, "paddingBottom")) - depx(getStyle(a, "borderBottomWidth")))
}

function getElementShift(b, a) {
    if (a == "top") {
        return depx(getStyle(b, "paddingTop")) + depx(getStyle(b, "borderTopWidth"))
    } else {
        if (a == "bottom") {
            return depx(getStyle(b, "paddingBottom")) + depx(getStyle(b, "borderBottomWidth"))
        } else {
            if (a == "left") {
                return depx(getStyle(b, "paddingLeft")) + depx(getStyle(b, "borderLeftWidth"))
            } else {
                if (a == "right") {
                    return depx(getStyle(b, "paddingRight")) + depx(getStyle(b, "borderRightWidth"))
                }
            }
        }
    }
    return 0
}

function overlayZIndex(d) {
    var c = 4999990;
    var b = document.body.childNodes || [];
    for (var a = 0; a < b.length; ++a) {
        c = Math.max(c, Math.ceil(parseFloat(getStyle(b[a], "zIndex"))) || 0)
    }
    return (d && Math.ceil(parseFloat(getStyle(d, "zIndex"))) == c) ? c : c + 10
}
if (Browser.isSafari) {
    editCSSRule(".glow", {
        outlineStyle: "auto"
    })
}

function depx(a) {
    if (!a || !a.match(/([\-0-9.]+)px/)) {
        return 0
    } else {
        return Number(RegExp.$1)
    }
}

function buildURL(f, g, j, l) {
    g = g || {};
    var b;
    if (f == "profile" && !j && g.name) {
        b = g.name;
        delete g.name;
        delete g.id
    }
    var c = hashToQueryArray(g);
    var d = Auth.getToken();
    if (d) {
        if (f.indexOf("img-") !== 0) {
            c.push(".tok=" + encodeURIComponent(d))
        }
    }
    var k = Conf.getLocale();
    if (k) {
        if (f.indexOf("img-") !== 0) {
            c.push(".locale=" + encodeURIComponent(k))
        }
    }
    if (b) {
        var a = c.length ? ("?" + c.join("&")) : "";
        return buildVanityURL(b, a, l)
    }
    if (f == "splash") {
        var h = c.length ? ("?" + c.join("&")) : "";
        return buildAbsURL("/" + h, l)
    }
    j = j || "cgi";
    c = "../" + j + "/" + f + (c.length ? ("?" + c.join("&")) : "");
    if (l && l != getProtocol()) {
        return buildAbsURL(c, l)
    } else {
        return c
    }
}

function normalizeURL(a) {
    var d = a;
    var b = "";
    if (isAbsURL(a) && a.length > 8) {
        b = a.substr(0, a.indexOf("/", 8));
        d = a.substr(b.length)
    } else {
        d = a
    }
    var c = d.split("/");
    var f = [];
    c.forEach(function(g) {
        if (g == ".") {
            return
        } else {
            if (g == ".." && f.length > 0) {
                if (f[f.length - 1] === "") {
                    return
                } else {
                    if (f[f.length - 1] == "..") {
                        f.push(g)
                    } else {
                        f.pop()
                    }
                }
            } else {
                f.push(g)
            }
        }
    });
    return b + f.join("/")
}

function _validateCDNImgParams(b, a) {
    return b == "img-set" || b == "img-thing" || b == "img-buddy"
}

function cdn() {
    return "akamai"
}

function buildImgURL(d, b) {
    var a;
    var c = b.size;
    var f = UI.sizeMap[c];
    if (c && f) {
        c = b.size = f.url
    }
    if (_validateCDNImgParams(d, b)) {
        a = buildCDNImgURL(cdn(), d, b)
    }
    return a || buildPolyvoreImgURL(d, b)
}

function hashImgParams(d, h) {
    var g = [d];
    var b = [];
    forEachKey(h, function(j) {
        b.push(j)
    });
    b.sort();
    b.forEach(function(j) {
        g.push(h[j])
    });
    var f = 0;
    g = g.join("");
    for (var c = 0, a = g.length; c < a; c++) {
        f += g.charCodeAt(c)
    }
    return f
}

function buildCDNImgURL(j, b, f) {
    var k, g;
    if (b == "img-set" || b == "img-thing" || b == "img-buddy") {
        if (b == "img-set") {
            f.id = f.spec_uuid;
            delete f.spec_uuid
        }
        g = Conf.getCDNImgHost(j, b, f);
        if (!g) {
            if (b == "img-set") {
                f.spec_uuid = f.id;
                delete f.id
            }
            return ""
        }
        var a = f[".out"];
        delete f[".out"];
        var h = [];
        forEachKey(f, function(m, l) {
            h.push(m)
        });
        h.sort();
        var d = ["/cgi", b];
        for (var c = 0; c < h.length; ++c) {
            d.push(h[c], encodeURIComponent(f[h[c]]))
        }
        k = [d.join("/"), ".", a].join("")
    } else {
        console.log("CDN was not enabled for " + b);
        return ""
    }
    return [getProtocol() + "://", g, k].join("")
}

function buildRsrcURL(b, a) {
    return normalizeURL(Conf.getRsrcUrlPrefix(cdn(), a) + b)
}

function buildAbsURL(k, g, c) {
    if (isAbsURL(k)) {
        return k
    }
    if (!g) {
        g = getProtocol()
    }
    var l;
    if (k.charAt(0) == "/") {
        var h = c ? c : Conf.getWebHost();
        l = g + "://" + h + k
    } else {
        if (!window._dirname) {
            var a = parseUri(window.location);
            var j = a.path.split(/\//);
            j.pop();
            j.push("");
            a.path = j.join("/");
            if (a.path.charAt(0) != "/") {
                a.path = "/" + a.path
            }
            window._dirname = reconstructUri({
                protocol: "",
                authority: a.authority,
                path: a.path
            })
        }
        var f = window._dirname;
        var b = c || window._polyvoreHost;
        if (b) {
            var d = f.match(/(\:\/\/)(.*?)(\/.*)/);
            f = d[1] + b + d[3]
        }
        l = g + f + k
    }
    return normalizeURL(l)
}

function getHostModePrefixes() {
    return ["live", "www", "testenv"]
}

function buildVanityURL(c, h, f) {
    var b = Conf.getCookieDomain();
    h = h || "";
    c = c.toLowerCase();
    c = c.replace(/![a-z0-9\-]/, "");
    var d = c + b;
    if (Conf.getDevName()) {
        d = c + "." + Conf.getDevName() + b
    }
    var g = Conf.getModeName();
    if (getHostModePrefixes().contains(g)) {
        d = g + "." + d
    }
    var a = buildAbsURL("/" + h, f, d);
    return a
}

function isAbsURL(a) {
    return (/^https?:\/\//).test(a)
}

function getProtocol() {
    if (!window._protocol) {
        var a = window.location.href;
        window._protocol = a.match(/^(\w+):/)[1]
    }
    return window._protocol
}

function parseUri(f) {
    var d = parseUri.options,
        a = d.parser[d.strictMode ? "strict" : "loose"].exec(f),
        c = {}, b = 14;
    while (b--) {
        c[d.key[b]] = a[b] || ""
    }
    c[d.q.name] = {};
    c[d.key[12]].replace(d.q.parser, function(h, g, l) {
        if (g) {
            var j = l;
            try {
                if (l) {
                    j = decodeURIComponent(l.replace(/\+/g, "%20"))
                }
            } catch (k) {}
            if (c[d.q.name][g]) {
                if (typeof(c[d.q.name][g]) == "string") {
                    c[d.q.name][g] = [c[d.q.name][g]]
                }
                c[d.q.name][g].push(j)
            } else {
                c[d.q.name][g] = j
            }
        }
    });
    return c
}
parseUri.options = {
    strictMode: false,
    key: ["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"],
    q: {
        name: "queryKey",
        parser: /(?:^|&)([^&=]*)=?([^&]*)/g
    },
    parser: {
        strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
        loose: /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
    }
};

function reconstructUri(d) {
    var f = d.authority;
    if (d.port && f) {
        f = f.replace(/:\d+$/, "")
    }
    var c = [];
    if (d.protocol || f) {
        c.push(d.protocol, "://", f);
        if (d.port) {
            c.push(":", d.port)
        }
    }
    c.push(d.path);
    if (d.queryKey) {
        var b = hashToQueryArray(d.queryKey);
        if (b.length) {
            c.push("?");
            for (var a = 0; a < b.length; ++a) {
                if (a) {
                    c.push("&")
                }
                c.push(b[a])
            }
        }
    }
    if (d.anchor) {
        c.push("#", d.anchor)
    }
    return c.join("")
}

function parsePolyvoreURL(a) {
    var b = parseUri(a);
    var l = b.path.split("/");
    l.shift();
    var c = l.pop();
    var h = {};
    if (c.match(/(?:set|thing|buddy)\.\d+/)) {
        var j = c.split(".");
        c = "img-" + j.shift();
        var k;
        if (c == "img-set") {
            if (j.length > 3) {
                k = ["cid", "spec_uuid", "size", ".out"]
            } else {
                return {
                    action: c,
                    args: {},
                    isStatic: true
                }
            }
        } else {
            if (c == "img-thing") {
                if (j.length > 3) {
                    k = ["tid", "size", "mask", ".out"]
                } else {
                    k = ["tid", "size", ".out"]
                }
            } else {
                if (c == "img-buddy") {
                    k = ["id", "size", ".out"]
                }
            }
        }
        for (var d = 0; d < k.length; d++) {
            h[k[d]] = j[d]
        }
        if (c == "img-thing" && h.mask) {
            h.mask = 1
        }
    } else {
        if (l.length > 1 && c.match(/^.*\.jpg$/i)) {
            return {
                action: l.pop(),
                args: {},
                isStatic: true
            }
        } else {
            h = b.queryKey
        } if (c == "img-set") {
            h.spec_uuid = h.id;
            delete h.id
        }
    } if (!c && isPolyvoreURL(a)) {
        var g = b.host.replace(/\.polyvore\.(net|com)$/, "");
        var f = g.split(".").filter(function(m) {
            return !["www", Conf.getModeName(), Conf.getDevName()].contains(m)
        });
        if (f && f.length >= 1) {
            c = "profile";
            h.name = f[0]
        } else {
            c = "splash"
        }
    }
    return {
        action: c,
        args: h
    }
}

function cleanURL(b) {
    if (!b) {
        return null
    }
    b = b.trim();
    if (!b.match(/^http:\/\//)) {
        b = b.replace(/^\w+:\/\//, "");
        b = "http://" + b
    }
    var a = parseUri(b);
    delete a.port;
    return reconstructUri(a)
}

function fullyQualified(a) {
    a = a || "./";
    if (!/^(([a-z]+):\/\/)/.test(a)) {
        var b = document.createElement("div");
        a = a.replace('"', "%22");
        b.innerHTML = '<a href="' + a + '" style="display:none">x</a>';
        a = b.firstChild.href
    }
    return a
}

function isPolyvoreURL(a) {
    if (!window.POLVYORE_URL) {
        window.POLYVORE_URL = new RegExp("^https?://([^/?]+\\.)?" + Conf.getCookieDomain().replace(/^\./, "").replace(".", "\\."))
    }
    return a.match(POLYVORE_URL) ? true : false
}

function isHTMLMobile() {
    var a = Cookie.get("m");
    return (a == "1" || a == 1)
}

function hashToQueryArray(b) {
    var h = [];
    var c = [];
    var f;
    for (f in b) {
        if (b.hasOwnProperty(f)) {
            h.push(f)
        }
    }
    h = h.sort();
    var a = function(k, l) {
        l.forEach(function(m) {
            c.push(k + "=" + encodeURIComponent(m))
        })
    };
    for (var d = 0; d < h.length; ++d) {
        f = h[d];
        var j = b[f];
        var g = typeof(j);
        if (j !== undefined && g != "function" && j !== null && j !== "") {
            if (g == "object" && j.constructor == Array.prototype.constructor) {
                a(f, j)
            } else {
                c.push(f + "=" + encodeURIComponent(j))
            }
        }
    }
    return c
}

function replaceURL(b, a) {
    if (!window.history.replaceState) {
        return
    }
    b = b || {};
    a = a || [];
    a.forEach(function(f) {
        b[f] = null
    });
    var c = parseUri(window.location);
    var d = false;
    forEachKey(b, function(g, f) {
        if (f === null) {
            if (g in c.queryKey) {
                delete c.queryKey[g];
                d = true
            }
        } else {
            if (c.queryKey[g] !== f) {
                c.queryKey[g] = f;
                d = true
            }
        }
    });
    if (d) {
        history.replaceState(null, null, reconstructUri(c))
    }
}
Form.TYPES = {
    header: function(a) {
        a.nodes = [createNode("h3", null, null, a.value)]
    },
    html: function(a) {
        a.nodes = [a.value]
    },
    spacer: function(a) {
        a.nodes = [createNode("hr")]
    },
    hidden: function(a) {
        a.inputNode = createNode("input", {
            type: "hidden",
            id: a.id,
            name: a.name,
            value: a.value
        })
    },
    text: function(a) {
        a.focusable = a.focusable === undefined ? true : a.focusable;
        a.inputNode = createNode("input", {
            type: "text",
            id: a.id,
            name: a.name,
            value: a.value
        });
        if (a.maxlength && (a.htmllevel || "none") === "none") {
            setNode(a.inputNode, {
                maxlength: a.maxlength
            })
        }
        if (a.readonly && a.value) {
            setNode(a.inputNode, {
                readonly: true
            })
        }
        if (a.tabIndex) {
            a.inputNode.tabIndex = a.tabIndex
        }
        if (a.autocomplete) {
            var d = a.autocomplete.data;
            if (!d && a.autocomplete.action) {
                d = new AjaxDataSource(a.autocomplete.action, {
                    data: a.autocomplete.params || {}
                }, {
                    hideProgress: true
                })
            }
            var c = new AutoComplete(a.inputNode, d, a.autocomplete.options)
        }
        if (a.lowercase) {
            var b = function() {
                var f = a.inputNode.value.toLowerCase();
                if (f !== a.inputNode.value) {
                    a.inputNode.value = f;
                    Event.trigger(a.inputNode, "change")
                }
            };
            Event.addListener(a.inputNode, "blur", b);
            Event.addListener(a.inputNode, "change", b)
        }
        a.nodes = [a.inputNode]
    },
    input_list: function(d) {
        var c = [];
        var h = d.inputs;
        for (var g = 0; g < h.length; g++) {
            var b = h[g];
            Form.TYPES[b.type](b);
            var f = ["type_input_list", b.className];
            if (b.required) {
                f.push("required_input")
            }
            addClass(b.nodes[0], f.join(" "));
            for (var a = 0; a < b.nodes.length; a++) {
                c.push(b.nodes[a])
            }
        }
        d.nodes = c
    },
    integer: function(a) {
        Form.TYPES.text(a)
    },
    url: function(a) {
        Form.TYPES.text(a)
    },
    price: function(a) {
        Form.TYPES.text(a)
    },
    email: function(a) {
        Form.TYPES.text(a)
    },
    date: function(a) {
        Form.TYPES.text(a)
    },
    dateSelect: function(b) {
        function f(n, m, l, j) {
            var h = [];
            for (var k = n; k <= m; k++) {
                h.push(createNode("option", {
                    value: k
                }, null, k))
            }
            if (j) {
                h = h.reverse()
            }
            h.unshift(createNode("option", {
                value: "",
                selected: true
            }, null, l));
            return h
        }
        b.inputNode = createNode("input", {
            type: "hidden",
            name: b.name
        });
        var a = createNode("select", null, null, f(1, 12, loc("Month")));
        var c = createNode("select", null, null, f(1, 31, loc("Day")));
        var d = createNode("select", null, null, f(1900, new Date().getFullYear(), loc("Year"), true));

        function g() {
            b.inputNode.value = "";
            var m = parseInt(d.value, 10);
            var o = parseInt(a.value, 10);
            var j = parseInt(c.value, 10);
            if (m && o) {
                var h = Date.getDaysInMonth(m, o - 1);
                var l = toArray(c.childNodes);
                l.forEach(function(p) {
                    var q = parseInt(p.value, 10);
                    if (q) {
                        if (q <= h) {
                            setNode(p, {
                                disabled: undefined
                            })
                        } else {
                            setNode(p, {
                                disabled: true
                            });
                            if (j === q) {
                                j = 0;
                                c.value = ""
                            }
                        }
                    }
                })
            } else {
                toArray(c.childNodes).forEach(function(p) {
                    setNode(p, {
                        disabled: undefined
                    })
                })
            } if (m && o && j) {
                var k = new Date(m, o - 1, j);
                if (k && k.getFullYear() === m && k.getMonth() === o - 1 && k.getDate() === j) {
                    var n = function(q, p, r) {
                        q = q + "";
                        while (q.length < p) {
                            q = r + q
                        }
                        return q
                    };
                    m = n(m, 4, "0");
                    o = n(o, 2, "0");
                    j = n(j, 2, "0");
                    b.inputNode.value = [m, o, j].join("-");
                    Event.trigger(b.inputNode, "change")
                }
            }
        }
        Event.addListener(a, "change", g);
        Event.addListener(c, "change", g);
        Event.addListener(d, "change", g);
        b.nodes = [b.inputNode, createNode("div", null, null, [a, "&nbsp;", c, "&nbsp;", d])]
    },
    textarea: function(a) {
        a.focusable = a.focusable === undefined ? true : a.focusable;
        a.inputNode = createNode("textarea", {
            id: a.id,
            name: a.name,
            rows: a.rows || 5
        }, null, a.value);
        if (a.maxlength && (a.htmllevel || "none") === "none") {
            setNode(a.inputNode, {
                maxlength: a.maxlength
            })
        }
        if (a.trackKs) {
            var b = new TrackKeyStroke(a.inputNode)
        }
        if (a.highlight) {
            HighlightingTextarea.init(a.inputNode, {
                msg: a.highlight_emptymsg,
                maxHeight: a.highlight_fixedheight ? 0 : null
            })
        }
        a.nodes = [a.inputNode]
    },
    select: function(a) {
        if (a.readonly && a.value) {
            a.inputNode = createNode("input", {
                type: "text",
                id: a.id,
                name: a.name,
                value: a.value,
                readonly: true
            })
        } else {
            a.inputNode = createNode("select", {
                name: a.name,
                id: a.id
            });
            if (a.options) {
                a.options.forEach(function(b) {
                    a.inputNode.appendChild(createNode("option", {
                        value: b.value,
                        selected: (b.value == a.value ? "selected" : null)
                    }, null, b.label))
                })
            }
        }
        a.nodes = [a.inputNode]
    },
    combo: function(a) {
        a.inputNode = createNode("input", {
            type: "hidden",
            id: a.id,
            name: a.name,
            value: a.value
        });
        var b = new ComboDropDown({
            item2display: function(d) {
                for (var c = 0; c < a.options.length; c++) {
                    if (d == a.options[c].value) {
                        return a.options[c].label
                    }
                }
                return d
            },
            display2item: function(d) {
                for (var c = 0; c < a.options.length; c++) {
                    if (d == a.options[c].label) {
                        return a.options[c].value
                    }
                }
                return d
            },
            items: a.options.map(function(c) {
                return c.value
            }),
            textWidth: a.textWidth || 200,
            listHeight: a.listHeight || 400
        });
        Event.addListener(b, "change", function(c) {
            a.inputNode.value = c
        });
        if (a.value === undefined) {
            b.select(a.options[0].value)
        } else {
            b.select(a.value)
        }
        a.nodes = [a.inputNode, b.getNode()]
    },
    username: function(b) {
        Form.TYPES.text(b);
        var c = createNode("span", {
            className: "feedback hidden"
        });
        var a = createNode("div", {
            className: "suggestions"
        });
        Form.instrumentUsernameInput(b, c, a);
        b.nodes = [b.inputNode, c, a]
    },
    password: function(a) {
        a.focusable = a.focusable === undefined ? true : a.focusable;
        a.inputNode = createNode("input", {
            type: "password",
            id: a.id,
            name: a.name,
            value: a.value
        });
        a.nodes = [a.inputNode]
    },
    checkbox: function(b) {
        b.inputNode = createNode("input", {
            type: "checkbox",
            id: b.id,
            name: b.name,
            value: b.value || 1,
            checked: b.checked
        });
        var a = b.cblabel || b.value;
        var c = createNode("label", {
            "for": b.id
        }, null, a);
        b.nodes = [b.inputNode, c]
    },
    checkbox_list: function(a) {
        a._value = a.value || [];
        a.inputNode = createNode("input", {
            type: "hidden",
            id: a.id,
            name: a.name,
            value: JSON2.stringify(a._value)
        });
        var b = createNode("ul", {
            className: "options"
        });
        a.options.forEach(function(d) {
            var c = createNode("input", {
                id: d.id || Dom.uniqueId(a.name),
                type: "checkbox",
                value: d.value,
                checked: a._value.contains(d.value)
            });
            Event.addListener(c, "change", function() {
                if (c.checked) {
                    a._value.push(c.value)
                } else {
                    a._value.remove(c.value)
                }
                a.inputNode.value = JSON2.stringify(a._value);
                Event.trigger(a.inputNode, "change")
            });
            b.appendChild(createNode("li", null, null, [c, createNode("label", {
                "for": c.id
            }, null, d.label)]))
        });
        a.nodes = [a.inputNode, b]
    },
    radio_select: function(a) {
        a.inputNode = createNode("input", {
            type: "hidden",
            id: a.id,
            name: a.name,
            value: a.value
        });
        var d = {};
        var c = {};
        var b = createNode("div", {
            className: "box"
        }, null, [createNode("div", {
            className: "hd"
        }, null, createNode("h3", null, null, a.hd_text)), createNode("ul", {
            className: "select"
        }, null, a.options.map(function(g) {
            var f = g.selected || a.value == g.value;
            var j = createNode("a", null, null, (f ? loc("Selected") : loc("Choose")));
            var h = createNode("li", {
                className: (f ? loc("selected") : "")
            }, null, createNode("ul", null, null, [createNode("li", null, null, createSprite(g.sprite)), createNode("li", null, null, g.label), createNode("li", {
                className: "right"
            }, null, j)]));
            d[g.value] = h;
            c[g.value] = j;
            Event.addListener(h, "click", function() {
                if (a.inputNode.value != g.value) {
                    removeClass(d[a.inputNode.value], "selected");
                    addClass(d[g.value], "selected");
                    c[a.inputNode.value].innerHTML = loc("Choose");
                    c[g.value].innerHTML = loc("Selected");
                    a.inputNode.value = g.value;
                    Event.trigger(a.inputNode, "change")
                }
            });
            return h
        }))]);
        a.nodes = [a.inputNode, b]
    },
    radios: function(a) {
        a.inputNode = createNode("input", {
            type: "hidden",
            id: a.id,
            name: a.name,
            value: a.value
        });
        a.layoutRenderer = a.layoutRenderer || UI.layoutColumns;
        var b = createNode("div", {
            className: "options"
        }, null, [a.layoutRenderer(a.options, {
            columns: a.columns,
            renderer: function(d, c) {
                var f = createNode("input", {
                    id: Dom.uniqueId(a.name),
                    type: "radio",
                    name: a.name + "_radio",
                    value: d.value,
                    checked: d.value == a.value
                });
                Event.addListener(f, "change", function() {
                    if (f.checked) {
                        a.inputNode.value = f.value
                    }
                });
                return [f, createNode("label", {
                    "for": f.id
                }, null, d.label)]
            }
        })]);
        a.nodes = [a.inputNode, b]
    },
    quick_share: function(b) {
        b._value = {
            enabled: b.checked,
            services: (b.services || []).map(function(h) {
                return h.service
            })
        };
        mergeObject(b._value, b.value || {});
        b.inputNode = createNode("input", {
            type: "hidden",
            name: b.name,
            id: b.id,
            value: JSON2.stringify(b._value)
        });
        var f = createNode("div");
        var d = f.appendChild(createNode("input", {
            type: "checkbox",
            name: Dom.uniqueId(b.name),
            id: Dom.uniqueId(b.id),
            value: true,
            checked: b._value.enabled
        }));
        var c = f.appendChild(createNode("label", {
            "for": d.id
        }, null, loc("Quick share with friends")));
        Event.addListener(d, "change", function() {
            b._value.enabled = d.checked;
            if (b._value.enabled) {
                show(a)
            } else {
                hide(a)
            }
            b.inputNode.value = JSON2.stringify(b._value);
            Event.trigger(b.inputNode, "change")
        });
        var g = createNode("input", {
            type: "hidden"
        });
        Event.addListener(g, "change", function() {
            b._value.services = g.value.split(",").filter(function(h) {
                return h
            });
            b.inputNode.value = JSON2.stringify(b._value);
            Event.trigger(b.inputNode, "change")
        });
        var a = Share.createAccountPicker({
            accounts: b.services,
            input: g,
            inDialog: b.inDialog,
            inParent: b.inParent,
            id: "quick_share"
        });
        b.nodes = [b.inputNode, f, g, a]
    },
    extsvc_connect: function(j) {
        var d = j.facebook === undefined ? true : j.facebook;
        var c = j.twitter === undefined ? true : j.twitter;
        var l = j.signin === undefined ? false : j.signin;
        var f = j.done === undefined ? window.location.href : j.done;
        var g = j.page === undefined ? window.polyvore_page_name : j.page;
        var a = j.src === undefined ? "unknown" : j.src;
        var h = j.permissions === undefined ? Facebook.connectPermissions().join(",") : j.permissions;
        j.nodes = [];
        if (d) {
            var b = createNode("a", {
                href: "#",
                className: "btn btn_action facebook_connect"
            }, null, loc("Connect with Facebook"));
            j.nodes.push(b);
            Event.addListener(b, "click", function(m) {
                Facebook.login({
                    doRedirect: true,
                    done: f,
                    permissions: h,
                    data: {
                        page: g,
                        src: a
                    }
                });
                return Event.stop(m)
            })
        }
        if (c) {
            var k = createNode("a", {
                href: "#",
                className: "btn btn_action twitter_connect"
            }, null, loc("Connect with Twitter"));
            j.nodes.push(k);
            Event.addListener(k, "click", function(m) {
                Twitter.login({
                    doRedirect: true,
                    signin: l,
                    data: {
                        page: g,
                        src: a
                    }
                });
                return Event.stop(m)
            })
        }
    },
    buttons: function(a) {
        var b = a.buttons.map(function(c) {
            c.className = c.className || "";
            var d;
            switch (c.type) {
                case "submit":
                    d = createNode("input", {
                        type: c.type,
                        id: c.id,
                        className: c.className || "btn btn_action",
                        name: c.name,
                        value: c.label,
                        disabled: c.disabled
                    });
                    break;
                case "cancel":
                    d = createNode("span", {
                        id: c.id,
                        className: c.className || "clickable"
                    }, null, c.label);
                    break;
                case "link":
                    d = createNode("a", {
                        id: c.id,
                        className: c.className,
                        href: c.url
                    }, null, c.label);
                    break
            }
            if (c.onClick) {
                Event.addListener(d, "click", c.onClick)
            }
            if (c.tabIndex) {
                d.tabIndex = c.tabIndex
            }
            return d
        });
        a.nodes = [createNode("ul", {
            className: "actions horizontal"
        }, null, b.map(function(c) {
            return createNode("li", null, null, c)
        }))]
    },
    set_style_picker: function(b) {
        var c = b.value;
        var a = [createLabel({
            className: "embed_radio embed_details",
            "for": b.id + "embed_details"
        }, null, createCheckboxOrRadio("radio", {
            name: b.name,
            id: b.id + "embed_details",
            value: "details",
            checked: c == "details"
        })), createLabel({
            className: "embed_radio embed_grid",
            "for": b.id + "embed_grid"
        }, null, createCheckboxOrRadio("radio", {
            name: b.name,
            id: b.id + "embed_grid",
            value: "grid",
            checked: c == "grid"
        })), createLabel({
            className: "embed_radio embed_list",
            "for": b.id + "embed_list"
        }, null, createCheckboxOrRadio("radio", {
            name: b.name,
            id: b.id + "embed_list",
            value: "list",
            checked: c == "list"
        })), createLabel({
            className: "embed_radio embed_basic",
            "for": b.id + "embed_basic"
        }, null, createCheckboxOrRadio("radio", {
            name: b.name,
            id: b.id + "embed_basic",
            value: "basic",
            checked: c == "basic" || c === null
        }))];
        b.inputNode = createNode("input", {
            type: "hidden",
            name: b.name + "hidden",
            id: b.id + "hidden",
            value: b.value
        });
        var d = createNode("div", {
            className: "embed_type",
            id: b.id
        }, null, a);
        b.nodes = [b.inputNode, d]
    },
    lookbook_style_picker: function(b) {
        var a = b.value;
        var d = [createLabel({
            "for": b.id + "embed_slideshow"
        }, null, [createCheckboxOrRadio("radio", {
            name: b.name,
            id: b.id + "embed_slideshow",
            value: "slideshow",
            checked: a == "slideshow"
        }), createNode("span", {
            className: "embed_radio embed_slideshow"
        }, null, loc("Slideshow"))]), createLabel({
            "for": b.id + "embed_carousel"
        }, null, [createCheckboxOrRadio("radio", {
            name: b.name,
            id: b.id + "embed_carousel",
            value: "carousel",
            checked: a == "carousel"
        }), createNode("span", {
            className: "embed_radio embed_carousel"
        }, null, loc("Carousel"))]), createLabel({
            "for": b.id + "embed_grid"
        }, null, [createCheckboxOrRadio("radio", {
            name: b.name,
            id: b.id + "embed_grid",
            value: "grid",
            checked: a == "grid"
        }), createNode("span", {
            className: "embed_radio embed_lb_grid"
        }, null, loc("Image grid"))])];
        b.inputNode = createNode("input", {
            type: "hidden",
            name: b.name + "hidden",
            id: b.id + "hidden",
            value: b.value
        });
        var c = createNode("div", {
            className: "embed_lb_type",
            id: b.id
        }, null, d);
        b.nodes = [b.inputNode, c]
    },
    item_picker: function(b) {
        var c = createNode("div");
        var a = Share.createItemPicker(b.items, c, "", b.basedon_tid);
        b.nodes = [a.itemInput, c]
    },
    size_picker: function(b) {
        var d = createNode("div", {
            className: "selectoption"
        });
        var a = createInput("hidden", {
            value: b.value,
            name: b.name
        });
        var c = {
            sizes: ["l", "e", "x", "y"]
        };
        if (b.itemType === "collection") {
            c.showHeight = true;
            c.disableWidth = false;
            c.disableHeight = true;
            c.custom = {
                showWidth: true,
                showHeight: true,
                defaultValue: "c600x600"
            }
        } else {
            if (b.itemType === "lookbook") {
                c.disableWidth = false;
                c.disableHeight = true;
                c.custom = {
                    showWidth: true,
                    showHeight: false,
                    defaultValue: "c600x600"
                }
            }
        }
        c.aspectRatio = b.aspectRatio;
        c.makeSquare = b.makeSquare;
        SizePicker.add(d, a, c);
        b.nodes = [a, d]
    }
};
Form.DEFAULTS = {
    integer: function(a) {
        a.validators.push(Validate.integer)
    },
    url: function(a) {
        a.validators.push(Validate.url)
    },
    price: function(a) {
        a.validators.push(Validate.price)
    },
    email: function(a) {
        a.minlength = 5;
        a.maxlength = 255;
        a.lowercase = true;
        a.validators.push(Validate.email)
    },
    username: function(a) {
        a.minlength = 4;
        a.maxlength = 32;
        a.lowercase = true;
        a.validators.push(Validate.userName)
    }
};
Form.createInputNode = function(a) {
    a.rowId = a.row_id;
    a.errorId = a.error_id;
    if (a.input_id) {
        a.inputNode = $(a.input_id);
        if (!a.inputNode) {
            throw "input node not found: " + a.input_id
        }
    }
    if (a.error_id) {
        a.errorNode = $(a.error_id);
        if (!a.errorNode) {
            throw "error node not found: " + a.error_id
        }
    }
    a.validators = a.js_validators.map(function(c) {
        if (typeof(c) === "function") {
            return c
        }
        var b = c.replace(/^Validate\./, "");
        var d = Validate[b];
        if (!d) {
            throw "validator not supported: " + c
        }
        return d
    });
    if (a.type == "username") {
        Form.instrumentUsernameInput(a, $(a.feedback_id), $(a.suggestions_id))
    }
};
Form.attachToHTMLForm = function(a) {
    Event.addSingleUseListener(document, "modifiable", function() {
        var c = $(a.id);
        if (!c) {
            throw "form not found: " + a.id
        }
        a.inputs.forEach(function(d) {
            if (d.type == "input_list" && d.inputs) {
                d.inputs.forEach(function(f) {
                    Form.createInputNode(f)
                })
            } else {
                Form.createInputNode(d)
            }
        });
        var b = new Form(a);
        b._formNode = c;
        b.addListeners();
        c._form = b
    })
};
Form.instrumentUsernameInput = function(b, d, a) {
    var f = b.inputNode;
    var c = new CheckAvailability(f, d, a);
    b.validators.push(Validate.userNameAvailable(c))
};
Form.fromNode = function(a) {
    if (!a) {
        return null
    }
    if (a.form) {
        a = a.form
    }
    if (a._form && a._form instanceof Form) {
        return a._form
    }
    return null
};

function Form(a) {
    a = a || {};
    this._id = a.id || Dom.uniqueId("form");
    this._className = a.className || "";
    this._action = a.action || "";
    this._method = a.method || "POST";
    this._inputs = a.inputs || [];
    this._confirm = a.confirm;
    this._allinputs = [];
    var b = a.data || {};
    if (a.onSubmit) {
        Event.addListener(this, "submit", a.onSubmit)
    }
    this._inputs.forEach(function(c) {
        c.validators = c.validators || [];
        var d = Form.DEFAULTS[c.type];
        if (d) {
            d(c)
        }
        c.id = c.id || Dom.uniqueId(this._id + "_input");
        c.rowId = c.rowId || Dom.uniqueId(this._id + "_row");
        c.errorId = c.errorId || Dom.uniqueId(this._id + "_error");
        c.value = b[c.name] || c.value;
        c.checked = b[c.name] || c.checked || c.defaultChecked || false;
        if (c.maxlength) {
            c.validators.unshift(Validate.maxlength)
        }
        if (c.minlength) {
            c.validators.unshift(Validate.minlength)
        }
        if (c.required) {
            c.validators.unshift(Validate.required)
        }
    }, this);
    this._inputs.forEach(function(c) {
        if (c.type == "input_list" && c.inputs) {
            for (var d = 0; d < c.inputs.length; d++) {
                var f = c.inputs[d];
                f.validators = f.validators || [];
                var g = Form.DEFAULTS[f.type];
                if (g) {
                    g(f)
                }
                f.id = f.id || Dom.uniqueId(this._id + "_input");
                f.rowId = f.rowId || Dom.uniqueId(this._id + "_row");
                f.errorId = c.errorId;
                f.value = f.value;
                f.checked = f.checked || f.defaultChecked || false;
                if (f.maxlength) {
                    f.validators.unshift(Validate.maxlength)
                }
                if (f.minlength) {
                    f.validators.unshift(Validate.minlength)
                }
                if (f.required) {
                    f.validators.unshift(Validate.required)
                }
                this._allinputs.push(f)
            }
        } else {
            this._allinputs.push(c)
        }
    }, this)
}
Form.prototype.requiresXsrf = function() {
    return Auth.isLoggedIn() && this._method === "POST" && (!isAbsURL(this._action) || isPolyvoreURL(this._action))
};
Form.prototype.getNode = function() {
    if (this._formNode) {
        return this._formNode
    }
    var b = createNode("form", {
        enctype: "multipart/form-data",
        id: this._id,
        action: this._action,
        method: this._method,
        className: "newform " + this._className
    });
    this._formNode = b;
    if (this.requiresXsrf()) {
        b.appendChild(createNode("input", {
            type: "hidden",
            name: ".xsrf",
            value: Auth.getToken()
        }))
    }
    var a = b.appendChild(createNode("div", {
        className: "hidden_inputs"
    }));
    var c = false;
    this._inputs.forEach(function(f) {
        if (!Form.TYPES[f.type]) {
            throw "invalie input type: " + f.type
        }
        Form.TYPES[f.type](f);
        if (f.type === "hidden") {
            a.appendChild(f.inputNode);
            return
        }
        var h = ["input", "type_" + f.type, f.className];
        if (f.required) {
            h.push("required_input")
        }
        var k = b.appendChild(createNode("div", {
            className: h.join(" "),
            id: f.rowId
        }));
        if (f.label) {
            if (f.inputNode) {
                k.appendChild(createNode("label", {
                    "for": f.inputNode.id
                }, null, f.label))
            } else {
                k.appendChild(createNode("div", {
                    className: "label"
                }, null, f.label))
            }
        }
        k.appendChild(createNode("div", {
            className: "value"
        }, null, f.nodes));
        if (f.hint) {
            k.appendChild(createNode("div", {
                className: "meta input_hint"
            }, null, f.hint))
        }
        if (f.inputNode) {
            f.errorNode = k.appendChild(createNode("ul", {
                id: f.errorId,
                className: "error"
            }))
        } else {
            if (f.type == "input_list" && f.inputs) {
                for (var g = 0; g < f.inputs.length; g++) {
                    f.inputs[g].errorNode = k.appendChild(createNode("ul", {
                        id: f.errorId,
                        className: "error"
                    }))
                }
            }
        } if (f.placeholder) {
            InputHint.add(f.inputNode, f.placeholder, this._formNode)
        } else {
            if (f.type == "input_list" && f.inputs) {
                for (var d = 0; d < f.inputs.length; d++) {
                    var j = f.inputs[d];
                    if (j.placeholder) {
                        InputHint.add(j.inputNode, j.placeholder, this._formNode)
                    }
                }
            }
        }
    }, this);
    this.addListeners();
    return this._formNode
};
Form.prototype.addListeners = function() {
    if (this._listenersAdded) {
        return
    }
    this._listenersAdded = true;
    Event.addListener(this._formNode, "submit", function(c) {
        return this.submit(c)
    }, this);
    if (this._confirm) {
        Event.addListener(this._formNode, "click", function(c) {
            if (!c) {
                return
            }
            var d = Event.getSource(c);
            if (d.tagName == "INPUT" && d.type == "submit") {
                this._submitButton = d
            }
            return true
        }, this)
    }
    this._validationQueueEnabled = false;
    this._validationQueue = [];
    var b = Event.wrapper(function() {
        return Event.addListener(document, "mousedown", function() {
            this._validationQueueEnabled = true;
            Event.addSingleUseListener(document, "mouseup", function() {
                this._validationQueueEnabled = false;
                this.triggerQueuedValidation()
            }, this)
        }, this)
    }, this);
    var a = false;
    this._allinputs.forEach(function(c) {
        if (c.inputNode) {
            if (c.onChange) {
                Event.addListener(c.inputNode, "change", c.onChange, c.inputNode)
            }
            Event.addListener(c.inputNode, "focus", function() {
                var g = b();
                Event.addSingleUseListener(c.inputNode, "blur", function() {
                    g.clean()
                })
            });
            if (!a && c.focusable && c.inputNode.focus && !c.value) {
                yield(function() {
                    c.inputNode.focus()
                });
                a = true
            }
            var d = function() {
                yield(function() {
                    this.validateInput(c, true)
                }, this)
            };
            var f = function() {
                yield(function() {
                    this.validateInput(c, false)
                }, this)
            };
            Event.addListener(c.inputNode, "blur", f, this);
            Event.addListener(c.inputNode, "change", f, this);
            Event.addListener(c.inputNode, "keypress", d, this)
        }
    }, this)
};
Form.prototype.validateInputNode = function(c, b) {
    var a;
    this._allinputs.forEach(function(d) {
        if (!a && d.inputNode == c) {
            a = d
        }
    });
    if (!a) {
        return false
    }
    return this.validateInput(a, b)
};
Form.prototype.validateInput = function(c, b) {
    if (this._validationQueueEnabled) {
        this._validationQueue.push(arguments);
        return false
    }
    c.isValid = true;
    var h = [];
    var g = Form.getInputValue(c);
    if (g !== undefined) {
        for (var f = 0; f < c.validators.length; f++) {
            var d = c.validators[f];
            var a = d(g, c, b);
            c.isValid = c.isValid && a.valid;
            if (!a.valid) {
                if (a.msg) {
                    h.push(a.msg)
                }
                break
            }
        }
    }
    if (c.errorNode) {
        setNode(c.errorNode, null, null, h.map(function(j) {
            return createNode("li", null, null, j)
        }))
    }
    return c.isValid
};
Form.prototype.triggerQueuedValidation = function() {
    var b = true;
    while (this._validationQueue.length > 0) {
        var a = this._validationQueue.pop();
        b = b && Form.prototype.validateInput.apply(this, a)
    }
    return b
};
Form.prototype.validate = function() {
    var a = true;
    this._allinputs.forEach(function(b) {
        a = this.validateInput(b) && a
    }, this);
    return a
};
Form.getInputValue = function(a) {
    if (a._value !== undefined) {
        return cloneObject(a._value, true)
    }
    if (a.inputNode) {
        return inputValue(a.inputNode)
    }
};
Form.prototype.getData = function() {
    var a = {};
    this._allinputs.forEach(function(b) {
        if (b.name) {
            a[b.name] = Form.getInputValue(b)
        }
    });
    return a
};
Form.prototype.submit = function(g) {
    if (!this.validate()) {
        Event.trigger(this, "error");
        return g ? Event.stop(g) : false
    } else {
        if (this._confirm) {
            var f = this._formNode;
            var b = this._confirm.message;
            if (typeof(b) == "function") {
                b = b(f)
            }
            var d = this._submitButton;
            if (!d) {
                for (var c = 0; c < f.length; c++) {
                    var a = f[c];
                    if (a.tagName == "INPUT" && a.type == "submit") {
                        d = a;
                        break
                    }
                }
            }
            ModalDialog.confirm({
                title: b,
                okLabel: this._confirm.okLabel,
                onOk: Event.wrapper(function() {
                    if (d) {
                        f.appendChild(createNode("input", {
                            type: "hidden",
                            name: d.name,
                            value: d.value
                        }))
                    }
                    f.submit();
                    Event.trigger(this, "submit", g)
                }, this)
            });
            return g ? Event.stop(g) : false
        }
        Event.trigger(this, "submit", g)
    }
    return true
};
Form.prototype.clean = function() {
    Event.release(this);
    purge(this._formNode, true)
};

function createForm(d) {
    d.id = d.id || Dom.uniqueId("form");
    var c = createNode("form", {
        id: d.id,
        enctype: "multipart/form-data"
    });
    if (d.action) {
        c.action = d.action
    }
    if (d.method) {
        c.method = d.method
    }
    var f = d.onSubmit ? d.onSubmit : Event.stop;
    Event.addListener(c, "submit", f);
    var b = createFormBody(c, d);
    var a = createNode("div", {
        className: "fieldset stdform"
    });
    c.appendChild(a);
    a.appendChild(b);
    return c
}

function createFormBody(a, l) {
    var g = l.inputs;
    var d = l.data;
    var c = l.noLabel;
    var j = l.id || a.getAttribute("id");
    if (d) {
        g.forEach(function(m) {
            if (!m || !m.name) {
                return
            }
            if (m.type == "twitter") {
                m.checked = d.twitter_post ? true : null;
                m.msg = d.twitter_msg;
                return
            }
            if (d[m.name] === undefined) {
                m.value = m.value || ""
            } else {
                if (m.type == "checkbox") {
                    m.checked = d[m.name] ? true : false
                } else {
                    m.value = d[m.name]
                }
            } if (!m.options || (d[m.name] && !d[m.name].contains)) {
                return
            }
            if (m.type == "checkboxes") {
                m.options.forEach(function(n) {
                    n.checked = (d[m.name] && d[m.name].contains(n.value)) ? true : null
                })
            }
        })
    }
    var k = createNode("table", {
        cellspacing: 0,
        cellpadding: 0
    });
    var f = createNode("tbody");
    k.appendChild(f);
    var h = createNode("div", null, null, k);
    hide(h);
    document.body.appendChild(h);
    var b = function(m) {
        if (m && m.type != "hidden") {
            Event.addListener(document, "modifiable", function() {
                yield(function() {
                    m.focus()
                })
            });
            b = noop
        }
    };
    g.forEach(function(al) {
        if (!al) {
            return
        }
        if (!al.id) {
            al.id = "input" + getUID(al)
        }
        var ah = createNode("label", {
            "for": al.id
        }, null, al.label);
        if (al.required) {
            ah.appendChild(createNode("span", {
                className: "required"
            }, null, "*"))
        }
        var G = {
            type: al.validate,
            minlength: al.minlength,
            maxlength: al.maxlength,
            htmllevel: al.htmllevel
        };
        if (Browser.isIE) {
            ah = createNode("span", null, null, ah);
            ah = createNode("span", null, null, ah.innerHTML)
        }
        var ad = al.list ? toList(al.value).join(", ") : (al.value || "");
        var A, p, ak, ab;
        var q = false;
        switch (al.type) {
            case "header":
                f.appendChild(createNode("tr", null, null, createNode("td", {
                    colSpan: 2
                }, null, createNode("h5", null, null, al.value))));
                return;
            case "subheader":
                A = f.appendChild(createNode("tr"));
                A.appendChild(createNode("td", {
                    className: "label"
                }, null, ""));
                A.appendChild(createNode("td", {
                    className: "value"
                }, null, createNode("h4", null, null, al.value)));
                return;
            case "spacer":
                f.appendChild(createNode("tr", {
                    className: al.rowClassName,
                    id: al.rowId
                }, null, createNode("td", {
                    colSpan: 2,
                    className: "spacer"
                }, null, "&nbsp")));
                return;
            case "rofullspan":
                f.appendChild(createNode("tr", null, null, createNode("td", {
                    colSpan: 2,
                    className: "rofullspan"
                }, null, al.value)));
                return;
            case "rotext":
                p = createNode("span", {
                    id: al.id,
                    className: al.className
                }, null, al.value);
                break;
            case "roimg":
                p = createNode("img", {
                    src: al.src,
                    width: al.width,
                    height: al.height
                });
                break;
            case "tip":
                A = f.appendChild(createNode("tr"));
                A.appendChild(createNode("td", {
                    className: "label"
                }, null, ""));
                A.appendChild(createNode("td", {
                    className: "value"
                }, null, createNode("span", {
                    className: "tip"
                }, null, al.value)));
                return;
            case "file":
                p = createNode("input", {
                    name: al.name,
                    id: al.id,
                    type: "file"
                });
                break;
            case "hidden":
                a.appendChild(createNode("input", {
                    name: al.name,
                    id: al.id,
                    type: "hidden",
                    value: ad
                }));
                return;
            case "user":
                p = UI.renderPerson(ad, {
                    noLink: true
                });
                p.appendChild(createNode("input", {
                    name: al.name,
                    id: al.id,
                    type: "hidden",
                    value: ad.user_id
                }));
                break;
            case "username":
                var u = createNode("input", {
                    name: al.name,
                    id: al.id,
                    type: "text",
                    value: decodeHtml(ad)
                });
                b(u);
                var T = createNode("span");
                p = createNode("span", {
                    className: "username_status"
                }, null, [u, T]);
                var U = new CheckAvailability(u, T, al.placeholder, al.available);
                G.type = G.type || "USERNAME";

                function Z() {
                    u.value = u.value.toLowerCase()
                }
                Event.addListener(u, "blur", Z);
                Event.addListener(u, "change", Z);
                break;
            case "select":
                p = createNode("select", {
                    name: al.name,
                    id: al.id
                });
                for (ak = 0; ak < al.options.length; ++ak) {
                    ab = al.options[ak];
                    var ar;
                    var at;
                    if (typeof(ab) == "object") {
                        ar = ab.value;
                        at = ab.label
                    } else {
                        ar = ab;
                        at = ab
                    }
                    p.appendChild(createNode("option", {
                        value: ar,
                        selected: (ar == ad ? "selected" : null)
                    }, null, at))
                }
                break;
            case "text":
            case "url":
            case "price":
                var n = {
                    name: al.name,
                    id: al.id,
                    type: "text",
                    value: decodeHtml(ad)
                };
                if (al.maxlength && (al.htmllevel || "none") === "none") {
                    n.maxlength = al.maxlength
                }
                p = createNode("input", n);
                b(p);
                if (!G.type) {
                    switch (al.type) {
                        case "text":
                            G.type = "TEXT";
                            break;
                        case "price":
                            G.type = "PRICE";
                            break;
                        case "url":
                            G.type = "URL";
                            break
                    }
                }
                break;
            case "email":
                p = createNode("input", {
                    name: al.name,
                    id: al.id,
                    type: "text",
                    value: decodeHtml(ad),
                    maxlength: 255
                });
                b(p);
                G.type = G.type || "EMAIL";
                break;
            case "textarea":
                p = createNode("textarea", {
                    name: al.name,
                    id: al.id,
                    rows: al.rows || 5
                }, null, "");
                b(p);
                p.value = decodeHtml(ad);
                if (al.trackKs) {
                    var J = new TrackKeyStroke(p)
                }
                if (al.highlight) {
                    yield(function() {
                        HighlightingTextarea.init(p, {
                            msg: al.highlight_emptymsg,
                            maxHeight: al.highlight_fixedheight ? 0 : null
                        })
                    })
                }
                q = true;
                G.type = G.type || "TEXTAREA";
                break;
            case "password":
                p = createNode("input", {
                    name: al.name,
                    id: al.id,
                    type: "password",
                    value: decodeHtml(ad)
                });
                b(p);
                if (al.verify) {
                    var S = p;
                    var r = createNode("span");
                    p = createNode("span", null, null, [p, r]);
                    delayed(function() {
                        var v = new VerifyPassword($(al.verify), S, r)
                    }, 0)()
                }
                break;
            case "copypaste":
                p = createNode("span", {
                    className: "copypaste_holder"
                }, null, createCopyPaste(al, ad));
                break;
            case "checkbox":
                if (!al.checkboxes && al.name) {
                    al.checkboxes = [{
                        id: al.id,
                        name: al.name,
                        checked: al.checked,
                        label: al.label,
                        disabled: al.disabled,
                        defaultChecked: al.defaultChecked
                    }];
                    al.id = null
                }
                al.id = al.id || Dom.uniqueId("checkbox");
                var D = [];
                al.checkboxes.forEach(function(v) {
                    var au;
                    if (v.checked !== undefined) {
                        au = v.checked
                    } else {
                        if (d[v.name] !== undefined) {
                            au = !! d[v.name]
                        } else {
                            au = v.defaultChecked
                        }
                    }
                    var av = v.id || Dom.uniqueId("checkbox_" + v.name);
                    D.push(createNode("span", {
                        className: "checkbox"
                    }, null, [createCheckboxOrRadio("checkbox", {
                        id: av,
                        name: v.name,
                        value: 1,
                        checked: au,
                        disabled: v.disabled
                    }), createLabel({
                        "for": av
                    }, null, v.label)]))
                });
                var B = al.cols || 1;
                if (D.length > 1) {
                    if (al.cols !== undefined) {
                        var s = [];
                        for (var ap = 0; ap < D.length + B - 1; ap += B) {
                            var Y = [];
                            for (var aj = 0; aj < B; aj++) {
                                Y.push(createNode("td", null, null, (ap + aj < D.length) ? D[ap + aj] : ""))
                            }
                            s.push(createNode("tr", null, null, Y))
                        }
                        p = createNode("table", {
                            cellspacing: 0,
                            cellpadding: 0,
                            className: "checkboxes"
                        }, null, [createNode("tbody", null, null, s)])
                    } else {
                        p = createNode("div", {
                            id: al.id,
                            className: "checkboxes horizontal"
                        }, null, D)
                    }
                } else {
                    p = D[0]
                } if (al.sidelabel) {
                    ah = createNode("label", {
                        "for": al.id
                    }, null, al.sidelabel)
                } else {
                    ah = createNode("label")
                }
                q = ((D.length / B) > 1);
                break;
            case "checkboxes":
                p = createNode("div", {
                    id: al.id,
                    className: "checkboxes"
                });
                al.options.forEach(function(v) {
                    v.id = v.id || Dom.uniqueId("checkbox_" + al.name);
                    v.name = al.name;
                    v.list = true;
                    p.appendChild(createNode("div", {
                        className: "checkbox"
                    }, null, [createCheckboxOrRadio("checkbox", v), createLabel({
                        "for": v.id
                    }, null, v.label)]))
                });
                break;
            case "radios":
                p = createNode("div", {
                    id: al.id,
                    className: "radios"
                });
                al.options.forEach(function(v) {
                    v.id = Dom.uniqueId("radio_" + al.name);
                    v.name = al.name;
                    v.enabled = true;
                    v.checked = ad && v.value && (v.value == ad);
                    p.appendChild(createNode("div", {
                        className: "radio"
                    }, null, [createCheckboxOrRadio("radio", v), createLabel({
                        "for": v.id
                    }, null, v.label)]))
                });
                break;
            case "buttons":
                var P = al.buttons.map(function(v) {
                    var au = v.type;
                    switch (au) {
                        case "submit":
                            p = createNode("input", {
                                type: "submit",
                                value: v.label,
                                disabled: v.disabled,
                                id: v.id
                            });
                            b(p);
                            break;
                        case "button":
                            p = createNode("input", {
                                type: "button",
                                value: v.label,
                                disabled: v.disabled,
                                id: v.id
                            });
                            b(p);
                            Event.addListener(p, "click", v.onClick);
                            break;
                        case "cancel":
                            p = createNode("span", {
                                className: "clickable cancel",
                                id: v.id
                            }, null, v.label);
                            Event.addListener(p, "click", v.onClick);
                            break;
                        case "link":
                            p = createNode("span", {
                                className: "clickable",
                                id: v.id
                            }, null, v.label);
                            Event.addListener(p, "click", v.onClick);
                            break;
                        default:
                            throw "Invalid button type: " + au
                    }
                    if (v.className) {
                        addClass(p, v.className)
                    }
                    return p
                });
                p = createNode("span", {
                    className: "button_list"
                }, null, P);
                if (al.centered) {
                    f.appendChild(createNode("tr", null, null, createNode("td", {
                        colSpan: 2,
                        align: "center"
                    }, null, p)));
                    return
                }
                break;
            case "agecheck":
                function aq(ay, ax, aw, au) {
                    var v = [];
                    for (var av = ay; av <= ax; av++) {
                        v.push(createNode("option", {
                            value: av,
                            selected: av == aw ? true : null
                        }, null, av))
                    }
                    if (au) {
                        v = v.reverse()
                    }
                    v.unshift(createNode("option", {
                        value: "",
                        selected: "" === aw ? true : null
                    }, null, "---"));
                    return v
                }
                ad = ad || new Date();
                var ae = (new Date()).getFullYear();
                p = createNode("div", null, null, [createNode("select", {
                    name: al.name + "_m"
                }, null, aq(1, 12, "")), "&nbsp;", createNode("select", {
                    name: al.name + "_d"
                }, null, aq(1, 31, "")), "&nbsp;", createNode("select", {
                    name: al.name + "_y"
                }, null, aq(1900, ae, "", true))]);
                break;
            case "fbconnect":
                p = createNode("div", {
                    className: "btn btn_action btn_connect"
                }, null, loc("Connect with Facebook"));
                Event.addListener(p, "click", function() {
                    ModalDialog.hide();
                    Track.stat("inc", "facebook", ["connect_start", "signin"]);
                    if (al.track) {
                        var v = ["connect_start"].concat(al.track);
                        Track.stat("inc", "regtests", v)
                    }
                    Facebook.login({
                        doRedirect: false,
                        onSuccess: al.onSuccess,
                        permissions: al.permissions,
                        track: al.track
                    })
                });
                break;
            case "strip_selector":
                p = createNode("div");
                var N = p.appendChild(createNode("input", {
                    type: "hidden",
                    name: al.name,
                    id: al.id
                }));
                StripSelector.show(al.renderer, {
                    container: p,
                    input: N,
                    source: al.source,
                    items: al.items,
                    value: al.value
                });
                q = true;
                break;
            case "calendar":
                var ao;
                if (al.date) {
                    ao = al.date
                } else {
                    if (ad) {
                        ao = new Date(ad)
                    } else {
                        ao = new Date()
                    }
                }
                p = createNode("div", null, null, ao.toLocaleString());
                var L = a.appendChild(createNode("input", {
                    name: al.name,
                    id: al.id,
                    type: "hidden",
                    value: ao.getTime()
                }));
                var aa = new CalendarAndTime();
                aa.setDate(ao);
                Event.addListener(aa, "select", function(v) {
                    aa.setDate(v);
                    setNode(p, null, null, v.toLocaleString());
                    L.value = v.getTime()
                });
                Event.addListener(p, "click", function(v) {
                    aa.show(v)
                });
                break;
            case "set_style_picker":
                var C = ad;
                var m = [createLabel({
                    className: "embed_radio embed_details",
                    "for": al.id + "embed_details"
                }, null, createCheckboxOrRadio("radio", {
                    name: al.name,
                    id: al.id + "embed_details",
                    value: "details",
                    checked: C == "details"
                })), createLabel({
                    className: "embed_radio embed_grid",
                    "for": al.id + "embed_grid"
                }, null, createCheckboxOrRadio("radio", {
                    name: al.name,
                    id: al.id + "embed_grid",
                    value: "grid",
                    checked: C == "grid"
                })), createLabel({
                    className: "embed_radio embed_list",
                    "for": al.id + "embed_list"
                }, null, createCheckboxOrRadio("radio", {
                    name: al.name,
                    id: al.id + "embed_list",
                    value: "list",
                    checked: C == "list"
                })), createLabel({
                    className: "embed_radio embed_basic",
                    "for": al.id + "embed_basic"
                }, null, createCheckboxOrRadio("radio", {
                    name: al.name,
                    id: al.id + "embed_basic",
                    value: "basic",
                    checked: C == "basic" || C === null
                }))];
                p = createNode("div", {
                    className: "embed_type",
                    id: al.id
                }, null, m);
                break;
            case "lookbook_style_picker":
                var V = ad;
                var x = [createLabel({
                    "for": al.id + "embed_slideshow"
                }, null, [createCheckboxOrRadio("radio", {
                    name: al.name,
                    id: al.id + "embed_slideshow",
                    value: "slideshow",
                    checked: V == "slideshow"
                }), createNode("span", {
                    className: "embed_radio embed_slideshow"
                }, null, loc("Slideshow"))]), createLabel({
                    "for": al.id + "embed_carousel"
                }, null, [createCheckboxOrRadio("radio", {
                    name: al.name,
                    id: al.id + "embed_carousel",
                    value: "carousel",
                    checked: V == "carousel"
                }), createNode("span", {
                    className: "embed_radio embed_carousel"
                }, null, loc("Carousel"))]), createLabel({
                    "for": al.id + "embed_grid"
                }, null, [createCheckboxOrRadio("radio", {
                    name: al.name,
                    id: al.id + "embed_grid",
                    value: "grid",
                    checked: V == "grid"
                }), createNode("span", {
                    className: "embed_radio embed_lb_grid"
                }, null, loc("Image grid"))])];
                p = createNode("div", {
                    className: "embed_lb_type",
                    id: al.id
                }, null, x);
                break;
            case "size_picker":
                p = createNode("div", {
                    className: "selectoption"
                });
                var E = a.appendChild(createInput("hidden", {
                    value: ad,
                    name: al.name
                }));
                var ag = {
                    sizes: ["l", "e", "x", "y"]
                };
                if (al.itemType === "collection") {
                    ag.showHeight = true;
                    ag.disableWidth = false;
                    ag.disableHeight = true;
                    ag.custom = {
                        showWidth: true,
                        showHeight: true,
                        defaultValue: "c600x600"
                    }
                } else {
                    if (al.itemType === "lookbook") {
                        ag.disableWidth = false;
                        ag.disableHeight = true;
                        ag.custom = {
                            showWidth: true,
                            showHeight: false,
                            defaultValue: "c600x600"
                        }
                    }
                }
                ag.aspectRatio = al.aspectRatio;
                ag.makeSquare = al.makeSquare;
                SizePicker.add(p, E, ag);
                break;
            case "item_picker":
                p = createNode("div");
                Share.createItemPicker(al.items, p, "", al.basedon_tid);
                break;
            case "quick_share":
                var H = createInput("hidden", {
                    value: ad,
                    name: al.listName
                });
                var an = createCheckboxOrRadio("checkbox", {
                    name: al.name,
                    id: al.id + "on",
                    value: "on",
                    checked: al.checked
                });
                var w = createLabel({
                    "for": al.id + "on"
                }, null, loc("Quick share with friends"));
                var M = createNode("span", null, null, [an, w]);
                var af = Share.createAccountPicker({
                    accounts: al.services,
                    input: H,
                    inDialog: al.inDialog,
                    inParent: al.inParent,
                    id: "quick_share"
                });
                var am = function() {
                    if (an.checked) {
                        show(af)
                    } else {
                        hide(af)
                    }
                };
                Event.addListener(H, "change", function() {
                    an.checked = true;
                    if (!H.value.length) {
                        hide(M)
                    } else {
                        showInline(M)
                    }
                    am()
                });
                Event.addListener(an, "change", am);
                am();
                p = createNode("div", {
                    className: "quickshare"
                }, null, [M, af, H]);
                break;
            case "quick_share_compact":
                var K = createInput("hidden", {
                    value: ad,
                    name: al.listName
                });
                var O = createCheckboxOrRadio("checkbox", {
                    name: al.name,
                    id: al.id + "on",
                    value: "on",
                    checked: al.checked
                });
                var t = Share.createAccountPicker({
                    accounts: al.services,
                    input: K,
                    inDialog: al.inDialog,
                    inParent: al.inParent,
                    checkbox: O,
                    accountListClass: " ",
                    id: "quick_share_compact"
                });
                p = createNode("div", {
                    className: "quickshare"
                }, null, [t, K]);
                break;
            default:
                throw "invalid type: " + al.type
        }
        if (al.list) {
            setNode(p, {
                list: al.list
            })
        }
        if (q) {
            al.rowClassName = al.rowClassName ? al.rowClassName + " form_multiline" : "form_multiline"
        }
        A = f.appendChild(createNode("tr", {
            id: al.rowId,
            className: al.rowClassName
        }));
        if (c) {
            A.appendChild(createNode("td", {
                className: "value nolabel"
            }, null, p))
        } else {
            var F = "label";
            A.appendChild(createNode("td", {
                className: "label"
            }, null, ah));
            A.appendChild(createNode("td", {
                className: "value"
            }, null, p))
        } if (al.placeholder) {
            var Q = f.appendChild(createNode("tr"));
            Q.appendChild(createNode("td", {
                className: "placeholder"
            }));
            Q.appendChild(createNode("td", {
                className: "placeholder",
                id: al.placeholder
            }))
        }
        var R = createNode("tr");
        var o;
        if (al.error || G.type) {
            var ai = {};
            if (!al.error) {
                ai.display = "none"
            }
            var X = "validate_" + al.name + "_error";
            o = createNode("tr", {
                id: X
            }, ai);
            o.appendChild(createNode("td"));
            o.appendChild(createNode("td", {
                className: "error"
            }, null, al.error));
            f.appendChild(o);
            if (G.type && G.type.toLowerCase() != "none") {
                var W = G.type;
                delete G.type;
                yield(function() {
                    Validate.formMonitor(a, al.id, W, o, G)
                })
            }
        }
        if (al.hint) {
            f.appendChild(R);
            R.appendChild(createNode("td"));
            R.appendChild(createNode("td", {
                className: "explain"
            }, null, al.hint))
        }
        if (al.requiredIf) {
            var I = $(al.requiredIf);
            if (I) {
                var ac = Browser.isIE ? "inline" : "table-row";
                setNode(A, null, {
                    display: inputValue(I) ? ac : "none"
                });
                setNode(R, null, {
                    display: inputValue(I) ? ac : "none"
                });
                Event.addListener(I, "change", function() {
                    setNode(A, null, {
                        display: inputValue(I) ? ac : "none"
                    });
                    setNode(R, null, {
                        display: inputValue(I) ? ac : "none"
                    })
                })
            } else {
                console.log("requiredIf src not found")
            }
        }
        if (al.ac_data) {
            var y;
            if (al.ac_data.data) {
                y = al.ac_data.data
            } else {
                if (al.ac_data.action) {
                    y = new AjaxDataSource(al.ac_data.action, {
                        data: al.ac_data.params || {}
                    }, {
                        hideProgress: true
                    })
                }
            }
            var z = new AutoComplete(p, y, al.ac_data.options);
            Event.addListener(a, "destruct", z.destruct, z)
        }
    });
    h.removeChild(k);
    document.body.removeChild(h);
    return k
}


function validateData(c, b) {
    if (!c || !b) {
        return true
    }
    var d = 0;

    function a(f) {
        return !(f === undefined || f === "")
    }
    b.forEach(function(n) {
        if (!n.name || n.type == "hidden") {
            return
        }
        var k = c[n.name];
        if (n.requiredIf && !a(c[n.requiredIf])) {
            return
        }
        if (n.type == "checkbox") {
            if (n.checkboxes) {
                n.checkboxes.forEach(function(o) {
                    if (o.name == n.name) {
                        o.checked = k ? true : false
                    }
                })
            } else {
                n.checked = k ? true : false
            }
        } else {
            if (n.type == "agecheck") {
                if (c[n.name + "_y"] && c[n.name + "_m"] && c[n.name + "_d"]) {
                    var h = parseInt(c[n.name + "_y"], 10);
                    var l = parseInt(c[n.name + "_m"], 10);
                    var f = parseInt(c[n.name + "_d"], 10);
                    k = new Date(h, l - 1, f);
                    if (k.getMonth() == l - 1 && k.getDate() == f) {
                        n.value = k
                    } else {
                        k = -1;
                        n.value = 0
                    }
                } else {
                    k = undefined;
                    n.value = 0
                }
            } else {
                n.value = k
            }
        }
        n.error = null;
        if ((n.required || n.requiredIf) && !a(k)) {
            d++;
            n.error = n.errMsg;
            if (!n.error) {
                n.error = loc("Please enter a value for this field")
            }
        }
        if (n.validate) {
            if (n.validate.toLowerCase() != "none") {
                var j = Validate.validate(k, n.validate);
                if (!j.valid) {
                    d++;
                    n.error = j.msg;
                    return
                }
            }
        } else {
            var g = Validate.validate(k, n.type);
            if (!g.valid) {
                d++;
                n.error = g.msg;
                return
            }
        } if (n.type == "agecheck") {
            if (k == -1) {
                d++;
                n.error = loc("Please enter a valid date")
            } else {
                var m = new Date(k);
                m.setFullYear(m.getFullYear() + n.minAge);
                if (m - new Date() > 0) {
                    d++;
                    n.error = loc("You must be at least {minage} years old", {
                        minage: n.minAge
                    })
                }
            }
        }
    });
    return d === 0
}

function extractInputValues(f) {
    f = $(f);
    var a = {};
    for (var d = 0; d < f.elements.length; ++d) {
        var b = f.elements[d];
        var c = b.name;
        var g = inputValue(b);
        if (c) {
            if (b.getAttribute("list")) {
                if (b.type == "checkbox") {
                    if (g) {
                        if (a[c]) {
                            a[c].push(g)
                        } else {
                            a[c] = [g]
                        }
                    }
                } else {
                    a[c] = g ? g.split(/\s*[,\n]\s*/) : []
                }
            } else {
                if (b.type == "radio") {
                    if (g) {
                        a[c] = g
                    }
                } else {
                    a[c] = g
                }
            }
        }
    }
    return a
}

function focusFirst(d) {
    d = d.constructor == String ? $(d) : d;
    var f = d.elements;
    var a = function(g) {
        return function() {
            g.focus()
        }
    };
    for (var c = 0; c < f.length; ++c) {
        var b = f[c];
        if (b.type != "hidden") {
            Event.addListener(document, "modifiable", a(b));
            break
        }
    }
}

function formCancel(c, b) {
    c = $(c);
    setNode(c, {
        onsubmit: null
    });
    Event.release(c);
    var d;
    for (var a = 0; a < c.elements.length; a++) {
        if (c.elements[a].type == "submit") {
            d = c.elements[a].name;
            break
        }
    }
    if (d) {
        c.appendChild(createNode("input", {
            type: "hidden",
            name: d,
            value: b || loc("Cancel")
        }))
    }
    c.submit()
}
var Validate = function() {
    var b = null;
    var a = {
        valid: true
    };
    return {
        validate: function(c, h, f, d) {
            if (!b) {
                b = {
                    EMAIL: Validate.email,
                    DISPLAYNAME: Validate.displayName,
                    DISPLAYNAMEOFFICIAL: Validate.displayNameOfficial,
                    USERNAME: Validate.userName,
                    URL: Validate.url,
                    INTEGER: Validate.integer,
                    FLOAT: Validate.floatType,
                    DATE: Validate.date,
                    PRICE: Validate.price,
                    MULTI_FILE_UPLOAD: Validate.multiFileUpload
                }
            }
            f = f || {};
            c = "" + c;
            c = c.trim();
            if (c.length === 0) {
                if (!f.required) {
                    return a
                } else {
                    return {
                        valid: false,
                        msg: f.error || loc("Please enter a value for this field")
                    }
                }
            }
            if (f.minlength && !d && c.length < f.minlength) {
                return {
                    valid: false,
                    msg: loc("Please enter at least {min} characters for this field", {
                        min: f.minlength
                    })
                }
            }
            if (f.maxlength) {
                var j = c;
                if ((f.htmllevel || "none") !== "none") {
                    j = stripHtml(j)
                }
                if (j.length > f.maxlength) {
                    return {
                        valid: false,
                        msg: loc("Please enter less than {max} characters for this field", {
                            max: f.maxlength
                        })
                    }
                }
            }
            h = h || "";
            var g = b[h.toUpperCase()];
            if (!g) {
                return a
            }
            return g(c, f, d)
        },
        formMonitor: function(j, f, h, k, g) {
            j = $(j);
            if (!j) {
                return
            }
            f = $(f) || j[f];
            k = $(k);
            if (!k) {
                return
            }
            g = g || {};
            var d;
            if (hasClass(k, "error")) {
                d = k
            } else {
                d = getElementsByClassName({
                    root: k,
                    className: "error"
                })[0]
            } if (!d || !f) {
                return
            }
            j._invalidInputs = j._invalidInputs || {};
            var l = function(n) {
                n = n && (!document.activeElement || document.activeElement === f);
                var m = Validate.validate(f.value, h, g, n);
                if (m.valid) {
                    delete j._invalidInputs[f.name];
                    setNode(k, null, {
                        display: "none"
                    })
                } else {
                    j._invalidInputs[f.name] = true;
                    if (m.msg) {
                        setNode(k, null, {
                            display: "table-row"
                        });
                        d.innerHTML = m.msg
                    } else {
                        setNode(k, null, {
                            display: "none"
                        })
                    }
                }
                return m.valid
            };
            if (h) {
                var c = Event.rateLimit(function() {
                    l(true)
                }, 100);
                Event.addListener(f, "blur", function() {
                    l(false)
                });
                Event.addListener(f, "change", function() {
                    l(false)
                });
                Event.addListener(f, "keyup", c)
            }
            Event.addListener(j, "submit", function(m) {
                if (l(false)) {
                    return
                }
                if (m) {
                    Event.stop(m)
                }
            })
        },
        isFormValid: function(c) {
            var d = $(c)._invalidInputs || {};
            var f = true;
            forEachKey(d, function() {
                f = false;
                return true
            });
            return f
        },
        required: function(f, d, c) {
            return f || c ? a : {
                valid: false,
                msg: d.error || loc("Please enter a value for this field")
            }
        },
        minlength: function(f, d, c) {
            if (c || !f || !f.length || f.length >= d.minlength) {
                return a
            } else {
                return {
                    valid: false,
                    msg: loc("Please enter at least {min} characters for this field", {
                        min: d.minlength
                    })
                }
            }
        },
        maxlength: function(f, d, c) {
            if (c || !f || !f.length) {
                return a
            } else {
                return f.length <= d.maxlength ? a : {
                    valid: false,
                    msg: loc("Please enter less than {max} characters for this field", {
                        max: d.maxlength
                    })
                }
            }
        },
        emailOrUserName: function(h, f, c) {
            h = h || "";
            h = h.toLowerCase();
            var d = Validate.email(h, f, c);
            var g = Validate.userName(h, f, c);
            return d.valid || g.valid ? a : {
                valid: false,
                msg: loc("Not a valid email address or username")
            }
        },
        email: function(f, d, c) {
            d = d || {};
            if (!f) {
                return a
            }
            if (c) {
                if (f.match(/^[@\w\-\.\+]*$/)) {
                    return a
                }
            }
            if (f.match(/^.+@[\w\-]+(\.[\w\-]+)*\.[A-Za-z]{2,10}/)) {
                return a
            }
            return {
                valid: false,
                msg: loc("Not a valid email address")
            }
        },
        displayName: function(f, d, c) {
            d = d || {};
            if (c) {
                if (f.match(/^[\w\']+(\s[\w\']+)*\s?$/)) {
                    return a
                }
            }
            if (f.length === 0) {
                return {
                    valid: false
                }
            }
            if (f.match(/^[\w\']+(\s[\w\']+)*$/)) {
                return a
            }
            return {
                valid: false,
                msg: loc("Only normal characters, numbers and single spaces")
            }
        },
        displayNameOfficial: function(f, d, c) {
            d = d || {};
            if (c) {
                if (f.match(/^[\w\'\-\.]+(\s[\w\'\-\.]+)*\s?$/)) {
                    return a
                }
            }
            if (f.length === 0) {
                return {
                    valid: false
                }
            }
            if (f.match(/^[\w\'\-\.]+(\s[\w\'\-\.]+)*$/)) {
                return a
            }
            return {
                valid: false,
                msg: loc("Only normal characters, numbers and single spaces")
            }
        },
        userName: function(f, d, c) {
            d = d || {};
            if (!f) {
                return a
            }
            if (f.length < 4 && !c) {
                return {
                    valid: false,
                    msg: loc("Too short")
                }
            }
            if (f.length > 32) {
                return {
                    valid: false,
                    msg: loc("Too long")
                }
            }
            if (c) {
                if (f.match(/^[a-z](-?[a-z0-9]+)*-?$/)) {
                    return a
                }
            }
            if (f.match(/[A-Z]/) && c) {
                return {
                    valid: false
                }
            }
            if (!f.match(/^[a-z](-?[a-z0-9])*$/)) {
                return {
                    valid: false,
                    msg: loc("Only lowercase letters (a-z), numbers (0-9), and dashes (-) are allowed.") + " " + loc("Cannot start or end with a number or dash.")
                }
            }
            return a
        },
        url: function(f, d, c) {
            d = d || {};
            if (c || !f) {
                return a
            }
            if (f.length && !(f.indexOf("http://") === 0 || f.indexOf("https://") === 0)) {
                f = "http://" + f
            }
            if (!f.match(/^https?:\/\/[^.]+(\.[^.]+)+/)) {
                return {
                    valid: false,
                    msg: loc("{url} is not a valid URL", {
                        url: f
                    })
                }
            }
            return a
        },
        integer: function(f, d, c) {
            d = d || {};
            if (!f) {
                return a
            }
            if (c) {
                if (!f.match(/^-?\d*$/)) {
                    return {
                        valid: false,
                        msg: loc("Not a valid number")
                    }
                }
                if ((d.max || d.max === 0) && f > 0 && f > Number(d.max)) {
                    return {
                        valid: false,
                        msg: loc("This value cannot be greater than {number}", {
                            number: d.max
                        })
                    }
                }
                if ((d.min || d.min === 0) && f < 0 && (f < Number(d.min))) {
                    return {
                        valid: false,
                        msg: loc("This value cannot be less than {number}", {
                            number: d.min
                        })
                    }
                }
            } else {
                if (!f.match(/^-?\d+$/)) {
                    return {
                        valid: false,
                        msg: loc("Not a valid number")
                    }
                }
                if ((d.min || d.min === 0) && f < Number(d.min)) {
                    return {
                        valid: false,
                        msg: loc("This value cannot be less than {number}", {
                            number: d.min
                        })
                    }
                }
                if ((d.max || d.max === 0) && f > Number(d.max)) {
                    return {
                        valid: false,
                        msg: loc("This value cannot be greater than {number}", {
                            number: d.max
                        })
                    }
                }
            }
            return a
        },
        floatType: function(g, f, c) {
            f = f || {};
            g = "" + g;
            if (!g) {
                return a
            }
            if (!c) {
                if (!g.match(/^-?[0-9]*\.?[0-9]+$/)) {
                    return {
                        valid: false,
                        msg: loc("Not a valid number")
                    }
                }
                if ((f.min || f.min === 0) && g < Number(f.min)) {
                    return {
                        valid: false,
                        msg: loc("This value cannot be less than {number}", {
                            number: f.min
                        })
                    }
                }
            }
            if ((f.max || f.max === 0) && g > Number(f.max)) {
                return {
                    valid: false,
                    msg: loc("This value cannot be greater than {number}", {
                        number: f.max
                    })
                }
            }
            var d = g.indexOf(".");
            if (f.decimal && d > -1 && (g.length - 1 - d > f.decimal)) {
                return {
                    valid: false,
                    msg: loc("This value cannot have more than {number} decimals", {
                        number: f.decimal
                    })
                }
            }
            if (c) {
                if (!g.match(/^-?[0-9]*\.?[0-9]*$/)) {
                    return {
                        valid: false,
                        msg: loc("Not a valid number")
                    }
                }
            }
            return a
        },
        multiFileUpload: function(h, g, f) {
            if (!h || !g || !g.inputNode || !g.inputNode._multiFileUpload) {
                return a
            }
            var d = [];
            var c = [];
            g.inputNode._multiFileUpload.pluploader.files.filter(function(j) {
                if (MultiFileUpload.isFileUploaded(j)) {
                    return
                }
                if (j.status == plupload.QUEUED || j.status == plupload.UPLOADING) {
                    d.push(j);
                    return
                }
                c.push(j)
            });
            if (c.length) {
                return {
                    valid: false,
                    msg: loc("Some files had errors.")
                }
            }
            if (!f && d.length) {
                return {
                    valid: false,
                    msg: loc("Some files have not finished uploading.")
                }
            }
            return a
        },
        price: function(g, d, c) {
            d = d || {};
            if (!g) {
                return a
            }
            var f = loc("price");
            if (!c) {
                if (!g.match(/^\s*\d*(\.\d{1,2})?\s*$/)) {
                    return {
                        valid: false,
                        msg: loc("Please enter a valid {priceName}", {
                            priceName: d.priceName || f
                        })
                    }
                }
            }
            if (d.min !== undefined && g < Number(d.min)) {
                return {
                    valid: false,
                    msg: loc("Minimum {priceName} is {number}", {
                        priceName: d.priceName || f,
                        number: d.min
                    })
                }
            }
            if (!g.match(/^\s*\d*(\.\d{0,2})?\s*$/)) {
                return {
                    valid: false,
                    msg: loc("Please enter a valid {priceName}", {
                        priceName: d.priceName || f
                    })
                }
            }
            if (d.max !== undefined && g > Number(d.max)) {
                return {
                    valid: false,
                    msg: loc("Maximum {priceName} is {number}", {
                        priceName: d.priceName || f,
                        number: d.max
                    })
                }
            }
            return a
        },
        userNameAvailable: function(c) {
            return function() {
                return c.available ? a : {
                    valid: false
                }
            }
        },
        phone: function(c, f, d) {
            if (d) {
                return a
            }
            if (c.match(/(.*\d){10}/)) {
                return a
            }
            return {
                valid: false,
                msg: loc("Not a valid phone number")
            }
        },
        host: function(f, d, c) {
            if (c) {
                if (f.match(/^[\-a-z0-9\.]*$/)) {
                    return a
                }
            }
            if (f.match(/^[\-a-z0-9]+(\.[\-a-z0-9]+)+/)) {
                return a
            }
            return {
                valid: false,
                msg: loc("Not a valid host name")
            }
        }
    }
}();
var ExecQueue = (function() {
    var b;
    var a = [];
    return {
        push: function(c, d) {
            a.push(c);
            if (!b && d !== false) {
                b = window.setTimeout(ExecQueue._exec, 200)
            }
        },
        exec: function() {
            if (!b) {
                ExecQueue._exec()
            } else {}
        },
        _exec: function() {
            var c = a;
            a = [];
            b = null;
            c.forEachNonBlocking(50, function(d) {
                d()
            })
        }
    }
})();
function Pool(a) {
    var b = [];
    return {
        get: function() {
            if (b.length) {
                return b.pop()
            } else {
                return a()
            }
        },
        release: function(c) {
            if (!b.contains(c)) {
                b.push(c)
            }
        }
    }
}

function AjaxResult(a) {
    if (!a) {
        return
    }
    forEachKey(a, function(b) {
        this[b] = a[b]
    }, this)
}
AjaxResult.prototype.hasGeneralError = function() {
    return !this.status || !this.status.ok
};
AjaxResult.prototype.hasFormError = function() {
    return !!this.form_error
};
AjaxResult.prototype.extractGeneralErrorMessages = function() {
    var a = [];
    if (!this.hasGeneralError()) {
        return a
    }
    if (!this.message) {
        a.push(loc("An error has occurred.") + " " + loc("Please try again later."))
    } else {
        this.message.forEach(function(b) {
            if (b.type != "error") {
                return
            }
            a.push(b.content)
        })
    }
    return a
};
AjaxResult.prototype.extractMessagesByType = function() {
    var a = {};
    if (!this.message) {
        return a
    } else {
        this.message.forEach(function(b) {
            a[b.type] = a[b.type] || [];
            a[b.type].push(b.content)
        });
        return a
    }
};
var Ajax = function() {
    var pool = new Pool(function() {
        return tryThese(function() {
            return new XMLHttpRequest()
        }, function() {
            return new ActiveXObject("Msxml2.XMLHTTP")
        }, function() {
            return new ActiveXObject("Microsoft.XMLHTTP")
        }) || false
    });
    var contracts = {};
    var passback = {};
    return {
        post: function(params) {
            params.method = "POST";
            return Ajax.request(params)
        },
        get: function(params) {
            params.method = "GET";
            return Ajax.request(params)
        },
        request: function(params) {
            var action = params.action;
            var resultType = params.resultType || "JSON";
            var data = params.data || {};
            var onSuccess = params.onSuccess || noop;
            var onError = params.onError || noop;
            var onProgress = params.onProgress || noop;
            var onFinally = params.onFinally || noop;
            var method = params.method || "POST";
            var busyMsg = params.busyMsg || loc("Busy") + "...";
            var contract = params.contract;
            var hideProgress = params.hideProgress || false;
            var track = params.track || false;
            Ajax.abortContract(contract);
            var xhr = pool.get();
            if (contract) {
                contracts[contract] = xhr
            }
            var url = params.url;
            var payload;
            var cacheable = !! data[".cacheable"];
            var out = (data._out || data[".out"] || "json").toLowerCase();
            if (out != "html" && out != "json" && out != "jsonx") {
                out = "json"
            }
            delete data._out;
            delete data[".out"];
            delete data[".cacheable"];
            data[".debug"] = !! window._Debug;
            var currURL = parseUri(window.location);
            forEachKey(currURL.queryKey, function(key, value) {
                if (key.match(/^\.exp_/)) {
                    data[key] = value
                }
            });
            if (resultType != "BEACON") {
                forEachKey(passback, function(key, value) {
                    data[".passback"] = data[".passback"] || {};
                    data[".passback"][key] = value
                })
            }
            if (method == "POST") {
                url = url || buildAbsURL(buildURL(action), null, window.location.host);
                var token = Auth.getToken();
                if (token) {
                    data[".tok"] = token
                }
                var locale = Conf.getLocale();
                if (locale) {
                    data[".locale"] = locale
                }
                if (window._xsrfToken) {
                    data[".xsrf"] = window._xsrfToken
                }
                payload = ".in=json&.out=" + out + "&request=" + encodeURIComponent(JSON2.stringify(data))
            } else {
                var urlParams = {
                    ".in": "json",
                    ".out": out,
                    request: JSON2.stringify(data)
                };
                if (cacheable) {
                    urlParams[".cacheable"] = 1
                }
                url = url || buildAbsURL(buildURL(action, urlParams), null, window.location.host);
                if (url.length > 2000) {
                    throw ("GET url is longer than 2000 chars: " + url)
                }
                payload = null
            } if (window._tracking_segments_cookie) {
                Cookie.set("st", window._tracking_segments_cookie)
            }
            
            xhr.open(method, url, true);
            if (isPolyvoreURL(url)) {
                xhr.withCredentials = true
            } else {
                xhr.withCredentials = false
            }
            xhr.onreadystatechange = function() {
                onProgress(xhr);
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        switch (resultType) {
                            case "JSON":
                                try {
                                    var result = new AjaxResult(eval("(" + xhr.responseText + ")"));
                                    Ajax.mergePassback(result[".passback"] || {});
                                    var jslint = window._Debug && _Debug.ajaxDebug(result, {
                                        url: url
                                    });
                                    var cacheControl = xhr.getResponseHeader("Cache-Control");
                                    if (!cacheControl || cacheControl == "no-cache") {
                                        window._xsrfToken = result.xsrf || window._xsrfToken
                                    }
                                    if (!result.hasGeneralError() && !result.hasFormError()) {
                                        if (result[".user"]) {
                                            Auth.setUser(result[".user"])
                                        }
                                        if (Cookie.canNotSetCookies() && result[".tok"]) {
                                            Auth.setToken(result[".tok"])
                                        }
                                        onSuccess(result);
                                        Event.checkForBackendEvent();
                                        if (Cookie.canNotSetCookies()) {
                                            Event.triggerBackendEvents(result[".events"], createUUID())
                                        }
                                    } else {
                                        onError(result)
                                    }
                                } catch (e) {
                                    var foo = window._Debug && window._Debug.logStackTrace();
                                    console.log("exception in handling of ajax response", e)
                                }
                                break;
                            case "BEACON":
                                onSuccess();
                                break;
                            case "HTML":
                                onSuccess(xhr.responseText);
                                break
                        }
                    } else {
                        if (resultType != "BEACON" && window.Beacon && Beacon.log) {
                            Beacon.log("ajaxerror", {
                                status: xhr.status
                            })
                        }
                        onError(new AjaxResult({
                            xhr_status: xhr.status
                        }))
                    } if (window.Progress !== undefined && !hideProgress) {
                        Progress.hide()
                    }
                    onFinally();
                    if (contract && contracts[contract] == xhr) {
                        delete contracts[contract]
                    }
                    yield(function() {
                        pool.release(xhr)
                    })
                }
            };
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
            if (!hideProgress) {
                Progress.show(busyMsg)
            }
            xhr.send(payload);
            return xhr
        },
        mergePassback: function(data) {
            passback = mergeObject(passback, data)
        },
        abort: function(xhr) {
            xhr.onreadystatechange = noop;
            xhr.abort();
            if (window.Progress !== undefined) {
                Progress.hide()
            }
        },
        abortContract: function(contract) {
            var old_xhr = Ajax.getContract(contract);
            if (!old_xhr) {
                return false
            }
            Ajax.abort(old_xhr);
            delete contracts[contract];
            return true
        },
        getContract: function(contract) {
            if (!contract) {
                return null
            }
            return contracts[contract] || null
        },
        getCacheKey: function(action, params) {
            action = action || "";
            params = params || {};
            var values = ["action=" + encodeURIComponent(action)];
            forEachKey(params, function(key, value) {
                if (key != "reqSize" && key != "page" && key != "length" && key != ".cacheable" && key.indexOf("_") !== 0) {
                    values.push(key + "=" + encodeURIComponent(value))
                }
            });
            values.sort();
            return JSON2.stringify(values)
        }
    }
}();
var Progress = function() {
    var a;
    var c;

    function b() {
        if (c) {
            window.clearTimeout(c)
        }
        if (!a) {
            a = $("progress")
        }
        if (!a) {
            a = createNode("div", {
                id: "progress"
            }, {
                display: "none"
            });
            document.body.appendChild(a)
        }
    }
    return {
        show: function(d) {
            b();
            setNode(a, null, {
                top: px(scrollXY().y),
                display: "block",
                zIndex: overlayZIndex(a)
            }, d)
        },
        hide: function() {
            b();
            setNode(a, null, {
                display: "none"
            })
        },
        flash: function(d) {
            b();
            setNode(a, null, {
                display: "none"
            });
            c = window.setTimeout(function() {
                setNode(a, null, {
                    display: "block"
                }, d);
                c = 0
            }, 5)
        }
    }
}();
var Beacon = function() {
    var j = [];
    var g = buildAbsURL("../rsrc/beacon.gif?", null, document.location.host || Conf.getWebHost());
    var f = g.length;
    var c = 2000;

    function h(k) {
        var m = ["type=" + k.type];
        var l = k.data;
        if (l) {
            forEachKey(l, function(n, o) {
                m.push(encodeURIComponent(n) + "=" + encodeURIComponent(o))
            })
        }
        return m.join("&")
    }

    function d() {
        if (j.length === 0) {
            return
        }
        var k = g + "&t=" + encodeURIComponent(new Date().getTime());
        var n = k;
        var m = true;
        while (j.length > 0) {
            var l = j.shift();
            n += "&" + h(l);
            if (n.length > c) {
                if (m) {
                    k = n
                } else {
                    j.unshift(l)
                }
                yield(d);
                break
            } else {
                k = n
            }
            m = false
        }
        Ajax.get({
            url: k,
            resultType: "BEACON",
            hideProgress: true,
            onSuccess: function() {
                if (l.length > 0) {
                    d()
                }
            }
        })
    }

    function a() {
        d()
    }
    var b = new Interval(500, a);
    Event.addListener(window, "beforeunload", a);
    return {
        log: function(l, m, k) {
            if (window._polyvorePageTs) {
                m._page_ts = window._polyvorePageTs
            }
            j.push({
                type: l,
                data: m
            });
            if (k) {
                a()
            }
        },
        flush: function() {
            a()
        }
    }
}();
var Track = function() {
    function c(j) {
        return matchingAncestor(j, null, "trackcontext")
    }

    function d(j) {
        return matchingAncestor(j, null, "trackelement")
    }
    var h = {
        splash: "s",
        thing: "t",
        set: "c",
        profile: "u",
        collection: "l",
        "shop.browse": "sb",
        shop: "sp",
        app: "e",
        activity: "a",
        string: "st",
        home: "h",
        contest: "co",
        "popular.fashion": "pf"
    };

    function b(l) {
        var j = (l && l.getAttribute("oid")) || "";
        if (j && /:/.test(j)) {
            return j
        }
        var k = l.href;
        if (k && isPolyvoreURL(k)) {
            var n = parsePolyvoreURL(k).action;
            var m = parseUri(k);
            return Track.classAndId(n, m.queryKey.id || m.query || "")
        }
        return j || ""
    }

    function a(j, o) {
        var m = parseUri(j);
        var l = m.host;
        var n = m.path;
        if (!l) {
            return j
        }
        var k;
        l = l.toLowerCase();
        if (l.match(/linksynergy/)) {
            k = "u1"
        } else {
            if (n && n.match(/click-2687457-/i)) {
                k = "sid"
            } else {
                if (l.match(/awin1/)) {
                    k = "clickref"
                } else {
                    if (l == "www.shareasale.com") {
                        k = "afftrack"
                    } else {
                        if (n && n.match(/^\/t\//)) {
                            k = "sid"
                        } else {
                            if (l == "rover.ebay.com") {
                                k = "customid"
                            } else {
                                if (l == "www.avantlink.com") {
                                    k = "ctc"
                                } else {
                                    if (l == "tracker.tradedoubler.com") {
                                        k = "epi"
                                    } else {
                                        if (l == "track.webgains.com") {
                                            k = "clickref"
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } if (k) {
            m.queryKey = m.queryKey || {};
            m.queryKey[k] = o;
            return reconstructUri(m)
        } else {
            return j
        }
    }
    Event.addListener(document, "mousedown", function(m) {
        var j = Event.getSource(m);
        var s = 0;
        while (j && ++s < 3) {
            if (j.tagName == "A" || (hasClass(j, "clickable") || hasClass(j, "btn"))) {
                if (j.ctrTrackcontext) {
                    Track.logCTRClick(j.ctrTrackcontext, b(j), j.srcId)
                }
                Track.flushStats();
                var n = j.href;
                if (n && n.indexOf("http") === 0) {
                    if (!isPolyvoreURL(n)) {
                        var r = j.getAttribute("oid");
                        if (r) {
                            r = r.split(":")[1]
                        }
                        var l = j.getAttribute("orighost");
                        var u = j.getAttribute("paidurl");
                        var t = j.getAttribute("trackelement");
                        var p = j.getAttribute("cpc");
                        var v = j.getAttribute("onsale");
                        var q = j.getAttribute("freeship");
                        if (j.getAttribute("showtooltip") && !(isRightClick(m) || (Browser.isMac && m.ctrlKey))) {
                            Event.stop(m);
                            return Shop.outbound({
                                url: n,
                                paid_url: u,
                                orighost: l,
                                thing_id: r
                            })
                        }
                        var o = createUUID();
                        var k = Track.trackOutboundUrl({
                            cpc: p,
                            tid: r,
                            on_sale: v,
                            free_ship: q,
                            url: j.href,
                            orighost: l,
                            paid_url: u,
                            track_id: o,
                            context: Track.getContext(j),
                            track_element: t
                        });
                        if (k && j.href != k) {
                            j.href = k
                        }
                        if (r) {
                            Ajax.post({
                                action: "outbound.store",
                                hideProgress: true,
                                data: {
                                    tid: r
                                }
                            })
                        }
                    }
                }
                return
            }
            j = j.parentNode
        }
    });
    var g = [];

    function f() {
        if (g.length) {
            Ajax.post({
                action: "stats.record",
                hideProgress: true,
                data: {
                    stats: g
                }
            });
            g = []
        }
    }
    Event.addListener(window, "beforeunload", f);
    return {
        classAndId: function(j, k) {
            j = h[j] || j.substring(0, 2).toLowerCase();
            if (k) {
                return [j, ":", k].join("")
            }
            return j
        },
        trackCTR: function(q, m, v) {
            var l;
            v = v || {};
            if (q.constructor == String) {
                l = getElementsWithAttributes({
                    root: v.root,
                    attributes: {
                        trackcontext: q
                    }
                })
            } else {
                l = [q];
                q = q.getAttribute("trackcontext");
                if (!q) {
                    return
                }
            } if (m) {
                var o = m;
                m = {};
                o.forEach(function(w) {
                    m[Track.classAndId(w)] = 1
                })
            }
            var u = function(w, z, B) {
                for (var x = 0; x < w.length; x++) {
                    var y = w[x];
                    if (y.ctrTrackcontext) {
                        continue
                    }
                    var A = b(y);
                    if (A) {
                        if (!m || m[A.split(":")[0]]) {
                            z.push(A);
                            y.ctrTrackcontext = q;
                            y.srcId = B
                        }
                    }
                }
            };
            for (var r = 0; r < l.length; r++) {
                var n = l[r];
                var s = [];
                var j = n.getAttribute("srcid");
                var p = getElementsByClassName({
                    root: n,
                    tagName: "SPAN",
                    className: "clickable"
                });
                var t = getElementsByClassName({
                    root: n,
                    tagName: "SPAN",
                    className: "btn"
                });
                var k = n.tagName == "A" ? [n] : n.getElementsByTagName("a");
                u(k, s, j);
                u(p, s, j);
                u(t, s, j);
                if (!v.clicksOnly) {
                    Track.logCTRView(q, s, j)
                }
            }
        },
        logCTRView: function(l, k, m) {
            k = toArray(k);
            if (!k.length) {
                return
            }
            var j = {};
            j[l] = k.uniq();
            var n = {
                view: JSON2.stringify(j)
            };
            if (m) {
                n.ctr_src_id = m
            }
            Beacon.log("ctr", n)
        },
        logCTRClick: function(l, k, m) {
            if (!k) {
                return
            }
            var j = {};
            j[l] = k;
            var n = {
                click: JSON2.stringify(j)
            };
            if (m) {
                n.ctr_src_id = m
            }
            Beacon.log("ctr", n, true)
        },
        logCPCView: function(k, j) {
            Beacon.log("cpc_view", {
                context: k,
                cpc: j
            })
        },
        trackCPCImpression: function(n) {
            if (!n) {
                return
            }
            var m = d(n);
            if (!m) {
                return
            }
            var j = c(m);
            if (!j) {
                return
            }
            var k = m.getAttribute("cpc");
            if (!k) {
                return
            }
            var l = Track.getContext(n);
            Beacon.log("cpc_view", {
                context: l,
                cpc: k
            })
        },
        stat: function(m, j, n, l) {
            var k = {
                action: m,
                name: j,
                params: n
            };
            if (l !== undefined) {
                k.value = l
            }
            Beacon.log("stat", k)
        },
        statCounter: function(m, j, n, l) {
            var k = {
                action: m,
                name: j,
                params: JSON2.stringify(n)
            };
            if (l !== undefined) {
                k.value = l
            }
            Beacon.log("statcounter", k)
        },
        flushStats: Beacon.flush,
        trackOutboundUrl: function(o) {
            var p = o.tid;
            var m = o.cpc;
            var j = o.orighost;
            var q = o.paid_url;
            var l = o.track_id;
            var k = o.url;
            var s = o.on_sale;
            var n = o.free_ship;
            if (j) {
                if (q) {
                    k = m ? q : a(q, l)
                }
                var t = "oc_" + j;
                Cookie.set(t, l, 30, undefined, "/conversion")
            }
            var r = {
                track_id: l,
                tid: p,
                orighost: j,
                url: k
            };
            if (m) {
                r.cpc = m
            }
            if (s == "1") {
                r.on_sale = s
            }
            if (n == "1") {
                r.free_ship = n
            }
            if (o.context) {
                r.track = o.context;
                if (o.track_element) {
                    r.track += "_" + o.track_element
                }
            }
            Beacon.log("outbound", r);
            return k
        },
        redirectOutboundUrl: function(l, k) {
            var j = Track.trackOutboundUrl(l);
            window.setTimeout(function() {
                window.location.replace(j)
            }, k)
        },
        ctrInfo: b,
        getContext: function(k) {
            if (!k || k.tagName == "HTML" || k.tagNode == "BODY") {
                return ""
            }
            if (k._trackContextPath !== undefined) {
                return k._trackContextPath
            }
            var l = [];
            var j = Track.getContext(k.parentNode);
            if (j) {
                l.push(j)
            }
            if (k.getAttribute && k.getAttribute("trackcontext")) {
                l.push(k.getAttribute("trackcontext"))
            }
            return (k._trackContextPath = l.join("|"))
        },
        setContext: function(k, j) {
            k._trackContextPath = j
        }
    }
}();

function notSeen(b) {
    var a = getStyle(b, "color");
    return (a == "#abcdef" || a == "rgb(171, 205, 239)")
}
var imgThingRe = new RegExp("/img-thing.*(?:/|&)tid(?:/|=)(\\d+)");

function getImageThingId(b) {
    if (!b) {
        return null
    }
    var a;
    if ((a = b.match(imgThingRe))) {
        return a[1]
    }
    return null
}
var imgSetRe = new RegExp("/img-set.*(?:/|&)cid(?:/|=)(\\d+)");

function getImageSetId(b) {
    if (!b) {
        return null
    }
    var a;
    if ((a = b.match(imgSetRe))) {
        return a[1]
    }
    return null
}
var L10N = function() {
    var a = {};
    var c = (window.Lexicon !== undefined);

    function b(f) {
        var d = "return " + f.replace(/\"/g, '\\"').split(/(\{\w+\})/).map(function(g) {
            return (g.match(/\{(\w+)\}/)) ? 'p["' + RegExp.$1 + '"]' : '"' + g + '"'
        }).join("+") + ";";
        return new Function("p", d)
    }
    return {
        loc: function(j, h) {
            if (h) {
                for (key in h) {
                    if (h.hasOwnProperty(key) && (typeof(h[key]) == "object")) {
                        h[key] = outerHTML(h[key])
                    }
                }
            }
            if (a[j]) {
                return a[j](h)
            }
            var d = c ? Lexicon[j] || j : j;
            var g = (a[j] = b(d));
            return g(h)
        }
    }
}();
var loc = L10N.loc;
var HighlightingTextarea = function() {
    var a;
    var h;

    function g(k, l) {
        if ("undefined" === typeof l) {
            l = k.value.length
        }
        if (k.setSelectionRange) {
            k.focus();
            k.setSelectionRange(l, l)
        } else {
            if (k.createTextRange) {
                var j = k.createTextRange();
                j.collapse(true);
                j.moveEnd("character", l);
                j.moveStart("character", l);
                j.select()
            }
        }
    }

    function f(j, k) {
        return k - (j.value.slice(0, k).split("\r\n").length - 1)
    }

    function d(n) {
        var q = 0,
            l = 0,
            p, m, k, j, o;
        if (typeof n.selectionStart == "number" && typeof n.selectionEnd == "number") {
            q = n.selectionStart;
            l = n.selectionEnd
        } else {
            m = document.selection.createRange();
            if (m && m.parentElement() == n) {
                j = n.value.length;
                p = n.value.replace(/\r\n/g, "\n");
                k = n.createTextRange();
                k.moveToBookmark(m.getBookmark());
                o = n.createTextRange();
                o.collapse(false);
                if (k.compareEndPoints("StartToEnd", o) > -1) {
                    q = l = j
                } else {
                    q = -k.moveStart("character", -j);
                    q += p.slice(0, q).split("\n").length - 1;
                    if (k.compareEndPoints("EndToEnd", o) > -1) {
                        l = j
                    } else {
                        l = -k.moveEnd("character", -j);
                        l += p.slice(0, l).split("\n").length - 1
                    }
                }
            }
        }
        return {
            start: q,
            end: l
        }
    }

    function b(j, k) {
        k = k || {};
        this.prefix = k.prefix;
        this.input = j;
        this.caretTokenIndex = 0;
        this.caretCharIndex = 0;
        this.currentTokens = [""];
        this.cleaner = new Cleaner();
        this.cleaner.push(Event.addListener(this.input, "click", this.refresh, this));
        this.cleaner.push(Event.addListener(this.input, "keyup", this.refresh, this))
    }
    b.prototype.destruct = function() {
        Event.release(this);
        this.cleaner.clean()
    };
    b.prototype.refresh = function(k) {
        this.currentTokens = inputValue(this.input).split((/(\s)/));
        var l = 0;
        this.caretCharIndex = Browser.isIE ? d(this.input).start : getCaretPosition(this.input);
        while (l < this.currentTokens.length && this.caretCharIndex > this.currentTokens[l].length) {
            this.caretCharIndex -= this.currentTokens[l].length;
            ++l
        }
        if (l >= this.currentTokens.length) {
            l = -1
        } else {
            var j = this.currentTokens[l];
            if (j.indexOf(this.prefix) !== 0) {
                l = -1
            }
        }
        var m = this.caretTokenIndex;
        this.caretTokenIndex = l;
        if (m != this.caretTokenIndex) {
            Event.trigger(this, "indexchange", k, m)
        }
    };
    b.prototype.caretToken = function(k) {
        if (this.caretTokenIndex < 0) {
            return ""
        }
        var j = this.currentTokens[this.caretTokenIndex];
        if (k !== undefined) {
            if (this.currentTokens.length - 1 == this.caretTokenIndex) {
                k = k + " "
            }
            this.currentTokens[this.caretTokenIndex] = k
        }
        return j.substring(this.prefix.length)
    };
    b.prototype.getTokenEnd = function(j) {
        var l = 0;
        for (var k = 0; k < this.currentTokens.length; k++) {
            l += this.currentTokens[k].length;
            if (l > j) {
                break
            }
        }
        return l
    };
    b.prototype.reconstructValue = function(j) {
        return this.currentTokens.join("")
    };

    function c(n, p) {
        var k = n.parentNode;
        var m = n.nextSibling;
        this.highlight = createNode("div", {
            className: "highlight"
        });
        this.minHeight = (p.minHeight - c.lineHeight) || 200;
        if (this.minHeight % c.lineHeight) {
            this.minHeight += c.lineHeight - this.minHeight % c.lineHeight
        }
        this.maxHeight = p.maxHeight || this.minHeight;
        var l = {
            "overflow-y": "hidden",
            "overflow-x": "hidden",
            margin: 0,
            padding: "0 0 0 0",
            border: "none",
            width: "100%",
            position: "relative",
            backgroundColor: "transparent",
            height: px(this.minHeight + c.lineHeight)
        };
        var o = {};
        if (p.width) {
            o.width = px(p.width)
        }
        if (Browser.isIE) {
            l.overflow = "hidden"
        }
        this.area = n;
        var j = false;
        if (document.activeElement == this.area) {
            j = true
        }
        setNode(this.area, null, l);
        this.highlighter = createNode("div", {
            className: "highlighter"
        }, null, [this.highlight, this.area]);
        this.node = createNode("div", {
            className: "highlighter_chrome"
        }, o, this.highlighter);
        Event.addListener(this.area, "keydown", function() {
            yield(function() {
                this.updateSize(true)
            }, this)
        }, this);
        Event.addListener(this.area, "scroll", function() {
            this.updateSize(false)
        }, this);
        Event.addListener(a, "loaded", function() {
            this.updateHighlights(true)
        }, this);
        Event.addListener(h, "loaded", function() {
            this.updateHighlights(true)
        }, this);
        this.mentionsInputTokenizer = new b(this.area, {
            prefix: "@"
        });
        this.contactsAC = new AutoComplete(this.area, a, {
            inputTokenizer: this.mentionsInputTokenizer,
            onSelect: Event.wrapper(function(q) {
                this.onSelect("@" + q._data.user_name, this.mentionsInputTokenizer)
            }, this),
            skipInitialLoad: true,
            refreshOnInputChange: false,
            renderer: function(q) {
                return UI.renderPerson(q)
            },
            matcher: function(q, r) {
                return q.user_name.match(r)
            }
        });
        this.hashtagsInputTokenizer = new b(this.area, {
            prefix: "#"
        });
        this.hashtagsAC = new AutoComplete(this.area, h, {
            minChars: 0,
            maxSize: 10,
            inputTokenizer: this.hashtagsInputTokenizer,
            onSelect: Event.wrapper(function(q) {
                this.onSelect("#" + q._data.hashtag, this.hashtagsInputTokenizer)
            }, this),
            skipInitialLoad: true,
            refreshOnInputChange: false,
            renderer: function(q) {
                return q.hashtag
            },
            matcher: function(q, r) {
                return q.hashtag.match(r)
            }
        });
        Event.addListener(this.area, "focus", function() {
            a.ensureLoaded();
            h.ensureLoaded()
        }, this);
        Event.addListener(this.area, "change", function() {
            this.updateSize(true)
        }, this);
        yield(function() {
            this.updateSize(true)
        }, this);
        if (m) {
            k.insertBefore(this.node, m)
        } else {
            k.appendChild(this.node)
        } if (j) {
            this.area.focus()
        } else {
            if (this.area.value) {
                Event.addSingleUseListener(this.area, "focus", function() {
                    g(this.area)
                }, this)
            }
        }
    }
    c.lineHeight = 14;
    c.prototype.onSelect = function(k, j) {
        var l = Browser.isIE ? d(this.area).start : getCaretPosition(this.area);
        j.caretToken(k);
        this.area.value = j.reconstructValue();
        this.updateSize(true);
        yield(function() {
            var m = j.getTokenEnd(l);
            if (Browser.isIE) {
                m = f(this.area, m)
            }
            g(this.area, m)
        }, this)
    };
    c.prototype.setNewStyles = function(m, l) {
        if (!this.oldStyles) {
            this.oldStyles = {}
        }
        var n = {};
        var k = false;
        if (this.oldStyles[m]) {
            var j = this.oldStyles[m];
            forEachKey(l, function(o, p) {
                if (!j[o] || j[o] !== p) {
                    n[o] = p;
                    k = true
                }
                j[o] = p
            })
        } else {
            k = true;
            n = l;
            this.oldStyles[m] = l
        } if (k) {
            setNode(m, null, n)
        }
    };
    c.prototype.updateSize = function(n) {
        var l = {};
        var j = {};
        if (!Browser.isIE) {
            this.setNewStyles(this.area, {
                height: 0
            })
        }
        var m = Math.max(this.area.scrollHeight, this.minHeight);
        if (this.maxHeight < m) {
            m = this.maxHeight;
            if (Browser.isIE) {
                l.overflow = ""
            }
            l["overflow-y"] = "auto"
        } else {
            if (Browser.isIE) {
                l.overflow = "hidden"
            }
            l["overflow-y"] = "hidden"
        }
        l.height = px(m + c.lineHeight);
        this.setNewStyles(this.area, l);
        var o = this.area.scrollTop;
        j.top = px(-o);
        var k = this.area.clientWidth;
        if (Browser.isFirefox) {
            k--
        }
        j.width = px(k);
        this.setNewStyles(this.highlight, j);
        if (n) {
            this.updateHighlights(false)
        }
    };
    c.prototype.updateHighlights = function(l) {
        var j = this.area.value;
        if (this.oldValue == j && !l) {
            return
        }
        this.oldValue = j;
        j = j.split(/\n/).join("<br/>");
        j = c._markUpWithMatches(/(@[a-z][a-z0-9\-]{3,31})/gi, a, j);
        j = c._markUpWithMatches(/(#[A-Za-z0-9][A-Za-z0-9\_]{0,98}[A-Za-z0-9])/g, h, j);
        var k;
        if (Browser.isIE) {
            k = "<pre>" + j + "</pre>"
        } else {
            k = j
        }
        setNode(this.highlight, null, null, k)
    };
    c._markUpWithMatches = function(l, k, j) {
        return j.replace(l, function(n, o) {
            if (k.contains(o.substring(1), function(q, p) {
                if (p.user_name) {
                    return (q == p.user_name)
                }
                if (p.hashtag) {
                    return (q.toLowerCase() == p.hashtag.toLowerCase())
                }
                return 0
            })) {
                className = "match found"
            } else {
                className = "match notfound"
            }
            var m = "<span class='" + className + "'>" + o + "</span>";
            if (Browser.isIE) {
                m = "</pre>" + m + "<pre>"
            }
            return m
        })
    };
    c.prototype.getNode = function() {
        return this.node
    };
    return {
        init: function(p, o) {
            var l = $(p);
            var k = Dim.fromNode(l);
            if (k.h === 0) {
                var m = function() {
                    HighlightingTextarea.init(p, o)
                };
                window.setTimeout(m, 100);
                return
            }
            o = o || {};
            if (!a) {
                a = new AjaxDataSource("autocomplete.user_tagging", null, {
                    hideProgress: true
                })
            }
            if (!h) {
                h = new AjaxDataSource("autocomplete.user_hashtags", null, {
                    hideProgress: true
                })
            }
            o.minHeight = k.h - 10;
            if (o.maxHeight === undefined) {
                o.maxHeight = Math.max(300, k.h)
            }
            o.width = k.w - 10;
            var n = new c(l, o);
            if (o.attachments) {
                var j = new CommentAttachments(n)
            }
        }
    }
}();

UI = {};
var URI_PATH_CHARS = "[a-z0-9\\-~_.!*'();@&=+$,\\/?%#\\[\\]:]";
var PORT_CHARS = "[0-9:]";
var DOMAIN_CHARS = "[a-z0-9\\-.]";
var SCHEME_CHARS = "[a-z]+";
var EMAIL_CHARS = "[a-z0-9!#$%&'*+-/=?^_`{|}~]";
var GENERIC_TLDS = "(aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|([a-z]{2}))";
var LINKABLE_REGEXP = new RegExp("\\b(((" + SCHEME_CHARS + ":\\/\\/)|(" + EMAIL_CHARS + "+@))?(" + DOMAIN_CHARS + "+\\." + GENERIC_TLDS + ")(" + PORT_CHARS + "*)(\\/" + URI_PATH_CHARS + "*)?)\\b", "igm");
var GENERIC_TLDS_REGEXP = new RegExp("\\." + GENERIC_TLDS + "\\b");
var MENTION_REGEXP = new RegExp("(\\w*@[a-z][a-z0-9\\-]{2,30}[a-z0-9])", "gi");
var HASHTAG_REGEXP = new RegExp("(#[a-z0-9A-Z][A-Za-z0-9\\_]{0,98}[A-Za-z0-9])\\b", "g");
UI.activateLinks = function(b, a) {
    b = $(b);
    if (!b) {
        return
    }
    Event.addListener(document, "modifiable", function() {
        var c = 0;
        LINKABLE_REGEXP.lastIndex = 0;
        _activateLinks(b, a, c, LINKABLE_REGEXP, _activateLinksOnMatch);
        MENTION_REGEXP.lastIndex = 0;
        _activateLinks(b, a, c, MENTION_REGEXP, _activateMentionsOnMatch);
        HASHTAG_REGEXP.lastIndex = 0;
        _activateLinks(b, a, c, HASHTAG_REGEXP, _activateHashtagsOnMatch)
    })
};

function _activateLinksOnMatch(b, c) {
    if (!b || /\.\./.test(b)) {
        return b
    }
    var f = parseUri(b);
    GENERIC_TLDS_REGEXP.lastIndex = 0;
    if (!GENERIC_TLDS_REGEXP.test(f.host) || (f.host.split(".").length <= 2 && !f.path.length && !f.protocol.length)) {
        return b
    }
    var a = b;
    if (!f.protocol || f.protocol.length < 2) {
        if (/[^\b]@[^\b]/.test(b)) {
            a = "mailto:" + b
        } else {
            a = "http://" + b
        }
    } else {
        if (!/^https?/.test(f.protocol)) {
            a = null
        }
    }
    var d = b;
    if (c) {
        d = teaser(d, c)
    }
    if (a) {
        if (/polyvore.com\//.test(a)) {
            b = '<a rel="nofollow" href="' + a + '">' + d + "</a>"
        } else {
            b = '<a rel="nofollow" target="_blank" href="' + a + '" orighost="' + f.host + '">' + d + "</a>"
        }
    }
    return b
}

function _activateMentionsOnMatch(b, c) {
    if (!b || b.charAt(0) != "@") {
        return b
    }
    var a = buildVanityURL(b.substring(1).toLowerCase());
    b = '<a rel="nofollow" name="' + b + '" href="' + a + '">' + b + "</a>";
    return b
}

function _activateHashtagsOnMatch(b, c) {
    if (!b || b.charAt(0) != "#") {
        return b
    }
    var a = buildURL("hashtag", {
        hashtag: b.substring(1).toLowerCase()
    });
    b = '<a rel="nofollow" href="' + a + '">' + b + "</a>";
    return b
}

function _activateLinks(c, k, f, h, d) {
    f = Number(f) || 0;
    if (c.nodeType == 3) {
        var j = escapeHTML(textContent(c));
        if (j.length + f > 32000) {
            return 0
        }
        var b = splitWithMatches(h, j, d).join("");
        if (j != b) {
            var m = createNode("span", null, null, b);
            var l = c.parentNode;
            l.insertBefore(m, c);
            domRemoveNode(c)
        }
        return j.length
    } else {
        if (c.tagName == "A") {
            return 0
        } else {
            if (c.childNodes && c.childNodes.length > 0) {
                var a = 0;
                for (var g = 0; g < c.childNodes.length; g++) {
                    a += _activateLinks(c.childNodes[g], k, f + a, h, d)
                }
                return a
            }
        }
    }
    return 0
}
if (!window.UI) {
    UI = {}
}
UI.sizeMap = {
    li: {
        url: "s",
        dim: 23
    },
    t: {
        url: "s",
        dim: 50
    },
    s: {
        url: "s",
        dim: 100
    },
    m: {
        url: "m",
        dim: 150
    },
    g: {
        url: "l",
        dim: 268
    },
    l: {
        url: "l",
        dim: 300
    },
    e: {
        url: "e",
        dim: 400
    },
    x: {
        url: "x",
        dim: 500
    },
    y: {
        url: "y",
        dim: 600
    },
    li2: {
        url: "s",
        dim: 22
    },
    t2: {
        url: "s",
        dim: 42
    },
    s3: {
        url: "s",
        dim: 72
    },
    s2: {
        url: "m",
        dim: 124
    },
    m2: {
        url: "l",
        dim: 152
    },
    l2: {
        url: "l",
        dim: 268
    },
    l3: {
        url: "l",
        dim: 214
    }
};
UI.numberSuffixes = "1 K M B T Q".split(" ");
UI.filter2label = {
    category_id: loc("Category"),
    price_int: loc("Price"),
    brand: loc("Brand"),
    color: loc("Color"),
    bgColor: loc("Background Color"),
    displayurl: loc("Store"),
    query: loc("Search"),
    size_class: loc("Size")
};
UI.sortedSizeMap = [];
forEachKey(UI.sizeMap, function(b, a) {
    a.key = b;
    UI.sortedSizeMap.push(a)
});
UI.sortedSizeMap = UI.sortedSizeMap.sort(function(d, c) {
    return d.dim - c.dim
});
UI.getImageKeyForSize = function(b) {
    for (var a = 0; a < UI.sortedSizeMap.length; a++) {
        if (UI.sortedSizeMap[a].dim >= b) {
            return UI.sortedSizeMap[a].key
        }
    }
    return UI.sortedSizeMap[UI.sortedSizeMap.length - 1].key
};
UI.getSmallestImageKeyForSize = function(b) {
    for (var a = 1; a < UI.sortedSizeMap.length; a++) {
        if (UI.sortedSizeMap[a].dim > b) {
            return UI.sortedSizeMap[a - 1].key
        }
    }
    return UI.sortedSizeMap[UI.sortedSizeMap.length - 1].key
};
UI.fontGridRender = function(b, a) {
    var c = createNode("a", {
        href: "#foo",
        title: b.title,
        className: "hover_clickable grid"
    });
    c.appendChild(UI.fontListRender(b, a));
    return c
};
UI.fontListRender = function(c, b) {
    var g = 40;
    b = b || "";
    var a = c.color;
    var d = c.bgColor || "";
    var f = createNode("div", {
        title: c.title,
        className: "hider " + b
    }, {
        height: px(g)
    });
    f.appendChild(createNode("img", {
        src: buildImgURL("img-text.list", {
            font_id: c.font_id,
            color: a,
            height: g,
            width: 2 * g,
            ".out": "png"
        }),
        alt: c.title
    }, {
        backgroundColor: d,
        padding: d ? "0 4px" : ""
    }));
    return f
};
UI.AmazonMP3Render = function(d, c) {
    c = c || "t";
    var a = new Dim(d.w, d.h);
    var f = UI.sizeMap[c].dim;
    a.fit(new Dim(f, f));
    var b = d.imgurl;
    b = b.replace(".jpg", "._SL" + f + "_.jpg");
    return createNode("img", {
        className: "unselectable",
        width: a.w,
        height: a.h,
        src: b
    })
};
UI.contestListRender = function(c) {
    var g = createNode("div", {
        className: "idea contest"
    });
    if (c.imgurl) {
        var d = g.appendChild(createNode("div", {
            className: "left bordered"
        }));
        var b = "t";
        d.appendChild(createImg({
            src: c.imgurl,
            width: UI.sizeMap[b].dim,
            height: UI.sizeMap[b].dim
        }));
        Event.addListener(d, "click", function() {
            Event.trigger(g, "action")
        })
    }
    var a = g.appendChild(createNode("div", {
        className: "body"
    }));
    var f = a.appendChild(createNode("div", {
        className: "clickable"
    }, null, c.title));
    Event.addListener(f, "click", function() {
        Event.trigger(g, "action")
    });
    a.appendChild(createNode("span", {
        className: "meta"
    }, null, [c.group_id ? loc("from {group}", {
        group: createNode("a", {
            href: buildURL("group.show", {
                id: c.group_id
            }),
            target: "_blank"
        }, null, c.group_title)
    }) + ". " : null, plural(c.active_entry_count, loc("entry"), loc("entries"), loc("No entries")).ucFirst(), ". ", loc("{duration} left", {
        duration: duration(c.time_left)
    }), "."]));
    a.appendChild(createNode("p", null, null, c.intro));
    g.appendChild(createNode("div", {
        className: "clear"
    }));
    return g
};
UI.AmazonMP3ListRender = function(c, b) {
    b = b || "t";
    var d = UI.sizeMap[b].dim;
    var a = c.imgurl;
    a = a.replace(".jpg", "._SL" + d + "_.jpg");
    return createNode("a", {
        href: "#foo",
        className: "amazon_mp3"
    }, null, [createNode("div", {
        className: "bordered imgwrap"
    }, {
        width: px(d),
        height: px(d),
        backgroundImage: "url(" + a + ")"
    }), createNode("span", null, null, [c.title, createNode("div", {
        className: "meta"
    }, null, c.artist)]), createNode("br", {
        className: "clear"
    })])
};
UI.itemGridRender = function(b, a) {
    a = a || "s";
    var c = UI.sizeMap[a].dim;
    var d = {
        tid: b.thing_id,
        size: UI.sizeMap[a].url,
        ".out": "jpg"
    };
    if (b.color) {
        d.color = b.color;
        d[".out"] = "png"
    }
    return createNode("a", {
        title: UI.getFullItemTitle(b),
        className: "grid",
        href: "#foo"
    }, null, createImg({
        width: c,
        height: c,
        src: buildImgURL("img-thing", d)
    }))
};
UI.userGridRenderAutoPageSize = function(c, b) {
    b = b || "s";
    var g = UI.sizeMap[b].dim;
    var a = buildImgURL("img-buddy", {
        size: UI.sizeMap[b].url,
        ".out": "jpg",
        id: c.buddyicon
    });
    var d = createNode("img", {
        src: a,
        width: px(g),
        height: px(g)
    });
    var f = c.user_name || c.name;
    var h = createNode("div", {
        className: "meta user_under_text"
    }, {
        width: px(g)
    }, f);
    return createNode("a", {
        title: f,
        className: "grid autosize",
        trackelement: "item",
        href: "#foo"
    }, null, [d, h])
};
UI.itemGridRenderAutoSize = function(g, k, l) {
    k = k || "s";
    l = l || {};
    var c = UI.sizeMap[k].dim;
    var j = {
        size: UI.sizeMap[k].url,
        ".out": "jpg"
    };
    var a = "img-";
    if (g.thing_id) {
        a += "thing";
        j.tid = g.thing_id;
        if (g.color) {
            j.color = g.color;
            j[".out"] = "png"
        }
    } else {
        if (g.spec_uuid || g.cid) {
            a += "set";
            j.cid = g.id || g.cid;
            j.spec_uuid = g.spec_uuid
        } else {
            if (g.buddyicon) {
                a += "buddy";
                j.id = g.buddyicon;
                j.spec_uuid = g.spec_uuid
            } else {
                console.log("WARN: Unknown item type in itemGridRenderAutoSize")
            }
        }
    }
    var h = buildImgURL(a, j);
    var f;
    if (Browser.isIE) {
        var b = createXImg({
            src: h
        }, {
            width: "100%"
        });
        f = b.outer;
        setNode(f, null, {
            height: "100%"
        });
        b.setSrc(h)
    } else {
        f = createNode("img", {
            src: h
        }, {
            width: "100%"
        })
    }
    var d = "";
    if (g.thing_id) {
        d = UI.getFullItemTitle(g)
    } else {
        d = g.name
    }
    return createNode("a", {
        title: d,
        className: "grid autosize " + (l.className || ""),
        trackelement: "item",
        href: "#foo"
    }, null, f)
};
UI.price = function(f, c) {
    if (!c) {
        c = {}
    }
    var b = (f.instock === undefined) ? true : f.instock;
    var d;
    var a = [];
    if (c.outOfStockNoPrice) {
        a.push(createNode("span", {
            className: "oos"
        }, null, loc("sold out").ucFirst()))
    } else {
        if (c.outOfStock) {
            if (f.lc_display_max_price) {
                d = f.lc_display_min_price + "&ndash;" + f.lc_display_max_price;
                a.push(createNode("span", {
                    className: "orig_price"
                }, null, d))
            } else {
                if (f.display_max_price) {
                    d = f.display_min_price + "&ndash;" + f.display_max_price;
                    a.push(createNode("span", {
                        className: "orig_price"
                    }, null, d))
                } else {
                    if (f.lc_display_price) {
                        a.push(createNode("span", {
                            className: "orig_price"
                        }, null, f.lc_display_price))
                    } else {
                        if (f.display_price) {
                            a.push(createNode("span", {
                                className: "orig_price"
                            }, null, f.display_price))
                        }
                    }
                }
            } if (a.length === 0) {
                a.push(createNode("span", {
                    className: "oos"
                }, null, loc("sold out").ucFirst()))
            }
        } else {
            if (f.lc_display_price) {
                if (f.lc_display_max_price && !c.hideRange) {
                    d = f.lc_display_min_price + "&ndash;" + f.lc_display_max_price;
                    a.push(createNode("span", {
                        className: "price"
                    }, null, d))
                } else {
                    a.push(createNode("span", {
                        className: "price"
                    }, null, f.lc_display_price))
                }
            } else {
                if (f.display_price) {
                    if (f.display_max_price && !c.hideRange) {
                        d = f.display_min_price + "&ndash;" + f.display_max_price;
                        a.push(createNode("span", {
                            className: "price"
                        }, null, d))
                    } else {
                        a.push(createNode("span", {
                            className: "price"
                        }, null, f.display_price))
                    }
                }
            } if (c.showOriginalPrice && f.orig_price && b) {
                if (f.orig_max_price && !c.hideRange) {
                    if (f.lc_orig_max_price) {
                        d = f.lc_orig_min_price + "&ndash;" + f.lc_orig_max_price;
                        a.push(createNode("span", {
                            className: "orig_price"
                        }, null, d))
                    } else {
                        d = f.orig_min_price + "&ndash;" + f.orig_max_price;
                        a.push(createNode("span", {
                            className: "orig_price"
                        }, null, d))
                    }
                } else {
                    d = f.lc_orig_price || f.orig_price;
                    a.push(createNode("span", {
                        className: "orig_price"
                    }, null, d))
                }
            }
            if (c.showUnlocalizedPrice && f.lc_display_price && f.display_price !== f.lc_display_price) {
                if (f.display_max_price && !c.hideRange) {
                    d = "(" + f.display_min_price + "&ndash;" + f.display_max_price + ")";
                    a.push(createNode("span", {
                        className: "price"
                    }, null, d))
                } else {
                    d = "(" + f.display_price + ")";
                    a.push(createNode("span", {
                        className: "price"
                    }, null, d))
                }
            }
        }
    }
    return a
};
UI.priceAndLink = function(h, j) {
    if (!j) {
        j = {}
    }
    var a = (h.instock === undefined) ? true : h.instock;
    var k = [];
    var c = UI.price(h, j);
    for (var b = 0; b < c.length; b++) {
        k.push(c[b])
    }
    var d = {
        href: h.url,
        paidurl: h.paid_url,
        orighost: h.displayurl,
        oid: Track.classAndId("thing", h.thing_id)
    };
    if (j.showNotbuyableTooltip && !a) {
        d.showtooltip = true
    }
    if (h.actual_bid_token) {
        d.cpc = h.actual_bid_token
    }
    var g = j.displayUrlMaxSize;
    var f = outboundLink(d, null, g ? teaser(h.displayurl, g) : h.displayurl);
    if (j.showNotbuyableTooltip && !a) {
        Event.addListener(f, "click", function(l) {
            return Event.stop(l)
        })
    }
    k.push(f);
    return k
};
UI.itemNotbuyableTooltipRender = function(c) {
    if (!Auth.isLoggedIn()) {
        return UI.thingOutOfStockUpsell(c)
    }
    var b = createNode("div", {
        className: "notbuyable",
        trackcontext: "notbuyable"
    });
    var a = b.appendChild(createNode("ul", {
        className: "container list"
    }));
    addList(a, loc("This item appears to be sold out."));
    addList(a, createNode("a", {
        href: c.url,
        paidurl: c.paid_url,
        cpc: c.actual_bid_token,
        orighost: c.orighost,
        oid: Track.classAndId("thing", c.thing_id),
        target: "_blank",
        trackelement: "continue"
    }, null, loc("Continue to {site} anyway", {
        site: c.orighost
    }) + "?"));
    addList(a, "&nbsp;");
    var d = createNode("div", null, {
        display: "none"
    });
    addList(a, d);
    Ajax.get({
        action: "thing.similar",
        data: {
            id: c.thing_id,
            ship_region: window._shipRegion,
            length: 3,
            ".out": "jsonx"
        },
        hideProgress: true,
        onSuccess: function(f) {
            if (!f || !f.result || !f.result.html) {
                return
            }
            UI.replaceNodes(f.result.replacements);
            str2nodes(f.result.html, d);
            show(d);
            ModalDialog.rePosition()
        }
    });
    return b
};
UI.showOutOfStockUpsell = function(a) {
    Event.addListener(document, "modifiable", function() {
        setTimeout(function() {
            ModalDialog.show(UI.thingOutOfStockUpsell(a))
        }, 1500)
    })
};
UI.thingOutOfStockUpsell = function(c) {
    var b = createNode("div", {
        className: "notbuyable oos_upsell",
        trackcontext: "oos_upsell"
    });
    b.appendChild(createNode("h2", {
        className: "title"
    }, null, [loc("This item is out of stock")]));
    b.appendChild(createNode("p", {
        className: "desc"
    }, null, loc("Weâ€™ll let you know when itâ€™s available again")));
    var a = createNode("a", {
        href: buildURL("register"),
        className: "btn register"
    }, null, loc("Sign me up for in-stock email alerts"));
    b.appendChild(createNode("div", null, null, a));
    Event.addListener(a, "click", function(d) {
        SignInBox.signInOrRegister({
            src: "oos_upsell",
            onSuccess: function() {
                Feedback.message(loc("We will let you know when this item is back in stock!"))
            }
        });
        return Event.stop(d)
    });
    b.appendChild(createNode("div", {
        className: "continue"
    }, null, createNode("a", {
        href: c.url,
        paidurl: c.paid_url,
        cpc: c.actual_bid_token,
        orighost: c.orighost,
        oid: Track.classAndId("thing", c.thing_id),
        target: "_blank",
        trackelement: "continue"
    }, null, loc("Continue to {site} anyway?", {
        site: c.orighost
    }))));
    Ajax.get({
        action: "thing.similar",
        data: {
            id: c.thing_id,
            ship_region: window._shipRegion,
            length: 3,
            ".out": "jsonx"
        },
        hideProgress: true,
        onSuccess: function(f) {
            if (!f || !f.result || !f.result.html) {
                return
            }
            var d = str2nodes(f.result.html);
            b.appendChild(createNode("div", {
                className: "similar"
            }, null, d.nodes));
            d.js();
            ModalDialog.rePosition()
        }
    });
    return b
};
UI.renderBreadcrumb = function(d) {
    if (!d) {
        return null
    }
    var a = createNode("div", {
        className: "breadcrumb"
    });
    for (var c = 0; c < d.length; c++) {
        var b = d[c];
        a.appendChild(createNode("a", {
            href: b.url
        }, null, b.anchor));
        if (c + 1 < d.length) {
            a.appendChild(createNode("span", null, null, " &gt; "))
        }
    }
    return a
};
UI.itemShopToolTipRender = function(o) {
    var q = "l2";
    var l = UI.sizeMap[q];
    var j = outboundLink({
        title: o.title,
        href: o.url,
        paidurl: o.paid_url,
        cpc: o.actual_bid_token,
        orighost: o.displayurl,
        trackelement: "img",
        oid: Track.classAndId("thing", o.thing_id)
    });
    o.imgurl = buildImgURL("img-thing", {
        tid: o.thing_id,
        size: UI.sizeMap[q].url,
        ".out": "jpg"
    });
    o.clickurl = null;
    var g = j.appendChild(UI.renderItem(o, {
        size: q
    }));
    var c = createNode("div");
    c.appendChild(j);
    var h = createNode("ul", {
        className: "list",
        trackelement: "tooltip"
    });
    if (o.breadcrumb) {
        addList(h, UI.renderBreadcrumb(o.breadcrumb))
    }
    if (o.title) {
        addList(h, createNode("h2", null, null, o.title))
    }
    addList(h, UI.priceAndLink(o, {
        showOriginalPrice: true,
        showUnlocalizedPrice: true
    }));
    if (o.description) {
        addList(h, UI.renderMoreText({
            text: o.description,
            numLines: 5
        }), {
            className: "description"
        })
    }
    var b;
    if (o.offer) {
        b = UI.renderOffer(o.offer);
        Track.stat("inc", "hover_offer", ["view", "y", o.displayurl])
    } else {
        Track.stat("inc", "hover_offer", ["view", "n", o.displayurl])
    }
    var p = buildURL("thing", {
        id: o.thing_id
    }, o.seo_title);
    var a = "shop_actions";
    var n = o.in_user_items;
    var d = null;
    var k;
    if (!Auth.isLoggedIn()) {
        k = loc("Tell me when this is on sale");
        d = {
            none_yet: k,
            no_likes: k,
            others: k
        }
    }
    var m = [UI.buyButtonRender(o, {
        label: true,
        sprite: true
    }), UI.renderFavoritesNew({
        className: "btn",
        type: "thing",
        id: o.thing_id,
        can_change_fav: true,
        fav_count: o.save_count,
        is_user_fav: n,
        labels: d,
        oid: "st:likeit"
    })];
    if (b) {
        m.unshift(b)
    }
    addList(h, createNode("ul", {
        className: "actions new_actions clearfix",
        trackcontext: a
    }, null, m.map(function(r) {
        return createNode("li", null, null, r)
    })));
    Track.trackCTR(a, ["st"], {
        root: h
    });
    addList(h, createNode("a", {
        href: p,
        target: "_blank"
    }, null, loc("Related looks and items") + " &raquo;"));
    var f = createNode("div", {
        className: "items"
    });
    addList(h, f);
    Ajax.get({
        action: "thing.similar",
        data: {
            id: o.thing_id,
            ship_region: window._shipRegion,
            length: 3,
            size: "s2"
        },
        hideProgress: true,
        onSuccess: function(r) {
            if (!r.result || !r.result.items || r.result.items.length < 3) {
                return
            }
            f.appendChild(UI.layoutN(r.result.items, {
                n: 3,
                size: "s2",
                renderer: function(s) {
                    s.clickurl = buildURL("shop", {
                        tid: s.thing_id,
                        thing_details: 1
                    });
                    return UI.renderItem(s, {
                        size: "s2",
                        className: "hoverborder"
                    })
                }
            }))
        }
    });
    return ToolTip.renderImageAndDetails(c, h)
};
UI.renderOffer = function(a) {
    return createNode("div", {
        className: "offer clearfix"
    }, null, [createSprite("sale"), createNode("div", {
        className: "offer_text"
    }, null, a)])
};
UI.itemOverlayRender = function(d, c) {
    if (!c) {
        c = "m"
    }
    var f = UI.sizeMap[c].dim;
    var h = d.clickurl || buildURL("thing", {
        id: d.thing_id
    }, d.seo_title);
    var a = createNode("a", {
        title: d.title,
        href: h
    }, null, createImg({
        className: "bordered",
        width: f,
        height: f,
        src: d.imgurl/*buildImgURL("img-thing", {
            tid: d.thing_id,
            size: UI.sizeMap[c].url,
            ".out": "jpg"
        })*/
    }));
    var g = createNode("div", {
        className: "tooltip_overlay details"
    });
    var b = g.appendChild(createNode("ul", {
        className: "list"
    }));
    if (d.title) {
        addList(b, createNode("a", {
            title: d.title,
            href: h
        }, null, teaser(d.title, 65)))
    }
    addList(b, UI.priceAndLink(d, {
        showOriginalPrice: true,
        showUnlocalizedPrice: true,
        showNotbuyableTooltip: true,
        displayUrlMaxSize: 25
    }));
    /*addList(b, UI.renderFavoritesNew({
        type: "thing",
        id: d.thing_id,
        can_change_fav: true,
        no_sprite: true,
        className: "meta",
        context: d.fav_context,
        labels: {
            no_likes: loc("Like this item"),
            just_me: loc("I like this")
        }
    }));
    addList(b, createNode("a", {
        className: "meta",
        href: h
    }, null, loc("View sets with this item")));*/
    if (d.shop_link) {
        addList(b, createNode("a", {
            className: "meta",
            href: d.shop_link.url
        }, null, loc("Shop for {items}", {
            items: d.shop_link.anchor
        })))
    }
    return ToolTip.renderImageAndDetails(a, g)
};
UI.buyButtonRender = function(c, b) {
    b = b || {};
    b.className = b.className || "btn btn_buy";
    var a = b.label ? loc("Buy at {store}", {
        store: c.displayurl
    }) : loc("Buy");
    if (b.sprite) {
        a = outerHTML(createSprite("buyit")) + " " + a
    }
    return outboundLink({
        className: b.className,
        href: c.url,
        paidurl: c.paid_url,
        cpc: c.actual_bid_token,
        orighost: c.displayurl,
        trackelement: "buy",
        oid: Track.classAndId("thing", c.thing_id),
        target: "_blank",
        hideFocus: 1
    }, null, a)
};
UI.renderRecommendedContact = function(d, g) {
    if (!g) {
        g = {}
    }
    var c = createNode("div", {
        className: "rec_follow " + (g.contact_class || "")
    });
    if (!d.buddyicon) {
        d.buddyicon = -1
    }
    var h = buildURL("profile", {
        id: d.user_id,
        name: d.user_name
    });
    c.appendChild(createNode("div", {
        className: "left icon"
    }, null, createNode("a", {
        href: h,
        target: "_blank"
    }, null, createImg({
        src: buildImgURL("img-buddy", {
            id: d.buddyicon,
            ".out": "jpg",
            size: "s"
        }),
        width: 50,
        height: 50,
        title: d.user_name,
        className: "bordered"
    }))));
    var b = c.appendChild(createNode("div", {
        className: "info"
    }));
    b.appendChild(createNode("div", {
        className: "name"
    }, null, createNode("a", {
        href: h,
        target: "_blank"
    }, null, d.user_name)));
    b.appendChild(createNode("div", {
        className: "meta"
    }, null, d.user_meta));
    var f = createNode("span", {
        className: "text"
    }, null, loc("Follow"));
    var a = b.appendChild(createNode("span", {
        className: "btn follow_action " + (g.action_class || "")
    }, null, f));
    Follow.init(a, {
        user_id: d.user_id,
        user_name: d.user_name,
        no_popup: true,
        count_node: null,
        follower_count: d.follower_count,
        stat: g.stat,
        contact_list_name: g.contact_list_name,
        text_node: f
    });
    return c
};
UI.amazonMP3OverlayRender = function(g) {
    var c = "m";
    var h = UI.sizeMap[c].dim;
    var f = createNode("ul", {
        className: "list"
    });
    g.w *= 100;
    g.h *= 100;
    var d = UI.AmazonMP3Render(g, "m");
    var b = f.appendChild(createNode("li", {
        className: "left"
    }, null, d));
    addClass(d, "grid");
    addList(f, teaser(g.title, 100));
    addList(f, outboundLink({
        href: g.url,
        target: "_blank"
    }, null, g.displayurl));
    var j = AmazonWidget.getMp3Html({
        width: 120,
        height: 90,
        asin: g.asin
    });
    var a = addList(f, "");
    window.setTimeout(function() {
        replaceChild(a, j)
    }, 0);
    return f
};
UI.setGridRender = function(b, a) {
    if (!a) {
        a = "s"
    }
    var c = UI.sizeMap[a].dim;
    var d = {
        spec_uuid: b.spec_uuid,
        size: UI.sizeMap[a].url,
        ".out": "jpg"
    };
    if (b.type == "c") {
        d.cid = b.id
    }
    return createNode("a", {
        title: b.title,
        className: "grid",
        href: "#foo"
    }, null, createImg({
        width: c,
        height: c,
        src: buildImgURL("img-set", d)
    }))
};
UI.buildLookbookImgURL = function(b, a) {
    if (b.spec_uuid) {
        return buildImgURL("img-set", {
            cid: b.id,
            spec_uuid: b.spec_uuid,
            size: UI.sizeMap[a].url,
            ".out": "jpg"
        })
    } else {
        if (b.thing_id) {
            return buildImgURL("img-thing", {
                tid: b.thing_id,
                size: UI.sizeMap[a].url,
                ".out": "jpg"
            })
        } else {
            if (b.buddyicon) {
                return buildImgURL("img-buddy", {
                    id: b.buddyicon,
                    size: UI.sizeMap[a].url,
                    ".out": "jpg"
                })
            }
        }
    }
};
UI.setGridRenderLookbook = function(d, c) {
    if (!c) {
        c = "s"
    }
    var b = createNode("div", {
        className: "lookbookedit lookbookOuter"
    });
    var g = createNode("img", {
        src: UI.buildLookbookImgURL(d, c)
    });
    var a = UI.renderStack(createNode("a", {
        title: d.title,
        href: "#foo"
    }, {
        textDecoration: "none"
    }, g));
    addClass(a, "mod_stack_size_" + c);
    b.appendChild(a);
    if (d.title) {
        var f = b.appendChild(createNode("div", {
            className: "under_long"
        }, null, d.title))
    }
    return b
};
UI.renderStackUIC = function(g, a) {
    var j = a.id || Dom.uniqueId();
    var d = a.className || "";
    var c = a.size || "s";
    var h = a.renderer || UI.renderItem;
    var f = h(g, {
        size: c
    });
    var b = ["mod_stack", d, "size_" + c].filter(function(k) {
        return !!k
    });
    return createNode("div", {
        id: j,
        className: b.join(" ")
    }, null, createNode("div", {
        className: "mod_stack_layer"
    }, null, createNode("div", {
        className: "mod_stack_layer"
    }, null, createNode("div", {
        className: "mod_stack_content"
    }, null, f))))
};
UI.renderStack = function(a) {
    return createNode("div", {
        className: "mod_stack"
    }, null, createNode("div", {
        className: "mod_stack_layer"
    }, null, createNode("div", {
        className: "mod_stack_layer"
    }, null, createNode("div", {
        className: "mod_stack_layer"
    }, null, a))))
};
UI.setGridRenderAutoSize = function(d, c, b) {
    c = c || "s";
    b = b || {};
    var h = UI.sizeMap[c].dim;
    var j = {
        spec_uuid: d.spec_uuid,
        size: UI.sizeMap[c].url,
        ".out": "jpg"
    };
    if (d.id && d.type == "c") {
        j.cid = d.id
    }
    var a = buildImgURL("img-set", j);
    var f;
    if (Browser.isIE) {
        var g = createXImg({
            src: a
        }, {
            width: "100%"
        });
        f = g.outer;
        setNode(f, null, {
            height: "100%"
        });
        g.setSrc(a);
        f._image = g
    } else {
        f = createNode("img", {
            src: a
        }, {
            width: "100%"
        })
    }
    return createNode(b.tag || "a", {
        title: d.title,
        className: "grid autosize " + (b.className || ""),
        href: "#foo"
    }, null, f)
};
UI.setCarouselRenderWithBy = function(d, c) {
    if (!c) {
        c = "t"
    }
    var f = UI.sizeMap[c].dim;
    var a = d.clickurl ? d.clickurl : buildURL("set", {
        id: d.id
    }, d.seo_title);
    var b = createNode("a", {
        className: "hoverborder",
        href: a
    }, null, createImg({
        width: f,
        height: f,
        title: d.title,
        src: d.imgurl ? d.imgurl : buildImgURL("img-set", {
            cid: d.id,
            spec_uuid: d.spec_uuid,
            size: UI.sizeMap[c].url,
            ".out": "jpg"
        })
    }));
    var g = [];
    if (d.userurl || d.user_id) {
        g = [loc("by") + " ", createNode("a", {
            href: d.userurl ? d.userurl : buildURL("profile", {
                id: d.user_id,
                name: d.user_name
            })
        }, null, d.user_name)]
    } else {
        if (d.itemtitle) {
            g = [createNode("a", {
                href: a
            }, null, d.itemtitle)]
        }
    }
    return createNode("div", {
        className: "grid"
    }, null, [b, addClass(createNode("span", {
        className: "under"
    }, null, g), "size_" + c)])
};
UI.setCarouselRenderWithLike = function(d, c) {
    if (!c) {
        c = "t"
    }
    var f = UI.sizeMap[c].dim;
    var b = createNode("a", {
        className: "hoverborder",
        href: buildURL("set", {
            id: d.id
        }, d.seo_title)
    }, null, createImg({
        width: f,
        height: f,
        src: d.imgurl ? d.imgurl : buildImgURL("img-set", {
            cid: d.id,
            spec_uuid: d.spec_uuid,
            size: UI.sizeMap[c].url,
            ".out": "jpg"
        })
    }));
    var a = UI.renderFavoritesNew({
        id: d.id,
        type: "set",
        fav_count: d.fav_count,
        is_user_fav: d.is_user_fav,
        can_change_fav: false
    });
    return createNode("div", {
        className: "grid"
    }, null, [b, createNode("div", {
        className: "under_carousel under size_" + c
    }, null, a)])
};
UI.renderFavoritesCompact = function(b) {
    if (!b.type || !b.id || !b.can_change_fav) {
        return null
    }
    var f = mergeObject({
        like_this: loc("Like"),
        you_like_this: loc("I like this")
    }, b.labels || {});
    if (!b.no_sprite) {
        var c = outerHTML(createSprite("likeit"));
        forEachKey(f, function(g) {
            f[g] = c + "<span>" + f[g] + "</span>"
        })
    }
    b.node_id = b.node_id || ("favorite_" + b.type + "_" + b.id);
    var d = createNode("span", {
        id: b.node_id,
        className: (b.className || "") + (b.can_change_fav ? " clickable" : ""),
        oid: b.oid,
        trackelement: "likeit",
        title: (b.is_user_fav ? loc("Unlike") : loc("Like"))
    }, null, createNode("span", {
        className: "action_label"
    }, null, (b.is_user_fav ? f.you_like_this : f.like_this)));
    var a = new LikeItToggle({
        id: b.id,
        type: b.type,
        is_user_fav: b.is_user_fav,
        button: d,
        liked_button: f.you_like_this,
        not_liked_button: f.like_this,
        context: b.context
    });
    return d
};
UI.renderFavoritesInline = function(b) {
    b = b || {};
    var c = (parseInt(b.fav_count, 10) || 0) - (b.is_user_fav ? 1 : 0);
    var a = (parseInt(b.fav_count, 10) || 0) + (b.is_user_fav ? 0 : 1);
    b.labels = mergeObject({
        none_yet: loc("0"),
        no_likes: loc("Like"),
        just_me: loc("1"),
        others: c,
        others_me: a
    }, b.labels || {});
    return UI.renderFavoritesNew(b)
};
UI.renderFavoritesNew = function(b) {
    b = b || {};
    b.className = b.className || "";
    b._new_favorites = true;
    var d = (parseInt(b.fav_count, 10) || 0) - (b.is_user_fav ? 1 : 0);
    var c = (parseInt(b.fav_count, 10) || 0) - (b.is_user_fav ? 1 : 0);
    var a = (parseInt(b.fav_count, 10) || 0) + (b.is_user_fav ? 0 : 1);
    var f = mergeObject({
        none_yet: loc("0 likes"),
        no_likes: loc("Like"),
        just_me: loc("1 like"),
        others: pluralNumber(c, loc("like"), loc("likes")),
        others_me: pluralNumber(a, loc("like"), loc("likes"))
    }, b.labels || {});
    if (!d && !b.can_change_fav) {
        b.labels = {
            like_this: f.none_yet
        }
    } else {
        if (d) {
            b.labels = {
                like_this: f.others,
                you_like_this: f.others_me
            }
        } else {
            b.labels = {
                like_this: f.no_likes,
                you_like_this: f.just_me
            }
        }
    } if (b.is_user_fav) {
        b.className = ["faved", b.className].join(" ")
    }
    return UI.renderFavoritesCompact(b)
};
UI.itemListRender = function(f, b) {
    if (!b) {
        b = {}
    }
    var d = b.size || "m";
    var h = UI.sizeMap[d].dim;
    var c = createNode("ul", {
        className: "list"
    });
    var g = f.clickUrl || buildURL("thing", {
        id: f.thing_id
    }, f.seo_title);
    var a = createNode("a", {
        href: g,
        target: "_blank"
    }, null, createImg({
        className: "bordered",
        width: h,
        height: h,
        src: UI.buildLookbookImgURL(f, d)
    }));
    if (b.dragableImage) {
        Event.addListener(a, "dragstart", function(j) {
            j.xDataTransfer.setData("item", f);
            j.xDataTransfer.proxy = createNode("img", {
                src: UI.buildLookbookImgURL(f, "s"),
                width: 50,
                height: 50
            });
            Event.stop(j)
        })
    }
    c.appendChild(createNode("li", {
        className: "left"
    }, null, a));
    if (f.title) {
        addList(c, f.title)
    }
    return c
};
UI.setListRender = function(f, b) {
    if (!b) {
        b = {}
    }
    var d = b.size || "m";
    var h = UI.sizeMap[d].dim;
    var c = createNode("ul", {
        className: "list"
    });
    var g = f.clickUrl || buildURL("set", {
        id: f.id
    }, f.seo_title);
    var a = createNode("a", {
        href: g,
        target: "_blank"
    }, null, createImg({
        className: "bordered",
        width: h,
        height: h,
        src: buildImgURL("img-set", {
            cid: f.id,
            spec_uuid: f.spec_uuid,
            size: UI.sizeMap[d].url,
            ".out": "jpg"
        })
    }));
    if (b.dragableImage) {
        Event.addListener(a, "dragstart", function(j) {
            j.xDataTransfer.setData("set", f);
            j.xDataTransfer.proxy = createNode("img", {
                src: buildImgURL("img-set", {
                    cid: f.id,
                    spec_uuid: f.spec_uuid,
                    size: "s",
                    ".out": "jpg"
                }),
                width: 50,
                height: 50
            });
            Event.stop(j)
        })
    }
    c.appendChild(createNode("li", {
        className: "left"
    }, null, a));
    if (f.title) {
        addList(c, f.title)
    }
    return c
};
UI.draftListRender = function(f, b) {
    if (!b) {
        b = {}
    }
    var d = b.size || "m";
    var g = UI.sizeMap[d].dim;
    var c = createNode("ul", {
        className: "list"
    });
    var h = {
        spec_uuid: f.spec_uuid,
        size: UI.sizeMap[d].url,
        ".out": "jpg"
    };
    var a = createImg({
        className: "bordered",
        width: g,
        height: g,
        src: buildImgURL("img-set", h)
    });
    if (b.dragableImage) {
        Event.addListener(a, "dragstart", function(j) {
            j.xDataTransfer.setData("draft", f);
            j.xDataTransfer.proxy = createNode("img", {
                src: buildImgURL("img-set", h),
                width: 50,
                height: 50
            });
            Event.stop(j)
        })
    }
    c.appendChild(createNode("li", {
        className: "left"
    }, null, a));
    if (f.updated_ago) {
        c.appendChild(createNode("li", null, null, f.updated_ago))
    }
    return c
};
UI.lookbookListRender = function(f, b) {
    if (!b) {
        b = {}
    }
    var d = b.size || "m";
    var g = UI.sizeMap[d].dim;
    var c = createNode("ul", {
        className: "list"
    });
    var a = createImg({
        className: "bordered",
        width: g,
        height: g,
        src: UI.buildLookbookImgURL(f, d)
    });
    if (f.id) {
        a = createNode("a", {
            href: buildURL("collection", {
                id: f.id
            }, f.seo_title),
            target: "_blank"
        }, null, a)
    }
    if (b.dragableImage) {
        Event.addListener(a, "dragstart", function(h) {
            h.xDataTransfer.setData("lookbook", f);
            h.xDataTransfer.proxy = createNode("img", {
                src: UI.buildLookbookImgURL(f, "s"),
                width: 50,
                height: 50
            });
            Event.stop(h)
        })
    }
    c.appendChild(createNode("li", {
        className: "left"
    }, null, a));
    if (f.title) {
        c.appendChild(createNode("li", null, null, f.title))
    }
    return c
};
UI.fontIconRender = function(b, a) {
    a = a || "t";
    var c = UI.sizeMap[a].dim;
    return createImg({
        width: c,
        height: c,
        title: b.title,
        src: buildImgURL("img-text.icon", {
            font_id: b.font_id,
            size: UI.sizeMap[a].url,
            ".out": "jpg"
        })
    })
};
UI.getFullItemTitle = function(a) {
    var b = a.title || a.name || "";
    if (b) {
        if (a.display_price) {
            b += " - " + a.display_price
        }
        if (a.displayurl) {
            b += " - " + a.displayurl
        }
    }
    return b
};
UI.itemRender = function(b, a) {
    a = a || "t";
    var c = UI.sizeMap[a].dim;
    var d = {
        tid: b.thing_id,
        size: UI.sizeMap[a].url,
        ".out": "jpg"
    };
    if (b.color) {
        d.color = b.color;
        d[".out"] = "png"
    }
    return createImg({
        width: c,
        height: c,
        title: UI.getFullItemTitle(b),
        src: buildImgURL("img-thing", d)
    })
};
UI.setRenderWithLink = function(b, a) {
    return createNode("a", {
        target: "_blank",
        href: buildURL("set", {
            id: b.id
        }, b.seo_title)
    }, {
        display: "block",
        position: "relative"
    }, UI.setRender(b, a))
};
UI.setRender = function(b, a) {
    a = a || "t";
    var c = UI.sizeMap[a].dim;
    return createImg({
        className: "img_size_" + a,
        width: c,
        height: c,
        title: b.title,
        src: b.imgurl ? b.imgurl : buildImgURL("img-set", {
            cid: b.id,
            spec_uuid: b.spec_uuid,
            size: UI.sizeMap[a].url,
            ".out": "jpg"
        })
    })
};
UI.renderPerson = function(d, k) {
    k = k || {};
    var c = d.id || d.user_id;
    var b = d.name || d.user_name;
    var g = d.buddyicon || -1;
    var f = buildURL("profile", {
        id: c,
        name: b
    });
    var j = buildImgURL("img-buddy", {
        id: g,
        ".out": "jpg",
        size: "li2"
    });
    var h = UI.renderItem({
        imgurl: j
    }, {
        size: "li2"
    });
    var a = createNode("span", null, null, b);
    if (d.state !== "active" || k.noLink) {
        return createNode("span", {
            className: "render_person"
        }, null, [h, a])
    } else {
        return createNode("a", {
            target: k.newWindow ? "_blank" : "",
            href: f,
            className: "render_person"
        }, null, [h, a])
    }
};
UI.renderBuddyIcon = function(a, j, c, k) {
    var g = a.user_id;
    var h = a.buddyicon || -1;
    j = j || "t";
    var d = UI.sizeMap[j].dim;
    k = k || {};
    var f = k.linkClass || "";
    var b = createNode("img", {
        src: buildImgURL("img-buddy", {
            id: h,
            ".out": "jpg",
            size: UI.sizeMap[j].url
        }),
        width: d,
        height: d,
        className: "bordered buddyicon img_size_" + j,
        alt: a.user_name
    });
    if (g == -1) {
        return b
    } else {
        return createNode("a", {
            href: buildURL("profile", {
                id: g,
                name: a.user_name
            }),
            className: f + " buddy_icon",
            title: a.user_name,
            target: c ? "_blank" : undefined
        }, null, b)
    }
};
UI.renderCommentUIC = function(g, r) {
    r = r || {};
    var q = r.size || "t2";
    var d = r.attSize || "s";
    var f = createNode("a", {
        className: "left",
        href: buildURL("profile", {
            id: g.user.user_id,
            name: g.user.user_name
        })
    }, null, g.user.user_name);
    var l = createNode("div", {
        className: "title clearfix"
    }, null, f);
    var c = [];
    var o = (g.user.user_id == Auth.userId());
    var k = "favorites";
    if (g.user_faved) {
        k += " user_faved"
    }
    if (o) {
        k += " prevent_change"
    }
    c.push({
        listItemClass: k,
        label: [g.id, g.cls, g.fav_count || 0].join("/"),
        action: "void(0)"
    });
    if (o) {
        c.push({
            label: "&nbsp;",
            className: "delete",
            title: loc("Delete"),
            actionClass: "btn",
            listItemClass: "hover_container delete",
            action: function() {
                Comment.del(g.cls || "collection", g.id)
            }
        })
    }
    if (c.length) {
        var j = l.appendChild(createNode("ul", {
            className: "actions inline right"
        }));
        c.forEach(function(s) {
            j.appendChild(UI.renderAction(s));
            j.appendChild(document.createTextNode(" "))
        })
    }
    var m = Math.floor(ts2age(g.createdon_ts));
    if (m > 0) {
        m = loc("wrote {age} ago", {
            age: duration(m)
        })
    } else {
        m = loc("wrote moments ago")
    }
    var p = createNode("div", {
        className: "meta"
    }, null, m.ucFirst());
    var h = createNode("div", {
        className: "list_item_icon"
    }, null, UI.renderBuddyIcon(g.user, q));
    var n = createNode("div", {
        className: "filling_block"
    }, null, [l, p, UI.renderMoreText({
        text: g.comment,
        escape: false
    })]);
    if (r.attachments && r.attachments.length) {
        var a = createNode("ul", {
            className: "attachments clearfix"
        });
        r.attachments.forEach(function(u) {
            var t = createNode("li");
            try {
                if (u.spec_uuid) {
                    u.imgurl = buildImgURL("img-set", {
                        cid: u.id,
                        spec_uuid: u.spec_uuid,
                        size: d,
                        ".out": "jpg"
                    });
                    u.clickurl = buildURL("set", {
                        id: u.id
                    })
                } else {
                    u.imgurl = buildImgURL("img-thing", {
                        tid: u.thing_id,
                        size: d,
                        ".out": "jpg"
                    });
                    u.clickurl = buildURL("thing", {
                        id: u.thing_id
                    })
                }
                t.appendChild(UI.renderItem(u, {
                    size: d
                }));
                if (u.title) {
                    var s = createNode("div");
                    s.appendChild(createNode("a", {
                        href: u.clickurl,
                        className: "hover_clickable"
                    }, null, u.title));
                    t.appendChild(s)
                }
                if (!u.spec_uuid) {
                    t.appendChild(createNode("div", null, null, UI.priceAndLink(u)))
                } else {
                    var w = createNode("a", {
                        href: buildURL("profile", {
                            id: u.user_id,
                            name: u.user_name
                        }),
                        className: "hover_clickable"
                    }, null, u.user_name);
                    t.appendChild(createNode("div", null, null, [loc("By {user_name}", {
                        user_name: w
                    })]))
                }
            } catch (v) {}
            a.appendChild(t)
        });
        var b = createNode("div", {
            className: "list_item_extra"
        });
        b.appendChild(a);
        n.appendChild(b)
    }
    return createNode("div", {
        className: "list_item clearfix " + (g.is_moderator ? "moderator_highlight " : "") + (r.className || "") + ("comment_" + g.id)
    }, null, [h, n])
};
UI.promoteLabels = function(m) {
    var j = m.type;
    if (!j) {
        return
    }
    var d = new Date().getTime() / 1000;
    var b, h, f;
    if (j === "set") {
        b = loc("Promote set");
        h = loc("Unpromote set")
    } else {
        if (j === "thing") {
            b = loc("Promote item");
            h = loc("Unpromote item")
        } else {
            if (j === "collection") {
                b = loc("Promote collection");
                h = loc("Unpromote collection")
            }
        }
    }
    f = h;
    var k = 7 * 24 * 60 * 60;
    var a = future(k);
    var l = m.promotion_end_ts || (k + d);
    var c = future(l - d);
    h = h + ". " + loc("Ends {intime}", {
        intime: c
    });
    f = f + ". " + loc("Ends {intime}", {
        intime: a
    });
    var g = mergeObject(m.labels || {}, {
        promote_label: b,
        unpromote_label: h,
        unpromote_js_label: f
    });
    return g
};
UI.renderActions = function(c, a) {
    a = a || {};
    a.className = a.className || "";
    var b = createNode("ul", {
        className: "actions " + a.className
    });
    c.forEach(function(d) {
        b.appendChild(UI.renderAction(d))
    });
    return b
};
UI.renderAction = function(action) {
    var className = action.className || "";
    var li = createNode("li", {
        className: "actions " + (action.listItemClass || "")
    });
    li.appendChild(createSprite(className));
    var node = null;
    if (action.url) {
        node = createNode("a", {
            href: action.url
        }, null, action.label)
    } else {
        if (action.action) {
            node = createNode("span", {
                className: "clickable"
            }, null, action.label)
        } else {
            node = createNode("span", null, null, action.label)
        }
    }
    li.appendChild(node);
    var onclick = noop;
    if (action.action) {
        if (typeof(action.action) === "string") {
            onclick = function() {
                eval("(" + action.action + ")")
            }
        } else {
            onclick = action.action
        }
    } else {
        if (action.url) {
            onclick = function() {
                window.location = action.url
            }
        }
    } if (action.id) {
        setNode(node, {
            id: action.id
        })
    }
    if (action.disabled) {
        setNode(node, {
            disabled: ""
        })
    }
    if (action.actionClass) {
        addClass(node, action.actionClass)
    }
    if (action.confirm) {
        Event.addListener(node, "click", function(event) {
            ModalDialog.confirm({
                content: action.confirm,
                onOk: onclick
            });
            return Event.stop(event)
        })
    } else {
        Event.addListener(node, "click", function(event) {
            onclick();
            return Event.stop(event)
        })
    }
    return li
};
UI.renderCenterMiddle = function(a) {
    return createNode("table", {
        className: "centermiddle"
    }, null, createNode("tbody", {
        className: "centermiddletbody"
    }, null, createNode("tr", {
        className: "centermiddlerow"
    }, null, createNode("td", {
        className: "centermiddlecell"
    }, null, a))))
};
UI.renderEditorItemAction = function(a, b, d) {
    if (d instanceof Array) {
        var c = d;
        d = function() {
            UI.showItemDropDownMenu(b.id, c)
        }
    }
    return UI.renderEditorItemButton(b, d)
};
UI.showItemDropDownMenu = function(a, c) {
    var b = new DropDownMenu(c);
    Event.addListener(b, "hide", function() {
        if (b) {
            b.destruct();
            b = null
        }
    });
    b.attach($(a), DropDownMenu.POSITION_BOTTOM_LEFT, 150);
    b.show()
};
UI.renderEditorItemButton = function(b, d) {
    var a = {
        id: b.id
    };
    if (b.trackelement) {
        a.trackelement = b.trackelement
    }
    var c = createNode("button", a, null, createSprite("", b.title));
    Event.addListener(c, "click", d);
    Event.addListener(c, "mousedown", Event.stop);
    c.title = b.alt;
    return c
};
UI.displayAjaxMessages = function(b, a) {
    if (!b) {
        return
    }
    b.forEach(function(c) {
        Feedback.message(createNode("span", {
            className: c.type
        }, null, c.content), c.delay || c.duration)
    })
};
UI.modalDisplayAjaxMessages = function(b) {
    if (!b) {
        return
    }
    if (b.length == 1) {
        ModalDialog.alert({
            content: b[0].content
        })
    } else {
        var a = createNode("div");
        b.forEach(function(c) {
            if (a.childNodes.length > 0) {
                a.appendChild(createNode("br"))
            }
            a.appendChild(createNode("h3", null, null, c.content))
        });
        ModalDialog.show_uic({
            title: loc("Errors and Warnings"),
            body: a,
            actions: [{
                label: createNode("span", {
                    className: "btn btn_action"
                }, null, loc("OK")),
                action: ModalDialog.hide
            }]
        })
    }
    return
};
UI.displayAjaxErrors = function(a, b) {
    b = $(b);
    if (b) {
        setNode(b, null, null, a.extractGeneralErrorMessages().join("<br>"))
    }
    if (a.form_error) {
        forEachKey(a.form_error, function(f, c) {
            var d = inOrderTraversal(function(g) {
                return g.name == f
            });
            if (!d) {
                return false
            }
            d.parentNode.appendChild(createNode("div", {
                className: "error"
            }, null, c));
            return true
        })
    }
};
UI.whiteblock = function(b) {
    if (!UI._whiteblock) {
        UI._whiteblock = document.body.appendChild(createNode("div", {
            className: "whiteblock"
        }));
        Event.addListener(UI._whiteblock, "click", UI.hideWhiteblock);
        UI._whiteblockStack = []
    }
    if (b) {
        var a = overlayZIndex(UI._whiteblock);
        if (UI._aboveWhiteblock) {
            UI._whiteblockStack.push(UI._aboveWhiteblock);
            UI._aboveWhiteblock = b;
            setNode(UI._whiteblock, null, {
                zIndex: a
            });
            setNode(UI._aboveWhiteblock, null, {
                zIndex: a + 1
            })
        } else {
            UI._aboveWhiteblock = b;
            setNode(UI._whiteblock, null, {
                zIndex: a
            });
            setNode(UI._aboveWhiteblock, null, {
                zIndex: a + 1
            })
        } if (b.parentNode != document.body) {
            document.body.appendChild(b)
        }
        show(UI._whiteblock);
        show(UI._aboveWhiteblock)
    }
    return UI._whiteblock
};
UI.hideWhiteblock = function() {
    if (UI._aboveWhiteblock && UI._aboveWhiteblock.parentNode) {
        UI._aboveWhiteblock.parentNode.removeChild(UI._aboveWhiteblock);
        UI._aboveWhiteblock = null
    }
    if (UI._whiteblockStack && UI._whiteblockStack.length) {
        UI.whiteblock(UI._whiteblockStack.pop())
    } else {
        hide(UI._whiteblock)
    }
};
UI.renderPolaroids = function(f, m, b) {
    var l = createNode("div");
    for (var c = 0; c < f.length; c++) {
        var k = f[c];
        var g = k["class"];
        var j = (c + 1) % b ? "" : "last";
        var a = l.appendChild(createNode("div", {
            className: "polaroid polaroid_size_" + m + " polaroid_" + g + " " + j
        }));
        var d = a.appendChild(createNode("div"));
        if (g == "collection") {
            d.appendChild(createNode("a", {
                href: k.clickurl
            }, null, UI.setRender(k, m)));
            a.appendChild(createNode("div", {
                className: "under_polaroid no_caption"
            }, null, createNode("div", {
                className: "unit"
            }, null, k.text_under)))
        } else {
            if (g == "thing") {
                d.appendChild(createNode("a", {
                    href: k.clickurl
                }, null, UI.itemRender(k, m)));
                var h = a.appendChild(createNode("div", {
                    className: "under_polaroid no_caption"
                }, null, createNode("div", {
                    className: "unit"
                }, null, k.text_under)));
                h.appendChild(createNode("div", {
                    className: "unit shop_link"
                }, null, UI.priceAndLink(k, {
                    showOriginalPrice: true
                })))
            }
        }
    }
    return l
};
UI.renderSetStream = function(a) {
    container = $(a.container);
    var f = a.stream;
    var h = a.cid;
    var b = a.size || "s2";
    var c = function(j, k) {
        switch (k.type) {
            case "fav":
                return buildURL("set", {
                    id: j.id,
                    faved_by: k.fav_userid
                }, j.seo_title);
            case "lookbook":
                return buildURL("set", {
                    id: j.id,
                    lid: k.lid
                }, j.seo_title);
            case "set":
                return buildURL("set", {
                    id: j.id,
                    stream: null
                }, j.seo_title)
        }
    };
    clearNode(container);
    var g = container.appendChild(createNode("div", {
        className: "car"
    }));
    g.appendChild(createNode("center", null, {
        height: "100%"
    }, createNode("span", {
        className: "loading"
    }, {
        height: "100%"
    })));
    makeUnselectable(g);
    var d;
    if (f.items) {
        d = new MemDataSource(f.items);
        yield(d.ensureLoaded, d)
    } else {
        d = new AjaxDataSource(f.datasource.action, f.datasource.params);
        Event.addListener(window, "load", d.ensureLoaded, d)
    }
    Event.addSingleUseListener(d, "loaded", function() {
        clearNode(g);
        var l = a.visible_items;
        var j = a.index;
        if (!j && j !== 0) {
            j = d.values().find({
                id: h
            }, function(o, n) {
                return o.id == n.id
            });
            j = Math.min(j, d.values().length - l);
            j = Math.max(j, 0)
        }
        var k = new CarouselWindow({
            data: d,
            duration: 300,
            className: "thin_carousel",
            renderer: function(p) {
                var n = UI.setRender(p, b);
                var o = createNode("a", {
                    href: c(p, f),
                    className: "hoverborder item " + (p.id == h ? "current" : "")
                }, null, n);
                return o
            },
            size: l,
            index: j
        });
        g.appendChild(k.getNode());
        var m = new FloatingPaginator({
            carouselWindow: k,
            container: g,
            className: "streamPag"
        });
        m.redraw();
        setNode(g, {
            trackcontext: "car." + f.type
        });
        setNode(m.next, {
            trackelement: "next"
        });
        setNode(m.prev, {
            trackelement: "prev"
        });
        k.redraw()
    })
};
UI.itemsGridRender = function(c, f) {
    var h = UI.sizeMap[f].dim;
    var b = createNode("div", {
        className: "grids"
    });
    for (var d = 0; d < c.length; d++) {
        var g = c[d];
        var a = b.appendChild(UI.itemGridRender(g, f));
        if (g.clickurl) {
            a.setAttribute("href", g.clickurl)
        }
    }
    return b
};
UI.setsGridRender = function(d, c) {
    var f = UI.sizeMap[c].dim;
    var a = createNode("div", {
        className: "grids"
    });
    for (var b = 0; b < d.length; b++) {
        a.appendChild(UI.setGridRender(d[b], c))
    }
    return a
};
UI.highchart = function(options) {
    options = options || {};
    options.tooltip = options.tooltip || {};
    options.yAxis = options.yAxis || {};
    options.yAxis.labels = options.yAxis.labels || {};
    options.xAxis = options.xAxis || {};
    options.xAxis.labels = options.xAxis.labels || {};
    if (options.tooltip.formatter) {
        eval("(options.tooltip.formatter = " + options.tooltip.formatter + ")")
    }
    if (options.yAxis.labels.formatter) {
        eval("(options.yAxis.labels.formatter = " + options.yAxis.labels.formatter + ")")
    }
    if (options.xAxis.labels.formatter) {
        eval("(options.xAxis.labels.formatter = " + options.xAxis.labels.formatter + ")")
    }
    if (options.defaultSeriesGroup && options.allSeriesGroups && !options.series) {
        options.series = options.allSeriesGroups[options.defaultSeriesGroup]
    }
    var chart = new Highcharts.Chart(options);
    if (options.defaultSeriesGroup && options.allSeriesGroups) {
        chart.allSeriesGroups = options.allSeriesGroups
    }
    return chart
};
UI.highchart.showSeries = function(a, d) {
    if (jQuery(a) === undefined) {
        return true
    }
    var c = jQuery(a).highcharts();
    if (c.allSeriesGroups === undefined || !(d in c.allSeriesGroups)) {
        return true
    }
    while (c.series.length > 0) {
        c.series[0].remove(false)
    }
    var b = c.allSeriesGroups[d];
    jQuery.each(b, function(f, g) {
        c.addSeries(g, false)
    });
    c.redraw();
    return false
};
UI.highchart.FORMAT_NUMBER_SHORT = function() {
    return UI.formatNumberShort(this.value)
};
UI.highchart.FORMAT_DATE_TT_ANALYTICS = function() {
    var c = this.point.y_raw === undefined ? this.y : this.point.y_raw;
    var b = 0;
    var a = mantissa(c);
    if (a) {
        b = Math.min(("" + a).length, 5)
    }
    return [Highcharts.dateFormat("%b %d, %Y", new Date(this.x), 1), "<br>", this.series.name, ": ", "<b>", Highcharts.numberFormat(c, b), "</b>"].join("")
};
UI.highchart.FORMAT_HRS_AGO_XLABEL = function() {
    return this.value > 1 ? this.value - 1 : loc("Now")
};
UI.highchart.FORMAT_HRS_AGO_TT = function() {
    var a;
    switch (this.x) {
        case 1:
            a = loc("Current hour");
            break;
        case 2:
            a = loc("Past hour");
            break;
        default:
            a = loc("{age} hours ago", {
                age: this.x - 1
            })
    }
    return [a, " : ", "<b>", this.y, "</b>", " ", this.series.name].join("")
};
UI.highchart.FORMAT_DATE_TT = function() {
    var a = this.point.y_raw === undefined ? this.y : this.point.y_raw;
    return [Highcharts.dateFormat("%b %d", new Date(this.x), 1), " : ", "<b>", UI.formatNumberShort(a), "</b>", " ", this.series.name].join("")
};
UI.formatNumberShort = function(b) {
    if (b < 1000) {
        return b
    }
    var a = 0;
    while (b >= 1000 && UI.numberSuffixes[a + 1]) {
        b /= 1000;
        ++a
    }
    b = b > 10 ? Math.round(b) : round(b, 0.1);
    return b + UI.numberSuffixes[a]
};
UI.colorBlockRender = function(d, a) {
    var b;
    if (a.w) {
        b = a
    } else {
        b = new Dim(UI.sizeMap[a].dim, UI.sizeMap[a].dim)
    }
    var c = createNode("div", {
        color: d.color.toUpperCase()
    }, {
        position: "relative",
        backgroundColor: d.color,
        width: px(b.w),
        height: px(b.h)
    });
    if (d.color.toUpperCase() == "#FFFFFF") {
        c.appendChild(createNode("div", {
            className: "whiteHighlight"
        }))
    }
    return createNode("a", {
        title: d.title || loc("Rectangle"),
        className: "colorblock",
        href: "#foo"
    }, null, c)
};
UI.colorBlockAutoSizeRender = function(d, a) {
    var b;
    if (a.w) {
        b = a
    } else {
        b = new Dim(UI.sizeMap[a].dim, UI.sizeMap[a].dim)
    }
    var c = createNode("div", {
        color: d.color.toUpperCase()
    }, {
        position: "relative",
        backgroundColor: d.color,
        width: "100%",
        height: "100%"
    });
    if (d.color.toUpperCase() == "#FFFFFF") {
        c.appendChild(createNode("div", {
            className: "whiteHighlight"
        }))
    }
    return createNode("a", {
        title: d.title || loc("Rectangle"),
        className: "grid shape autosize",
        href: "#foo"
    }, null, c)
};
UI.renderDropDownMenu = function(b, d) {
    var g = b.choices || [];
    if (g.length === 0) {
        return d
    }
    var f = b.id || Dom.uniqueId("drop_down_menu");
    var a = b.anchor_id || Dom.uniqueId("drop_down_menu_anchor");
    var c = createNode("span", {
        id: f,
        className: "mod_drop_down_menu"
    }, null, [createNode("span", {
        className: "content"
    }, null, d), createNode("span", {
        id: a,
        className: "anchor"
    }, null, [createNode("span", {
        className: "mod_arrow down blue"
    }, null, "")])]);
    DropDownMenu.createNavDropDown({
        anchor: f,
        actuator: a,
        choices: g,
        position: b.position || "bottom_left"
    });
    return c
};
UI.replaceNodes = function(d) {
    d = d || [];
    for (var b = 0; b < d.length; b++) {
        var c = d[b];
        var f = $(c.id);
        if (f) {
            var a = str2nodes(c.html);
            setNode(f, null, null, a.nodes);
            a.js()
        }
    }
};
UI.layoutN = function(a, b) {
    return createNode("ul", {
        id: b.id || "",
        className: "layout_n " + (b.className || "")
    }, null, UI.layoutNBody(a, b))
};
UI.layoutNBody = function(h, k) {
    h = h || [];
    k = k || {};
    var a = k.n;
    var j = k.size || "m2";
    var b = k.offset || 0;
    var f = k.no_last_row || b;
    var g = k.renderer || function(m) {
            return m
        };
    var l = [];
    var c = b;
    var d;
    if (a && !f) {
        d = h.length - (h.length % a || a)
    }
    return h.map(function(n) {
        var m = ["size_" + j];
        if (a && c % a == a - 1) {
            m.push("last")
        }
        if (d !== undefined && c >= d) {
            m.push("last_row")
        }++c;
        return createNode("li", {
            className: m.join(" ")
        }, null, g(n, {
            size: j
        }))
    })
};
UI.layoutColumns = function(a, b) {
    b = b || {};
    b.renderer = b.renderer || UI.renderItem;
    b.columns = b.columns || 1;
    b.className = b.className || "";
    var d = Math.floor(100 / b.columns) + "%";
    a = a.sortColumns(b.columns);
    var c = 0;
    return createNode("ul", {
        className: "layout_columns " + b.className
    }, null, a.map(function(g) {
        var f = c++ % b.columns == b.columns - 1 ? "last" : "";
        return createNode("li", {
            className: f
        }, {
            width: d
        }, b.renderer(g, b))
    }))
};
UI.layoutColumnsTable = function(a, b) {
    b = b || {};
    b.renderer = b.renderer || UI.renderItem;
    b.columns = b.columns || 1;
    b.className = b.className || "";
    a = a.sortColumns(b.columns);
    var f = createNode("table", {
        cellspacing: 0,
        cellpadding: 0,
        border: 0,
        className: b.className
    });
    var d = f.appendChild(createNode("tbody"));
    var c = 0;
    var g;
    while (a.length) {
        if (c++ % b.columns === 0) {
            g = d.appendChild(createNode("tr"))
        }
        g.appendChild(createNode("td", null, null, b.renderer(a.shift(), b)))
    }
    return f
};
UI.renderItem = function(c, a) {
    var g;
    var b = [];
    if (a.size) {
        g = UI.sizeMap[a.size].dim;
        b.push("img_size_" + a.size)
    }
    if (a.className) {
        b.push(a.className)
    }
    var f = createNode("img", {
        id: a.id || "",
        src: c.imgurl,
        className: b.join(" "),
        width: g || c.imgw,
        height: g || c.imgh,
        title: c.title_attr || c.title || "",
        alt: c.alt || c.title,
        trackelement: c.trackelement
    });
    if (c.clickurl) {
        var d = {
            trackcontext: "image",
            oid: a.oid || "",
            className: a.linkClass || "",
            target: a.target || "",
            href: c.clickurl
        };
        if (a.rel) {
            d.rel = a.rel
        }
        if (!c.linkInternal && isAbsURL(c.clickurl) && !isPolyvoreURL(c.clickurl)) {
            if (c.actual_bid_token) {
                d.cpc = c.actual_bid_token
            }
            d.paidurl = c.paid_url;
            d.orighost = c.displayurl;
            d.oid = d.oid || Track.classAndId("thing", c.thing_id);
            f = outboundLink(d, null, f)
        } else {
            f = createNode("a", d, null, f)
        }
    }
    return f
};
UI.box = function(d, c) {
    var a = d.attributes || {};
    a.className = "box " + (a.className || "");
    var b = d.header_attributes || {};
    b.className = "hd " + (b.className || "");
    var j = d.body_attributes || {};
    j.className = "bd " + (j.className || "");
    var f = createNode("div", a);
    var h = createNode("div", b);
    if (d.header) {
        h.appendChild(createNode("h3", null, null, d.header))
    }
    if (d.actions) {
        UI.renderActions(d.actions, {
            className: "inline"
        })
    }
    f.appendChild(h);
    var g = createNode("div", j, null, c);
    f.appendChild(g);
    return f
};
UI.moreFans = function(b) {
    b = b || {};
    var a = $(b.container);
    var c = $(b.actuator);
    Event.addListener(c, "click", function(j) {
        var l = b.n;
        var f = b.size;
        var h = UI.sizeMap[f].dim;
        var k = function(m) {
            m.title = loc("{user} liked this {ago} ago.", {
                user: m.user_name,
                ago: duration(m.age)
            });
            m.alt = m.user_name;
            return UI.renderItem(m, {
                id: m.domid || "",
                size: f
            })
        };
        var d = [];
        var g = new AutoPaginator(b.action, {
            id: b.object_id,
            length: 200,
            page: 0
        }, {
            scrollbottomNode: a,
            attachNode: a,
            disableUrlRewrite: true,
            onNext: function(m) {
                var o = m.items.filter(function(q) {
                    q.domid = ["fan", q.id || q.age].join("");
                    return !$(q.domid) && !d.contains(q)
                });
                o.unshift.apply(o, d);
                if (m.more_pages) {
                    var p = o.length % l;
                    d = o.splice(o.length - p, p)
                }
                var n = UI.layoutNBody(o, {
                    no_last_row: m.more_pages,
                    n: l,
                    size: f,
                    renderer: k
                });
                n.forEach(function(q) {
                    a.appendChild(q)
                });
                if (nodeXY(a).y + Dim.fromNode(a).h < scrollXY().y + getWindowSize().h) {
                    yield(this.next, this)
                }
            }
        });
        g.next();
        domRemoveNode(c);
        return Event.stop(j)
    })
};
UI.renderSubnav = function(a) {
    a = a || {};
    a.id = a.id || Dom.uniqueId("subnav");
    a.className = a.className || "";
    a.linkClassName = a.linkClassName || "";
    a.openDelay = a.openDelay === undefined ? 100 : a.openDelay;
    a.label = a.label || "";
    a.url = a.url || "";
    a.arrow = a.arrow || false;
    if (a.arrow) {
        a.linkClassName += " mod_arrow down"
    }
    var c;
    if (a.url) {
        c = createNode("a", {
            href: a.url,
            className: a.linkClassName
        }, null, a.label)
    } else {
        c = createNode("span", {
            className: a.linkClassName
        }, null, a.label)
    }
    var b = createNode("div", {
        id: a.id,
        className: "mod_subnav " + a.className
    }, null, [c, UI.renderSubnavBody(a)]);
    if (a.openDelay) {
        DropDownMenu.initSubnav(b, a.openDelay)
    }
    return b
};
UI.renderSubnavBody = function(a) {
    a = a || {};
    a.dock = a.dock || "bl";
    a.subnav = a.subnav || [];
    return createNode("ul", {
        className: "dock_" + a.dock
    }, null, a.subnav.map(function(c) {
        c.className = c.className || "";
        if (c.selected) {
            c.className += " selected"
        }
        var b = createNode("li", {
            className: c.className
        }, null, createNode("a", {
            href: c.url
        }, null, c.label));
        if (c.onClick) {
            Event.addListener(b, "click", c.onClick)
        }
        return b
    }))
};
UI.ln2Br = function(a) {
    return a.replace(/\n/g, "<br>")
};
UI.renderFollowLink = function(g, d, o) {
    var h, a, l, b;
    var k = [];
    var f = [];
    var j = [];
    var m, c, n;
    o = o || {};
    o.actionClass = o.actionClass || "";
    o.noButton = o.noButton || false;
    o.stat = o.stat || "";
    o.emailRender = o.emailRender || 0;
    h = o.followLabel || loc("Follow");
    a = o.followingLabel || loc("Following");
    l = o.unfollowLabel || loc("Unfollow");
    b = buildURL("favorite.add_contact", {
        contact_id: g.id
    });
    if (o.emailRender) {
        if (d) {
            return a
        }
        return createNode("a", {
            href: buildAbsURL(b)
        }, null, h)
    }
    k = [o.actionClass, "follow_action", "clickable"];
    if (d) {
        k.push("following")
    } else {
        k.push("follow")
    }
    c = Dom.uniqueId("follow");
    n = Dom.uniqueId("text");
    m = createNode("span", {
        id: n,
        className: "label"
    }, null, (d ? a : h));
    j.push(m);
    if (o.sprite) {
        j.unshift(createNode("div", {
            className: "sprite follow"
        }))
    }
    f = UI.renderActionLink({
        actionClass: k.join(" "),
        label: nodes2str(j),
        id: c
    });
    Follow.init(f, {
        user_id: g.id,
        user_name: g.name,
        is_following: d,
        count_node: o.countNodeId || "",
        follower_count: o.followerCount || "",
        rejectNode: o.rejectNodeId || "",
        src_class: o.srcClass,
        src_id: o.srcId,
        action_class: o.actionClass,
        toggle_class: o.toggleClass,
        no_button: o.noButton,
        stat: o.stat,
        contact_list_name: g.contact_list_name,
        follow_label: h,
        following_label: a,
        unfollow_label: l,
        beacon_src: o.beaconSrc,
        beacon_params: o.beaconParams,
        text_node: m
    });
    return f
};
UI.renderActionLink = function(a) {
    var b = a.actionClass || "clickable";
    return createNode("span", {
        id: a.id,
        className: b
    }, null, a.label)
};
UI.switchToMobile = function(b) {
    var a = $(b);

    function c(d) {
        window.Cookie.set("m", 1);
        window.location.reload();
        return Event.stop(d)
    }
    Event.addListener(a, "click", c)
};
UI.andWords = function(b) {
    if (b === undefined && b.constructor === Array || b.length === 0) {
        return ""
    }
    if (b.length === 1) {
        return b[0]
    }
    var a = b.pop();
    return loc("{list} and {last}", {
        list: b.join(", "),
        last: a
    })
};
window.UI = window.UI || {};
UI.setupRenderMore = function(f) {
    var b = $(f.contentNode);
    var a = $(f.containerNode);
    var d = f.moreOnClick;
    var g;
    if (!b) {
        return
    }
    var c = function() {
        var j = false;
        var m, l, h, k;
        var n = function() {
            addClass(b, "expanded");
            m = getStyle(b, "maxHeight");
            setNode(b, null, {
                maxHeight: "none"
            });
            j = true;
            hide(g)
        };
        if (b.clientHeight === 0 || b.scrollHeight - b.clientHeight < 16) {
            setNode(b, null, {
                maxHeight: "none"
            })
        } else {
            g = createNode("div", {
                className: "tease_more clickable"
            }, {
                display: "none"
            });
            a.appendChild(g);
            l = fromPx(getStyle(b, "line-height"));
            m = fromPx(getStyle(b, "max-height"));
            h = m - l;
            g.innerHTML = loc("more") + "...";
            show(g);
            setNode(b, null, {
                maxHeight: px(h)
            });
            Event.addListener(g, "click", function(o) {
                Event.stop(o);
                n()
            })
        }
    };
    if (b.scrollHeight === 0 && b.clientHeight === 0) {
        Event.addListener(document, "modifiable", c)
    } else {
        c()
    }
};
UI.maybeRenderMore = function(f) {
    var g = $(f.moreNode);
    var b = $(f.contentNode);
    var a = $(f.containerNode);
    var d = f.moreOnClick;
    if (!b) {
        return
    }
    var c = function() {
        var k = false;
        var n, m, j;
        var l;
        var o = function() {
            addClass(b, "expanded");
            n = getStyle(b, "maxHeight");
            setNode(b, null, {
                maxHeight: "none"
            });
            k = true;
            if (f.inplace) {
                g.innerHTML = loc("less") + "..."
            } else {
                hide(g)
            }
        };
        var p = function() {
            setNode(b, null, {
                maxHeight: n
            });
            removeClass(b, "expanded");
            g.innerHTML = loc("more") + "...";
            k = false
        };
        if (b.clientHeight === 0 || b.scrollHeight - b.clientHeight <= 17) {
            setNode(b, null, {
                maxHeight: "none"
            });
            hide(g)
        } else {
            m = fromPx(getStyle(b, "line-height"));
            n = fromPx(getStyle(b, "max-height"));
            j = n - m;
            g.innerHTML = loc("more") + "...";
            show(g);
            setNode(b, null, {
                maxHeight: px(j)
            });
            if (f.inplace) {
                var h = $(f.containerNode);
                setNode(h, null, {
                    maxHeight: getStyle(h, "height")
                })
            }
            Event.addListener(g, "click", function(q) {
                Event.stop(q);
                if (d) {
                    d()
                } else {
                    if (k) {
                        p()
                    } else {
                        o()
                    }
                }
            })
        }
    };
    if (b.scrollHeight === 0 && b.clientHeight === 0) {
        Event.addListener(document, "modifiable", c)
    } else {
        c()
    }
};
UI.renderMoreText = function(a) {
    a = a || {};
    style = a.style || {};
    style.maxHeight = px(16 * (a.numLines || 2));
    var f = a.text.split("\n");
    if (a.escape || a.escape === undefined) {
        f = f.map(escapeHTML)
    }
    f = f.join("<br>");
    var d = createNode("div", {
        className: "tease"
    }, style, f);
    var b = createNode("div", {
        className: "tease_more clickable"
    }, {
        display: "none"
    });
    UI.activateLinks(d);
    var c = createNode("div", {
        className: "tease_container " + (a.className || "")
    }, null, [d, b]);
    if (a.imgNode || a.metaNode) {
        UI.maybeRenderMorePolaroid({
            moreNode: b,
            contentNode: d,
            imgDiv: a.imgNode,
            textDiv: a.metaNode
        })
    } else {
        UI.maybeRenderMore({
            moreNode: b,
            contentNode: d,
            containerNode: c,
            inplace: a.inplace
        })
    }
    return c
};
UI.maybeRenderMorePolaroid = function(g) {
    var f = $(g.moreNode);
    var j = $(g.contentNode);
    var o = $(g.imgDiv);
    var h = $(g.textDiv);
    var m;
    var l;
    var b;
    var a;
    var k;
    var d = true;
    var c = new Animation({
        duration: 250,
        renderer: function(q) {
            if (d) {
                q = 1 - q
            }
            var p = m + q * (l - m);
            setNode(o, null, {
                height: px(p)
            });
            setNode(h, null, {
                height: px(b - p - a)
            })
        }
    });
    var n = function() {
        setNode(o, null, {
            overflow: "hidden"
        });
        setNode(h, null, {
            overflow: "hidden"
        });
        var q = Dim.fromNode(h).h;
        m = m || Dim.fromNode(o).h;
        b = b || (q + m);
        var r = Dim.fromNode(j.parentNode).h;
        k = k || getStyle(j, "maxHeight");
        d = !d;
        setNode(j, null, {
            maxHeight: d ? k : "none"
        });
        f.innerHTML = (d ? loc("more") : loc("less")) + "...";
        var s = Dim.fromNode(j.parentNode).h;
        var p = q + (s - r);
        l = l === undefined ? Math.max(m - (p - q), 0) : l;
        a = a || (q - getElementInnerDim(h).h);
        c.run();
        Event.addListener(c, "done", function() {
            setNode(h, null, {
                overflowY: "auto"
            });
            Event.trigger(h, "expanded")
        });
        c.run()
    };
    UI.maybeRenderMore({
        moreNode: f,
        contentNode: j,
        moreOnClick: n
    })
};
UI = window.UI || {};

function CachedAjaxData(a) {
    this._pageSize = 50;
    this._timeOut = a || 600;
    this.reset()
}
CachedAjaxData.prototype.reset = function() {
    this._pages = {};
    this._cachedEntry = null;
    this._filters = {};
    this._timerId = null;
    delete this._totalResults
};
CachedAjaxData.prototype.contains = function(f) {
    if (this._cachedEntry) {
        return true
    }
    if (f._start === 0 && f._end === 0) {
        return (this._totalResults !== undefined && this._totalResults > 0)
    }
    var g = f._start || (f.page - 1) * f.length;
    var a = f._end || f.page * f.length;
    if (this._totalResults !== undefined) {
        a = Math.min(a, this._totalResults)
    }
    for (var c = g; c < a; c++) {
        var d = Math.floor(c / this._pageSize);
        var b = c % this._pageSize;
        if (!this._pages[d] || !this._pages[d][b]) {
            return false
        }
    }
    return true
};
CachedAjaxData.prototype.get = function(g) {
    var h = g._start || (g.page - 1) * g.length;
    var a = g._end || g.page * g.length;
    if (this._cachedEntry) {
        return this._cachedEntry
    }
    if (g._start === 0 && g._end === 0) {
        h = 0;
        a = this._totalResults || 0
    }
    if (this._totalResults !== undefined && h >= this._totalResults) {
        h = (Math.ceil(this._totalResults / g.length) - 1) * g.length;
        a = h + g.length
    }
    var d = [];
    for (var c = h; c < a; c++) {
        var f = Math.floor(c / this._pageSize);
        var b = c % this._pageSize;
        if (this._pages[f] && this._pages[f][b]) {
            d.push(this._pages[f][b])
        }
    }
    if (this._timeOut) {
        if (this._timerId) {
            clearTimeout(this._timerId)
        }
        this._timerId = setTimeout(Event.wrapper(function() {
            this.reset()
        }, this), this._timeOut * 1000)
    }
    return {
        page: g.page,
        items: d,
        more_pages: this.hasMorePages(g),
        filters: this._filters,
        current_page: this.getCurrentPage(g),
        total_pages: this.getTotalPages(g)
    }
};
CachedAjaxData.prototype.getTotalPages = function(a) {
    if (this._totalResults !== undefined) {
        return Math.ceil(this._totalResults / a.length)
    }
    return null
};
CachedAjaxData.prototype.getCurrentPage = function(a) {
    if (this._totalResults !== undefined && this._totalResults < a.page * a.length) {
        return this.getTotalPages(a)
    }
    return a.page
};
CachedAjaxData.prototype.hasMorePages = function(a) {
    if (this._totalResults !== undefined) {
        return (this._totalResults > a.page * a.length)
    }
    return true
};
CachedAjaxData.prototype.set = function(j, b) {
    if (b.items === undefined) {
        this._cachedEntry = b;
        return
    }
    var c = b.items;
    var g = b.current_page || b.page || j.page;
    if (!j.length) {
        j.length = c.length
    }
    var d = j.length * (g - 1);
    if ((b.total_pages !== undefined && b.current_page >= b.total_pages) || (b.more_pages !== undefined && !b.more_pages)) {
        this._totalResults = (g - 1) * j.length + c.length
    }
    for (var f = 0; f < c.length; f++) {
        var h = Math.floor((d + f) / this._pageSize);
        var a = (d + f) % this._pageSize;
        if (!this._pages[h]) {
            this._pages[h] = [];
            this._pages[h].length = this._pageSize
        }
        this._pages[h][a] = cloneObject(c[f], true)
    }
    if (b.filters) {
        this._filters = b.filters
    }
};
var CachedAjax = function() {
    var b = new Hash();
    var a = {};

    function c(j) {
        var k = j._start;
        var f = j._end;
        var h = [];
        h.hasPending = function() {
            for (var l = 0; l < this.length; ++l) {
                if (this[l].pending) {
                    return true
                }
            }
            return false
        };
        if (k === 0 && f === 0) {
            h.push({
                _start: 0,
                _end: 0,
                pending: true
            });
            return h
        }
        var d = j.reqSize;
        k = Math.floor(k / d) * d;
        f = Math.ceil(f / d) * d;
        for (var g = k / d; g < f / d; g++) {
            h.push({
                page: g + 1,
                length: d,
                pending: true
            })
        }
        return h
    }
    return {
        getCachedPage: function(d) {
            return b.get(Ajax.getCacheKey(d.action, d.data))
        },
        clearAll: function() {
            b.clear()
        },
        clear: function(f) {
            var d = Ajax.getCacheKey(f.action, f.data);
            b.remove(d)
        },
        abortContract: function(d) {
            var f = d && a[d];
            if (f) {
                f.forEach(Ajax.abort);
                delete a[d];
                return true
            }
            return false
        },
        get: function(d) {
            d.method = "GET";
            return CachedAjax.request(d)
        },
        request: function(l) {
            l = cloneObject(l || {}, true);
            if (!l.data.page) {
                l.data.page = 1
            }
            l.data.reqSize = l.data.reqSize || 50;
            var j = l.contract;
            CachedAjax.abortContract(j);
            if (l.data._start === undefined || l.data._end === undefined) {
                if (l.data.length) {
                    l.data._start = (l.data.page - 1) * l.data.length;
                    l.data._end = l.data._start + l.data.length
                } else {
                    l.data._start = 0;
                    l.data._end = 0
                }
            }
            var g = Ajax.getCacheKey(l.action, l.data);
            var h = l.shuffle;
            if (h) {
                g = g + "-" + h.offset + "-" + (h.granularity || 0)
            }
            var d = b.get(g);
            if (!d) {
                d = new CachedAjaxData(l.expires);
                b.put(g, d)
            }
            var k = l.onSuccess || noop;
            if (d.contains(l.data)) {
                k({
                    result: d.get(l.data)
                });
                if (l.onFinally) {
                    l.onFinally()
                }
            } else {
                var m = c(l.data);
                var f;
                if (j) {
                    f = a[j] = []
                }
                m.forEach(function(n) {
                    if (d.contains(n)) {
                        n.pending = false;
                        return
                    }
                    var p = cloneObject(l.data, true);
                    p.page = n.page;
                    p.length = n.length;
                    delete p.reqSize;
                    delete p._start;
                    delete p._end;
                    var o = Ajax.request({
                        method: l.method || "GET",
                        action: l.action,
                        hideProgress: l.hideProgress,
                        data: p,
                        onSuccess: function(q) {
                            n.pending = false;
                            if (h && q.result.items) {
                                q.result.items.fixedShuffle(h.offset, h.granularity)
                            }
                            d.set(p, q.result);
                            if (!m.hasPending()) {
                                k({
                                    result: d.get(l.data)
                                });
                                if (l.onFinally) {
                                    l.onFinally()
                                }
                                if (j && a[j] == f) {
                                    delete a[j]
                                }
                            }
                        },
                        onError: function() {
                            if (l.onError) {
                                l.onError.apply(null, arguments)
                            }
                            if (l.onFinally && !m.hasPending()) {
                                l.onFinally()
                            }
                        }
                    });
                    if (f) {
                        f.push(o)
                    }
                })
            }
        }
    }
}();


var ModalDialog = function() {
    setDefaultEmbedWMode("opaque");
    var c;
    var f;
    var b;
    var h;
    var a;
    var d = false;

    function g() {
        if (c) {
            return
        }
        h = createNode("div", {
            className: "close"
        }, null, "&times;");
        b = createNode("span", {
            className: "container"
        }, null, h);
        f = createNode("center", {
            className: "dialog"
        }, {
            display: "none"
        }, b);
        c = createNode("div", {
            className: "block"
        }, {
            display: "none"
        });
        document.body.appendChild(c);
        document.body.appendChild(f);
        Event.addListener(h, "click", ModalDialog.hide);
        Event.addListener(document, "keydown", function(l) {
            if (!d) {
                return
            }
            if (l.keyCode == 27) {
                ModalDialog.hide();
                return Event.stop(l)
            }
            if (l.keyCode == 13) {
                if (Event.getSource(l).tagName.match(/input|textarea/i)) {
                    return
                }
                var k = getElementsByClassName({
                    root: ModalDialog.getContent(),
                    tagName: "ul",
                    className: "actions"
                });
                if (k.length) {
                    var j = getElementsByClassName({
                        root: k[k.length - 1],
                        className: "btn_action"
                    });
                    if (j.length) {
                        j[0].click();
                        Event.stop(l)
                    }
                }
            }
        });
        Event.addListener(window, "resize", ModalDialog.rePosition)
    }
    return {
        init: g,
        isShown: function() {
            return d
        },
        getContent: function() {
            if (!d) {
                return null
            }
            return a
        },
        setContent: function(j) {
            if (!d) {
                return
            }
            g();
            a = j;
            while (b.childNodes.length) {
                b.removeChild(b.childNodes[0])
            }
            setNode(b, null, null, flatten([h, a]));
            ModalDialog.rePosition()
        },
        show: function(j) {
            g();
            d = true;
            ModalDialog.setContent(j);
            ModalDialog.reRaise();
            show(c);
            show(f);
            ModalDialog.rePosition();
            yield(ModalDialog.rePosition);
            Event.trigger(ModalDialog, "show");
            return false
        },
        reRaise: function() {
            if (!d) {
                return
            }
            var j = overlayZIndex();
            setNode(c, null, {
                zIndex: j
            });
            setNode(f, null, {
                zIndex: j + 1
            })
        },
        rePosition: function() {
            if (!d) {
                return
            }
            var l = Dim.fromNode(f);
            var j = getWindowSize();
            if (j.h > l.h) {
                var k = Math.min((j.h - l.h) / 3, 50);
                setNode(f, null, {
                    position: "fixed",
                    top: px(k)
                })
            } else {
                var m = scrollXY();
                setNode(f, null, {
                    position: "absolute",
                    top: px(m.y)
                })
            }
        },
        hide: function(j) {
            if (!d) {
                return
            }
            g();
            j = j || (j === undefined);
            setNode(f, null, {
                display: "none"
            });
            setNode(c, null, {
                display: "none"
            });
            if (a) {
                b.removeChild(a);
                if (j) {
                    Event.trigger(a, "destruct");
                    purge(a)
                }
                a = null
            }
            d = false;
            Event.trigger(ModalDialog, "hide")
        },
        createErrorElement: function(j) {
            return {
                type: "rotext",
                id: j,
                className: "error",
                rowClassName: "error_placeholder"
            }
        },
        show_uic: function(k) {
            k = k || {};
            if (k.constructor !== Object) {
                k = {
                    body: k
                }
            }
            k.id = k.id || "";
            k.className = k.className || "";
            var l = createNode("div", {
                id: k.id,
                className: "uic " + k.className
            });
            var j = l.appendChild(createNode("div", {
                className: "body"
            }));
            if (k.title) {
                j.appendChild(createNode("h1", null, null, k.title))
            }
            if (k.body) {
                j.appendChild(k.body)
            }
            if (k.actions && k.actions.length > 0) {
                l.appendChild(UI.renderActions(k.actions))
            }
            if (k.onHide) {
                Event.addSingleUseListener(ModalDialog, "hide", k.onHide)
            }
            ModalDialog.show(l)
        },
        alert: function(j) {
            j = j || {};
            if (j.constructor !== Object) {
                j = {
                    content: j
                }
            }
            j.body = createNode("div", {
                className: "alert_content"
            }, null, j.content);
            j.className = (j.className || "") + " alert";
            j.actions = [{
                label: createNode("span", {
                    className: "btn btn_action"
                }, null, j.okLabel || loc("OK")),
                action: function() {
                    (j.onOk || noop)();
                    ModalDialog.hide()
                }
            }];
            return ModalDialog.show_uic(j)
        },
        confirm: function(j) {
            j = j || {};
            if (j.constructor !== Object) {
                j = {
                    title: j
                }
            }
            if (j.content) {
                j.body = createNode("div", {
                    className: "confirm_content"
                }, null, j.content)
            }
            j.className = (j.className || "") + " confirm";
            j.actions = [{
                label: createNode("span", {
                    className: "btn btn_action"
                }, null, j.okLabel || loc("OK")),
                action: function() {
                    (j.onOk || noop)();
                    ModalDialog.hide()
                }
            }, {
                label: j.cancelLabel || loc("Cancel"),
                action: function() {
                    (j.onCancel || noop)();
                    ModalDialog.hide()
                }
            }];
            return ModalDialog.show_uic(j)
        }
    }
}();

var ToolTipPosition = function() {
    var k = "lt";
    var b = "lc";
    var a = "lb";
    var d = "rt";
    var h = "rc";
    var g = "rb";
    var m = "bl";
    var j = "bc";
    var n = "br";
    var c = "tl";
    var l = "tc";
    var f = "tr";
    return {
        ANCHOR: {
            LEFT_TOP: k,
            LEFT_CENTER: b,
            LEFT_BOTTOM: a,
            RIGHT_TOP: d,
            RIGHT_CENTER: h,
            RIGHT_BOTTOM: g,
            BOTTOM_LEFT: m,
            BOTTOM_CENTER: j,
            BOTTOM_RIGHT: n,
            TOP_LEFT: c,
            TOP_CENTER: l,
            TOP_RIGHT: f
        },
        ANCHOR_ORDER: {
            LEFT_FIRST: [k, a, m, n, g, d, f, c],
            RIGHT_FIRST: [d, g, n, m, a, k, c, f],
            BOTTOM_LEFT: [m, n]
        },
        POS: {
            CENTER: function(p, u, D) {
                var z = p.w;
                var v = p.h;
                if (!D) {
                    D = {}
                }
                var B = nodeXY(u);
                var A = Dim.fromNode(u);
                var x = B.y + A.h / 2;
                var r = B.x - z + A.w / 2;
                var C = getWindowSize();
                var t = C.w;
                var o = C.h;
                var y = scrollXY();
                var s = y.x;
                var q = y.y;
                if (r < s) {
                    r += z;
                    if (r + z > t + s) {
                        r = (t - z) / 2 + s
                    }
                }
                if (x + v > q + o) {
                    x -= v;
                    if (x < q) {
                        x = (o - v) / 2 + q
                    }
                }
                return {
                    top: x,
                    left: r
                }
            },
            EDGE: function(O, A, u) {
                var B = u.stemDim;
                var t = u.anchorOrder;
                if (!u) {
                    u = {}
                }
                var C = O.w;
                var N = O.h;
                var M = nodeXY(A);
                var R = Dim.fromNode(A);
                var y = getWindowSize();
                var S = y.w;
                var z = y.h;
                var q = scrollXY();
                var H = q.x;
                var s = q.y;
                var r = new Point(M.x + R.w, M.y);
                var v = new Point(M.x, M.y);
                var Q = new Point(M.x + R.w, M.y + R.h);
                var p = new Point(M.x, M.y + R.h);
                var L = 2;
                var x = 4;
                var P = [3, 2, 3, 4];
                if (Browser.isIE) {
                    P = [2, 2, 2, 2]
                }
                if (!B) {
                    B = [new Dim(0, 0), new Dim(0, 0), new Dim(0, 0), new Dim(0, 0)];
                    P = [0, 0, 0, 0]
                }
                if (!t || !t.length) {
                    t = ToolTipPosition.ANCHOR_ORDER.LEFT_FIRST
                }

                function F(w) {
                    switch (w) {
                        case k:
                            return {
                                point: new Point(v.x - O.w, v.y),
                                padding: new Point(Math.min(-x, -B[3].w + P[3]), 0),
                                stemXY: new Point(v.x - B[3].w, v.y + (Math.min(R.h, N) / 2) - B[3].h / 2),
                                anchor: k
                            };
                        case b:
                            return {
                                point: new Point(v.x - O.w, p.y - (R.h / 2) - (O.h / 2)),
                                padding: new Point(Math.min(-x, -B[3].w + P[3]), 0),
                                stemXY: new Point(v.x - B[3].w, v.y + (R.h / 2) - (B[3].h / 2)),
                                anchor: b
                            };
                        case a:
                            return {
                                point: new Point(p.x - O.w, p.y - O.h),
                                padding: new Point(Math.min(-x, -B[3].w + P[3]), -L),
                                stemXY: new Point(v.x - B[3].w, p.y - (Math.min(R.h, N) / 2) - B[3].h / 2),
                                anchor: a
                            };
                        case d:
                            return {
                                point: r,
                                padding: new Point(Math.max(L, B[1].w - P[1]), 0),
                                stemXY: new Point(r.x, r.y + Math.min(N, R.h) / 2 - B[1].h / 2),
                                anchor: d
                            };
                        case h:
                            return {
                                point: new Point(r.x, p.y - (R.h / 2) - (O.h / 2)),
                                padding: new Point(Math.max(L, B[1].w - P[1]), 0),
                                stemXY: new Point(r.x, v.y + (R.h / 2) - (B[1].h / 2)),
                                anchor: h
                            };
                        case g:
                            return {
                                point: new Point(Q.x, Q.y - O.h),
                                padding: new Point(Math.max(L, B[1].w - P[1]), -L),
                                stemXY: new Point(Q.x, Q.y - (Math.min(N, R.h) / 2) - B[1].h / 2),
                                anchor: g
                            };
                        case m:
                            return {
                                point: p,
                                padding: new Point(0, Math.max(L, B[2].h - P[2])),
                                stemXY: new Point(p.x + Math.min(C, R.w) / 2 - B[2].w / 2, p.y),
                                anchor: m
                            };
                        case j:
                            return {
                                point: new Point(Q.x - (R.w / 2) - (O.w / 2), Q.y),
                                padding: new Point(0, Math.max(L, B[2].h - P[2])),
                                stemXY: new Point(p.x + (R.w / 2) - (B[2].w / 2), p.y),
                                anchor: j
                            };
                        case n:
                            return {
                                point: new Point(Q.x - O.w, Q.y),
                                padding: new Point(-L, Math.max(L, B[2].h - P[2])),
                                stemXY: new Point(Q.x - (Math.min(C, R.w) / 2) - B[2].w / 2, Q.y),
                                anchor: n
                            };
                        case c:
                            return {
                                point: new Point(v.x, v.y - O.h),
                                padding: new Point(0, Math.min(-x, -B[0].h + P[0])),
                                stemXY: new Point(v.x + (Math.min(C, R.w) / 2) - B[0].w / 2, v.y - B[0].h),
                                anchor: c
                            };
                        case l:
                            return {
                                point: new Point(r.x - (R.w / 2) - (O.w / 2), r.y - O.h),
                                padding: new Point(-L, Math.min(-x, -B[0].h + P[0])),
                                stemXY: new Point(p.x + (R.w / 2) - (B[0].w / 2), r.y - B[0].h),
                                anchor: l
                            };
                        case f:
                            return {
                                point: new Point(r.x - O.w, r.y - O.h),
                                padding: new Point(-L, Math.min(-x, -B[0].h + P[0])),
                                stemXY: new Point(r.x - (Math.min(C, R.w) / 2) - B[0].w / 2, r.y - B[0].h),
                                anchor: f
                            }
                    }
                }
                var K = [];
                t.forEach(function(w) {
                    K.push(F(w))
                });
                var o = new Point(H, s);
                var J = new Point(H + S, s + z);
                for (var I = 0; I < K.length; I++) {
                    var G = K[I].point;
                    var E = K[I].padding;
                    if (G.x + E.x >= o.x && G.y + E.y >= o.y && G.x + E.x + O.w <= J.x && G.y + E.y + O.h <= J.y) {
                        return {
                            top: G.y + E.y,
                            left: G.x + E.x,
                            anchor: K[I].anchor,
                            stemXY: K[I].stemXY
                        }
                    }
                }
                var D = F(n);
                return {
                    top: D.point.y + D.padding.y,
                    left: D.point.x + D.padding.x,
                    anchor: D.anchor,
                    stemXY: D.stemXY
                }
            }
        }
    }
}();

function ToolTipInstance() {
    this.node = null;
    this.stemNode = null;
    this._engaged = false;
    this.timer = new Timer();
    this.autoOptions = {};
    this.anchorNode = null;
    this.autoAnchorNode = null
}
ToolTipInstance.prototype._createAutoHider = function(a) {
    var b = Event.wrapper(function() {
        this.timer.replace(Event.wrapper(function() {
            if (this.autoAnchorNode || this.engaged()) {
                b()
            } else {
                this.hide()
            }
        }, this), a)
    }, this);
    return b
};
ToolTipInstance.prototype._init = function() {
    if (this.node) {
        return
    }
    this.node = createNode("div", {
        className: "tooltip polyvore_tooltip"
    }, {
        display: "none"
    });
    if (!Browser.isIE) {
        addClass(this.node, "drop_shadowed")
    }
    this.stemNode = createNode("div", {
        className: "tooltip_stem polyvore_tooltip_stem"
    }, {
        display: "none"
    });
    document.body.appendChild(this.node);
    document.body.appendChild(this.stemNode);
    Event.addListener(this.node, "mouseover", Event.wrapper(function() {
        this._engaged = true;
        Event.trigger(this, "engaged")
    }, this));
    Event.addListener(this.node, "mouseout", Event.wrapper(function() {
        this._engaged = false;
        Event.trigger(this, "disengaged");
        if (this.anchorNode) {
            var a = this.autoOptions[getHashKey(this.anchorNode)];
            if (a) {
                this._createAutoHider(a.disengage)()
            }
        }
    }, this))
};
ToolTipInstance.prototype._onDocMouseUp = function(a) {
    var b = Event.getSource(a);
    if (b.tagName != "HTML" && !domContainsChild(this.node, b)) {
        this.hide(a)
    }
};
ToolTipInstance.prototype.renderImageAndDetails = function(a, d, j) {
    if (j) {
        return createNode("div", {
            className: "tall"
        }, null, [d, a])
    } else {
        var g = createNode("table", {
            className: "wide",
            cellSpacing: 0,
            cellPadding: 0
        });
        var b = g.appendChild(createNode("tbody"));
        var h = b.appendChild(createNode("tr", {
            vAlign: "top"
        }));
        var f = h.appendChild(createNode("td"));
        f.appendChild(a);
        var c = h.appendChild(createNode("td"));
        c.appendChild(d);
        return g
    }
};
ToolTipInstance.prototype.showing = function() {
    return this.node && this.node.style.display != "none"
};
ToolTipInstance.prototype.engaged = function() {
    return this.showing() && this._engaged
};
ToolTipInstance.prototype.show = function(h, k, p) {
    this._init();
    this.timer.reset();
    var f = null;
    p = p || {};
    if (p.closeButton) {
        f = createNode("div", {
            className: "close"
        }, null, "&times;");
        Event.addListener(f, "click", this.hide, this);
        k = createNode("div", {
            className: "container"
        }, null, k)
    }
    setNode(this.node, null, {
        display: "block",
        visibility: "hidden",
        top: "0px",
        left: "0px"
    }, k);
    var d = ["tooltip"];
    var a = Track.getContext(h);
    if (a) {
        d.unshift(a)
    }
    Track.setContext(this.node, d.join("|"));
    if (f) {
        this.node.appendChild(f)
    }
    setNode(this.node, {
        className: "tooltip polyvore_tooltip"
    }, {
        width: null
    });
    setNode(this.stemNode, {
        className: "tooltip_stem polyvore_tooltip_stem"
    });
    if (!Browser.isIE) {
        addClass(this.node, "drop_shadowed")
    }
    if (p.className) {
        addClass(this.node, p.className);
        addClass(this.stemNode, p.className)
    }
    if (p.width) {
        setNode(this.node, null, {
            width: p.width
        })
    }
    var c = Dim.fromNode(this.node);
    if (p.dim_w) {
        c.w = p.dim_w
    }
    if (p.dim_h) {
        c.h = p.dim_h
    }
    var j = p.pos || ToolTipPosition.POS.CENTER;
    var o = j(c, h, p);
    var m = Math.max(p.top || o.top, 0);
    var g = Math.max(p.left || o.left, 0);
    var n = overlayZIndex(this.node);
    if (p.stemDim && o.stemXY && o.anchor) {
        addClass(this.stemNode, "tooltip_stem_" + o.anchor);
        setNode(this.stemNode, null, {
            left: px(o.stemXY.x),
            top: px(o.stemXY.y),
            display: "block",
            visibility: "visible",
            zIndex: n + 1
        })
    }
    var b = {
        visibility: "visible",
        zIndex: n,
        left: px(g),
        top: px(m)
    };
    if (p.userClose && p.closeButton) {
        var l = Rect.fromNode(h);
        this.monitor = new Monitor(function() {
            if (h.offsetWidth === 0 || h.offsetHeight === 0) {
                return true
            }
            var q = Rect.fromNode(h);
            if (!l.equals(q)) {
                return true
            }
            return false
        }, this);
        Event.addListener(this.monitor, "change", this.hide, this)
    } else {
        Event.addListener(document, "mouseup", Event.wrapper(this._onDocMouseUp, this))
    }
    this.anchorNode = h;
    setNode(this.node, null, b);
    Event.trigger(this, "show", this.anchorNode, k);
    return this
};
ToolTipInstance.prototype.autoShow = function(c, h, b) {
    var a = getHashKey(c);
    var g = this.autoOptions[a];
    if (!g) {
        g = this.autoOptions[a] = {}
    }
    var f = b.click === undefined ? 0 : b.click;
    var d = b.mousepause === undefined ? 300 : b.mousepause;
    var j = b.mouseout;
    if (g.disengage === undefined) {
        g.disengage = j === undefined ? 300 : j
    }
    if (j === undefined) {
        j = g.disengage
    }
    if (b.showOnClick) {
        Event.addListener(c, "click", Event.wrapper(function(k) {
            this.timer.reset();
            this.show(c, h(), b);
            Event.stop(k)
        }, this))
    }
    Event.addListener(c, "mousepause" + d, Event.wrapper(function() {
        this.timer.reset();
        if (this.anchorNode == c) {
            return
        }
        this.show(c, h(), b);
        Event.addSingleUseListener(c, "mouseout", this._createAutoHider(j))
    }, this));
    Event.addListener(c, "mouseover", Event.wrapper(function(k) {
        this.autoAnchorNode = c
    }, this));
    Event.addListener(c, "mouseout", Event.wrapper(function(k) {
        this.autoAnchorNode = null
    }, this))
};
ToolTipInstance.prototype.hide = function(a) {
    this._init();
    this.timer.reset();
    if (a) {
        var b = Event.getSource(a);
        while (b) {
            if (b._data) {
                return
            }
            b = b.parentNode
        }
    }
    clearNode(this.node);
    this.anchorNode = null;
    Event.removeListener(document, "mouseup", this._onDocMouseUp);
    if (this.monitor) {
        this.monitor.stop();
        Event.removeListener(this.monitor, "change", this.hide)
    }
    hide(this.node);
    hide(this.stemNode);
    Event.trigger(this, "hide", a)
};
var ToolTip = new ToolTipInstance();
makeStatic(ToolTip);
var MessageToolTipInstance = function() {
    MessageToolTipInstance.superclass.constructor.call(this)
};
extend(MessageToolTipInstance, ToolTipInstance);
MessageToolTipInstance.prototype.show = function(d, g, c) {
    var a;
    if (Browser.isIE) {
        a = [new Dim(21, 13), new Dim(13, 21), new Dim(21, 13), new Dim(13, 21)]
    } else {
        a = [new Dim(36, 20), new Dim(13, 21), new Dim(21, 13), new Dim(21, 34)]
    } if (!c) {
        c = {}
    }
    c.stemDim = a;
    if (!c.pos) {
        c.pos = ToolTipPosition.POS.EDGE
    }
    if (c.anchorPos) {
        c.anchorOrder = [c.anchorPos]
    }
    var f = c.className || "";
    c.className = f + " msg_tooltip";
    var b = createNode("div", null, null, g);
    if (c.delay) {
        window.setTimeout(Event.wrapper(function() {
            MessageToolTipInstance.superclass.show.call(this, d, b, c)
        }, this), c.delay)
    } else {
        MessageToolTipInstance.superclass.show.call(this, d, b, c)
    }
};
var MessageToolTip = new MessageToolTipInstance();
makeStatic(MessageToolTip);
var SerialMessageToolTip = function() {
    return {
        show: function(a) {
            Event.addListener(document, "modifiable", function() {
                function c(d) {
                    if (d >= a.length) {
                        return
                    }
                    var g = a[d];
                    var f = $(g.node);
                    MessageToolTip.show(f, g.message, {
                        anchorPos: g.anchorPos
                    });
                    if (g.type) {
                        Feedback.markRead(g.type)
                    }
                }
                var b = 0;
                c(b);
                Event.addListener(MessageToolTip, "hide", function() {
                    b++;
                    c(b)
                })
            })
        }
    }
}();
var SpriteToolTip = (function() {
    return {
        autoShow: function(b, c, a) {
            if (!a) {
                a = {}
            }
            if (a.className === undefined) {
                a.className = "sprite_tooltip"
            }
            if (a.width === undefined) {
                a.width = "250px"
            }
            if (a.pos === undefined) {
                a.pos = ToolTipPosition.POS.EDGE
            }
            if (a.closeButton === undefined) {
                a.closeButton = true
            }
            if (a.anchorOrder === undefined) {
                a.anchorOrder = [ToolTipPosition.ANCHOR.RIGHT_CENTER, ToolTipPosition.ANCHOR.BOTTOM_CENTER]
            }
            if (a.stemDim === undefined) {
                a.stemDim = (Browser.isIE) ? [new Dim(21, 13), new Dim(13, 21), new Dim(21, 13), new Dim(13, 21)] : [new Dim(36, 20), new Dim(13, 21), new Dim(21, 13), new Dim(21, 34)]
            }
            ToolTip.autoShow(b, c, a)
        }
    }
})();

function CheckAvailability(j, c, m, a) {
    var l = this;
    l.available = a;
    var h;

    function g() {
        l.available = false;
        addClass(c, "hidden");
        addClass(m, "hidden")
    }

    function f(n) {
        l.available = false;
        setNode(c, {
            className: "invalid"
        }, null, loc("Taken"));
        removeClass(c, "hidden");
        if (m) {
            clearNode(m);
            replaceChild(m, createNode("div", null, null, loc("The following user names are available:")));
            var o = m.appendChild(createNode("ul"));
            n.forEach(function(p) {
                var q = o.appendChild(createNode("li", {
                    className: "clickable"
                }, null, p));
                Event.addListener(q, "click", function() {
                    h.value = p;
                    d()
                })
            });
            removeClass(m, "hidden")
        }
    }

    function d() {
        l.available = true;
        addClass(m, "hidden");
        setNode(c, {
            className: "valid"
        }, null, loc("Available"));
        removeClass(c, "hidden")
    }

    function b(n) {
        h = $(j);
        c = $(c);
        m = $(m);
        if (!Validate.userName(n).valid) {
            return g()
        }
        Ajax.get({
            hideProgress: true,
            action: "register.check_availability",
            data: {
                name: n
            },
            contract: this,
            onSuccess: Event.wrapper(function(o) {
                this.available = !! o.available;
                return this.available ? d() : f(o.suggestions)
            }, this)
        })
    }
    var k = new Monitor(function() {
        return j.value
    });
    Event.addListener(k, "change", delayed(Event.wrapper(b, this), 500));
    if (j.value) {
        b(j.value)
    }
}

function VerifyPassword(c, a, b) {
    function d() {
        var g = c.value;
        var f = a.value;
        if (f.length > 0 && g != f) {
            return setNode(b, {
                className: "invalid"
            }, null, loc("Please enter the same password"))
        }
        if (g.length === 0) {
            return setNode(b, {
                className: "invalid"
            }, null, loc("Password cannot be empty"))
        } else {
            return setNode(b, {
                className: null
            }, null, "")
        }
    }
    Event.addListener(c, "blur", d);
    Event.addListener(a, "blur", d)
}



function buildThingImgUrl(b, a) {
    return buildImgURL("img-thing", {
        ".out": "jpg",
        tid: b.thing_id,
        size: a == "t" ? "s" : a
    })
}


function stripQuotes(a) {
    if (typeof(a) == "string" && a.charAt(0) == '"' && a.charAt(a.length - 1) == '"') {
        return a.substring(1, a.length - 1)
    } else {
        return a
    }
}


function BaseSelector() {
    this.show = function() {}
}
function LikeItToggle(a) {
    this.label = $(a.label);
    this.button = $(a.button);
    this.isUserFav = a.is_user_fav;
    this.options = a;
    Event.addListener(Event.BACKEND, "add_" + a.type, this.onBackendToggleAdd, this);
    Event.addListener(Event.BACKEND, "delete_" + a.type, this.onBackendToggleDelete, this);
    var b;
    if (a.type == "thing") {
        b = {
            consumerThing: {
                thing_id: a.id
            },
            consumer: "set"
        }
    }
    Event.addListener(this.button, "click", function(g) {
        var f = (a.type || "");
        var h = "save_" + f;
        var d = matchingAncestor(this.button, null, "trackcontext");
        if (d) {
            var c = d.getAttribute("trackcontext");
            if (c) {
                h = "save_" + f + "_" + c
            }
        }
        callOrSignIn(Event.wrapper(function() {
            Ajax.post({
                hideProgress: true,
                action: this.isUserFav ? "favorite.delete_" + a.type : "favorite.add_" + a.type,
                data: {
                    id: a.id,
                    context: a.context
                },
                onSuccess: function(j) {
                    Event.trigger(LikeItToggle, "done")
                }
            });
            if (this.isUserFav) {
                Event.trigger(Event.BACKEND, "delete_" + a.type, a.id)
            } else {
                Event.trigger(Event.BACKEND, "add_" + a.type, a.id)
            }
        }, this), h, b);
        return Event.stop(g)
    }, this)
}
LikeItToggle.prototype.onBackendToggleAdd = function(a) {
    if (a == this.options.id && !this.isUserFav) {
        this.toggle()
    }
};
LikeItToggle.prototype.onBackendToggleDelete = function(a) {
    if (a == this.options.id && this.isUserFav) {
        this.toggle()
    }
};
LikeItToggle.prototype.toggle = function(b) {
    this.isUserFav = !this.isUserFav;
    if (this.label) {
        label.innerHTML = this.isUserFav ? this.options.liked_label : this.options.not_liked_label
    }
    setNode(this.button, null, null, this.isUserFav ? (this.options.liked_button || loc("Change")) : (this.options.not_liked_button || loc("Me too")));
    removeClass(this.button, "no_text");
    if (!textContent(this.button)) {
        addClass(this.button, "no_text")
    }
    if (this.isUserFav) {
        addClass(this.button, "faved");
        setNode(this.button, {
            title: loc("Unlike")
        });
        var a = window._pv;
        if (a && a.UA && typeof a.UA.trackEvent === "function") {
            a.UA.trackEvent("engagement", "like-" + this.options.type, this.options.id)
        }
    } else {
        removeClass(this.button, "faved");
        setNode(this.button, {
            title: loc("Like")
        })
    }
};


function Overlay(d, a, b) {
  console.log(d);
  console.log(a);
  console.log(b);
    var c = function() {
        var k = $(d);
        var f = k.parentNode;
        b = b || {};
        if (!b.renderer) {
            b.renderer = function(m) {
                switch (m.type) {
                    case "image":
                        return UI.itemOverlayRender(m);
                    case "amazon_mp3":
                        return UI.amazonMP3OverlayRender(m);
                    case "text":
                        return createNode("a", {
                            href: m.link
                        }, null, m.text)
                }
            }
        }
        addClass(f, "overlay_parent");
        if (!b.clickThrough) {
            Event.addListener(f, "click", Event.stop)
        } else {
            if (b.clickThrough == "item") {
                Event.addListener(k, "click", Event.stop)
            }
        }
        a.forEach(function(m) {
            m.in_placeholder = m.in_placeholder || 0;
            m.w *= 1;
            m.h *= 1;
            m.x *= 1;
            m.y *= 1;
            m.z *= 1;
            m.order = (m.w * m.h) / (1 + m.z)
        });
        a.sort(function(n, m) {
            return (n.in_placeholder - m.in_placeholder) || (m.order - n.order)
        });
        var g = null;
        var j = 10;
        var l = new Timer();
        a.forEach(function(r) {
          console.log(r);
            var p = r.w + Math.min(r.x - 2, 0);
            var m = r.h + Math.min(r.y - 2, 0);
            var n;
            if (r.is_important) {
                n = "overlay important"
            } else {
                n = "overlay"
            }
            var o = "#foo";
            if (b.clickThrough == "container") {
                o = f.href || "#foo"
            } else {
                if (b.clickThrough == "item") {
                    o = r.clickurl || "#foo"
                }
            }
            var q = createNode("a", {
                hidefocus: "hidefocus",
                trackelement: "overlay",
                className: n + " set_anchor",
                href: o
            }, {
                position: "absolute",
                zIndex: j++,
                top: px(Math.max(r.y - 2, 0)),
                left: px(Math.max(r.x - 2, 0)),
                width: px(p),
                height: px(m)
            }, createNode("div", {
                className: "inner"
            }, {
                height: px(m - 2),
                width: px(p - 2)
            }, createNode("div", {
                className: "ie"
            })));
            f.appendChild(q);
            Event.addListener(ToolTip, "hide", function(t) {
                removeClass(f, "showing_tooltip");
                if (g) {
                    removeClass(g, "showing_tooltip");
                    g = null
                }
            });
            Event.addListener(ToolTip, "show", function(t, u) {
                if (hasClass(t, "set_anchor")) {
                    addClass(f, "showing_tooltip");
                    if (g) {
                        removeClass(g, "showing_tooltip")
                    }
                    addClass(t, "showing_tooltip");
                    g = t
                }
            });
            var s = b.pos || ToolTipPosition.POS.EDGE;
            ToolTip.autoShow(q, function() {
                if (ModalDialog.isShown()) {
                    yield(function() {
                        ToolTip.hide()
                    });
                    return
                }
                return b.renderer(r)
            }, {
                pos: s,
                anchorOrder: ToolTipPosition.ANCHOR_ORDER.RIGHT_FIRST,
                showOnClick: !b.clickThrough
            })
        });

        function h() {
            addClass(f, "flash");
            window.setTimeout(function() {
                removeClass(f, "flash")
            }, 500)
        }
        if (b.is_embed) {
            h()
        } else {
            Event.addListener(k, "load", delayed(function() {
                h()
            }, 500));
            if (Browser.isIE) {
                k.src = k.src
            }
        }
    };
    if (b && b.is_embed) {
        c()
    } else {
        Event.addListener(document, "modifiable", c)
    }
}
