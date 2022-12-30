<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!empty($offer['offer_title'])){
	$offer_title = $offer['offer_title'];
}else{
	$offer_title = 'No Offer Title';
}

if(!empty($offer['offer_price'])){
	$offer_price = $offer['offer_price'];
}else{
	$offer_price = intval(0);
}
$offer_price = number_format($offer_price, 2);
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
if(!empty($offer['offer_price'])){
	$cal = $vta /100 ;
	$grand_price = ($offer_price * $cal) + $offer_price;
}else{
	$cal = $vta /100 ;
	$grand_price = intval(0);
}
$grand_price = number_format($grand_price, 2);
?>
<body class=" dom-pending">
<div id="i1f8k" class="tb">
	<div id="ix8od" class="cw">
		<img id="fkt-image-702-e96-810" title="" target="_self" src="<?php echo base_url();?>assets/thankyou/img/logo.png" href="" alt="" onclick="" class="imgresp tblogo re-fk-lazy fk-disable-lazy" width="" height="" align="middle">
	</div>
</div>
<div id="i4ftw" class="hero_ty re-fk-lazy fk-disable-lazy">
	<div id="ij6tq" class="cw">
		<div id="ize6q" class="hero_wrapper">
			<div data-text="text" class="hero_h1"><?php echo $this->lang->line('heading_1'); ?></div>
			<div data-text="text" class="hero_divider divider"></div>
			<div data-text="text" id="ixbuv" class="hero_p"><?php echo $this->lang->line('heading_1_p_1'); ?>
				<br>
				<br><?php echo $this->lang->line('heading_1_p_2'); ?>
				<br>
				<br><?php echo $this->lang->line('heading_1_p_3'); ?>
				<br>
				<br><?php echo $this->lang->line('heading_1_p_4'); ?>
			</div>
			<div data-text="text" class="whitespacer"></div>
			<a id="fkt-link-056-781-be0" data-id="fkt-link-88f-483-ae5" title="Afficher l'état de la commande" target="_self" align="center" href="javascript:void(0);" class="hoverbtn herobtn btnpink">
				<span data-text="text">Afficher l'état de la commande</span></a>
		</div>
	</div>
