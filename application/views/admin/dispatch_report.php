<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="last name" class="">Select Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="daterange">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <div id="pie_chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/backend/libs/apexcharts/apexcharts.min.js"></script>
<script type="text/javascript">
    function getdata() {
        var date = $("#daterange").val();
        var user_id = $("#user_id").val();
        $.ajax({
            url: '<?php echo base_url(); ?>administrator/DispatchReport/getReport',
            type: 'POST',
            data: {
                "date": date,
            },
            beforeSend:function(){
                //renderGraph([0,0]);
            },
            success: function(data) {
                result = JSON.parse(data);
                if (result.error == 0) {
                   renderGraph(result.data);
                } else {
                    toastr['warning']('Something went wrong');
                }
            },
            error: function(e) {
                toastr['warning'](e.message);
            }
        });
    }
    function renderGraph(data) {
       var options ={
            chart: {
                height: 320,
                type: "pie"
            },
            series: data,
            labels: ["Ferrours Qty", "Non Ferrous Qty"],
            colors: [ "#fcb92c", "#4aa3ff"],
            legend: {
                show: !0,
                position: "bottom",
                horizontalAlign: "center",
                verticalAlign: "middle",
                floating: !1,
                fontSize: "14px",
                offsetX: 0,
                offsetY: 5
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: !1
                    }
                }
            }]
        };
        chart = new ApexCharts(document.querySelector("#pie_chart"), options);
        chart.render();
    }
    $(document).ready(function(){
        getdata();
    });
</script>