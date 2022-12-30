<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
	.all_errors{
		color: red;
		margin-bottom: 1.5rem;
	}
	#zip{
		max-width: 100% !important;
	}
	#country{
		width: 100%;
		background: white;
	}

	iframe {

		position: absolute;
		top: 25%;
		height: 40%;
		margin-left: 15%;
		width: 70%;
		z-index: 99999;
		border: 1px solid #ccc;
		background: #fff;
	}

	}
</style>
<body>
<div class="tb">
	<div class="cw">
		<?php echo $this->lang->line('free_welcome'); ?>
	</div>
</div>
<div class="lb">
	<img src="<?php echo base_url();?>assets/sticky/img/logo.png" class="logo">
</div>
<?php
if(!empty($offer['offer_title'])){
	$offer_title = $offer['offer_title'];
}else{
	$offer_title = 'No Offer Title';
}

if(!empty($offer['offer_price'])){
	$offer_price = number_format($offer['offer_price'], 2);
}else{
	$offer_price = intval(0);
}
if(!empty($offer['offer_currency'])){
	$offer_currency = strval($offer['offer_currency']);
}else{
	$offer_currency = strval('EUR');
}
if(!empty($offer['offer_description'])){
	$offer_description = $offer['offer_description'];
}else{
	$offer_description = 'No Offer Title';
}
if(!empty($offer['offer_image'])){
	$offer_image = $offer['offer_image'];
}else{
	$offer_image = '';
}
if(!empty($vat)){
	$vta = $vat;
}else{
	$vta = 0;
}
$arrayMap = array("fr"=>"france","es"=>"spain","pt"=>"portugal","de"=>"germany");
$country = '';
$code= '';
if(!empty($_GET['geo']) && in_array($_GET['geo'],array("fr","de","pt","es"))) {
	if($_GET['geo']=='fr'){
		$country ="France";
		$code = 'fr';
		$vta = 20;
	}elseif ($_GET['geo']=='de'){
		$country ="Germany";
		$code = 'de';
	}
	elseif ($_GET['geo']=='pt'){
		$country ="Portugal";
		$code = 'pt';
		$vta = 23;
	}
	elseif ($_GET['geo']=='es'){
		$country ="Spain";
		$code = 'es';
		$vta = 21;
	}
}
if(!empty($offer['offer_price'])){
	$cal = $vta /100 ;
	$grand_price = intval($offer['offer_price'] * $cal) + intval($offer['offer_price']);
	$grand_price = number_format($grand_price, 2);
}else{
	$cal = $vta /100 ;
	$grand_price = intval(0);
}

if(!empty($offer['offer_price'])){
	$cal = $vta /100 ;
	$grand_price =  $offer_price;
	$grand_price = number_format($grand_price, 2);
}else{
	$cal = $vta /100 ;
	$grand_price = intval(0);
}
//if($shipping_free == false ){
//	$grand_price =  SHIPPING_PRICE;
//	$offer_price = "0.00";
//}
$shipping_free = false;
$grand_price =  SHIPPING_PRICE;
$offer_price = "0.00";
$shipping_fee = SHIPPING_PRICE;
?>

