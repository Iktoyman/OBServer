<?php
	require "connect.php";
	$conn = mysqli_connect("localhost", "root", "admin", "skms");
	
	/*
	$employees = array();
	$myfile = fopen("resources/Nestle employee data.csv", 'r');
	while ($row = fgetcsv($myfile, 1000)) {
		$employees[] = $row;
	}
	fclose($myfile);

	foreach ($employees as $employee) {
		$ct = mysqli_num_rows(mysqli_query($conn, "SELECT username FROM users WHERE username = '" . $employee[1] . "'"));

		$name = explode(',', $employee[0]);
		$lname = trim($name[0]);
		$fname = trim($name[1]);

		if (!$ct) {
			echo "User " . trim($employee[1]) . " does not exist in KMS <br>";
			$qry1 = "INSERT INTO users(username, first_name, last_name) VALUES('$employee[1]', '$name[1]', '$name[0]')";
			if (mysqli_query($conn, $qry1))
				echo $qry1 . " success<br>";
			$qry2 = "INSERT INTO user_team_grouping(user_group_id, username) VALUES(5, '$employee[1]')";
			if (mysqli_query($conn, $qry2))
				echo $qry2 . " success<br>";
		}
		else
			echo "User exists in KMS<br><br>";

		$ct2 = mysqli_num_rows(mysqli_query($link, "SELECT user_id FROM users WHERE username = '" . $employee[1] . "'"));

		if (!$ct2) {
			$qry3 = "INSERT INTO users(username, first_name, last_name, hire_date, team_id, team_join_date) VALUES('$employee[1]', '$name[1]', '$name[0]', '$employee[2]', 4, '$employee[2]')";
			if (mysqli_query($link, $qry3))
				echo $qry3 . "<br>";
			$user_id = mysqli_insert_id($link);
			$res = mysqli_query($link, "SELECT item_classification_id, item_classification_name FROM item_classification WHERE account_id IN (SELECT account_id FROM account WHERE team_id = 4) OR account_id IS NULL");
			while ($row = mysqli_fetch_array($res)) {
	     	$res2 = mysqli_query($link, "SELECT item_id, item_name FROM item WHERE item_classification_id = " . $row['item_classification_id']);
	      while ($row2 = mysqli_fetch_array($res2)) {
	        mysqli_query($link, "INSERT INTO tracked_item (user_id, item_id, start_date, status) VALUES($user_id, " . $row2['item_id'] . ", NOW(), 'Pending')");
	      }
	    }
	  }
	  else
	  	echo "User already exists <br><br>";
	}
	*/
?>