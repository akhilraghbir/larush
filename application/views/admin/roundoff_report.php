roundoff_report<div class="col-md-12">
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
                                <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive tasks dataGridTable">
                        <table id="roundOffList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S No</th>
                                    <th>Receipt No</th>
                                    <th>Created On</th>
                                    <th>Round Off</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function getdata() {
        var date = $("#daterange").val();
        $('#roundOffList').DataTable({
            "destroy": true,
            "responsive": false,
            "dom": 'Bfrtip',
            "processing": true,
            "serverSide": true,
            "order": [
                [2, "desc"]
            ],
            buttons: ["copy", "csv", "pdf"],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>RoundOffReport/ajaxListing",
                "type": 'POST',
                'data': {date:date}
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

</script>
