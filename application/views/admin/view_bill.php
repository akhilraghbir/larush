<div class="col-md-12">
    <div class="main-card mb-3 card card-body">
        <h5 class="card-title">View Bill</h5>
        <div class="row bill_view_row">
        	<div class="col-sm-6">
        		<div class="row bill_view_row">
        			<label class="col-sm-4 col-form-label f-w-700">Reference Number:</label>
                    <div class="col-sm-8">
                        <?= $record['reference_number']; ?>
                    </div>
        		</div>
        		<div class="row bill_view_row">
        			<label class="col-sm-4 col-form-label f-w-700">Category:</label>
                    <div class="col-sm-8">
                            <?= $record['category_name']; ?>
                    </div>
        		</div>
        		<div class="row bill_view_row">
        			<label class="col-sm-4 col-form-label f-w-700">Financial Year:</label>
                    <div class="col-sm-8">
                        <?= $record['financial_year']; ?>
                    </div>
        		</div>
        		<div class="row bill_view_row">
        			<label class="col-sm-4 col-form-label f-w-700">Payment Mode:</label>
                    <div class="col-sm-8">
                        <?= $record['payment_mode']; ?>
                    </div>
        		</div>
        		<?php if($record['payment_mode'] == 'credit' || $record['payment_mode'] == 'debit'){ ?>
                <div class="row bill_view_row">
                    <label class="col-sm-4 col-form-label f-w-700">Account Number:</label>
                    <div class="col-sm-8">
                    <?= $record['account_number']; ?>
                    </div>
                </div>
        		<?php } ?>
        		<div class="row bill_view_row">
        			<label class="col-sm-4 col-form-label f-w-700">Created On:</label>
                    <div class="col-sm-8">
                    <?= displayDateInWords($record['created_on']); ?>
                    </div>
        		</div>
        	</div>
        	<div class="col-sm-6">
        	    <div class="row bill_view_row">
        			<label class="col-sm-4 col-form-label f-w-700">Bill Date:</label>
                    <div class="col-sm-8">
                        <?= displayDateInWords($record['billed_on']); ?>
                    </div>
        		</div>
        		<div class="row bill_view_row">
        			<label class="col-sm-4 col-form-label f-w-700">Bill Title:</label>
                    <div class="col-sm-8">
                        <?= $record['bill_title']; ?>
                    </div>
        		</div>
        		<div class="row bill_view_row">
        			<label class="col-sm-4 col-form-label f-w-700">Bill Type:</label>
                    <div class="col-sm-8">
                        <?= $record['bill_type']; ?>
                    </div>
        		</div>
        		<div class="row bill_view_row">
        			<label class="col-sm-4 col-form-label f-w-700">Bill Amount:</label>
                    <div class="col-sm-8">
                        <?= $record['bill_amount']; ?>
                    </div>
        		</div>
        		<div class="row bill_view_row">
        			<label class="col-sm-4 col-form-label f-w-700">Payment Month:</label>
                    <div class="col-sm-8">
                        <?= date("M",$record['month']); ?>
                    </div>
        		</div>
        		
        	</div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title mt-4">Attachments</h5>
            </div>
            <div class="col-md-12">
                <?php for($i=0;$i<count($attachments);$i++){ ?>
                <div class=" icon-list-demo">
                    <a href="<?= base_url($attachments[$i]['file']); ?>" target="_blank"> <?= displayfileicon($attachments[$i]['file_type']); ?></a>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>