<!DOCTYPE html>
<html lang="en">
<?php
    require "connect.php";
    require "../../connect.php";
    session_start();
    require "establish_user.php";

    if (isset($_GET['user'])) {
        $manager_view = 1;
        $user_id = $_GET['user'];
        $emp_name = mysqli_fetch_assoc(mysqli_query($link, "SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = " . $user_id))['name'];
    }
    else {
        $manager_view = 0;
        $user_id = $_SESSION['ob_uid'];
    }

    $team = mysqli_fetch_assoc(mysqli_query($link, "SELECT team_id FROM users WHERE user_id = $user_id"))['team_id'];

    $item_classifications = array();
    $ic_res = mysqli_query($link, "SELECT item_classification_id, item_classification_name, icon_path FROM item_classification WHERE (account_id IN (SELECT account_id FROM account WHERE team_id = $team) OR account_id IS NULL) AND type_id = 1");
    $i = 0;
    while ($ic_row = mysqli_fetch_array($ic_res)) {
        $item_classifications[$i]['id'] = $ic_row['item_classification_id'];
        $item_classifications[$i]['name'] = $ic_row['item_classification_name'];
        $ic_id = substr($ic_row['icon_path'], 4);
        $ic_id = substr($ic_id, 0, strpos($ic_id, '.'));
        $item_classifications[$i]['element_id'] = $ic_id;

        $count_res1 = mysqli_query($link, "SELECT COUNT(ti.tracked_item_id) as ct FROM tracked_item ti, item i WHERE ti.item_id = i.item_id AND ti.user_id = " . $user_id . " AND i.item_classification_id = " . $ic_row['item_classification_id'] . " AND (ti.status = 'Completed' OR ti.status = 'N/A')");
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
    
    <title>OBServer - Training Progress</title>
    <link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon">
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/progress.css"/>
    <link href="css/simple-sidebar.css" rel="stylesheet">

    <link rel="stylesheet" href="css/circle.css">

    <!-- online css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/datepicker.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" /> -->

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/datepicker.min.js"></script>

    <script>
        var current_div = '<?php echo $_GET['id']; ?>';
        var manager_view = <?php echo $manager_view; ?>;
        var current_user = <?php echo $user_id; ?>;
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
        <a href="#menu-toggle" class="titleHeaderStyle" id="menu-toggle">&#9776;</a> &nbsp; <span class="titleHeaderstyle"><a href="../" class="titleHeaderStyle">OBserver</a></span> 
        <span class="glyphicon glyphicon-chevron-right headerChevron"></span>
        <a href="./" class="subtitleHeaderStyle"> Training </a>
    </header>


    <div id="wrapper">
    <?php
        require "sidebar.php";        
    ?>
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
                <div style='overflow: auto;'>
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
                <div class="col-lg-9" style="background-color: #E3E3E3; height: 50%; margin-bottom: 5%">
                <?php
                    foreach ($item_classifications as $ic) {
                        echo "<div id='" . $ic['element_id'] . "Div' style='padding:10px'>"
                        . "<center>"
                            . "<table class='GeneratedTable'>"
                                . "<thead>"
                                    . "<tr>"
                                        . "<th width=3%></th>"
                                        . "<th width=67%>Title</th>"
                                        . "<th width=30% colspan=2>Status</th>"
                                    . "</tr>"
                                . "</thead>"
                                . "<tbody>";
                                $item_res = mysqli_query($link, "SELECT ti.tracked_item_id, i.item_name, ti.completion_date, ti.status FROM tracked_item ti, item i WHERE ti.item_id = i.item_id AND ti.user_id = " . $user_id . " AND i.item_classification_id = " . $ic['id']);
                                while ($item_row = mysqli_fetch_array($item_res)) {
                                    echo "<tr>";
                                        echo "<td>";
                                        if ($item_row['status'] != 'Completed')
                                            echo "<input type='checkbox' class='progress-selection-checkbox' id='ps-chkbox_" . $item_row['tracked_item_id'] . "' />";
                                        echo "</td>";
                                        echo "<td>" . $item_row['item_name'] . "</td>";
                                        
                                        if ($item_row['status'] == 'Completed') {
                                            echo "<td colspan=2>" . $item_row['status'] . " on " . date('M j, Y', strtotime($item_row['completion_date']));
                                            if (isset($_SESSION['ob_manager_id']) || (isset($_SESSION['ob_trainer_id']) && in_array($ic['id'], explode(',', $_SESSION['ob_trainer_items']))) || $_SESSION['ob_uid'] == $user_id) {
                                            echo "<a class='edit-date-btn' id='edit-date_" . $item_row['tracked_item_id'] . "' onclick='editCompletionDate(this.id)'>"
                                                . "<span class='edit-date-btn glyphicon glyphicon-pencil' data-toggle='tooltip' data-placement='top' title='Correct completion date'></span>"
                                            . "</a>";
                                            }
                                            echo "<div class='edit-datepicker-div' id='edit-datepicker-div_" . $item_row['tracked_item_id'] . "'>"
                                                . "<input type='text' class='edit-datepicker' id='edit-datepicker_" . $item_row['tracked_item_id'] . "' value='" . date('Y-m-d', strtotime($item_row['completion_date'])) . "' >&nbsp;&nbsp;"
                                                . "<a class='edit-save-date-btn' data-toggle='tooltip' data-placement='top' title='Save changes'>Save</a>&nbsp;&nbsp;"
                                                . "<a class='edit-cancel-date-btn' data-toggle='tooltip' data-placement='top' title='Cancel changes'>Cancel</a>"
                                                . "</div>"
                                            . "</td>";
                                        }
                                        else if ($item_row['status'] == 'N/A')
                                            echo "<td colspan=2>" . $item_row['status'] ."&nbsp;&nbsp;"
                                                . "<a class='progress-btn' style='float: right' id='item_row".$item_row['tracked_item_id']."' onclick='markAsComplete(this.id, ".$item_row['tracked_item_id'].")'>"
                                                . "<span class='progress-btn-span glyphicon glyphicon-ok' data-toggle='tooltip' data-placement='top' title='Mark this item as Completed'></span>"
                                                . "</a>"
                                                ."</td>";
                                        else {
                                            if (isset($_SESSION['ob_manager_id']) || (isset($_SESSION['ob_trainer_id']) && in_array($ic['id'], explode(',', $_SESSION['ob_trainer_items']))) || $_SESSION['ob_uid'] == $user_id) {
                                                echo "<td>" . $item_row['status'] . "</td>";
                                                echo "<td width=7.5%>"
                                                . "<a class='progress-btn' id='item_row".$item_row['tracked_item_id']."' onclick='markAsComplete(this.id, ".$item_row['tracked_item_id'].")'>"
                                                . "<span class='progress-btn-span glyphicon glyphicon-ok' data-toggle='tooltip' data-placement='top' title='Mark this item as Completed'></span>"
                                                . "</a>&nbsp;&nbsp;"
                                                . "<a class='progress-btn' id='item_row".$item_row['tracked_item_id']."' onclick='markAsNotApplicable(this.id)'>"
                                                . "<span class='progress-btn-span glyphicon glyphicon-ban-circle' data-toggle='tooltip' data-placement='top' title='Mark this item as Not Applicable'></span>"
                                                . "</a>"
                                                . "</td>";
                                            }
                                            else 
                                                echo "<td colspan=2>" . $item_row['status'] . "</td>";
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

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover();
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
