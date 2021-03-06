<?php
ob_start();
session_start();
error_reporting(0);
header('Content-Type: application/json');
require("_access.php");
access([1,2,3,5]);
require_once("dbconnection.php");
$style='
<style>
 td,th{
    padding:3px;
    text-align:center;
  }
';
require("../config.php");

$status = 4;
$store = $_REQUEST['store'];



$total = [];

try{
  $count = "select count(*) as count from orders ";
  $query = "select orders.*, date_format(orders.date,'%Y-%m-%m') as dat,
            clients.name as client_name,clients.phone as client_phone,
            cites.name as city,towns.name as town,branches.name as branch_name,
            stores.name as store_name
            from orders
            left join clients on clients.id = orders.client_id
            left join cites on  cites.id = orders.to_city
            left join stores on  stores.id = orders.store_id
            left join towns on  towns.id = orders.to_town
            left join branches on  branches.id = orders.to_branch
            ";
  $where = "where invoice_id = 0 and ";
  $filter = "";

  if($status >= 1){
    $filter .= " and order_status_id =".$status;
  }
  if($store >= 1){
    $filter .= " and store_id=".$store;
  }

  if($filter != ""){
     $filter = preg_replace('/^ and/', '', $filter);
     $filter = $where." ".$filter;
     $count .= " ".$filter;
     $query .= " ".$filter." order by orders.date";
  }else{
    $query .=" order by orders.date";
  }

  $count1 = getData($con,$count);
  $orders = $count1[0]['count'];
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
// set default header data
if($status == 4){
  $status_name = "مستلمة";
  $style .= "
  .head-tr {
   background-color: #33CC00;
   color:#111;
  }
</style>
  ";
}else if($status == 6 || $status == 9 ){
  $status_name = "راجعه";
 $style .= "
  .head-tr {
   background-color: #FF3300;
   color:#111;
  }
</style>
  ";
}else if($status == 7){
  $status_name = "مؤجل";
   $style .= "
  .head-tr {
   background-color: #FFFF99;
   color:#111;
  }
</style>
  ";
}else{
  $status_name = "غير معروفه";
   $style .= "
  .head-tr {
   background-color: #CCCCCC;
   color:#111;
  }
</style>
  ";
}
if($orders > 0){
    try{
        $i = 0;
        $content = "";
        $success = 0;
        $pdf_name = date('Y-m-d')."_".uniqid().".pdf";
        $sql = "insert into invoice (path,store_id,orders_status) values(?,?,?)";
        $res = setData($con,$sql,[$pdf_name,$store,$status]);
    if($res > 0){
      $success = 1;
      $sql = "select * from invoice where path=? and store_id =? order by date DESC limit 1";
      $res = getdata($con,$sql,[$pdf_name,$store]);
      $invoice = $res[0]['id'];

        foreach($data as $k=>$v){
          $total['income'] += $data[$i]['new_price'];
                $sql = "select * from client_dev_price where client_id=? and city_id=?";
                $dev_price  = getData($con,$sql,[$v['client_id'],$v['to_city']]);
                if(count($dev_price) > 0){
                  $dev_p = $dev_price[0]['price'];
                }else{
                  if($v['to_city'] == 1){
                   $dev_p = $config['dev_b'];
                  }else{
                   $dev_p = $config['dev_o'];
                  }
                }
                $data[$i]['dev_price'] = $dev_p;
                $data[$i]['client_price'] = ($data[$i]['new_price'] -  $dev_p) + $data[$i]['discount'];

        $hcontent .=
         '<tr>
           <td width="30" align="center">'.($i+1).'</td>
           <td width="100" align="center">'.$data[$i]['dat'].'</td>
           <td align="center">'.$data[$i]['order_no'].'</td>
           <td width="100" align="center">'.$data[$i]['customer_phone'].'</td>
           <td align="center" width="180">'.$data[$i]['city'].'/'.$data[$i]['town'].$data[$i]['address'].'</td>
           <td align="center">'.number_format($data[$i]['price']).'</td>
           <td align="center">'.number_format($data[$i]['new_price']).'</td>
           <td align="center">'.number_format($data[$i]['dev_price']).'</td>
           <td align="center">'.number_format($data[$i]['client_price']).'</td>
           <td align="center">'.$data[$i]['note'].'</td>
         </tr>';
          $total['discount'] += $data[$i]['discount'];
          $total['dev_price'] += $data[$i]['dev_price'];
          $total['client_price'] += $data[$i]['client_price'];
          $i++;

          //--- update invoice for each order
           $sql = "update orders set invoice_id =? where id=?";
           $res = setData($con,$sql,[$invoice,$v['id']]);
       }
       $total['invoice'] = $invoice;
       $total['status'] = $status_name;
       $total['date'] = $res[0]['id'];
    }



          $total['orders'] = $orders;
          if($store >=1){
           $total['client'] = $data[0]['client_name'];
           $total['store'] = $data[0]['store_name'];
          }else{
           $total['client'] = 'لم يتم تحديد عميل';
           $total['store'] = 'لم يتم التحديد';
          }

    } catch(PDOException $ex) {
       $data=["error"=>$ex];
       $success="0";
    }

require_once("../tcpdf/tcpdf.php");
class MYPDF extends TCPDF {
    public function Header() {
        // Set font
        $t = $GLOBALS['total'];
        $this->SetFont('aealarabiya', 'B', 12);
        // Title
        $this->writeHTML('

         <table>
         <tr>
          <td rowspan="4"><img src="../img/logos/logo.png" height="90px"/></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
         </tr>
         <tr>
          <td width="230px">اسم العميل او الصفحه:'. $t['client'].'</td>
          <td width="400px" style="color:#FF0000;text-align:center;display:block;">كشف حساب ('.$t['status'].')</td>
          <td >التاريخ:'.$t['date'].'</td>
         </tr>
         <tr>
          <td width="230px">الصافي للعميل:'.$t['client_price'].'</td>
          <td width="400px" style="text-align:center;display:block;">عدد الطلبيات:'.$t['orders'].'</td>
          <td >رقم الكشف:'.$t['orders'].'</td>
         </tr>
        </table>
        ');
    }
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('07822816693');
$pdf->SetTitle('تقرير الطلبيات');
$pdf->SetSubject($start."-".$end);
// set some language dependent data:
$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'ar';
$lg['w_page'] = 'page';

// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);
// set font
$pdf->SetFont('aealarabiya', '', 12);


//$pdf->SetHeaderData("../../../".$config['Company_logo'],30, ' اسم الصفحه او البيح: '.$total['store']."               "." الفترة الزمنية: ".date("Y-m-d",strtotime($start))." || ".date("Y-m-d",strtotime($end))," حالة الطلبات : ".$status_name."\n"."السعر الصافي للعميل: ".number_format($total['client_price'])."                "."\n"."عدد الطلبيات: ".$total['orders']." ");

// set header and footer fonts
$pdf->setHeaderFont(Array('aealarabiya', '', 12));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// ---------------------------------------------------------


// add a page
$pdf->AddPage('L', 'A4');

// Persian and English content

$htmlpersian = '		<table border="1" class="table">
			       <thead>
	  						<tr  class="head-tr">
                                        <th width="30">#</th>
                                        <th width="100">تاريخ الادخال</th>
										<th>رقم الوصل</th>
										<th width="100">اسم ورقم هاتف المستلم</th>
										<th width="180">عنوان الارسال</th>
                                        <th>سعر الوصل</th>
										<th>المبلغ المستلم</th>
										<th>سعر التوصيل</th>
										<th>السعر الصافي للعميل</th>
										<th>الملاحظات</th>
							</tr>
      	            </thead>
                            <tbody id="ordersTable">'
                            .$hcontent.
                            '</tbody>
		</table>
        ';
$pdf->WriteHTML($style.$htmlpersian, true, 0, true, 0);

// set LTR direction for english translation
$pdf->setRTL(false);

$pdf->SetFontSize(10);
// print newline
$pdf->Ln();
//Close and output PDF document
ob_end_clean();
//print_r($hcontent);

$pdf->Output(dirname(__FILE__).'/../invoice/'.$pdf_name, 'F');
}else{
  $success = 2;
}
echo json_encode(['num'=>$count,'success'=>$success,'invoice'=>$pdf_name]);
?>