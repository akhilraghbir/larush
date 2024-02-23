</div>
<!-- End Page-content -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <?= date('Y'); ?> © <?= SITENAME; ?>.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
</footer>

</div>
<!-- end main content-->
</div>
<!--  Modal content for the above example -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title modalTitle" id="myLargeModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<script src="<?= base_url('assets/backend/'); ?>libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/backend/'); ?>libs/metismenu/metisMenu.min.js"></script>
<script src="<?= base_url('assets/backend/'); ?>libs/simplebar/simplebar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js" integrity="sha512-zP5W8791v1A6FToy+viyoyUUyjCzx+4K8XZCKzW28AnCoepPNIXecxh9mvGuy3Rt78OzEsU+VCvcObwAMvBAww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= base_url('assets/backend/'); ?>js/common.js"></script>
<script src="<?= base_url('assets/backend/'); ?>js/app.js"></script>


<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<script>
function getNotifCount(){
    $.ajax({
        url: '<?php echo base_url(); ?>administrator/dashboard/getNotifCount',
        type: 'POST',
        success: function(data) {
            result = JSON.parse(data);
            var msg = result.message;
            if (result.error == '0' && result.count>0) {
                $(".noti-dot").css('display','block');
            } else {
                $(".noti-dot").css('display','none');
            }
        }
    });
}
function getNotifications(){
    $.ajax({
        url: '<?php echo base_url(); ?>administrator/dashboard/getNotifications',
        type: 'POST',
        success: function(data) {
            result = JSON.parse(data);
            var msg = result.message;
            if (result.error == '0') {
                $(".notifications-dropdown .simplebar-content").html(result.html);
            } else {
                console.log(result.message);
            }
        }
    });
}
getNotifCount();
$(function() {
var start = moment().subtract(29, 'days');
var end = moment();
function cb(start, end) {
    $('.daterange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    //$('.daterange').val('');
}
$('.daterange').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
       'Last 3 Months': [moment().subtract(90, 'days'), moment()],
       'Last 6 Months': [moment().subtract(180, 'days'), moment()]
    }
}, cb);
cb(start, end);
});
</script>
</body>

</html>