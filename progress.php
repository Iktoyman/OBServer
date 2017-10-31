<!DOCTYPE html>
<html lang="en">
<?php
    require "connect.php";
    require "../connect.php";
    session_start();

    if (isset($_GET['user'])) {
        $user_id = $_GET['user'];
        $emp_name = mysqli_fetch_assoc(mysqli_query($link, "SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = " . $user_id))['name'];
    }
    else {
        $user_id = $_SESSION['ob_uid'];
    }

    $item_classifications = array();
    $ic_res = mysqli_query($link, "SELECT item_classification_id, item_classification_name, icon_path FROM item_classification WHERE account_id IN (SELECT account_id FROM account WHERE team_id = " . $_SESSION['ob_team'] . ") OR account_id IS NULL");
    $i = 0;
    while ($ic_row = mysqli_fetch_array($ic_res)) {
        $item_classifications[$i]['id'] = $ic_row['item_classification_id'];
        $item_classifications[$i]['name'] = $ic_row['item_classification_name'];
        $ic_id = substr($ic_row['icon_path'], 4);
        $ic_id = substr($ic_id, 0, strpos($ic_id, '.'));
        $item_classifications[$i]['element_id'] = $ic_id;

        $count_res1 = mysqli_query($link, "SELECT COUNT(ti.tracked_item_id) as ct FROM tracked_item ti, item i WHERE ti.item_id = i.item_id AND ti.user_id = " . $user_id . " AND i.item_classification_id = " . $ic_row['item_classification_id'] . " AND ti.status = 'Completed'");
        $count1 = mysqli_fetch_assoc($count_res1)['ct'];
        $count_res2 = mysqli_query($link, "SELECT COUNT(item_id) as ct FROM item WHERE item_classification_id = " . $ic_row['item_classification_id']);
        $count2 = mysqli_fetch_assoc($count_res2)['ct'];
        $value = ($count1 / $count2) * 100;
        $value = ROUND($value);
        $item_classifications[$i]['completion'] = $value;
        $i++;
    }

    if (isset($_GET['id'])) $load_div = true;
    else $load_div = false;
?>  

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
    <link rel="stylesheet" type="text/css" href="css/progress.css"/>
    <link href="css/simple-sidebar.css" rel="stylesheet">

    <link rel="stylesheet" href="css/circle.css">

    <!-- online css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

    <script>
        var item_classes = <?php echo json_encode($item_classifications); ?>;
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<?php
if ($load_div) echo "<body onload=show_class('".$_GET['id']."Div')>";
else echo "<body>";
?>
    <header class="headerStyle">
        <a href="#menu-toggle" class="titleHeaderStyle" id="menu-toggle">&#9776;</a> &nbsp; <span class="titleHeaderstyle"><a href="../OBserver" class="titleHeaderStyle">OBserver</a></div>
    </header>


    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        KMS v2
                    </a>
                </li>
                <li>
                    <a href="#">Profile</a>
                </li>
                <li>
                    <a href="#">Add Member</a>
                </li>
                <li>
                    <a href="#">Add Training</a>
                </li>
                <li>
                    <a href="#">View Report</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div class="row">
            <div class="col-lg-12">
                <div class="progressHeader" id="progressHeader">
                    General Onboarding
                </div>
                <?php
                if ($_SESSION['ob_uid'] != $user_id) {
                    echo "<div id='employee-name'>"
                        . $emp_name . "<br>"
                        . "<a href='manager_view.php'> <span class='glyphicon glyphicon-chevron-left'></span>Back</a>"
                    . "</div>";
                }
                ?>
            </div>
            <div class="col-lg-12">
                <div>
                    <center>
                        <table>
                            <tr>
                            <?php
                                foreach($item_classifications as $ic) {
                                    echo "<td class='tableTdStyle' onmouseover=item_class_mo('" . $ic['element_id'] . "') onmouseout=item_class_moh('" . $ic['element_id'] . "') onclick=show_class('" . $ic['element_id'] . "Div') >";
                                        echo "<center>";
                                            echo "<img src='img/" . $ic['element_id'] . ".png' id='" . $ic['element_id'] . "' style='width: 80px; height: 80px'>";
                                            echo "<br>";
                                            echo "<p>" . $ic['name'] . "</p>";
                                        echo "</center>";
                                    echo "</td>";
                                }
                            ?>
                            </tr>
                        </table>
                    </center>
                </div>
            </div>
            <div class="royw" style="margin-left: 20%; margin-right:10%;">
                <div class="col-lg-9" style="background-color: #E3E3E3; height: 50%;">
                <?php
                    foreach ($item_classifications as $ic) {
                        echo "<div id='" . $ic['element_id'] . "Div' style='padding:10px'>";
                        echo "<center>";
                            echo "<table class='GeneratedTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                        echo "<th colspan=2>Status</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                $item_res = mysqli_query($link, "SELECT ti.tracked_item_id, i.item_name, ti.status FROM tracked_item ti, item i WHERE ti.item_id = i.item_id AND ti.user_id = " . $user_id . " AND i.item_classification_id = " . $ic['id']);
                                while ($item_row = mysqli_fetch_array($item_res)) {
                                    echo "<tr>";
                                        echo "<td>" . $item_row['item_name'] . "</td>";
                                        if ($item_row['status'] == 'Completed')
                                            echo "<td colspan=2>" . $item_row['status'] . "</td>";
                                        else {
                                            echo "<td>" . $item_row['status'] . "</td>";
                                            echo "<td width=5%><a class='progress-btn' id='item_row".$item_row['tracked_item_id']."' onclick='markAsComplete(this.id, ".$item_row['tracked_item_id'].")'><span class='progress-btn-span glyphicon glyphicon-ok' style='float:right'></span></a></td>";
                                        }
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                        echo "</center>";
                        echo "</div>";
                    }
                ?>
                </div>
                <div class="col-lg-3">
                <?php
                    echo '<div class="c100 p'.$item_classifications[0]['completion'].' big" id="percentageRing">';
                    echo "<span id='percentageRing_val'>".$item_classifications[0]['completion']."%</span>";
                ?>
                    <div class="slice">
                        <div class="bar"></div>
                        <div class="fill"></div>
                    </div>
                </div>
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
