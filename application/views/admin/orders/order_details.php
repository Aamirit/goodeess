<div class="modal-header">
  <h5 class="modal-title order-status" id="staticBackdropLabel"><?php echo $status_info ; ?></h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="container order_data_here">
    <?php 
      if(!empty($order_info['import_order_post_data'])){
        $order_response = json_decode($order_info['import_order_post_data'], true);
        $email = empty($order_response['email'])?"N/A":$order_response['email'];
        $phone = empty($order_response['phone'])?"N/A":$order_response['phone'];
        $billingAddress1 = empty($order_response['billingAddress1'])?"N/A":$order_response['billingAddress1'];
        $billingAddress2 = empty($order_response['billingAddress2'])?"N/A":$order_response['billingAddress2'];
        $billingCity = empty($order_response['billingCity'])?"N/A":$order_response['billingCity'];
        $billingState = empty($order_response['billingState'])?"N/A":$order_response['billingState'];
        $billingZip = empty($order_response['billingZip'])?"N/A":$order_response['billingZip'];
        $billingCountry = empty($order_response['billingCountry'])?"N/A":$order_response['billingCountry'];
    ?>
        <div class="row my-2">
          <h3>Customer Information</h3>
        </div>
    
        <div class="row my-1">
          <div class="col-6"><strong>Customer Name: </strong><span><?php echo $order_response['firstName']." ".$order_response['lastName']; ?></span></div>
          <div class="col-6"><strong>Customer Email: </strong><span><?php echo $email; ?></span></div>
        </div>
    
        <div class="row my-1">
          <div class="col-6"><strong>Customer Phone: </strong><span><?php echo $phone; ?></span></div>
        </div>
    
        <div class="row my-2">
          <h3>Billing Information</h3>
        </div>
    
        <div class="row my-1">
          <div class="col-6"><strong>Billing Name: </strong><span><?php echo $order_response['billingFirstName']." ".$order_response['billingLastName']; ?></span></div>
          <div class="col-6"><strong>Billing Email: </strong><span><?php echo $email; ?></span></div>
        </div>
    
        <div class="row my-1">
          <div class="col-6"><strong>Billing Address-1: </strong><span><?php echo $billingAddress1; ?></span></div>
          <div class="col-6"><strong>Billing Address-2: </strong><span><?php echo $billingAddress2; ?></span></div>
        </div>
    
        <div class="row my-1">
          <div class="col-6"><strong>Billing City: </strong><span><?php echo $billingCity; ?></span></div>
          <div class="col-6"><strong>Billing State: </strong><span><?php echo $billingState; ?></span></div>
        </div>
    
        <div class="row my-1">
          <div class="col-6"><strong>Billing Zip: </strong><span><?php echo $billingZip; ?></span></div>
          <div class="col-6"><strong>Billing Country: </strong><span><?php echo $billingCountry; ?></span></div>
        </div>
    
      <?php }else { ?>
        <div class="row my-2">
          <h3>Information Not Available!</h3>
        </div>
      <?php	} ?>
          
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>


