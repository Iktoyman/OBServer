<?php
	require "connect.php";

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
		if ($_POST['action'] == 'mark_complete') {
			$ar = array();
			$id = $_POST['id'];

			$qry = "UPDATE tracked_item SET status = 'Completed', completion_date = NOW() WHERE tracked_item_id = " . $id;
			if (mysqli_query($link, $qry)) {
				$get_dets = mysqli_query($link, "SELECT i.item_name, ti.status FROM item i, tracked_item ti WHERE ti.item_id = i.item_id AND ti.tracked_item_id = " . $id);
				$ar = mysqli_fetch_array($get_dets);
				$ar['result'] = 1;
			}
			else {
				$ar['result'] = 0;
			}

			echo json_encode($ar);
		}
	}

?>	