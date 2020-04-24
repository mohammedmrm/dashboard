<?php
require_once("script/_access.php");
access([1,2,5,6]);
?>
<?
include("config.php");
?>
<style>
fieldset {

		margin: 0;
		xmin-width: 0;
		padding: 10px;
		position: relative;
		border-radius:4px;
        border-bottom:2px solid #FF6600;
		background-color:#f5f5f5;
		padding-left:10px !important;
		width:100%;
}
legend
{
	font-size:14px;
	font-weight:bold;
	margin-bottom: 0px;
	width: 55%;
	border: 1px solid #ddd;
	border-radius: 4px;
	padding: 5px 5px 5px 10px;
	background-color: #ffffff;
}
@media print {
  body * {
    visibility: hidden;

  }
  #printReportForm, .header{
    display: none;
  }

  #section-to-print, #section-to-print * {
    visibility: visible;
    color: #000000;

  }
  #section-to-print {
    //position: absolute;
    margin:0px;
    padding: 0px;
    left: 0;

  }
  .dele, .edit{
   visibility: hidden;
   display: none;
  }
}
.text-white {
  color: #FFFFFF;
  padding: 15px;
}
 .form-group {
    margin-bottom: 0rem;
}
@page {
  size: landscape;
  margin: 5mm 5mm 5mm 5mm;
  }
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">

            </div>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
					<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				اضافه طلبيات
			</h3>
		</div>
	</div>


	<div class="kt-portlet__body">
    <form id="orderstabledata" class="kt-form kt-form--fit kt-margin-b-20">
          <div class="row kt-margin-b-20">
		  <div class="form-group col-lg-2">
				<label>ادخال حسب</label>
                <select data-show-subtext="true" onchange="byupdate()" type="text" class="selectpicker  form-control dropdown-primary" name="by" id="by"  value="">
                  <option value='store'>الصفحه (البيج)</option>
                  <option value='city'>المدينه</option>
                </select>
                <span id="branch_err" class="form-text text-danger"></span>
		  </div>
          <div class="form-group col-lg-2" style="display: none">
				<label>الفرع</label>
                <select data-show-subtext="true" data-live-search="true" type="text" class="  form-control dropdown-primary" name="mainbranch" id="mainbranch"  value="">

                </select>
                <span id="mainbranch_err" class="form-text text-danger"></span>
			</div>
            <div class="form-group col-lg-2">
				<label>الصفحه (البيج)</label>
                <select data-show-subtext="true" data-live-search="true" type="text" class="  form-control dropdown-primary" name="mainstore" id="mainstore"  value="">

                </select>
                <span id="mainstore_err" class="form-text text-danger"></span>
			</div>
<!--	        <div class="form-group col-lg-2">
				<label>هاتف</label>
				<input class="form-control" name="store_phone" id="store_phone" value="" />
                <span id="store_phone_err" class="form-text text-danger"></span>
			</div>
	        <div class="form-group col-lg-2">
                <label>-</label>
                <input data-toggle="modal" data-target="#addClientModal" type="button" class="btn btn-primary form-control" name="add_store" id="add_store" value="اضافة عميل"/>
            </div>-->
            <div class="form-group col-lg-2" style="display: ">
				<label>المدينه</label>
                <select  onchange="updatetown()" data-show-subtext="true" data-live-search="true" type="text" class="  form-control dropdown-primary" name="maincity" id="maincity"  value="">

                </select>
                <span id="maincity_err" class="form-text text-danger"></span>
			</div>
            <div class="form-group col-lg-2">
				<label>رمز الشركه</label>
				<input type="text" class="form-control" id="prefix" name="prefix" value="">
				<span id="prefix_err" class="form-text text-danger"></span>
			</div>
          </div>
         <div id="order-section">
         <fieldset><legend>شحنه رقم <span>1</span></legend>
          <div class="row">
