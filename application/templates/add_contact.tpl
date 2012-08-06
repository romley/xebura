<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<title>Contact Detail</title>
{literal}
<script type="text/javascript">

$(function(){
  $('#update_now').click(function(){
	$.post("update_contact", $("#update_contact").serialize(),function(result) {$('iframe').colorbox.close(); window.location.reload(); });
	$().find('.Errors').html();

  });
});

$(function(){
$("#update_contact").keydown(function(e){
	if (e.keyCode == 13) {
	$.post("update_contact", $("#update_contact").serialize(),function(result) {$('iframe').colorbox.close(); window.location.reload(); });
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
<div class="li record_detail_title">Add Contact</div>
<div class="li avail_reports_bar"> </div>
<form method="post" id="update_contact">
<label for="fname">First Name</label><input name="fname" type="text" value="{$fname}" />
<label for="lname">Last Name</label><input name="lname" type="text" value="{$lname}" /><br />
<label for="company">Company</label><input name="company" type="text" value="{$company}" /><br />
<label for="email">Email</label><input name="email" type="text" value="{$email}" /><br />
<label for="phone">Phone</label><input name="phone" type="text" value="{$phone}" /><br />
<label for="street1">Street 1</label><input name="street1" type="text" value="{$street1}" /><br />
<label for="street2">Street 2</label><input name="street2" type="text" value="{$street2}" /><br />
<label for="city">City</label><input name="city" type="text" value="{$city}" />
<label for="state">State</label><input name="state" type="text" value="{$state}" />
<label for="zip">Zip / Postal Code</label><input name="zip" type="text" value="{$zip}" /><br />
<label for="country">Country</label><input name="country" type="text" value="{$country}" /><br />
<label for="status">Subscription Status</label><select name="status">
  <option value="0" {if $status eq 0}selected="selected"{/if}>Single Opt-In</option>
  <option value="1" {if $status eq 1}selected="selected"{/if}>Double Opt-In</option>
  <option value="2" {if $status eq 2}selected="selected"{/if}>Unsubscribed</option>
  <option value="3" {if $status eq 3}selected="selected"{/if}>Bounced</option>
</select><br />

<input type="hidden" name="gid" value="{$gid}" />
<input type="hidden" name="action" value="add" />
Added on: {$date}<br />
<input name="update" type="button" id="update_now" value="update" />
</form>
</div>
</body>
</html>