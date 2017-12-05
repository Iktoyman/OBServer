<?php
	require "connect.php";
	$conn = mysqli_connect("localhost", "root", "admin", "skms");
	session_start();

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
		switch($_POST['action']) {
			// --------------------------------------------------------------------------------------------------------------------------------------------------------------
			// 		Mark an item as 'Completed'
			// ==============================================================================================================================================================
			case 'mark_complete':
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
				break;

			// --------------------------------------------------------------------------------------------------------------------------------------------------------------
			// 		Mark an item as 'N / A'
			// ==============================================================================================================================================================
			case 'mark_notapplicable':
				$id = $_POST['id'];

				$qry = "UPDATE tracked_item SET status ='N/A', completion_date = NOW() WHERE tracked_item_id = " . $id;
				$result = mysqli_query($link, $qry) ? 1 : 0;

				echo json_encode($result);
				break;

			// --------------------------------------------------------------------------------------------------------------------------------------------------------------
			// 		Save a new item to the database and populate tracked items for appropriate employees
			// ==============================================================================================================================================================
			case 'add_access_item':
				$item_class = $_POST['item_class'];
				$item_class_name = $_POST['item_class_name'];
				$item_name = $_POST['item_name'];
				//$item_url = $_POST['item_url'];
				$days_completion = $_POST['days_completion'];
				$type = $_POST['type'];
				$acct = $_POST['acct'];
				$logo_src = $_POST['logo_src'];
				$logo_src_hover = $_POST['logo_src_hover'];
				$logo_name = $_POST['logo_name'];
				$logo_hover = $_POST['logo_hover'];

				$result = 0;

				if (file_exists($logo_name))
					$result = 2;
				else {
					if ($item_class == 'new') {
						if ($type == 'team') {
							// Create item classification for account-specific access
							$qry = "INSERT INTO item_classification(item_classification_name, type_id, account_id, icon_path) VALUES('$item_class_name', 2, $acct, '$logo_name')";
							if (mysqli_query($link, $qry)) {
								$ic_id = mysqli_insert_id($link);
								copy($logo_src, $logo_name);
								copy($logo_src_hover, $logo_hover);
								if (mysqli_query($link, "INSERT INTO item(item_classification_id, item_name, days_before_completion) VALUES($ic_id, '$item_name', $days_completion)"))
									$result = 1;
							}
						}
						else {
							// Create item classification for general access
							$qry = "INSERT INTO item_classification(item_classification_name, type_id, account_id, icon_path) VALUES('$item_class_name', 2, NULL, '$logo_name')";
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
						
						// Add KMS entry link
						//if ($item_url != '')
							//mysqli_query($link, "INSERT INTO kms_training(item_id, kms_link) VALUES($item_id, '$item_url')");

						// Check if General or Team-Specific Access
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

						// If user is a trainer and item is created with a new classification, add it to their responsibility
						/*
						if (isset($_SESSION['ob_trainer_id']) && $item_class == 'new') {
							$qry = "INSERT INTO trainer_responsibility(item_classification_id, trainer_id) VALUES($ic_id, ".$_SESSION['ob_trainer_id'].")";
							//var_dump($qry);
							mysqli_query($link, $qry);
						}
						*/
					}
				}
				
				echo json_encode($result);
				break;

			// --------------------------------------------------------------------------------------------------------------------------------------------------------------
			// 		Delete an access item from database
			// ==============================================================================================================================================================
			case 'delete_access_item':
				$id = $_POST['id'];

				if (mysqli_query($link, "DELETE FROM tracked_item WHERE item_id = $id")) {
					$get_classification = mysqli_fetch_assoc(mysqli_query($link, "SELECT item_classification_id FROM item WHERE item_id = $id"))['item_classification_id'];
					$remaining = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(item_id) AS ct FROM item WHERE item_classification_id = $get_classification"))['ct'];
					mysqli_query($link, "DELETE FROM kms_training WHERE item_id = $id");
					mysqli_query($link, "DELETE FROM item WHERE item_id = $id");
					if ($remaining == 1) {
						//mysqli_query($link, "DELETE FROM trainer_responsibility WHERE item_classification_id = $get_classification");
						mysqli_query($link, "DELETE FROM item_classification WHERE item_classification_id = $get_classification");
					}
					$result = 1;
				}
				else 
					$result = 0;

				echo json_encode($result);
				break;

			// --------------------------------------------------------------------------------------------------------------------------------------------------------------
			// 		Check if KMS entry linked exists
			// ==============================================================================================================================================================
			case 'check_kms':
				$id = $_POST['id'];

				$ct = mysqli_query($conn, "SELECT document_no FROM content WHERE document_id = '$id'");
				if (mysqli_num_rows($ct))
					$result = 1;
				else
					$result = 0;

				echo json_encode($result);
				break;

			// --------------------------------------------------------------------------------------------------------------------------------------------------------------
			// 		Save changes to an edited access item
			// ==============================================================================================================================================================
			case 'save_edit_access_item':
				$id = $_POST['id'];
				$name = $_POST['name'];
				//$url = $_POST['url'];

				if (mysqli_query($link, "UPDATE item SET item_name = '$name' WHERE item_id = $id")) {
					//$check_if_exists_qry = "SELECT kms_training_id FROM kms_training WHERE item_id = $id";
					//$check_if_exists_res = mysqli_query($link, $check_if_exists_qry);
					//if (mysqli_num_rows($check_if_exists_res)) 
						//$kms_query = "UPDATE kms_training SET kms_link = '$url' WHERE item_id = $id";
					//else
						//$kms_query = "INSERT INTO kms_training(item_id, kms_link) VALUES($id, '$url')";

					//$result = mysqli_query($link, $kms_query) ? 1 : 0;
					$result = 1;
				}
				else 
					$result = 0;

				echo json_encode($result);
				break;

			// --------------------------------------------------------------------------------------------------------------------------------------------------------------
			// 		Save changes to item classification name
			// ==============================================================================================================================================================
			case 'save_edit_class_name':
				$id = $_POST['id'];
				$name = $_POST['name'];

				if (mysqli_query($link, "UPDATE item_classification SET item_classification_name = '$name' WHERE item_classification_id = $id"))
					$result = 1;
				else
					$result = 0;

				echo json_encode($result);
				break;

			// --------------------------------------------------------------------------------------------------------------------------------------------------------------
			// 		Save changes to item completion date
			// ==============================================================================================================================================================
			case 'save_edit_date':
				$id = $_POST['id'];
				$completion_date = $_POST['comp_date'];

				if (mysqli_query($link, "UPDATE tracked_item SET completion_date = '$completion_date' WHERE tracked_item_id = $id"))
					$result = 1;
				else
					$result = 0;

				echo json_encode($result);
				break;
		}
		/*
		if ($_POST['action'] == 'mark_complete') {
			
		}
		else if ($_POST['action'] == 'mark_notapplicable') {
			
		}
		else if ($_POST['action'] == 'add_training_item') {
			
		}
		else if ($_POST['action'] == 'delete_training_item') {

		}
		else if ($_POST['action'] == 'check_kms') {

		}
		else if ($_POST['action'] == 'save_edit_training_item') {

		}
		else if ($_POST['action'] == 'save_edit_class_name') {

		}
		*/
	}

?>	