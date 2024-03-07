<!-- BEGIN Sidebar -->
<div id="sidebar" class="navbar-collapse collapse">
    <!-- BEGIN Navlist -->
    <ul class="nav nav-list">
        <li>
            <a href="../home/index.php">
                <i class="icon-home"></i>
                <span>首頁</span>
            </a>
        </li>
        <?php coderAdmin::drawMenu(); ?>
        <?php if (coderAdmin::isGodAdmin()) { ?>
        <?php } ?>
    </ul>
    <!-- END Navlist -->

    <!-- BEGIN Sidebar Collapse Button -->
    <div id="sidebar-collapse" class="visible-lg">
        <i class="icon-double-angle-left"></i>
    </div>
    <!-- END Sidebar Collapse Button -->
</div>
<!-- END Sidebar -->
