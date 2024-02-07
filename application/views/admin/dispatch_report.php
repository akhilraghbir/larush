<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Month</label>
                                <input type="month" class="form-control" id="month" name="month">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Fe - Non Fe Dispatch Report</h4>
                                <canvas id="bar" height="300"></canvas>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function getdata() {
        var month = $("#month").val();
        var user_id = $("#user_id").val();
        $('#expensesList').DataTable({
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
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>EmployeeExpenseReport/ajaxListing",
                "type": 'POST',
                'data': {month:month,user_id:user_id}
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
