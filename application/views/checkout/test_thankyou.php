<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<body class=" dom-pending">
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
