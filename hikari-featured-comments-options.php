<?php

global $hkFC_Op;



class HkFC_Op extends HkFC_HkToolsOptions{

	public $debug=false;
	public $optionsName = 'hkFC';
	protected $pluginfile = HkFC_pluginfile;
	protected $optionsDBVersion = 1;
	
	
	public $opStructure = array(

	
	
	);
	
	
	
	

	public function __construct(){
		parent::__construct();
		
		
		
		$this->uninstallArgs = array(
				'name' => $this->optionspageName,
				'plugin_basename' => HkFC_basename,
				'options' => array(
						array(
							'opType' => 'wp_options',
							'itemNames' => array($this->optionsDBName)
						)
					)
			);
		
		
		
	}







}
