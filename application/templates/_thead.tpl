{strip}
<div id="report_info">
	<div id="nav_header">
		<div id="logo_box">
			<img id="logo" src="images/logo.png" onClick="goHome();" style="cursor:pointer;" title="Go Home" />
		</div>
     
       
        
         

     <a id="campaign_active" class="nocufon" href="campaigns">Campaigns</a>
     <a id="reports_active" class="nocufon" href="reports">Reports</a>
     <a id="list_active" class="nocufon" href="lists">Lists</a>
		<a {if $action eq 'report_history' || $action eq 'purchase_history' || $action eq 'change_password'}id="adv_search_active"{else}id="adv_search"{/if} class="nocufon" href="account_tools">My Account</a>
		<div class="my_account_info" id="my_account_info" class="nocufon">
		<div class="nocufon">
		</div>
		</div>
	</div>
{/strip}