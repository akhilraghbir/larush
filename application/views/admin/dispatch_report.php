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
                                <div id="chartContainer" style="height:300px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
<script type="text/javascript">
    function getdata() {
        var date = $("#date").val();
        var user_id = $("#user_id").val();
        $.ajax({
            url: '<?php echo base_url(); ?>administrator/DispatchReport/getReport',
            type: 'POST',
            data: {
                "date": date,
            },
            success: function(data) {
                result = JSON.parse(data);
                var msg = result.message;
                if (result.error == '0') {
                    renderGraph(result.data);
                } else {
                    toastr['warning'](msg);
                }
            },
            error: function(e) {
                toastr['warning'](e.message);
            }
        });
    }
    function renderGraph(data) {
        var options = {
            animationEnabled: true,
            title: {
                text: "Fe - Non Fe Dispatch Report"
            },
            data: [{
                type: "doughnut",
                innerRadius: "40%",
                showInLegend: true,
                legendText: "{label}",
                indexLabel: "{label}: #percent%",
                // dataPoints: [
                //     { label: "Department Stores", y: 6492917 },
                //     { label: "Discount Stores", y: 7380554 },
                //     { label: "Stores for Men / Women", y: 1610846 },
                //     { label: "Teenage Specialty Stores", y: 950875 },
                //     { label: "All other outlets", y: 900000 }
                // ]
                dataPoints: data
            }]
        };
        $("#chartContainer").CanvasJSChart(options);
    }
    $(document).ready(function(){
        getdata();
    });
</script>