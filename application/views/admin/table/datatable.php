<style>
  #example1_filter {
    display: flex;
    justify-content: end;
}
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<?php// echo '<pre>';print_r($products); ?>
<div class="content-wrapper" style="min-height: 1302.4px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>DataTables</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">DataTables</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- <div class="card">
              <div class="card-header">
                <h3 class="card-title">DataTable with minimal features &amp; hover style</h3>
              </div> -->
              <!-- /.card-header -->
              <!-- <div class="card-body">
                <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4"><div class="row"><div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                  <thead>
                  <tr><th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Rendering engine</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Browser</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Platform(s)</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">Engine version</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">CSS grade</th></tr>
                  </thead>
                  <tbody>
                  <tr class="odd">
                    <td class="dtr-control sorting_1" tabindex="0">Gecko</td>
                    <td>Firefox 1.0</td>
                    <td>Win 98+ / OSX.2+</td>
                    <td>1.7</td>
                    <td>A</td>
                  </tr><tr class="even">
                    <td class="dtr-control sorting_1" tabindex="0">Gecko</td>
                    <td>Firefox 1.5</td>
                    <td>Win 98+ / OSX.2+</td>
                    <td>1.8</td>
                    <td>A</td>
                  </tr><tr class="odd">
                    <td class="dtr-control sorting_1" tabindex="0">Gecko</td>
                    <td>Firefox 2.0</td>
                    <td>Win 98+ / OSX.2+</td>
                    <td>1.8</td>
                    <td>A</td>
                  </tr><tr class="even">
                    <td class="dtr-control sorting_1" tabindex="0">Gecko</td>
                    <td>Firefox 3.0</td>
                    <td>Win 2k+ / OSX.3+</td>
                    <td>1.9</td>
                    <td>A</td>
                  </tr><tr class="odd">
                    <td class="sorting_1 dtr-control">Gecko</td>
                    <td>Camino 1.0</td>
                    <td>OSX.2+</td>
                    <td>1.8</td>
                    <td>A</td>
                  </tr><tr class="even">
                    <td class="sorting_1 dtr-control">Gecko</td>
                    <td>Camino 1.5</td>
                    <td>OSX.3+</td>
                    <td>1.8</td>
                    <td>A</td>
                  </tr><tr class="odd">
                    <td class="sorting_1 dtr-control">Gecko</td>
                    <td>Netscape 7.2</td>
                    <td>Win 95+ / Mac OS 8.6-9.2</td>
                    <td>1.7</td>
                    <td>A</td>
                  </tr><tr class="even">
                    <td class="sorting_1 dtr-control">Gecko</td>
                    <td>Netscape Browser 8</td>
                    <td>Win 98SE+</td>
                    <td>1.7</td>
                    <td>A</td>
                  </tr><tr class="odd">
                    <td class="sorting_1 dtr-control">Gecko</td>
                    <td>Netscape Navigator 9</td>
                    <td>Win 98+ / OSX.2+</td>
                    <td>1.8</td>
                    <td>A</td>
                  </tr><tr class="even">
                    <td class="sorting_1 dtr-control">Gecko</td>
                    <td>Mozilla 1.0</td>
                    <td>Win 95+ / OSX.1+</td>
                    <td>1</td>
                    <td>A</td>
                  </tr></tbody>
                  <tfoot>
                  <tr><th rowspan="1" colspan="1">Rendering engine</th><th rowspan="1" colspan="1">Browser</th><th rowspan="1" colspan="1">Platform(s)</th><th rowspan="1" colspan="1">Engine version</th><th rowspan="1" colspan="1">CSS grade</th></tr>
                  </tfoot>
                </table></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="example2_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="example2_previous"><a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example2" data-dt-idx="2" tabindex="0" class="page-link">2</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example2" data-dt-idx="3" tabindex="0" class="page-link">3</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example2" data-dt-idx="4" tabindex="0" class="page-link">4</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0" class="page-link">5</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0" class="page-link">6</a></li><li class="paginate_button page-item next" id="example2_next"><a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li></ul></div></div></div></div>
              </div> -->
              <!-- /.card-body -->
            <!-- </div> -->
            <!-- /.card -->

            <div class="card">
              <div class="row mr-3 mt-3" style="justify-content: right;">
                <div class="col-2">
                <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#add-product">
                  Add Products
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
                      Product Name
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                      Product Price
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                      Offer Id
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                      Capmaign Id
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                      Shipping Id
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" >
                      Billing Id
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                      CRM Product Id
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                      Is Upsell
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
                  <?php foreach($products as $product){ ?>
                  <tr class="odd">
                    <td class="dtr-control sorting_1" tabindex="0"><?php echo $product['product_name'];?></td>
                    <td><?php echo $product['product_price'];?></td>
                    <td><?php echo $product['offer_id'];?></td>
                    <td><?php echo $product['campign_id'];?></td>
                    <td><?php echo $product['shipping_id'];?></td>
                    <td><?php echo $product['billing_id'];?></td>
                    <td><?php echo $product['crm_product_id'];?></td>
                    <td>
                          <?php if($product['is_upsell'] == '0'){echo 'False';}else{echo 'True';} ?>
                    </td>
                    <td>
                      <button type="button" class="btn btn-primary btn-flat editBtn"  data-toggle="modal" data-target="#edit-product" data-id="<?php echo $product['product_id'];?>">
                        Edit
                      </button>
                    </td>
                    <td>
                      <form class="deleteProduct">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
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
                  <form id="add_productForm">
                    <div class="card-body row">
                      <div class="form-group col-md-4">
                        <label for="name">Product Name</label>
                        <input type="text" name="product_name" class="form-control" id="product_name" placeholder="Enter email">
                        <span class="product_name_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="price">Product Price</label>
                        <input type="text" name="product_price" class="form-control" id="p_price" placeholder="Product Price">
                        <span class="product_price_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="offerId">Offer Id</label>
                        <input type="number" name="offer_id" class="form-control" id="offer_id" placeholder="Offer Id">
                        <span class="offer_id_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="campaignId">Campaign Id</label>
                        <input type="number" name="campign_id" class="form-control" id="campaign_id" placeholder="Campaign Id">
                        <span class="campign_id_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="shippingId">Shipping Id</label>
                        <input type="number" name="shipping_id" class="form-control" id="shipping_id" placeholder="Shipping Id">
                        <span class="shipping_id_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="billingId">Billing Id</label>
                        <input type="number" name="billing_id" class="form-control" id="billing_id" placeholder="Billing Id">
                        <span class="billing_id_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="crmProductId">CRM Product Id</label>
                        <input type="number" name="crm_product_id" class="form-control" id="crm_id" placeholder="CRM Product Id">
                        <span class="crm_product_id_error all_error text-danger"></span>
                      </div>
                      <div class="form-group col-md-4">
                        <label>Is Upsell</label>
                        <select name="is_upsell" class="custom-select is_upsell">
                          <option value="1">True</option>
                          <option value="0">False</option>
                        </select>
                        <span class="is_upsell_error all_error text-danger"></span>
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


<!-- Edit Product Model -->

<div class="modal fade show" id="edit-product" style="padding-right: 17px; display: none;" aria-modal="true" role="dialog">
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
            <form id="edit_productForm">
              <div class="card-body row">
                <input type="hidden" name="product_id">
                <div class="form-group col-md-4">
                  <label for="name">Product Name</label>
                  <input type="text" name="product_name" class="form-control" id="product_name" placeholder="Enter email">
                  <span class="product_name_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-4">
                  <label for="price">Product Price</label>
                  <input type="text" name="product_price" class="form-control" id="p_price" placeholder="Product Price">
                  <span class="product_price_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-4">
                  <label for="offerId">Offer Id</label>
                  <input type="number" name="offer_id" class="form-control" id="offer_id" placeholder="Offer Id">
                  <span class="offer_id_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-4">
                  <label for="campaignId">Campaign Id</label>
                  <input type="number" name="campign_id" class="form-control" id="campaign_id" placeholder="Campaign Id">
                  <span class="campign_id_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-4">
                  <label for="shippingId">Shipping Id</label>
                  <input type="number" name="shipping_id" class="form-control" id="shipping_id" placeholder="Shipping Id">
                  <span class="shipping_id_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-4">
                  <label for="billingId">Billing Id</label>
                  <input type="number" name="billing_id" class="form-control" id="billing_id" placeholder="Billing Id">
                  <span class="billing_id_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-4">
                  <label for="crmProductId">CRM Product Id</label>
                  <input type="number" name="crm_product_id" class="form-control" id="crm_id" placeholder="CRM Product Id">
                  <span class="crm_product_id_error all_error text-danger"></span>
                </div>
                <div class="form-group col-md-4">
                  <label>Is Upsell</label>
                  <select name="is_upsell" class="custom-select is_upsell">
                    <option value="1">True</option>
                    <option value="0">False</option>
                  </select>
                  <span class="is_upsell_error all_error text-danger"></span>
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


<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js" defer></script>

<script>
  $(document).ready(function() {

    $('#datatable').DataTable({});



      $(document).on('submit', '#add_productForm', function (e) {
        e.preventDefault();
        $('.all_error').empty();
        var formObj = $(this);
        $.ajax({
          url: base_url + 'admin/Table/addproducts',
          data: new FormData(this),
          method: 'POST',
          dataType: 'JSON',
          contentType: false,
          processData: false,
          success: function (data) {
            if (data.response == true) {
              // alert('Successfully Done!');
              // formObj.trigger('reset');
              setTimeout(() => {window.location.replace(base_url + 'admin/table');},500);
            } else {
              alert('Failed!');
              errors2(data.errors);
              $('.product_image_error').html(data.image_errors);
            }
          }
        });
      });

      function errors2(errors = '') {
        $.each(errors, function (key, value) {
          $('#add-product ' + '.' + key + '_error').html(value);
        });
        return false;
      }

    $('.deleteProduct').on('submit',function(e){
      e.preventDefault();
      const hideRow = $(e.target).parent().parent();
      hideRow.fadeOut("slow", function() {
        // Animation complete.
      });
      $.ajax({
        url: base_url + 'admin/Table/deleteproduct',
        data: new FormData(this),
        method: 'POST',
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (data) {

        }
      });
    });
      

    $('.editBtn').on('click',function(e){
      var id = $(e.target).attr('data-id');

      $.ajax({
        url: base_url + 'admin/Table/getedit' + `?id=${id}`,
        method: 'POST',
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (data) {
          if(data.response){
            errors1(data.data);
          }
        }
      });

    });

    
    function errors1(formdata = '') {
      if(formdata != ''){
        $.each(formdata[0], function (key, value) {
          $('#edit-product '+`[name=${key}]`).val(value);
        });
      }
      return false;
    }


    
    $(document).on('submit', '#edit_productForm', function (e) {
        e.preventDefault();
        $('.all_error').empty();
        var formObj = $(this);
        $.ajax({
          url: base_url + 'admin/Table/editproducts',
          data: new FormData(this),
          method: 'POST',
          dataType: 'JSON',
          contentType: false,
          processData: false,
          success: function (data) {
            if (data.response == false) {
              errors2(data.errors);
            }else{
              alert('Success!');
              window.location.reload();
            }
          }
        });
      });

      
      function errors2(errors = '') {
        $.each(errors, function (key, value) {
          $('#edit-product '+'.' + key + '_error').html(value);
        });
        return false;
      }

  });
</script>