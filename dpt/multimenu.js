activateMenu = function(nav) {

    /* currentStyle restricts the Javascript to IE only */
	if (document.all && document.getElementById(nav).currentStyle) {  
        var navroot = document.getElementById(nav);
        
        /* Get all the list items within the menu */
        var lis=navroot.getElementsByTagName("LI");  
        for (i=0; i<lis.length; i++) {
        
           /* If the LI has another menu level */
            if(lis[i].lastChild.tagName=="UL"){
            
                /* assign the function to the LI */
             	lis[i].onmouseover=function() {	
                
                   /* display the inner menu */
                   this.lastChild.style.display="block";
                }
                lis[i].onmouseout=function() {                       
                   this.lastChild.style.display="none";
                }
            }
        }
    }
}
window.onload= function(){
    /* pass the function the id of the top level UL */
    activateMenu('vertnav'); 
}
