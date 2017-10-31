<!DOCTYPE html>
<html lang="en">
<?php
    require "connect.php";
    require "../connect.php";
    session_start();

    // Check if manager, and get team if so.
    $is_manager = mysqli_query($link, "SELECT m.team_id, t.team_name FROM manager m, team t WHERE t.team_id = m.team_id AND m.user_id = " . $_SESSION['ob_uid']);
    if (mysqli_num_rows($is_manager)) {
        $handled_team = mysqli_fetch_array($is_manager);
    }

    $team = $_GET['team'];

    $item_classifications = array();
    $ic_res = mysqli_query($link, "SELECT item_classification_id, item_classification_name, icon_path FROM item_classification WHERE account_id IN (SELECT account_id FROM account WHERE team_id = " . $handled_team['team_id'] . ") OR account_id IS NULL");
    while ($ic_row = mysqli_fetch_array($ic_res))
        $item_classifications[] = $ic_row;

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
    <link rel="stylesheet" type="text/css" href="css/training_list.css"/>
    <link href="css/simple-sidebar.css" rel="stylesheet">

    <link rel="stylesheet" href="css/circle.css">

    <!-- online css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/trainings.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <header class="headerStyle">
        <a href="#menu-toggle" class="titleHeaderStyle" id="menu-toggle">&#9776;</a> &nbsp; <span class="titleHeaderstyle"><a href="../OBserver" class="titleHeaderStyle">OBserver</a></div>
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
                    <a href="#">Shortcuts</a>
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
        <div class="row">
            <div class="col-lg-12 page-tableHeader">
                <div class="progressHeader" id="progressHeader">
                    <?php echo $handled_team['team_name']; ?> Training List
                </div>
            </div>
            <div>
                <table class='GeneratedTable employee-progress-table'>
                    <thead>
                        <tr>
                            <th colspan=2 width=35%> Training Classification </th>
                            <th width=65%> Training Details </th>
                        </tr>
                        <tr>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        for ($x = 0; $x < sizeof($item_classifications); $x++) {
                            $item_count = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(item_id) AS ct FROM item WHERE item_classification_id = " . $item_classifications[$x]['item_classification_id']))['ct'] + 1;
                            echo "<tr>"
                            . "<td class='item-classification-td' rowspan=$item_count><img height='60px' width='60px' src='" . $item_classifications[$x]['icon_path'] . "'></td>"
                            . "<td class='item-classification-td' rowspan=$item_count>" . $item_classifications[$x]['item_classification_name'] . "</td>";
                            $item_res = mysqli_query($link, "SELECT item_id, item_name FROM item WHERE item_classification_id = " . $item_classifications[$x]['item_classification_id']);
                            $a = 0;
                            while ($item_row = mysqli_fetch_array($item_res)) {
                                if ($a > 0)
                                    echo "<tr>";
                                echo "<td>" . $item_row['item_name'] . "</td>";
                                echo "</tr>";
                            }
                            echo "<tr><td><a class='add-item-btn' id='itemclass_".$item_classifications[$x]['item_classification_id']."'><span class='glyphicon glyphicon-plus'></span>&nbsp;&nbsp; Add Item </a></td></tr>";
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

<!-- ADD ITEM MODAL -->
<div class="modal fade" id="add_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: right;width: 2%; background-color: #eee;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="color: black;"> Add New Training Item </h4>
            </div> 
            <div class="modal-body" style="color: black;">
                <label> Training Classification </label><br>
                <select name='training_class_select' id='training_class_select'>
                    <option value=''> -- Select Classification -- </option>
                <?php
                    for ($x = 0; $x < sizeof($item_classifications); $x++) {
                        echo "<option value=" . $item_classifications[$x]['item_classification_id'] . ">" . $item_classifications[$x]['item_classification_name'] . "</option>";   
                    }
                ?>
                </select>
                <br><br>
                <label> Training Name </label> <br>
                <input type='text' name='training_name' id='training_name' placeholder='Training Name' />
            </div>
            <div class="modal-footer">
                <button type="button" id="add-item_save" class="btn btn-primary">Save changes</button> 
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- END ADD ITEM MODAL -->

</html>
