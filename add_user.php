<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>OBServer - Add Employee</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link href="css/add_user.css" rel="stylesheet">

    <!-- online css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- custom js -->
    <script src="js/custom.js"></script>
    <script type="text/javascript" src="js/add_user.js"></script>

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
    require "connect.php";
    require "../connect.php";
    session_start();
    require "establish_user.php";

    // Check if manager, and get team if so.
    $is_manager = mysqli_query($link, "SELECT m.team_id, t.team_name FROM manager m, team t WHERE t.team_id = m.team_id AND m.user_id = " . $_SESSION['ob_uid']);
    if (mysqli_num_rows($is_manager)) {
        $handled_team = mysqli_fetch_array($is_manager);
    }
    else {
        $employee_team = mysqli_query($link, "SELECT u.team_id, t.team_name FROM users u, team t WHERE t.team_id = u.team_id AND u.user_id = " . $_SESSION['ob_uid']);
        $handled_team = mysqli_fetch_array($employee_team);
    }

    $teams = array();
    $get_teams = mysqli_query($link, "SELECT team_id, team_name FROM team");
    while ($team_row = mysqli_fetch_array($get_teams)) {
        $teams[] = $team_row;
    }
?>
    <header class="headerStyle">
        <a href="#menu-toggle" class="titleHeaderStyle" id="menu-toggle">&#9776;</a> &nbsp; <a href="./" class="titleHeaderstyle">OBserver</a></div>
    </header>


    <div id="wrapper">
    <?php
        require "sidebar.php";        
    ?>
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <table class='add-user-table'>
                    <tr>
                        <th colspan=2><h3>Add New Employee User</h3></th>
                    </tr>
                    <tr>
                        <td width=40%><label>Employee First Name</label></td>
                        <td><input type='text' name='first_name' id='first_name' placeholder='Employee First Name' /></td>
                    </tr>
                    <tr>
                        <td><label>Employee Last Name</label></td>
                        <td><input type='text' name='last_name' id='last_name' placeholder='Employee Last Name' /></td>
                    </tr>
                    <tr>
                        <td><label>Employee E-mail</label></td>
                        <td><input type='text' name='email' id='email' placeholder='Email Address' /></td>
                    </tr>
                    <tr>
                        <td><label>Hire Date</label></td>
                        <td><i class='glyphicon glyphicon-calendar'></i> <input type='text' class='datepicker-here' data-language="en" name='hire_date' id='hire_date' placeholder='Company Hire Date' /></td>
                    </tr>
                    <tr>
                        <td><label>Team</label></td>
                        <td>
                            <i class='glyphicon glyphicon-user'></i> 
                            <select name='team' id='team'>
                                <option value=0> -- Select Team -- </option>
                                <?php
                                foreach($teams as $team)
                                    echo "<option value=" . $team['team_id'] . ">" . $team['team_name'] . "</option>";
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Team Start Date</label></td>
                        <td><i class='glyphicon glyphicon-calendar'></i> <input type='text' class='datepicker-here' data-language="en" name='team_start' id='team_start' placeholder='Team Start Date' /></td>
                    </tr>
                    <tr>
                        <th colspan=2>
                            <h4>
                                <a id='save-user-btn'>Save</a>
                            </h4>
                        </th>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

</body>

</html>
