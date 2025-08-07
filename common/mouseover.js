//
// Browser Detection
//
IE5 = true;

// Generic Layer Object Functions

// Make an object visible
function showObject(obj) 
{
       obj.visibility = "visible";
}

// Hides an object
function hideObject(obj) 
{
    obj.visibility = "hidden";
}

// Move a layer
function moveTo(obj,xL,yL) 
{
    obj.left = xL;
    obj.top = yL;
}


//
// MouseTip Methods
//

function MouseTip_ShowNew()
{
	this.WriteLayer();
	this.Show();
}

function MouseTip_Hide()
{
	this.snow = 0;
	hideObject(this.over);
}

function MouseTip_Show() 
{
	if (this.snow == 0) 	
	{
		if (this.dir == 2) 
		{ // Center
			moveTo(this.over,this.x+MouseTip.offsetX-(MouseTip.width/2),this.y+MouseTip.offsetY);
		}
		if (this.dir == 1) 
		{ // Right
			moveTo(this.over,this.x+MouseTip.offsetX,this.y+MouseTip.offsetY);
		}
		if (this.dir == 0) 
		{ // Left
			moveTo(this.over,this.x-MouseTip.offsetX-MouseTip.width,this.y+MouseTip.offsetY);
		}
		showObject(this.over);
		this.snow = 1;
	}
}


// Writes to a layer
function MouseTip_WriteLayer() 
{
	document.all["overDiv"].innerHTML = this.txt;
}

function MouseTip(type,dir,x,y)
{
	this.x = x;
	this.y = y;
	this.snow = 0;
	this.dir = dir;
	this.over = null;
	
	this.ShowNew = MouseTip_ShowNew;
	this.Hide = MouseTip_Hide;
	this.Show = MouseTip_Show;
	this.WriteLayer = MouseTip_WriteLayer;

	this.over = document.all["overDiv"].style;

	this.txt = '<TABLE WIDTH=100% BORDER=0 CELLPADDING=1 CELLSPACING=0 BGCOLOR="#0f62a2"><TR><TD>' +
		'<TABLE WIDTH=100% BORDER=0 CELLPADDING=2 CELLSPACING=0 BGCOLOR="#E5E5E5"><TR><TD>' + type + '</TD>' +
		'</TR></TABLE></TD></TR></TABLE><p></p>';
	
}


MouseTip.current = null;
MouseTip.tips = new Array();
MouseTip.offsetX = 10;
MouseTip.offsetY = 10;
MouseTip.divPath = "document.overDiv";


function ShowMouseTip(obj,e,type)
{
		var x = 0;
		var y = 0;
		x=e.clientX+document.body.scrollLeft-280; y=e.clientY+document.body.scrollTop;

		MouseTip.current = new MouseTip(type,1,x,y);
		MouseTip.current.ShowNew();
}

function HideMouseTip()
{
		if (MouseTip.current)
			MouseTip.current.Hide();
}