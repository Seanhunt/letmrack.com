/**
Script: Slideshow.KenBurns.js
	Slideshow.KenBurns - KenBurns extension for Slideshow, includes zooming and panning effects.

License:
	MIT-style license.

Copyright:
	Copyright (c) 2008 [Aeron Glemann](http://www.electricprism.com/aeron/).
	
Dependencies:
	Slideshow.
*/
(function(){Slideshow.KenBurns=new Class({Extends:Slideshow,options:{pan:[100,100],zoom:[50,50]},initialize:function(b,c,a){a=a||{};a.overlap=true;a.resize="fill";["pan","zoom"].each(function(d){if(this[d]!=undefined){if(typeOf(this[d])!="array"){this[d]=[this[d],this[d]]}this[d].map(function(e){return(e.toInt()||0).limit(0,100)})}},a);this.parent(b,c,a)},_show:function(c){if(!this.image.retrieve("morph")){$$(this.a,this.b).set({tween:{duration:this.options.duration,link:"cancel",onStart:this._start.bind(this),onComplete:this._complete.bind(this),property:"opacity"},morph:{duration:(this.options.delay+this.options.duration*2),link:"cancel",transition:"linear"}})}this.image.set("styles",{bottom:"auto",left:"auto",right:"auto",top:"auto"});var e=["top left","top right","bottom left","bottom right"][this.counter%4].split(" ");this.image.setStyles([0,0].associate(e));var h=this.data.images[this._slide].replace(/([^?]+).*/,"$1"),b=this.cache[h];dh=this.height/b.height;dw=this.width/b.width;delta=(dw>dh)?dw:dh;var a={},f=(Number.random.apply(Number,this.options.zoom)/100)+1,g=Math.abs((Number.random.apply(Number,this.options.pan)/100)-1);["height","width"].each(function(m,j){var l=Math.ceil(b[m]*delta),k=(l*f).toInt();a[m]=[k,l];if(dw>dh||j){l=(this[m]-this.image[m]);k=(l*g).toInt();a[e[j]]=[k,l]}},this);var d=((this.firstrun&&this.options.paused)||this.paused);if(c||d){this._center(this.image);this.image.get("morph").cancel();if(d){this.image.get("tween").cancel().set(0).start(1)}else{this.image.get("tween").cancel().set(1)}}else{this.image.get("morph").start(a);this.image.get("tween").set(0).start(1)}}})})();