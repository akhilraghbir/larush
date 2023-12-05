<div class="col-lg-12">
    <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
            <div class="col-md-12">
                <ul class="list-view">
                    <?php if(!empty($notifications)){
                      foreach($notifications as $notif){ ?>
                    <li class="notifDiv notif-<?= $notif['id'];?>">
                        <div class="card list-view-media">
                            <div class="card-block">
                                 <div class="media">
                                    <div class="media-body">
                                        <div class="col-xs-12">
                                            <h6 class="d-inline-block"> <?= $notif['notif_message'];?></h6>
                                            <div class="f-13 text-muted m-b-15"><?= $notif['created_on'];?></div>
                                        </div>
                                        <p><?= $notif['message']; ?></p>
                                        <?php if($notif['status'] == 'Active' && $this->session->user_type == 'Admin'){ ?>
                                        <div class="m-t-15">
                                            <button type="button" onclick="changeStatus(<?= $notif['id'];?>,<?= $notif['sender'];?>,<?= $notif['reciever'];?>,<?= $notif['ref_id'];?>,'accept')" data-toggle="tooltip" title="" class="btn btn-success btn waves-effect waves-light" data-original-title="Accept">
                                                <span class="icon-check"></span> Accept
                                            </button>
                                            <button type="button" onclick="changeStatus(<?= $notif['id'];?>,<?= $notif['sender'];?>,<?= $notif['reciever'];?>,<?= $notif['ref_id'];?>,'reject')" data-toggle="tooltip" title="" class="btn btn-danger btn waves-effect waves-light" data-original-title="Reject">
                                                <span class="icon-close"></span> Reject
                                            </button>
                                        </div>
                                        <?php }else{ ?>
                                            <button type="button" onclick="markasread(<?= $notif['id'];?>)" data-toggle="tooltip" title="" class="btn btn-danger btn waves-effect waves-light" data-original-title="Mark as read">
                                                <span class="icon-envelope-open"></span> Mark as Read
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="nonotif d-none card list-view-media">
                            <div class="card-block">
                                 <div class="media">
                                    <div class="media-body">
                                        No Notification Found.....
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php } }else{ ?>
                    <li>
                        <div class="nonotif card list-view-media">
                            <div class="card-block">
                                 <div class="media">
                                    <div class="media-body">
                                        No Notification Found.....
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function changeStatus(notifid,sender,reciever,refid,status){
        $('#page-overlay1').hide();
        var TtMsg = 'Are you sure you want to ' + status + ' this request';
        $.confirm({
            title: TtMsg,
            buttons: {
                formSubmit: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function() {
                        $('#page-overlay').show();
                        $.ajax({
                            url: '<?php echo base_url(); ?>administrator/notifications/updateStatus',
                            type: 'POST',
                            data: {
                                "notifid": notifid,
                                "sender": sender,
                                "reciever": reciever,
                                "refid":refid,
                                "status":status
                            },
                            success: function(data) {
                                result = JSON.parse(data);
                                var msg = result.message;
                                if (result.error == '0') {
                                    notify('top', 'right', 'fa fa-success', 'success', 'animated fadeInDown', 'animated fadeOutDown',msg);
                                    $('.notif-'+notifid).remove();
                                    getNotifCount();
                                    var c = $(".notifDiv").length;
                                    if(c == 0){
                                        $(".nonotif").removeClass('d-none');
                                    }
                                } else {
                                    notify('top', 'right', 'fa fa-danger', 'danger', 'animated fadeInDown', 'animated fadeOutDown',msg);
                                }
                            },
                            error: function(e) {
                                notify('top', 'right', 'fa fa-danger','danger',  'animated fadeInDown', 'animated fadeOutDown',e.msg);
                            }
                        });
                    }
                },
                no: function() {
                    //close
                    $('#page-overlay').hide();
                },
            },
            onContentReady: function() {
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function(e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    }
    
    function markasread(notifid,sender,reciever,refid,status){
        $('#page-overlay1').hide();
        var TtMsg = 'Are you sure you want to mark as read this notification';
        $.confirm({
            title: TtMsg,
            buttons: {
                formSubmit: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function() {
                        $('#page-overlay').show();
                        $.ajax({
                            url: '<?php echo base_url(); ?>administrator/notifications/markasread',
                            type: 'POST',
                            data: {
                                "notifid": notifid,
                            },
                            success: function(data) {
                                result = JSON.parse(data);
                                var msg = result.message;
                                if (result.error == '0') {
                                    notify('top', 'right', 'fa fa-success', 'success', 'animated fadeInDown', 'animated fadeOutDown',msg);
                                    $('.notif-'+notifid).remove();
                                    getNotifCount();
                                    var c = $(".notifDiv").length;
                                    if(c == 0){
                                        $(".nonotif").removeClass('d-none');
                                    }
                                } else {
                                    notify('top', 'right', 'fa fa-danger',  'animated fadeInDown', 'animated fadeOutDown',msg);
                                }
                            },
                            error: function(e) {
                                notify('top', 'right', 'fa fa-danger',  'animated fadeInDown', 'animated fadeOutDown',e.msg);
                            }
                        });
                    }
                },
                no: function() {
                    //close
                    $('#page-overlay').hide();
                },
            },
            onContentReady: function() {
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function(e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    }
    
</script>