<!--			<div class="form-group col-lg-2">
				<label>اسم المستلم</label>
				<input type="text" class="form-control" id="customer_name1" name="customer_name[]" value="">
				<span id="customer_name_err1" class="form-text text-danger"></span>
			</div>-->
            <div class="form-group col-lg-2">
            	<label>رقم الوصل:</label>
            	<input  no="no" id="order_no1" name="order_no[]" barcode="barcode"  type="text" class="form-control kt-input" placeholder="">
               <span id="order_no_err1" class="form-text text-danger"></span>
           </div>
			<div class="form-group col-lg-2" style="display:none;" >
				<label>الصفحه (البيج)</label>
				<select store='store'  data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="store[]" id="store1"  value="">

                </select>
                <span id="store_err1" storeerr="storeerr" class="form-text text-danger"></span>
			</div>
			<div class="form-group col-lg-2">
				<label>المحافظة المرسل لها</label>
				<select  city="city" onchange='getTowns($("#town1"),$("#city1").val())' city="city" data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="city[]" id="city1"  value="">

                </select>
                <span id="city_err1"class="form-text text-danger"></span>
			</div>
            <div class="form-group  col-lg-2">
				<label>القضاء او المنطقه</label>
				<select   town="town" data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="town[]" id="town1"  value="">

                </select>
                <span id="town_err" class="form-text text-danger"></span>
			</div>
            <div class="form-group col-lg-2">
				<label>تفاصيل اكثر عن العنوان</label>
				<textarea  address="address" type="text" class="form-control" name="order_address[]" value="" style="margin-top: 0px; margin-bottom: 0px; height: 38px;" ></textarea>
				<span id="order_address_err1" class="form-text text-danger"></span>
			</div>
            <div class="form-group  col-lg-2">
				<label>رقم الهاتف المستلم</label>
				<input  type="tel" phone="phone" style="direction: ltr !important;"  data-inputmask="'mask': '9999-999-9999'" value="07" class="form-control" id="customer_phone1" name="customer_phone[]"/>
				<span id="customer_phone_err1"  class="form-text text-danger"></span>
			</div>
            <div class="form-group col-lg-2">
				<label>المبلغ الكلي</label>
				<input   price="price" type="text" class="form-control" id="order" name="order_price[]" placeholder="المبلغ" value="">
				<span id="order_price_err1" class="form-text text-danger"></span>
			</div>
            <div class="form-group  col-lg-2">
				<label>ملاحظات</label>
				<textarea type="text" note="note" class="form-control" id="order_note1" name="order_note[]" value="" style="margin-top: 0px; margin-bottom: 0px; height: 38px;"></textarea>
				<span id="order_note_err1" class="form-text text-danger"></span>
			</div>
            <div class="form-group  col-lg-2">
            <br />
				<span case="case" class="form-text text-success"></span>
			</div>
            <div class="form-group  col-lg-2">
				<label>تاريخ الزبون</label> <br />
				<button type="button"  class="btn btn-clean btn-icon-lg" onclick="customerHistory(1)"><span class="fa fa-history"></span></button>
            </div>
            <div class="form-group  col-lg-6">
            <br />
               <div id="customerHistory1"></div>
            </div>
            <input type='hidden' order='order' id="order_id1" value='' name='order_id[]'>
<!--			<div class="form-group  col-lg-2">
				<label>الفرع المرسل له</label>
				<select data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="branch_to[]" id="branch_to1"  value="">

                </select>
                <span id="branch_to_err1" class="form-text text-danger"></span>
			</div>-->
<!--			<div class="form-group col-lg-2">
				<label>نوع الطلب</label>
				<select data-show-subtext="true" data-live-search="true" class="selectpicker  form-control" id="order_type1" name="order_type[]" placeholder="نوع الطلب" value="">
                    <option value="عام">عامة</option>
                    <option value="ملابس">ملابس</option>
                    <option value="الكترونيات">الكترونيات</option>
                    <option value="وثائق">وثائق</option>
                    <option value="اثاث">اثاث</option>
                </select>
				<span id="order_type_err1" class="form-text text-danger"></span>
			</div>-->
<!--			<div class="form-group col-lg-2">
				<label>العدد</label>
				<input type="number" class="form-control" id="qty1" name="qty[]" value="1"/>
				<span id="qty_err1" class="form-text text-danger"></span>
			</div>-->
