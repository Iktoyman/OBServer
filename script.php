<?php
	require "connect.php";
/*
	$qry1 = mysqli_query($link, "SELECT item_id FROM item WHERE item_id >= 55");
	while ($row1 = mysqli_fetch_array($qry1)) {
		$item = $row1['item_id'];
		$qry2 = mysqli_query($link, "SELECT user_id, hire_date FROM users");
		while ($row2 = mysqli_fetch_array($qry2)) {
			$qry3 = mysqli_query($link, "INSERT INTO tracked_item(user_id, item_id, completion_proof_img_path, target_completion_date, start_date, completion_date, status) VALUES(".$row2['user_id'].", ".$item.", NULL, DATE_ADD('".$row2['hire_date']."', INTERVAL 180 DAY), '".$row2['hire_date']."', NULL, 'Pending')");
		}
	}
*/	
?>