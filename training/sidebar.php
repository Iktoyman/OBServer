<?php
    $handled_teams = array();
    // Check if manager, and get team if so.
    $is_manager = mysqli_query($link, "SELECT m.team_id, t.team_name FROM manager m, team t WHERE t.team_id = m.team_id AND m.user_id = " . $_SESSION['ob_uid']);
    if (mysqli_num_rows($is_manager)) {
        while ($team_row = mysqli_fetch_array($is_manager))
            $handled_teams[] = $team_row;
    }
    else {
        $employee_team = mysqli_query($link, "SELECT u.team_id, t.team_name FROM users u, team t WHERE t.team_id = u.team_id AND u.user_id = " . $_SESSION['ob_uid']);
        $handled_teams[0] = mysqli_fetch_array($employee_team);
    }
?>

<!-- Sidebar -->
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <?php
            if (isset($_SESSION['ob_manager_id'])) {
            echo '<li style="margin-top: 0.5vw">'
                . '<a href="../add_user.php">Add New Employee</a>'
                . '</li>'
                . '<li>'
                . '<a href="../manage_users.php">Manage Employees</a>'
                . '</li>'
                . '<hr>';
            }
        ?>
        <li>
            <h4> Trainings </h4>
        </li>
        <li>
            <a href="./">Dashboard</a>
        </li>
        <li>
            <?php echo "<a href='training_list.php?team=".$handled_teams[0]['team_id']."'>Training List</a>"; ?>
        </li>
        <?php
        if (isset($_SESSION['ob_manager_id']) || isset($_SESSION['ob_trainer_id'])) {
        echo '<li>'
            . '<a href="manager_view.php">Manager View</a>'
            . '</li>';
        }
        ?>
        <hr>
        <li>
            <h4> Accesses </h4>
        </li>
        <li>
            <a href="../access/">Dashboard</a>
        </li>
        <li>
            <?php echo "<a href='../access/access_list.php?team=".$handled_teams[0]['team_id']."'>Access List</a>"; ?>
        </li>
        <?php
        if (isset($_SESSION['ob_manager_id']) || isset($_SESSION['ob_trainer_id'])) {
        echo '<li>'
            . '<a href="../access/manager_view.php">Manager View</a>'
            . '</li>';
        }
        ?>
        <hr>
        <li>
            <h4> Skills </h4>
        </li>
        <li>
            <a href="">Dashboard</a>
        </li>
        <li>
            <a href="">Skills List</a>
        </li>
        <li>
            <a href="">Manager View</a>
        </li>
        <hr>
        <li>
            <h4> Connect </h4>
        </li>
        <li>
            <a href=".././connect"> Manager Connect </a>
        </li>
        <hr>
        <li>
            <a href='../../logout.php'> Logout </a>
        </li>
    </ul>
</div>
<!-- /#sidebar-wrapper -->