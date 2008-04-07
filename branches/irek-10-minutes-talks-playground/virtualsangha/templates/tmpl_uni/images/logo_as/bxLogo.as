import flash.filters.*;

class logo_as.bxLogo extends MovieClip
{
	private var
		txt1, txt2:TextField,
		mcBack:MovieClip;
		
	function bxLogo()
	{
		txt1.text = txt2.text = "";
	}
	
	function init(sText1:String, sText2:String)
	{
		txt1.text = sText1;
		txt2.text = sText2;
	}
}