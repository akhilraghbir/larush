$(document).ready(function() {
    if(notif){
        toastr[type](message);
    }
});

$(".numberOnly,.Onlynumbers").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && e.which != 110 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        $(this).attr("placeholder", "Allows Digits Only");
        return false;
    }
});
$(".numberOnly").blur(function(){
    var phoneNumberTest = new RegExp("^[1-9]{1}[0-9]{9}$");
    $('span.error-keyup-7').remove();
    if (!phoneNumberTest.test($(this).val()) && $(this).val() != '') {
        $(this).focus();
        $(this).after('<span class="error text-danger error-keyup-7">Invalid Mobile Number.</span>');
        return false;
    }
});
$(".email").blur(function (e) {
    var inputVal = $(this).val();
    $('span.error-keyup-7').remove();
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (!emailReg.test(inputVal)) {
        $(this).focus();
        $(this).after('<span class="error text-danger error-keyup-7">Invalid Email Format.</span>');
    }
});

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": 300,
    "hideDuration": 1000,
    "timeOut": 5000,
    "extendedTimeOut": 1000,
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }

  function uploadDocs(formdata, targetId, hiddenele) {
	$("#" + targetId)
		.next()
		.find(".progress-bar")
		.css("width", "0%");
	$("#" + targetId)
		.next()
		.show();
	$.ajax({
		url: base_url + "administrator/Common_upload/ImageUpload",
		type: "POST",
		data: formdata,
		contentType: false,
		async: true,
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
						$("#" + targetId)
							.next()
							.find(".progress-bar")
							.text(percentComplete + "%");
						$("#" + targetId)
							.next()
							.find(".progress-bar")
							.css("width", percentComplete + "%");
						if (percentComplete == "100") {
						}
					}
				},
				false
			);
			return xhr;
		},
		success: function (data) {
			var data = JSON.parse(data);
			if (data.status == "success") {
				$("#" + targetId + "").append(data.uploadedFile);
				setTimeout(function () {
				    $("." + hiddenele + "").val();
					var uploadedfile = data.file_name;
                    $("." + hiddenele + "").val(uploadedfile);
					$("#" + targetId)
						.next()
						.hide();
					$("#" + targetId)
						.next()
						.find(".progress-bar")
						.css("width", "0%");
				}, 1000);
			} else {
				$("#" + targetId)
					.next()
					.hide();
				$("#" + targetId)
					.next()
					.find(".progress-bar")
					.css("width", "0%");
				$("#" + targetId + "").html(data.msg);
				$("#" + targetId + "").css("color", "red");
			}
		},
		error: function () {},
	});
}
