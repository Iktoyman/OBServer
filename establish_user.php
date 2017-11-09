<?php
// Establish user privileges
	// -	If user does not log in	- //
	if (!isset($_SESSION['username'])) {
		header("Location:../login.php?redirect=" . urlencode(substr($_SERVER['REQUEST_URI'], 1)));
	}
	// -	If user logs in	- //
	else {
		// - 	Get user name 	- //
		$name_qry = "SELECT first_name, last_name FROM users WHERE username = '" . $_SESSION['username'] . "';";
		$name_result = mysqli_query($conn, $name_qry);
		$name_row = mysqli_fetch_array($name_result);
			$_SESSION['first_name'] = $name_row['first_name'];
			$_SESSION['last_name'] = $name_row['last_name'];

		// -	If user is in approver group	- //
		$approversql = "SELECT * FROM approver_group WHERE username = '" . $_SESSION['username'] . "';";
		$approverresult = mysqli_query($conn, $approversql);
		// -	If user is in reviewer group	- //		
		$reviewersql = "SELECT * FROM reviewer_group WHERE username = '" . $_SESSION['username'] . "';";
		$reviewerresult = mysqli_query($conn, $reviewersql);
		// -	If iser is in admin group 	- //
		$adminsql = "SELECT * FROM admin_users_group WHERE username = '" . $_SESSION['username'] . "';";
		$adminresult = mysqli_query($conn, $adminsql);
		// -	If iser is in LT group 	- //
		$lt_sql = "SELECT * FROM eao_lt_group WHERE username = '" . $_SESSION['username'] . "';";
		$lt_result = mysqli_query($conn, $lt_sql);

		// -	Set normal user privileges first	- //
		$_SESSION['admin'] = false;
		$_SESSION['isReviewer'] = false;
		$_SESSION['isApprover'] = false;

		// -	Set reviewer privileges	- //
		if (mysqli_num_rows($reviewerresult) > 0) {
			$_SESSION['admin'] = false;
			$_SESSION['isReviewer'] = true;
		}
		
		// -	Set approver privileges	- //
		if (mysqli_num_rows($approverresult) > 0) {
			$_SESSION['admin'] = false;
			$_SESSION['isApprover'] = true;
		}

		// -	Set administrator privileges 	- //
		if (mysqli_num_rows($adminresult) > 0) {
			$_SESSION['admin'] = true;
		}

		if (mysqli_num_rows($lt_result) > 0) {
			$_SESSION['manager'] = true;
		}
		else {
			$_SESSION['manager'] = false;
		}

		// -	See if user exists in OBserver database - //
		$user_exists = "SELECT user_id, team_id FROM users WHERE username = '" . $_SESSION['username'] . "'";
		$user_exists_result = mysqli_query($link, $user_exists);
		if (mysqli_num_rows($user_exists_result) > 0) {
			$user_row = mysqli_fetch_array($user_exists_result);
			$_SESSION['ob_uid'] = $user_row['user_id'];
			$_SESSION['ob_team'] = $user_row['team_id'];
			//echo "User exists<br><br>";
			// Set session variables

	    // Check if trainer
	    $is_trainer = mysqli_query($link, "SELECT trainer_id FROM trainer WHERE user_id = " . $_SESSION['ob_uid']);
	    if (mysqli_num_rows($is_trainer)) {
	        $_SESSION['ob_trainer_id'] = mysqli_fetch_assoc($is_trainer)['trainer_id'];
	        $handled_trainings = mysqli_query($link, "SELECT item_classification_id FROM trainer_responsibility WHERE trainer_id = " . $_SESSION['ob_trainer_id']);
	        $trainings = array();
	        while ($tr_row = mysqli_fetch_assoc($handled_trainings))
	        	$trainings[] = $tr_row['item_classification_id'];
	        $_SESSION['ob_trainer_items'] = implode(',', $trainings);
	    }

	    // Check if manager
	    $is_manager = mysqli_query($link, "SELECT manager_id FROM manager WHERE user_id = " . $_SESSION['ob_uid']);
	    if (mysqli_num_rows($is_manager)) {
	    	$_SESSION['ob_manager_id'] = mysqli_fetch_assoc($is_manager)['manager_id'];
	    }
		}
		else {
			 echo "<script>";
			 //echo " $('#signupModal').load('index.php');";
			 header("Location: form.php");
			 echo "</script>";
			// Input employee ID, HP_hire_date, team, team_join_date
			// Execute ajax query to save user in database
			// Once save user is successful, set session variable for team ID
		}
	}
	//echo "<script>console.log('URL: " . $_SERVER['PHP_SELF'] . "');</script>";
?>

