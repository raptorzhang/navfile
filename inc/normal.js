/*
#---------------------------
# PHP Navigator 4.0
# dated: 03-8-2006
# Coded by: Cyril Sebastian,
# web: navphp.sourceforge.net
#-----------------------------
# PHP Navigator 4.12.12
# dated: 25-07-2007
# edited: 02-12-2008
# Modified by: Paul Wratt,
# Homeless, Melbourne,Australia
# web: phpnav.isource.net.nz
#-----------------------------*/

function rename(){
  if(fname=="") alert("First select a file by clicking on it");
  else{
    oldname=fname;
    newname=window.prompt("Rename- Enter the new file name:",oldname);
    if(newname && (newname!=oldname) && (newname.indexOf(' ')!=0)){
      oldname=encode(fname);
      newname=encode(newname);
      dir=encode(f.dir.value);
      window.location.href="?action=Rename&file="+oldname+"&change="+newname+"&dir="+dir;
    }
  }
}

function delet(){
  if(fname=="") alert("First select a file by clicking on it");
  else{ msg="";
    if(oldficon.getAttribute("spec").indexOf("d")>0) msg="All files/folder inside this will be deleted!";
    if(confirm("Delete file '"+ fname+"' ?\n"+msg)){
      oldname=encode(fname);
      dir=encode(f.dir.value);
      window.location.href="?action=Delete&file="+oldname+"&dir="+dir;
    }
  }
}

function chmode(i){
  change=eval("f.mode"+i+".value");
  if(fname=="") alert("First select a file by clicking on it");
  else{
    oldname=encode(fname);
    dir=encode(f.dir.value);
    window.location.href="?action=Chmode&change="+change+"&file="+oldname+"&dir="+dir;
  }
}

function copy(){
  if (fname=="") alert("First select a file by clicking on it");
  else{
    sourcedir = f.dir.value;
    destdir = window.prompt("Copy '"+fname+"' to folder:",sourcedir);
    destdir = destdir.replace('\\','/').replace('//','/');
    if(destdir && (destdir!=sourcedir) && (destdir!="./") && (destdir!=".\\") ){
      oldname = encode(fname);
      destdir = encode(destdir);
      dir = encode(f.dir.value);
      window.location.href = "?action=Copy&file="+oldname+"&change="+destdir+"&dir="+dir;
    }
  }
}

function move(){
  if(fname=="") alert("First select a file by clicking on it");
  else{
    sourcedir=f.dir.value;
    destdir=window.prompt("Move '"+fname+"' to folder:",sourcedir);
    destdir = destdir.replace('\\','/').replace('//','/');
    if(destdir && (destdir!=sourcedir) && (destdir!="./") && (destdir!=".\\") ){
      oldname=encode(fname);
      destdir=encode(destdir);
      dir=encode(f.dir.value);
      window.location.href="?action=Move&file="+oldname+"&change="+destdir+"&dir="+dir;
    }
  }
}

function newfolder(){
  newname=escape(window.prompt("Enter the new folder name:","new_folder"));
  if(newname && newname.indexOf('%20')!=0){
    newname=encode(newname);
    dir=encode(f.dir.value);
    window.location.href="?action=New folder&change="+newname+"&dir="+dir;
  }
}

function newfile(){
  newname=escape(window.prompt("Enter the new file name:","new_file"));
  if(newname && newname.indexOf('%20')!=0){
    newname=encode(newname);
    dir=encode(f.dir.value);
    window.location.href="?action=New file&change="+newname+"&dir="+dir;
  }
}

function extract(){
  if(fname=="") alert("First select a file by clicking on it");
  else if(oldficon.getAttribute("spec").indexOf("z")>0){
    if(confirm("Extract all files '"+fname+"' to the current folder?")){
      oldname=encode(fname);
      dir=encode(f.dir.value);
      window.location.href="?action=Extract&file="+oldname+"&dir="+dir;
    }
  }
}


// dummy functions

function getzipinfo(){
}

function getfolderinfo(){
}

function getimageinfo(){
}
