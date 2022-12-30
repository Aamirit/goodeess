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
    <title>Thank you</title>
</head>

<body>
<?php
        if(!empty($offer['offer_title'])){
            $offer_title = $offer['offer_title'];
        }else{
            $offer_title = 'No Offer Title';
        }

        if(!empty($offer['offer_price'])){
            $offer_price = $offer['offer_price'];
        }else{
            $offer_price = 2.00;
        }

    ?>
    <header class="container thankyou">
        <div class="top-logot">
            <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="">
        </div>
    </header>

    <section class="herobanner_thankyou">
        <div class="container">
            <div class="row">
                <div class="col-md-4 hero_main">
                    <div class="hero_heading">
                        <h1><?php echo $this->lang->line('heading_1'); ?></h1>
                    </div>
                    <div class="divider"></div>
                    <div class="hero_details">
                        <p>
                        <?php echo $this->lang->line('heading_1_p_1'); ?>
                            <br><br> <?php echo $this->lang->line('heading_1_p_2'); ?>
                            <br><br> <?php echo $this->lang->line('heading_1_p_3'); ?>
                            <br><br> <?php echo $this->lang->line('heading_1_p_4'); ?>
                        </p>
                        <a href=""><?php echo $this->lang->line('order_status'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div>
        <img class="img-fluid" src="<?php echo base_url('assets/img/logostrip.jpg'); ?>" alt="">
    </div>

    <main class="thankyouMain bgthankyou">
        <div class="container">
            <div class="row orderInfo">
                <div class="col-md-4">
                    <h1><?php echo $this->lang->line('heading_2'); ?></h1>
                    <div class="divider"></div>
                    <p>
                    <?php echo $this->lang->line('heading_2_p_1'); ?>
                    </p>
                    <div class="orderinfo_summary">
                        <div class="summary_head"><?php echo $this->lang->line('SYNTHESIS'); ?></div>
                        <div class="summary_body">
                            <div class="summary_table">
                                <div><?php echo $this->lang->line('product'); ?></div>
                                <div><?php echo $this->lang->line('price'); ?></div>
                            </div>
                            <div class="summary_table">
                                <div>
                                    <?php echo $offer_title; ?>
                                </div>
                                <div><?php echo $offer_price; ?>€</div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:void()" class="order-status"><?php echo $this->lang->line('checkout'); ?></a>
                </div>
                <div class="col-md-8">
                    <div class="row thankyou_product_dtl">
                        <div class="col-md-3 col-sm-3 prdt_img">
                            <img src="<?php echo base_url('assets/img/logo.png'); ?>" class="img-fluid" alt="">
                        </div>
                        <div class="col-md-2 col-sm-3 qty">
                            <p>1</p>
                        </div>
                        <div class="col-md-4 col-sm-3 prdt_name">
                            <p>
                                <?php echo $offer_title; ?>
                            </p>
                        </div>
                        <div class="col-md-3 col-sm-3 prdt_price">
                            <p> <?php echo $offer_price; ?>€ </p>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-5 thankyou_subtotal">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><?php echo $this->lang->line('subtotal'); ?>: </p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p> <?php echo $offer_price; ?>€ </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><?php echo $this->lang->line('transport'); ?>: </p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p> <?php echo $this->lang->line('free'); ?> </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><?php echo $this->lang->line('vat'); ?> </p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p> 19% </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><?php echo $this->lang->line('grand'); ?>: </p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p> <?php echo $offer_price; ?>€ </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <div class="gap"></div>

    <footer class="thankyou_footer">
        <div class="footer-row row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div>
                    <span class="footer-h1">Shop</span>
                    <ul>
                        <li><a href="">Tout magasiner</a></li>
                        <li><a href="">Beauté et santé</a></li>
                        <li><a href="">Gadgets</a></li>
                        <li><a href="">Électronique</a></li>
                        <li><a href="">Maison & Jardin</a></li>
                        <li><a href="">Des sports</a></li>
                        <li><a href="">Jouets et enfants</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div>
                    <span class="footer-h1">Informations</span>
                    <ul>
                        <li><a href="">Politique de retour et de remboursement</a></li>
                        <li><a href="">Conditions générales</a></li>
                        <li><a href="">Politique en matière de cookies</a></li>
                        <li><a href="">Politique de confidentialité</a></li>
                        <li><a href="">Clause de non-responsabilité</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div>
                    <span class="footer-h1">Customer service </span>
                    <ul>
                        <li><a href="">Termes de recherche</a></li>
                        <li><a href="">Recherche Avancée</a></li>
                        <li><a href="">Recherche de commande et de retour</a></li>
                        <li><a href="">Nous contacter</a></li>
                        <li><a href="">Aide et FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div>
                    <span class="footer-h1">INSCRIVEZ-VOUS À NOTRE BULLETIN D'INFORMATION </span>
                    <span class="footer-p">
                        Recevez nos dernières mises à jour sur nos produits et nos promotions. 
                    </span><br>
                    <span class="footer-h1">RESTER CONNECTÉ </span>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>