<!--			<div class="form-group col-lg-2">
				<label>الوزن</label>
				<input type="number" class="form-control" id="weight1" name="weight[]" value="1"/>
				<span id="weight_err1" class="form-text text-danger"></span>
			</div>-->
           </div>
           <input type="hidden" value="1" id="num" name="num[]"/>
          </fieldset>
          </div>
          <div class="row kt-margin-b-20"><hr /></div>
          <div class="row kt-margin-b-20">
            <div class="form-group">
                <button type="button" class="btn btn-success" id="addmoreorders" onclick="addMore()">+ اصافة</button>
            </div>
            <div class="form-group  col-lg-1">
               <input type="number" value="1" step='1' max="50" min="1" id='addmorenumber' class="form-control" />
            </div>
          </div>
          <div class="row kt-margin-b-20">
            <span> ملاحظه : يمكن اضافه 50 شحنه بالمره الواحده</span>
          <hr /></div>
          <div class="row kt-margin-b-20 text-center">
              <button type="button" onclick="addOrders()" class="btn btn-info btn-lg text-white">رفع و تاكيد الشحنات</button>
          </div>
          <input  type="hidden" value="1" id="counter"/>

          <div id="confirmOrders">

          </div>
		<!--begin: Datatable -->
        </form>
        <!--end: Datatable -->
	</div>

</div>

</div>
<!-- end:: Content -->
</div>


            <!--begin::Page Vendors(used by this page) -->
                            <script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
                        <!--end::Page Vendors -->



            <!--begin::Page Scripts(used by this page) -->
                            <script src="assets/js/demo1/pages/components/datatables/extensions/responsive.js" type="text/javascript"></script>
<script src="js/getBraches.js" type="text/javascript"></script>
<script src="js/getClients.js" type="text/javascript"></script>
<script src="js/getStores.js" type="text/javascript"></script>
<script src="js/getorderStatus.js" type="text/javascript"></script>
<script src="js/getCities.js" type="text/javascript"></script>
<script src="js/getTowns.js" type="text/javascript"></script>
<script src="js/scanner-jquery.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js" type="text/javascript"></script>
<script type="text/javascript">
getCities($("#maincity"));
getCities($("#city1"));
getTowns($("#town1"),$("#city1").val());
getBraches($("#branch_to1"));
getBraches($("#mainbranch"));
$(function () {
  $('[data-toggle="popover"]').popover()
})
function  customerHistory(){
  $('[data-toggle="popover"]').popover();
}
function getAllClient(ele){
   $.ajax({
     url:"script/_getClientsAll.php",
     type:"POST",
     success:function(res){
       ele.html("");
       ele.append(
           '<option value="">... اختر ...</option>'
       );
       $.each(res.data,function(){
         ele.append("<option value='"+this.id+"'>"+this.name+"-"+this.phone+"</option>");
       });
       console.log(res);
       ele.selectpicker('refresh');
     },
     error:function(e){
        ele.append("<option value='' class='bg-danger'>خطأ اتصل بمصمم النظام</option>");
        console.log(e);
     }
   });
}

$(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
	endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	onComplete: function(barcode, qty){
	  $.ajax({
           url:"script/_getOrderBarcode.php",
           data:{id: Number(barcode) },
           type:"POST",
           success:function(res){
            console.log(res)
            if(res.success == 1){
              $.each(res.data,function(){
                $("[price='price']").last().val(this.price);
                $("[no='no']").last().val(this.order_no);
                $('[store="store"]').last().val(this.store_id);
                $("[city='city']").last().val(this.to_city);
                getTowns($("[town='town']").last(),$("[city='city']").last().val());
                $("[town='town']").last().val(this.to_town);
                $("[address='address']").last().val(this.address);
                $("[town='price']").last().val(this.price);
                $("[phone='phone']").last().val(this.customer_phone);
                $("[note='note']").last().val(this.note);
                $("[order='order']").last().val(Number(barcode));
                $("[case='case']").last().html('<h2>تاكيد فقط</h2>');
                $('.selectpicker').selectpicker('refresh');
                $("#confirmOrders").append("<input type='hidden' value='"+barcode+"' name='cOrder[]'>");
                $("#addmorenumber").val(1);
                addMore();
              });
            }
           },
           error:function(e){
             console.log(e);
           },
         })

     } // main callback function
});

