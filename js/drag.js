function hideMe() {	document.getElementById("theLayer").style.visibility="hidden"; }
function showMe() {	document.getElementById("theLayer").style.visibility="visible"; }

//Generic Drag Script- ? Dynamic Drive (www.dynamicdrive.com)
//For full source code and terms of usage,
//visit http://www.dynamicdrive.com

var ie = document.all;
var ns6 = document.getElementById && !document.all;

var dragapproved = false;
var z,x,y, temp1, temp2;

function move(e) {
	if (dragapproved) {
		z.style.left = (ns6 ? temp1 + e.clientX - x : temp1 + event.clientX - x) + "px";
		z.style.top = (ns6 ? temp2 + e.clientY - y : temp2 + event.clientY - y) + "px";
		return false;
	}
}

function drags(e) {
	if (!ie && !ns6)
		return;
	var firedobj = ns6 ? e.target : event.srcElement;
	var topelement = ns6 ? "HTML" : "BODY";

	if(ie && firedobj.tagName == "HTML")
		return;

	while (firedobj.tagName != topelement && firedobj.className != "drag") {
		firedobj = ns6 ? firedobj.parentNode : firedobj.parentElement;
	}

	if (firedobj.className == "drag") {
		dragapproved = true;
		z = firedobj;
		temp1 = parseInt(z.style.left + 0);
		temp2 = parseInt(z.style.top + 0);
		x = ns6 ? e.clientX : event.clientX;
		y = ns6 ? e.clientY : event.clientY;
		document.onmousemove = move;
		return false;
	}
}

document.onmousedown = drags;
document.onmouseup = new Function("dragapproved=false");
