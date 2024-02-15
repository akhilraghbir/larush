<div class="col-md-12"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="last name" class="">Select Warehouse <span class="text-danger">*</span></label>
                                <select name="warehouse" id="warehouse" class="form-control">
                                    <option value="All">All</option>
                                    <?php foreach($warehouses as $warehouse){ ?>
                                        <option value="<?= $warehouse['id'];?>"><?= $warehouse['warehouse_name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="last name" class="">Select Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="daterange">
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Search">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive tasks dataGridTable">
                        <table id="stockReportList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Product Name</th>
                                    <th>Units</th>
                                    <th>Available Stock</th>
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
        var warehouse = $("#warehouse").val();
        var date = $("#daterange").val();
        var clist = $('#stockReportList').DataTable({
            "destroy": true,
            "dom": 'Bfrtip',
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [1, "asc"]
            ],
            lengthChange: !1,
            buttons: ["copy", "csv", "pdf"],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>StockReport/ajaxListing",
                "type": 'POST',
                'data': {warehouse:warehouse,date:date}
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
