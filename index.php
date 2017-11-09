<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Onboarding Tracker</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/simple-sidebar.css" rel="stylesheet">

    <!-- online css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- custom js -->
    <script src="js/custom.js"></script>

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
?>
    <header class="headerStyle">
        <a href="#menu-toggle" class="titleHeaderStyle" id="menu-toggle">&#9776;</a> &nbsp; <span class="titleHeaderstyle">OBserver</div>
    </header>


    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li>
                    <a href="./connect"> Manager Connect </a>
                </li>
                <li>
                    <a href="#">Dashboard</a>
                </li>
                <li>
                    <a href="manager_view.php">Manager View</a>
                </li>
                <li>
                    <?php echo "<a href='training_list.php?team=".$handled_team['team_id']."'>Trainings</a>"; ?>
                </li>
                <li>
                    <a href="#">Overview</a>
                </li>
                <li>
                    <a href="#">Events</a>
                </li>
                <li>
                    <a href="#">About</a>
                </li>
                <li>
                    <a href="#">Services</a>
                </li>
                <li>
                    <a href="#">Contact</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <center>
                            <table style="margin-top: 3%">  
                            <?php
                                
                            $i = 0;
                            $team = $_SESSION['ob_team'];
                            $res = mysqli_query($link, "SELECT item_classification_id, item_classification_name, icon_path FROM item_classification WHERE account_id IN (SELECT account_id FROM account WHERE team_id = " . $team . ") OR account_id IS NULL");
                            while ($ic_row = mysqli_fetch_array($res)) {
                                $ic_id = substr($ic_row['icon_path'], 4);
                                $ic_id = substr($ic_id, 0, strpos($ic_id, '.'));
                                if ($i % 4 == 0)
                                    echo "<tr>";
                                echo "<td class='tdStyle' onmouseover=item_class_mo('".$ic_id."') onmouseout=item_class_moh('".$ic_id."')>";
                                    echo "<a href='progress.php?id=".$ic_id."'>";
                                        echo "<img src='" . $ic_row['icon_path'] . "' class='iconStyle' id='".$ic_id."'>";
                                        echo "<br><br>";
                                        echo "<center><span class='titleIconStyle'>" . $ic_row['item_classification_name'] . "</span></center>";
                                        $completed_qry = mysqli_query($link, "SELECT COUNT(ti.item_id) as completed FROM tracked_item ti, item i WHERE ti.item_id = i.item_id AND i.item_classification_id = " . $ic_row['item_classification_id'] . " AND ti.user_id = " . $_SESSION['ob_uid'] . " AND ti.status = 'Completed'");
                                        $completed_ct = mysqli_fetch_assoc($completed_qry)['completed'];
                                        $total_qry = mysqli_query($link, "SELECT COUNT(item_id) AS total FROM item WHERE item_classification_id = " . $ic_row['item_classification_id']);
                                        $total_ct = mysqli_fetch_assoc($total_qry)['total'];
                                        $percentage_completion = ($completed_ct / $total_ct) * 100;
                                        if ($percentage_completion == 0) $percentage_completion = 1;
                                        echo "<div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='".$percentage_completion."' aria-valuemin='0' aria-valuemax='100' style='width:100%'>";
                                            echo $completed_ct . " of " . $total_ct . " completed";
                                        echo "</div>";
                                    echo "</a>";
                                echo "</td>";
                                $i++;
                                if ($i % 4 == 0)
                                    echo "</tr>";
                            }
                                
                            ?>
                            </table>
                        </center>
                    </div>
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
