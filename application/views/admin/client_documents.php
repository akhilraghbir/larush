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
                if (isset($formData['id'])) {
                    $id = $formData['id'];
                    $url = CONFIG_SERVER_ADMIN_ROOT . "ClientDocuments/edit/$id";
                } else {
                    $url = CONFIG_SERVER_ADMIN_ROOT . "ClientDocuments/add";
                }
                echo form_open($url, array('class' => ' dropzone userRegistration', 'id' => 'userRegistration')); ?>

                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group"><label for="first name" class="">Select Client</label>
                            <input value="" name="client_id" id="client_id" placeholder="Please Enter Name or Email Id or Phone Number" autocomplete='off' type="text" class="ui-autocomplete-input ui-autocomplete-loading form-control">
                            <span class="client-clear"><i class="icon-close"></i></i></span>
                            <input value="" name="user_id" id="user_id" type="hidden">
                        </div>
                    </div>
                   <?php /* <div class="col-md-6">
                        <div class="position-relative form-group"><label for="exampleEmail11" class="">Select Financial Year</label>
                            <select class="form-control" name='financial_year'>
                                <option value="">Select Financial Year</option>
                                <?php foreach($financial_years as $year){ ?>
                                    <option value="<?= $year['id'];?>" <?= ($year['status'] == 'Active') ? "selected" : ""; ?>><?= $year['financial_year'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Month </label>
                            <select name="month" id="month" class="form-control">
                                <?php foreach(months() as $month => $monthname){ ?>
                                    <option value="<?= $month;?>"  <?= (date('m') == $month) ? 'selected' : ''; ?> ><?= $monthname;?></option>
                                <?php }  ?>
                            </select>
                        </div>
                    </div> */ ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select Files to Upload </label>
                            <input name="file" type="file" id="docs" class="file-upload" multiple />
                            <div id="UploadedDoc"></div>
                            <div style="display:none" class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-primary" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>

<script>

$('#docs').change(function() {
    formdata = new FormData();
    if ($(this).prop('files').length > 0) {
        var files = $(this).prop('files');
        var error = '';
        for(var count = 0; count<files.length; count++){
            var name = files[count].name;
            var extension = name.split('.').pop().toLowerCase();
            if(jQuery.inArray(extension, ['pdf','doc','docx','xls','csv','xlsx','png','jpg','jpeg']) == -1){
                error += "Invalid " + files[count].name + " Image File"
            }
            else{
                formdata.append("docs[]", files[count]);
            }
        }
        formdata.append('client_id',$("#user_id").val());
        if($("#user_id").val()==''){
            notify('top', 'right', 'fa fa-danger', 'danger', 'animated fadeInDown', 'animated fadeOutDown','Please Select Client');
        }else if(error != ''){
            notify('top', 'right', 'fa fa-danger', 'danger', 'animated fadeInDown', 'animated fadeOutDown',error);
        }else{
            uploadDocs(formdata, 'UploadedDoc');
        }
    }
});
function uploadDocs(formdata, targetId) {
	$("#" + targetId).next().find(".progress-bar").css("width", "0%");
	$("#" + targetId).next().show();
	$.ajax({
		url: base_url + "administrator/ClientDocuments/DocsUpload",
		type: "POST",
		data: formdata,
		contentType: false,
		async: true,
		dataType: 'json',
		cache: false,
		processData: false,
		xhr: function () {
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener(
				"progress",
				function (evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						$("#" + targetId).next().find(".progress-bar").text(percentComplete + "%");
						$("#" + targetId).next().find(".progress-bar").css("width", percentComplete + "%");
						if (percentComplete == "100") {
						}
					}
				},
				false
			);
			return xhr;
		},
		success: function (resdata) {
		    var resultdata = JSON.stringify(resdata);
			var resultdataa = JSON.parse(resultdata);
			if (resultdataa.status == "success") {
				setTimeout(function () {
					$("#" + targetId).next().hide();
					$("#" + targetId).next().find(".progress-bar").css("width", "0%");
					$("#"+ targetId).prepend(resultdataa.uploadedFile);
					notify('top', 'right', 'fa fa-success', 'success', 'animated fadeInDown', 'animated fadeOutDown','File Uploaded Successfully');
				}, 1000);
			} else {
				$("#" + targetId).next().hide();
				$("#" + targetId).next().find(".progress-bar").css("width", "0%");
				notify('top', 'right', 'fa fa-danger', 'danger', 'animated fadeInDown', 'animated fadeOutDown',resultdataa.msg);
			}
		},
		error: function () {},
	});
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
                                    <label>Select Client</label>
                                        <input value="" name="client_id" id="client_id" placeholder="Please Enter Name or Email Id or Phone Number" autocomplete='off' type="text" class="ui-autocomplete-input ui-autocomplete-loading form-control">
                                        <input value="" name="user_id" id="user_id" type="hidden">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Enter File Name</label>
                                        <input value="" name="document_name" id="document_name" placeholder="Please Enter File Name" autocomplete='off' type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Uploaded By</label>
                                    <select name="uploaded_by" class="form-control" id="uploaded_by">
                                        <option value="All">All</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Client">Client</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Search">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive tasks dataGridTable">
                            <table id="userList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Document Name
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
        var user_id = $("#user_id").val();
        var document_name = $("#document_name").val();
        var uploaded_by = $("#uploaded_by").val();
        $('#userList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>ClientDocuments/ajaxListing",
                "type": 'POST',
                'data': {
                    client_id:user_id,
                    document_name:document_name,
                    uploaded_by:uploaded_by
                }
            },
        });
    }
    getdata();
</script>

<?php } ?>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $('#client_id').keyup(function(){
    user_search();
});
function user_search(){
    $('#client_id').autocomplete({
    source: function (request, response) {
    var input = this.element;
    $.ajax({
        url: base_url+'administrator/users/getUsers',
        type: 'POST',
        dataType: 'json',
        data: {
            accountant:'',
            term: request.term,
        },
        success: function (data) {
            $("#UploadedDoc").html('');
            if (data.length == '0') {
                $("#user_id").val('');
            } else {
                response($.map(data, function (item) {
                    Object.values(item);
                   return {id: item.id, value: item.name};
                })
                );
            }
        }
    });
    }, select: function (event, ui) {
        var input = $(this);
        $("#user_id").val(ui.item.id);
        $(this).val(ui.item.label);
        return false;
        }
    });
}
function deleteDoc(id,type) {
        $('#page-overlay1').hide();
        var TtMsg = 'Are you sure you want to delete this file';
        $.confirm({
            title: TtMsg,
            buttons: {
                formSubmit: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function() {
                        $('#page-overlay').show();
                        $.ajax({
                            url: '<?php echo base_url(); ?>administrator/ClientDocuments/deleteFile',
                            type: 'POST',
                            data: {
                                "id": id,
                                "type":type
                            },
                            success: function(data) {
                                result = JSON.parse(data);
                                var msg = result.message;
                                if (result.error == '0') {
                                    notify('top', 'right', 'fa fa-success', 'success', 'animated fadeInDown', 'animated fadeOutDown',msg);
                                   if(result.type == 'table'){
                                    $('#userList').DataTable().ajax.reload();
                                   }else{
                                    $("#div"+result.id).remove();
                                   }
                                } else {
                                    notify('top', 'right', 'fa fa-danger','danger',  'animated fadeInDown', 'animated fadeOutDown',msg);
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
</script>