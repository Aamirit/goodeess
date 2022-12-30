<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<style>
	    #status_filter {
        height: 34px !important;
    }
	#image_collection{
		display: flex;
		justify-content: space-evenly;
	}
	#image_collection i{
		color: red;
		background: white;
		cursor: pointer;
	}
	.form-control2{
		display: block;
		width: 10% !important;
		height: calc(1.25rem + 2px) !important;
	}
	.hide{
		display: none !important;
	}
	#image_collection img{
		max-width : 100% !important;
	}
</style>
<?php
$countryList = array(
	"DE" => "Germany",
	"ES" => "Spain",
	"PT" => "Portugal",
	"FR" => "France"
);

?>
<div class="content-wrapper" style="min-height: 1302.4px;">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Orders</h1>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<div class="container-fluid">
	<div class="row">
		<!-- Status Filter Start-->
						<div class="col-md-4">
								<div class="form-group filters row">
										<label class="text-right col-sm-4 control-label no-padding-right" for="form-field-1"> Status: </label>
										<div class="col-sm-8">
												<select class="form-control status_filter all_filters select2" id="status_filter" name="status_filter">
														<option value="">Select Status</option>
														<option value="1">Successful</option>
														<option value="0">Failed</option>
												</select>
										</div>
								</div>
						</div>
			<!-- Status Filter End-->

			<!-- Date Filter Start -->
			<div class="col-md-4">
								<div class="form-group filters row">
										<label class="text-right col-sm-4 control-label no-padding-right" for="form-field-1"> From Date: </label>
										<div class="col-sm-8">
												<input type="date" name="from_date" id="from_date" class="form-control date_range">
										</div>
								</div>
						</div>
						<div class="col-md-4">
								<div class="form-group filters row">
										<label class="text-right col-sm-4 control-label no-padding-right" for="form-field-1"> To Date: </label>
										<div class="col-sm-8">
												<input type="date" name="to_date" id="to_date" class="form-control date_range">
										</div>
								</div>
						</div>
			<!-- Date Filter End -->
	</div>
	</div>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="row mr-3 mt-3" style="justify-content: space-between;">
							<div class="col-2 ml-3">
<!--								<select name="compaign_filter" id="compaign_filter" class="form-control">-->
<!--									<option value="">Select Campaign</option>-->
<!--									--><?php //foreach($campaigns as $campaign){ ?>
<!--										<option value="--><?php //echo $campaign['campaign_id'];?><!--">--><?php //echo $campaign['campaign_title'];?><!--</option>-->
<!--									--><?php //} ?>
<!--								</select>-->
							</div>
						</div>
						<!-- <div class="card-header">
						  <h3 class="card-title">Products</h3>
						</div> -->
						<!-- /.card-header -->
						<div class="card-body">
							<div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
								<div class="row">
									<div class="col-sm-12">
										<table id="datatable" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
											<thead>
											<tr>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Name
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Email
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Order ID
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Total
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Status
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Action
												</th>
											</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
									<!-- /.card-body -->
								</div>
								<!-- /.card -->
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</div>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h5 class="modal-title order-status" id="staticBackdropLabel"><?php echo $sss ; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<div class="container order_data_here">
          
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div> -->
    </div>
  </div>
</div>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js" defer></script>

<script>
	$(document).ready(function() {

		$(document).on('click', '.view_order', function (e) {
			e.preventDefault();
			$('.order_data_here').empty();
			var id = $(this).attr("rel");
			if (id == '') {
				return false;
			}
			$.ajax({
				url: base_url + "admin/orders/view_order/" + id,
				type: "GET",
				dataType: 'html',
				success: function (data) {
					$('.modal-content').html(data);
					$('#staticBackdrop').modal("show");
      	},
    	});
    return false;
  });

// error alert
  $(document).on('click', '.view_error', function () {
	var errordata = $(this).attr("data");
	swal({
			title: "Error",
			text: errordata,
			icon: "warning",
		});			
  });
// error  alert end

//resend order start
$(document).on('click', '.resend_order', function () {
	var id = $(this).attr("rel");
	var order_id = $(this).attr("data");
	console.log(base_url + "admin/CustomersBigBuy/resend_big_buyorder");
	swal({
		title: "Are you sure?",
		text: "You want to resend order?",
		icon: "info",
		buttons:["NO","Yes"],
	})
	.then((value) => {
		if (value) {
			$.ajax({
				url: base_url + "admin/CustomersBigBuy/resend_big_buyorder",
				type: "POST",
				dataType: 'json',
				data:{id:id,order_id:order_id},
				success: function (data) {
					swal("Success! Order sent successfully!", {
					icon: "success",
				});
					$("#datatable").DataTable().ajax.reload();

				}
			});
		} 
	});
});
//resend order end

		var offertables = $('#datatable').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				"url": "<?php echo base_url('orders/list'); ?>",
				"type": "POST",
				"data": function(d) {
					return $.extend({}, d, {
						// "compaign_filter": $('#compaign_filter').val()
						status_filter: $("#status_filter").val(),
						from_date: $("#from_date").val(),
						to_date: $("#to_date").val()
					});
				}
			},
			"order": [
      [2, 'desc']
    ],
    "columnDefs": [
      { targets: [0, 1, 2, 3, 4, 5], 'orderable': false }
    ],
			"columns": [
				{ "data": "name" },
				{ "data": "email" },
				{ "data": "order_id" },
				{ "data": "total" },
				{ "data": "status" },
				{ "data": "action" }
			]
		});
		$(document).on("change", "#status_filter", function (e) {
   	 $("#datatable").DataTable().ajax.reload();
  	});
		$(document).on("change", "#from_date", function (e) {
    $("#datatable").DataTable().ajax.reload();
 		});
		$(document).on("change", "#to_date", function (e) {
			$("#datatable").DataTable().ajax.reload();
		});
		// $("#compaign_filter").on("change", function (){
		// 	offertables.draw();
		// });

	});
</script>

