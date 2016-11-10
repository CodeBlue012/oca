	
	
	
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
		thisURL = $(location).attr('href');
		thisURLarr = thisURL.split("/");
		thisURLcount = thisURLarr.length;
		findlastelement = thisURLarr[thisURLcount-1]+".";
		findlastelementarr = findlastelement.split(".");
		lastelement = findlastelementarr[0];
		currentpage = lastelement.replace('-css','');
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


$(window).load(function(){

	$('#myModal0').on('show.bs.modal', function () {
		$('#myModal0 div.modal-body').html('<iframe src="//www.youtube.com/embed/rmMBsltM13M" width="100%" height="281" frameborder="0" allowfullscreen=""></iframe>');  
		});
	$('#myModal0').on('hide.bs.modal', function () {
			$('#myModal0 div.modal-body').html('');  
		});
		
		
		
		$('#myModalrescube').on('show.bs.modal', function () {
		$('#myModalrescube div.modal-body').html('<iframe width="560" height="315" src="https://www.youtube.com/embed/NMO-ytbNls8" frameborder="0" allowfullscreen></iframe>');  
		});
	$('#myModalrescube').on('hide.bs.modal', function () {
			$('#myModalrescube div.modal-body').html('');  
		});

	navid = getcurrentpage();
	if (navid == "faqs-system") { navid = "faqs"; }
	navid = "#id_"+navid;
	$(navid).addClass('active');
	
	

	});

  $(document).ready(function() {
     $('.js-activated').dropdownHover().dropdown();
      //fix to make carousel auto rotate
      $('.carousel').carousel();
    });
    
function openassistant(whichaccount) {
	
		window.open("http://server.iad.liveperson.net/hc/"+whichaccount+"/?cmd=file&file=visitorWantsToChat&site="+whichaccount+"&byhref=1","assistant","width=480,height=458,toolbar=false,status=false")
	
		}
		
	//automatically adding GA to links,spans,divs
$(document).ready(function() { 
$('.gaclick').click(function() { 
   whichid = this.id; 
   whichpathname = $(location).attr('pathname'); 
   if (whichpathname == "/") { whichpathname = "home"; }
   //window.alert("data "+whichpathname+" "+whichid);
   _gaq.push(['_trackEvent', whichpathname, 'Click', whichid]); 
}); 
});

