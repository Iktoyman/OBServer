<!DOCTYPE html>
<html lang="en">
<?php
    require "connect.php";
    require "../../connect.php";
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

    $team = $_GET['team'];
    // Get accounts under team
    $accounts = array();
    $acct_res = mysqli_query($link, "SELECT account_id, account_acronym, account_name FROM account WHERE team_id = $team ORDER BY account_acronym");
    while ($acct = mysqli_fetch_array($acct_res))
        $accounts[] = $acct;

    $team_name = mysqli_fetch_assoc(mysqli_query($link, "SELECT team_name FROM team WHERE team_id = $team"))['team_name'];

    $item_classifications = array();
    $ic_res = mysqli_query($link, "SELECT item_classification_id, item_classification_name, icon_path FROM item_classification WHERE (account_id IN (SELECT account_id FROM account WHERE team_id = $team) OR account_id IS NULL) AND type_id = 2");
    while ($ic_row = mysqli_fetch_array($ic_res))
        $item_classifications[] = $ic_row;

?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>OBServer - Access List</title>
    <link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon">
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/progress.css"/>
    <link rel="stylesheet" type="text/css" href="css/access_list.css"/>
    <link href="css/simple-sidebar.css" rel="stylesheet">

    <link rel="stylesheet" href="css/circle.css">

    <!-- online css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/access.js"></script>

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
        <a href="./" class="subtitleHeaderStyle"> Access </a>
    </header>


    <div id="wrapper">
    <?php
        require "sidebar.php";        
    ?>
        <!-- Page Content -->
        <div class="row">
            <div class="col-lg-12 page-tableHeader">
                <div class="progressHeader" id="progressHeader">
                    <?php echo $team_name; ?> Access List
                </div>
            </div>
            <div>
                <div class='add-selection-list-div'>
                    <ul class='add-selection-list'>
                        <li><a class='add-item-btn' id='itemclass_'><span class='glyphicon glyphicon-plus'></span>&nbsp; Add New Item under existing Classification </a></li>
                        <li><a class='edit-all-items' id='edit-all-items'><span class='glyphicon glyphicon-pencil'></span>&nbsp; Toggle Edit Items </a></li>
                        <li><a class='add-item-btn' id='itemclass_new'><span class='glyphicon glyphicon-plus'></span>&nbsp; Add New Item under New Classification </a></li>
                    </ul>
                </div>
                <table class='GeneratedTable employee-progress-table'>
                    <thead>
                        <tr>
                            <th width=27.5%> Access Classification </th>
                            <th width=72.5%> Access Details </th>
                        </tr>
                        <tr>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        for ($x = 0; $x < sizeof($item_classifications); $x++) {
                            if (isset($_SESSION['ob_manager_id']) || (isset($_SESSION['ob_trainer_id']) && in_array($item_classifications[$x]['item_classification_id'], explode(',', $_SESSION['ob_trainer_items']))))
                                $item_count = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(item_id) AS ct FROM item WHERE item_classification_id = " . $item_classifications[$x]['item_classification_id']))['ct'] + 1;
                            else
                                $item_count = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(item_id) AS ct FROM item WHERE item_classification_id = " . $item_classifications[$x]['item_classification_id']))['ct'];
                            echo "<tr>"
                            . "<td class='item-classification-td' style='border-right: 0' rowspan=$item_count><img height='90px' width='90px' src='" . $item_classifications[$x]['icon_path'] . "'><br>" 
                            . "<span class='class-name' id='class-name_" . $item_classifications[$x]['item_classification_id'] . "'>" . $item_classifications[$x]['item_classification_name'] . "</span>"
                            . "<div class='class-name-input-div' id='class-name-input-div_" . $item_classifications[$x]['item_classification_id'] . "'>"
                                . "<input type='text' class='class-name-input' id='class-name-input_" . $item_classifications[$x]['item_classification_id'] . "'><br>"
                                . "<a class='save-class-name' id='save-class-name_" . $item_classifications[$x]['item_classification_id'] . "' onclick='saveClassName(this.id)'>Save</a>&nbsp;&nbsp;&nbsp;&nbsp;"
                                . "<a class='cancel-class-name' id='cancel-class-name_" . $item_classifications[$x]['item_classification_id'] . "' onclick='cancelEditClassName(this.id)'>Cancel</a>"
                            . "</div>";
                            if (isset($_SESSION['ob_manager_id']) || (isset($_SESSION['ob_trainer_id']) && in_array($item_classifications[$x]['item_classification_id'], explode(',', $_SESSION['ob_trainer_items'])))) {
                                echo "<div class='class-edit-btn-div'><a class='class-edit-btn' id='class-edit_" . $item_classifications[$x]['item_classification_id'] . "'>" 
                                    . "<span class='glyphicon glyphicon-pencil' data-toggle='tooltip' data-placement='top' title='Edit Item Classification Name'></span>"
                                    . "</a></div>";
                            }
                            echo "</td>";

                            $item_res = mysqli_query($link, "SELECT item_id, item_name FROM item WHERE item_classification_id = " . $item_classifications[$x]['item_classification_id']);
                            $a = 0;
                            while ($item_row = mysqli_fetch_array($item_res)) {
                                // Search for KMS link
                                /*
                                $kms_res = mysqli_query($link, "SELECT kms_link FROM kms_training WHERE item_id = " . $item_row['item_id']);
                                if (mysqli_num_rows($kms_res))
                                    $kms_url = mysqli_fetch_assoc($kms_res)['kms_link'];
                                else 
                                    $kms_url = '';
                                */

                                if ($a > 0)
                                    echo "<tr>";
                                echo "<td class='item-td' id='item-td" . $item_row['item_id'] . "'>";
                                /*
                                if ($kms_url != '')
                                    echo "<span id='itemname_".$item_row['item_id']."'><a href='$kms_url' target='_blank'>" . $item_row['item_name'] . "</a></span>";
                                else
                                    echo "<span id='itemname_".$item_row['item_id']."'>" . $item_row['item_name'] . "</span>";
                                */
                                echo "<span id='itemname_" . $item_row['item_id'] . "'>" . $item_row['item_name'] . "</span>";
                                if (isset($_SESSION['ob_manager_id']) || (isset($_SESSION['ob_trainer_id']) && in_array($item_classifications[$x]['item_classification_id'], explode(',', $_SESSION['ob_trainer_items'])))) {
                                    echo "<span class='edit-span' id='item_".$item_row['item_id']."'>"
                                    . "<a class='edit-btn'><span class='glyphicon glyphicon-pencil' data-toggle='tooltip' data-placement='top' title='Edit Access item'></span></a>&nbsp;&nbsp;"
                                    . "<a class='delete-btn'><span class='glyphicon glyphicon-trash' data-toggle='tooltip' data-placement='top' title='Delete Access item'></span></a>"
                                    . "</span>";
                                }
                                echo "</td>";
                                echo '<td class="edit-td" id="edit-td' . $item_row['item_id'] . '">' 
                                . '<span id="itemname_' . $item_row['item_id'] . '">Name: <input type="text" name="edit-name" class="edit-name" id="edit-name' . $item_row['item_id'] . '" value="' . $item_row['item_name'] . '"></span><br><br>'
                                //. '<span id="itemlink_' . $item_row['item_id'] . '">Link: &nbsp;&nbsp;<input type="text" name="edit-link" class="edit-link" id="edit-link' . $item_row['item_id'] . '" value="' . $kms_url . '"></span>'
                                . '<span class="save-span" id="save_item_' . $item_row['item_id'] . '">'
                                . '<a class="save-edit-btn" id="save-edit-btn' . $item_row['item_id'] . '" onclick="saveEdit(this.id)"><span class="glyphicon glyphicon-ok" data-toggle="tooltip" data-placement="top" title="Save Changes"></span></a>&nbsp;&nbsp;'
                                . '<a class="cancel-edit-btn" id="cancel-edit-btn' . $item_row['item_id'] . '" onclick="cancelEdit(this.id)"><span class="glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="top" title="Cancel Changes"></span></a></span>'
                                . '</td>';
                                echo "</tr>";
                            }
                            if (isset($_SESSION['ob_manager_id']) || (isset($_SESSION['ob_trainer_id']) && in_array($item_classifications[$x]['item_classification_id'], explode(',', $_SESSION['ob_trainer_items']))))
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
                <h4 class="modal-title" id="myModalLabel" style="color: black;"> Add New Access Item </h4>
            </div> 
            <div class="modal-body" style="color: black;">
                <table class='add-item-table'>
                    <tr>
                        <td width=50%>
                            <label> Access Classification </label><br>
                            <select name='access_class_select' id='access_class_select'>
                                <option value=''> -- Select Classification -- </option>
                            <?php
                                for ($x = 0; $x < sizeof($item_classifications); $x++) {
                                    if (isset($_SESSION['ob_manager_id']) || (isset($_SESSION['ob_trainer_id']) && in_array($item_classifications[$x]['item_classification_id'], explode(',', $_SESSION['ob_trainer_items']))))
                                        echo "<option value=" . $item_classifications[$x]['item_classification_id'] . ">" . $item_classifications[$x]['item_classification_name'] . "</option>";   
                                }
                            ?>
                                <option value='new'> + Create New Classification</option>
                            </select>
                        </td>
                        <td class='team-specific-option-td'>
                            <label> Classification Name </label><br>
                            <input type='text' name='new_classification_name' id='new_classification_name' placeholder="Item Classification Name" />
                        </td>
                    </tr>

                    <tr>
                        <td width=50% class='team-specific-option-td'>
                            <label> Access Type </label><br>
                            <select name='access_type' id='access_type'>
                                <option value='gen'> General (Applicable to entire Organization) </option>
                                <option value='team'> Team-Specific </option>
                            </select>
                        </td>
                        <td class='team-specific-option-subtd'>
                            <label> Team Accounts </label><br>
                            <select name='team_accounts' id='team_accounts'>
                            <?php
                                for ($a = 0; $a < sizeof($accounts); $a++) {
                                    echo "<option value=" . $accounts[$a]['account_id'] . ">" . $accounts[$a]['account_acronym'] . " - " . $accounts[$a]['account_name'] . "</option>";
                                }
                            ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td colspan=2 class='team-specific-option-td'>
                            <label> Classification Logo </label><br>
                            <input type='text' name='logo_name' id='logo_name' placeholder="Enter logo filename. Do not use any special characters or spaces. Ex: teamERM" />
                            <ul class='logo-selection-list'>
                                <br><br><br>
                                <input type='radio' name='class_logo' id='class_logo' value='img/certifications.png'> <img src='img/certifications.png' width=64px> </input>
                                <input type='radio' name='class_logo' id='class_logo' value='img/eaoAccli.png'> <img src='img/eaoAccli.png' width=64px> </input>
                                <input type='radio' name='class_logo' id='class_logo' value='img/genOnboard.png'> <img src='img/genOnboard.png' width=64px> </input>
                                <input type='radio' name='class_logo' id='class_logo' value='img/techTraining.png'> <img src='img/techTraining.png' width=64px> </input>
                                <br><br><br>
                                <input type='radio' name='class_logo' id='class_logo' value='img/nestleExams.png'> <img src='img/nestleExams.png' width=64px> </input>
                                <input type='radio' name='class_logo' id='class_logo' value='img/nestleOnboard.png'> <img src='img/nestleOnboard.png' width=64px> </input>
                                <input type='radio' name='class_logo' id='class_logo' value='img/name.png'> <img src='img/name.png' width=64px> </input>
                                <input type='radio' name='class_logo' id='class_logo' value='img/team.png'> <img src='img/team.png' width=64px> </input>
                            </ul>
                        </td>
                    </tr>

                    <tr>
                        <td width=50%>
                            <label> Access Name </label> <br>
                            <input type='text' name='access_name' id='access_name' placeholder='Access Name' />
                        </td>
                        <td> </td>
                        <!--
                        <td>
                            <label> Link to KMS </label> <br>
                            <input type='text' name='access_link' id='access_link' placeholder='KMS Entry URL' />
                        </td>
                        -->
                    </tr>

                    <tr>
                        <td>
                            <label> Days Before Completion </label> <br>
                            <input type='number' name='days_completion' id='days_completion' value=0 />
                        </td>
                        <td></td>
                    </tr>
                </table>
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
