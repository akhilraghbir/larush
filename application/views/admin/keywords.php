<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive tasks dataGridTable">
                        <table id="userList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Keyword</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(!empty($keywords)){ 
                                    foreach($keywords as $keyword){
                                ?>
                                <tr>
                                    <td><?= $keyword['name'];?></td>
                                    <td><?= $keyword['keyword'];?> &nbsp;&nbsp; <a href="javascript:copyToclipboard('<?= $keyword['keyword'];?>')" data-toggle="tooltip" data-placement="top" data-original-title=" Click to copy"><i class="fa fa-copy"></i></a></td>
                                </tr>
                                <?php
                                    } }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function copyToclipboard(keyword){
        var dummy = document.createElement("textarea");
        document.body.appendChild(dummy);
        dummy.value = keyword;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
        notify('top', 'right', 'fa fa-info-circle', 'info', 'animated fadeInDown', 'animated fadeOutDown','Copied to clipboard');
    }
</script>