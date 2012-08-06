<?php
if($_SESSION['Account_Id']=="1"){
	$rows_user_details = $db->AFSelectArtist('AF_ARTIST_MID', $_SESSION['Member_Id']);
}
else if($_SESSION['Account_Id']=="7"){
	$rows_user_details = $db->AFSelectVenue('AF_VENUE_MID', $_SESSION['Member_Id']);
}
$sql_user_message="select * from xebura_MESSAGE where AF_MESSAGE_RECEIVER='".$_SESSION['Member_Id']."' and  AF_MESSAGE_OPENED='0' ";
$number_unread_message=$db->query_affected_rows($sql_user_message);
$smarty->assign("number_unread_message",$number_unread_message);

$sql_user_offer_message="select * from xebura_MESSAGE where AF_MESSAGE_RECEIVER='".$_SESSION['Member_Id']."' and    AF_MESSAGE_OPENED='0' and AF_MESSAGE_OFFER='1' ";
$number_unread_offer_message=$db->query_affected_rows($sql_user_offer_message);

$smarty->assign("number_unread_offer_message",$number_unread_offer_message);

?>