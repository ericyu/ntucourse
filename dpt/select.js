function Init() {
	var old_array = document.ThisForm.dpt_choice.value.split(",");
	var t = document.getElementsByName("check");
	var olen = old_array.length;
	var tlen = t.length;
	for(var i = 0; i < tlen; ++i)
		for (var j = 0; j < olen; ++j)
			if(t[i].value == old_array[j])
				t[i].checked = true;
}

function ClearAllDpt() {
	var t = document.getElementsByName("check");
	for(var i = 0, tlen = t.length; i < tlen; ++i)
		t[i].checked = false;
}

function UpdateFromInput() {
	ClearAllDpt();
	Init();
}

function ClearAllInput() {
	ClearAllDpt();
	var f = document.ThisForm;
	f.dpt_choice.value = "";
	f.under.checked = false;
	f.graduate.checked = false;
	f.program.checked = false;
}

function RemoveFromArray(array, value) {
	var newarray = new Array();
	for (var i = 0; i < array.length; ++i) {
		if(array[i] == value) {
			for(j = i; j < array.length; ++j)
				array[j] = array[j+1];
			array.length = array.length-1;
		}
	}
}

function AddToArray(array, value) {
	var newarray = new Array();
	len = array.length;
	for (var i = 0; i < len; ++i) {
		if(array[i] == value)
			return;
	}
	array[len] = value;
}

function Update(obj) {
	var old = document.ThisForm.dpt_choice.value;
	re = /^ *$/;
	if(!re.exec(old))
		array = old.split(",");
	else
		array = new Array();

	if(obj.checked == true)
		AddToArray(array, obj.value);
	else
		RemoveFromArray(array, obj.value);

	document.ThisForm.dpt_choice.value = array.join(",");
}

function CheckDpt(name, val) {
	var re = new Array();
	re["under"] = /^(([0-9AB])|(0000))$/;
	re["graduate"] = /^([0-9AB])M$/;
	re["program"] = /^P/;
	var t = document.getElementsByName("check");
	for (var i = 0, len = t.length; i < len; ++i) {
		var e = t[i];
		if(re[name].exec(e.value)) {
			e.checked = val;
			Update(e);
		}
	}
}
