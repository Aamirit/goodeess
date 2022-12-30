<!DOCTYPE html>
<html>
<head>
<title><?php echo !empty($title) ?  $title : 'Offer';  ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link href="<?php echo base_url();?>assets/layout2/favicon.png" rel="shortcut icon">
	<!-- <meta http-equiv="refresh" content="16;url=URL" /> -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Jquery -->
	<script src="<?php echo base_url();?>assets/layout2/js/jquery-1.12.4.min.js"></script>

	<!-- Normalize CSS -->

	<link rel="stylesheet" href="<?php echo base_url();?>assets/layout2/css/normalize.min.css">

	<!-- Custom CSS -->

	<script>document.write('<link rel="stylesheet" href="<?php echo base_url();?>assets/layout2/css/styles.css?version=' + Date.now() + '"">');</script><link rel="stylesheet" href="../../views/layout2/css/styles.css?version=1653302165857" "="">

	<link rel="stylesheet" href="<?php echo base_url();?>assets/layout2/css/animate.css">

	<!-- datejs -->

	<script src="<?php echo base_url();?>assets/layout2/js/date-fr-FR.js"></script>

	<script src="<?php echo base_url();?>assets/layout2/js/countdown.min.js"></script>

	<!-- fontawesome -->

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

	<!-- The Perfect Scrollbar CSS files -->

	<link href="<?php echo base_url();?>assets/layout2/js/scrollbar/perfect-scrollbar.css" rel="stylesheet">

	<!-- The Perfect Scrollbar JS files -->

	<script src="<?php echo base_url();?>assets/layout2/js/scrollbar/perfect-scrollbar.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
  </script>

</head>
<body>
<div class="topbar">

	<div class="cw"><?php echo $this->lang->line('free_welcome'); ?></div>

</div>
<div class="centerlogo">

	<img src="<?php echo base_url();?>assets/layout2/img/logo.png">

</div>
<div class="section-content">

	{_yield}

</div>

<div class="footer">

	<div class="cw">

		<div class="centerlogo">

			<img src="<?php echo base_url();?>assets/layout2/img/logo.png">

		</div>

		<div class="footer-subh1" style="margin-top:-10px"><?php echo $this->lang->line('rester_connect'); ?></div>

		<div class="flexrow footer-soclinks">

			<div><a href="#" class="stylish-btn"><i class="fab fa-facebook-f"></i></a></div>

			<div><a href="#" class="stylish-btn"><i class="fab fa-instagram"></i></a></div>

		</div>

		<img src="<?php echo base_url();?>assets/layout2/img/cc-footer.png" class="imgresp">

		<div class="footer-links">

			<div><a href="javascript:void(0);" class="modalreturn"><?php echo $this->lang->line('return_fund'); ?></a></div>

			<div><a href="javascript:void(0);" class="modalterms"><?php echo $this->lang->line('term_cond'); ?></a></div>

			<div><a href="javascript:void(0);" class="modalcookies"><?php echo $this->lang->line('cookie_policy'); ?></a></div>

			<div><a href="javascript:void(0);" class="modalprivacy"><?php echo $this->lang->line('privacy_policy'); ?></a></div>

			<div><a href="javascript:void(0);" class="modalshipping"><?php echo $this->lang->line('shipping_policy'); ?></a></div>

			<div><a href="javascript:void(0);" class="modaldisclaimer"><?php echo $this->lang->line('dis_policy'); ?></a></div>
		</div>

		<div class="spacer"></div>

		<div class="spacer"></div>

		<?php echo $this->lang->line('copy_right'); ?>

	</div>

</div>


