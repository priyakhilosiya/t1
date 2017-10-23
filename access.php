<?php
global $access;
$access = array
(
	"dashboard" => array
	(
		"index" => array("A"),
		"ajax_list" => array("A"),
		"details" => array("A"),
	),
	
	"sponsor" => array
	(
		"index" => array("A"),
		"addSponsor"=>array("A"),
		"editSponsor"=>array("A"),
		"cancelSponsor"=>array("A"),
		"ajax_list" => array("A"),
	
	),	
	
	"users" => array
	(
		"index" => array("A","S"),
        'addattendee'=> array("A","S"),
        'editAttendee'=> array("A","S"),
        'inviteAttendees'=> array("A","S"),
        'messageAll'=> array("A","S"),
        'messageAttendee'=> array("A","S"),
        'resendTicket'=> array("A","S"),
        'cancelAttendee'=> array("A","S"),
        'orderView'=> array("A","S"),   
		'export'=> array("A","S"), 
		'viewAccompany'=> array("A","S"), 
	),	
	"tickets" => array
	(
		"index" => array("A"),
	
	),
	"registereduser" => array
	(
		"index" => array("A"),
	
	),
);
?>