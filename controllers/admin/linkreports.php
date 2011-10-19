<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Link Reports controller, this enables the AJAXY interactions with 
 *
 * @author	   John Etherton
 * @package	   Link Reports
 */

class Linkreports_Controller extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->template->this_page = 'settings';

		// If this is not a super-user account, redirect to dashboard
		if(!$this->auth->logged_in('admin') && !$this->auth->logged_in('superadmin'))
		{
			url::redirect('admin/dashboard');
		}
	}
	
	public function delete_link($id=false)
	{
	}//end index method
	
	
	public function search()
	{
		//turn off the template stuff
		$this->template = "";
		$this->auto_render = FALSE;
		
		//get the query string		
		$q = strtolower($_GET["q"]);
		if (!$q) return;
		
		//sanitize $q
		$q = mysql_real_escape_string($q);
		
		//create the SQL
		$params = array(" ((i.incident_title LIKE '%".$q. "%') OR (i.incident_description LIKE '%".$q. "%') OR (l.location_name LIKE '%".$q. "%'))");
		$reports = Incident_Model::get_incidents($params);
		
		
		foreach($reports as $report)
		{
			echo $report->incident_title/*." - " . $report->incident_description. " - ".$report->location_name */."|". $report->incident_id."\n";		
		}
	}

	
}