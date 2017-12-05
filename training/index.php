<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>OBServer - Training</title>
    <link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon">
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

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
    require "../../connect.php";
    session_start();
    require "establish_user.php";

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
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <center>
                            <table style="margin-top: 3%">  
                            <?php
                                
                            $i = 0;
                            $team = $_SESSION['ob_team'];
                            $res = mysqli_query($link, "SELECT item_classification_id, item_classification_name, icon_path FROM item_classification WHERE (account_id IN (SELECT account_id FROM account WHERE team_id = " . $team . ") OR account_id IS NULL) AND type_id = 1");
                            while ($ic_row = mysqli_fetch_array($res)) {
                                $ic_id = substr($ic_row['icon_path'], 4);
                                $ic_id = substr($ic_id, 0, strpos($ic_id, '.'));
                                if ($i % 4 == 0)
                                    echo "<tr>";
                                echo "<td class='tdStyle' onmouseover=item_class_mo('".$ic_id."') onmouseout=item_class_moh('".$ic_id."') width=22.5%>";
                                    echo "<a href='progress.php?id=".$ic_id."'>";
                                        echo "<img src='" . $ic_row['icon_path'] . "' class='iconStyle' id='".$ic_id."'>";
                                        echo "<br><br>";
                                        echo "<center><span class='titleIconStyle'>" . $ic_row['item_classification_name'] . "</span></center>";
                                        $completed_qry = mysqli_query($link, "SELECT COUNT(ti.item_id) as completed FROM tracked_item ti, item i WHERE ti.item_id = i.item_id AND i.item_classification_id = " . $ic_row['item_classification_id'] . " AND ti.user_id = " . $_SESSION['ob_uid'] . " AND (ti.status = 'Completed' or ti.status = 'N/A')");
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
