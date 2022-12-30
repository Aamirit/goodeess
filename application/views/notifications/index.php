<div class="cw">

	<div class="flexrow sc-row">

		<div>


			<div class="kf-gallery">

				<div>

					<div class="kf-gallery-selection ps">
						<?php
						foreach ($images as $image) {
							?>
							<a href="javascript:void(0);">

								<div style="background-image:url(<?php echo base_url();?>assets/uploads/<?php echo $image['offer_image'];?>)">

								</div>
							</a>
						<?php }	?>

						<div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>

				</div>
				<div>
					<?php  if(!empty($images)){  ?>
						<div class="kf-gallery-scene" style="background-image: url(<?php echo base_url();?>assets/uploads/<?php echo $images[0]['offer_image'];?>);">
						</div>
					<?php } ?>
				</div>

			</div>
		</div>

		<div>

			<div class="sc-prodname"><?php echo !empty($offer['offer_title']) ? $offer['offer_title'] : '';  ?></div>

			<div class="sc-rating">

				<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>

				<div class="rating-value" style="width:80%">

					<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>

				</div>

			</div>



			<span class="sc-rating-no">(80) <a href="#"><?php echo $this->lang->line('write_review'); ?></a></span>
			<?php  if($offer['discount_offer_price'] != 0) {?>
				<div class="sc-pricing">
					<span class="pricing-new"><span data-product_price="<?php echo $offer['discount_offer_price'];?>" id="price_label"><?php echo $offer['discount_offer_price'];?></spanid>€</span>
					<span class="pricing-old"><?php echo $this->lang->line('cost'); ?> <span><?php echo $offer['offer_price'];?></span>€</span>
				</div>
				<div class="sc-dctxt">
					<?php echo $this->lang->line('save_now'); ?> <span>(<?php echo $discount; ?>%)</span>
				</div>
			<?php } else { ?>
				<div class="sc-pricing">
					<span class="pricing-new"><span data-product_price="<?php echo $offer['discount_offer_price'];?>" id="price_label"><?php echo $offer['offer_price'];?></span>€</span>
				</div>
			<?php } ?>


			<div class="sc-icons"><i class="fas fa-fire animate__animated animate__infinite animate__flash" style="color:#ef6654;font-size:1.5em"></i><?php echo $this->lang->line('sold_product'); ?></div>

			<div class="sc-icons"><i class="fa fa-truck"></i><?php echo $this->lang->line('shipping_days'); ?></div>

			<div class="sc-icons"><i class="fa fa-clock"></i><?php echo $this->lang->line('availible_stock'); ?></div>

			<div class="sc-icons"><i class="fa fa-users"></i><span class="periodically-increment-js">158</span> <?php echo $this->lang->line('people_looking'); ?></div>

			<div class="couponinsert">

				<div>

					<input type="text" id="email" placeholder="<?php echo $this->lang->line('notification_place_holder'); ?>" required>

				</div>

				<div>

					<a data-offer_id="<?php echo $offer['offer_id']; ?>" href="javascript:void(0);" class="hoverbtn couponbtn ylwbtn" id="notification_btn"><span><?php echo $this->lang->line('notification'); ?></span></a>

				</div>

			</div>

			<div id="coupon_success" style="display: none;">

				<div class="stylish-btn cta-btn animate__animated animate__infinite animate__pulse" style="background-color: #00AAEA;"><span><span id="coupon_percentage"></span>% de réduction appliquée</span></div>

			</div>



			<img src="<?php echo base_url();?>assets/layout2/img/gcred.png" class="imgresp">
		</div>

	</div>

	<div class="sc-divider"></div>

	<div class="flexrow sc-row-icons">

		<div>

			<div class="row-icon">

				<div><img src="<?php echo base_url();?>assets/layout2/img/i1.png"></div>

				<div>

					<div class="row-icon-h1"><?php echo $this->lang->line('secure_payment'); ?></div>

					<div><?php echo $this->lang->line('secure_payment_d'); ?></div>

				</div>

			</div>

		</div>

		<div>

			<div class="row-icon">

				<div><img src="<?php echo base_url();?>assets/layout2/img/i2.png"></div>

				<div>

					<div class="row-icon-h1"><?php echo $this->lang->line('trust'); ?></div>

					<div><?php echo $this->lang->line('trust_d'); ?></div>

				</div>

			</div>

		</div>

		<div>

			<div class="row-icon">

				<div><img src="<?php echo base_url();?>assets/layout2/img/i3.png"></div>

				<div>

					<div class="row-icon-h1"><?php echo $this->lang->line('delivery'); ?></div>

					<div><?php echo $this->lang->line('delivery_d'); ?></div>

				</div>

			</div>

		</div>

		<div>

			<div class="row-icon">

				<div><img src="<?php echo base_url();?>assets/layout2/img/i4.png"></div>

				<div>

					<div class="row-icon-h1"><?php echo $this->lang->line('after_sale'); ?></div>

					<div><?php echo $this->lang->line('after_sale_d'); ?></div>

				</div>

			</div>

		</div>

	</div>

	<div class="spacer"></div>
	<div class="sc-tab-parent">

		<div class="sc-info-tabs">

			<a href="javascript:void(0)" class="active"><?php echo $this->lang->line('tab_1'); ?></a>

			<a href="javascript:void(0)"><?php echo $this->lang->line('tab_2'); ?></a>

			<a href="javascript:void(0)"><?php echo $this->lang->line('tab_3'); ?></a>

			<a href="javascript:void(0)"><?php echo $this->lang->line('tab_4'); ?></a>

		</div>

		<div class="sc-info-tab-panel">

			<div>
				<?php echo $offer['offer_description'];?>
			</div>

			<div>
				<?php echo $offer['offer_features'];?>
			</div>

			<div>

				<div class="sc-tab-h1">Les clients demandant un remboursement envoient les produits à:</div><br>

				<div>Vous pouvez contacter le service des retours sur return@goodeess.com</div><br>

				<div>Veuillez inclure votre numéro de commande.</div><br>

				<div>Les remboursements sont transférés sur votre compte bancaire dans les 10 jours ouvrables suivant la réception des marchandises.</div><br>

				<div>En cas de produits défectueux, nous vous rembourserons vos frais de port.</div><br>

				<div>Veuillez envoyer les retours par courrier postal, car les services de colis ou de courrier recommandé sont une dépense inutile.</div><br>

				<div class="sc-tab-h1">Remplacement de marchandises</div><br>

				<div>Il peut y avoir plusieurs raisons pour souhaiter échanger vos produits. Si vous trouvez un défaut par exemple, ou si votre produit a été endommagé pendant le transport. Dans tous les cas, nous nous excusons pour toute commande inférieure à nos niveaux de qualité habituels. Dans ces situations, nous vous recommandons de demander un remplacement et je vous assure qu'au moins 99% de nos commandes arrivent en parfait état.</div><br>

				<div>Si la valeur de la commande/du produit est faible, par ex. Un produit défectueux ou un article d'une valeur inférieure à 5 € veuillez nous contacter avant de renvoyer l'article afin que nous puissions trouver une solution. Dans certains cas, il ne sera même pas nécessaire de retourner votre article. les produits ou les échanges doivent être envoyés à:</div><br>

				<div>N'oubliez pas de joindre la confirmation de votre commande.</div><br>

				<div class="sc-tab-h1">Annulation de l'adhésion</div><br>

				<div>L'envoi d'un retour n'annule pas automatiquement votre adhésion.</div><br>

				<div>Si vous ne souhaitez plus être membre du Goodeess Plus +, n'oubliez pas d'annuler votre adhésion.</div><br>

				<div>Veuillez avoir votre numéro de commande à portée de main.</div><br>
			</div>

			<div class="js-comments-target">



			</div>

		</div>

	</div>
	<div class="flexrow logoreel">

		<div><img src="<?php echo base_url();?>assets/layout2/images/<?php echo $geo;   ?>/t1.jpg"></div>

		<div><img src="<?php echo base_url();?>assets/layout2/img/<?php echo $geo;   ?>/t2.jpg"></div>

		<div><img src="<?php echo base_url();?>assets/layout2/img/<?php echo $geo;   ?>/t3.jpg"></div>

		<div><img src="<?php echo base_url();?>assets/layout2/img/<?php echo $geo;   ?>/t4.jpg"></div>

	</div>
</div>
<script>
	$(document).ready(function (){
		function validateEmail(email) {
			const res = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return res.test(String(email).toLowerCase());
		}
	$("#notification_btn").click(function (e){
			e.preventDefault();
			if($("#email").val() == ''){
				alert('Email field is required');
				console.error('Email field is required');
				return true;
			}
			if(!validateEmail($("#email").val())){
				console.error('Invalid Email field');
				alert('Invalid Email');
				return true;
			}
			$.ajax({
				url: base_url + "get/notification",
				method: 'POST',
				data : {product_id:$(this).data('offer_id'),email:$("#email").val()}
			}).then(function (response){
				const data = JSON.parse(response);
				if(data.status){
					alert(data.message);
					$("#email").val('');
				} else {
					console.error(data.message);
					alert(data.message);
				}
			});
		})
	})
</script>
