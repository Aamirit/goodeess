<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
                <meta charset="utf-8" />
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <meta name="viewport" content="initial-scale=1.0, width=device-width" />
                    <title><?php echo !empty($title) ?  $title : 'Checkout - Sticky';  ?></title>
 
                <meta name="description" content="overview &amp; stats" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
 
                <!-- bootstrap & fontawesome -->
         <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">

        <style>
        body.dom-pending *,
        .fk-lazy {
            background-image: none !important;
        }
		.btn1 {
		border: none;
		width: 100%;
		cursor: pointer;
		}
        </style>
        
	
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
                <!-- page specific plugin styles -->
				
	   
	   <script src="<?php echo base_url();?>assets/sticky/js/jquery-1.12.4.min.js"></script>
		<!-- Normalize CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/sticky/css/normalize.min.css">
		<!-- Custom CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/sticky/css/styles_2.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/sticky/css/animate_2.css">
		<!-- datejs -->
		<script src="<?php echo base_url();?>assets/sticky/js/date.min.js"></script>
</head>
 
<body>

<!--header sec start-->


<!--header sec end-->

<!--item sec start-->
{_yield}
<!--item sec end-->

<!--footer sec start-->

<!--footer sec end-->


</body>
</html>


