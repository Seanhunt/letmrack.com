/**
 Script: Slideshow.js
 Slideshow - A javascript class for Mootools to stream and animate the presentation of images on your website.

 License:
 MIT-style license.

 Copyright:
 Copyright (c) 2011 [Aeron Glemann](http://www.electricprism.com/aeron/).

 Dependencies:
 Mootools 1.3.1 Core: Fx.Morph, Fx.Tween, Selectors, Element.Dimensions.
 Mootools 1.3.1.1 More: Assets.
 */
(function() {
	WhenPaused = 1 << 0;
	WhenPlaying = 1 << 1;
	OnStart = 1 << 2;
	Slideshow = new Class({
		Implements : [Chain, Events, Options],
		options : {
			accesskeys : {
				first : {
					key : "shift left",
					label : "Shift + Leftwards Arrow"
				},
				prev : {
					key : "left",
					label : "Leftwards Arrow"
				},
				pause : {
					key : "p",
					label : "P"
				},
				next : {
					key : "right",
					label : "Rightwards Arrow"
				},
				last : {
					key : "shift right",
					label : "Shift + Rightwards Arrow"
				}
			},
			captions : true,
			center : true,
			classes : [],
			controller : true,
			data : null,
			delay : 2000,
			duration : 1000,
			fast : false,
			height : false,
			href : "",
			hu : "",
			linked : false,
			loader : true,
			loop : true,
			match : /\?slide=(\d+)$/,
			overlap : true,
			paused : false,
			random : false,
			replace : [/(\.[^\.]+)$/, "t$1"],
			resize : "fill",
			slide : 0,
			thumbnails : true,
			titles : false,
			transition : "sine:in:out",
			width : false
		},
		initialize : function(f, k, q) {
			this.setOptions(q);
			this.el = document.id(f);
			if (!this.el) {
				return
			}
			var m = window.location.href.match(this.options.match);
			this.slide = this._slide = this.options.match && m ? m[1].toInt() : this.options.slide;
			this.counter = this.timeToNextTransition = this.timeToTransitionComplete = 0;
			this.direction = "left";
			this.cache = {};
			this.paused = false;
			if (!this.options.overlap) {
				this.options.duration *= 2
			}
			var l = this.el.getElement("a") || new Element("a");
			if (!this.options.href) {
				this.options.href = l.get("href") || ""
			}
			if (this.options.hu.length && !this.options.hu.test(/\/$/)) {
				this.options.hu += "/"
			}
			if (this.options.fast === true) {
				this.options.fast = WhenPaused | WhenPlaying
			}
			var p = "slideshow first prev play pause next last images captions controller thumbnails hidden visible inactive active loader".split(" "), o = p.map(function(s, r) {
				return this.options.classes[r] || s
			}, this);
			this.classes = o.associate(p);
			this.classes.get = function() {
				var t = "." + this.slideshow;
				for (var s = 0, r = arguments.length; s < r; s++) {
					t += "-" + this[arguments[s]]
				}
				return t
			}.bind(this.classes);
			if (!k) {
				this.options.hu = "";
				k = {};
				var g = this.el.getElements(this.classes.get("thumbnails") + " img");
				this.el.getElements(this.classes.get("images") + " img").each(function(t, u) {
					var v = t.src, s = t.alt || t.title, r = t.getParent().href, w = g[u] ? g[u].src : "";
					k[v] = {
						caption : s,
						href : r,
						thumbnail : w
					}
				})
			}
			var j = this.load(k);
			if (!j) {
				return
			}
			this.events = {};
			this.events.push = function(s, r) {
				if (!this[s]) {
					this[s] = []
				}
				this[s].push(r);
				document.addEvent(s, r);
				return this
			}.bind(this.events);
			this.accesskeys = {};
			for (action in this.options.accesskeys) {
				var i = this.options.accesskeys[action];
				this.accesskeys[action] = accesskey = {
					label : i.label
				};
				["shift", "control", "alt"].each(function(r) {
					var s = new RegExp(r, "i");
					accesskey[r] = i.key.test(s);
					i.key = i.key.replace(s, "")
				});
				accesskey.key = i.key.trim()
			}
			this.events.push("keyup", function(r) {
				Object.each(this.accesskeys, function(t, s) {
					if (r.key == t.key && r.shift == t.shift && r.control == t.control && r.alt == t.alt) {
						this[s]()
					}
				}, this)
			}.bind(this));
			var f = this.el.getElement(this.classes.get("images")), h = this.el.getElement("img") || new Element("img"), n = f ? f.empty() : new Element("div", {
				"class" : this.classes.get("images").substr(1)
			}).inject(this.el), e = n.getSize();
			this.height = this.options.height || e.y;
			this.width = this.options.width || e.x;
			n.set({
				styles : {
					height : this.height,
					width : this.width
				}
			});
			this.el.store("images", n);
			this.a = this.image = h;
			if (Browser.ie && Browser.version >= 7) {
				this.a.style.msInterpolationMode = "bicubic"
			}
			this.a.set("styles", {
				display : "none"
			});
			this.b = this.a.clone();
			[this.a, this.b].each(function(r) {
				l.clone().cloneEvents(l).grab(r).inject(n)
			});
			this.options.captions && new a(this);
			this.options.controller && new c(this);
			this.options.loader && new d(this);
			this.options.thumbnails && new b(this);
			this._preload(this.options.fast & OnStart)
		},
		go : function(g, f) {
			var e = (this.slide + this.data.images.length) % this.data.images.length;
			if (g == e || Date.now() < this.timeToTransitionComplete) {
				return
			}
			clearTimeout(this.timer);
			this.timeToNextTransition = this.timeToTransitionComplete = 0;
			this.direction = f ? f : g < this._slide ? "right" : "left";
			this.slide = this._slide = g;
			if (this.preloader) {
				this.preloader = this.preloader.destroy()
			}
			this._preload((this.options.fast & WhenPlaying) || (this.paused && this.options.fast & WhenPaused))
		},
		first : function() {
			this.prev(true)
		},
		prev : function(e) {
			var f = 0;
			if (!e) {
				if (this.options.random) {
					if (this.showed.i < 2) {
						return
					}
					this.showed.i -= 2;
					f = this.showed.array[this.showed.i]
				} else {
					f = (this.slide - 1 + this.data.images.length) % this.data.images.length
				}
			}
			this.go(f, "right")
		},
		pause : function(e) {
			if (e != undefined) {
				this.paused = e ? false : true
			}
			if (this.paused) {
				this.paused = false;
				this.timeToTransitionComplete = 0;
				this.timer = this._preload.delay(50, this);
				[this.a, this.b].each(function(f) {
					["morph", "tween"].each(function(g) {
						if (this.retrieve(g)) {
							this.get(g).resume()
						}
					}, f)
				});
				this.controller && this.el.retrieve("pause").getParent().removeClass(this.classes.play);
				this.fireEvent("resume")
			} else {
				this.paused = true;
				this.timeToTransitionComplete = this.timeToTransitionComplete - Date.now();
				clearTimeout(this.timer);
				[this.a, this.b].each(function(f) {
					["morph", "tween"].each(function(g) {
						if (this.retrieve(g)) {
							this.get(g).pause()
						}
					}, f)
				});
				this.controller && this.el.retrieve("pause").getParent().addClass(this.classes.play);
				this.fireEvent("pause")
			}
		},
		next : function(e) {
			var f = e ? this.data.images.length - 1 : this._slide;
			this.go(f, "left")
		},
		last : function() {
			this.next(true)
		},
		load : function(g) {
			this.firstrun = true;
			this.showed = {
				array : [],
				i : 0
			};
			if (typeOf(g) == "array") {
				this.options.captions = false;
				g = new Array(g.length).associate(g.map(function(n, m) {
					return n + "?" + m
				}))
			}
			this.data = {
				images : [],
				captions : [],
				hrefs : [],
				thumbnails : [],
				targets : [],
				titles : []
			};
			for (var j in g) {
				var i = g[j] || {}, j = this.options.hu + j, f = i.caption ? i.caption.trim() : "", e = i.href ? i.href.trim() : this.options.linked ? j : this.options.href, h = i.target ? i.target.trim() : "_self", l = i.thumbnail ? this.options.hu + i.thumbnail.trim() : j.replace(this.options.replace[0], this.options.replace[1]), k = f.replace(/<.+?>/gm, "").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "'");
				this.data.images.push(j);
				this.data.captions.push(f);
				this.data.hrefs.push(e);
				this.data.targets.push(h);
				this.data.thumbnails.push(l);
				this.data.titles.push(k)
			}
			if (this.options.random) {
				this.slide = this._slide = Number.random(0, this.data.images.length - 1)
			}
			if (this.options.thumbnails && this.el.retrieve("thumbnails")) {
				this._thumbnails()
			}
			if (this.el.retrieve("images")) {
				[this.a, this.b].each(function(m) {
					["morph", "tween"].each(function(n) {
						if (this.retrieve(n)) {
							this.get(n).cancel()
						}
					}, m)
				});
				this.slide = this._slide = this.timeToTransitionComplete = 0;
				this.go(0)
			}
			return this.data.images.length
		},
		destroy : function(e) {
			Object.each(this.events, function(g, f) {
				if ("each" in g) {
					g.each(function(h) {
						document.removeEvent(f, h)
					})
				}
			});
			this.pause(1);
			"caption loader thumbnails".split(" ").each(function(f, g) {
				this.options[f] && ( g = this[f].retrieve("timer")) && clearTimeout(g)
			}, this);
			typeOf(this.el[e]) == "function" && this.el[e]();
			delete this.el.uid
		},
		_preload : function(e) {
			var i = this.data.images[this._slide].replace(/([^?]+).*/, "$1"), g = loaded = !!this.cache[i];
			if (!g) {
				if (!this.preloader) {
					this.preloader = new Asset.image(i, {
						onerror : function() {
						},
						onload : function() {
							this.store("loaded", true)
						}
					})
				}
				loaded = this.preloader.retrieve("loaded") && this.preloader.get("width")
			}
			if (loaded && Date.now() > this.timeToNextTransition && Date.now() > this.timeToTransitionComplete) {
				var i = this.data.images[this._slide].replace(/([^?]+).*/, "$1");
				if (this.preloader) {
					this.cache[i] = {
						height : this.preloader.get("height"),
						src : i,
						width : this.preloader.get("width")
					}
				}
				if (this.stopped) {
					if (this.options.captions) {
						this.caption.get("morph").cancel().start(this.classes.get("captions", "hidden"))
					}
					this.pause(1);
					if (this.end) {
						this.fireEvent("end")
					}
					this.stopped = this.end = false;
					return
				}
				this.image = this.counter % 2 ? this.b : this.a;
				this.image.set("styles", {
					display : "block",
					height : null,
					visibility : "hidden",
					width : null,
					zIndex : this.counter
				});
				this.image.set(this.cache[i]);
				this.image.width = this.cache[i].width;
				this.image.height = this.cache[i].height;
				this.options.resize && this._resize(this.image);
				this.options.center && this._center(this.image);
				var f = this.image.getParent();
				if (this.data.hrefs[this._slide]) {
					f.set("href", this.data.hrefs[this._slide]);
					f.set("target", this.data.targets[this._slide])
				} else {
					f.erase("href");
					f.erase("target")
				}
				var h = this.data.titles[this._slide];
				this.image.set("alt", h);
				this.image.set("title", h);
				this.options.titles && f.set("title", h);
				this.options.loader && this.loader.fireEvent("hide");
				this.options.captions && this.caption.fireEvent("update", e);
				this.options.thumbnails && this.thumbnails.fireEvent("update", e);
				this._show(e);
				this._loaded(e)
			} else {
				if (Date.now() > this.timeToNextTransition && this.options.loader) {
					this.loader.fireEvent("show")
				}
				this.timer = this._preload.delay(50, this, e)
			}
		},
		_show : function(f) {
			if (!this.image.retrieve("morph")) {
				var g = this.options.overlap ? {
					link : "cancel"
				} : {
					link : "chain"
				};
				$$(this.a, this.b).set("morph", Object.merge(g, {
					duration : this.options.duration,
					onStart : this._start.bind(this),
					onComplete : this._complete.bind(this),
					transition : this.options.transition
				}))
			}
			var i = this.classes.get("images", (this.direction == "left" ? "next" : "prev")), j = this.classes.get("images", "visible"), e = this.counter % 2 ? this.a : this.b;
			if (f) {
				e.get("morph").cancel().set(i);
				this.image.get("morph").cancel().set(j)
			} else {
				if (this.options.overlap) {
					e.get("morph").set(j);
					this.image.get("morph").set(i).start(j)
				} else {
					var h = function(k) {
						this.image.get("morph").start(k)
					}.pass(j, this);
					if (this.firstrun) {
						return h()
					}
					i = this.classes.get("images", (this.direction == "left" ? "prev" : "next"));
					this.image.get("morph").set(i);
					e.get("morph").set(j).start(i).chain(h)
				}
			}
		},
		_loaded : function(e) {
			this.counter++;
			this.timeToNextTransition = Date.now() + this.options.duration + this.options.delay;
			this.direction = "left";
			this.timeToTransitionComplete = e ? 0 : Date.now() + this.options.duration;
			if (this._slide == (this.data.images.length - 1) && !this.options.loop && !this.options.random) {
				this.stopped = this.end = true
			}
			if (this.options.random) {
				this.showed.i++;
				if (this.showed.i >= this.showed.array.length) {
					var f = this._slide;
					if (this.showed.array.getLast() != f) {
						this.showed.array.push(f)
					}
					while (this._slide == f) {
						this.slide = this._slide = Number.random(0, this.data.images.length - 1)
					}
				} else {
					this.slide = this._slide = this.showed.array[this.showed.i]
				}
			} else {
				this.slide = this._slide;
				this._slide = (this.slide + 1) % this.data.images.length
			}
			if (this.image.getStyle("visibility") != "visible") {
				(function() {
					this.image.setStyle("visibility", "visible")
				}).delay(1, this)
			}
			if (this.preloader) {
				this.preloader = this.preloader.destroy()
			}
			this.paused || this._preload()
		},
		_center : function(f) {
			var g = f.getSize(), i = g.y, e = g.x;
			f.set("styles", {
				left : (e - this.width) / -2,
				top : (i - this.height) / -2
			})
		},
		_resize : function(f) {
			var i = f.get("height").toFloat(), e = f.get("width").toFloat(), j = this.height / i, g = this.width / e;
			if (this.options.resize == "fit") {
				j = g = j > g ? g : j
			}
			if (this.options.resize == "fill") {
				j = g = j > g ? j : g
			}
			f.set("styles", {
				height : Math.ceil(i * j),
				width : Math.ceil(e * g)
			})
		},
		_start : function() {
			this.fireEvent("start")
		},
		_complete : function() {
			if (this.firstrun && this.options.paused) {
				this.pause(1)
			}
			this.firstrun = false;
			this.fireEvent("complete")
		}
	});
	var a = new Class({
		Implements : [Chain, Events, Options],
		options : {
			delay : 0,
			link : "cancel"
		},
		initialize : function(g) {
			if (!g) {
				return
			}
			var f = g.options.captions;
			if (f === true) {
				f = {}
			}
			this.setOptions(f);
			var h = g.el.getElement(g.classes.get("captions")), e = h ? h.dispose().empty() : new Element("div", {
				"class" : g.classes.get("captions").substr(1)
			});
			g.caption = e;
			e.set({
				"aria-busy" : false,
				"aria-hidden" : false,
				events : {
					update : this.update.bind(g)
				},
				morph : this.options,
				role : "description"
			}).store("delay", this.options.delay);
			if (!e.get("id")) {
				e.set("id", "Slideshow-" + Date.now())
			}
			g.el.retrieve("images").set("aria-labelledby", e.get("id"));
			e.inject(g.el)
		},
		update : function(e) {
			var h = !this.data.captions[this._slide].length, j;
			if ( j = this.caption.retrieve("timer")) {
				clearTimeout(j)
			}
			if (e) {
				var i = h ? "hidden" : "visible";
				this.caption.set({
					"aria-hidden" : h,
					html : this.data.captions[this._slide]
				}).get("morph").cancel().set(this.classes.get("captions", i))
			} else {
				var g = h ? function() {
				} : function(k) {
					this.caption.store("timer", setTimeout( function(l) {
						this.caption.set("html", l).morph(this.classes.get("captions", "visible"))
					}.pass(k, this), this.caption.retrieve("delay")))
				}.pass(this.data.captions[this._slide], this);
				var f = function() {
					this.caption.set("aria-busy", false)
				}.bind(this);
				this.caption.set("aria-busy", true).get("morph").cancel().start(this.classes.get("captions", "hidden")).chain(g, f)
			}
		}
	});
	var c = new Class({
		Implements : [Chain, Events, Options],
		options : {
			link : "cancel"
		},
		initialize : function(j) {
			if (!j) {
				return
			}
			var f = j.options.captions;
			if (f === true) {
				f = {}
			}
			this.setOptions(f);
			var k = j.el.getElement(j.classes.get("controller")), e = k ? k.dispose().empty() : new Element("div", {
				"class" : j.classes.get("controller").substr(1)
			});
			j.controller = e;
			e.set({
				"aria-hidden" : false,
				role : "menubar"
			});
			var h = new Element("ul", {
				role : "menu"
			}).inject(e), g = 0;
			Object.each(j.accesskeys, function(n, m) {
				var i = new Element("li", {
					"class" : (m == "pause" && this.options.paused) ? this.classes.play + " " + this.classes[m] : this.classes[m]
				}).inject(h);
				var l = this.el.retrieve(m, new Element("a", {
					role : "menuitem",
					tabindex : g++,
					title : n.label
				}).inject(i));
				l.set("events", {
					click : function(o) {
						this[o]()
					}.pass(m, this),
					mouseenter : function(o) {
						this.addClass(o)
					}.pass(this.classes.active, l),
					mouseleave : function(o) {
						this.removeClass(o)
					}.pass(this.classes.active, l)
				})
			}, j);
			e.set({
				events : {
					hide : this.hide.pass(j.classes.get("controller", "hidden"), e),
					show : this.show.pass(j.classes.get("controller", "visible"), e)
				},
				morph : this.options
			}).store("hidden", false);
			j.events.push("keydown", this.keydown.bind(j)).push("keyup", this.keyup.bind(j)).push("mousemove", this.mousemove.bind(j));
			e.inject(j.el).fireEvent("hide")
		},
		hide : function(e) {
			if (this.get("aria-hidden") == "false") {
				this.set("aria-hidden", true).morph(e)
			}
		},
		keydown : function(f) {
			Object.each(this.accesskeys, function(g, e) {
				if (f.key == g.key && f.shift == g.shift && f.control == g.control && f.alt == g.alt) {
					if (this.controller.get("aria-hidden") == "true") {
						this.controller.get("morph").set(this.classes.get("controller", "visible"))
					}
					this.el.retrieve(e).fireEvent("mouseenter")
				}
			}, this)
		},
		keyup : function(f) {
			Object.each(this.accesskeys, function(g, e) {
				if (f.key == g.key && f.shift == g.shift && f.control == g.control && f.alt == g.alt) {
					if (this.controller.get("aria-hidden") == "true") {
						this.controller.set("aria-hidden", false).fireEvent("hide")
					}
					this.el.retrieve(e).fireEvent("mouseleave")
				}
			}, this)
		},
		mousemove : function(h) {
			var f = this.el.retrieve("images").getCoordinates(), g = (h.page.x > f.left && h.page.x < f.right && h.page.y > f.top && h.page.y < f.bottom) ? "show" : "hide";
			this.controller.fireEvent(g)
		},
		show : function(e) {
			if (this.get("aria-hidden") == "true") {
				this.set("aria-hidden", false).morph(e)
			}
		}
	});
	var d = new Class({
		Implements : [Chain, Events, Options],
		options : {
			fps : 20,
			link : "cancel"
		},
		initialize : function(h) {
			if (!h) {
				return
			}
			var g = h.options.loader;
			if (g === true) {
				g = {}
			}
			this.setOptions(g);
			var e = new Element("div", {
				"aria-hidden" : false,
				"class" : h.classes.get("loader").substr(1),
				morph : this.options,
				role : "progressbar"
			}).store("animate", false).store("i", 0).store("delay", 1000 / this.options.fps).inject(h.el);
			h.loader = e;
			var f = e.getStyle("backgroundImage").replace(/url\(['"]?(.*?)['"]?\)/, "$1").trim();
			if (f && f != "none") {
				if (f.test(/\.png$/) && Browser.ie && Browser.version < 7) {
					e.setStyles({
						backgroundImage : "none",
						filter : 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' + f + '", sizingMethod="crop")'
					})
				}
				new Asset.image(f, {
					onload : function() {
						var j = e.getSize(), k = this.get("width"), i = this.get("height");
						if (k > j.x) {
							e.store("x", j.x).store("animate", "x").store("frames", (k / j.x).toInt())
						}
						if (i > j.y) {
							e.store("y", j.y).store("animate", "y").store("frames", (i / j.y).toInt())
						}
					}
				})
			}
			e.set("events", {
				animate : this.animate.bind(e),
				hide : this.hide.pass(h.classes.get("loader", "hidden"), e),
				show : this.show.pass(h.classes.get("loader", "visible"), e)
			});
			e.fireEvent("hide")
		},
		animate : function() {
			var e = this.retrieve("animate");
			if (!e) {
				return
			}
			var f = (this.retrieve("i").toInt() + 1) % this.retrieve("frames");
			this.store("i", f);
			var g = (f * this.retrieve(e)) + "px";
			if (e == "x") {
				this.setStyle("backgroundPosition", g + " 0px")
			}
			if (e == "y") {
				this.setStyle("backgroundPosition", "0px " + g)
			}
		},
		hide : function(e) {
			if (this.get("aria-hidden") == "false") {
				this.set("aria-hidden", true).morph(e);
				if (this.retrieve("animate")) {
					clearTimeout(this.retrieve("timer"))
				}
			}
		},
		show : function(e) {
			if (this.get("aria-hidden") == "true") {
				this.set("aria-hidden", false).morph(e);
				if (this.retrieve("animate")) {
					this.store("timer", function() {
						this.fireEvent("animate")
					}.periodical(this.retrieve("delay"), this))
				}
			}
		}
	});
	var b = new Class({
		Implements : [Chain, Events, Options],
		options : {
			columns : null,
			fps : 50,
			link : "cancel",
			position : null,
			rows : null,
			scroll : null
		},
		initialize : function(h) {
			var e = (h.options.thumbnails === true) ? {} : h.options.thumbnails;
			this.setOptions(e);
			var j = h.el.getElement(h.classes.get("thumbnails")), l = j ? j.empty() : new Element("div", {
				"class" : h.classes.get("thumbnails").substr(1)
			});
			h.thumbnails = l;
			l.set({
				role : "menubar",
				styles : {
					overflow : "hidden"
				}
			});
			var g = l.retrieve("uid", "Slideshow-" + Date.now()), f = new Element("ul", {
				role : "menu",
				styles : {
					left : 0,
					position : "absolute",
					top : 0
				},
				tween : {
					link : "cancel"
				}
			}).inject(l);
			h.data.thumbnails.each(function(p, o) {
				var m = new Element("li", {
					id : g + o
				}).inject(f), n = new Element("a", {
					"class" : h.classes.get("thumbnails", "hidden").substr(1),
					events : {
						click : this.click.pass(o, h)
					},
					href : h.data.images[o],
					morph : this.options,
					role : "menuitem",
					tabindex : o
				}).store("uid", o).inject(m);
				if (h.options.titles) {
					n.set("title", h.data.titles[o])
				}
				new Asset.image(p, {
					onload : this.onload.pass(o, h)
				}).inject(n)
			}, this);
			l.set("events", {
				scroll : this.scroll.bind(l),
				update : this.update.bind(h)
			});
			var k = l.getCoordinates();
			if (!e.scroll) {
				e.scroll = (k.height > k.width) ? "y" : "x"
			}
			var i = (e.scroll == "y") ? "top bottom height y width".split(" ") : "left right width x height".split(" ");
			l.store("props", i).store("delay", 1000 / this.options.fps);
			h.events.push("mousemove", this.mousemove.bind(l));
			l.inject(h.el);
			if (l.addEventListener) {
				l.set("events", {
					touchstart : this.touchstart.bind(l),
					touchmove : this.touchmove.bind(l),
					touchend : this.touchend.bind(l)
				})
			}
		},
		touchstart : function(f) {
			if (f.touches.length == 1) {
				this.store("touch", [f.touches[0].pageX, f.touches[0].pageY])
			} else {
				this.store("touch", 0)
			}
		},
		touchmove : function(g) {
			var i = this.retrieve("touch");
			if (i) {
				var f = [g.touches[0].pageX, g.touches[0].pageY];
				var h = [i[0] - f[0], i[1] - f[1]];
				if (h[0] || h[1]) {
					g.preventDefault();
					this.store("touch", f);
					this.fireEvent("scroll", [-1, h])
				}
			}
		},
		touchend : function(f) {
			if (this.retrieve("touch")) {
				this.store("touch", 0)
			}
		},
		click : function(e) {
			this.go(e);
			return false
		},
		mousemove : function(g) {
			if (this.retrieve("touch")) {
				return
			}
			var f = this.getCoordinates();
			if (g.page.x > f.left && g.page.x < f.right && g.page.y > f.top && g.page.y < f.bottom) {
				this.store("page", g.page);
				if (!this.retrieve("mouseover")) {
					this.store("mouseover", true);
					this.store("timer", function() {
						this.fireEvent("scroll")
					}.periodical(this.retrieve("delay"), this))
				}
			} else {
				if (this.retrieve("mouseover")) {
					this.store("mouseover", false);
					clearTimeout(this.retrieve("timer"))
				}
			}
		},
		onload : function(l) {
			var j = this.thumbnails, t = j.getElements("a")[l];
			if (t) {
				(function(i) {
					var r = l == this.slide ? "active" : "inactive";
					i.store("loaded", true).get("morph").set(this.classes.get("thumbnails", "hidden")).start(this.classes.get("thumbnails", r))
				}).delay(Math.max(1000 / this.data.thumbnails.length, 100), this, t)
			}
			if (j.retrieve("limit")) {
				return
			}
			var p = j.retrieve("props"), v = this.options.thumbnails, q = p[1], h = p[2], g = p[4], k = j.getElement("li:nth-child(" + (l + 1) + ")"), u = k.getCoordinates();
			if (v.columns || v.rows) {
				j.setStyles({
					height : this.height,
					width : this.width
				});
				if (v.columns.toInt()) {
					j.setStyle("width", u.width * v.columns.toInt())
				}
				if (v.rows.toInt()) {
					j.setStyle("height", u.height * v.rows.toInt())
				}
			}
			var f = j.getCoordinates();
			if (v.position) {
				if (v.position.test(/bottom|top/)) {
					j.setStyles({
						bottom : "auto",
						top : "auto"
					}).setStyle(v.position, -f.height)
				}
				if (v.position.test(/left|right/)) {
					j.setStyles({
						left : "auto",
						right : "auto"
					}).setStyle(v.position, -f.width)
				}
			}
			var o = Math.floor(f[g] / u[g]), s = Math.ceil(this.data.images.length / o), e = this.data.images.length % o, m = s * (u[h] + parseInt(k.getStyle("margin-" + q)) || 0 + parseInt(k.getStyle("margin-" + p[0])) || 0), n = j.getElement("ul").setStyle(h, m);
			n.getElements("li").setStyles({
				height : u.height,
				width : u.width
			});
			j.store("limit", f[h] - m)
		},
		scroll : function(g, j) {
			var e = this.getCoordinates(), l = this.getElement("ul").getPosition(), o = this.retrieve("props"), h = o[3], s, p = o[0], u = o[2], q, t = this.getElement("ul").set("tween", {
				property : p
			}).get("tween");
			if (g < 0) {
				this.store("mouseover", false);
				clearTimeout(this.retrieve("timer"));
				s = -j[h == "x" ? 0 : 1];
				q = (l[h] - e[p] + s).limit(this.retrieve("limit"), 0);
				t.set(q)
			} else {
				if (this.retrieve("touch")) {
					return
				}
				if (g != undefined) {
					var k = this.retrieve("uid"), r = document.id(k + g).getCoordinates();
					s = e[p] + (e[u] / 2) - (r[u] / 2) - r[p];
					q = (l[h] - e[p] + s).limit(this.retrieve("limit"), 0);
					t[j?"set":"start"](q)
				} else {
					var f = e[o[2]] / 3, m = this.retrieve("page"), i = -(this.retrieve("delay") * 0.01);
					if (m[h] < (e[p] + f)) {
						s = (m[h] - e[p] - f) * i
					} else {
						if (m[h] > (e[p] + e[u] - f)) {
							s = (m[h] - e[p] - e[u] + f) * i
						}
					}
					if (s) {
						q = (l[h] - e[p] + s).limit(this.retrieve("limit"), 0);
						t.set(q)
					}
				}
			}
		},
		update : function(e) {
			var g = this.thumbnails, f = g.retrieve("uid");
			g.getElements("a").each(function(h, j) {
				if (h.retrieve("loaded")) {
					if (h.retrieve("uid") == this._slide) {
						if (!h.retrieve("active", false)) {
							h.store("active", true);
							var l = this.classes.get("thumbnails", "active");
							if (e) {
								h.get("morph").set(l)
							} else {
								h.morph(l)
							}
						}
					} else {
						if (h.retrieve("active", true)) {
							h.store("active", false);
							var k = this.classes.get("thumbnails", "inactive");
							if (e) {
								h.get("morph").set(k)
							} else {
								h.morph(k)
							}
						}
					}
				}
			}, this);
			if (!g.retrieve("mouseover")) {
				g.fireEvent("scroll", [this._slide, e])
			}
		}
	})
})(); 