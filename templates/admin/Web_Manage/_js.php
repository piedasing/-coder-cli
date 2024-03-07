
<!--basic scripts-->

<script type="text/javascript" src="../assets/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../assets/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="../assets/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="../assets/nicescroll/jquery.nicescroll.js"></script>
<script type="text/javascript" src="../assets/jquery-cookie/jquery.cookie.js"></script>
<script type="text/javascript" src="../assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="../assets/gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="../assets/jquery-loading/jquery-loading.min.js"></script>
<script type="text/javascript" src="../assets/colorbox/jquery.colorbox.js"></script>
<script type="text/javascript" src="../assets/chosen-bootstrap/chosen.jquery.min.js"></script>
<script src="<?php echo $nowHost != 'production' ? '../assets/vue/vue.js' : '../assets/vue/vue.min.js'; ?>"></script>
<!--page specific plugin scripts-->

<script>
    window.ZOOM_IN = 1;
    document.body.style.zoom = window.ZOOM_IN;
    document.body.style.setProperty("--zoom", ZOOM_IN);
</script>

<!--flaty scripts-->
<script type="text/javascript" src="../js/flaty.js"></script>
<!--select選單-->
<script type="text/javascript" src="../js/public.js"></script>
<script>
    if (self === top) {
        (function () {
            let timer = null
            window.onresize = () => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    $.fn.colorbox.resize({
                        width: (window.innerWidth * 0.95 / window.ZOOM_IN + 'px'),
                        height: (window.innerHeight * 0.95 / window.ZOOM_IN + 'px'),
                    });
                }, 500);
            }
        })()
    } else {
        $('html').addClass('isInIframe');
    }
</script>
