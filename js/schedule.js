//Pop up information box II (Mike McGrath (mike_mcgrath@lineone.net,  http://website.lineone.net/~mike_mcgrath))
//Permission granted to Dynamicdrive.com to include script in archive
//For this and 100's more DHTML scripts, visit http://dynamicdrive.com

Xoffset= 20;    // modify these values to ...
Yoffset= -20;    // change the popup position.

topColor = "#6699FF";
subColor = "#DDDDDD";

var old,skn,iex=(document.all),yyy=-1000;

var ns4=document.layers;
var ns6=document.getElementById && !document.all;
var ie4=document.all;

document.write('<div id="dek"></div>');

if (ns4)
	skn=document.dek;
else if (ns6)
	skn=document.getElementById("dek").style;
else if (ie4)
	skn=document.all.dek.style;
	if(ns4)
		document.captureEvents(Event.MOUSEMOVE);
else {
	skn.visibility = "visible";
	skn.display = "none";
}
document.onmousemove = get_mouse;

function popup(TTitle, TContent) {
	content = '<table border="0" width="200" cellspacing="0" cellpadding="0">'+
	'<tr><td bgcolor='+topColor+'>'+
	'<font class="tooltiptitle">&nbsp;'+TTitle+'</font>'+
	'<tr><td bgcolor='+subColor+'>'+
	'<font class="tooltipcontent">'+TContent+'</font>'+
	'</table>';
	yyy = Yoffset;
	if(ns4) {skn.document.write(content);skn.document.close();skn.visibility="visible"}
	if(ns6) {document.getElementById("dek").innerHTML=content;skn.display=''}
	if(ie4) {document.all("dek").innerHTML=content;skn.display=''}
}

function get_mouse(e) {
if(ns4||ns6) {
	x = e.pageX;
	y = e.pageY;
} else if(document.documentElement && document.documentElement.scrollTop) {
	x = event.x + document.documentElement.scrollLeft;
	y = event.y + document.documentElement.scrollTop;
} else if(document.body) {
}

skn.left = x+Xoffset+"px";
skn.top = y+yyy+"px";
}

function kill() {
	yyy=-1000;
	if(ns4)
		skn.visibility="hidden";
	else if (ns6||ie4)
		skn.display="none";
}
