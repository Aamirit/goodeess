$(document).ready(function(){

  $(document).on('submit', '#checkout-form', function(e){
    e.preventDefault();
    var formObj = $(this);
    $('.all_errors').empty();
    $.ajax({
      url: base_url + 'home/checkout_process',
      type: 'POST',
      data: new FormData(this),
      dataType: 'JSON',
      processData: false,
      contentType: false,
      success: function(data){
        console.log(data);
      }

    });
  });

});



$(document).on('submit', '#addForm', function (e) {
  e.preventDefault();
  $.LoadingOverlay("show");
  var obj = $(this);
  $('.all_errors').empty();
  var form_data = new FormData(this);
  $.ajax({
      url: base_url + "admin/products/process_add",
      type: "POST",
      dataType: 'json',
      data: new FormData(this),
      processData: false, 
      contentType: false,
      success: function (data) {
          if(data.response){
              obj.trigger("reset");
              swal("Data has been saved successfully.")
              .then((value) => {
                location.reload(); 
              });
          }
          else{
              if(data.image_error!=''){
                $('.image_error').html(data.image_error);
              }
              else{
                errors(data.errors);
              }
          }
      },
      complete: function(){
        $.LoadingOverlay("hide");
      }
  });
  return false;
});