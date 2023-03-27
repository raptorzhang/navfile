// initialize object names (id) as rooted objects
// therefore this script MUST be inserted at the very BOTTOM of the page

// 									-= Initialize =-
ObjectList = '';
chkObjects = new Array('a','body','div','font','form','input','span','table','td','textarea');

for(j=0;j<chkObjects.length;j++){
  xObjs = document.getElementsByTagName(chkObjects[j]);
  for(i=0;i<xObjs.length;i++){
    if(xObjs[i].id) { eval(xObjs[i].id+'=document.getElementById("'+xObjs[i].id+'");'); ObjectList = ObjectList + xObjs[i].id + '\\n'; }
  }
}
xObjs = null;
