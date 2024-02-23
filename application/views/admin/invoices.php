<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                            <label for="">Select Buyer</label>
                                <select name="buyer" id="buyer" class="form-control">
                                    <option value="All">All</option>
                                    <?php if(!empty($buyers)){ 
                                    foreach($buyers as $buyer){    
                                    ?>
                                    <option value="<?= $buyer['id'];?>"><?= $buyer['company_name'];?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="last name" class="">Select Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control daterange" id="daterange">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Search">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive tasks dataGridTable">
                        <table id="invoicesList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Invoice Number</th>
                                    <th>Company Name</th>
                                    <th>Dispacth Date</th>
                                    <th>Created On</th>
                                    <th>Action</th>
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
        var buyer = $("#buyer").val();
        $('#invoicesList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [4, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>invoices/ajaxListing",
                "type": 'POST',
                'data': {
                    date:date,
                    buyer:buyer
                }
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