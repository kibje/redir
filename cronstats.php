<?php

$time_start = microtime(true);
$today = strtotime(date("Y-m-d"));

include("functions.php");	
$con = db_connect();

if ($res = $con->query("SELECT * FROM addonstats")) {
    while ($row = $res->fetch_row()) {
    	$addonid = $row[0];
    	$addonstats = $row[1];
	$qry1 = "SELECT * FROM download WHERE addonid='".$addonid."' AND dldate='".$today."'";
    	$res2 = $con->query($qry1);
	if ($res2->num_rows > 0) {
            $qry2 = "UPDATE download SET stats='".$addonstats."' WHERE addonid = '".$addonid."' AND dldate='".$today."'";
	    } else {
            $qry2 = "INSERT INTO download (addonid, dldate, stats) VALUES ('".$addonid. "','".$today."','".$addonstats."' )";
    	}
	$res3 = $con->query($qry2);
	$res2->close();
    }
    $res->close();
}    
$con->close();

$time_taken = microtime(true) - $time_start;
$resstring = date("Y-m-d h:m")." - saving stats took ". $time_taken . " \n";
file_put_contents(dirname(__FILE__)."/cronstatslog.txt", $resstring, FILE_APPEND | LOCK_EX);

?>
