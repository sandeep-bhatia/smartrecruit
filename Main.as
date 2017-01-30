package 
{
	//flash related imports
	import flash.display.Sprite;
	import flash.media.Microphone;
	import flash.media.Video;
	import flash.system.Security;
	import flash.events.MouseEvent;
	import flash.events.Event;
	import fl.transitions.Tween;
	import fl.transitions.easing.Strong;
	import flash.media.Camera;
	import flash.display.Bitmap;
	import flash.display.BitmapData;
	import flash.utils.Timer;
	import flash.events.TimerEvent;
	import flash.net.URLLoader;
    import flash.net.URLRequest;
    import flash.net.URLRequestHeader;
    import flash.net.URLRequestMethod;
    import flash.net.URLVariables;
	import flash.utils.ByteArray;
	
	//external code imports
	import org.bytearray.micrecorder.*;
	import org.bytearray.micrecorder.events.RecordingEvent;
	import org.bytearray.micrecorder.encoder.WaveEncoder;
	import com.adobe.images.PNGEncoder;
	import flash.utils.setInterval;
	import flash.utils.clearInterval;
		
	//This is the main class for our audio video recorder 
	//please modify the same if you change the corressponding php pages etc
	public class Main extends Sprite
	{
		//sound related variables
		private var mic:Microphone;
		private var waveEncoder:WaveEncoder = new WaveEncoder();
		private var recorder:MicRecorder = new MicRecorder(waveEncoder);
		
		//rec bar related variable, don't know how to use tween came as side effect here
		private var recBar:RecBar = new RecBar();
		private var tween:Tween;
		
		//video capturing capabilities
		private var video:Video;
 		private var camera:Camera;
		
		//image related intermediate variables
		private var imgBD:BitmapData;
 		private var imgBitmap:Bitmap;
		
		//request builders for PHP
		
 		private var sendHeader:URLRequestHeader;
 		private var sendReq:URLRequest;
 		private var sendLoader:URLLoader;
		private var imgBA:ByteArray;
		private var soundBA:ByteArray;
		private var sendHeaderAudio:URLRequestHeader;
 		private var sendReqAudio:URLRequest;
 		private var sendLoaderAudio:URLLoader;
		
		//Video related variables
		private var imageWidth:int = 300;
		private var imageHeight:int = 180;
		private var framesPerSecond:int = 30;
		private var bandwidth:int = 0; // Specifies the maximum amount of bandwidth that the current outgoing video feed can use, in bytes per second. To specify that Flash Player video can use as much bandwidth as needed to maintain the value of quality , pass 0 for bandwidth . The default value is 16384.
 		private var quality:int = 100; // this value is 0-100 with 1 being the lowest quality.
		
		//private php Paths
		private var phpPath:String  = "http://localhost/saveimg.php";
		private var phpAudioPath:String = "http://localhost/audiosave.php";
		private var requestType:String = "Content-type";
		private var streamType:String = "application/octet-stream";
		private var intervalId: uint;
		private var frameQueue:Queue;
		private var recordingStarted: Boolean;
		
		public function Main():void
		{
			framesPerSecond = root.loaderInfo.parameters.fps;
			setupVideoCapture();
			setupMicrophone();
			captureHouseKeeping();
			prepareSaveImageHeaders();
			prepareAudioHeaders();
			addListeners();
			
		}

		private function captureHouseKeeping() : void
		{
			recButton.stop();
			recordingStarted = false;
			imgBD = new BitmapData(video.width,video.height);
 		 	var timerInterval = 1000 / framesPerSecond;
			intervalId = setInterval(captureVideoFrameAndSend, timerInterval);
			frameQueue = new Queue();
		}
		
		private function setupMicrophone() : void
		{
			mic = Microphone.getMicrophone();
			mic.setSilenceLevel(0);
			mic.gain = 100;
			mic.setLoopBack(false);
			mic.setUseEchoSuppression(true);
		}
		
		private function setupVideoCapture() : void 
		{
			camera = Camera.getCamera();
 			camera.setQuality(bandwidth, quality);
 			camera.setMode(imageWidth,imageHeight, framesPerSecond ,false); // setMode(videoWidth, videoHeight, video fps, favor area)
 			video = new Video(imageWidth, imageHeight);
 			video.attachCamera(camera);
		}
		
		private function prepareSaveImageHeaders() : void 
		{
			sendHeader = new URLRequestHeader(requestType, streamType);
    		sendReq = new URLRequest(phpPath);
    		sendReq.requestHeaders.push(sendHeader);
    		sendReq.method = URLRequestMethod.POST;
    		sendLoader = new URLLoader();
    		sendLoader.addEventListener(Event.COMPLETE,imageSentHandler);
		}
		
		private function prepareAudioHeaders() : void 
		{
			sendHeaderAudio = new URLRequestHeader(requestType, streamType);
    		sendReqAudio = new URLRequest(phpAudioPath);
    		sendReqAudio.requestHeaders.push(sendHeaderAudio);
    		sendReqAudio.method = URLRequestMethod.POST;
    		sendLoaderAudio = new URLLoader();
    		sendLoaderAudio.addEventListener(Event.COMPLETE,audioSentHandler);
		}
		
		private function addListeners():void
		{
			recButton.addEventListener(MouseEvent.MOUSE_UP, startRecording);
			recorder.addEventListener(RecordingEvent.RECORDING, recording);
		}

		private function startRecording(e:MouseEvent):void
		{
			if (mic != null)
			{
				recorder.record();
				e.target.gotoAndStop(2);

				recButton.removeEventListener(MouseEvent.MOUSE_UP, startRecording);
				recButton.addEventListener(MouseEvent.MOUSE_UP, stopRecording);
				addChild(video);
				addChild(recBar);
				tween = new Tween(recBar,"y",Strong.easeOut, -  recBar.height,0,1,true);
				
			}
			
			recordingStarted = true;
		}
		
		private function captureVideoFrameAndSend():void
		{
			if(recordingStarted)
			{
				imgBD.draw(video);
				imgBA = PNGEncoder.encode(imgBD);
				prepareSaveImageHeaders();
				sendReq.data = imgBA;
 		    	sendLoader.load(sendReq);
			}
		}

		private function stopRecording(e:MouseEvent):void
		{
			recordingStarted = false;
			recorder.stop();
			clearInterval(intervalId);
			soundBA = recorder.output;
			sendReqAudio.data = soundBA;
 		    sendLoaderAudio.load(sendReqAudio);
						
						
			mic.setLoopBack(false);
			e.target.gotoAndStop(1);
			recButton.removeEventListener(MouseEvent.MOUSE_UP, stopRecording);
			recButton.addEventListener(MouseEvent.MOUSE_UP, startRecording);

			tween = new Tween(recBar,"y",Strong.easeOut,0, recBar.height,1,true);
		}

		private function recording(e:RecordingEvent):void
		{
			var currentTime:int = Math.floor(e.time / 1000);
			recBar.counter.text = String(currentTime) + ' seconds';
		}

		function imageSentHandler(event:Event):void {
    		var dataStr:String = event.currentTarget.data.toString();
		} 
		
		function audioSentHandler(event:Event):void {
    		var dataStr:String = event.currentTarget.data.toString();
		} 
	}
}