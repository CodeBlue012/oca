<?php
require_once('product_model.php');   
class customizer{

  public function getPopupsCustomizer($popups){
	  
	  #original code
/*
    foreach($popups as $popup){ 
      //for other type we set view on stats ajax call   
      if(($popup['background_color']==3 || $popup['background_color']==4) && $popup['show_when']==1){
        if(!$this->helper->getIsCrawler()){       
            $this->setPopupData($popup['popup_id'],'views',$popup['views']+1); 
        }
      }     
    }
*/


	#after hours custom code

	foreach($popups as $key => $popup){

		if($popup['popup_id']=='5' && date("H")>17){
			unset($popup[$key]);
			} 
		
		if($popup['popup_id']=='6' && date("H")<17){
			unset($popup[$key]);
			} 
			
		$isWeekend = date('N') >= 6 ? true : false;
		if($popup['popup_id']=='7' && !$isWeekend){
			unset($popup[$key]);
			}
			
		if($popup['popup_id']=='8' && $isWeekend){
			unset($popup[$key]);
			}

		//for other type we set view on stats ajax call 
		if(($popup['background_color']==3 || $popup['background_color']==4) && $popup['show_when']==1){
			if(!$this->helper->getIsCrawler()){ 
				$this->setPopupData($popup['popup_id'],'views',$popup['views']+1); 
				}
			} 
		}

    return $popups;
  }
  
}  