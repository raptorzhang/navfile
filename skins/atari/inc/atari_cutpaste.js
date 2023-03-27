
function textCut(){
  alert(window.getSelection().getRangeAt(0).toString());
}

function textCopy(){
  xText = getRangeAndCaret();
}

function textPaste(){
  insertCode(code,replaceCursorBefore);
}

function getSelectedText(){
//window.getSelection().getRangeAt(0).toString();
}

function getClipboard(){
	if(window.innerHeight) {
	} else {
		return window.clipboardData.getData('Text');
	}
}

function putClipboard(xText){
	if(window.innerHeight) {
	} else {
		window.clipboardData.setData('Text',xText);
	}
}

function getRangeAndCaret(){
	if(window.innerHeight) {
		var range = window.getSelection().getRangeAt(0);
		var range2 = range.cloneRange();
		var node = range.endContainer;			
		var caret = range.endOffset;
		range2.selectNode(node);	
		return [range2.toString(),caret];
	} else {
		var range = document.data.selection.createRange();
		var caret = Math.abs(range.moveStart('character', -1000000)+1);
		return [range.toString(),caret];
	}
}

function insertCode(code,replaceCursorBefore){
	if(window.innerHeight) {
		var range = window.getSelection().getRangeAt(0);
		var node = window.document.createTextNode(code);
		var selct = window.getSelection();
		var range2 = range.cloneRange();
		// Insert text at cursor position
		selct.removeAllRanges();
		range.deleteContents();
		range.insertNode(node);
		// Move the cursor to the end of text
		range2.selectNode(node);		
		range2.collapse(replaceCursorBefore);
		selct.removeAllRanges();
		selct.addRange(range2);
	} else {
		var repdeb = '';
		var repfin = '';
		
		if(replaceCursorBefore) { repfin = code; }
		else { repdeb = code; }
		
		if(typeof document.data.selection != 'undefined') {
			var range = document.data.selection.createRange();
			range.text = repdeb + repfin;
			range = document.data.selection.createRange();
			range.move('character', -repfin.length);
			range.select();	
		}	
	}
}