</div>
<img id="fkt-image-343-c95-8c9" title="" target="_self" src="<?php echo base_url();?>assets/thankyou/img/logostrip.jpg" href=""  class="imgresp logostrip re-fk-lazy fk-disable-lazy" align="middle">
<div id="iv6j2" class="section_orderinfo">
	<div class="cw">
		<div class="frow orderinfo_row">
			<div id="i155g">
				<div data-text="text" class="orderinfo_h1">
					<?php echo $this->lang->line('heading_2'); ?>
				</div>
				<div data-text="text" class="orderinfo_divider divider"></div>
				<div data-text="text" class="orderinfo_p"><?php echo $this->lang->line('heading_2_p_1'); ?></div>

				<div class="orderinfo_summary">
					<div data-text="text" class="summary_head"><?php echo $this->lang->line('SYNTHESIS'); ?></div>
					<div class="summary_body">
						<div>
							<div data-text="text"><?php echo $this->lang->line('product'); ?></div>
							<div data-text="text"><?php echo $this->lang->line('price'); ?></div>
						</div>
						<div id="it2wj"><div data-text="text" id="ib4z1" class="product_rl_title"><?php  echo $offer_title; ?></div>
							<div><span data-text="text" id="i74l2" class="product_rl_price"><?php echo $grand_price; ?>€</span></div>
						</div>
					</div>
				</div>
				<a id="fkt-link-264-8a6-8b9" data-id="fkt-link-b17-b9c-898" title="Voir le panier" target="_self" action="choose" align="center"  href="javascript:void(0);" class="hoverbtn orderinfobtn btnpink">
					<span data-text="text"><?php echo $this->lang->line('checkout'); ?></span></a>
			</div>
			<div id="i29b9">
				<div id="ipcuh" class="prodlist">
					<div id="iksy4" class="proditem i4gyd8">
						<div id="i1nah" class="pthumb">
							<img id="fkt-image-8aa-1a8-bf4" title="" target="_self" src="<?php echo base_url();?>assets/thankyou/img/logo.png" href="" alt="" onclick="" class="pimage fk-disable-lazy" width="" height="" align="middle">
						</div>
						<div data-text="text" id="i3a67" class="pinfo ptitle">1</div>
						<div data-text="text" id="ifejl" class="pinfo pdesc"><?php  echo $offer_title; ?></div>
						<div data-text="text" id="ils7y-2" class="pprice"><?php echo $grand_price; ?>€</div>
					</div>
				</div>
				<div id="iv23b" class="breakdown">
					<div id="ipdwbj" class="bd_listitem">
						<div data-text="text" id="ivurf"><?php echo $this->lang->line('subtotal'); ?>:</div>
						<div data-text="text" id="il2o3" class="product_rl_price"><?php echo $grand_price; ?>€</div>
					</div>
					<div class="bd_listitem">
						<div data-text="text"><?php echo $this->lang->line('transport'); ?>:</div>
						<div data-text="text" id="it2b8"><?php echo $this->lang->line('free'); ?></div>
					</div>
					<div id="i5mki" class="bd_listitem">
						<div data-text="text" id="idsuy"><?php echo $this->lang->line('vat'); ?></div>
						<div data-text="text" id="ic2pd"><?php echo $vta; ?>%</div>
					</div>
					<div data-text="text" class="bd_divider"></div>
					<div class="bd_listitem">
						<div data-text="text" id="ikmkb"><?php echo $this->lang->line('grand'); ?>:</div>
						<div data-text="text" id="i09bg" class="product_rl_price"><?php echo $grand_price; ?>€</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div data-text="text" id="i4pog" class="section_oab"></div>

