function getUrlVars(){
  var vars = [], hash;
  var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
  for(var i = 0; i < hashes.length; i++)
  {
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }

  return vars;
}

var url = new URL(window.location.href);
var geo = url.searchParams.get("geo");
var versioningfix = Date.now();

$(document).on("ready",function(){

  $.get(geo+"/checkouturl.txt?version="+versioningfix, function(html_string)
   {
    var urldata = getUrlVars();
    var urltarget = html_string;

      if(urldata != ''){
        if(urldata[0] != window.location.href){
          for(x=0;x<urldata.length;x++){

            var urlparam = urldata[x];
            var urlvalue = getUrlVars()[urlparam];

            if(urlvalue == undefined){
              var urlconcat = urlparam;
            }else{
              var urlconcat = urlparam+"="+urlvalue;
            }

            if(urlparam == 'geo' || urlparam == 'layout'){

            }else{
              urltarget = urltarget+urlconcat;
            }

            if(urlparam == 'geo' || urlparam == 'layout'){

            }else{
              if((x+1)==(urldata.length)){
                urltarget = urltarget;
              }else{
                urltarget = urltarget+"&";
              }
            }
          }
        }
      }
    $(".javacta").attr("href",urltarget);
   });

  new PerfectScrollbar('.kf-gallery-selection');

  var commentscheck = $(".sc-comment").length;

  if(commentscheck > 0){

    var pathname = window.location.pathname;

    $.ajax({
        url: 'https://randomuser.me/api/?results=10&nat=fr&seed='+pathname+'-comments&inc=name,picture',
        dataType: 'json',
        success: function(data) {


            var i = $(".sc-comment").length;
            for (x = 0; x < i; x++) {
                var fname = data.results[x].name.first;
                var lname = data.results[x].name.last;
                var tmbnl = data.results[x].picture.medium;
                var datedynamic = Date.today().add(-((x*(x+1)))-1).day().toString("MMM d, yyyy");

                var target = $('.sc-info-tab-panel .js-comments-target > div:eq('+x+')');
                target.find(".comment-profile-name").text(fname+" "+lname);
                target.find(".sc-comment-dp").css("background-image", 'url('+tmbnl+')');
                target.find(".comment-profile-date").text(datedynamic);

            }

        }

    });

  }

  setInterval(function(){
    $(".periodically-increment-js").each(function(){
      var dd = $(this).text();
      dd++;
      $(this).text(dd);
    });
  },5000)

});


$(document).on("click",".sc-info-tabs a",function(){
  $(this).closest(".sc-info-tabs").find("a").removeClass("active");
  $(this).addClass("active");
  var x = $(this).index();
  $(this).closest(".sc-tab-parent").find(".sc-info-tab-panel > div").hide();
  $(this).closest(".sc-tab-parent").find('.sc-info-tab-panel > div:eq('+x+')').fadeIn();
});

$(document).on("click",".kf-gallery-selection a",function(){
  var selectimg = $(this).find("div").css("background-image");
  $(".kf-gallery-scene").css("background-image",selectimg);
});


$(document).on("click",".footer-links a",function(){
  var target_file = $(this).attr("class");
  $.get("../../modals/"+geo+"/"+target_file+".html?version="+versioningfix, function(html_string)
   {
      $("body").addClass("body_disabled").append(html_string);
      $(".modal").fadeIn(500);
   },'html');    // this is the change now its working
});



$(document).on("click",function(){
  if($(".modal").length == 1){
    if(!$(event.target).is(".modal *")){
      $(".modal").fadeOut(500);
      $("body").removeClass("body_disabled");
      setTimeout(function(){
        $(".modal").remove();
      },500);
    }
  }
});

$(document).on("click",".modal_upper a",function(){
  $(".modal").fadeOut(500);
  $("body").removeClass("body_disabled");
  setTimeout(function(){
    $(".modal").remove();
  },500);
});

var coupon_applied = false;
$("#coupon_success").hide();

$(document).on("click","#discount_code_btn",function(e){
  var discount_code = $("#discount_code").val();
  if(discount_code!=''){
    if(coupon_applied==false){
      coupon_detail(discount_code);
    }
  }
});

function coupon_detail(code)
{
  //var original_price = $(".new_price").html();
  // var original_price = '<?php echo($newprice); ?>';

  var original_price = $(".pricing-new > span").text();
  console.log(original_price);
  var http = new XMLHttpRequest();
  var url = 'https://mycasezs.com/goodeess/coupon_detail.php';
  var params = new Object();
  params.couponCode = code;

  switch(geo){
    case "fr":
    params.campaignId = '17';
    break;
    case "de":
    params.campaignId = '24';
    break;
    case "es":
    params.campaignId = '31';
    break;
  }
  
  var post_data = new URLSearchParams(params).toString();
  console.log(post_data);
  http.open('POST', url, true);
  //Send the proper header information along with the request
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  http.onreadystatechange = function() {
    var results = JSON.parse(http.responseText);
    //console.log(response);
    if(results.response){
      var coupon_detail = results.result;
      console.log(coupon_detail);
      if(coupon_detail.discountType=="PERCENT"){
        var percentage = coupon_detail.couponDiscountPerc;
        //alert(percentage);
        var discount = Number(percentage/100)*Number(original_price);
        var new_price = Number(original_price)-Number(discount)
;
        new_price = new_price.toFixed(2);
        $(".pricing-new > span").html(new_price);
        var checkout_url = $(".javacta").attr("href");
        var url = new URL(checkout_url);
        url.searchParams.set('couponCode', code);
        $(".javacta").attr("href",url);
        $("#coupon_percentage").html(percentage);
        $("#coupon_success").fadeIn();
        coupon_applied = true;
      }
    }
    else{
      return false;
    }
  }
  http.send(post_data);
}

$(document).on('change', '#variants', function () {
    var variant = $("#variants option:selected").val();
    var checkout_url = $(".javacta").attr("href");
    var url = new URL(checkout_url);
    url.searchParams.set('variant1', variant);
    $(".javacta").attr("href", url);

});

$(document).on("change",".cdropdown > select",function(){
  var ddlabel = $(this).find("option:selected").text();
  $(".cdd-disp").text("Selected: "+ddlabel);
});