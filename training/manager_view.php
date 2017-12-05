<!DOCTYPE html>
<html lang="en">
<?php
    require "connect.php";
    require "../../connect.php";
    session_start();
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>OBServer - Manager Dashboard</title>
    <link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon">
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/progress.css"/>
    <link rel="stylesheet" type="text/css" href="css/manager_view.css"/>
    <link href="css/simple-sidebar.css" rel="stylesheet">

    <link rel="stylesheet" href="css/circle.css">

    <!-- online css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <header class="headerStyle">
        <a href="#menu-toggle" class="titleHeaderStyle" id="menu-toggle">&#9776;</a> &nbsp; <span class="titleHeaderstyle"><a href="../" class="titleHeaderStyle">OBserver</a></span> 
        <span class="glyphicon glyphicon-chevron-right headerChevron"></span>
        <a href="./" class="subtitleHeaderStyle"> Training </a>
    </header>


    <div id="wrapper">
    <?php
        require "sidebar.php";  

        if (isset($_GET['team'])) {
            $team = $_GET['team'];
            foreach ($handled_teams as $handled_team) {
                if ($handled_team['team_id'] == $_GET['team']) {
                    $team_name = $handled_team['team_name'];
                    break;
                }
            }
        }
        else {
            $team = $handled_teams[0]['team_id'];
            $team_name = $handled_teams[0]['team_name'];
        }

        $item_classifications = array();
        $ic_res = mysqli_query($link, "SELECT item_classification_id, item_classification_name FROM item_classification WHERE (account_id IN (SELECT account_id FROM account WHERE team_id = $team) OR account_id IS NULL) AND type_id = 1");
        while ($ic_row = mysqli_fetch_array($ic_res)) {
            $item_classifications[] = $ic_row;
        }      
    ?>
        <!-- Page Content -->
        <div class="row">
            <div class="col-lg-12 page-tableHeader">
                <div class="progressHeader" id="progressHeader">
                    <?php 
                        echo "$team_name Training Progress <br>";
                        if (sizeof($handled_teams) > 1) {
                            echo "<select id='team_select'>";
                                echo "<option value=0> -- Select Team -- </option>"; 
                                foreach ($handled_teams as $handled_team) {
                                    echo "<option value=" . $handled_team['team_id'] . ">" . $handled_team['team_name'] . "</option>";
                                }
                            echo "</select>";
                        }
                    ?>
                </div>
            </div>
            <div class='table-container-div'>
                <table class='GeneratedTable employee-progress-table'>
                    <thead>
                        <tr>
                            <th rowspan=2 width=30% style='border-right: 1px solid black !important'> Name </th>
                            <?php echo "<th class='left-cell' colspan=".sizeof($item_classifications)."> Training Classification </th>"; ?>
                        </tr>
                        <tr>
                        <?php
                            for ($x = 0; $x < sizeof($item_classifications); $x++) {
                                echo "<td width=5%>" . $item_classifications[$x]['item_classification_name'] . "</td>";
                            }
                        ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $user_res = mysqli_query($link, "SELECT u.user_id, CONCAT(u.last_name, ', ', u.first_name) AS name FROM users u WHERE u.team_id = $team ORDER BY name");
                        while ($user_row = mysqli_fetch_array($user_res)) {
                            echo "<tr>"
                            . "<th class='left-cell'><a href='progress.php?id=genOnboard&user=" . $user_row['user_id'] . "'>" . $user_row['name'] . "</a></th>";
                            foreach ($item_classifications as $ic) {
                                $total_items = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(tracked_item_id) AS ct FROM tracked_item WHERE user_id = " . $user_row['user_id'] . " AND item_id IN (SELECT item_id FROM item WHERE item_classification_id = " . $ic['item_classification_id'] . ")"))['ct'];
                                if ($total_items) {
                                    $num_completed = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(tracked_item_id) AS ct FROM tracked_item WHERE user_id = " . $user_row['user_id'] . " AND item_id IN (SELECT item_id FROM item WHERE item_classification_id = " . $ic['item_classification_id'] . " AND (status = 'Completed' OR status = 'N/A'))"))['ct'];
                                    echo "<td>" . round(($num_completed/$total_items) * 100) . "%</td>";
                                }
                                else 
                                    echo "<td> N/A </td>";

                            }
                            echo "<tr>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
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

    <!-- custom js -->
    <script src="js/custom.js"></script>
    <!-- you need to include the shieldui css and js assets in order for the components to work -->
<link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light/all.min.css" />
<script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>

<script type="text/javascript" src="js/circleprogress.js"></script>

</body>

</html>
