<?php
class LanguageLoader
{
	function initialize() {
		$ci =& get_instance();
		$ci->load->helper('language');
		
		$arrayMap = array("fr"=>"france","es"=>"spain","pt"=>"portugal","de"=>"germany");
		$siteLang = $ci->session->userdata('site_lang');
		if(!empty($_GET['geo']) && in_array($_GET['geo'],array("fr","de","pt","es"))) {
		    $siteLang = $arrayMap[$_GET['geo']];
		    $ci->session->set_userdata('site_lang',$siteLang);
		}
		
		
		
		if (!empty($siteLang)) {
		    
		    if(!empty($arrayMap[$siteLang])) {
		        $siteLang = $arrayMap[$siteLang]; 
		    }
		    
			$ci->lang->load('information',$siteLang);
		} else {
			$ci->lang->load('information','france');
		}
	}
}
?>
