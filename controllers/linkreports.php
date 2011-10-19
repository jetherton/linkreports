<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Link Reports controller, this enables the AJAXY interactions with 
 *
 * @author	   John Etherton
 * @package	   Link Reports
 */

class Linkreports_Controller extends Template_Controller
{
   public $auto_render = FALSE;

    // Main template
    public $template = '';
    
  	public function __construct()
    {
        parent::__construct();    
    }
    
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