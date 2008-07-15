import mx.transitions.*;
import mx.transitions.easing.None;

class logo_as.bxImage extends MovieClip
{
	private var
		mcHolder:MovieClip,
		bLoaded:Boolean = false;
		
	function bxImage()
	{
		this._visible = false;
	}
	
	function init(sUrl:String)
	{
		var mclListener:Object = new Object(), oSelf:Object = this;
		mclListener.onLoadInit = function(mc:MovieClip)
		{
			var width = Stage.width, height = Stage.height;
			mc._xscale = mc._yscale = Math.min(width * 100 / mc._width, height * 100 / mc._height);
			mc._x = (width - mc._width) / 2;
			mc._y = (height - mc._height) / 2;
			oSelf.bLoaded = true;
			oSelf.onLoading();
		}
		
		var mcLoader:MovieClipLoader = new MovieClipLoader();
		mcLoader.addListener(mclListener);
		mcLoader.loadClip(sUrl, mcHolder);
	}
	
	function onLoading(){}
	
	function show(bMode:Boolean)
	{
		if(bMode) this.swapDepths(_parent.getNextHighestDepth());
		TransitionManager.start(this, {type: Fade, direction: bMode ? Transition.IN : Transition.OUT, duration: 1, easing: None.easeNone});
	}
}