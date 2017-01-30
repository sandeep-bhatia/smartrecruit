 (function () {
	'use strict';
	var App = {
		init: function () {
			// The shim requires options to be supplied for it's configuration,
			// which can be found lower down in this file. Most of the below are
			// demo specific and should be used for reference within this context
			// only
			if ( !!this.options ) {
				this.pos = 0;
				this.cam = null;
				this.filter_on = false;
				this.endrecord = false;
				this.recording = false;
				this.filter_id = 0;
				this.guidValue = '';
				this.indexValue = 0;
				this.intervalId = 0;
				this.canvas = document.getElementById("canvas");
				this.ctx = this.canvas.getContext("2d");
				this.img = new Image();
				this.ctx.clearRect(0, 0, this.options.width, this.options.height);
				this.image = this.ctx.getImageData(0, 0, this.options.width, this.options.height);
				this.startResponseBtn = document.getElementById('startResponse');


				getUserMedia(this.options, this.success, this.deviceError);
				window.webcam = this.options;
				this.addEvent('click', this.startResponseBtn, this.InitResponse);

			} else {
				alert('No options were supplied to the shim!');
			}
		},

		addEvent: function (type, obj, fn) {
			if (obj.attachEvent) {
				obj['e' + type + fn] = fn;
				obj[type + fn] = function () {
					obj['e' + type + fn](window.event);
				}
				obj.attachEvent('on' + type, obj[type + fn]);
			} else {
				obj.addEventListener(type, fn, false);
			}
		},

		// options contains the configuration information for the shim
		// it allows us to specify the width and height of the video
		// output we're working with, the location of the fallback swf,
		// events that are triggered onCapture and onSave (for the fallback)
		// and so on.
		options: {
			"audio": true,
			"video": true,
			el: "webcam",

			extern: null,
			append: true,

			width: 320, 
			height: 240, 

			mode: "callback",
			// callback | save | stream
			swffile: "../dist/fallback/jscam_canvas_only.swf",
			quality: 85,
			context: "",
			
			debug: function () {},
			onCapture: function () {
				window.webcam.save();
			},
			onTick: function () {},
			onSave: function (data) {

				var col = data.split(";"),
					img = App.image,
					tmp = null,
					w = this.width,
					h = this.height;

				for (var i = 0; i < w; i++) { 
					tmp = parseInt(col[i], 10);
					img.data[App.pos + 0] = (tmp >> 16) & 0xff;
					img.data[App.pos + 1] = (tmp >> 8) & 0xff;
					img.data[App.pos + 2] = tmp & 0xff;
					img.data[App.pos + 3] = 0xff;
					App.pos += 4;
				}

				if (App.pos >= 4 * w * h) { 
					App.ctx.putImageData(img, 0, 0);
					App.pos = 0;
				}

			},
			onLoad: function () {}
		},

		success: function (stream) {

			if (App.options.context === 'webrtc') {

				var video = App.options.videoEl,
					vendorURL = window.URL || window.webkitURL;
				video.src = vendorURL ? vendorURL.createObjectURL(stream) : stream;

				video.onerror = function () {
					stream.stop();
					streamError();
				};

			} else{
				//flash context
			}
			
		},

		deviceError: function (error) {
			alert('No camera available.');
			//console.error('An error occurred: [CODE ' + error.code + ']');
		},

		changeFilter: function () {
			if (this.filter_on) {
				this.filter_id = (this.filter_id + 1) & 7;
			}
		},



		getSnapshot: function () {
			// If the current context is WebRTC/getUserMedia (something
			// passed back from the shim to avoid doing further feature
			// detection), we handle getting video/images for our canvas 
			// from our HTML5 <video> element.
			if (App.options.context === 'webrtc') {
				var video = document.getElementsByTagName('video')[0]; 
				App.canvas.width = video.videoWidth;
				App.canvas.height = video.videoHeight;
				App.canvas.getContext('2d').drawImage(video, 0, 0);

			// Otherwise, if the context is Flash, we ask the shim to
			// directly call window.webcam, where our shim is located
			// and ask it to capture for us.
			} else if(App.options.context === 'flash'){

				window.webcam.capture();
				App.changeFilter();
			}
			else{
				alert('No context was supplied to getSnapshot()');
			}


		},


		InitResponse: function() {
				var guid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) { 
				    var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8); 
				    return v.toString(16); 
				});
				window.indexValue = 0;
				window.guidValue = guid;
				window.recording = true;
		},


		startResponse: function () {
									if(window.recording)
									{
										App.getSnapshot(); 
										var data = App.canvas.toDataURL("image/png");
										var data = 'imageData=' + encodeURIComponent(App.canvas.toDataURL("image/png")) + '&guid=' + window.guidValue + '&imageIndex=' + window.indexValue;
										window.indexValue = window.indexValue + 1;
										$.ajax({  
											 type: "POST",  
											 url: "/imagedatapost.php",  
	 	                						         cache: false, 
											 data: data,  
											 success: function(html) {  
											 } 
									     	       });  
									}
	       },

              endResponse: function() {
			window.recording = false;
			var data = 'guid=' + encodeURIComponent(window.guidValue);  

			$.ajax({  
					 type: "POST",  
					 url: "/endrecording.php",  
      			    		 cache: false, 
					 data: data,  
					 success: function(html) {  
					 	
					 } 
				  });  
		
	      }
	};
	window.AppObject = App;
	App.init();
;

})();


