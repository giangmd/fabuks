
            <div class="footer">
                <div class="footer-inner">
                    <div class="footer-content">
                        <span class="bigger-120">
                            <span class="blue bolder">{{ config('app.name') }}</span>
                            App &copy; 2020
                        </span>
                    </div>
                </div>
            </div>

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->
        <script src="{{ asset('/admin/assets/js/jquery-2.1.4.min.js') }}"></script>

        <!-- <![endif]-->

        <!--[if IE]>
        <script src="{{ asset('/admin/assets/js/jquery-1.11.3.min.js') }}"></script>
        <![endif]-->
        <script src="{{ asset('/admin/assets/js/bootstrap.min.js') }}"></script>

        <!-- page specific plugin scripts -->

        <!--[if lte IE 8]>
          <script src="{{ asset('/admin/assets/js/excanvas.min.js') }}"></script>
        <![endif]-->
        <script src="{{ asset('/admin/assets/js/jquery-ui.custom.min.js') }}"></script>
        <script src="{{ asset('/admin/assets/js/jquery.ui.touch-punch.min.js') }}"></script>
        <script src="{{ asset('/admin/assets/js/jquery.easypiechart.min.js') }}"></script>
        <script src="{{ asset('/admin/assets/js/jquery.sparkline.index.min.js') }}"></script>
        <script src="{{ asset('/admin/assets/js/jquery.flot.min.js') }}"></script>
        <script src="{{ asset('/admin/assets/js/jquery.flot.pie.min.js') }}"></script>
        <script src="{{ asset('/admin/assets/js/jquery.flot.resize.min.js') }}"></script>

        <!-- ace scripts -->
        <script src="{{ asset('/admin/assets/js/ace-elements.min.js') }}"></script>
        <script src="{{ asset('/admin/assets/js/ace.min.js') }}"></script>

    </body>
</html>
