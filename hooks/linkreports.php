<?php defined('SYSPATH') or die('No direct script access.');
/**
 * File Upload - sets up the hooks
 *
 * @author	   John Etherton
 * @package	   File Upload
 */

class linkreports {
	
	/**
	 * Registers the main event add method
	 */
	 
	 
	public function __construct()
	{
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
		$this->post_data = null; //initialize this for later use
		
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		//check to see when the user is requesting reports/submit or admin/reports/edit
		if (Router::$controller == 'reports')
		{
			switch (Router::$method)
	        {
	        	case 'edit':
	        		Event::add('ushahidi_action.report_form_admin', array($this, '_edit'));
	        		plugin::add_javascript("linkreports/js/jquery.autocomplete.pack.js");
	        		plugin::add_stylesheet("linkreports/css/jquery.autocomplete");	

	        		Event::add('ushahidi_action.report_submit_admin', array($this,'_save_post_data'));
	        		Event::add('ushahidi_action.report_edit', array($this, '_save_links'));
	        		

	        	case 'view':
	        		Event::add('ushahidi_action.report_extra', array($this, '_view'));	        			        		
	        	break;    	
	        }
		}
	}
	
	
	
	/**
	 * Save the links that are in the post data
	 * with the incident that just got passed in
	 * delete any links that don't exist anymore
	 */
	public function _save_links()
	{
		$incident = Event::$data;
		
		$incident_id = $incident->id;
	
		
		//blow away everything that used to be there
		ORM::factory("linkreports")
			->where("from_incident_id", $incident_id)
			->delete_all();
			
		if(!isset($this->post_data['linkreportid']))
		{
			return;
		}
		
		$num_links = $this->post_data['linkreportid'];
		foreach($this->post_data['linkreportid'] as $to_id)
		{
			$link = ORM::factory("linkreports");
			$link->from_incident_id = $incident_id;
			$link->to_incident_id = $to_id;
			$link->save();
		}
	}
	
	
	/**
	 * Cause we need the post data
	 */
	public function _save_post_data()
	{
		$this->post_data = Event::$data;
	}
	
	/**
	 * Display the edit UI to the user, and any prexisting links
	 */
	public function _edit()
	{		
		$id = Event::$data;
		//get the prexisting links
		if($id)
		{
			$links = ORM::factory("linkreports")
				->where("from_incident_id", $id)
				->find_all();
		}
		else
		{
			$links = array();
		}
		
		if($id)
		{
			$links_to = ORM::factory("linkreports")
				->where("to_incident_id", $id)
				->find_all();
		}
		else
		{
			$links_to = array();
		}
		
		$view = View_Core::factory('linkreports/linkreports_edit');
		$view->links = $links;
		$view->links_count = count($links) + 1;
		$view->links_to = $links_to;
		$view->render(TRUE);
	}
	
	/**
	 * Display to the publi user any links that exist
	 * Enter description here ...
	 */
	public function _view()
	{
		$id = Event::$data;
		//get the prexisting links
		if($id)
		{
			$links_from = ORM::factory("linkreports")
				->where("from_incident_id", $id)
				->find_all();
		}
		else
		{
			$links_from = array();
		}
		
		if($id)
		{
			$links_to = ORM::factory("linkreports")
				->where("to_incident_id", $id)
				->find_all();
		}
		else
		{
			$links_to = array();
		}
		
		$view = View_Core::factory('linkreports/linkreports_view');
		$view->links_to_count = count($links_to);		
		$view->links_to = $links_to;
		$view->links_from_count = count($links_from);
		$view->links_from = $links_from;
		$view->render(TRUE);
	}
	
}

new linkreports;
