<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo base_url(); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/main-style.css'); ?>">
    <script>
        var base_url = '<?php echo base_url(); ?>';
    </script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>
    <title>Checkout</title>
    <style>
        .all_errors {
            color: red;
        }
    </style>
</head>

<body class="bg">
    <?php
        if(!empty($offer['offer_title'])){
            $offer_title = $offer['offer_title'];
        }else{
            $offer_title = 'No Offer Title';
        }

        if(!empty($offer['offer_price'])){
            $offer_price = intval($offer['offer_price']*100);
        }else{
            $offer_price = intval(2.00*100);
        }

        if(!empty($offer['offer_currency'])){
            $offer_currency = strval($offer['offer_currency']);
        }else{
            $offer_currency = strval('EUR');
        }
    ?>

    <header>
        <div class="container">
            <div class="top-header-c">
                <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="">
            </div>
        </div>
    </header>

    <main>
        <div class="container checkout-main">
            <div class="checkout-top-bar">
                <div class="row">
                    <div class="col">
                        <h3> <?php echo $this->lang->line('welcome'); ?></h3>
                    </div>
                </div>
            </div>

            <div class="progress-steps">
                <div class="hr-line">
                    <hr>
                </div>
                <div class="progress-strip">
                    <div class="progress-circle">
                        <div class="inner-circle first"></div>
                        <span><?php echo $this->lang->line('step_1'); ?></span>
                    </div>
                    <div class="progress-circle">
                        <div class="inner-circle second"></div>
                        <span><?php echo $this->lang->line('step_2'); ?></span>
                    </div>
                    <div class="progress-circle">
                        <div class="inner-circle third"></div>
                        <span><?php echo $this->lang->line('step_3'); ?></span>
                    </div>
                </div>
            </div>

            <div class="checkout-main-form">
                <form class="row" method="post" id="payment-form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <?php 
                    if(!empty($slug)){
                    ?>
                        <input type="hidden" name="slug" value="<?php echo $slug; ?>" />
                        <input type="hidden" name="current_url" value="<?php echo base_url().'checkout/'.$slug; ?>" />
                        <?php 
                    }
                    ?>
                    <input type="hidden" name="offer_currency" value="<?php echo $offer_currency; ?>">
                    <input type="hidden" name="offer_price" value="<?php echo $offer_price; ?>">
                    <div class="col-md-4">
                        <div class="p_inner">
                            <h3><?php echo $this->lang->line('step_1_title'); ?></h3>
                            <div class="click_errors all_errors"></div>
                            <div class="lead_errors all_errors"></div>
                            <div class="order_errors all_errors"></div>
                            <div class="form-group">
                                <label for="firstName"><?php echo $this->lang->line('first_name'); ?>*</label>
                                <input type="text" name="firstName" class="form-control" id="firstName" required>
                            </div>
                            <div class="firstName_error all_errors">fits name is requird</div>
                            <div class="form-group">
                                <label for="lastName"><?php echo $this->lang->line('last_name'); ?>*</label>
                                <input type="text" name="lastName" class="form-control" id="lastName" required>
                            </div>
                            <div class="lastName_error all_errors"></div>
                            <div class="form-group">
                                <label for="address1"><?php echo $this->lang->line('address'); ?>*</label>
                                <input type="text" name="address1" class="form-control" id="address1" required>
                            </div>
                            <div class="address1_error all_errors"></div>
                            <div class="form-group">
                                <label for="phoneNumber"><?php echo $this->lang->line('mobile_no'); ?>*</label>
                                <input type="number" name="phoneNumber" class="form-control" id="phoneNumber" required>
                            </div>
                            <div class="phoneNumber_error all_errors"></div>
                            <div class="form-group">
                                <label for="country"><?php echo $this->lang->line('country'); ?></label>
                                <select name="country" id="country" class="form-control">
                                    <option value="DE">Germany</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city"><?php echo $this->lang->line('city'); ?>*</label>
                                <input type="text" name="city" class="form-control" id="city" required>
                            </div>
                            <div class="city_error all_errors"></div>
                            <div class="form-group">
                                <label for="postalCode"><?php echo $this->lang->line('postal_code'); ?>*</label>
                                <input type="text" name="postalCode" class="form-control" id="postalCode" required>
                            </div>
                            <div class="postalCode_error all_errors"></div>
                            <div class="form-group">
                                <label for="email"><?php echo $this->lang->line('email'); ?>*</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="email_error all_errors"></div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="billShipSame">
                                <label class="form-check-label" for="billShipSame"><?php echo $this->lang->line('other_address'); ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p_inner">
                            <h3><?php echo $this->lang->line('step_2_title'); ?></h3>
                            <div class="payment-errors all_errors"></div>
                            <div class="securion_errors all_errors"></div>
                            <div class="crediet">
                                <label>
                                    <input type="radio" checked>
                                    <img src="<?php echo base_url('assets/img/payment.png'); ?>" alt="">
                                </label>
                                <div class="crediet-name">
                                    <span>
                                        Visa - MasterCard - Maestro - American Express 
                                    </span>
                                </div>
                            </div>
                            <!--securion form data fields-->
                            <div class="form-group">
                                <label for="cardNumber"><?php echo $this->lang->line('card_no'); ?></label>
                                <input type="number" class="form-control" id="cardNumber" maxlength="20" data-securionpay="number" required>
                            </div>
                            <div class="form-group">
                                <label for="CVV"><?php echo $this->lang->line('cvv'); ?></label>
                                <input type="text" class="form-control" id="cardSecurityCode" maxlength="3" data-securionpay="cvc" required>
                            </div>
                            <div class="form-group">
                                <label for="cardMonth"><?php echo $this->lang->line('expiry_month'); ?></label>
                                <select id="cardMonth" maxlength="2"  class="form-control" data-securionpay="expMonth" required>
                                    <option value=""><?php echo $this->lang->line('month'); ?></option>
                                    <option value="01"> 01</option>
                                    <option value="02"> 02</option>
                                    <option value="03"> 03</option>
                                    <option value="04"> 04</option>
                                    <option value="05"> 05</option>
                                    <option value="06"> 06</option>
                                    <option value="07"> 07</option>
                                    <option value="08"> 08</option>
                                    <option value="09"> 09</option>
                                    <option value="10"> 10</option>
                                    <option value="11"> 11</option>
                                    <option value="12"> 12</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cardYear"><?php echo $this->lang->line('expiry_year'); ?></label>
                                <select id="cardYear" maxlength="4"  class="form-control" data-securionpay="expYear" required>
                                    <option value=""><?php echo $this->lang->line('year'); ?></option>
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
                        </div>
                    </div>

                    <!--securion form data fields-->
                    <div class="col-md-4">
                        <div class="p_inner right">
                            <h3><?php echo $this->lang->line('step_3_title'); ?></h3>
                            <div class="product-detail">
                                <div class="top-det">
                                    <div class="top-left">
                                        <span class="left">
                                        <?php echo $this->lang->line('amount'); ?>
                                        </span>&nbsp;&nbsp;
                                        <span class="left">
                                        <?php echo $this->lang->line('description'); ?>
                                            <br><br>
                                           <span> 
                                           <?php 
                                               echo $offer_title;
                                            ?>   
                                            </span>
                                        </span>
                                    </div>
                                    <div class="top-left">
                                        <span class="right">
                                        <?php echo $this->lang->line('price_per_unit'); ?>
                                            <br><br>
                                            <span>
                                                €<?php 
                                                    echo $offer_price;
                                                ?>
                                            </span>
                                        </span>&nbsp;&nbsp;
                                        <span class="right">
                                        <?php echo $this->lang->line('total'); ?>
                                            <br><br>
                                            <span>
                                                €<?php 
                                                    echo $offer_price;
                                                ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="bottom-det">
                                    <div class="btm-inner">
                                        <div><?php echo $this->lang->line('subtotal'); ?></div>
                                        <div>
                                            €<?php 
                                                echo $offer_price;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="btm-inner">
                                        <div><?php echo $this->lang->line('vat'); ?></div>
                                        <div>19%</div>
                                    </div>
                                    <div class="btm-inner">
                                        <div><?php echo $this->lang->line('trial_period'); ?></div>
                                        <div>€0.00</div>
                                    </div>
                                    <div class="btm-inner">
                                        <div><?php echo $this->lang->line('delivery_days'); ?></div>
                                        <div>€0.00</div>
                                    </div>
                                    <div class="total">
                                        <div><?php echo $this->lang->line('grand_total'); ?></div>
                                        <div>
                                            €<?php 
                                                echo $offer_price;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="accept-ord"><?php echo $this->lang->line('accept_order'); ?></div>
                                    <div class="form-radio">
                                        <div>
                                            <input type="radio" name="goodeess-terms" required>
                                        </div>
                                        <div>
                                        <?php echo $this->lang->line('terms'); ?>
                                        </div>
                                    </div>
                                    <div class="btn">
                                        <button class="btn-checkout" type="submit"><?php echo $this->lang->line('accept_order'); ?></button>
                                    </div>
                                    <div class="brands">
                                        <img src="<?php echo base_url('assets/img/brands.jpeg'); ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </main>


    <footer class="footer-checkout container">
        <div class="footer-logo">
            <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="">
        </div>
        <div class="footer-social">
            <div>
                <a href=""><img src="<?php echo base_url('assets/img/fab.png'); ?>" alt=""></a>
            </div>
            <div>
                <a href=""><img src="<?php echo base_url('assets/img/insta.png'); ?>" alt=""></a>
            </div>
            <div>
                <a href=""><img src="<?php echo base_url('assets/img/print.png'); ?>" alt=""></a>
            </div>
            <div>
                <a href=""><img src="<?php echo base_url('assets/img/insta.png'); ?>" alt=""></a>
            </div>
            <div>
                <a href=""><img src="<?php echo base_url('assets/img/youtb.png'); ?>" alt=""></a>
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
                    <a href=""><?php echo $this->lang->line('link_2'); ?></a>
                </li>
                <li>
                    <a href=""><?php echo $this->lang->line('link_3'); ?></a>
                </li>
                <li>
                    <a href=""><?php echo $this->lang->line('link_1'); ?></a>
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

    <script type="text/javascript" src="https://securionpay.com/js/securionpay.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            Securionpay.setPublicKey('pk_test_hewuceVPWzE69aXFcX8zPs3X');

            $(document).on('submit', '#payment-form', function(e){
                var form = $('#payment-form');
                form.find('button').prop('disabled', true);
                Securionpay.createCardToken(form, createCardTokenCallback);
                return false;
            });

            function createCardTokenCallback(token) {
                var form = $('#payment-form');
                if (token.error) {
                    form.find('.payment-errors').text(token.error.message);
                    form.find('button').prop('disabled', false);
                } else {
                    SecurionPay.verifyThreeDSecure({
                        amount: <?php echo $offer_price; ?>,
                        currency: "<?php echo $offer_currency; ?>",
                        card: token.id
                    }, verifyThreeDSecureCallback);
                }
            }

            function verifyThreeDSecureCallback(token) {
                var form = $('#payment-form');
                if (token.error) {
                    form.find('.payment-errors').text(token.error.message);
                    form.find('button').prop('disabled', false);
                } else {
                    form.append($('<input type="hidden" name="token" />').val(token.id));
                    form.unbind();
                    $.ajax({
                        url: base_url + 'home/checkout_process',
                        type: 'POST',
                        data: new FormData(document.getElementById("payment-form")),
                        dataType: 'JSON',
                        processData: false,
                        contentType: false,
                        success: function(data){
                            if(data.response===true){
                                window.location = data.redirect_url;
                            }else {
                                if(data.errors){
                                    errors(data.errors);
                                }
                                if(data.IMPORT_ORDER_ERROR){
                                    $('.order_errors').html(data.IMPORT_ORDER_ERROR);
                                }
                                if(data.IMPORT_LEAD_ERROR){
                                    $('.lead_errors').html(data.IMPORT_LEAD_ERROR);
                                }
                                if(data.IMPORT_CLICK_ERROR){
                                    $('.click_errors').html(data.IMPORT_CLICK_ERROR);
                                }
                                if(data.SecurionPay_ERROR){
                                    $('.securion_errors').html(data.SecurionPay_ERROR);
                                }
                            }
                        }
                    });
                }
            }
            
    function errors(arr = ''){
        if(arr != ''){
            $.each(arr, function( key, value ) {
            $('.'+key+'_error').html(value);
            });
        }
        return false;
    }

    });
    </script>

</body>

</html>
