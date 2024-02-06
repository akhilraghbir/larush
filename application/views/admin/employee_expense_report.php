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
                        <table id="expensesList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Category</th>
                                    <th>Purpose</th>
                                    <th>Amount</th>
                                    <th>Expense Date</th>
                                    <th>Created On</th>
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
