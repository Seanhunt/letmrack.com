// -----------------------------------------------------------------------------------
// http://visualslideshow.com/
// Visual JavaScript SlideShow is a free software that helps you easily generate delicious
// slideshows with gorgeous transition effects, in a few clicks without writing a single line of code.
// Last updated: 2012-14-9
// -----------------------------------------------------------------------------------
function VisualSlideShow(a) {
	if (a.effect && a.effect.toLowerCase() == "fade") {
		a.effect = ""
	}
	var c = "";
	var b = /^(.*)visualslideshow\.js$/;
	Array.each($$("script"), function(i, g, f) {
		if (b.test(i.src)) {
			var h = b.exec(i.src);
			c = h[1]
		}
	});
	function e(g, f) {
		document.write('<script type="text/javascript"' + ( g ? ' src="' + c + g + '"' : "") + ">" + (f || "") + "<\/script>")
	}

	e("slideshow.js");
	if (a.effect) {
		e("slideshow." + a.effect.toLowerCase() + ".js")
	}
	if (a.sound) {
		e("swfobject.js")
	}
	window.addEvent("domready", function() {
		if (a.sound) {
			var l;
			if (window.Audio) {
				l = new Audio();
				if (!l.canPlayType || !l.canPlayType("audio/mp3")) {
					l = 0
				}
			}
			if (l) {
				l.src = a.sound;
				if (a.loop) {
					l.loop = "loop"
				}
				if (!a.paused) {
					l.play()
				}
			} else {
				var h = "vssSound" + a.id;
				var f = "vssSL" + a.id;
				window[f] = {
					onInit : function() {
					}
				};
				$(a.id).grab(new Element("div", {
					id : h
				}));
				swfobject.createSWF({
					data : c + "player_mp3_js.swf",
					width : "1",
					height : "1"
				}, {
					allowScriptAccess : "always",
					loop : true,
					FlashVars : "listener=" + f + "&loop=" + (a.loop ? 1 : 0) + "&autoplay=" + (a.paused ? 0 : 1) + "&mp3=" + a.sound
				}, h)
			}
			a.onPause = function() {
				if (l) {
					l.pause()
				} else {
					$(h).SetVariable("method:pause", "")
				}
			};
			a.onResume = function() {
				if (l) {
					l.play()
				} else {
					$(h).SetVariable("method:play", "")
				}
			}
		}
		$$("#" + a.id + " div.slideshow-images img").set({
			styles : {
				position : "absolute"
			}
		});
		var k = new Element("a", {
			href : "#",
			styles : {
				border : "none",
				display : "block",
				height : "100%",
				width : "100%"
			}
		});
		$$("#" + a.id + " .slideshow-frame").grab(k);
		a.onStart = function() {
			k.href = this.image.parentNode.href || "#";
			k.target = this.image.parentNode.target || "_self"
		};
		var g;
		if (a.effect) {
			g = new Slideshow[a.effect](a.id, null, a)
		} else {
			g = new Slideshow(a.id, null, a)
		}
		if (!window.visualslideshow) {
			window.visualslideshow = []
		}
		window.visualslideshow[window.visualslideshow.length] = g;
		var j = $$("#" + a.id + " div.slideshow-images");
		var i = "VisualSlideshow.com";
		if (j && i) {
			var m = new Element("div", {
				styles : {
					position : "absolute",
					right : 0,
					bottom : 0,
					padding : "0 3px 2px",
					"background-color" : "#EEE",
					"z-index" : 999999
				},
				events : {
					contextmenu : function(n) {
						return false
					}
				}
			});
			j.grab(m);
			d = new Element("a", {
				href : "http://" + i.toLowerCase(),
				html : i,
				styles : {
					color : "#555",
					font : "10px Arial,Verdana,sans-serif",
					padding : "0 6px 3px",
					width : "auto",
					height : "auto",
					margin : "0 0 0 0",
					outline : "none"
				}
			});
			m.grab(d)
		}
	})
}VisualSlideShow({
	"duration" : 1000,
	"delay" : 2000,
	"id" : "show",
	"width" : 640,
	"height" : 480,
	"captions" : true,
	"controller" : true,
	"thumbnails" : true,
	"loop" : true,
	"paused" : false,
	"effect" : "Fade"
}); 