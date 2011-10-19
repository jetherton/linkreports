<?php if($links_from_count > 0) {?>
<div>
<h5 style="font-size:14px;border-top:1px dotted #c0c2b8;"><?php echo Kohana::lang("linkreports.report_links_from")?></h5>
<ul style="margin-left:20px;">
<?php 
	foreach($links_from as $link)
	{
		$incident = ORM::factory("incident")
			->where("id", $link->to_incident_id)
			->find();
		echo '<li><a href="'.url::base().'reports/view/'.$incident->id.'">'.$incident->incident_title.'<a/></li>';
	}
?>
</ul>


</div>
<br/><br/>
<?php }?>


<?php if($links_to_count > 0) {?>
<div>
<h5 style="font-size:14px;border-top:1px dotted #c0c2b8;"><?php echo Kohana::lang("linkreports.report_links_to")?></h5>
<ul style="margin-left:20px;">
<?php 
	foreach($links_to as $link)
	{
		$incident = ORM::factory("incident")
			->where("id", $link->from_incident_id)
			->find();
		echo '<li><a href="'.url::base().'reports/view/'.$incident->id.'">'.$incident->incident_title.'<a/></li>';
	}
?>
</ul>


</div>
<br/><br/>
<?php }?>