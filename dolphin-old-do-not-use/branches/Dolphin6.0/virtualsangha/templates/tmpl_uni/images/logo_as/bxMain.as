class logo_as.bxMain
{
	private var
		aImages:Array,
		iInterval:Number = 30, iCurrent:Number = -1;
		
	function bxMain(sUrl:String, sImages:String, sInterval:String)
	{
		if(sInterval != undefined && !isNaN(sInterval)) iInterval = Number(sInterval);
		aImages = new Array();
		var aInitImages = sImages.split(",");
		for(var i=0; i<aInitImages.length; i++)
		{
			var mcImage = _root.mcHolder.attachMovie("image", "mcImage" + i, i);
			mcImage.init(sUrl + aInitImages[i]);
			aImages.push(mcImage);
		}
		var oSelf:Object = this;
		aImages[0].onLoading = function(){oSelf.init();}
	}
	
	function init()
	{
		setInterval(this, "showNext", iInterval * 1000);
		showNext();
	}
	
	function showNext()
	{
		aImages[iCurrent].show(false);
		aImages[getNext()].show(true);
	}
	
	function getNext():Number
	{
		for(var i=iCurrent+1; i<aImages.length; i++)
			if(aImages[i].bLoaded)
			{
				iCurrent = i;
				return iCurrent;
			}
		for(var i=0; i<iCurrent; i++)
			if(aImages[i].bLoaded)
			{
				iCurrent = i;
				return iCurrent;
			}
		return iCurrent;
	}
}