<form id="card_form" class="form_panel payment_check"  action="<?php echo base_url();?>home/checkout_process" method="POST">
	<input type="hidden" id="seacurity_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
	<?php
	if(!empty($slug)){
		?>
		<input type="hidden" name="slug" value="<?php echo $slug; ?>" />
		<input type="hidden" name="current_url" value="<?php echo base_url().'checkout/'.$slug; ?>" />
		<?php
	}
	?>
	<input type="hidden" name="offer_currency" value="<?php echo $offer_currency; ?>">
	<input type="hidden" id="amount" name="x_amount" data-threeds="amount" value="<?php echo $grand_price; ?>">
	<input type="hidden" name="product" value="<?php echo time().'1'; ?>" id="product">
	<!--<input type="hidden" name="x_transaction_id" data-threeds="id">-->
	<div class="ms">
		<div class="cw">
			<h1 class="msheading"><?php echo $this->lang->line('verifier'); ?></h1>
			<div class="flexrow msrow">
				<div>
					<h2 class="formheading">1. <?php echo $this->lang->line('step_1_title'); ?></h2>
					<h3 class="formsub"><?php echo $this->lang->line('enter_info'); ?></h3>
					<div class="forminput">
						<div class="formlabel"><?php echo $this->lang->line('first_name'); ?> *</div>
						<input type="text" name="firstname" id="firstName">
					</div>
					<div class="firstname_error all_errors"></div>
					<div class="forminput">
						<div class="formlabel"><?php echo $this->lang->line('last_name'); ?> *</div>
						<input type="text" name="lastname" id="lastName" >
					</div>
					<div class="lastname_error all_errors"></div>
					<div class="forminput">
						<div class="formlabel"><?php echo $this->lang->line('address'); ?> *</div>
						<input type="text" name="address" id="address">
					</div>
					<div class="address_error all_errors"></div>
					<div class="forminput formselect">
						<div class="formlabel"><?php echo $this->lang->line('country'); ?> *</div>
						<?php if(!empty($country)){ ?>
							<select name="country" id="country">
								<option value="<?php echo $code ?>"><?php echo $country ?></option>
							</select>
						<?php } ?>
					</div>
					<div class="flexrow forminline">
						<div class="forminput">
							<div class="formlabel"><?php echo $this->lang->line('city'); ?> *</div>
							<input type="text" name="city" id="city">
						</div>

						<div class="forminput">
							<div class="formlabel"><?php echo $this->lang->line('postal_code'); ?> *</div>
							<input type="text" name="zip" id="zip">
						</div>
					</div>
					<div class="flexrow forminline">
						<div class="city_error all_errors"></div>
						<div class="zip_error all_errors"></div>
					</div>
					<h3 class="formsub"><?php echo $this->lang->line('your_cordinate'); ?> </h3>
					<div class="forminput">
						<div class="formlabel"><?php echo $this->lang->line('email'); ?>  *</div>
						<input type="text" name="email" id="email">
					</div>
					<div class="email_error all_errors"></div>
					<div class="forminput">
						<div class="formlabel"><?php echo $this->lang->line('mobile_no'); ?>*</div>
						<input type="text" name="phone" id="phone">
					</div>
					<div class="phone_error all_errors"></div>
					<h3 class="formsub"><?php echo $this->lang->line('enter_payment_detail'); ?> </h3>
					<div class="forminput">
						<div class="formlabel"><?php echo $this->lang->line('cardHolder'); ?>*</div>
						<input type="text" name="cardholder">
					</div>
					<div class="cardholder_error all_errors"></div>
					<div class="forminput">
						<div class="formlabel"><?php echo $this->lang->line('card_no'); ?></div>
						<input type="text" name="ccnumber" data-threeds="pan" maxlength="16"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required >
					</div>
					<div class="ccnumber_error all_errors"></div>
					<div class="flexrow forminline">
						<div class="forminput" style="width: 30%;">
							<div class="formlabel">MM *</div>
							<input type="text" name="expdatem" data-threeds="month" maxlength="2" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"required>
						</div>
						<div class="forminput" style="width: 30%;">
							<div class="formlabel">YY *</div>
							<input type="text" name="expdatey" data-threeds="year" maxlength="2"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"required>
						</div>
						<div class="forminput" style="width: 30%;">
							<div class="formlabel">CVV *</div>
							<input type="text" name="cvv" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
						</div>
					</div>
					<div class="flexrow forminline">
						<div class="expdatem_error all_errors"></div>
						<div class="expdatey_error all_errors"></div>
						<div class="cvv_error all_errors"></div>
					</div>
					<label class="formcheckbox">
						<input type="checkbox" name="goodeess-terms"  checked>
						<div class="chkboxdisp"></div>
						<div><?php echo $this->lang->line('terms'); ?></div>
					</label>
					<button   id="submit_order_btn" type="submit" class="hoverbtn btn1 animated infinite pulse"><span><?php echo $this->lang->line('continue'); ?></span></button>
				</div>
				<div>
					<h2 class="formheading"><?php echo $this->lang->line('step_3_title'); ?></h2>
					<div class="flextable">
						<div class="tablerow">
							<div><?php echo $this->lang->line('qty'); ?></div>
							<div>1</div>
						</div>
						<div class="tablerow">
							<div><?php echo $this->lang->line('subtotal'); ?></div>
							<div>€<?php echo $offer_price; ?></div>
						</div>
						<div class="tablerow">
							<div><?php echo $this->lang->line('vat'); ?></div>
							<div><?php echo $vta; ?>%</div>
						</div>
						<div class="tablerow">
							<div><?php echo $this->lang->line('trial_period'); ?></div>
							<div>€0.00</div>
						</div>
						<div class="tablerow">
							<?php if($shipping_free == false ) { ?>
								<div><?php echo $this->lang->line('shipping_handling'); ?></div>
								<div>€<?php echo $shipping_fee; ?></div>
							<?php } else { ?>
								<div><?php echo $this->lang->line('delivery_days'); ?></div>
								<div>€0.00</div>

							<?php }  ?>
						</div>
						<div class="tablerow grandtotal">
							<div><?php echo $this->lang->line('grand_total'); ?></div>
							<div>€<?php echo $grand_price; ?></div>
						</div>
						<div class="flexrow iteminfo">
							<?php  if(!empty($images)){  ?>
								<div>
									<img src="<?php echo base_url();?>assets/uploads/<?php echo  $images[0]['offer_image'];; ?>" class="imgresp">
								</div>
							<?php } ?>
							<div>
								<div class="itemname"><?php echo $offer_title; ?></div>
								<div class="itemsubinfo"><?php echo $this->lang->line('qty'); ?> 1</div>
								<div class="itemsubinfo">€<?php echo $offer_price; ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</form>
