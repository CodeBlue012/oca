
 /* google analytics */
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-1030312-14', 'onecallmedicalalert.com');
  
  // Optimizely Universal Analytics Integration
 window.optimizely = window.optimizely || [];
 window.optimizely.push("activateUniversalAnalytics");
  
  ga('send', 'pageview');  
 /* end google analytics */
 
/* phone number script */
/*
var thisref = document.referrer.replace(/&/g, "zzzzz");
var ciJsHost = (("https:" == document.location.protocol) ? "https://" : "http://");
document.write(unescape("%3Cscript src='" + ciJsHost + "tracking.callmeasurement.com/clickx/click_matrix.cfm?munique=" + new Date().getTime() + "&prev=" + thisref + "' type='text/javascript'%3E%3C/script%3E"));
*/
/* end phone number script */


/* adroll */
adroll_adv_id = "ZBOS7323XNHRNJ32V3J27Y"; 
adroll_pix_id = "RMGZVKYJQBE5XMNZQGIUXW"; 
(function () { 
var oldonload = window.onload; 
window.onload = function(){ 
   __adroll_loaded=true; 
   var scr = document.createElement("script"); 
   var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com"); 
   scr.setAttribute('async', 'true'); 
   scr.type = "text/javascript"; 
   scr.src = host + "/j/roundtrip.js"; 
   ((document.getElementsByTagName('head') || [null])[0] || 
    document.getElementsByTagName('script')[0].parentNode).appendChild(scr); 
   if(oldonload){oldonload()}}; 
}()); 
/* end adroll */