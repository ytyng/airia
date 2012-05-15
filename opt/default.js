function delete_confirm(){
	switch(confirm('【ファイル削除】\nファイルは削除されます。\nよろしいですか？')){
	case true:
		document.editorform.mode.value="delete";
		document.editorform.submit();
		break;
	}
}

function new_file(){
	switch(confirm('【新規作成】\n保存していない変更は破棄されます。\nよろしいですか？')){
	case true:
		document.editorform.file.value="";
		document.editorform.contents.value="";
		break;
	}
}


function formOnSubmit(){
	document.editorform.scrollvalue.value=document.editorform.contents.scrollTop;
}

function taScroll(v){
	document.editorform.contents.scrollTop=v;
}

var isBgEditing = false;
var taOldData = "";

function saveTaOldData(){
	taOldData = document.editorform.contents.value;
}

function setBgEditing(){
	if(!isBgEditing){
		if(taOldData != document.editorform.contents.value){
			document.editorform.contents.style.background="#ffffdd";
			document.getElementById("indicator").innerHTML="E";
			isBgEditing = true;
		}
	}
}


function executeShortcut(keyCode){
	if(keyCode == 83){
		formOnSubmit();
		document.editorform.submit();
		return false;
	}
}

function reloadMenu(){
	parent.frame_menu.location.reload();
}

function insertHr(){
	HR="\n"+
		"━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"+
		"■ \n"+
		"───────────────────────────────────────\n"
	document.editorform.contents.value += HR;
	document.editorform.contents.scrollTop=99999;
}

function textAreaResizeAndroid(){
	ta = document.getElementById("ta-contents");
	ta.style.height = "50%";
}
