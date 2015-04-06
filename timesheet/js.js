function toggleLayer(whichLayer, iState) {
	if (document.getElementById){
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		style2.display = iState? "":"none";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.display = iState? "":"none";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.display = iState? "":"none";
	}
}

function toggleLayer2(whichLayer) {
	if (document.getElementById){
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		if(style2.display=="") {
			style2.display = "none";
		} else if(style2.display=="none") {
			style2.display = "";
		}
		//style2.display = iState? "":"none";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		if(style2.display=="") {
			style2.display = "none";
		} else if(style2.display=="none") {
			style2.display = "";
		}
		//style2.display = iState? "":"none";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		if(style2.display=="") {
			style2.display = "none";
		} else if(style2.display=="none") {
			style2.display = "";
		}
		//style2.display = iState? "":"none";
	}
}

function getFormElements(frm) {
	//frm = document.form1
	var getstr = "";
	for (i=0; i<frm.length; i++) {
		if (frm.elements[i].tagName == "INPUT") {
			if (frm.elements[i].type == "text") {
				getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&";
			}
			if (frm.elements[i].type == "hidden") {
				getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&";
			}
			if (frm.elements[i].type == "button") {
				getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&";
			}
			if (frm.elements[i].type == "checkbox") {
				if (frm.elements[i].checked) {
					getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&";
				} else {
					getstr += frm.elements[i].name + "=&";
				}
			}
			if (frm.elements[i].type == "radio") {
				if (frm.elements[i].checked) {
					getstr += frm.elements[i].name + "=" + encodeURIComponent(frm.elements[i].value) + "&";
				}
			}
		}
		if (frm.elements[i].tagName == "SELECT") {
			var sel = frm.elements[i];
			getstr += sel.name + "=" + sel.options[sel.selectedIndex].value + "&";
		}   
	}
	return getstr;
}

function getXmlHttpRequestObject() {
	if (window.XMLHttpRequest) {
		return new XMLHttpRequest();
	} else if(window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		alert('Status: Cound not create XmlHttpRequest Object. Consider upgrading your browser.');
	}
} 

function doAjax(url,method,getStr,postStr,divtag) {
	var Req = getXmlHttpRequestObject();
	//document.getElementById(divtag).innerHTML = "<img src='progress.gif'>";
	if (Req.readyState == 4 || Req.readyState == 0) { 
		if(method=="GET") {
			Req.open("GET", url+"?"+getStr, true); 
		} else if(method=="POST") {
			Req.open("POST", url+"?"+getStr, true); 
			Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		} else {
			Req.open("GET", url+"?"+getStr, true); 
		}
		Req.onreadystatechange = function() {
			if (Req.readyState == 4) { 
				var xmldoc = Req.responseText; 
				document.getElementById(divtag).innerHTML = xmldoc;
			} 
		} 
		if(method=="GET") {
			Req.send(null);  
		} else if(method=="POST") {
			Req.send(postStr); 
		} else {
			Req.send(null); 
		}
	}
}

function GetSelectedItem() {	
	len = document.formAction.cdate.length;
	i = 0;
	chosen = "";
	
	for (i = 0; i < len; i++) {
		if (document.formAction.cdate[i].selected) {
			chosen = chosen + document.formAction.cdate[i].value + "\n";
		}
	}	
	return chosen;
} 
function chooseobject(str) {
	len = document.formAction.cdate.length;
	i = 0;
	chosen = "";
	
	for (i = 0; i < len; i++) {
		if(document.formAction.cdate[i].value==str) {
			document.formAction.cdate[i].selected = true;
		}
	}	
	document.formAction.timetaken.focus();
}

function handleKey(e) 
{
  // get the event
  e = (!e) ? window.event : e;
  // get the code of the character that has been pressed        
  code = (e.charCode) ? e.charCode :
         ((e.keyCode) ? e.keyCode :
         ((e.which) ? e.which : 0));
  // handle the keydown event       
  if (e.type == "keydown") 
  {
    // if enter (code 13) is pressed
    /*
	if(code == 13)
    {
      // send the current message  
      sendMessage();
    }
	*/
	return code;
  }
}