<div class="footer">
	<div class="cw">
		<div class="frow footer_row">
			<div>
				<div data-text="text" class="footer_h1"><?php echo $this->lang->line('shop'); ?></div>
				<div class="footer_link">
					<a id="fkt-link-b0c-59c-998" data-id="fkt-link-993-ea2-849" title="Tout magasiner" target="_self"  href="javascript:void(0);"  quantity="" price=""><?php echo $this->lang->line('shop_all'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-21f-4ae-886" data-id="fkt-link-030-591-a42"><?php echo $this->lang->line('beauty_healthy'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-c6a-fb3-932" data-id="fkt-link-d3a-78c-ad4" title="Gadgets" target="_self" href="javascript:void(0);" ><?php echo $this->lang->line('gadgets'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-7b1-3ad-a02" data-id="fkt-link-94e-a91-b5e" title="Électronique" target="_self" href="javascript:void(0);"><?php echo $this->lang->line('electronics'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-f03-49e-8c5" data-id="fkt-link-baf-fa5-b10" title="Maison &amp; Jardin" target="_self" href="javascript:void(0);"><?php echo $this->lang->line('home_garden'); ?></a>
				</div>
				<div class="footer_link"><a id="fkt-link-4dc-e8a-829" data-id="fkt-link-282-1bf-8a7" title="Des sports" target="_self" action="choose"><?php echo $this->lang->line('sports'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-8ac-683-9a8" data-id="fkt-link-a09-6ab-b09" title="Jouets et enfants" target="_self"  href="javascript:void(0);"><?php echo $this->lang->line('toys_children'); ?></a>
				</div>
			</div>

			<div>
				<div data-text="text" class="footer_h1"><?php echo $this->lang->line('information'); ?></div>
				<div class="footer_link">
					<a id="fkt-link-a98-fb7-9bd" data-id="fkt-link-556-bac-bfe" title="Politique de retour et de remboursement" target="_self" action="choose" href="javascript:void(0);"><?php echo $this->lang->line('return_fund'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-d3d-6b3-9d5" data-id="fkt-link-d48-f96-bbd" title="Conditions générales" target="_self" action="choose" align="center"  href="javascript:void(0);" ><?php echo $this->lang->line('term_cond'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-044-0be-a53" data-id="fkt-link-63d-889-8b4" title="Politique en matière de cookies" target="_self" action="choose" align="center"  href="javascript:void(0);" ><?php echo $this->lang->line('cookie_policy'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-5e0-aaa-b15" data-id="fkt-link-f5f-6a7-8cf" title="Politique de confidentialité" target="_self" action="choose" align="center"  href="javascript:void(0);" ><?php echo $this->lang->line('privacy_policy'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-cc5-888-85e" data-id="fkt-link-16e-e86-ac1" title="Clause de non-responsabilité" target="_self" action="choose" align="center"  href="javascript:void(0);" ><?php echo $this->lang->line('disclaimer'); ?></a>
				</div>
			</div>
			<div>
				<div data-text="text" class="footer_h1"><?php echo $this->lang->line('cutomer_service'); ?></div>
				<div class="footer_link">
					<a id="fkt-link-0cd-7ab-977" data-id="fkt-link-48b-788-a42" title="Termes de recherche" target="_self" action="choose" align="center"  href="javascript:void(0);" ><?php echo $this->lang->line('search_term'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-842-6bc-be2" data-id="fkt-link-413-1aa-842" title="Recherche Avancée" target="_self" action="choose" align="center"  href="javascript:void(0);"  price=""><?php echo $this->lang->line('search_advance'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-b9d-98a-a2e" data-id="fkt-link-b07-ea5-b2f" title="Recherche de commande et de retour" target="_self"  href="javascript:void(0);" ><?php echo $this->lang->line('search_order'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-c03-bb5-bac" data-id="fkt-link-914-096-9da" title="Nous contacter" target="_self" action="choose" align="center"  href="javascript:void(0);" ><?php echo $this->lang->line('contact_us'); ?></a>
				</div>
				<div class="footer_link">
					<a id="fkt-link-c41-ead-87e" data-id="fkt-link-e82-d97-94c" title="Aide et FAQ" target="_self" action="choose" align="center" href="javascript:void(0);" ><?php echo $this->lang->line('help_faq'); ?></a>
				</div>
			</div>
			<div>
				<div data-text="text" class="footer_h1"><?php echo $this->lang->line('subscribe_news'); ?></div>
				<div data-text="text" class="footer_p"><?php echo $this->lang->line('receive_news'); ?></div>
				<div data-text="text" class="footer_h1"><?php echo $this->lang->line('stay_connect'); ?></div>
			</div>

		</div>
	</div>
</div>


<script id="scriptData">
	window.addEventListener('load', (function(){
		$("#loading").hide();
		$("body").removeClass("dom-pending");

		var lazyLoadThrottleTimeout;
		function recursiveOffsetParentTop(img) {
			if (img.offsetParent) {
				return img.offsetParent.offsetTop + recursiveOffsetParentTop(img.offsetParent);
			} else {
				return 0;
			}
		}
		function lazyLoad() {
			if (lazyLoadThrottleTimeout) {
				clearTimeout(lazyLoadThrottleTimeout);
			}
			lazyLoadThrottleTimeout = setTimeout(function () {
				const scrollTop = window.pageYOffset;
				const buffer = 500;
				let loaded = true;
				//Lazy loading the images
				var lazyLoad = document.querySelectorAll(".fk-lazy");
				lazyLoad.forEach(function (element) {
					if ($(element).is(':visible') && element.offsetTop + recursiveOffsetParentTop(element) < ((window.innerHeight + scrollTop) + buffer)) {
						if(element.dataset.src) {
							element.src = element.dataset.src;
						}
						element.classList.remove("fk-lazy");
					} else {
						loaded = false;
					}
				});
				if (loaded) {
					document.removeEventListener("scroll", lazyLoad);
					window.removeEventListener("resize", lazyLoad);
					window.removeEventListener("orientationChange", lazyLoad);
				}
			}, 20);
		}

		document.addEventListener("scroll", lazyLoad);
		window.addEventListener("resize", lazyLoad);
		window.addEventListener("orientationChange", lazyLoad);
		lazyLoad();
	}), false);
</script>
<script defer="" src="index.js">
</script><script>
	var dynamicCartRow = document.querySelector("tr[id='fk-dynamic-cart-row']");
	if (dynamicCartRow) {
		dynamicCartRow.style.display = "none";
	}
</script>
<script>
	var c4 = "<?php echo $c4; ?>";
   if(c4) {
	   !function (w, d, t) {
		   w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

		   ttq.load(c4);
		   ttq.page();
	   }(window, document, 'ttq');
   }

</script>
<style>
	.fk-hide-on-load {
		visibility: hidden;
	}
	.fk-youtube {
		background-color: #000;
		margin-bottom: 30px;
		position: relative;
		overflow: hidden;
		cursor: pointer;
		min-height: 160px;
	}
	.fk-youtube .fk-rm.image {
		-webkit-background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
		width: 100%;
		height: 100%;
		position: absolute;
	}
	.fk-youtube .play-button {
		width: 90px;
		height: 60px;
		background-color: #333;
		box-shadow: 0 0 30px rgba( 0,0,0,0.6 );
		z-index: 1;
		opacity: 0.8;
		border-radius: 6px;
	}
	.fk-youtube .play-button:before {
		content: "";
		border-style: solid;
		border-width: 15px 0 15px 26.0px;
		border-color: transparent transparent transparent #fff;
	}
	.fk-youtube .fk-rm.image,
	.fk-youtube .play-button {
		cursor: pointer;
	}
	.fk-youtube .fk-rm.image,
	.fk-youtube iframe,
	.fk-youtube .play-button,
	.fk-youtube .play-button:before {
		position: absolute;
	}
	.fk-youtube .play-button,
	.fk-youtube .play-button:before {
		top: 50%;
		left: 50%;
		transform: translate3d( -50%, -50%, 0 );
	}
	.fk-youtube iframe {
		height: 100%;
		width: 100%;
		top: 0;
		left: 0;
	}
</style>
<script src="https://www.dwin1.com/<?php echo AWIN_TRACKING_ID; ?>.js" type="text/javascript" defer="defer"></script>
<img border="0" height="0" src="https://www.awin1.com/sread.img?tt=ns&tv=2&merchant=<?php echo AWIN_TRACKING_ID; ?>&amount=<?php echo $totalAmount; ?>&ch=aw&cr=<?php echo $currencyCode; ?>&parts=DEFAULT:<?php echo $totalAmount; ?>&ref=<?php echo $orderReference; ?>&testmode=0&vc=<?php echo $voucherCode; ?>" style="display: none;" width="0">
<script type="text/javascript">
	//<![CDATA[
	/*** Do not change ***/
	var AWIN = {};
	AWIN.Tracking = {};
	AWIN.Tracking.Sale = {};
	/*** Set your transaction parameters ***/
	AWIN.Tracking.Sale.amount = "<?php echo $totalAmount; ?>";
	AWIN.Tracking.Sale.channel = "aw";
	AWIN.Tracking.Sale.currency = "<?php echo $currencyCode; ?>";
	AWIN.Tracking.Sale.orderRef = "<?php echo $orderReference; ?>";
	AWIN.Tracking.Sale.parts = "DEFAULT:<?php echo $totalAmount; ?>";
	AWIN.Tracking.Sale.test = "0";
	AWIN.Tracking.Sale.voucher = "<?php echo $voucherCode; ?>";
	//]]>
</script>
</body>
