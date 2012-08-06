<?PHP
$email_confirm_link=SITE_TITLE."/confirm.php";
for($i=0;$i<=12;$i++)
{
if(strlen($i)==1){$i="0".$i;}
$hour[]=$i;
}
for($i=0;$i<=59;$i++)
{
if(strlen($i)==1){$i="0".$i;}
$minute[]=$i;
}
for($i=0;$i<=59;$i++)
{
if(strlen($i)==1){$i="0".$i;}
$second[]=$i;
}
$meridian=array("AM","PM");

//$topmenu=array("Home","Mail","Contacts","Network","Offers","Bookings","Contracts","Opportunities","Tour Insight");
$topmenu_opt = array("Home","Mail","Contacts","Network","Offers","Bookings","Documents");
$topmenu = array(_DASHBOARD,_MAIL,_CONTACTS,_COMMUNITY,_OFFERS,_BOOKING,_DOCUMENTS);

//$status_value=array("Open","Accepted","Countered","Declined - Closed","Expired - Closed","Send");
$status_value=array("Unread","Agent Review","Manager Review","Artist Review","Pending",
	"Accepted","Accepted by Manager","Accepted by Artist","Countered by Agent","Countered by Manager",
	"Countered by Artist","Declined by Venue","Declined by Buyer","Declined by Promoter","Declined by Agency",
	"Declined by Agent","Declined by Manager","Declined by Artist","Closed/Expired","Booked",
	"Declined","Counter Offer");
$currency=array("$","&euro;","&yen;","&pound;","Rs","R$","NRs");
$currency_val=array("$","eu","ye","po","Rs","R$","NRs");

//Booking status
$booking_status_val = array("Contract Status","Payment Status","Lodging Status","Transportation Status","Rider Status");

$create_new_opt = array("message");
$create_new_opt_code = array("1");
?>