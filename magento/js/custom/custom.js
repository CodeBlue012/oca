	
	
	
	function randomXToY(minVal,maxVal) {
  		var randVal = minVal+(Math.random()*(maxVal-minVal));
  		return Math.round(randVal);
		}

	function setgooglecustomvar(whichfield) {
		obj = document.getElementById(whichfield);
		if (obj) {
			randomid = randomXToY(10000,99999);
			visitorid = "w"+randomid;
			obj.value = visitorid;
			_gaq.push(['_setCustomVar',1,'VisitorID',visitorid,1]);
			}
		}

	function getcurrentpage() {

		thisURL = $j(location).attr('href');
		thisURLarr = thisURL.split("/");
		thisURLcount = thisURLarr.length;
		findlastelement = thisURLarr[thisURLcount-1]+".";
		findlastelementarr = findlastelement.split(".");
		lastelement = findlastelementarr[0];
		currentpage = lastelement.replace('-css','');

		//currentpage = "";
		return(currentpage);
		}
	currentpage = getcurrentpage();

	if (currentpage == "landing-a" || currentpage == "landing-bX") {
		obj = document.getElementById("id_header");
		obj.style.display = "none";
		obj = document.getElementById("id_footer");
		obj.style.display = "none";
		document.body.style.paddingTop = "0px";
		document.body.style.paddingBottom = "0px";
		setgooglecustomvar("Form_Form_EditableTextField57");
				}
	if (currentpage == "how-to-order") {
		setgooglecustomvar("Form_Form_EditableTextField54");
	}
	
	//window.alert(currentpage);
	
	
	if (currentpage == "reliable-medical-alert-monitoring") {
		
		obj = document.getElementById("id_header");
		obj.style.display = "none";
		obj = document.getElementById("id_footer");
		obj.style.display = "none";

		document.body.style.paddingTop = "0px";
		document.body.style.paddingBottom = "0px";
	}


$j(window).load(function(){

	$j('#myModal0').on('show.bs.modal', function () {
		$j('#myModal0 div.modal-body').html('<iframe src="//www.youtube.com/embed/rmMBsltM13M" width="100%" height="281" frameborder="0" allowfullscreen=""></iframe>');  
		});
	$j('#myModal0').on('hide.bs.modal', function () {
			$j('#myModal0 div.modal-body').html('');  
		});
		
		
		
		$j('#myModalrescube').on('show.bs.modal', function () {
		$j('#myModalrescube div.modal-body').html('<iframe width="560" height="315" src="https://www.youtube.com/embed/NMO-ytbNls8" frameborder="0" allowfullscreen></iframe>');  
		});
	$j('#myModalrescube').on('hide.bs.modal', function () {
			$j('#myModalrescube div.modal-body').html('');  
		});

	navid = getcurrentpage();
	if (navid == "faqs-system") { navid = "faqs"; }
	navid = "#id_"+navid;
	$j(navid).addClass('active');
	
	

	});

  $j(document).ready(function() {
     $j('.js-activated').dropdownHover().dropdown();
      //fix to make carousel auto rotate
      $j('.carousel').carousel();
    });
    
function openassistant(whichaccount) {
	
		window.open("http://server.iad.liveperson.net/hc/"+whichaccount+"/?cmd=file&file=visitorWantsToChat&site="+whichaccount+"&byhref=1","assistant","width=480,height=458,toolbar=false,status=false")
	
		}
		
	//automatically adding GA to links,spans,divs
$j(document).ready(function() { 
$j('.gaclick').click(function() { 
   whichid = this.id; 
   whichpathname = $j(location).attr('pathname'); 
   if (whichpathname == "/") { whichpathname = "home"; }
   //window.alert("data "+whichpathname+" "+whichid);
   _gaq.push(['_trackEvent', whichpathname, 'Click', whichid]); 
}); 
});
//fix dropdowns
(function() {
    var isBootstrapEvent = false;
    if (window.jQuery) {
        var all = jQuery('*');
        jQuery.each(['hide.bs.dropdown', 
            'hide.bs.collapse', 
            'hide.bs.modal', 
            'hide.bs.tooltip',
            'hide.bs.popover'], function(index, eventName) {
            all.on(eventName, function( event ) {
                isBootstrapEvent = true;
            });
        });
    }
    var originalHide = Element.hide;
    Element.addMethods({
        hide: function(element) {
            if(isBootstrapEvent) {
                isBootstrapEvent = false;
                return element;
            }
            return originalHide(element);
        }
    });
})();