getStores($("#mainstore"));
getStores($("#store1"));
var counter = 1;
function addMore(){
  $("#addmoreorders").addClass("kt-spinner kt-spinner--lg kt-spinner--danger");
  n = Number($("#addmorenumber").val());
  content = "";
  cities = $('#city1').html();
  towns = $('#town1').html();
  stores = $('#mainstore').html();
  branch_to = $('#branch_to1').html();
  order_note = $("#order-section fieldset:last textarea:last").val();
  order_no = Number($("#order-section fieldset:last input:first").val());

  console.log(order_note);
  if(Number($("#order-section fieldset").length) <= 501 && Number(n) <= 50){
  for (i = 0; i < n; i++) {
    if(order_no != 0){
    order_no = order_no + 1;
    }else{
      order_no = "";
    }
    $("#counter").val(Number($("#counter").val())+1);
    number =  $("#counter").val();
    content =content + `<fieldset id="f`+number+`"><legend>شحنه رقم `+number+`</legend>
          <div class="row">
            <div class="form-group col-lg-2">
            	<label>رقم الوصل:</label>
            	<input  no="no" id="order_no`+number+`" value="" name="order_no[]"  type="text" class="form-control kt-input" placeholder="">
                <span id="order_no_err`+number+`" class="form-text text-danger"></span>
            </div>
			<div class="form-group col-lg-2" style="display:none;">
				<label>الصفحه</label>
				<select  store="store"  data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="store[]" id="store`+number+`"  value="">
                  `+stores+`
                </select>
                <span id="store_err`+number+`" storeerr="storeerr" class="form-text text-danger"></span>
			</div>
			<div class="form-group col-lg-2">
				<label>المحافظة</label>
				<select  city="city" onchange='`+`getTowns($("#town`+number+`"),$("#city`+number+`").val())`+`' city="city" data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="city[]" id="city`+number+`"  value="">
                  `+cities+`
                </select>
                <span id="city_err`+number+`"  class="form-text text-danger"></span>
			</div>
            <div class="form-group  col-lg-2">
				<label> القضاء او المنطقه</label>
				<select  town="town" data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="town[]" id="town`+number+`"  value="">
                  `+towns+`
                </select>
                <span id="town_err`+number+`" class="form-text text-danger"></span>
			</div>
            <div class="form-group col-lg-2">
				<label>تفاصيل اكثر عن العنوان</label>
				<textarea  type="text" class="form-control" id="order_address`+number+`" name="order_address[]" value="" style="margin-top: 0px; margin-bottom: 0px; height: 38px;" ></textarea>
				<span id="order_address_err`+number+`" class="form-text text-danger"></span>
			</div>
            <div class="form-group  col-lg-2">
				<label>رقم الهاتف</label>
				<input  phone="phone" type="tel" style="direction: ltr !important;" data-inputmask="'mask': '9999-999-9999'" class="form-control" id="customer_phone`+number+`" name="customer_phone[]" value="07" />
				<span id="customer_phone_err`+number+`" class="form-text text-danger"></span>
			</div>
           <div  class="form-group col-lg-2">
				<label>المبلغ الكلي</label>
				<input foucs="foucs" price="price"  type="text" class="form-control" id="order_price`+number+`" name="order_price[]" placeholder="المبلغ" value="">
				<span id="order_price_err`+number+`" class="form-text text-danger"></span>
			</div>
            <div class="form-group  col-lg-2">
				<label>ملاحظات</label>
				<textarea  note="note" type="text" class="form-control" name="order_note[]" style="height: 38px;">`+order_note+`</textarea>
				<span id="order_note_err`+number+`" class="form-text text-danger"></span>
			</div>

<!--            <div class="form-group col-lg-2">
				<label>الاسم المستلم</label>
				<input type="text" class="form-control" id="customer_name`+number+`" name="customer_name[]" value="">
				<span id="customer_name_err`+number+`" class="form-text text-danger"></span>
			</div>-->
<!--			<div class="form-group  col-lg-2">
				<label>الفرع المرسل له</label>
				<select data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="branch_to[]" id="branch_to`+number+`"  value="">
                   `+branch_to+`
                </select>
                <span id="branch_to_err`+number+`" class="form-text text-danger"></span>
			</div>-->
<!--			<div class="form-group col-lg-2">
				<label>نوع الطلب</label>
				<select data-show-subtext="true" data-live-search="true" class="  form-control" id="order_type`+number+`" name="order_type[]" placeholder="نوع الطلب" value="">
                    <option value="عام">عامة</option>
                    <option value="ملابس">ملابس</option>
                    <option value="الكترونيات">الكترونيات</option>
                    <option value="وثائق">وثائق</option>
                    <option value="اثاث">اثاث</option>
                </select>
				<span id="order_type_err" class="form-text text-danger"></span>
			</div>-->
<!--			<div class="form-group col-lg-2">
				<label>العدد</label>
				<input type="number" class="form-control" id="qty`+number+`" name="qty[]" value="1"/>
				<span id="qty_err`+number+`" class="form-text text-danger"></span>
			</div>
			<div class="form-group col-lg-1">
				<label>الوزن</label>
				<input type="number" class="form-control" id="weight`+number+`" name="weight[]" value="1"/>
				<span id="weight_err`+number+`" class="form-text text-danger"></span>
			</div>-->
            <div class="form-group  col-lg-2">
				<br />
				<span case="case" class="form-text text-success"></span>
			</div>
            <div class="form-group  col-lg-2">
				<label>تاريخ الزبون</label> <br />
				<button type="button"  class="btn btn-clean btn-icon-lg" onclick="customerHistory(`+number+`)"><span class="fa fa-history"></span></button>
            </div>
            <div class="form-group  col-lg-5">
             <br />
               <div id="customerHistory`+number+`"></div>
            </div>
            <div class="form-group  col-lg-1">
              <label>حذف</label><br />
              <button type="button" class="btn btn-danger" onclick="deleteOrder(`+number+`)"><span class="flaticon-delete"></span></button>
            </div>
           </div>
           <input type="hidden" value="`+number+`" id="num" name="num[]"/>
           <input type='hidden' order='order' id="order_id`+number+`" value='' name='order_id[]'>
          </fieldset>`;
  }

    $('#order-section').append(content);
    $('.selectpicker').selectpicker('refresh');
    if($('#by').val() == 'city'){
      $("[store='store']").parent().parent().css('display','inline-block');
      $("[city='city']").parent().parent().css('display','none');
    }else{
      $("[store='store']").parent().parent().css('display','none');
      $("[city='city']").parent().parent().css('display','inline-block');
    }
  }else{
    Toast.warning("لايمكن اضافه اكتر من 50 شحنه",'خطأ');
  }
  $("#addmoreorders").removeClass("kt-spinner kt-spinner--lg kt-spinner--danger");
    $(":input").inputmask(); 
}
$('#maincity').parent().css('display','none');

