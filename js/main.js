   // var cp_obj = CraftyPostcodeCreate();
   // cp_obj.set("access_token", "xxxxx-xxxxx-xxxxx-xxxxx"); // your token here
   // cp_obj.set("result_elem_id", "crafty_postcode_result_display");
   // cp_obj.set("form", "address");
   // cp_obj.set("elem_company"  , "companyname");
   // cp_obj.set("elem_street1"  , "address1");
   // cp_obj.set("elem_street2"  , "address2");
   // cp_obj.set("elem_street3"  , "address3");
   // cp_obj.set("elem_town"     , "town");
   // cp_obj.set("elem_postcode" , "postcode");

$(document).ready(function(){
  $("#btnFindPostCode").click(function(){
    $("#spnPostcode_result_display").show();
    $("#cmbPostCode").hide();
    $("#spnAJAXWait").show();
    var vPostCode = $("#postcode").val();
    vPostCode = vPostCode.replace(' ', '');
    var getAddressAPI = "/marjan.com/include/getaddress.php?postcode="+vPostCode;
    $.getJSON( getAddressAPI, {
      format: "json"
    })
      .done(function( data ) {
        var optionAddress = '';
        if(data != null){
          for(iCnt = 0, cnt = data.length; iCnt < cnt; iCnt++){
            optionAddress += '<option value="'+data[iCnt]+'">'+data[iCnt]+'</optioon>';
          }
          $("#spnAJAXWait").hide();
          $("#cmbPostCode").html(optionAddress);
          $("#cmbPostCode").show();
        }
        else{
          $("#spnAJAXWait").hide();
          $("#cmbPostCode").html('');
          $("#spnPostcode_result_display").hide(); 
          alert('Your postcode is not valid.\nPlease enter a valid postcode.'); 
          $("#address1").val('');
          $("#address2").val('');
          $("#town").val('');    
        }
      });
   });

  $("#cmbPostCode").change(function(){
    var arrAddress = $(this).val().split(',');
    $("#address1").val(arrAddress[0]);
    $("#address2").val(arrAddress[4]);
    $("#town").val(arrAddress[5]);

  });

  $('.rate-star').raty({
    path: 'css/raty/images',
    starOff: 'star-off-big.png',
    starOn: 'star-on-big.png'
  });
});