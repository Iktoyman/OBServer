<!-- Sidebar -->
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <?php
            if (isset($_SESSION['ob_manager_id'])) {
            echo '<li style="margin-top: 0.5vw">'
                . '<a href="../add_user.php">Add New Employee</a>'
                . '</li>'
                . '<hr>';
            }
        ?>
        <li>
            <h4> Trainings </h4>
        </li>
        <li>
            <a href="../training/">Dashboard</a>
        </li>
        <li>
            <?php echo "<a href='../training/training_list.php?team=".$handled_team['team_id']."'>Training List</a>"; ?>
        </li>
        <?php
        if (isset($_SESSION['ob_manager_id']) || isset($_SESSION['ob_trainer_id'])) {
        echo '<li>'
            . '<a href="../training/manager_view.php">Manager View</a>'
            . '</li>';
        }
        ?>
        <hr>
        <li>
            <h4> Accesses </h4>
        </li>
        <li>
            <a href="./">Dashboard</a>
        </li>
        <li>
            <?php echo "<a href='access_list.php?team=" . $handled_team['team_id'] . "'>Access List</a>"; ?>
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