/*
jQWidgets v3.0.4 (2013-Nov-01)
Copyright (c) 2011-2013 jQWidgets.
License: http://jqwidgets.com/license/
*/
(function (d) {
    var b = {
        defineInstance: function () {
            this.width = 250;
            this.height = 250;
            this.radius = "50%";
            this.endAngle = 270;
            this.startAngle = 30;
            this.value = 0;
            this.min = 0;
            this.max = 220;
            this.disabled = false;
            this.ticksDistance = "20%";
            this.colorScheme = "scheme01";
            this.animationDuration = 400;
            this.showRanges = true;
            this.easing = "easeOutCubic";
            this.labels = null;
            this.pointer = null;
            this.cap = null;
            this.caption = null;
            this.border = null;
            this.ticksMinor = null;
            this.ticksMajor = null;
            this.style = null;
            this.ranges = [];
            this._radius;
            this._border = null;
            this._radiusDifference = 2;
            this._pointer = null;
            this._labels = [];
            this._cap = null;
            this._ticks = [];
            this._ranges = [];
            this._gauge = null;
            this._caption = null;
            this._animationTimeout = 10;
            this._r = null;
            this._animations = [];
            this.aria = {
                "aria-valuenow": {
                    name: "value",
                    type: "number"
                },
                "aria-valuemin": {
                    name: "min",
                    type: "number"
                },
                "aria-valuemax": {
                    name: "max",
                    type: "number"
                },
                "aria-disabled": {
                    name: "disabled",
                    type: "boolean"
                }
            }
        },
        createInstance: function (e) {
            d.jqx.aria(this);
            this._radius = this.radius;
            this.value = new Number(this.value);
            this.endAngle = this.endAngle * Math.PI / 180 + Math.PI / 2;
            this.startAngle = this.startAngle * Math.PI / 180 + Math.PI / 2;
            this._refresh();
            this.setValue(this.value, 0);
            this._r.getContainer().css("overflow", "hidden");
            if (!this.host.jqxChart) {
                throw new Error("jqxGauge: Missing reference to jqxchart.js.")
            }
            var f = this;
            d.jqx.utilities.resize(this.host, function () {
                f._refresh()
            });
            this.host.addClass(this.toThemeProperty("jqx-widget"))
        },
        _validateEasing: function () {
            return !!d.easing[this.easing]
        },
        _validateProperties: function () {
            if (this.startAngle === this.endAngle) {
                throw new Error("The end angle can not be equal to the start angle!")
            }
            if (!this._validateEasing()) {
                this.easing = "linear"
            }
            this.ticksDistance = this._validatePercentage(this.ticksDistance, "20%");
            this.border = this._borderConstructor(this.border, this);
            this.style = this.style || {
                fill: "#ffffff",
                stroke: "#E0E0E0"
            };
            this.ticksMinor = new this._tickConstructor(this.ticksMinor, this);
            this.ticksMajor = new this._tickConstructor(this.ticksMajor, this);
            this.cap = new this._capConstructor(this.cap, this);
            this.pointer = new this._pointerConstructor(this.pointer, this);
            this.labels = new this._labelsConstructor(this.labels, this);
            this.caption = new this._captionConstructor(this.caption, this);
            for (var e = 0; e < this.ranges.length; e += 1) {
                this.ranges[e] = new this._rangeConstructor(this.ranges[e], this)
            }
        },
        _hostInit: function () {
            var h = this._getScale(this.width, "width", this.host.parent()),
                e = this._getScale(this.height, "height", this.host.parent()),
                g = this._outerBorderOffset(),
                i = this.host,
                f;
            i.width(h);
            i.height(e);
            this.radius = f = (this._getScale(this._radius, "width", this.host) || h / 2) - g;
            this._originalRadius = parseInt(this.radius, 10) - this._radiusDifference;
            this._innerRadius = this._originalRadius;
            if (this.border) {
                this._innerRadius -= this._getSize(this.border.size)
            }
            i[0].innerHTML = "<div />";
            this._gaugeParent = i.children();
            this._gaugeParent.width(h);
            this._gaugeParent.height(e);
            this._r.init(this._gaugeParent)
        },
        _refresh: function () {
            var e = null;
            this._isVML = false;
            if (document.createElementNS && (this.renderEngine == "SVG" || this.renderEngine == undefined)) {
                e = new d.jqx.svgRenderer();
                if (!e.init(this.host)) {
                    if (this.renderEngine == "SVG") {
                        throw "Your browser does not support SVG"
                    }
                    return
                }
            }
            if (e == null && this.renderEngine != "HTML5") {
                e = new d.jqx.vmlRenderer();
                if (!e.init(this.host)) {
                    if (this.renderEngine == "VML") {
                        throw "Your browser does not support VML"
                    }
                    return
                }
                this._isVML = true
            }
            if (e == null && (this.renderEngine == "HTML5" || this.renderEngine == undefined)) {
                e = new d.jqx.HTML5Renderer();
                if (!e.init(this.host)) {
                    throw "Your browser does not support HTML5 Canvas"
                }
            }
            this._r = e;
            this._validateProperties();
            this._hostInit();
            this._removeElements();
            this._render();
            this.setValue(this.value, 0)
        },
        val: function (e) {
            if (arguments.length == 0 || typeof (e) == "object") {
                return this.value
            }
            this.setValue(e, 0)
        },
        refresh: function () {
            this._refresh.apply(this, Array.prototype.slice(arguments))
        },
        _outerBorderOffset: function () {
            var e = parseInt(this.border.style["stroke-width"], 10) || 1;
            return e / 2
        },
        _removeCollection: function (f) {
            for (var e = 0; e < f.length; e += 1) {
                d(f[e]).remove()
            }
            f = []
        },
        _render: function () {
            this._addBorder();
            this._addGauge();
            this._addRanges();
            this._addTicks();
            this._addLabels();
            this._addCaption();
            this._addPointer();
            this._addCap()
        },
        _addBorder: function () {
            if (!this.border.visible) {
                return
            }
            var e = this.border.style.fill,
                f = this._outerBorderOffset();
            if (!e) {
                e = "#BABABA"
            }
            if (this.border.showGradient) {
                if (e.indexOf("url") < 0 && e.indexOf("#grd") < 0) {
                    this._originalColor = e
                } else {
                    e = this._originalColor
                }
                e = this._r._toLinearGradient(e, true, [
                    [0, 1],
                    [25, 1.1],
                    [50, 1.5],
                    [100, 1]
                ])
            }
            this._border = this._r.circle(this._originalRadius + f, this._originalRadius + f, this._originalRadius);
            this.border.style.fill = e;
            this._r.attr(this._border, this.border.style)
        },
        _addGauge: function () {
            var g = this._originalRadius,
                e = this._r._toLinearGradient("#ffffff", [
                    [3, 2],
                    [100, 1]
                ]),
                f = this._outerBorderOffset();
            this._gauge = this._r.circle(g + f, g + f, this._innerRadius);
            this._r.attr(this._gauge, this.style)
        },
        _addCap: function () {
            var e = "visible",
                g = this._outerBorderOffset();
            if (!this.cap.visible) {
                e = "hidden"
            }
            var h = this._originalRadius,
                f = this._getSize(this.cap.size),
                i;
            i = this._r.circle(h + g, h + g, f);
            this._capCenter = [h, h];
            this._r.attr(i, this.cap.style);
            d(i).css("visibility", e);
            this._cap = i
        },
        _addTicks: function () {
            var e = this.ticksMinor,
                l = this.ticksMajor,
                h = e.interval,
                k = l.interval,
                m = {};
            for (var g = this.min, f = this.min; g <= this.max || f <= this.max; g += h, f += k) {
                if (f <= this.max && l.visible) {
                    this._drawTick({
                        angle: this._getAngleByValue(f),
                        distance: this._getDistance(this.ticksDistance),
                        style: l.style,
                        size: this._getSize(l.size),
                        type: "major"
                    });
                    m[f.toFixed(5)] = true
                }
                if (!m[g.toFixed(5)] && e.visible) {
                    if (g <= this.max) {
                        this._drawTick({
                            angle: this._getAngleByValue(g),
                            distance: this._getDistance(this.ticksDistance),
                            style: e.style,
                            size: this._getSize(e.size),
                            type: "minor"
                        })
                    }
                }
            }
            this._handleTicksVisibility()
        },
        _handleTicksVisibility: function () {
            if (!this.ticksMinor.visible) {
                this.host.children(".jqx-gauge-tick-minor").css("visibility", "hidden")
            } else {
                this.host.children(".jqx-gauge-tick-minor").css("visibility", "visible")
            } if (!this.ticksMajor.visible) {
                this.host.children(".jqx-gauge-tick-major").css("visibility", "hidden")
            } else {
                this.host.children(".jqx-gauge-tick-major").css("visibility", "visible")
            }
        },
        _getSize: function (e) {
            if (e.toString().indexOf("%") >= 0) {
                e = (parseInt(e, 10) / 100) * this._innerRadius
            }
            e = parseInt(e, 10);
            return e
        },
        _getDistance: function (e) {
            return this._getSize(e) + (this._originalRadius - this._innerRadius)
        },
        _drawTick: function (q) {
            var j = q.angle,
                g = q.distance,
                p = q.size,
                k = this._outerBorderOffset(),
                e = this._originalRadius,
                i = e - g,
                l = i - p,
                h = e + k + i * Math.sin(j),
                n = e + k + i * Math.cos(j),
                f = e + k + l * Math.sin(j),
                m = e + k + l * Math.cos(j),
                o;
            q.style["class"] = this.toThemeProperty("jqx-gauge-tick-" + q.type);
            if (this._isVML) {
                h = Math.round(h);
                f = Math.round(f);
                n = Math.round(n);
                m = Math.round(m)
            }
            o = this._r.line(h, n, f, m, q.style);
            this._ticks.push(o)
        },
        _addRanges: function () {
            var f = "visible";
            if (!this.showRanges) {
                f = "hidden"
            } else {
                var e = this.ranges;
                for (var g = 0; g < e.length; g += 1) {
                    this._addRange(e[g], f)
                }
            }
        },
        _getMaxRangeSize: function () {
            var f, h = -1,
                j, e;
            for (var g = 0; g < this.ranges.length; g += 1) {
                j = this.ranges[g].startWidth;
                e = this.ranges[g].endWidth;
                if (j > h) {
                    h = j
                }
                if (e > h) {
                    h = e
                }
            }
            return h
        },
        _getRangeDistance: function (i, e) {
            var h = this._getLabelsDistance(),
                f = this._getDistance(i),
                g = this._getMaxRangeSize();
            if (this.labels.position === "outside") {
                if (h < f + this._getMaxTickSize()) {
                    return this._getDistance(this.ticksDistance) + g / 2 + this._getSize(this.ticksMajor.size)
                }
            } else {
                if (this.labels.position === "inside") {
                    if (h + this._getMaxTickSize() < f) {
                        return this._getSize(this.border.size) + this._originalRadius / 20
                    }
                }
            }
            return f
        },
        _addRange: function (m, g) {
            if (m.startValue < this.min || m.endValue > this.max) {
                return
            }
            var p = this._getAngleByValue(m.startValue),
                j = this._getAngleByValue(m.endValue),
                n = this._originalRadius,
                f = n - this._getRangeDistance(m.startDistance, m.startWidth),
                r = n - this._getRangeDistance(m.endDistance, m.endWidth),
                l = m.startWidth,
                e = m.endWidth,
                k = this._outerBorderOffset(),
                i = {
                    x: n + k + f * Math.sin(p),
                    y: n + k + f * Math.cos(p)
                }, q = {
                    x: n + k + r * Math.sin(j),
                    y: n + k + r * Math.cos(j)
                }, s = this._getProjectionPoint(p, n + k, f, l),
                o = this._getProjectionPoint(j, n + k, r, e),
                h = "default",
                t, m;
            if (Math.abs(j - p) > Math.PI) {
                h = "opposite"
            }
            if (this._isVML) {
                t = this._rangeVMLRender(i, q, n, s, o, e, l, f, r, h)
            } else {
                t = this._rangeSVGRender(i, q, n, s, o, e, l, f, r, h)
            }
            m.style.visibility = g;
            m.style["class"] = this.toThemeProperty("jqx-gauge-range");
            m = this._r.path(t, m.style);
            this._ranges.push(m)
        },
        _rangeSVGRender: function (i, m, k, o, l, e, j, f, n, h) {
            var p = "",
                f = k - f,
                n = k - n,
                g = ["0,1", "0,0"];
            if (h === "opposite") {
                g = ["1,1", "1,0"]
            }
            p = "M" + i.x + "," + i.y + " ";
            p += "A" + (k - f) + "," + (k - f) + " 100 " + g[0] + " " + m.x + "," + m.y + " ";
            p += "L " + (l.x) + "," + (l.y) + " ";
            p += "A" + (k - e - f) + "," + (k - e - f) + " 100 " + g[1] + " " + (o.x) + "," + (o.y) + " ";
            p += "L " + (i.x) + "," + (i.y) + " ";
            p += "z";
            return p
        },
        _rangeVMLRender: function (p, m, h, w, i, l, n, q, s, f) {
            h -= h - q + 10;
            var o = "",
                r = Math.floor(h + (n + l) / 2),
                q = Math.floor(h - q),
                s = Math.floor(s),
                t = {
                    x: (w.x + i.x) / 2,
                    y: (w.y + i.y) / 2
                }, e = Math.sqrt((i.x - w.x) * (i.x - w.x) + (i.y - w.y) * (i.y - w.y)),
                v = Math.floor(t.x + Math.sqrt(h * h - (e / 2) * (e / 2)) * (w.y - i.y) / e),
                u = Math.floor(t.y + Math.sqrt(h * h - (e / 2) * (e / 2)) * (i.x - w.x) / e),
                x = {
                    x: (p.x + m.x) / 2,
                    y: (p.y + m.y) / 2
                }, g = Math.sqrt((m.x - p.x) * (m.x - p.x) + (m.y - p.y) * (m.y - p.y)),
                k = Math.floor(x.x + Math.sqrt(Math.abs(r * r - (g / 2) * (g / 2))) * (p.y - m.y) / g),
                j = Math.floor(x.y + Math.sqrt(Math.abs(r * r - (g / 2) * (g / 2))) * (m.x - p.x) / g);
            if (f === "opposite") {
                v = Math.floor(t.x - Math.sqrt(h * h - (e / 2) * (e / 2)) * (w.y - i.y) / e);
                u = Math.floor(t.y - Math.sqrt(h * h - (e / 2) * (e / 2)) * (i.x - w.x) / e);
                k = Math.floor(x.x - Math.sqrt(Math.abs(r * r - (g / 2) * (g / 2))) * (p.y - m.y) / g);
                j = Math.floor(x.y - Math.sqrt(Math.abs(r * r - (g / 2) * (g / 2))) * (m.x - p.x) / g)
            }
            h = Math.floor(h);
            m = {
                x: Math.floor(m.x),
                y: Math.floor(m.y)
            };
            p = {
                x: Math.floor(p.x),
                y: Math.floor(p.y)
            };
            w = {
                x: Math.floor(w.x),
                y: Math.floor(w.y)
            };
            i = {
                x: Math.floor(i.x),
                y: Math.floor(i.y)
            };
            o = "m " + m.x + "," + m.y;
            o += "at " + (k - r) + " " + (j - r) + " " + (r + k) + " " + (r + j) + " " + m.x + "," + m.y + " " + p.x + "," + p.y;
            o += "l " + w.x + "," + w.y;
            o += "m " + m.x + "," + m.y;
            o += "l " + i.x + "," + i.y;
            o += "at " + (v - h) + " " + (u - h) + " " + (h + v) + " " + (h + u) + " " + i.x + "," + i.y + " " + w.x + "," + w.y;
            o += "qx " + w.x + " " + w.y;
            return o
        },
        _getProjectionPoint: function (i, f, h, g) {
            var e = {
                x: f + (h - g) * Math.sin(i),
                y: f + (h - g) * Math.cos(i)
            };
            return e
        },
        _addLabels: function (f) {
            var g = this._getDistance(this._getLabelsDistance());
            for (var e = this.min; e <= this.max; e += this.labels.interval) {
                if (this.labels.visible) {
                    this._addLabel({
                        angle: this._getAngleByValue(e),
                        value: this.labels.interval >= 1 ? e : new Number(e).toFixed(2),
                        distance: g,
                        style: this.labels.className
                    })
                }
            }
        },
        _getLabelsDistance: function () {
            var g = this._getMaxLabelSize(),
                f = this._getDistance(this.labels.distance),
                e = this._getDistance(this.ticksDistance);
            g = g.width;
            if (this.labels.position === "inside") {
                return e + g - 5
            } else {
                if (this.labels.position === "outside") {
                    if (f < (e - g * 1.5)) {
                        return f
                    }
                    return Math.max(e - g * 1.5, 0.6 * g)
                }
            }
            return this.labels.distance
        },
        _addLabel: function (q) {
            var g = q.angle,
                f = this._originalRadius,
                o = f - q.distance,
                h = this.labels.offset,
                p = this.labels.formatValue,
                i = this._outerBorderOffset(),
                m = f + i + o * Math.sin(g) + h[0],
                k = f + i + o * Math.cos(g) + h[1],
                n = q.value,
                j = q.style || "",
                e, l;
            if (typeof p === "function") {
                n = p(n)
            }
            e = this._r.measureText(n, 0, {
                "class": j
            });
            l = this._r.text(n, Math.round(m) - e.width / 2, Math.round(k), e.width, e.height, 0, {
                "class": this.toThemeProperty("jqx-gauge-label")
            });
            this._labels.push(l)
        },
        _addCaption: function () {
            var l = this.caption.value,
                j = this.toThemeProperty("jqx-gauge-caption"),
                k = this.caption.offset,
                h = this._r.measureText(l, 0, {
                    "class": j
                }),
                e = this._getPosition(this.caption.position, h, k),
                i = this.caption.style,
                f = this._outerBorderOffset(),
                g = this._r.text(l, e.left + f, e.top + f, h.width, h.height, 0, {
                    "class": j
                });
            this._caption = g
        },
        _getPosition: function (e, f, j) {
            var i = 0,
                h = 0,
                g = this._originalRadius;
            switch (e) {
            case "left":
                i = (g - f.width) / 2;
                h = g - f.height / 2;
                break;
            case "right":
                i = g + (g - f.width) / 2;
                h = g - f.height / 2;
                break;
            case "bottom":
                i = (2 * g - f.width) / 2;
                h = (g + 2 * g - f.height) / 2;
                break;
            default:
                i = (2 * g - f.width) / 2;
                h = (g + f.height) / 2;
                break
            }
            return {
                left: i + j[0],
                top: h + j[1]
            }
        },
        _addPointer: function () {
            var g = "visible";
            if (!this.pointer.visible) {
                g = "hidden"
            }
            var f = this._originalRadius,
                i = this._getSize(this.pointer.length),
                j = i * 0.9,
                k = this._getAngleByValue(this.value),
                e = this.pointer.pointerType,
                h;
            h = this._computePointerPoints(this._getSize(this.pointer.width), k, i, e !== "default");
            this._pointer = this._r.path(h, this.pointer.style);
            d(this._pointer).css("visibility", g)
        },
        _computePointerPoints: function (e, g, h, f) {
            if (!f) {
                return this._computeArrowPoints(e, g, h)
            } else {
                return this._computeRectPoints(e, g, h)
            }
        },
        _computeArrowPoints: function (n, g, k) {
            var f = this._originalRadius - 0.5,
                l = Math.sin(g),
                q = Math.cos(g),
                j = this._outerBorderOffset(),
                o = f + j + k * l,
                m = f + j + k * q,
                i = f + j + n * q,
                e = f + j - n * l,
                h = f + j - n * q,
                s = f + j + n * l,
                p;
            if (this._isVML) {
                i = Math.round(i);
                h = Math.round(h);
                e = Math.round(e);
                s = Math.round(s);
                o = Math.round(o);
                m = Math.round(m)
            }
            p = "M " + i + "," + e + " L " + h + "," + s + " L " + o + "," + m + "";
            return p
        },
        _computeRectPoints: function (q, i, o) {
            var f = this._originalRadius,
                p = Math.sin(i),
                t = Math.cos(i),
                u = o,
                l = this._outerBorderOffset(),
                n = f + l - q * t + o * p,
                h = f + l + q * p + o * t,
                m = f + l + q * t + o * p,
                g = f + l - q * p + o * t,
                k = f + l + q * t,
                e = f + l - q * p,
                j = f + l - q * t,
                v = f + l + q * p,
                s;
            if (this._isVML) {
                k = Math.round(k);
                j = Math.round(j);
                e = Math.round(e);
                v = Math.round(v);
                n = Math.round(n);
                h = Math.round(h);
                m = Math.round(m);
                g = Math.round(g)
            }
            s = "M " + k + "," + e + " L " + j + "," + v + " L " + n + "," + h + " " + m + "," + g;
            return s
        },
        _getAngleByValue: function (i) {
            var h = this.startAngle,
                g = this.endAngle,
                k = this.min,
                e = this.max,
                f = (h - g) / (e - k);
            var j = f * (i - this.min) + h + Math.PI;
            return j
        },
        _setValue: function (g) {
            if (g <= this.max && g >= this.min) {
                var h = this._getAngleByValue(g),
                    e = this.pointer.pointerType,
                    f = this._computePointerPoints(this._getSize(this.pointer.width), h, this._getSize(this.pointer.length), e !== "default");
                if (this._isVML) {
                    this._r.attr(this._pointer.childNodes[0], {
                        v: f
                    })
                } else {
                    this._r.attr(this._pointer, {
                        d: f
                    })
                }
                this.value = g;
                d.jqx.aria(this, "aria-valuenow", g)
            }
        },
        propertyChangedHandler: function (e, f, h, g) {
            if (f == "min") {
                this.min = parseInt(g);
                d.jqx.aria(e, "aria-valuemin", g)
            }
            if (f == "max") {
                this.max = parseInt(g);
                d.jqx.aria(e, "aria-valuemax", g)
            }
            if (f == "value") {
                this.value = parseInt(g)
            }
            if (f === "disabled") {
                if (g) {
                    this.disable()
                } else {
                    this.enable()
                }
                d.jqx.aria(this, "aria-disabled", g)
            } else {
                if (f === "value") {
                    this.value = h;
                    this.setValue(g)
                } else {
                    if (f === "startAngle") {
                        this.startAngle = this.startAngle * Math.PI / 180 + Math.PI / 2
                    } else {
                        if (f === "endAngle") {
                            this.endAngle = this.endAngle * Math.PI / 180 + Math.PI / 2
                        } else {
                            if (f === "colorScheme") {
                                this.pointer.style = null;
                                this.cap.style = null
                            } else {
                                if (f === "radius") {
                                    this._radius = g
                                }
                            }
                        }
                    } if (f !== "animationDuration" && f !== "easing") {
                        this._refresh()
                    }
                }
            } if (this._r instanceof d.jqx.HTML5Renderer) {
                this._r.refresh()
            }
        },
        _tickConstructor: function (f, e) {
            if (this.host) {
                return new this._tickConstructor(f, e)
            }
            f = f || {};
            this.size = e._validatePercentage(f.size, "10%");
            this.interval = parseFloat(f.interval);
            if (!this.interval) {
                this.interval = 5
            }
            this.style = f.style || {
                stroke: "#898989",
                "stroke-width": 1
            };
            if (typeof f.visible === "undefined") {
                this.visible = true
            } else {
                this.visible = f.visible
            }
        },
        _capConstructor: function (g, e) {
            var f = e._getColorScheme(e.colorScheme)[0];
            if (this.host) {
                return new this._capConstructor(g, e)
            }
            g = g || {};
            if (typeof g.visible === "undefined") {
                this.visible = true
            } else {
                this.visible = g.visible
            }
            this.size = e._validatePercentage(g.size, "4%");
            this.style = g.style || {
                fill: f,
                "stroke-width": "1px",
                stroke: f,
                "z-index": 30
            }
        },
        _pointerConstructor: function (g, e) {
            var f = e._getColorScheme(e.colorScheme)[0];
            if (this.host) {
                return new this._pointerConstructor(g, e)
            }
            g = g || {};
            if (typeof g.visible === "undefined") {
                this.visible = true
            } else {
                this.visible = g.visible
            }
            this.pointerType = g.pointerType;
            if (this.pointerType !== "default" && this.pointerType !== "rectangle") {
                this.pointerType = "default"
            }
            this.style = g.style || {
                "z-index": 0,
                stroke: f,
                fill: f,
                "stroke-width": 1
            };
            this.length = e._validatePercentage(g.length, "70%");
            this.width = e._validatePercentage(g.width, "2%")
        },
        _labelsConstructor: function (f, e) {
            if (this.host) {
                return new this._labelsConstructor(f, e)
            }
            f = f || {};
            if (typeof f.visible === "undefined") {
                this.visible = true
            } else {
                this.visible = f.visible
            }
            this.offset = f.offset;
            if (!(this.offset instanceof Array)) {
                this.offset = [0, -10]
            }
            this.interval = parseFloat(f.interval);
            if (!this.interval) {
                this.interval = 20
            }
            this.distance = e._validatePercentage(f.distance, "38%");
            this.position = f.position;
            if (this.position !== "inside" && this.position !== "outside") {
                this.position = "none"
            }
            this.formatValue = f.formatValue;
            if (typeof this.formatValue !== "function") {
                this.formatValue = function (g) {
                    return g
                }
            }
        },
        _captionConstructor: function (f, e) {
            if (this.host) {
                return new this._captionConstructor(f, e)
            }
            f = f || {};
            if (typeof f.visible === "undefined") {
                this.visible = true
            } else {
                this.visible = f.visible
            }
            this.value = f.value || "";
            this.position = f.position;
            if (this.position !== "bottom" && this.position !== "top" && this.position !== "left" && this.position !== "right") {
                this.position = "bottom"
            }
            this.offset = f.offset;
            if (!(this.offset instanceof Array)) {
                this.offset = [0, 0]
            }
        },
        _rangeConstructor: function (f, e) {
            if (this.host) {
                return new this._rangeConstructor(f, e)
            }
            f = f || {};
            this.startDistance = e._validatePercentage(f.startDistance, "5%");
            this.endDistance = e._validatePercentage(f.endDistance, "5%");
            this.style = f.style || {
                fill: "#000000",
                stroke: "#111111"
            };
            this.startWidth = parseInt(f.startWidth, 10);
            if (!this.startWidth) {
                this.startWidth = 10
            }
            this.startWidth = Math.max(this.startWidth, 2);
            this.endWidth = parseInt(f.endWidth, 10);
            if (!this.endWidth) {
                this.endWidth = 10
            }
            this.endWidth = Math.max(this.endWidth, 2);
            this.startValue = parseInt(f.startValue, 10);
            if (!this.startValue) {
                this.startValue = 0
            }
            this.endValue = parseInt(f.endValue, 10);
            if (undefined == this.endValue) {
                this.endValue = 100
            }
        },
        _borderConstructor: function (f, e) {
            if (this.host) {
                return new this._borderConstructor(f, e)
            }
            f = f || {};
            this.size = e._validatePercentage(f.size, "10%");
            this.style = f.style || {
                stroke: "#cccccc"
            };
            if (typeof f.showGradient === "undefined") {
                this.showGradient = true
            } else {
                this.showGradient = f.showGradient
            } if (typeof f.visible === "undefined") {
                this.visible = true
            } else {
                this.visible = f.visible
            }
        }
    };
    var c = {
        _events: ["valueChanging", "valueChanged"],
        _animationTimeout: 10,
        _schemes: d.jqx._jqxChart.prototype.colorSchemes,
        _getScale: function (e, g, f) {
            if (e && e.toString().indexOf("%") >= 0) {
                e = parseInt(e, 10) / 100;
                return f[g]() * e
            }
            return parseInt(e, 10)
        },
        _removeElements: function () {
            this.host.children(".chartContainer").remove();
            this.host.children("#tblChart").remove()
        },
        _getMaxLabelSize: function () {
            var h = this.max,
                e = this.min;
            if (this.labels.interval < 1) {
                e = new Number(e).toFixed(2);
                h = new Number(h).toFixed(2)
            }
            var g = this._r.measureText(h, 0, {
                "class": this.toThemeProperty("jqx-gauge-label")
            }),
                f = this._r.measureText(e, 0, {
                    "class": this.toThemeProperty("jqx-gauge-label")
                });
            if (f.width > g.width) {
                return f
            }
            return g
        },
        disable: function () {
            this.disabled = true;
            this.host.addClass(this.toThemeProperty("jqx-fill-state-disabled"))
        },
        enable: function () {
            this.disabled = false;
            this.host.removeClass(this.toThemeProperty("jqx-fill-state-disabled"))
        },
        destroy: function () {
            this._removeElements()
        },
        _validatePercentage: function (f, e) {
            if (parseFloat(f) !== 0 && (!f || !parseInt(f, 10))) {
                f = e
            }
            return f
        },
        _getColorScheme: function (f) {
            var e;
            for (var g = 0; g < this._schemes.length; g += 1) {
                e = this._schemes[g];
                if (e.name === f) {
                    return e.colors
                }
            }
            return null
        },
        setValue: function (e, f) {
            if (!this.disabled) {
                if (e > this.max) {
                    e = this.max
                }
                if (e < this.min) {
                    e = this.min
                }
                f = f || this.animationDuration || 0;
                var g = f / this._animationTimeout;
                this._animate((e - this.value) / g, this.value, e, f);
                d.jqx.aria(this, "aria-valuenow", e)
            }
        },
        _animate: function (f, h, e, g) {
            if (this._timeout) {
                this._endAnimation(this.value, false)
            }
            if (!g) {
                this._endAnimation(e, true);
                return
            }
            this._animateHandler(f, h, e, 0, g)
        },
        _animateHandler: function (g, j, e, i, h) {
            var f = this;
            if (i <= h) {
                this._timeout = setTimeout(function () {
                    f.value = j + (e - j) * d.easing[f.easing](i / h, i, 0, 1, h);
                    f._setValue(f.value);
                    f._raiseEvent(0, {
                        value: f.value
                    });
                    f._animateHandler(g, j, e, i + f._animationTimeout, h)
                }, this._animationTimeout)
            } else {
                this._endAnimation(e, true)
            }
        },
        _endAnimation: function (e, f) {
            clearTimeout(this._timeout);
            this._timeout = null;
            this._setValue(e);
            if (f) {
                this._raiseEvent(1, {
                    value: e
                })
            }
        },
        _getMaxTickSize: function () {
            return Math.max(this._getSize(this.ticksMajor.size), this._getSize(this.ticksMinor.size))
        },
        _raiseEvent: function (g, f) {
            var h = d.Event(this._events[g]),
                e;
            h.args = f || {};
            e = this.host.trigger(h);
            return e
        }
    }, a = {
            defineInstance: function () {
                this.value = -50;
                this.max = 40;
                this.min = -60;
                this.width = 100;
                this.height = 300;
                this.pointer = {};
                this.labels = {};
                this.animationDuration = 1000;
                this.showRanges = {};
                this.ticksMajor = {
                    size: "15%",
                    interval: 5
                };
                this.ticksMinor = {
                    size: "10%",
                    interval: 2.5
                };
                this.ranges = [];
                this.easing = "easeOutCubic";
                this.colorScheme = "scheme01";
                this.disabled = false;
                this.rangesOffset = 0;
                this.background = {};
                this.ticksPosition = "both";
                this.rangeSize = "5%";
                this.scaleStyle = null;
                this.ticksOffset = null;
                this.scaleLength = "90%";
                this.orientation = "vertical";
                this.aria = {
                    "aria-valuenow": {
                        name: "value",
                        type: "number"
                    },
                    "aria-valuemin": {
                        name: "min",
                        type: "number"
                    },
                    "aria-valuemax": {
                        name: "max",
                        type: "number"
                    },
                    "aria-disabled": {
                        name: "disabled",
                        type: "boolean"
                    }
                };
                this._originalColor;
                this._width;
                this._height;
                this._r
            },
            createInstance: function () {
                d.jqx.aria(this);
                this.host.css("overflow", "hidden");
                if (!this.host.jqxChart) {
                    throw new Error("jqxGauge: Missing reference to jqxchart.js.")
                }
                this.host.addClass(this.toThemeProperty("jqx-widget"));
                var e = this;
                d.jqx.utilities.resize(this.host, function () {
                    e.refresh()
                })
            },
            val: function (e) {
                if (arguments.length == 0 || typeof (e) == "object") {
                    return this.value
                }
                this.setValue(e, 0)
            },
            refresh: function (f) {
                var e = null;
                this._isVML = false;
                if (document.createElementNS && (this.renderEngine == "SVG" || this.renderEngine == undefined)) {
                    e = new d.jqx.svgRenderer();
                    if (!e.init(this.host)) {
                        if (this.renderEngine == "SVG") {
                            throw "Your browser does not support SVG"
                        }
                        return
                    }
                }
                if (e == null && this.renderEngine != "HTML5") {
                    e = new d.jqx.vmlRenderer();
                    if (!e.init(this.host)) {
                        if (this.renderEngine == "VML") {
                            throw "Your browser does not support VML"
                        }
                        return
                    }
                    this._isVML = true
                }
                if (e == null && (this.renderEngine == "HTML5" || this.renderEngine == undefined)) {
                    e = new d.jqx.HTML5Renderer();
                    if (!e.init(this.host)) {
                        throw "Your browser does not support HTML5 Canvas"
                    }
                }
                this._r = e;
                this._validateProperties();
                this._reset();
                this._init();
                this._performLayout();
                this._render()
            },
            _getBorderSize: function () {
                var f = 1,
                    e;
                if (this._isVML) {
                    f = 0
                }
                if (this.background) {
                    e = (parseInt(this.background.style["stroke-width"], 10) || f) / 2;
                    if (this._isVML) {
                        return Math.round(e)
                    }
                    return e
                }
                return f
            },
            _validateProperties: function () {
                this.background = this._backgroundConstructor(this.background, this);
                this.ticksOffset = this.ticksOffset || this._getDefaultTicksOffset();
                this.rangesOffset = this.rangesOffset || 0;
                this.rangeSize = this._validatePercentage(this.rangeSize, 5);
                this.ticksOffset[0] = this._validatePercentage(this.ticksOffset[0], "5%");
                this.ticksOffset[1] = this._validatePercentage(this.ticksOffset[1], "5%");
                this.ticksMinor = this._tickConstructor(this.ticksMinor, this);
                this.ticksMajor = this._tickConstructor(this.ticksMajor, this);
                this.scaleStyle = this.scaleStyle || this.ticksMajor.style;
                this.labels = this._labelsConstructor(this.labels, this);
                this.pointer = this._pointerConstructor(this.pointer, this);
                for (var e = 0; e < this.ranges.length; e += 1) {
                    this.ranges[e] = this._rangeConstructor(this.ranges[e], this)
                }
            },
            _getDefaultTicksOffset: function () {
                if (this.orientation === "horizontal") {
                    return ["5%", "36%"]
                }
                return ["36%", "5%"]
            },
            _handleOrientation: function () {
                if (this.orientation === "vertical") {
                    d.extend(this, linearVerticalGauge)
                } else {
                    d.extend(this, linearHorizontalGauge)
                }
            },
            _reset: function () {
                this.host.empty()
            },
            _performLayout: function () {
                var e = parseInt(this.background.style["stroke-width"], 10) || 1;
                this._width -= e;
                this._height -= e;
                this.host.css("padding", e / 2)
            },
            _init: function () {
                var f = this._getBorderSize(),
                    e;
                this._width = this._getScale(this.width, "width", this.host.parent()) - 3;
                this._height = this._getScale(this.height, "height", this.host.parent()) - 3;
                this.element.innerHTML = "<div/>";
                this.host.width(this._width);
                this.host.height(this._height);
                this.host.children().width(this._width);
                this.host.children().height(this._height);
                this._r.init(this.host.children());
                e = this._r.getContainer();
                e.width(this._width);
                e.height(this._height)
            },
            _render: function () {
                this._renderBackground();
                this._renderTicks();
                this._renderLabels();
                this._renderRanges();
                this._renderPointer()
            },
            _renderBackground: function () {
                if (!this.background.visible) {
                    return
                }
                var g = this.background.style,
                    f = d.jqx._rup(this._getBorderSize()),
                    e = "rect",
                    h;
                g = this._handleShapeOptions(g);
                if (this.background.backgroundType === "roundedRectangle" && this._isVML) {
                    e = "roundrect"
                }
                if (!this._Vml) {
                    g.x = f;
                    g.y = f
                }
                h = this._r.shape(e, g);
                if (this._isVML) {
                    this._fixVmlRoundrect(h, g)
                }
            },
            _handleShapeOptions: function (g) {
                var e = this.background.style.fill,
                    f = this._getBorderSize();
                if (!e) {
                    e = "#cccccc"
                }
                if (this.background.showGradient) {
                    if (e.indexOf("url") < 0 && e.indexOf("#grd") < 0) {
                        this._originalColor = e
                    } else {
                        e = this._originalColor
                    }
                    e = this._r._toLinearGradient(e, this.orientation === "horizontal", [
                        [1, 1.1],
                        [90, 1.5]
                    ])
                }
                this.background.style.fill = e;
                if (this.background.backgroundType === "roundedRectangle") {
                    if (this._isVML) {
                        g.arcsize = this.background.borderRadius + "%"
                    } else {
                        g.rx = this.background.borderRadius;
                        g.ry = this.background.borderRadius
                    }
                }
                g.width = this._width - 1;
                g.height = this._height - 1;
                return g
            },
            _fixVmlRoundrect: function (g, f) {
                var e = this._getBorderSize();
                g.style.position = "absolute";
                g.style.left = e;
                g.style.top = e;
                g.style.width = this._width - 1;
                g.style.height = this._height - 1;
                g.strokeweight = 0;
                delete f.width;
                delete f.height;
                delete f.arcsize;
                this._r.attr(g, f)
            },
            _renderTicks: function () {
                var k = Math.abs(this.max - this.min),
                    h = this.ticksMinor,
                    f = this.ticksMajor,
                    i = k / f.interval,
                    g = k / h.interval,
                    e, j;
                e = {
                    size: this._getSize(f.size),
                    style: f.style,
                    visible: f.visible,
                    interval: f.interval
                };
                j = {
                    size: this._getSize(h.size),
                    style: h.style,
                    visible: h.visible,
                    interval: h.interval,
                    checkOverlap: true
                };
                if (this.ticksPosition === "near" || this.ticksPosition === "both") {
                    this._ticksRenderHandler(e);
                    this._ticksRenderHandler(j)
                }
                if (this.ticksPosition === "far" || this.ticksPosition === "both") {
                    e.isFar = true;
                    j.isFar = true;
                    this._ticksRenderHandler(e);
                    this._ticksRenderHandler(j)
                }
                this._renderConnectionLine()
            },
            _ticksRenderHandler: function (f) {
                if (!f.visible) {
                    return
                }
                var i = this._getSize(this.ticksOffset[0], "width"),
                    g = this._getSize(this.ticksOffset[1], "height"),
                    e = this._getBorderSize(),
                    h = this._calculateTickOffset() + this._getMaxTickSize();
                if (f.isFar) {
                    h += f.size
                }
                this._drawTicks(f, e, h + e)
            },
            _drawTicks: function (g, f, j) {
                var e;
                for (var h = this.min; h <= this.max; h += g.interval) {
                    e = this._valueToCoordinates(h);
                    if (!g.checkOverlap || !this._overlapTick(h)) {
                        this._renderTick(g.size, e, g.style, j)
                    }
                }
            },
            _calculateTickOffset: function () {
                var f = this._getSize(this.ticksOffset[0], "width"),
                    e = this._getSize(this.ticksOffset[1], "height"),
                    g = e;
                if (this.orientation === "vertical") {
                    g = f
                }
                return g
            },
            _overlapTick: function (e) {
                e += this.min;
                if (e % this.ticksMinor.interval === e % this.ticksMajor.interval) {
                    return true
                }
                return false
            },
            _renderConnectionLine: function () {
                if (!this.ticksMajor.visible && !this.ticksMinor.visible) {
                    return
                }
                var f = this._getScaleLength(),
                    e = this._getBorderSize(),
                    h = this._valueToCoordinates(this.max),
                    j = this._valueToCoordinates(this.min),
                    i = this._getMaxTickSize(),
                    g = i + e;
                if (this.orientation === "vertical") {
                    g += this._getSize(this.ticksOffset[0], "width");
                    this._r.line(g, h, g, j, this.scaleStyle)
                } else {
                    g += this._getSize(this.ticksOffset[1], "height");
                    this._r.line(h, g, j, g, this.scaleStyle)
                }
            },
            _getScaleLength: function () {
                return this._getSize(this.scaleLength, (this.orientation === "vertical" ? "height" : "width"))
            },
            _renderTick: function (e, i, f, h) {
                var g = this._handleTickCoordinates(e, i, h);
                this._r.line(Math.round(g.x1), Math.round(g.y1), Math.round(g.x2), Math.round(g.y2), f)
            },
            _handleTickCoordinates: function (e, g, f) {
                if (this.orientation === "vertical") {
                    return {
                        x1: f - e,
                        x2: f,
                        y1: g,
                        y2: g
                    }
                }
                return {
                    x1: g,
                    x2: g,
                    y1: f - e,
                    y2: f
                }
            },
            _getTickCoordinates: function (f, g) {
                var e = this._handleTickCoordinates(f, 0, this._calculateTickOffset());
                if (this.orientation === "vertical") {
                    e = e.x1
                } else {
                    e = e.y1
                }
                e += f;
                return e
            },
            _renderLabels: function () {
                if (!this.labels.visible) {
                    return
                }
                var g = this._getSize(this.ticksOffset[0], "width"),
                    i = this._getMaxTickSize(),
                    k = this.labels.position,
                    j = "height",
                    f = this._getBorderSize(),
                    h = this._calculateTickOffset() + i,
                    e;
                if (this.orientation === "vertical") {
                    g = this._getSize(this.ticksOffset[1], "height");
                    j = "width"
                }
                e = this._getMaxLabelSize()[j];
                if (k === "near" || k === "both") {
                    this._labelListRender(h - i - e + f, g + f, e, "near")
                }
                if (k === "far" || k === "both") {
                    this._labelListRender(h + i + e + f, g + f, e, "far")
                }
            },
            _labelListRender: function (k, e, f, m) {
                var h = this.labels.interval,
                    n = Math.abs(this.max - this.min) / h,
                    g = this._getScaleLength(),
                    j = g / n,
                    o = (this.orientation === "vertical") ? this.max : this.min;
                k += this._getSize(this.labels.offset);
                for (var l = 0; l <= n; l += 1) {
                    this._renderLabel(e, m, k, f, o);
                    o += (this.orientation === "vertical") ? -h : h;
                    e += j
                }
            },
            _renderLabel: function (f, m, j, g, n) {
                var i = {
                    "class": this.toThemeProperty("jqx-gauge-label")
                }, h = this.labels.interval,
                    l, e, k;
                k = this.labels.formatValue(n, m);
                e = this._r.measureText(k, 0, i);
                if (this.orientation === "vertical") {
                    l = (m === "near") ? g - e.width : 0;
                    this._r.text(k, Math.round(j) + l - g / 2, Math.round(f - e.height / 2), e.width, e.height, 0, i)
                } else {
                    l = (m === "near") ? g - e.height : 0;
                    this._r.text(k, Math.round(f - e.width / 2), Math.round(j) + l - g / 2, e.width, e.height, 0, i)
                }
            },
            _renderRanges: function () {
                if (!this.showRanges) {
                    return
                }
                var h = (this.orientation === "vertical") ? "width" : "height",
                    j = this._getSize(this.rangesOffset, h),
                    g = this._getSize(this.rangeSize, h),
                    e;
                for (var f = 0; f < this.ranges.length; f += 1) {
                    e = this.ranges[f];
                    e.size = g;
                    this._renderRange(e, j)
                }
            },
            _renderRange: function (q, k) {
                var h = this._getScaleLength(),
                    j = this._getBorderSize(),
                    i = this._getSize(this.ticksOffset[0], "width"),
                    g = this._getSize(this.ticksOffset[1], "height"),
                    n = this._getMaxTickSize(),
                    p = this._getSize(q.size),
                    m = this._valueToCoordinates(q.endValue);
                var f = q.startValue;
                if (f < this.min) {
                    f = this.min
                }
                var o = Math.abs(this._valueToCoordinates(f) - m),
                    l, e;
                if (this.orientation === "vertical") {
                    l = this._r.rect(i + n + k - p + j, m, q.size, o, q.style)
                } else {
                    e = o;
                    l = this._r.rect(this._valueToCoordinates(q.startValue), g + n + j, e, q.size, q.style)
                }
                this._r.attr(l, q.style)
            },
            _renderPointer: function () {
                if (!this.pointer.visible) {
                    return
                }
                if (this.pointer.pointerType === "default") {
                    this._renderColumnPointer()
                } else {
                    this._renderArrowPointer()
                }
            },
            _renderColumnPointer: function () {
                this._pointer = this._r.rect(0, 0, 0, 0, this.pointer.style);
                this._r.attr(this._pointer, this.pointer.style);
                this._setValue(this.value)
            },
            _renderArrowPointer: function () {
                var e = this._getArrowPathByValue(0);
                this._pointer = this._r.path(e, this.pointer.style)
            },
            _getArrowPathByValue: function (n) {
                var h = this._getBorderSize(),
                    l = Math.ceil(this._valueToCoordinates(n)) + h,
                    f = h,
                    g = Math.ceil(this._getSize(this.ticksOffset[0], "width")),
                    e = Math.ceil(this._getSize(this.ticksOffset[1], "height")),
                    i = Math.ceil(this._getSize(this.pointer.offset)),
                    m = Math.ceil(this._getMaxTickSize()),
                    q = Math.ceil(this._getSize(this.pointer.size)),
                    j = Math.ceil(Math.sqrt((q * q) / 3)),
                    p, k, o;
                if (this.orientation === "vertical") {
                    f += g + m + i;
                    k = (i >= 0) ? f + q : f - q;
                    p = "M " + f + " " + l + " L " + k + " " + (l - j) + " L " + k + " " + (l + j)
                } else {
                    f += g + m * 2 + j + i;
                    o = l;
                    l = f;
                    f = o;
                    k = (i >= 0) ? l - q : l + q;
                    p = "M " + f + " " + l + " L " + (f - j) + " " + k + " L " + (f + j) + " " + k
                }
                return p
            },
            _setValue: function (e) {
                if (this.pointer.pointerType === "default") {
                    this._performColumnPointerLayout(e)
                } else {
                    this._performArrowPointerLayout(e)
                }
                this.value = e
            },
            _performColumnPointerLayout: function (h) {
                var e = this._valueToCoordinates(this.min),
                    m = this._valueToCoordinates(h),
                    p = Math.abs(e - m),
                    k = this._getBorderSize(),
                    j = this._getSize(this.ticksOffset[0], "width"),
                    g = this._getSize(this.ticksOffset[1], "height"),
                    n = this._getMaxTickSize(),
                    f = this._getSize(this.pointer.size),
                    l = this._getSize(this.pointer.offset),
                    o = {}, i;
                if (this.orientation === "vertical") {
                    i = j + n;
                    o = {
                        left: i + l + 1 + k,
                        top: m,
                        height: p,
                        width: f
                    }
                } else {
                    i = g + n;
                    o = {
                        left: e,
                        top: i + l - f - 1 + k,
                        height: f,
                        width: p
                    }
                }
                this._setRectAttrs(o)
            },
            _performArrowPointerLayout: function (f) {
                var e = this._getArrowPathByValue(f);
                if (this._isVML) {
                    this._r.attr(this._pointer.childNodes[0], {
                        v: e
                    });
                    this._pointer.v = e
                } else {
                    this._r.attr(this._pointer, {
                        d: e
                    })
                }
            },
            _setRectAttrs: function (e) {
                if (!this._isVML) {
                    this._r.attr(this._pointer, {
                        x: e.left
                    });
                    this._r.attr(this._pointer, {
                        y: e.top
                    });
                    this._r.attr(this._pointer, {
                        width: e.width
                    });
                    this._r.attr(this._pointer, {
                        height: e.height
                    })
                } else {
                    this._pointer.style.top = e.top;
                    this._pointer.style.left = e.left;
                    this._pointer.style.width = e.width;
                    this._pointer.style.height = e.height
                }
            },
            _valueToCoordinates: function (h) {
                var e = this._getBorderSize(),
                    j = this._getScaleLength(),
                    g = this._getSize(this.ticksOffset[0], "width"),
                    f = this._getSize(this.ticksOffset[1], "height"),
                    i = Math.abs(this.min - h),
                    k = Math.abs(this.max - this.min);
                if (this.orientation === "vertical") {
                    return this._height - (i / k) * j - (this._height - f - j) + e
                }
                return (i / k) * j + (this._width - g - j) + e
            },
            _getSize: function (e, f) {
                f = f || (this.orientation === "vertical" ? "width" : "height");
                if (e.toString().indexOf("%") >= 0) {
                    e = (parseInt(e, 10) / 100) * this["_" + f]
                }
                e = parseInt(e, 10);
                return e
            },
            propertyChangedHandler: function (f, g, i, h) {
                if (g == "min") {
                    this.min = parseInt(h);
                    d.jqx.aria(this, "aria-valuemin", h)
                }
                if (g == "max") {
                    this.max = parseInt(h);
                    d.jqx.aria(this, "aria-valuemax", h)
                }
                if (g == "value") {
                    this.value = parseInt(h)
                }
                if (g === "disabled") {
                    if (h) {
                        this.disable()
                    } else {
                        this.enable()
                    }
                    d.jqx.aria(this, "aria-disabled", h)
                } else {
                    if (g === "value") {
                        this.value = i;
                        this.setValue(h)
                    } else {
                        if (g === "colorScheme") {
                            this.pointer.style = null
                        } else {
                            if (g === "orientation" && i !== h) {
                                var e = this.ticksOffset[0];
                                this.ticksOffset[0] = this.ticksOffset[1];
                                this.ticksOffset[1] = e
                            }
                        } if (g !== "animationDuration" && g !== "easing") {
                            this.refresh()
                        }
                    }
                } if (this._r instanceof d.jqx.HTML5Renderer) {
                    this._r.refresh()
                }
            },
            _backgroundConstructor: function (g, e) {
                if (this.host) {
                    return new this._backgroundConstructor(g, e)
                }
                var f = {
                    rectangle: true,
                    roundedRectangle: true
                };
                g = g || {};
                this.style = g.style || {
                    stroke: "#cccccc",
                    fill: null
                };
                if (g.visible || typeof g.visible === "undefined") {
                    this.visible = true
                } else {
                    this.visible = false
                } if (f[g.backgroundType]) {
                    this.backgroundType = g.backgroundType
                } else {
                    this.backgroundType = "roundedRectangle"
                } if (this.backgroundType === "roundedRectangle") {
                    if (typeof g.borderRadius === "number") {
                        this.borderRadius = g.borderRadius
                    } else {
                        this.borderRadius = 15
                    }
                }
                if (typeof g.showGradient === "undefined") {
                    this.showGradient = true
                } else {
                    this.showGradient = g.showGradient
                }
            },
            _tickConstructor: function (f, e) {
                if (this.host) {
                    return new this._tickConstructor(f, e)
                }
                this.size = e._validatePercentage(f.size, "10%");
                this.interval = parseFloat(f.interval);
                if (!this.interval) {
                    this.interval = 5
                }
                this.style = f.style || {
                    stroke: "#A1A1A1",
                    "stroke-width": "1px"
                };
                if (typeof f.visible === "undefined") {
                    this.visible = true
                } else {
                    this.visible = f.visible
                }
            },
            _labelsConstructor: function (f, e) {
                if (this.host) {
                    return new this._labelsConstructor(f, e)
                }
                this.position = f.position;
                if (this.position !== "far" && this.position !== "near" && this.position !== "both") {
                    this.position = "both"
                }
                if (typeof f.formatValue === "function") {
                    this.formatValue = f.formatValue
                } else {
                    this.formatValue = function (g) {
                        return g
                    }
                }
                this.visible = f.visible;
                if (this.visible !== false && this.visible !== true) {
                    this.visible = true
                }
                if (typeof f.interval !== "number") {
                    this.interval = 10
                } else {
                    this.interval = f.interval
                }
                this.offset = e._validatePercentage(f.offset, 0)
            },
            _rangeConstructor: function (f, e) {
                if (this.host) {
                    return new this._rangeConstructor(f, e)
                }
                if (typeof f.startValue === "number") {
                    this.startValue = f.startValue
                } else {
                    this.startValue = e.min
                } if (typeof f.endValue === "number" && f.endValue > f.startValue) {
                    this.endValue = f.endValue
                } else {
                    this.endValue = this.startValue + 1
                }
                this.style = f.style || {
                    fill: "#dddddd",
                    stroke: "#dddddd"
                }
            },
            _pointerConstructor: function (g, e) {
                if (this.host) {
                    return new this._pointerConstructor(g, e)
                }
                var f = e._getColorScheme(e.colorScheme)[0];
                this.pointerType = g.pointerType;
                if (this.pointerType !== "default" && this.pointerType !== "arrow") {
                    this.pointerType = "default"
                }
                this.style = g.style || {
                    fill: f,
                    stroke: f,
                    "stroke-width": 1
                };
                this.size = e._validatePercentage(g.size, "7%");
                this.visible = g.visible;
                if (this.visible !== true && this.visible !== false) {
                    this.visible = true
                }
                this.offset = e._validatePercentage(g.offset, 0)
            }
        };
    d.extend(b, c);
    d.extend(a, c);
    d.jqx.jqxWidget("jqxLinearGauge", "", {});
    d.jqx.jqxWidget("jqxGauge", "", {});
    d.extend(d.jqx._jqxGauge.prototype, b);
    d.extend(d.jqx._jqxLinearGauge.prototype, a)
})(jQuery);