<?php
	require "connect.php";
	/*	
	$employees = array();

	$row = 1;
	if (($handle = fopen("resources/observer_sat2.csv", "r")) !== FALSE) {
			$x = 0;
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	        $num = count($data);
	        //echo "<p> $num fields in line $row: <br /></p>\n";
	        $row++;

          //echo $data[$c] . "<br />\n";
          $name = explode(',', $data[0]);
        	$employees[$x]['last_name'] = $name[0];
        	$employees[$x]['first_name'] = $name[1];
        	$employees[$x]['start_date'] = $data[1];
        	$employees[$x]['emp_id'] = $data[2];
        	$employees[$x]['email'] = $data[3];

	        $x++;
	    }
	    fclose($handle);
	}

	foreach($employees as $emp) {
		$lname = $emp['last_name'];
		$fname = $emp['first_name'];
		$empid = $emp['emp_id'];
		$hdate = $emp['start_date'];
		$email = $emp['email'];
		$team = 2;

		if (mysqli_query($link, "INSERT INTO users(employee_id, username, first_name, last_name, hire_date, team_id, team_join_date) VALUES($empid, '$email', '$fname', '$lname', '$hdate', $team, '$hdate')")) {
      $user_id = mysqli_insert_id($link);

      // Initialize tracked items 
      $res = mysqli_query($link, "SELECT item_classification_id FROM item_classification WHERE account_id IN (SELECT account_id FROM account WHERE team_id = $team) OR account_id IS NULL");
      while ($row = mysqli_fetch_array($res)) {
        $res2 = mysqli_query($link, "SELECT item_id FROM item WHERE item_classification_id = " . $row['item_classification_id']);
        while ($row2 = mysqli_fetch_array($res2)) {
          mysqli_query($link, "INSERT INTO tracked_item (user_id, item_id, start_date, status) VALUES($user_id, " . $row2['item_id'] . ", NOW(), 'Pending')");
        }
      }
    }
	}
	*/
?>