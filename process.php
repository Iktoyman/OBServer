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
		else if ($_POST['action'] == 'add_training_item') {
			$item_class = $_POST['item_class'];
			$item_class_name = $_POST['item_class_name'];
			$item_name = $_POST['item_name'];
			$days_completion = $_POST['days_completion'];
			$type = $_POST['type'];
			$acct = $_POST['acct'];
			$logo_src = $_POST['logo_src'];
			$logo_src_hover = $_POST['logo_src_hover'];
			$logo_name = $_POST['logo_name'];
			$logo_hover = $_POST['logo_hover'];

			$result = 0;

			if ($item_class == 'new') {
				if ($type == 'team') {
					// Create item classification for account-specific training
					$qry = "INSERT INTO item_classification(item_classification_name, account_id, icon_path) VALUES('$item_class_name', $acct, '$logo_name')";
					if (mysqli_query($link, $qry)) {
						$ic_id = mysqli_insert_id($link);
						copy($logo_src, $logo_name);
						copy($logo_src_hover, $logo_hover);
						if (mysqli_query($link, "INSERT INTO item(item_classification_id, item_name, days_before_completion) VALUES($ic_id, '$item_name', $days_completion)"))
							$result = 1;
					}
				}
				else {
					// Create item classification for general training
					$qry = "INSERT INTO item_classification(item_classification_name, account_id, icon_path) VALUES('$item_class_name', NULL, '$logo_name')";
					if (mysqli_query($link, $qry)) {
						$ic_id = mysqli_insert_id($link);
						copy($logo_src, $logo_name);
						copy($logo_src_hover, $logo_hover);
						if (mysqli_query($link, "INSERT INTO item(item_classification_id, item_name, days_before_completion) VALUES($ic_id, '$item_name', $days_completion)"))
							$result = 1;
					}
				}
			}
			else {
				$qry = "INSERT INTO item(item_classification_id, item_name, days_before_completion) VALUES($item_class, '$item_name', $days_completion)";
				if (mysqli_query($link, $qry))
					$result = 1;
			}

			if ($result) {
				$item_id = mysqli_insert_id($link);

				// Check if General or Team-Specific Training
				if ($item_class = 'new' && $type == 'team') {
					$team_id = mysqli_fetch_assoc(mysqli_query($link, "SELECT a.team_id FROM account a, item_classification ic WHERE ic.account_id = a.account_id AND a.account_id = $acct"))['team_id'];
					$users = mysqli_query($link, "SELECT user_id FROM users WHERE team_id = $team_id");
				}
				else if ($item_class = 'new' && $type == 'gen') {
					$users = mysqli_query($link, "SELECT user_id FROM users");
				}
				else if ($item_class != 'new') {
					$acct_id = mysqli_fetch_assoc(mysqli_query($link, "SELECT ic.account_id FROM item_classification ic, item i WHERE i.item_classification_id = ic.item_classification_id AND i.item_id = $item_id"))['account_id'];
					if ($acct_id == NULL) {
						$users = mysqli_query($link, "SELECT user_id FROM users");
					}
					else {
						$team_id = mysqli_fetch_assoc(mysqli_query($link, "SELECT team_id FROM account WHERE account_id = $acct_id"))['team_id'];
						$users = mysqli_query($link, "SELECT user_id FROM users WHERE team_id = $team_id");
					}
				}

				while ($user = mysqli_fetch_array($users)) {
					$qry = "INSERT INTO tracked_item(user_id, item_id, status) VALUES(".$user['user_id'].", $item_id, 'Pending')";
					mysqli_query($link, $qry);
				}
			}
			
			echo json_encode($result);
		}
		else if ($_POST['action'] == 'delete_training_item') {
			$id = $_POST['id'];

			if (mysqli_query($link, "DELETE FROM tracked_item WHERE item_id = $id")) {
				$get_classification = mysqli_fetch_assoc(mysqli_query($link, "SELECT item_classification_id FROM item WHERE item_id = $id"))['item_classification_id'];
				$remaining = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(item_id) AS ct FROM item WHERE item_classification_id = $get_classification"))['ct'];
				mysqli_query($link, "DELETE FROM item WHERE item_id = $id");
				if ($remaining == 1)
					mysqli_query($link, "DELETE FROM item_classification WHERE item_classification_id = $get_classification");
				$result = 1;
			}
			else 
				$result = 0;

			echo json_encode($result);
		}
		else if ($_POST['action'] == 'save_edit_training_item') {
			$id = $_POST['id'];
			$name = $_POST['name'];

			if (mysqli_query($link, "UPDATE item SET item_name = '$name' WHERE item_id = $id"))
				$result = 1;
			else 
				$result = 0;

			echo json_encode($result);
		}
	}

?>	