<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo base_url();  ?>assets/checkout/checkout.css">
	<title>Checkout</title>
</head>

<body>
<section class="section">
	<div class="checkout">
		<div class="main-logo">
			<img src="<?php echo base_url();  ?>assets/img/logo.png" alt="">
		</div>
		<div class="promo-heading">
			KOSTENLOSER VERSAND FÜR ALLE HEUTE AUFGEGEBENEN BESTELLUNGEN
		</div>
		<div class="form-section">
			<div class="form-steps">
				<div class="hr-line">
					<hr>
				</div>
				<div class="progress-strip">
					<div class="progress-circle">
						<div class="inner-circle first"></div>
						<span>Address</span>
					</div>
					<div class="progress-circle">
						<div class="inner-circle second"></div>
						<span>Zahlung</span>
					</div>
					<div class="progress-circle">
						<div class="inner-circle third"></div>
						<span>Es ist vollbracht ! </span>
					</div>
				</div>
			</div>
			<div class="form-content">
				<div class="address-form">
					<div class="p-all">
						<div class="f-heading">
							<span>Rechnungsadresse</span>
						</div>
						<form>
							<div class="input-group">
								<label for="Vorname">Vorname*</label><br>
								<input type="text" name="vorname" class="form-control-custom">
							</div>
							<div class="input-group">
								<label for="Name">Name*</label><br>
								<input type="text" name="name" class="form-control-custom">
							</div>
							<div class="input-group">
								<label for="Address">Address*</label><br>
								<input type="text" name="address" class="form-control-custom">
							</div>
							<div class="input-group">
								<label for="Handy-Nr">Handy-Nr.*</label><br>
								<input type="number" name="handy-nr" class="form-control-custom">
							</div>
							<div class="input-group">
								<label for="Land">Land*</label><br>
								<select name="Land" id="country" class="form-control-custom">
									<option value="DE">Germany</option>
								</select>
							</div>
							<div class="input-group">
								<label for="Stadt">Stadt*</label><br>
								<input type="text" name="stadt" class="form-control-custom">
							</div>
							<div class="input-group">
								<label for="Postleitzahl">Postleitzahl*</label><br>
								<input type="text" name="postleitzahl" class="form-control-custom">
							</div>
							<div class="input-group">
								<label for="E-mail">E-mail *</label><br>
								<input type="email" name="e-mail" class="form-control-custom">
							</div>
							<div class="input-group checkbox">
								<input type="checkbox" name="checkbox">
								<span>An eine andere Adresse senden</span>
							</div>
						</form>
					</div>
				</div>
				<div class="payment-form">
					<div class="p-all">
						<div class="f-heading">
							<span>Zahlungsweise</span>
						</div>
						<form>
							<div class="crediet">
								<label>
									<input type="radio" checked>
									<img src="<?php echo base_url();  ?>assets/img/payment.png" alt="">
								</label>
								<div class="crediet-name">
                                        <span>
                                            Visa - MasterCard - Maestro - American Express
                                        </span>
								</div>
							</div>
							<div class="input-group">
								<label for="Vorname">Kartennummer</label><br>
								<input type="number" name="kartennummer" class="form-control-custom">
							</div>
							<div class="input-group">
								<label for="Vorname">CVV</label><br>
								<input type="text" name="cvv" class="form-control-custom">
							</div>
							<div class="input-group">
								<label for="Karte-Ablaufmonat">Karte Ablaufmonat</label><br>
								<select name="Land" id="expiryMonth" class="form-control-custom">
									<option value="">Mont</option>
									<option value="01">01</option>
									<option value="02">02</option>
									<option value="03">03</option>
									<option value="04">04</option>
									<option value="05">05</option>
									<option value="06">06</option>
									<option value="07">07</option>
									<option value="08">08</option>
									<option value="09">09</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
								</select>
							</div>
							<div class="input-group">
								<label for="Karte-Ablaufjahr">Karte Ablaufjahr</label><br>
								<select name="Land" id="expiryyear" class="form-control-custom">
									<option value="2022">2022</option>
									<option value="2023">2023</option>
									<option value="2024">2024</option>
									<option value="2025">2025</option>
									<option value="2026">2026</option>
									<option value="2027">2027</option>
									<option value="2028">2028</option>
									<option value="2029">2029</option>
									<option value="2030">2030</option>
									<option value="2031">2031</option>
									<option value="2032">2032</option>
								</select>
							</div>
						</form>
					</div>
				</div>
				<div class="finished-form">
					<div class="p-all">
						<div class="f-heading">
							<span>Bestellzusammenfassung</span>
						</div>
						<div class="product-detail">
							<div class="top-det">
								<div class="top-left">
                                        <span class="left">
                                            Menge
                                        </span>&nbsp;&nbsp;
									<span class="left">
                                            Beschreibung
                                            <br><br>
                                           <span> PROFI-HAARSTRIMMER </span>
                                        </span>
								</div>
								<div class="top-left">
                                        <span class="right">
                                            Preis pro Einheit
                                            <br><br>
                                            <span>€19.95</span>
                                        </span>&nbsp;&nbsp;
									<span class="right">
                                            Insgesamt
                                            <br><br>
                                            <span>€19.95</span>
                                        </span>
								</div>
							</div>
							<div class="bottom-det">
								<div class="btm-inner">
									<div>Zwischensumme</div>
									<div>€19.95</div>
								</div>
								<div class="btm-inner">
									<div>MwSt</div>
									<div>19%</div>
								</div>
								<div class="btm-inner">
									<div>Kostenloser 14-tägiger Testzeitraum </div>
									<div>€0.00</div>
								</div>
								<div class="btm-inner">
									<div>Standardlieferung 2-7 Werktage</div>
									<div>€0.00</div>
								</div>
								<div class="total">
									<div>Gesamtsumme TTL </div>
									<div>€19.95</div>
								</div>
								<div class="accept-ord">Auftrag annehmen</div>
								<div class="form-radio">
									<div>
										<input type="radio">
									</div>
									<div>
										Ich habe die <a href="">Allgemeinen Geschäftsbedingungen</a> gelesen und akzeptiere sie.a <a href="">Abonnement-Bedingungen</a> von Goodeess.com
									</div>
								</div>
								<div class="btn">
									<button type="button">WEITERGEHEN</button>
								</div>
								<div class="brands">
									<img src="<?php echo base_url();  ?>assets/img/brands.jpeg" alt="">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer class="footer">
			<div class="footer-logo">
				<img src="<?php echo base_url();  ?>assets/img/logo.png" alt="">
			</div>
			<div class="footer-social">
				<div>
					<a href=""><img src="<?php echo base_url();  ?>assets/img/fab.png" alt=""></a>
				</div>
				<div>
					<a href=""><img src="<?php echo base_url();  ?>assets/img/insta.png" alt=""></a>
				</div>
				<div>
					<a href=""><img src="<?php echo base_url();  ?>assets/img/print.png" alt=""></a>
				</div>
				<div>
					<a href=""><img src="<?php echo base_url();  ?>assets/img/insta.png" alt=""></a>
				</div>
				<div>
					<a href=""><img src="<?php echo base_url();  ?>assets/img/youtb.png" alt=""></a>
				</div>
			</div>
			<div class="footer-links">
				<ul class="toplinks">
					<li>
						<a href="">Impressum</a>
					</li>
					<li>
						<a href="">Rücksende- und Rückerstattungsbedingungen</a>
					</li>
					<li>
						<a href="">Datenschutzrichtlinie</a>
					</li>
					<li>
						<a href="">Allgemeine Geschäftsbedingungen</a>
					</li>
					<li>
						<a href="">Rückerstattungsbedingungen</a>
					</li>
					<li>
						<a href="">Richtlinien zu Cookies</a>
					</li>
					<li>
						<a href="">Versandpolitik</a>
					</li>
				</ul>
				<ul class="bottomlinks">
					<li><a href="">Haftungsausschlusserklärung</a></li>
					<li><a href="">Abonnement-Bedingungen</a></li>
				</ul>
			</div>
			<div class="footerText">
				<p> Goodeess
					<br>Copyright © 2021 All rights reserved.</p>
			</div>
		</footer>
	</div>
</section>
</body>

</html>