<script>
	function getUrlVars(){
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}

		return vars;
	}

	var url = new URL(window.location.href);
	var geo = url.searchParams.get("geo");
	var versioningfix = Date.now();
	$(document).on("ready",function(){
		var urldata = getUrlVars();
		// var urltarget = "";
		// if(urldata != ''){
		// 	if(urldata[0] != window.location.href){
		// 		for(x=0;x<urldata.length;x++){
		//
		// 			var urlparam = urldata[x];
		// 			var urlvalue = getUrlVars()[urlparam];
		//
		// 			if(urlvalue == undefined){
		// 				var urlconcat = urlparam;
		// 			}else{
		// 				var urlconcat = urlparam+"="+urlvalue;
		// 			}
		//
		// 			if(urlparam == 'geo' || urlparam == 'layout'){
		//
		// 			}else{
		// 				urltarget = urltarget+urlconcat;
		// 			}
		//
		// 			if(urlparam == 'geo' || urlparam == 'layout'){
		//
		// 			}else{
		// 				if((x+1)==(urldata.length)){
		// 					urltarget = urltarget;
		// 				}else{
		// 					urltarget = urltarget+"&";
		// 				}
		// 			}
		// 		}
		// 	}
		// }
		// $(".javacta").attr("href",urltarget);
		//
		// $.get(geo+"/checkouturl.txt?version="+versioningfix, function(html_string)
		// {
		// 	var urldata = getUrlVars();
		// 	var urltarget = html_string;
		//
		// 	if(urldata != ''){
		// 		if(urldata[0] != window.location.href){
		// 			for(x=0;x<urldata.length;x++){
		//
		// 				var urlparam = urldata[x];
		// 				var urlvalue = getUrlVars()[urlparam];
		//
		// 				if(urlvalue == undefined){
		// 					var urlconcat = urlparam;
		// 				}else{
		// 					var urlconcat = urlparam+"="+urlvalue;
		// 				}
		//
		// 				if(urlparam == 'geo' || urlparam == 'layout'){
		//
		// 				}else{
		// 					urltarget = urltarget+urlconcat;
		// 				}
		//
		// 				if(urlparam == 'geo' || urlparam == 'layout'){
		//
		// 				}else{
		// 					if((x+1)==(urldata.length)){
		// 						urltarget = urltarget;
		// 					}else{
		// 						urltarget = urltarget+"&";
		// 					}
		// 				}
		// 			}
		// 		}
		// 	}
		// 	$(".javacta").attr("href",urltarget);
		// });

		new PerfectScrollbar('.kf-gallery-selection');

		var commentscheck = $(".sc-comment").length;

		if(commentscheck > 0){

			var pathname = window.location.pathname;

			$.ajax({
				url: 'https://randomuser.me/api/?results=10&nat=fr&seed='+pathname+'-comments&inc=name,picture',
				dataType: 'json',
				success: function(data) {


					var i = $(".sc-comment").length;
					for (x = 0; x < i; x++) {
						var fname = data.results[x].name.first;
						var lname = data.results[x].name.last;
						var tmbnl = data.results[x].picture.medium;
						var datedynamic = Date.today().add(-((x*(x+1)))-1).day().toString("MMM d, yyyy");

						var target = $('.sc-info-tab-panel .js-comments-target > div:eq('+x+')');
						target.find(".comment-profile-name").text(fname+" "+lname);
						target.find(".sc-comment-dp").css("background-image", 'url('+tmbnl+')');
						target.find(".comment-profile-date").text(datedynamic);

					}

				}

			});

		}

		setInterval(function(){
			$(".periodically-increment-js").each(function(){
				var dd = $(this).text();
				dd++;
				$(this).text(dd);
			});
		},5000)

	});


	$(document).on("click",".sc-info-tabs a",function(){
		$(this).closest(".sc-info-tabs").find("a").removeClass("active");
		$(this).addClass("active");
		var x = $(this).index();
		$(this).closest(".sc-tab-parent").find(".sc-info-tab-panel > div").hide();
		$(this).closest(".sc-tab-parent").find('.sc-info-tab-panel > div:eq('+x+')').fadeIn();
	});

	$(document).on("click",".kf-gallery-selection a",function(){
		var selectimg = $(this).find("div").css("background-image");
		$(".kf-gallery-scene").css("background-image",selectimg);
	});


	$(document).on("click",".footer-links a",function(){
		var target_file = $(this).attr("class");
		 var base_url = '<?php echo base_url('/assets/modals/');  ?>';
		 var lang = '<?php echo isset($offer['country']) ? strtolower($offer['country']) : 'fr'; ?>';
		 $.get(base_url+lang+"/"+target_file+".html?version="+versioningfix, function(html_string)
		{
			$("body").addClass("body_disabled").append(html_string);
			$(".modal").fadeIn(500);
		},'html');    // this is the change now its working
	});



	$(document).on("click",function(){
		if($(".modal").length == 1){
			if(!$(event.target).is(".modal *")){
				$(".modal").fadeOut(500);
				$("body").removeClass("body_disabled");
				setTimeout(function(){
					$(".modal").remove();
				},500);
			}
		}
	});
	//
	$(document).on("click",".modal_upper a",function(){
		$(".modal").fadeOut(500);
		$("body").removeClass("body_disabled");
		setTimeout(function(){
			$(".modal").remove();
		},500);
	});
	//
	// var coupon_applied = false;
	// $("#coupon_success").hide();

	// $(document).on("click","#discount_code_btn",function(e){
	// 	var discount_code = $("#discount_code").val();
	// 	if(discount_code!=''){
	// 		if(coupon_applied==false){
	// 			coupon_detail(discount_code);
	// 		}
	// 	}
	// });

	// function coupon_detail(code)
	// {
	// 	//var original_price = $(".new_price").html();
	// 	// var original_price = '9.95';
	//
	// 	var original_price = $(".pricing-new > span").text();
	// 	console.log(original_price);
	// 	var http = new XMLHttpRequest();
	// 	var url = 'https://mycasezs.com/goodeess/coupon_detail.php';
	// 	var params = new Object();
	// 	params.couponCode = code;
	//
	// 	switch(geo){
	// 		case "fr":
	// 			params.campaignId = '17';
	// 			break;
	// 		case "de":
	// 			params.campaignId = '24';
	// 			break;
	// 		case "es":
	// 			params.campaignId = '31';
	// 			break;
	// 	}
	//
	// 	var post_data = new URLSearchParams(params).toString();
	// 	console.log(post_data);
	// 	http.open('POST', url, true);
	// 	//Send the proper header information along with the request
	// 	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	// 	http.onreadystatechange = function() {
	// 		var results = JSON.parse(http.responseText);
	// 		//console.log(response);
	// 		if(results.response){
	// 			var coupon_detail = results.result;
	// 			console.log(coupon_detail);
	// 			if(coupon_detail.discountType=="PERCENT"){
	// 				var percentage = coupon_detail.couponDiscountPerc;
	// 				//alert(percentage);
	// 				var discount = Number(percentage/100)*Number(original_price);
	// 				var new_price = Number(original_price)-Number(discount)
	// 				;
	// 				new_price = new_price.toFixed(2);
	// 				$(".pricing-new > span").html(new_price);
	// 				var checkout_url = $(".javacta").attr("href");
	// 				var url = new URL(checkout_url);
	// 				url.searchParams.set('couponCode', code);
	// 				$(".javacta").attr("href",url);
	// 				$("#coupon_percentage").html(percentage);
	// 				$("#coupon_success").fadeIn();
	// 				coupon_applied = true;
	// 			}
	// 		}
	// 		else{
	// 			return false;
	// 		}
	// 	}
	// 	http.send(post_data);
	// }
	//
	// $(document).on('change', '#variants', function () {
	// 	var variant = $("#variants option:selected").val();
	// 	var checkout_url = $(".javacta").attr("href");
	// 	var url = new URL(checkout_url);
	// 	url.searchParams.set('variant1', variant);
	// 	$(".javacta").attr("href", url);
	//
	// });
	//
	// $(document).on("change",".cdropdown > select",function(){
	// 	var ddlabel = $(this).find("option:selected").text();
	// 	$(".cdd-disp").text("Selected: "+ddlabel);
	// });
	var urldata = getUrlVars();
	const location_str = window.location.href.replace('product', 'checkout');
	const afterLastSlash = urldata[0].substring(urldata[0].lastIndexOf('/') + 1);
	$("#buy_now_product").attr('href', location_str);
</script>
<script src="https://www.dwin1.com/<?php echo AWIN_TRACKING_ID; ?>.js" type="text/javascript" defer="defer"></script>
</body>
</html>
