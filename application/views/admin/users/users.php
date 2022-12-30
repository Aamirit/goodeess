<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
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
            <h1>Users</h1>
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
              <div class="row mr-3 mt-3" style="justify-content: right;">
                <div class="col-2 text-right">
                <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#add-user">
                  Add User
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
                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                    Name
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                   Email
                    </th>
<!--                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">-->
<!--                    VAT-->
<!--                    </th>-->
<!--                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">-->
<!--                    Campaign Settings-->
<!--                    </th>-->
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                      Edit
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                      Delete
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php foreach($users as $user){
                  	if($user['email'] !== 'goodiesadmin@gmail.com'){
                  	?>
                  <tr class="odd">
                    <td class="dtr-control sorting_1" tabindex="0"><?php echo $user['name'];?></td>
                    <td><?php echo $user['email'];?></td>
<!--                    <td>--><?php //echo $campaign['vat'];?><!--</td>-->
<!--                    <td>--><?php //echo $campaign['campaign_settings'];?><!--</td>-->
                    <td>
                      <button type="button" class="btn btn-primary btn-flat editBtn"  data-toggle="modal" data-target="#edit-user" data-id="<?php echo $user['id'];?>">
                        Edit
                      </button>
                    </td>
                    <td>
                      <form class="deleteProduct">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <button type="submit" class="btn btn-block btn-danger btn-flat">Delete</button>
                      </form>
                    </td>
                  </tr>
                  <?php } } ?>
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



<!-- Add Campaigns Start -->
  <div class="modal fade show" id="add-user" style="padding-right: 17px; display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add New User</h4>
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
                  <form id="add_userForm">
                    <div class="card-body row">
                      <div class="form-group col-md-6">
                        <label for="name">User Name</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                        <span class="name_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="text" name="email" class="form-control" id="email" required>
                        <span class="email_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="text" name="password" class="form-control" id="password" required>
                        <span class="password_error all_error text-danger"></span>
                      </div>
						<div class="form-group col-md-6"></div>
						<div class="form-group col-md-12">
							<h3><u>User Permissions</u></h3>
						</div>
						<div class="form-group col-md-12">
							<div class="form-check">
								<label class="form-check-label">
									<input  type="checkbox" class="form-check-input all_permission">Allow All Permissions
								</label>
							</div>
						</div>
						<div class="form-group col-md-3">
							<div class="form-check">
								<label class="form-check-label">
									<input name="permissions[]" value="manage_users"  type="checkbox" class="form-check-input permissions users_permissions">Manage Users
								</label>
							</div>
						</div>
						<div class="form-group col-md-3">
							<div class="form-check">
								<label class="form-check-label">
									<input name="permissions[]" value="manage_campaigns" type="checkbox" class="form-check-input permissions campaigns_permissions">Manage Campaigns
								</label>
							</div>
						</div>
						<div class="form-group col-md-3">
							<div class="form-check">
								<label class="form-check-label">
									<input name="permissions[]" value="manage_products"  type="checkbox" class="form-check-input permissions products_permissions">Manage Products
								</label>
							</div>
						</div>
						<div class="form-group col-md-3">
							<div class="form-check">
								<label class="form-check-label">
									<input name="permissions[]" value="manage_orders"  type="checkbox" class="form-check-input permissions orders_permissions" >Manage Orders
								</label>
							</div>
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
            <div class="modal-footer">
              <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div><!--modal-dialog -->
      </div>

<!-- Add Campaigns End -->

<!-- Edit Campaigns Model Start-->

