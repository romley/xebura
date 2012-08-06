<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Schedule Campaign - Xebura.com</title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 7]>
<link href="css/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
{literal}
<style type="text/css" title="currentStyle">
@import "datatable/media/css/demo_page.css";
 @import "datatable/media/css/box_report_main.css";
</style>
<script type="text/javascript" language="javascript" src="datatable/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="datatable/media/js/jquery.dataTables.js"></script>
<script  type="text/javascript" src="datatable/media/js/dataTables.Currency.js"></script>
<script  type="text/javascript" src="datatable/media/js/dataTables.formatNumber.js"></script>
<script  type="text/javascript" src="datatable/media/js/dataTables.Percent.js"></script>
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.li-scroller.1.0.js'></script>
<link rel="stylesheet" type="text/css" href="css/li-scroller.css" />
<script type="text/javascript" src="colorbox/colorbox/jquery.colorbox.js"></script>
<link type="text/css" media="screen" rel="stylesheet" href="colorbox/colorbox/colorbox2.css" />
<script src="js/cufon-yui.js" type="text/javascript"></script>
<script src="js/TourIntelPro.font.js" type="text/javascript"></script>
<script type="text/javascript" src="jquery-ui/js/jquery-ui-1.8.9.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="jquery-ui/css/ui-lightness/jquery-ui-1.8.9.custom.css"/>
<script type="text/javascript" src="jquery-ui/js/jquery.ui.timepicker-0.0.7.js"></script>
<link rel="stylesheet" type="text/css" href="jquery-ui/css/jquery-ui-timepicker.css"/>
<style>
.dataTables_length {
	top: -28px;
	left:275px;
}
.dataTables_filter {
	top: -28px;
}
</style>


<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				bTable = $('#boxoffice').dataTable( {
					"bProcessing": true,
					"sAjaxSource": 'campaign_chart?list=all',
					"aaSorting": [[ 3, "desc" ]],
					"bPaginate": true,
					"bLengthChange": true,
					"bFilter": true,
					"aoColumns": [
								{ "bSearchable": false, "bVisible": false },
								null,
								null,
								null,
								null,
								],
					"fnInitComplete": function ( bSettings ) {
					$(bTable.fnGetNodes()).click( function () {
					var iPos = bTable.fnGetPosition( this );
					var aData = bSettings.aoData[ iPos ]._aData;
					/*window.open('artist_sub_scores?artist={/literal}{$artistid}&start={$startdate}&end={$enddate}{literal}&city='+aData[0],'cityreport','width=1020,height=700,resizable=0');*/
					/*$("embed").attr("wmode", "opaque").wrap('<div>');*/
					$.fn.colorbox({iframe:false,initialWidth:75,initialHeight:75,href:"edit_campaign?i="+aData[0]});
					} );
					}

				} );
			} );
		</script>
<script type="text/javascript">
/*		$('#chart_loader').insertBefore('#visualization');
*/</script>
<script>
   if ($.browser.msie) {
/*    alert("Internet Explorer is not a fully supported browser. For the best experience, please use a modern browser such as Firefox, Safari or Chrome.");
*/
$("<div class='nocufon browser_warning'>Internet Explorer is not fully supported. Please use a modern browser with HTML5 support such as <a class='nocufon' href='http://www.mozilla.com' target='_new'>Firefox</a>, <a class='nocufon' href='http://www.apple.com/safari/' target='_new'>Safari</a> or <a class='nocufon' href='http://www.google.com/chrome/' target='_new'>Chrome.</a></div>").appendTo(document.body);
}
 
 </script>
 
 
<script type="text/javascript">

$(function(){
  $('#hide_topmsg').click(function(){
     $('.top_msg').slideToggle(); 
	 $.get("hidemsg");
  });
});
</script>


<script type="text/javascript">

$(function(){
		   $('#addcontact').click(function(){
		   $.fn.colorbox({iframe:false,initialWidth:75,initialHeight:75,href:"add_contact"});
		   });
		   });
</script>
<script>
	$(function() {
		$( "#launchdate" ).datepicker({ dateFormat: 'yy-mm-dd' });;
	});
	
	
	$(function() {
	$('#launchtime').timepicker({
    timeSeparator: ':',           // The caracter to use to separate hours and minutes. (default: ':')
    showLeadingZero: true,        // Define whether or not to show a leading zero for hours < 10. (default: true)
    showMinutesLeadingZero: true, // Define whether or not to show a leading zero for minutes < 10. (default: true)
    showPeriod: false,            // Define whether or not to show AM/PM with selected time. (default: false)
    hourText: 'Hour',             // Define the locale text for "Hours"
    minuteText: 'Minute',         // Define the locale text for "Minute"
    amPmText: ['AM', 'PM'],       // Define the locale text for periods
});
	});
	
	
	</script>


{/literal}
</head>
<body>


<div id="wrapper">
{include file="_thead.tpl"}
<div id="top_bar">
  <div class="li" id="report_type"><strong><!--{$random_quote}--></strong></div>
  <div class="li" id="poweredby_top">Account: Xebura Testing</div>
</div>
<!-- home content here -->

<!-- NEW HOME INSTRUCTIONS -->
{if $show_msg eq 0}
<div class="top_msg nocufon">
<p><strong>Howdy {$firstname}, this is your new TourIntel homepage.<br /><br />Click on the TourIntel logo above from anywhere inside the app to return back home.</strong></p>
<p>All of the information on this page is updated in real-time as we receive new data.</p> 
<p>If you would like to view the profile of any artist in the Recent Box Office Reports ticker, point your cursor at the artist name and click.</p>
<p>You can view any Artist profile for the current Top 20 tours by clicking on the name of the artist, the graph, or their current rank.</p>
<p>Moving your mouse over the circular data points on the TI Concert Attendance Index graph will display the details about that point on the graph. This graph does not allow you to drill down to the underlying data - clicking on it will not show any additional details.</p>
<p>Clicking on an Artist name under Popular on TourIntel or Recently Viewed will take you to view the TI profile for that act.</p>
<p>Last, if you’d like details on any of the Top 50 Scores, click anywhere on the box office report row for the underlying box office report and links to the Artist and Venue profiles related to the report.</p>
<p>If you enjoy TourIntel.com, please help evangelize our service to your colleagues in the industry!</p>
<span id="hide_topmsg" style="cursor:pointer;">Click here to hide this message</span>
</div>
{/if}


<div id="top-scores">
<h2>Schedule Campaign Launch</h2>
  <form id="schedule_campaign" name="schedule_campaign" method="post" action="">
    <p>&nbsp;</p>
    <p>
      <label>Launch Date
        <input type="text" name="launchdate" id="launchdate" />
      </label>
    </p>
    <p>
      <label>Launch Time
        <input type="text" name="launchtime" id="launchtime" />
      </label>
    </p>
    <p>&nbsp;</p>
    <p>
      
      <input type="hidden" name="cid" value="{$cid}" />
      
      
      <input type="hidden" name="action" value="{if $action eq 'update'}update{else}create{/if}"/>
      <input type="submit" name="submit" id="submit" value="Submit" />
    </p>
  </form>
</div>
<!-- end home content -->
