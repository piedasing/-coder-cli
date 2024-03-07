<?php
include('_config.php');

?>
<!DOCTYPE html>
<html>
    <head>
		<?php include('../_head.php'); ?>
    </head>
    <body>
		<?php include('../_navbar.php'); ?>
        <!-- BEGIN Container -->
        <div class="container" id="main-container">
			<?php include('../_left.php'); ?>
            <!-- BEGIN Content -->
            <div id="main-content">
                <div id="app" v-cloak>
                    <!-- BEGIN Page Title -->
                    <div class="page-title">
                        <div>
                            <h1><i class="icon-home"></i> 首頁資訊</h1>
                            <h4><?php echo $page_desc; ?></h4>
                        </div>
                    </div>
                    <!-- END Page Title -->
    
                    <!-- BEGIN Breadcrumb -->
                    <div id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li class="active"><i class="icon-home"></i> Home</li>
                        </ul>
                    </div>
                    <!-- END Breadcrumb -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tile">
                                <p class="title"><?php echo $adminuser['name']; ?> - 歡迎使用本系統</p>
                                <p style="margin-top: 10px;">
                                    <img src="<?php echo $adminuser['pic']; ?>" onerror="this.src='../images/avatar.png'" style="float: left; width: 30px; height: 30px; border-radius: 100%; object-fit: cover;">
                                    <div style="float: left; margin: 5px;">
                                        <?php echo '您本次登入時間為:' . $adminuser['time'] . '<br>登入IP:' . request_ip() . '<br><li class="icon-smile"> ' . coderAdmin::sayHello() . '</li>'; ?>
                                    </div>
                                    <div class="clearfix"></div>
                                </p>
                                <div class="img img-bottom">
                                    <i class="icon-desktop"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('../footer.php'); ?>

                <a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="icon-chevron-up"></i></a>
            </div>
            <!-- END Content -->
        </div>
        <!-- END Container -->

        <?php include('../_js.php'); ?>
        <script src="../assets/vue.min.js"></script>
        <script>
            new Vue({
                el: '#app',
                data() {
                    return {
                        chartFrameHeight: '100%'
                    };
                },
                mounted() {
                    const _self = this;
                    window.addEventListener('message', function (evt) {
                        try {
                            const { event, data } = evt.data;
                            if (event !== 'update:size') {
                                return;
                            }
                            _self.chartFrameHeight = `${data.height}px`;
                        } catch(error) {}
                    });
                },
            });
        </script>
    </body>
</html>
