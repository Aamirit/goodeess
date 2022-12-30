<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

<style>
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
					<h1>Products</h1>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="row mr-3 mt-3" style="justify-content: space-between;">
							<div class="col-2 ml-3">
								<select name="compaign_filter" id="compaign_filter" class="form-control">
									<option value="">Select Campaign</option>
									<?php foreach($campaigns as $campaign){ ?>
										<option value="<?php echo $campaign['campaign_id'];?>"><?php echo $campaign['campaign_title'];?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-2 text-right">
								<button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#add-product">
									Add Product
								</button>
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
													Title
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													English Title
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Price
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Discounted Price
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													URL
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Free Offer
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Country Code
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
												Duplicate
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Edit
												</th>
												<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
													Delete
												</th>
											</tr>
											</thead>
											<tbody>
											<?php foreach($offers as $offer){ ?>
												<tr class="odd">
													<td><?php echo $offer['offer_title'];?></td>
													<td><?php echo $offer['offer_price'];?></td>
													<td><?php echo $offer['discount_offer_price'];?></td>
													<td><a target="_blank" href="<?php echo $offer['offer_url'];?>"><?php echo $offer['offer_url'];?></a></td>
													<td><a target="_blank" href="<?php echo $offer['free_offer_url'];?>"><?php echo $offer['free_offer_url'];?></a></td>
													<td><?php echo $offer['country'];?></td>
													<!--                    <td>--><?php //echo $offer['offer_settings'];?><!--</td>-->
													<td>
														<button type="button" class="btn btn-primary btn-flat editBtn"  data-toggle="modal" data-target="#edit-offers" data-id="<?php echo $offer['offer_id'];?>">
															Edit
														</button>
													</td>
													<td>
														<form class="deleteProduct">
															<input type="hidden" name="offer_id" value="<?php echo $offer['offer_id']; ?>">
															<button type="submit" class="btn btn-block btn-danger btn-flat">Delete</button>
														</form>
													</td>
												</tr>
											<?php } ?>
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



<!-- Add Offers Start -->
<div class="modal fade show" id="add-product" style="padding-right: 17px; display: none;" aria-modal="true" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add new Product</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-11 m-auto">
						<div class="card card-primary">
							<!-- /.card-header -->
							<!-- form start -->
							<form id="add_offerForm">
								<div class="card-body row">
									<div class="form-group col-md-6">
										<label for="campaign_id">Select Campaign Title</label>
										<select name="campaign_id" id="campaign_id" class="form-control">
											<option value="">Select Campaign</option>
											<?php foreach($campaigns as $campaign){ ?>
												<option value="<?php echo $campaign['campaign_id'];?>"><?php echo $campaign['campaign_title'];?></option>
											<?php } ?>
										</select>
										<span class="campaign_id_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="offer_crm_id">Offer CRM ID</label>
										<input type="text" name="offer_crm_id" class="form-control" id="offer_crm_id">
										<span class="offer_crm_id_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="offer_title">Product CRM ID</label>
										<input type="text" name="product_id" class="form-control" id="product_id">
										<span class="product_id_error all_error text-danger"></span>
									</div>
									<!--						<div class="form-group col-md-6">-->
									<!--							<label for="offer_title">Billing Model ID</label>-->
									<!--							<input type="text" name="billing_model_id" class="form-control" id="billing_model_id">-->
									<!--							<span class="billing_model_id_error all_error text-danger"></span>-->
									<!--						</div>-->
									<div class="form-group col-md-6">
										<label for="billing_model_id">Billing Model ID</label>
										<select class="form-control" name="billing_model_id" id="billing_model_id">
											<option value="2">Goodeess Membership</option>
											<!--								<option value="2">One Time Purchase</option>-->
										</select>
										<span class="billing_model_id_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="shipping_id">Shipping ID</label>
										<select class="form-control" name="shipping_id" id="shipping_id">
											<option value="3">Free Product</option>
											<option value="2">Free Shipping</option>
										</select>
										<span class="shipping_id_id_error all_error text-danger"></span>
									</div>
									<!--						<div class="form-group col-md-6">-->
									<!--							<label for="shipping_id">Shipping ID</label>-->
									<!--							<input type="text" name="shipping_id" class="form-control" id="shipping_id">-->
									<!--							<span class="shipping_id_id_error all_error text-danger"></span>-->
									<!--						</div>-->
									<div class="form-group col-md-6">
										<label for="offer_title">Product Title</label>
										<input type="text" name="offer_title" class="form-control" id="offer_title">
										<span class="offer_title_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="product_eng_title">English Product Title</label>
										<input type="text" name="product_eng_title" class="form-control" id="product_eng_title">
										<span class="product_eng_title_error all_error text-danger"></span>
									</div>
									<!--                      <div class="form-group col-md-6">-->
									<!--                        <label for="country">Country Code</label>-->
									<!--                        <input type="text" name="country" class="form-control" id="country">-->
									<!--                        <span class="country_error all_error text-danger"></span>-->
									<!--                      </div>-->
									<div class="form-group col-md-6">
										<label for="country">Country Code</label>
										<select class="form-control" name="country" id="country">
											<option value="">Select Country Code</option>
											<?php foreach ($countryList as $key => $value) { ?>
												<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
											<?php } ?>
										</select>
										<span class="country_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6 hide">
										<label for="price_type">Price Type</label>
										<select class="form-control" name="price_type" id="price_type">
											<option value="1">recurring</option>
											<option value="2">one time</option>
										</select>
										<span class="price_type_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="offer_price">Regular Price</label>
										<input type="text" name="offer_price" class="form-control" id="offer_price">
										<span class="offer_price_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="discount_offer_price">Discount Price</label>
										<input type="text" name="discount_offer_price" class="form-control" id="discount_offer_price">
										<span class="discount_offer_price_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="offer_currency">Product Currency (Optional)</label>
										<input type="text" name="offer_currency" class="form-control" id="offer_currency">
										<span class="offer_currency_error all_error text-danger"></span>
									</div>
									<!--						<div class="form-group col-md-6">-->
									<!--							<label for="offer_terms">Offer Terms</label>-->
									<!--							<textarea name="offer_terms" class="form-control" id="offer_terms" cols="30" rows="3"></textarea>-->
									<!--							<span class="offer_terms_error all_error text-danger"></span>-->
									<!--						</div>-->
									<div class="form-group col-md-6 hide">
										<label for="offer_settings">Product Settings</label>
										<textarea name="offer_settings" class="form-control" id="offer_settings" cols="30" rows="5"></textarea>
										<span class="offer_settings_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="offer_features">Product Features</label>
										<textarea id="offer_features" name="offer_features" class="form-control"  cols="30" rows="15" ></textarea>
										<span class="offer_features_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="summernote">Product Description</label>
										<textarea id="summernote" name="offer_description" class="form-control"  cols="30" rows="15" ></textarea>
										<span class="offer_description_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="exampleInputFile">Image</label>
										<div class="input-group">
											<div class="custom-file">
												<input accept="image/*" type="file" name="offer_image[]" class="custom-file-input" id="exampleInputFile" multiple>
												<label class="custom-file-label" for="exampleInputFile">Choose file</label>
											</div>
										</div>
										<span class="offer_image_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6 hide">
										<label for="offer_settings">Featured Product</label>
										<input type="checkbox" name="featured_offer"  class="form-control form-control2"  >
									</div>
								</div>
								<!-- /.card-body -->

								<div class="card-footer">
									<button type="submit" class="btn btn-primary">Add</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div><!--modal-dialog -->
</div>

<!-- Add Campaigns End -->

<!-- Edit Campaigns Model Start-->

<div class="modal fade show" id="edit-offers" style="padding-right: 17px; display: none;" aria-modal="true" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Edit Product</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-11 m-auto">
						<div class="card card-primary">
							<!-- /.card-header -->
							<!-- form start -->
							<form id="edit_offerForm" enctype="multipart/form-data" >
								<input type="hidden" name="offer_id" value="<?php echo $offer['offer_id']; ?>">
								<div class="card-body row">
									<div class="form-group col-md-6">
										<label for="campaign_id">Select Campaign Title</label>
										<select name="campaign_id" id="campaign_id" class="form-control">
											<?php foreach($campaigns as $campaign){ ?>
												<option value="<?php echo $campaign['campaign_id'];?>"><?php echo $campaign['campaign_title'];?></option>
											<?php } ?>
										</select>
										<span class="campaign_id_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="offer_crm_id">Offer CRM ID</label>
										<input type="text" name="offer_crm_id" class="form-control" id="offer_crm_id">
										<span class="offer_crm_id_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="offer_title">Product CRM ID</label>
										<input type="text" name="product_id" class="form-control" id="product_id">
										<span class="product_id_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="billing_model_id">Billing Model ID</label>
										<select class="form-control" name="billing_model_id" id="billing_model_id">
											<option value="2">Goodeess Membership</option>
											<!--						  <option value="2">One Time Purchase</option>-->
										</select>
										<span class="billing_model_id_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="shipping_id">Shipping ID</label>
										<select class="form-control" name="shipping_id" id="shipping_id">
											<option value="3">Free Product</option>
											<option value="2">Free Shipping</option>
										</select>
										<span class="shipping_id_id_error all_error text-danger"></span>
									</div>
									<!--				  <div class="form-group col-md-6">-->
									<!--					  <label for="offer_title">Billing Model ID</label>-->
									<!--					  <input type="text" name="billing_model_id" class="form-control" id="billing_model_id">-->
									<!--					  <span class="billing_model_id_error all_error text-danger"></span>-->
									<!--				  </div>-->
									<!--				  <div class="form-group col-md-6">-->
									<!--					  <label for="shipping_id">Shipping ID</label>-->
									<!--					  <input type="text" name="shipping_id" class="form-control" id="shipping_id">-->
									<!--					  <span class="shipping_id_id_error all_error text-danger"></span>-->
									<!--				  </div>-->
									<div class="form-group col-md-6">
										<label for="offer_title">Product Title</label>
										<input type="text" name="offer_title" class="form-control" id="offer_title">
										<span class="offer_title_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="product_eng_title">English Product Title</label>
										<input type="text" name="product_eng_title" class="form-control" id="product_eng_title">
										<span class="product_eng_title_error all_error text-danger"></span>
									</div>
									<!--                <div class="form-group col-md-6">-->
									<!--                  <label for="country">Country Code</label>-->
									<!--                  <input type="text" name="country" class="form-control" id="country">-->
									<!--                  <span class="country_error all_error text-danger"></span>-->
									<!--                </div>-->
									<div class="form-group col-md-6">
										<label for="country">Country Code</label>
										<select class="form-control" name="country" id="country">
											<option value="">Select Country Code</option>
											<?php foreach ($countryList as $key => $value) { ?>
												<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
											<?php } ?>
										</select>
										<span class="country_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6 hide">
										<label for="price_type">Price Type</label>
										<select class="form-control" name="price_type" id="price_type">
											<option value="1">recurring</option>
											<option value="2">one time</option>
										</select>
										<span class="price_type_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="offer_price">Regular Price</label>
										<input type="text" name="offer_price" class="form-control" id="offer_price">
										<span class="offer_price_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="discount_offer_price">Discount Price</label>
										<input type="text" name="discount_offer_price" class="form-control" id="discount_offer_price">
										<span class="discount_offer_price_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="offer_currency">Product Currency (Optional)</label>
										<input type="text" name="offer_currency" class="form-control" id="offer_currency">
										<span class="offer_currency_error all_error text-danger"></span>
									</div>
									<!--				  <div class="form-group col-md-6">-->
									<!--					  <label for="offer_terms">Offer Terms</label>-->
									<!--					  <textarea name="offer_terms" class="form-control" id="offer_terms" cols="30" rows="3"></textarea>-->
									<!--					  <span class="offer_terms_error all_error text-danger"></span>-->
									<!--				  </div>-->
									<div class="form-group col-md-6 hide">
										<label for="offer_settings">Product Settings</label>
										<textarea name="offer_settings" class="form-control" id="offer_settings" cols="30" rows="5"></textarea>
										<span class="offer_settings_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="offer_features">Product Features</label>
										<textarea id="offer_features2" name="offer_features" class="form-control"  cols="30" rows="15" ></textarea>
										<span class="offer_features_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6">
										<label for="summernote2">Product Description</label>
										<textarea id="summernote2" name="offer_description" class="form-control"  cols="30" rows="15" ></textarea>
										<span class="offer_description_error all_error text-danger"></span>
									</div>
									
									<div class="form-group col-md-6">
										<label for="exampleInputFile">Image</label>
										<div class="input-group">
											<div class="custom-file">
												<input accept="image/*" type="file" name="offer_image[]" class="custom-file-input" id="exampleInputFile" multiple>
												<label class="custom-file-label" for="exampleInputFile">Choose file</label>
											</div>
										</div>
										<span class="offer_image_error all_error text-danger"></span>
									</div>
									<div class="form-group col-md-6 hide">
										<label for="offer_settings">Featured Product</label>
										<input type="checkbox" name="featured_offer" id="featured_offer" class="form-control form-control2"  >
									</div>
									<div class="form-group col-md-12" id="image_collection">

									</div>
								</div>
								<!-- /.card-body -->

								<div class="card-footer">
									<button type="submit" class="btn btn-primary">update</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div><!--modal-dialog -->
</div>

<!-- Edit Campaigns Model End-->

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js" defer></script>
<script>
	$(function () {
		// Summernote
		$('#summernote').summernote();
		$('#summernote2').summernote();
		$('#offer_features').summernote();
		$('#offer_features2').summernote();
	})
</script>
<script>
	$(document).ready(function() {
		$('body').on('click','.delete_img',function(e){
			e.preventDefault();
			const hideRow = $(this).parent();
			const img_id = $(this).data('img');
			$.ajax({
				url: base_url + 'admin/Offers/deleteImage?id='+img_id,
				method: 'POST',
				dataType: 'JSON',
				contentType: false,
				processData: false,
				success: function (data) {
					hideRow.fadeOut("slow", function() {
						// Animation complete.
					});
				}
			});
		});
		var offertables = $('#datatable').DataTable({
			// Processing indicator
			"processing": true,
			// DataTables server-side processing mode
			"serverSide": true,
			// Initial no order.
			"order": [],
			// Load data from an Ajax source
			"ajax": {
				"url": "<?php echo base_url('admin/offers/offers_list'); ?>",
				"type": "POST",
				"data": function(d) {
					return $.extend({}, d, {
						"compaign_filter": $('#compaign_filter').val()
					});
				}
			},
			//Set column definition initialisation properties
			"columnDefs": [{
				"targets": [0],
				"orderable": false
			}],
			"columns": [
				{ "data": "offer_title" },
				{ "data": "product_eng_title" },
				{ "data": "offer_price" },
				{ "data": "discount_offer_price" },
				{ "data": "offer_url" },
				{ "data": "free_offer_url" },
				{ "data": "offer_country" },
				{"data": "duplicate" },
				{ "data": "edit" },
				{ "data": "delete" }
			]
		});
		$(document).on('submit', '#add_offerForm', function (e) {
			e.preventDefault();
			$('.all_error').empty();
			var formObj = $(this);
			$.ajax({
				url: base_url + 'admin/Offers/addoffers',
				data: new FormData(this),
				enctype: 'multipart/form-data',
				method: 'POST',
				dataType: 'JSON',
				contentType: false,
				processData: false,
				success: function (data) {
					if (data.response == true) {
						window.location.reload();
					} else {
						if(data.slug_error){
							$('.offer_title_error').html(data.slug_error);
						}
						if(data.errors){
							addCmpErr(data.errors);
						}
					}
				}
			});
		});

		function addCmpErr(errors = '') {
			$.each(errors, function (key, value) {
				$('#add_offerForm ' + '.' + key + '_error').html('*'+value);
			});
			return false;
		}

		$('body').on('submit','.deleteProduct',function(e){
			e.preventDefault();
			const hideRow = $(e.target).parent().parent();
			swal({
				title: "Are you sure?",
				text: "Once deleted, you will not be able to recover this!",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								url: base_url + 'admin/Offers/deleteoffer',
								data: new FormData(this),
								method: 'POST',
								dataType: 'JSON',
								contentType: false,
								processData: false,
								success: function (data) {
									hideRow.fadeOut("slow", function() {
									});
									swal("Poof! Your has been deleted!", {
										icon: "success",
									});
								}
							});
						} else {
							swal("Your Product is safe!");
						}
					});
		});


		$('body').on('click','.editBtn', function(e){
			var id = $(e.target).attr('data-id');

			$.ajax({
				url: base_url + 'admin/Offers/getedit' + `?id=${id}`,
				method: 'POST',
				dataType: 'JSON',
				contentType: false,
				processData: false,
				success: function (data) {
					if(data.response){
						getDisplay(data.data);
						displayImages(data.images);
					}
				}
			});

		});
		function displayImages(images){
			$("#image_collection").html("");
			$.each(images, function (key, value) {
				$("#image_collection").append('<div> <i data-img="'+value.id+'" class="fa fa-trash delete_img"></i><img width="200" height="200" src="./assets/uploads/'+value.offer_image+'"></div>');
			});
		}

		function getDisplay(formdata = '') {
			if(formdata != ''){
				// if(formdata[0]['offer_image']) {
				// 	$("#edit_offer_image").attr('src', "./assets/uploads/"+formdata[0]['offer_image']);
				// }
				$.each(formdata[0], function (key, value) {
					if(key !== 'featured_offer') {
						$('#edit-offers '+`[name=${key}]`).val(value);
					}

					if(key === 'offer_description') {
						$('#summernote2').summernote('code', value);
					}
					if(key === 'offer_features') {
						$('#offer_features2').summernote('code', value);
					}
					if(key === 'featured_offer' && value === "1") {
						$("#featured_offer").attr("checked", true);
					}
				});
			}
			return false;
		}

		//duplicateform start
		
