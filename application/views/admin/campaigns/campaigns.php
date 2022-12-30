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
            <h1>Campaigns</h1>
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
                <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#add-product">
                  Add Campaign
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
                    Campaign Title
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                    Country Code
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
<!--                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">-->
<!--                      Delete-->
<!--                    </th>-->
                  </tr>
                  </thead>
                  <tbody>
                  <?php foreach($campaigns as $campaign){ ?>
                  <tr class="odd">
                    <td class="dtr-control sorting_1" tabindex="0"><?php echo $campaign['campaign_title'];?></td>
                    <td><?php echo $campaign['country_code'];?></td>
<!--                    <td>--><?php //echo $campaign['vat'];?><!--</td>-->
<!--                    <td>--><?php //echo $campaign['campaign_settings'];?><!--</td>-->
                    <td>
                      <button type="button" class="btn btn-primary btn-flat editBtn"  data-toggle="modal" data-target="#edit-campaign" data-id="<?php echo $campaign['campaign_id'];?>">
                        Edit
                      </button>
                    </td>
<!--                    <td>-->
<!--                      <form class="deleteProduct">-->
<!--                        <input type="hidden" name="campaign_id" value="--><?php //echo $campaign['campaign_id']; ?><!--">-->
<!--                        <button type="submit" class="btn btn-block btn-danger btn-flat">Delete</button>-->
<!--                      </form>-->
<!--                    </td>-->
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



<!-- Add Campaigns Start -->
  <div class="modal fade show" id="add-product" style="padding-right: 17px; display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add New Campaign</h4>
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
                  <form id="add_campaignForm">
                    <div class="card-body row">
                      <div class="form-group col-md-6">
                        <label for="price">Campaign Title</label>
                        <input type="text" name="campaign_title" class="form-control" id="campaign_title">
                        <span class="campaign_title_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="campaign_crm_id">Campaign CRM ID</label>
                        <input type="text" name="campaign_crm_id" class="form-control" id="campaign_crm_id">
                        <span class="campaign_crm_id_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="offerId">Country Code</label>
                        <select class="form-control" name="country_code" id="country_code">
                          <option value="">Select Country Code</option>
							<?php foreach ($countryList as $key => $value) { ?>
								<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php } ?>
                        </select>
                        <span class="country_code_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="vat">VAT</label>
                        <input type="text" name="vat" class="form-control" id="vat">
                        <span class="vat_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="campaignId">Campaign Settings</label>
                        <textarea name="campaign_settings" class="form-control" id="campaign_settings" cols="30" rows="5"></textarea>
                        <span class="campaign_settings_error all_error text-danger"></span>
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

<div class="modal fade show" id="edit-campaign" style="padding-right: 17px; display: none;" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Campaign</h4>
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
            <form id="edit_campaignForm">
              <div class="card-body row">
                <input type="hidden" name="campaign_id">
                <div class="form-group col-md-6">
                  <label for="name">Campaign Title</label>
                  <input type="text" name="campaign_title" class="form-control" id="campaign_title" >
                  <span class="campaign_title_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="campaign_crm_id">Campaign CRM ID</label>
                  <input type="text" name="campaign_crm_id" class="form-control" id="campaign_crm_id">
                  <span class="campaign_crm_id_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="offerId">Country Code</label>
                  <select class="form-control" name="country_code" id="country_code">
                    <option value="">Select Country Code</option>
					  <?php foreach ($countryList as $key => $value) { ?>
						  <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
					  <?php } ?>
                  </select>
                  <span class="country_code_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="vat">VAT</label>
                  <input type="text" name="vat" class="form-control" id="vat">
                  <span class="vat_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-6">
                    <label for="offerId">Campaign Settings</label>
                    <textarea name="campaign_settings" class="form-control" id="campaign_settings" cols="30" rows="5"></textarea>
                    <span class="campaign_settings_error all_error text-danger"></span>
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
  $(document).ready(function() {

    $('#datatable').DataTable({});



      $(document).on('submit', '#add_campaignForm', function (e) {
        e.preventDefault();
        $('.all_error').empty();
        var formObj = $(this);
        $.ajax({
          url: base_url + 'admin/Campaigns/addcampaigns',
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
          $('#add_campaignForm ' + '.' + key + '_error').html('*'+value);
        });
        return false;
      }

    $('.deleteProduct').on('submit',function(e){
      e.preventDefault();
      const hideRow = $(e.target).parent().parent();
      
      $.ajax({
        url: base_url + 'admin/Campaigns/deletecampaign',
        data: new FormData(this),
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
      

    $('.editBtn').on('click',function(e){
      var id = $(e.target).attr('data-id');

      $.ajax({
        url: base_url + 'admin/Campaigns/getedit' + `?id=${id}`,
        method: 'POST',
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (data) {
          if(data.response){
            getDisplay(data.data);
          }
        }
      });

    });

    
    function getDisplay(formdata = '') {
      if(formdata != ''){
        $.each(formdata[0], function (key, value) {
          $('#edit-campaign '+`[name=${key}]`).val(value);
        });
      }
      return false;
    }


    
    $(document).on('submit', '#edit_campaignForm', function (e) {
        e.preventDefault();
        $('.all_error').empty();
        var formObj = $(this);
        $.ajax({
          url: base_url + 'admin/Campaigns/editcampaigns',
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
          $('#edit-campaign '+'.' + key + '_error').html(value);
        });
        return false;
      }

  });
</script>
