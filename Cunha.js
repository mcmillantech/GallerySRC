/*
*/
var timeout         = 500;
var closetimer		= 0;
var ddmenuitem      = 0;			// HTML div of currently showing drop down element

// -----------------------------------------------------
//  Toggle between adding and removing the "responsive" 
//  class to topnav when the user clicks on the icon 
//  
// -----------------------------------------------------
function toggleMenu() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
    var el = document.getElementById("myTopnav");
}


function sizeCheck()
{
	swidth = screen.width;
	wid = window.innerWidth;
//	alert (wid);
	if (wid < 1100)
	{
		wid = wid * 0.9;
		el = document.getElementById('container');
		el.style.width = wid+'px';
		var mgn = - (wid/2) + 'px';
		el.style.marginLeft = mgn;
	}
}

function logon(targetPage)
{
    window.location = "logon.php?page=" + targetPage;
}

// ---------------------------------------
// Open a drop down
//
//	Handler for onMouseOver a bar
//
//	Parameter	id of the drop down div
// ---------------------------------------
function mopen(id)
{	

	mcancelclosetime();			//	cancel close timer

								// If a drop down is showing, hide it
	if(ddmenuitem)
		ddmenuitem.style.visibility = 'hidden';

								// Locate the new drop down and show it
	ddmenuitem = document.getElementById(id);
	ddmenuitem.style.visibility = 'visible';

}

// -----------------------------------
//	Close the current pull down div
//
// -----------------------------------
function mclose()
{
	if(ddmenuitem)				// Is a pull down showuing?
		ddmenuitem.style.visibility = 'hidden';
}

// go close timer
function mclosetime()
{
	closetimer = window.setTimeout(mclose, timeout);
}

// cancel close timer
function mcancelclosetime()
{
	if(closetimer)
	{
		window.clearTimeout(closetimer);
		closetimer = null;
	}
}

// -----------------------------------
// 	Close drop down when click-out
//
//	Set the default onClick handler
// -----------------------------------
document.onclick = mclose; 


function colMouseOver(id)
{

	var imid = 'coli' + id;
	var imid2 = imid + 'm';
	var elimg = document.getElementById(imid);
	elimg.style.display = 'none'			// This works
	var eltxt = document.getElementById(imid2);
	eltxt.style.display = 'inline';
	eltxt = document.getElementById('coltx' + id);
	eltxt.style.visibility = 'visible';

}

function colMouseOut(id)
{
	var imid = 'coli' + id;
	var imid2 = imid + 'm';
	var elimg = document.getElementById(imid);
	var eltxt = document.getElementById(imid2);
	eltxt.style.display = 'none'
	elimg.style.display = 'inline';		// This works
	eltxt = document.getElementById('coltx' + id);
	eltxt.style.visibility = 'hidden';
}

function openColl(id)
{
	var str = "collections.php?col=" + id;
	document.location = str;
}

function loadLargeImage(el)
{
	var container = document.getElementById('largeImage');
	var maxWid = container.offsetWidth;
	var picWid = el.width;
	if (picWid < maxWid)
	{
		container.style.marginLeft = (maxWid - picWid) / 2;
	}
	else
	{
		var ratio = maxWid / picWid;
		el.width *= ratio;
	}
}

// -----------------------------------
//	Handler for 'Buy' button
//
// -----------------------------------
function buy(id)
{
	var loc = "order.php?id=" + id;
	document.location = loc;
}

var hAjax;

// ---------------------------------------------
//	Open the Ajax handler
//
// ---------------------------------------------
function openAjax()
{
	if (hAjax != null)
	{
		if (hAjax.readyState == 4)
			return hAjax;

		hAjax.abort();
		return hAjax;
	}
// -------- Create the AJAX handle   -------
	var xmlhttp;
	if (window.XMLHttpRequest)
	{					// code for all but old IE
	  xmlhttp=new XMLHttpRequest();
	}
	else
	{					// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	hAjax = xmlhttp;
	return xmlhttp;
}

// -----------------------------------------------------
//	Check for a valid AJAX response
//
//	Returns true when AJAX is ready and OK
// -----------------------------------------------------
function ajax_response(hAjax)
{
	return(hAjax.readyState==4 && hAjax.status==200);
}

