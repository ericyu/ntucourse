/*
*************************************************************************************
**  Copyright Notice                                                               **
**  ColorPop v1.0 is written by David Clements                                     **
**  Copyright(pending) 2002 David Clements. All Rights Reserved.                   **
**  This script is free to use and alter as much as you like.                      **
**  You may not resell or redistribute this script without permission.             **
**  You may not pass the script off as your own work.                              **
**  You must leave this notice intact.                                             **
**  You may place a link to http://www.barefootc.com/ on your web site.            **
**  If you find any bugs with this script, please e-mail me at dave@barefootc.com  **
**                                                                                 **
** ADDITION: (JS-X 2/2002)                                                         **
**   Script code shortened by allowing for loops to create the HTML code that was  **
**   already in place by the previous existing version.                            **
**   This allows the file to run as is in one file. It uses more memory than the   **
**   original version.  Users are encouraged to try out the original version.      **
*************************************************************************************
*/
function _f(_a)
{
  var _o='';
  for(var i=0;i<_a.length;i++)
  {
    _o+='<td width="14" height="14" bgcolor="'+_a[i]+'">';
    _o+='<a href="#" onclick="window.opener.doIt(\''+_a[i]+'\');window.close()">';
    _o+='<IMG SRC="http://www.js-x.com/images/shim.gif" width="14" height="14" border="0" alt="'+_a[i]+'"></a></td>';
  }
  return(_o);
}

var _o='';
_o+='<HTML><HEAD><TITLE>Color Chart</TITLE><sc'+'ript>function loadfocus(){refButton = opener.document.Form.fieldName.value;window.focus()}</scr'+'ipt></HEAD>';
_o+='<body onload="loadfocus()" onblur="window.focus()" bgcolor="#334455" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">';
_o+='<table border="1" bgcolor="#888888" cellpadding="0" cellspacing="0">';
_o+='<!-- RED --><tr>';
var _a$=new Array("#ffeeee","#ffcccc","#ffaaaa","#ff8888","#ff6666","#ff4444","#ff2222","#ff0000","#ee0000","#cc0000","#aa0000","#880000","#770000","#660000","#550000","#440000","#330000");
_o+=_f(_a$);
_o+='</tr><!-- GREEN --><tr>';
var _b$=new Array("#eeffee","#ccffcc","#aaffaa","#88ff88","#66ff66","#44ff44","#22ff22","#00ff00","#00ee00","#00cc00","#00aa00","#008800","#007700","#006600","#005500","#004400","#003300");
_o+=_f(_b$);
_o+='</tr><!-- BLUE --><tr>';
var _c$=new Array("#eeeeff","#ccccff","#aaaaff","#8888ff","#6666ff","#4444ff","#2222ff","#0000ff","#0000ee","#0000cc","#0000aa","#000088","#000077","#000066","#000055","#000044","#000033");
_o+=_f(_c$);
_o+='</tr><!-- YELLOW --><tr>';
var _d$=new Array("#ffffee","#ffffcc","#ffffaa","#ffff88","#ffff66","#ffff44","#ffff22","#ffff00","#eeee00","#cccc00","#aaaa00","#888800","#777700","#666600","#555500","#444400","#333300");
_o+=_f(_d$);
_o+='</tr><!-- PURPLE --><tr>';
var _e$=new Array("#ffeeff","#ffccff","#ffaaff","#ff88ff","#ff66ff","#ff44ff","#ff22ff","#ff00ff","#ee00ee","#cc00cc","#aa00aa","#880088","#770077","#660066","#550055","#440044","#330033");
_o+=_f(_e$);
_o+='</tr><!-- ORANGE --><tr>';
var _f$=new Array("#ffddd0","#ffe0aa","#ffdd88","#ffcc77","#ffbb66","#ffaa55","#ffaa44","#ff9944","#ff8833","#ff7722","#ff6622","#ee5522","#dd4411","#cc3300","#aa2200","#882200","#662200");
_o+=_f(_f$);
_o+='</tr><!-- CYAN --><tr>';
var _g$=new Array("#eeffff","#ccffff","#aaffff","#88ffff","#66ffff","#44ffff","#22ffff","#00ffff","#00eeee","#00cccc","#00aaaa","#008888","#007777","#006666","#005555","#004444","#003333");
_o+=_f(_g$);
_o+='</tr><!-- GRAY --><tr>';
var _h$=new Array("#ffffff","#eeeeee","#dddddd","#cccccc","#bbbbbb","#aaaaaa","#a0a0a0","#999999","#888888","#777777","#666666","#555555","#444444","#333333","#222222","#111111","#000000");
_o+=_f(_h$);
_o+='</tr></table></BODY></HTML>';

var win= null;

function NewWindow(field)
{
// Save the name of the target input field to the hidden input
document.Form.fieldName.value=field

// Opens the Color Chart Popup
win2=window.open('','colorPop','height=130,width=274,top=150,left=100,scrollbars=no,resizable=no');
win2.document.open();
win2.document.write(_o);
win2.document.close();
}

function doIt(color)
{
// Get the selected color and send it to the target input field
eval('document.Form.' + document.Form.fieldName.value + '.value=\'' + color + '\'');

if (document.layers)
	window.document.layers[document.Form.fieldName.value].bgColor = color;
else if (document.all)
	window.document.all[document.Form.fieldName.value].style.background = color;
}