</div>
<div class="footer">
	<div>
		<div><?php echo $this->lang->line('copy_right'); ?></div>
		<div><a href="#"><?php echo $this->lang->line('link_1'); ?></a></div>
		<div><a href="#"><?php echo $this->lang->line('link_2'); ?></a></div>
		<div><a href="#"><?php echo $this->lang->line('link_3'); ?></a></div>
	</div>
	<div>
		<div><img src="<?php echo base_url();?>assets/sticky/img/footericons/1.png"></div>
		<div><img src="<?php echo base_url();?>assets/sticky/img/footericons/2.png"></div>
		<div><img src="<?php echo base_url();?>assets/sticky/img/footericons/3.png"></div>
		<div><img src="<?php echo base_url();?>assets/sticky/img/footericons/4.png"></div>
		<div><img src="<?php echo base_url();?>assets/sticky/img/footericons/5.png"></div>
	</div>
</div>
<script src="https://cdn.3dsintegrator.com/threeds.min.2.1.0.js"></script>
<script type='application/javascript'>
	window.new_prospect = false;
	var base_url = '<?php echo base_url('/');  ?>';
	var api_url = '';
	if (window.location.href.indexOf("dev") > -1 || window.location.href.indexOf("localhost") > -1) {
		api_url = 'https://api-sandbox.3dsintegrator.com/v2';
	} else {
		api_url = 'https://api.3dsintegrator.com/v2';
	}
	const tds = new ThreeDS(
			'card_form',
			'd9f44abc396e1752633512165ecebb5f',
			null,
			{
				endpoint        : api_url,
				showChallenge : true,
				challengeIndicator: '04',
				autoSubmit: false,
				verbose         : true,
				addResultToForm : false,
				resolve         : function(response)
				{
					console.log('TDS RESPONSE gg: ', response);
				}
			}

	);

	const form = document.querySelector("#card_form");
	form.addEventListener("submit", event => {
		event.preventDefault();
		tds.verify(
				// Function that gets called when a successful 3DS response is returned
				response => {
					console.log("3DS response data for this authentication:", response);
					// Add values to form...
					//
					if (response.authenticationValue)
					{
						//response.authenticationValue.replace(/[^a-z0-9]/gi,'');
						$('<input>')
								.attr('type', 'hidden')
								.attr('name', 'cavv')
								.attr('value', response.authenticationValue)
								.appendTo('#card_form');
					}

					if (response.cavv)
					{
						$('<input>')
								.attr('type', 'hidden')
								.attr('name', 'cavv')
								.attr('value', response.cavv)
								.appendTo('#card_form');
					}
					//var $status = $("#status_id");

					$('<input>')
							.attr('type', 'hidden')
							.attr('name', 'eci')
							.attr('value', response.eci)
							.appendTo('#card_form');
					$('<input>')
							.attr('type', 'hidden')
							.attr('name', 'ds_trans_id')
							.attr('value', response.dsTransId)
							.appendTo('#card_form');
					$('<input>')
							.attr('type', 'hidden')
							.attr('name', 'acs_trans_id')
							.attr('value', response.acsTransId)
							.appendTo('#card_form');
					$('<input>')
							.attr('type', 'hidden')
							.attr('name', 'status')
							.attr('value', response.status)
							.appendTo('#card_form');
					$('<input>')
							.attr('type', 'hidden')
							.attr('name', '3d_version')
							.attr('value', response.protocolVersion)
							.appendTo('#card_form');
					$("#submit_order_btn").prop("disabled",true);
					$.ajax({
						url: base_url + 'home/checkout_process2',
						type: 'POST',
						data: new FormData(document.getElementById("card_form")),
						dataType: 'JSON',
						processData: false,
						contentType: false,
						success: function(data){
							if(data.response===true){
								window.location = data.redirect_url;
							}else {
								if(data.errors){
									errors(data.errors);
									var security_token = '<?php echo $this->security->get_csrf_hash(); ?>';
									$("#seacurity_token").val(security_token);
								}
								if(data.response===false && data.error_message){
									alert(data.error_message);
								}
							}
							$("#submit_order_btn").prop("disabled",false);
						}
					});
				},
				// Function that gets called when a server error is returned
				error => {
					var error = JSON.parse(error);
					alert(error.error);
					console.log("Error message returned for this authentication:", error)
				},
				// Amount object which needs have the value as a integer/float
				{ amount: parseFloat(document.querySelector("#amount").value) }
		);
	});

