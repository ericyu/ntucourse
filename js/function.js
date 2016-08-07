// safety
if (!document.getElementById)
	document.getElementById = function() { return null; }

function checkAll(val) {
	var day = new Array("1", "2", "3", "4", "5", "6");
	var classname = new Array("0","1","2","3","4","5","6","7","8","9","X","A","B","C","D");
	for(var i=0; i<day.length; ++i) {
		for(var j=0; j<classname.length; ++j) {
			var x = "class["+ day[i]+classname[j] + "]";
			document.ThisForm.elements[x].checked = val;
		}
	}
	var elts = document.ThisForm.elements["cls_sel"];
    for (var k = 0; k < elts.length; k++)
        elts[k].checked = val;

	elts = document.ThisForm.elements["day_sel"];
	for (k = 0; k < elts.length; k++)
		elts[k].checked = val;

	for(var i=0; i<day.length; ++i) {
		var y = "c" + day[i];
		document.ThisForm.elements[y+"a"].checked = val;
		document.ThisForm.elements[y+"p"].checked = val;
	}
}

function checkPart(day, sec, val) {
	if(sec == 0)
		var classname = new Array("0","1","2","3","4");
	else if(sec == 1)
		var classname = new Array("6","7","8","9","X");
		for(var j=0; j<classname.length; ++j) {
			var x = "class["+ day + classname[j] + "]";
			document.ThisForm.elements[x].checked = val;
		}
}

function checkDay(d, val) {
	var classname = new Array("0","1","2","3","4","5","6","7","8","9","X","A","B","C","D");
	for(var i=0; i<classname.length; ++i) {
		var x = "class["+ d + classname[i] + "]";
		var y = "c" + d;
		document.ThisForm.elements[x].checked = val;
		document.ThisForm.elements[y+"a"].checked = val;
		document.ThisForm.elements[y+"p"].checked = val;
	}
}

function checkClass(c, val) {
		for(var i=1; i<=6; ++i) {
			var x = "class["+ i + c + "]";
			document.ThisForm.elements[x].checked = val;
		}
}

function ClearAll() {
	checkAll(0);
	var F = document.ThisForm;
	var field = new Array("dpt_choice","cou_cname","not_cou_cname",
		"tea_cname","year","not_year","credit","clsrom","not_clsrom",
		"mark","not_mark");
	var check = new Array("grep","ge_sel[1]","ge_sel[2]","ge_sel[3]","ge_sel[4]",
		"no_multi_ge","no_void_time","no_void_serial","csv","night","no_cancelled");
	var radio = new Array("radio_cou_cname","radio_tea_cname","radio_year",
		"radio_clsrom","radio_mark");
	for(i=0; i<field.length; i++) { F[field[i]].value=""; }
	for(i=0; i<check.length; i++) { F[check[i]].checked=0; }
	for(i=0; i<radio.length; i++) { F[radio[i]][1].checked=true; }
	F.start.value="1";
	F.number.value="100";
	F.interval.value="";
	F.elective.value="";
	F.sortby.value="";
	F.order.value="";
}

function example() {
	var F=document.ThisForm;
	F.dpt_choice.value="7M,9,T010";
	F.number.value=100;
	F.cou_cname.value="財務,程式";
	F.not_cou_cname.value="論文";
	F.year.value="1,2,3,4,碩士";
	F.not_year.value="博士";
	F.interval.value="half";
}

function setCheckboxes(FormName, do_check) {
	var elts = document.forms[FormName].elements['selected_cou[]'];
	var elts_cnt = elts.length;

	if(typeof(elts_cnt) == "undefined")
		elts.checked = do_check;

    for (var i = 0; i < elts_cnt; i++) {
        elts[i].checked = do_check;
    }
}

function FillClasses() {
	var pWnd = window.open("FillClasses.php","SelectWindow","width=200,height=200,status=yes,resizable=yes,scrollbars=yes");
	if ((document.window != null) && (!pWnd.opener))
		pWnd.opener = document.window;
}

function toggleVis(btn){
	// Taken from http://www.fiendish.demon.co.uk/html/hidetablecols.html

// Set the default "show" mode to that specified by W3C DOM
// compliant browsers

var showMode = 'table-cell';

// However, IE5 at least does not render table cells correctly
// using the style 'table-cell', but does when the style 'block'
// is used, so handle this

if (document.all) showMode='block';

// This is the function that actually does the manipulation

	// First isolate the checkbox by name using the
	// name of the form and the name of the checkbox

	btn   = document.forms['tcol'].elements[btn];

	// Next find all the table cells by using the DOM function
	// getElementsByName passing in the constructed name of
	// the cells, derived from the checkbox name

	cells = document.getElementsByName('t'+btn.name);

	// Once the cells and checkbox object has been retrieved
	// the show hide choice is simply whether the checkbox is
	// checked or clear

	mode = btn.checked ? showMode : 'none';

	// Apply the style to the CSS display property for the cells

	for(j = 0; j < cells.length; j++)
		cells[j].style.display = mode;
}

// piaip: menu
var modeSideMenu = "1";
var bodyTextMarginLeft = 0;

function initTreeMenu(menuId, actuatorId) {
    var menu = document.getElementById(menuId);
    var actuator = document.getElementById(actuatorId);

    if (menu == null || actuator == null) return;

    //if (window.opera) return; // I'm too tired

    actuator.parentNode.style.listStyleImage = "url(css/plus.gif)";
    actuator.onclick = function() {
        var display = menu.style.display;
        this.parentNode.style.listStyleImage =
            (display == "block") ? "url(css/plus.gif)" : "url(css/minus.gif)";
        menu.style.listStyleImage = "url(css/square.gif)";
        menu.style.display = (display == "block") ? "none" : "block";

        return false;
    }
}

function switchOptions() {
	var opt = document.getElementById("optiontable");
	var lnk = document.getElementById("switchOptLink");
	var display = opt.style.display;
	opt.style.display = (display == "" ? "none" : "");
	lnk.innerHTML = (display == "" ?
		'<img src="images/triangle.gif" height="11" width="11">顯示搜尋選項' :
		'<img src="images/opentriangle.gif" height="11" width="11">隱藏搜尋選項');
}

function eventTrigger (e) {
	if (!e)
		e = event;
	return e.target || e.srcElement;
}

function setCheck(e, name) {
	var obj = document.getElementById(name);
	if(eventTrigger(e).tagName != 'INPUT')
		obj.checked = !obj.checked;
}

function updateGECheckboxes() {
	var table = document.getElementById("table");
	var year = parseInt(table.options[table.selectedIndex].value.split('_')[0]);
	var GE;
	if(year <= 95)
		GE = new Array('人文學','社會科學','物質科學','生命科學');
	else
		GE = new Array('文學與藝術', '歷史思維', '世界文明', '哲學與道德思考', '公民意識與社會分析', '量化分析與數學素養', '物質科學', '生命科學');

	if(GE.length == $("#geContainer > :checkbox").size())
		return;

	var container = document.getElementById("geContainer");
	container.innerHTML = '';
	for(var i = 0; i < GE.length; i++) {
		var cb = document.createElement("input");
		cb.type = "checkbox";
		cb.name = "ge_sel[" + (i+1) + "]";
		cb.id = "ge" + (i+1);
		container.appendChild(cb);
		var label = document.createElement("label");
		label.appendChild(document.createTextNode(GE[i]));
		label.setAttribute("for", "ge" + (i+1));
		container.appendChild(label);
		container.appendChild(label);
		if(i == 3 || i == 5) {
			container.appendChild(document.createElement("br"));
		}
	}
}