function byupdate(){
  if($('#by').val() == 'city'){
    $('#mainstore').parent().parent().css('display','none');
    $('#add_store').parent().css('display','none');
    $('#maincity').parent().parent().css('display','inline-block');
    $('#mainbranch').parent().css('display','inline-block');
    $("[store='store']").parent().parent().css('display','inline-block');
    $("[city='city']").parent().parent().css('display','none');
  }else{
    $('#mainstore').parent().parent().css('display','inline-block');
    $('#add_store').parent().css('display','inline-block');
    $('#maincity').parent().parent().css('display','none');
    $('#mainbranch').parent().css('display','none');
    $("[store='store']").parent().parent().css('display','none');
    $("[city='city']").parent().parent().css('display','inline-block');
  }
}


function addOrders(){
  $.ajax({
    url:"script/_addOrders.php",
    type:"POST",
    data:$("#orderstabledata").serialize(),
    beforeSend:function(){
      $('.text-danger').text('');
    },
    success:function(res){
        console.log(res);
       if(res.success == 1){
         $("#kt_form input[name='order_no[]']").val("");
         $("#kt_form input[name='order_price[]']").val("");
         $("#kt_form input[name='customer_name[]']").val("");
         $("#kt_form input[name='customer_phone[]']").val("");
         $("#kt_form input[name='order_note[]']").val("");
         Toast.success('تم الاضافة');
         $("#kt_form .text-danger").text("");
       }else{
            no = res.error["no"]  ;
           $("#order_no_err"+no).text(res.error["order_no"]);
           $("#order_type_err"+no).text(res.error["order_type"]);
           $("#order_price_err"+no).text(res.error["order_price"]);
           $("#weight_err"+no).text(res.error["weight"]);
           $("#qty_err"+no).text(res.error["qty"]);
           $("#store_err"+no).text(res.error["store"]);
           $("#store_phone_err"+no).text(res.error["store_phone"]);
           $("#customer_name_err"+no).text(res.error["customer_name"]);
           $("#customer_phone_err"+no).text(res.error["customer_phone"]);
           $("#city_err"+no).text(res.error["city"]);
           $("#town_err"+no).text(res.error["town"]);
           $("#branch_err"+no).text(res.error["branch_from"]);
           $("#branch_to_err"+no).text(res.error["branch_to"]);
           $("#town_err"+no).text(res.error["town"]);
           $("#with_dev_err"+no).text(res.error["with_dev"]);
           $("#order_note_err"+no).text(res.error["order_note"]);
           $("#order_address_err"+no).text(res.error["order_address"]);
           $("#mainbranch_err").text(res.error["branch_from"]);
           $("#maincity_err").text(res.error["city"]);
           $("#mainstore_err").text(res.error["store"]);
           $("#prefix_err").text(res.error["prefix"]);

           if(
           res.error["order_no"] != null ||
           res.error["order_type"] != null ||
           res.error["order_price"] != null ||
           res.error["dev_price"] != null ||
           res.error["weight"] != null   ||
           res.error["qty"] != null
           ){$("#s1").addClass("text-danger");}
           if(
           res.error["branch_from"] != null ||
           res.error["store_phone"] != null ||
           res.error["store"] != null
           ){$("#s2").addClass("text-danger");}
           if(
           res.error["customer_name"] != null ||
           res.error["customer_phone"] != null ||
           res.error["city"]!= null ||
           res.error["town"]!= null
           ){$("#s3").addClass("text-danger");}
           if(
           res.error["branch_to"] != null
           ){$("#s4").addClass("text-danger");}
           Toast.warning("هناك بعض المدخلات غير صالحة",'شحنه رقم '+no);
       }
    },
    error:function(e){
      console.log(e);
       Toast.error('خطأ');
    }
  });
}
function updatetown(){
  getTowns($('[town="town"]'),$("#maincity").val());
}
function deleteOrder(no){
  $("#f"+no).fadeOut(300, function() { $(this).remove(); });;
}

