<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Simple Sidebar - Start Bootstrap Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link href="css/form.css" rel="stylesheet">

    <!-- online css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- custom js -->
    <script src="js/custom.js"></script>

    <!-- date picker -->
    <link href="css/datepicker.min.css" rel="stylesheet" type="text/css">
    <script src="js/datepicker.min.js"></script>
    <script src="js/datepicker.en.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<?php
    $destination = $_GET['redirect'];

    require "connect.php";
    require "../connect.php";
    session_start();
?>

    <header class="headerStyle">
        <span class="titleHeaderstyle">&nbsp;&nbsp;OBserver</div>
    </header>

    <div class="formStyle">
        <table> 
        <form method="POST" action="">
            <tr>
                <td class="formtdStyle">Employee Number:</td>
                <td><img src="img/idnumber.png" style="width: 50px; height:50px"></td>
                <td class="formInputStyle"><input type="text" name="emp_id"></td>
            </tr>
            <tr>
                <td class="formtdStyle">Hire Date:</td>
                <td><img src="img/calendar.png" style="width: 50px; height:50px"></td>
                <td class="formInputStyle"><input type='text' class='datepicker-here' data-language='en' name="hire_date" /></td>
            </tr>
            <!--
            <tr>
                <td class="formtdStyle">Name:</td>
                <td><img src="img/name.png" style="width: 50px; height:50px"></td>
                <td class="formInputStyle"><input type="text"></td>
            </tr>
            -->
            <tr>
                <td class="formtdStyle">Team:</td>
                <td><img src="img/team.png" style="width: 50px; height:50px"></td>
                <td class="formInputStyle">
                    <select name="team">
                      <option> -- SELECT TEAM -- </option>
                      <option value=1> SAT1 </option>
                      <option value=2> SAT2 </option>
                      <option value=3> SAT3 </option>
                      <option value=4> Nestle SAP Basis </option>
                      <option value=5> Backup Team </option>
                      <option value=6> Platforms Team </option>
                      <option value=7> Mainframe Team </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="formtdStyle">Team Join Date:</td>
                <td><img src="img/calendar.png" style="width: 50px; height:50px;"></td>
                <td class="formInputStyle"><input type='text' class='datepicker-here' data-language='en' name="join_date" /></td>
            </tr>
        </table>
        <br><br>
        <input type="submit" class="submitButtonStyle" name="user_signup" value="Submit">
        </form>
    </div>



    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <?php
    if (isset($_POST['user_signup'])) {
        $emp_id = $_POST['emp_id'];
        $hire_date = new DateTime($_POST['hire_date']);
        $hire_date = date_format($hire_date, 'Y-m-d');
        $team = $_POST['team'];
        $join_date = new DateTime($_POST['join_date']);
        $join_date = date_format($join_date, 'Y-m-d');
        $username = $_SESSION['username'];
        $first_name = $_SESSION['first_name'];
        $last_name = $_SESSION['last_name'];

        if (mysqli_query($link, "INSERT INTO users(employee_id, username, first_name, last_name, hire_date, team_id, team_join_date) VALUES(" . $emp_id . ", '" . $username . "', '" . $first_name . "', '" . $last_name . "', '" . $hire_date . "', " . $team . ", '" . $join_date . "')")) {
          $user_id = mysqli_insert_id($link);
          $_SESSION['ob_uid'] = $user_id;
          $_SESSION['ob_team'] = $team;
          // Initialize tracked items 
          $res = mysqli_query($link, "SELECT item_classification_id, item_classification_name FROM item_classification WHERE account_id IN (SELECT account_id FROM account WHERE team_id = " . $team . ") OR account_id IS NULL");
          while ($row = mysqli_fetch_array($res)) {
            $res2 = mysqli_query($link, "SELECT item_id, item_name FROM item WHERE item_classification_id = " . $row['item_classification_id']);
            while ($row2 = mysqli_fetch_array($res2)) {
              mysqli_query($link, "INSERT INTO tracked_item (user_id, item_id, start_date, status) VALUES(" . $user_id . ", " . $row2['item_id'] . ", NOW(), 'Pending')");
            }
          }
          echo "<script>";
          echo "alert('User successfully added! Welcome to *Onboarding Tracker Name*!');";
          echo "window.location.href = '".$destination."'";
          echo "</script>";
        }
      }  
    ?>
</body>

</html>
