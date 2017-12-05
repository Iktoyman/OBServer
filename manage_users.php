<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>OBServer - Manage Employees</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link href="css/manage_user.css" rel="stylesheet">

    <!-- online css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- custom js -->
    <script src="js/custom.js"></script>
    <script type="text/javascript" src="js/manage_user.js"></script>

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

    $users = array();
    $user_res = mysqli_query($link, "SELECT u.user_id, CONCAT(u.last_name, ', ', u.first_name) AS name, t.team_name FROM users u, team t WHERE u.team_id = t.team_id ORDER BY t.team_id, u.last_name");
    while ($row = mysqli_fetch_array($user_res))
        $users[] = $row;
?>
    <script>
        var users = <?php echo json_encode($users); ?>;
    </script>
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
                <div class='table-header-div'>
                    <span> Manage Users </span>
                    <div class='table-header-input-div'>
                        <select id='team_select'>
                            <option value=''> -- Select Team -- </option>
                            <option value=0> All Teams </option>
                            <option value=2> SAT2 SAP Basis </option>
                            <option value=4> Nestle SAP Basis </option>
                            <option value=5> Nestle Platforms </option> 
                        </select>
                        <input type='text' id='search-user' class='form-control fa fa-search' placeholder="&#xF002; Search by name"/>
                    </div>
                </div>
                <table class='table table-hover manage-user-table'>
                    <thead>
                        <tr>
                            <th width=5%> </th>
                            <th width=47.5%> Employee Name </th>
                            <th width=47.5% colspan=2> Employee Team </th>
                        </tr>
                    </thead>
                </table>
                <div class='manage-user-tbody'>
                    <table class='table table-hover table-striped manage-user-table'>
                        <tbody id='manage-user-table-tbody'>
                        <?php
                            foreach ($users as $user) {
                                echo "<tr>"
                                . "<td width=5% style='text-align: center'><input type='checkbox' class='user-checkbox' id='user-row_" . $user['user_id'] . "' ></td>"
                                . "<td width=47.5%>" . $user['name'] . "</td>"
                                . "<td width=40%>" . $user['team_name'] . "</td>"
                                . "<td width=7.5% style='text-align: center'>"
                                    . "<a id='edit-btn_" . $user['user_id'] . "' onclick='editUser(this.id)'><span class='edit-btn glyphicon glyphicon-pencil'></span></a>&nbsp;&nbsp;"
                                    . "<a id='delete-btn_" . $user['user_id'] . "' onclick='deleteUser(this.id)'><span class='delete-btn glyphicon glyphicon-trash'></span></a>"
                                . "</td>"
                                . "</tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
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
