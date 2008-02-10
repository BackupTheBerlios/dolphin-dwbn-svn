var imn_height = 200;
var step_d = 10;
var pos_hide = -9999;
var foo = 0;
var food = 1;

var hcNS = (document.layers) ? true : false;
var hcIE = (document.all) ? true : false;
var hcDOM = (document.getElementById) ? true : false;
if (hcIE)
        hcDOM = false;
var hcMAC = (navigator.platform) && (navigator.platform.toUpperCase().indexOf("MAC") >= 0);
if (hcNS)
        hcMAC = false;

var gl_sx = 0;
var gl_sy = 0;


function onScrollHandler()
{
        var o = document.getElementById ('flcontainer');
        var sx = 0;
        var sy = 0;

    if (hcIE)
    {

        eval('try {' +
               'if (typeof(document.documentElement) != "undefined") {' +
                    'scrollPosY = document.documentElement.scrollTop;' +
                    'scrollPosX = document.documentElement.scrollLeft;' +
                '}' +
            '} catch (e) {}');
        sy = Math.max(document.body.scrollTop, scrollPosY);
        sx = Math.max(document.body.scrollLeft, scrollPosX);

    }
    else if (hcNS)
    {

        sx = pageXOffset;
        sy = pageYOffset;

    }
    else if (hcDOM)
    {

        sx = pageXOffset;
        sy = pageYOffset;
    }


        if (gl_sx != sx || gl_sy != sy)
        {

                if ( parseInt(o.style.top) > pos_hide )
                {

                        o.style.top = sy + foo + "px";
                        o.style.left = sx + "px";

                        gl_sx = sx;
                        gl_sy = sy;

                        foo += food;
                        if (10 == foo) food = -1;
                        if ( 0 == foo) food = 1;
                }
        }

        setTimeout('onScrollHandler()', 250);
}

function IMInvitationShow(bool, pos)
{
        var o = document.getElementById ('flcontainer');
        var sx = 0;
        var sy = 0;

        if (!pos)
        {
                onScrollHandler();
        }

        if (!bool)
        {
                o.style.top = pos_hide + 'px';
                return;
        }

        if (hcIE)
        {

        eval('try {' +
               'if (typeof(document.documentElement) != "undefined") {' +
                    'scrollPosY = document.documentElement.scrollTop;' +
                    'scrollPosX = document.documentElement.scrollLeft;' +
                '}' +
            '} catch (e) {}');
                sy = Math.max(document.body.scrollTop, scrollPosY);
                sx = Math.max(document.body.scrollLeft, scrollPosX);

        }
        else if (hcNS)
        {

                sx = pageXOffset;
                sy = pageYOffset;

        }
        else if (hcDOM)
        {

                sx = pageXOffset;
                sy = pageYOffset;
        }

        o.style.top = String (-imn_height + pos + sy) + 'px';
        o.style.left = sx + 'px';

        pos += step_d;

        if (pos <= imn_height)
        {
                setTimeout('IMInvitationShow(' + bool + ',' + pos + ')', 50);
        }
}

function inviteShow(bShow)
{
  if (bShow)
  {
        var o = document.getElementById ('flcontainer');
        o.style.top = "-200px";
        IMInvitationShow(true, 0);
  }
  else
        IMInvitationShow(false, 0);
}