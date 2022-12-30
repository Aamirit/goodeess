<div class="content-wrapper" style="min-height: 1345.6px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>General Form</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">General Form</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6 m-auto">
            <!-- general form elements -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Credentials</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="settings">
                  <input type="hidden" name="setting_id" value="<?php echo $data[0]['setting_id']; ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label for="limelight_url">Limelight Url</label>
                    <input type="text" value="<?php echo $data[0]['limelight_url']; ?>" class="form-control" id="limelight_url" name="limelight_url" placeholder="Limelight Url">
                  </div>
                    <div class="limelight_url_error all_error text-danger"></div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">Update Url</button>
                    <button type="button" class="btn btn-primary btn-block validate">Validate Credentials</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <script>
      $(document).ready(function() { 
        $(document).on('submit', '#settings', function (e) {
            e.preventDefault();
            $('.all_error').empty();
            var formObj = $(this);
            $.ajax({
            url: base_url + 'admin/Form/post_setting',
            data: new FormData(this),
            method: 'POST',
            dataType: 'JSON',
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.response == true) {
                    swal({
                        title: "Good job!",
                        text: "You Updated the Url",
                        icon: "success",
                    });
                } else {
                    errors(data.errors);
                }
            }
            });
        });

        function errors(errors = '') {
            $.each(errors, function (key, value) {
                $('.' + key + '_error').html(value);
            });
            return false;
        }

        $('.validate').on('click',() => {
           var urlVal = $('#limelight_url').val();

           var settings = {
                "url": `${urlVal}/api/v1/validate_credentials`,
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json",
                    "Authorization": "Basic YXBpdGVzdDpQUU1IdUZaQ3F4R1FnMg=="
                },
            };

            $.ajax(settings)
            .done(() => 
                    swal({
                        title: "Good job!",
                        text: "This Url is Authenticated",
                        icon: "success",
                    })
                )
            .fail(() => swal({
                        title: "OOPS!",
                        text: "This Url is not Valid",
                        icon: "error",
                    })
                )

        });

        

      });
  </script>