$(document).on('click', '.duplicate_btn', function (e) {
	var id = $(this).attr("data-id");
	swal({
		title: "Are you sure?",
		text: "You want to Duplicate product?",
		icon: "info",
		buttons:["NO","Yes"],
		})
		.then((value) => {
		if (value) {
			$.ajax({
				url: base_url + "admin/offers/duplicate_offers",
				type: "POST",
				dataType: 'json',
				data:{id:id},
				success: function (data) {
					swal("Poof! The product has been duplicated!", {
			icon: "success",
    });
	$("#datatable").DataTable().ajax.reload();

        }
    });
  } 
});
});
	//duplicate form end

		$(document).on('submit', '#edit_offerForm', function (e) {
			e.preventDefault();
			$('.all_error').empty();
			var formObj = $(this);
			$.ajax({
				url: base_url + 'admin/Offers/editoffer',
				data: new FormData(this),
				method: 'POST',
				dataType: 'JSON',
				contentType: false,
				processData: false,
				success: function (data) {
					if (data.response == true) {
						window.location.reload();
					}else{
						if(data.slug_error){
							$('.offer_title_error').html(data.slug_error);
						}
						if(data.errors){
							EditCmpErr(data.errors);
						}

					}
				}
			});
		});


		function EditCmpErr(errors = '') {
			$.each(errors, function (key, value) {
				$('#edit-offers '+'.' + key + '_error').html(value);
			});
			return false;
		}
		$("#compaign_filter").on("change", function (){
			offertables.draw();
		});

	});
</script>