<div class="modal fade show" id="edit-user" style="padding-right: 17px; display: none;" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update User</h4>
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
            <form id="edit_userForm">
              <div class="card-body row">
                <input type="hidden" name="id">
				  <div class="form-group col-md-6">
					  <label for="name">User Name</label>
					  <input type="text" name="name" class="form-control" id="name" required>
					  <span class="name_error all_error text-danger"></span>
				  </div>
				  <div class="form-group col-md-6">
					  <label for="email">Email</label>
					  <input type="text" name="email" class="form-control" id="email" required>
					  <span class="email_error all_error text-danger"></span>
				  </div>
				  <div class="form-group col-md-6">
					  <label for="password">Password</label>
					  <input type="text" name="password" class="form-control" id="password">
					  <span class="password_error all_error text-danger"></span>
				  </div>
				  <div class="form-group col-md-6"></div>
				  <div class="form-group col-md-12">
					  <h3><u>User Permissions</u></h3>
				  </div>
				  <div class="form-group col-md-12">
					  <div class="form-check">
						  <label class="form-check-label">
							  <input  type="checkbox" class="form-check-input all_permission">Allow All Permissions
						  </label>
					  </div>
				  </div>
				  <div class="form-group col-md-3">
					  <div class="form-check">
						  <label class="form-check-label">
							  <input name="permissions[]" value="manage_users"  type="checkbox" class="manage_users form-check-input permissions users_permissions">Manage Users
						  </label>
					  </div>
				  </div>
				  <div class="form-group col-md-3">
					  <div class="form-check">
						  <label class="form-check-label">
							  <input name="permissions[]" value="manage_campaigns" type="checkbox" class="manage_campaigns form-check-input permissions campaigns_permissions">Manage Campaigns
						  </label>
					  </div>
				  </div>
				  <div class="form-group col-md-3">
					  <div class="form-check">
						  <label class="form-check-label">
							  <input name="permissions[]" value="manage_products"  type="checkbox" class="manage_products form-check-input permissions products_permissions">Manage Products
						  </label>
					  </div>
				  </div>
				  <div class="form-group col-md-3">
					  <div class="form-check">
						  <label class="form-check-label">
							  <input name="permissions[]" value="manage_orders"  type="checkbox" class="manage_orders form-check-input permissions orders_permissions" >Manage Orders
						  </label>
					  </div>
				  </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Edit</button>
              </div>
            </form>
          </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
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
  $(document).ready(function() {
		  $('.all_permission').click(function() {
			  if($(this).prop("checked") === true) {
				  $('.permissions').prop('checked', true);
			  }
			  else if($(this).prop("checked") === false) {
				  $('.permissions').prop('checked', false);
			  }
		  });
    $('#datatable').DataTable({});

      $(document).on('submit', '#add_userForm', function (e) {
        e.preventDefault();
        $('.all_error').empty();
        var formObj = $(this);
        $.ajax({
          url: base_url + 'admin/add/user',
          data: new FormData(this),
          method: 'POST',
          dataType: 'JSON',
          contentType: false,
          processData: false,
          success: function (data) {
            if (data.response == true) {
              // alert('Successfully Done!');
              // formObj.trigger('reset');
              window.location.reload();
            } else {
              addCmpErr(data.errors);
            }
          }
        });
      });

      function addCmpErr(errors = '') {
        $.each(errors, function (key, value) {
          $('#add_userForm ' + '.' + key + '_error').html('*'+value);
        });
        return false;
      }

    $('.deleteProduct').on('submit',function(e){
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
							url: base_url + 'admin/user/delete',
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
      

    $('.editBtn').on('click',function(e){
      var id = $(e.target).attr('data-id');
      $.ajax({
        url: base_url + 'admin/user/getedit' + `?id=${id}`,
        method: 'POST',
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (data) {
          if(data.response){
            getDisplay(data.data);
            if(data.permissions.length > 0) {
				getDisplayPermission(data.permissions)
			}
          }
        }
      });

    });

    
    function getDisplay(formdata = '') {
      if(formdata != ''){
        $.each(formdata[0], function (key, value) {
          if(key !== 'password'){
			  $('#edit-user '+`[name=${key}]`).val(value);
		  }
        });
      }
      return false;
    }
	  function getDisplayPermission(formdata = '') {
    	var permissions = JSON.parse(formdata[0].permissions)
    	if(permissions.length > 0) {
			permissions.map((per) => {
				 $('.'+per).prop('checked', true);
			});
			if(permissions.length === 4){
				$('.all_permission').prop('checked', true);
			}
		}
	  }

    
    $(document).on('submit', '#edit_userForm', function (e) {
        e.preventDefault();
        $('.all_error').empty();
        var formObj = $(this);
        $.ajax({
          url: base_url + 'admin/users/edituser',
          data: new FormData(this),
          method: 'POST',
          dataType: 'JSON',
          contentType: false,
          processData: false,
          success: function (data) {
            console.log(data);
            if (data.response == false) {
              EditCmpErr(data.errors);
            }else{
              window.location.reload();
            }
          }
        });
      });

      
      function EditCmpErr(errors = '') {
        $.each(errors, function (key, value) {
          $('#edit-user '+'.' + key + '_error').html(value);
        });
        return false;
      }

  });
</script>