</script>
<script type="text/javascript">
	//var base_url = '<?php //echo base_url('/');  ?>//';
	//$(document).on('submit', '#card_form', function(e){
	//	e.preventDefault();
	//	$("#submit_order_btn").prop("disabled",true);
	//	$.ajax({
	//		url: base_url + 'home/free_checkout_process',
	//		type: 'POST',
	//		data: new FormData(document.getElementById("card_form")),
	//		dataType: 'JSON',
	//		processData: false,
	//		contentType: false,
	//		success: function(data){
	//			if(data.response===true){
	//				window.location = data.redirect_url;
	//			}else {
	//				if(data.errors){
	//					errors(data.errors);
	//					var security_token = '<?php //echo $this->security->get_csrf_hash(); ?>//';
	//					$("#seacurity_token").val(security_token);
	//				}
	//				if(data.response===false && data.error_message){
	//					alert(data.error_message);
	//				}
	//				// if(data.IMPORT_ORDER_ERROR){
	//				// 	$('.order_errors').html(data.IMPORT_ORDER_ERROR);
	//				// }
	//				// if(data.IMPORT_LEAD_ERROR){
	//				// 	$('.lead_errors').html(data.IMPORT_LEAD_ERROR);
	//				// }
	//				// if(data.IMPORT_CLICK_ERROR){
	//				// 	$('.click_errors').html(data.IMPORT_CLICK_ERROR);
	//				// }
	//				// if(data.SecurionPay_ERROR){
	//				// 	$('.securion_errors').html(data.SecurionPay_ERROR);
	//				// }
	//			}
	//			$("#submit_order_btn").prop("disabled",false);
	//		}
	//	});
	//});
	function errors(arr = ''){
		$(".all_errors").html("");
		if(arr != ''){
			$.each(arr, function( key, value ) {
				$('.'+key+'_error').html(value);
			});
		}
		return false;
	}
	$(document).ready(function(){
		function isEmail(email) {
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			return regex.test(email);
		}
		$("#email, #phone").on('change', function(){
			if($("#email").val() !== '' && isEmail($("#email").val())){
				$.ajax({
					url: base_url + 'home/new_prospect',
					type: 'POST',
					data: new FormData(document.getElementById("card_form")),
					dataType: 'JSON',
					processData: false,
					contentType: false,
					success: function(data){
						if(data.response===true){
							window.new_prospect = true;
						}else {
							console.error("error.....");
						}
					}
				});
			}
		});
	})
</script>
<script src="https://www.dwin1.com/<?php echo AWIN_TRACKING_ID; ?>.js" type="text/javascript" defer="defer"></script>
</body>
