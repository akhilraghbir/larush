<div class="col-md-12">
    <div class="main-card mb-3 card card-body">
            <h5 class="card-title"></h5>
            <?php
            $url = CONFIG_SERVER_ADMIN_ROOT . "bills/edit/".base64_encode($record['reference_number']);
            echo form_open($url, array('class' => 'userRegistration', 'id' => 'userRegistration','enctype' => 'multipart/form-data')); ?>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="first name" class="">Bill Title</label>
                            <input value="<?php if (isset($record['bill_title'])) { echo $record['bill_title'];} ?>" name="bill_title" id="bill_title" placeholder="Please Enter Bill Title" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="first name" class="">Billed on</label>
                            <input value="<?php if (isset($record['billed_on'])) { echo $record['billed_on'];} ?>" name="billed_on" id="billed_on" placeholder="Please Enter Bill Date" autocomplete='off' type="date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="first name" class="">Billed Month</label>
                            <select class="form-control" name='month'>
                                <option value=''>Select Month</option>
                                <?php foreach(months() as $month => $monthname){ ?>
                                        <option value="<?= $month;?>"  <?= ($record['month'] == $month) ? 'selected' : ''; ?> ><?= $monthname;?></option>
                                    <?php }  ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="first name" class="">Reference Number</label>
                            <input value="<?php if (isset($record['reference_number'])) { echo $record['reference_number'];} ?>" name="reference_number" id="reference_number" placeholder="Please Enter Referenc Number" readonly autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group"><label for="last name" class="">Financial Year</label>
                            <select class="form-control" name='financial_year'>
                                <option value=''>Select Financial Year</option>
                                <?php if(!empty($years)){ 
                                    foreach($years as $year){ ?>
                                      <option value="<?= $year['id']; ?>" <?= (isset($year['id']) && $record['financial_year'] == $year['id']) ? "selected" : ""; ?>><?= $year['financial_year']; ?></option>
                                    <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="exampleEmail11" class="">Category</label>
                                <select class="form-control" name='category_id'>
                                    <option value=''>Select Category</option>
                                    <?php if(!empty($categories)){ 
                                        foreach($categories as $category){ ?>
                                          <option value="<?= $category['id']; ?>" <?= (isset($category['id']) && $record['category_id'] == $category['id']) ? "selected" : ""; ?>><?= $category['category_name']; ?></option>
                                        <?php }} ?>
                                </select>
                        </div>
                    </div>
                   
                </div>
                <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="examplePassword11" class="">Bill Amount</label>
                                <input value="<?php if (isset($record['bill_amount'])) {echo $record['bill_amount'];} ?>" name="bill_amount" id="bill_amount" placeholder="Please Enter Bill Amount" maxlength="4" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="examplePassword11" class="">Payment Mode</label>
                                <select name="payment_mode" id="payment_mode" class="form-control">
                                    <option <?php if (isset($record['payment_mode']) && $record['payment_mode'] == 'online') { echo "selected"; } ?> value="online">online</option>
                                    <option <?php if (isset($record['payment_mode']) && $record['payment_mode'] == 'debit') { echo "selected"; } ?> value="debit">debit</option>
                                    <option <?php if (isset($record['payment_mode']) && $record['payment_mode'] == 'credit') { echo "selected"; } ?> value="credit">credit</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <div class="form-row">
                  <div class="account-div col-md-6 <?= ($record['payment_mode'] == 'debit' || $record['payment_mode'] == 'credit') ? '' : 'd-none'; ?>">
                      <div class="position-relative form-group">
                          <label for="examplePassword11" class="">Account Number</label>
                          <input type="text" maxlength="4" name="account_number" value="<?php if (isset($record['account_number'])) {echo $record['account_number'];} ?>" placeholder="Enter Account Number" class="numberOnly form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                        <label for="examplePassword11" class="">Upload Attachments</label>
                        <input type="file" name="attachment[]" multiple class="form-control">
                    </div>
                </div>
                <div class="form-row">
                        <div class="col-md-12">
                            <h5 class="card-title">Attachments</h5>
                        </div>
                        <?php for($i=0;$i<count($attachments);$i++){ ?>
                            <div class="col-sm-1 icon-list-demo">
                             <a href="<?= base_url($attachments[$i]['file']); ?>" target="_blank"> <?= displayfileicon($attachments[$i]['file_type']); ?></a>
                            </div>
                        <?php } ?>
                    </div>
                <div class="position-relative">
                        <input type='hidden' name="id" value="<?php echo $record['id'] ?>">
                        <input type='hidden' name="client_id" value="<?php echo $record['client_id'] ?>">
                        <input type='hidden' name="log_id" value="<?php echo $log_id ?>">
                        <input type="submit" name="edit" class="mt-2 btn btn-primary pull-right" value="Update">
                </div>
            </form>
        </div>
</div>
<script>
    $("#payment_mode").change(function(){
     var pm = $(this).val();
     if(pm == 'credit' || pm == 'debit'){
         $(".account-div").removeClass('d-none');
     }else{
         $(".account-div").addClass('d-none');
     }
    });
</script>