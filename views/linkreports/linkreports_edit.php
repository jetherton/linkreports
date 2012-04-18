	
<script type="text/javascript">

$().ready(function() {
	$("#linkreport").autocomplete("<?php echo url::base();?>linkreports/search", {
		width: 560,
		selectFirst: false,
		cacheLength: 1000,
		max: 100
	});
	
	$("#linkreport").result(function(event, data, formatted) {
		if (data)
			$("#linkreportValue").val(data[1]);
	});
	
});

var linkreportsCount = <?php echo $links_count; ?>;

function addLinkToReport()
{
	linkreportsCount++;
	
	var id = $("#linkreportValue").val();
	var title = $("#linkreport").val();
	if(id == null || id == undefined || id == "")
	{
		return false;
	} 
	var html = '<li style="margin:5px;" id="linkreports_link_'+linkreportsCount+'"><a href="<?php echo url::base();?>admin/reports/edit/' + id + '">'+title+'</a>';
	html += '<input id="linkreportid[]" name="linkreportid[]" value="'+id+'" type="hidden"/>';
	html += '<a href="#" class="new-cat" style="background-image:url(<?php echo url::base();?>media/img/icon-minus.gif)" onclick="removeLinkToReport(\''+linkreportsCount+'\'); return false;"><?php echo Kohana::lang("linkreports.remove");?></a></li>';
	$("#linkreports_links").append(html);

	//reset the inputs
	$("#linkreportValue").val("");
	$("#linkreport").val("");
	return false;
	
}

function removeLinkToReport(id)
{
	$("#linkreports_link_"+id).remove();
}
</script>

<div class="row" id="linkreports_div">
<h4><?php echo Kohana::lang("linkreports.link_to_other_reports")?></h4>
<?php echo Kohana::lang("linkreports.link_to_report")?> <input width="200px" id="linkreport" name="linkreport"/>
<a href="#" id="linkreportsAdd" class="new-cat" onclick="addLinkToReport(); return false;"> <?php echo Kohana::lang("linkreports.add");?></a>
<input id="linkreportValue" name="linkreportvalue" type="hidden"/>
<ul id="linkreports_links">
<?php 
	$i = 0;
	foreach($links as $link)
	{
		$i++;
		$incident = ORM::factory("incident")->where("id", $link->to_incident_id)->find();
		
	echo'<li style="margin:5px;" id="linkreports_link_'.$i.'"><a href="'.url::base().'admin/reports/edit/' . $link->to_incident_id . '">'.$incident->incident_title.'</a>';
	echo '<input id="linkreportid[]" name="linkreportid[]" value="'.$i.'" type="hidden"/>';
	echo '<a href="#" class="new-cat" style="background-image:url('. url::base().'media/img/icon-minus.gif)" onclick="removeLinkToReport(\''.$i.'\'); return false;">'. Kohana::lang("linkreports.remove").'</a></li>';
	}
?>
</ul>
</div>

<?php if(count($links_to) > 0){?>
<div class="row" id="linkreports_to_div">
<h4><?php echo Kohana::lang("linkreports.report_links_to")?></h4>

<ul>
<?php 
	$i = 0;
	foreach($links_to as $link)
	{
		$incident = ORM::factory("incident")
			->where("id", $link->from_incident_id)
			->find();
		echo '<li><a href="'.url::base().'admin/reports/edit/'.$incident->id.'">'.$incident->incident_title.'<a/></li>';
	}
?>
</ul>
</div>
<?php }?>