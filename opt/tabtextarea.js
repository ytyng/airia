// tabtextarea.js
// http://hp.vector.co.jp/authors/VA003334/
// 2007/02/04

function lboxTextarea( id, a, b ) {

	this.target = document.getElementById(id);
	this.num = null;
	this.num_row = null;
	if ( a != null ) {
		this.num = a;
		this.num_row = b;
		this.target.setAttribute( "num", this.num );
		this.target.setAttribute( "num_row", this.num_row );
	}

	this.sc = function() {
		var obj1,obj2,obj;
		if ( this.target ) {
			obj = this.target;
			obj1 = document.getElementById( this.num );
			obj2 = document.getElementById( this.num_row );
		}
		else {
			obj = this;
			obj1 = obj.getAttribute( "num" );
			obj1 = document.getElementById( obj1 );
			obj2 = obj.getAttribute( "num_row" );
			obj2 = document.getElementById( obj2 );
		}

		var t_num = obj.value.split(/\n/).length;
		var t_str = "";
		var t_i;
		for ( t_i = 0; t_i < t_num + 1; t_i++ ) {
			t_str = t_str + (t_i+1) + "<br>";
		}

		obj2.innerHTML = t_str;

		obj1.scrollTop = obj.scrollTop;
	}

	if (window.navigator.appName.toLowerCase().indexOf("microsoft") > -1) {
		this.target.onkeydown = SetTab;
	}
	else {
		this.target.onkeypress = SetTab2;
	}

	if ( a != null ) {
		this.target.onscroll = this.sc;
		if (window.navigator.appName.toLowerCase().indexOf("microsoft") > -1) {
			this.target.onkeyup = this.sc;
		}
		else {
			this.target.onmousemove = this.sc;
			this.target.onkeyup = this.sc;
		}
	}
}


// ******************************************************
// tab for textarea ( for IE )
// ******************************************************
function SetTab( ) {

	if (window.navigator.appName.toLowerCase().indexOf("microsoft") > -1) {

		if ( window.event.keyCode != 9 ) {
			return;
		}

		window.event.returnValue = false;

		var objTextArea,objTextRange,nLen,nChars,strData

		objTextArea = document.selection
		objTextRange = objTextArea.createRange( )

		try {
			strData = objTextRange.text
			nLen = strData.length
			if ( window.event.shiftKey ) {
				strData = strData.replace( /\n\t/g, "\n" );
				if ( strData.substr( 0, 1 ) == "\t" ) {
					strData = strData.substr( 1, strData.length-1 );
				}
			}
			else {
				strData = strData.replace( /\n/g, "\n\t" );
				strData = "\t" + strData
				strData = strData.replace( /\t\r\n/g, "\r\n" );
			}
			if ( nLen == 0 ) {
				objTextRange.text = strData;
			}
			else {
				objTextRange.text = strData + "\n";
			}
		}
		catch( e ) {
		}
	}
	else {
	}

} 

// ******************************************************
// tab for textarea
// Firefox is ok, Opera needs name attribute
// ******************************************************
function SetTab2( evt ) {

	if (window.navigator.appName.toLowerCase().indexOf("microsoft") > -1) {

	}
	else {
		var obj = evt.target;
		if ( evt.keyCode != 9 ) {
			return;
		}
		
		st = obj.scrollTop; //Yanagi
		
		nStart = obj.selectionStart;
		nEnd = obj.selectionEnd;

		if ( nStart == nEnd ) {
			strValue = obj.value.substring(	0, nStart );
			strValue += "\t";
			strValue += obj.value.substring(nEnd,obj.value.length);
			obj.value = strValue;
			obj.setSelectionRange(nStart+1,nStart+1);
		}
		else {
			if ( evt.shiftKey ) {
				strValue = obj.value.substring(	nStart, nEnd );
				strValue = strValue.replace( /\n\t/g, "\n" );
				if ( strValue.substr( 0, 1 ) == "\t" ) {
					strValue = strValue.substr( 1, strValue.length-1 );
				}
				nLen = strValue.length
				strValue = obj.value.substring(	0, nStart )
					+ strValue + obj.value.substring(nEnd,obj.value.length);
				obj.value = strValue;
				obj.setSelectionRange(nStart,nStart+nLen);
			}
			else {
				strValue = obj.value.substring(	nStart, nEnd );
				strValue = strValue.replace( /\n/g, "\n\t" );
				if ( strValue.substr( strValue.length-1, 1 ) == "\t" ) {
					strValue = "\t" + strValue.substr( 0, strValue.length-2 ) + "\n";
				}
				else {
					strValue = "\t" + strValue;
				}
				strValue = strValue.replace( /\t\n/g, "\n" );

				nLen = strValue.length
				strValue = obj.value.substring(	0, nStart )
					+ strValue + obj.value.substring(nEnd,obj.value.length);
				obj.value = strValue;
				obj.setSelectionRange(nStart,nStart+nLen);
			}
		}

		evt.preventDefault();

		if (window.navigator.appName.toLowerCase().indexOf("opera") > -1) {
			window.setTimeout(
				"document.getElementsByName(\""+ obj.name + "\")[0].focus();", 10
			);
		}
		
		 obj.scrollTop=st; //Yanagi
	}
}
