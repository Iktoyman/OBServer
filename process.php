<?php
	$conn = mysqli_connect("localhost", "root", "admin", "skms");
	require "connect.php";
	session_start();

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
		switch ($_POST['action']) {
			// -----------------------------------------------------------------------------------------------------------------------------------------------
			//	Add Employee
			// -----------------------------------------------------------------------------------------------------------------------------------------------
			case 'add_user':
				$inputs = $_POST['inputs'];
				$team = $_POST['team'];

				$check_if_user_exists = mysqli_query($conn, "SELECT username FROM users WHERE username = '" . $inputs[2] . "'");
				if (mysqli_num_rows($check_if_user_exists)) {
					$check_in_observer = mysqli_query($link, "SELECT user_id FROM users WHERE username = '" . $inputs[2] . "'");
					if (mysqli_num_rows($check_in_observer))
						$result = 2;
					else
						$result = 1;
				}
				else {
					// Try to check LDAP if email is valid and existing

					// Add User to KMS user DB
					if (mysqli_query($conn, "INSERT INTO users(username, first_name, last_name) VALUES('" . $inputs[2] . "', '" . $inputs[0] . "', '" . $inputs[1] . "')")) {
						// Assign User to KMS Team
						# if Nestle	
						if ($team == 4 || $team == 5)
							$kms_team = 5;
						# if Platforms
						else if ($team == 7)					
							$kms_team = 11;
						# if Mainframe
						else if ($team == 8)
							$kms_team = 15;
						# SAT1-3 or Backup team
						else
							$kms_team = $team;

						mysqli_query($conn, "INSERT INTO user_team_grouping(user_group_id, username) VALUES($kms_team, '" . $inputs[2] . "')");
						$result = 1;
					}
					else
						$result = 0;
				}

				if ($result == 1) {
					// Add user to OBServer user DB
					if (mysqli_query($link, "INSERT INTO users(username, first_name, last_name, hire_date, team_id, team_join_date) VALUES('" . $inputs[2] . "', '" . $inputs[0] . "', '" . $inputs[1] . "', '" . $inputs[3] . "', $team, '" . $inputs[4] . "')")) {
						// Populate user's tracked items according to team
						$user_id = mysqli_insert_id($link);
	          $res = mysqli_query($link, "SELECT item_classification_id FROM item_classification WHERE account_id IN (SELECT account_id FROM account WHERE team_id = $team) OR account_id IS NULL");
	          while ($row = mysqli_fetch_array($res)) {
	            $res2 = mysqli_query($link, "SELECT item_id, item_name FROM item WHERE item_classification_id = " . $row['item_classification_id']);
	            while ($row2 = mysqli_fetch_array($res2)) {
	              mysqli_query($link, "INSERT INTO tracked_item (user_id, item_id, start_date, status) VALUES($user_id, " . $row2['item_id'] . ", NOW(), 'Pending')");
	            }
	          }

						$result = 1;
					}
					else
						$result = 0;
				}

				echo json_encode($result);
				break;
			// -----------------------------------------------------------------------------------------------------------------------------------------------
			//	Team select dropdown
			// -----------------------------------------------------------------------------------------------------------------------------------------------
			case 'change_team':
				$team = $_POST['team'];
				$users = array();

				if ($team)
					$qry = "SELECT u.user_id, CONCAT(u.last_name, ', ', u.first_name) AS name, t.team_name FROM users u, team t WHERE u.team_id = t.team_id AND u.team_id = $team ORDER BY u.last_name";
				else
					$qry = "SELECT u.user_id, CONCAT(u.last_name, ', ', u.first_name) AS name, t.team_name FROM users u, team t WHERE u.team_id = t.team_id ORDER BY u.team_id, u.last_name";
				$res = mysqli_query($link, $qry);
				while ($row = mysqli_fetch_array($res))
					$users[] = $row;

				echo json_encode($users);
				break;
			// -----------------------------------------------------------------------------------------------------------------------------------------------
			//	Search bar
			// -----------------------------------------------------------------------------------------------------------------------------------------------
			case 'search_user':
				$is_filtered = $_POST['is_filtered'];
				$team = $_POST['filter'];
				$term = $_POST['search_term'];
				$users = array();

				if ($is_filtered)
					$qry_filter = " AND u.team_id = $team";
				else
					$qry_filter = "";

				$qry = "SELECT u.user_id, CONCAT(u.last_name, ', ', u.first_name) AS name, t.team_name FROM users u, team t WHERE u.team_id = t.team_id AND (u.last_name LIKE '%" . $term . "%' OR u.first_name LIKE '%" . $term . "%')" . $qry_filter . " ORDER BY u.team_id, u.last_name";
				$res = mysqli_query($link, $qry);
				while ($row = mysqli_fetch_array($res))
					$users[] = $row;

				echo json_encode($users);
				break;
		}
	}

?>	