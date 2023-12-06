<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<div class="col-md-12">
    <?php
    defined('BASEPATH') or exit('No direct script access allowed');
    if (isset($data)) {
        if (!empty($data)) {
            $formData = $data;
        } else {
            $formData = $this->form_validation->get_session_data();
        }
    } else {
        $formData = $this->form_validation->get_session_data();
    }
    ?>
    <?php if (isset($form_action)) { ?>
        <div class="">
            <div class="main-card mb-3 card card-body">
                <h5 class="card-title"></h5>
                <?php
                $url = CONFIG_SERVER_ADMIN_ROOT . "buyers/add";
                echo form_open($url, array('class' => 'userRegistration', 'id' => 'userRegistration')); ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="warehouse" class="">Select Warehouse <span class="text-danger">*</span></label>
                            <select name="warehouse_id" class="form-control">
                                <option value="">Select Warehouse</option>
                                <?php if (!empty($warehouses)) {
                                    foreach ($warehouses as $warehouse) {
                                ?>
                                        <option value="<?= $warehouse['id']; ?>"><?= $warehouse['warehouse_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="supplier" class="">Select Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-control">
                                <option value="">Select Supplier</option>
                                <?php if (!empty($suppliers)) {
                                    foreach ($suppliers as $supplier) {
                                ?>
                                        <option value="<?= $supplier['id']; ?>"><?= $supplier['supplier_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="date" class="">Receipt Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="receipt_date">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <input type="text" id="product" class="form-control" placeholder="Enter Product Name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </thead>
                            <tbody class="purchase_body">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <input type="submit" name="add" class="mt-2 btn btn-primary pull-right" value="Submit">
                </div>
                </form>
            </div>
        </div>
        <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $('#product').keyup(function() {
                product_search();
            });

            function product_search() {
                $('#product').autocomplete({
                    source: function(request, response) {
                        var input = this.element;
                        $.ajax({
                            url: base_url + 'administrator/inventory/getProducts',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                term: request.term,
                            },
                            success: function(data) {
                                if (data.length > '0') {
                                    response($.map(data, function(item) {
                                        Object.values(item);
                                        return {
                                            id: item.id,
                                            value: item.name
                                        };
                                    }));
                                }
                            }
                        });
                    },
                    select: function(event, ui) {
                        var input = $(this);
                        addProduct(ui.item.id);
                        $(this).val(ui.item.label);
                        return false;
                    }
                });
            }

            function addProduct(id = null) {
                if (id != '') {
                    $.ajax({
                        url: '<?php echo base_url(); ?>administrator/inventory/addProduct',
                        type: 'POST',
                        data: {"id": id},
                        success: function(data) {
                            var result = JSON.parse(data);
                            if (result.error == '0') {
                                $(".purchase_body").append(result.html);
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
        </script>
    <?php } else { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Select Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="All">All</option>
                                        <option value="Active" selected>Active</option>
                                        <option value="Inactive">In Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Search">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive tasks dataGridTable">
                            <table id="buyersList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Buyer Id</th>
                                        <th>Buyer Name</th>
                                        <th>Company Name</th>
                                        <th>Email Id</th>
                                        <th>Phone Number</th>
                                        <th>Status</th>
                                        <th>Created On</th>
                                        <th>Action</th>
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
        var status = $("#status").val();
        $('#buyersList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>buyers/ajaxListing",
                "type": 'POST',
                'data': {
                    status: status,
                    role: role
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

    function statusUpdate(e, uId, sTaTus) {
        $('#page-overlay1').hide();
        var TtMsg = 'Are you sure you want to ' + sTaTus + ' this status';
        $.confirm({
            title: TtMsg,
            buttons: {
                formSubmit: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function() {
                        $('#page-overlay').show();
                        $.ajax({
                            url: '<?php echo base_url(); ?>administrator/buyers/updateStatus',
                            type: 'POST',
                            data: {
                                "statusresult": "1",
                                "sid": uId,
                                "status": sTaTus
                            },
                            success: function(data) {
                                result = JSON.parse(data);
                                var msg = result.message;
                                if (result.error == '0') {
                                    toastr['success'](msg);
                                    $('#buyersList').DataTable().ajax.reload();
                                } else {
                                    toastr['warning'](msg);
                                    $('#buyersList').DataTable().ajax.reload();
                                }
                            },
                            error: function(e) {
                                toastr['warning'](e.message);
                                $('#buyersList').DataTable().ajax.reload();
                            }
                        });

                    }
                },
                no: function() {
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

    function getDetails(id) {
        if (id != '') {
            $.ajax({
                url: '<?php echo base_url(); ?>administrator/buyers/getDetails',
                type: 'POST',
                data: {
                    "id": id
                },
                success: function(data) {
                    result = JSON.parse(data);
                    var msg = result.message;
                    if (result.error == '0') {
                        $(".modalTitle").text('Buyers Details');
                        $(".modal-body").html(result.html);
                        $(".bs-example-modal-lg").modal('show');
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
</script>

<?php } ?>