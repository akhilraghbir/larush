<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="last name" class="">Select Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control daterange" id="daterange">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Employee</label>
                                <select class="form-control" id="user_id" name="user_id">
                                    <option value="">Select Employee</option>
                                    <?php if(!empty($employees)){
                                        foreach($employees as $employee){
                                    ?>
                                    <option value="<?= $employee['id']; ?>"><?= $employee['first_name']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive tasks dataGridTable">
                        <table id="attendanceList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S No</th>
                                    <th>Employee</th>
                                    <th>Date</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Hours</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-lg fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Attendance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateAttendance" method="post">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <label>Date</label>
                    <input type="date" name="date" readonly id="date" class="form-control">
                    <input type="hidden" name="id" id="id">
                </div>
                <div class="col-md-4">
                    <label>Clock In</label>
                    <input type="datetime-local" name="clock_in" id="clock_in" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Clock Out</label>
                    <input type="datetime-local" name="clock_out" id="clock_out" class="form-control">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" onclick="updateAttendance()" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
    function getdata() {
        var date = $("#daterange").val();
        var user_id = $("#user_id").val();
        $('#attendanceList').DataTable({
            "destroy": true,
            "responsive": false,
            "dom": 'Bfrtip',
            "processing": true,
            "serverSide": true,
            "order": [
                [1, "desc"]
            ],
            buttons: ["copy", "csv", "pdf"],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>AttendanceReport/ajaxListing",
                "type": 'POST',
                'data': {date:date,user_id:user_id}
            },
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        });
    }
    getdata();
    function getAttendance(id=''){
        if(id!=''){
            $.ajax({
                url: '<?php echo base_url(); ?>administrator/attendanceReport/getAttendance',
                type: 'POST',
                data: {"id": id},
                success: function(data) {
                    var result = JSON.parse(data);
                    if (result.error == '0') {
                        $("#id").val(result.data.id);
                        $("#date").val(result.data.date);
                        $("#clock_in").val(result.data.clock_in);
                        $("#clock_out").val(result.data.clock_out);
                        $("#staticBackdrop").modal('show');
                    } else {
                        console.log(result);
                    }
                },
                error: function(e) {
                    console.log(e.message);
                }
            });
        }
    }
    function updateAttendance(){
        var data = $("#updateAttendance").serialize();
        $.ajax({
            url: '<?php echo base_url(); ?>administrator/attendanceReport/updateAttendance',
            type: 'POST',
            data: data,
            success: function(data) {
                var result = JSON.parse(data);
                if (result.error == '0') {
                    getdata();
                    toastr['success'](result.msg);
                    $("#staticBackdrop").modal('hide');
                } else {
                    toastr['warning'](result.msg);
                }
            },
            error: function(e) {
                console.log(e.message);
            }
        });
    }
</script>
