<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<title>Contact Detail</title>
{literal}
<script type="text/javascript">

$(function(){
  $('#submit').click(function(){
	$.post("create_list", $("#create_list").serialize(),function(result) {$('iframe').colorbox.close(); window.location.reload(); });
	$().find('.Errors').html();

  });
});

$(function(){
$("#name").keydown(function(e){
	if (e.keyCode == 13) {
	$.post("create_list", $("#create_list").serialize(),function(result) {$('iframe').colorbox.close(); window.location.reload(); });
	$().find('.Errors').html();
	}
});
});

</script>
{/literal}
</head>

<body>
<div id="wrapper">

<div class="avail_reports">
<div class="li record_detail_title">Create New List</div>
<div class="li avail_reports_bar"> </div>
 <form id="create_list" name="create_list" method="post" action="">
    <p>
      <label>List Name
        <input type="text" name="name" id="name" value="{$name}" />
      </label>
    </p>
    <p>
    
    <input type="hidden" name="action" value="{if $action eq 'update'}update{else}create{/if}"/>
      <input type="button" name="submit" id="submit" value="Submit" />
    </p>
  </form>
</div>
</body>
</html>