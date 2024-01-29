<div class="col-md-12"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
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
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>stockreport/ajaxListing",
                "type": 'POST',
                'data': {}
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