$(document).ready(function(){
  //$('[type="tel"]').mask('0000-000-0000');
  $(":input").inputmask();
});
jQuery.extend(jQuery.expr[':'], {
    focusable: function (el, index, selector) {
        return $(el).is('a, button, :input, [tabindex]');
    }
});
$(document).keydown(function(e) {
if (event.which === 13 || event.keyCode === 13 ) {
    event.stopPropagation();
    event.preventDefault();
    $(":focus").closest('fieldset').nextAll().find('[no="no"]').focus();
}


var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
if(key == 37) {
    $(":focus").closest('.form-group').next().find('input,button,textarea').first().focus();  
}
if(key == 39) {
    $(":focus").closest('.form-group').prev().find('input,button,textarea').first().focus();
}
});

function customerHistory(ref){
  $.ajax({
        url:"charts/_getCustomerHistory.php",
        type:"POST",
        data:{phone: $("#customer_phone"+ref).val()},
        beforeSend:function(){
          $("#customerHistory"+ref).addClass("loading");
        },
        success:function(res){
          $("#customerHistory"+ref).removeClass("loading");
          console.log(res);
          if(res.success == 1 && res.data[0].recieved != null){
           $.each(res.data,function(){
             $("#customerHistory"+ref).html(
             '<span style="color:#006600">المستلمة:</span><span style="color:#006600">'+this.recieved+'</span> | '+
             '<span style="color:#CC0000">الراجعه: </span><span style="color:#CC0000">'+this.returnd+'</span> | '+
             '<span style="color:#333333">اخر ملاحظه: </span><span style="color:#333333">'+this.note+'</span>'
             );
           });
          }else{
            $("#customerHistory"+ref).html(
             '<span>لايوجد بينات</span><br />'
             );
          }
        },
        error:function(e){
        $("#customerHistory").removeClass("loading");
                    $("#customerHistory").html(
             '<span>لايوجد بينات</span><br />'
        );
        }
  });
}

</script>