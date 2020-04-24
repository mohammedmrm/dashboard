<?php
ob_start();
session_start();
error_reporting(0);
require("_access.php");
access([1,2,3,5]);
require_once("dbconnection.php");
$style='
<style>

  td,th{
    padding:3px;
    text-align:center;
  }
  .head-tr {
   background-color: #ddd;
   color:#111;
  }
</style>';
require("../config.php");

$branch = $_REQUEST['branch'];
$city = $_REQUEST['city'];
$customer = $_REQUEST['customer'];
$order = $_REQUEST['order_no'];
$client= $_REQUEST['client'];
$status = $_REQUEST['orderStatus'];
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);


$total = [];
$money_status = trim($_REQUEST['money_status']);
if(empty($end)) {
  $end = date('Y-m-d 00:00:00', strtotime($end. ' + 1 day'));
}else{
   $end =date('Y-m-d', strtotime($end. ' + 1 day'));
   $end .=" 00:00:00";
}
if(empty($start)) {
  $start = date('Y-m-d 00:00:00');
}else{
   $start .=" 00:00:00";
}

try{
  $count = "select count(*) as count from orders ";
  $query = "select orders.*,
            clients.name as client_name,clients.phone as client_phone,
            cites.name as city,towns.name as town,branches.name as branch_name
            from orders left join
            clients on clients.id = orders.client_id
            left join cites on  cites.id = orders.to_city
            left join towns on  towns.id = orders.to_town
            left join branches on  branches.id = orders.to_branch
            ";
$where = "where";
  $filter = "";
  if($branch >= 1){
   $filter .= " and from_branch =".$branch;
  }
  if($city >= 1){
    $filter .= " and to_city=".$city;
  }
  if(($money_status == 1 || $money_status == 0) && $money_status !=""){
    $filter .= " and money_status='".$money_status."'";
  }
  if($client >= 1){
    $filter .= " and client_id=".$client;
  }
  if(!empty($customer)){
    $filter .= " and (customer_name like '%".$customer."%' or
                      customer_phone like '%".$customer."%')";
  }
  if(!empty($order)){
    $filter .= " and order_no like '%".$order."%'";
  }
  if($status >= 1){
    $filter .= " and order_status =".$status;
  }

  function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
  if(validateDate($start) && validateDate($end)){
      $filter .= " and date between '".$start."' AND '".$end."'";
     }
  if($filter != ""){
    $filter = preg_replace('/^ and/', '', $filter);
    $filter = $where." ".$filter;
    $count .= " ".$filter;
    $query .= " ".$filter." order by date";
  }else{
    $query .=" order by date";
  }

  $count = getData($con,$count);
  $orders = $count[0]['count'];
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
try{
  $i = 0;
  $content = "";
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
   <td width="30" align="center">'.$i.'</td>
   <td align="center">'.$data[$i]['order_no'].'</td>
   <td align="center">'.$data[$i]['client_name'].'<br />'.$data[$i]['client_phone'].'</td>
   <td width="100" align="center">'.$data[$i]['customer_name'].'<br />'.$data[$i]['customer_phone'].'</td>
   <td align="center">'.$data[$i]['branch_name'].'</td>
   <td align="center">'.$data[$i]['city'].'/'.$data[$i]['town'].'</td>
   <td width="100" align="center">'.$data[$i]['date'].'</td>
   <td align="center">'.number_format($data[$i]['price']).'</td>
   <td align="center">'.number_format($data[$i]['new_price']).'</td>
   <td align="center">'.number_format($data[$i]['dev_price']).'</td>
   <td align="center">'.number_format($data[$i]['discount']).'</td>
   <td align="center">'.number_format($data[$i]['client_price']).'</td>
 </tr>';
  $total['discount'] += $data[$i]['discount'];
  $total['dev_price'] += $data[$i]['dev_price'];
  $total['client_price'] += $data[$i]['client_price'];
  $i++;
}
$total['orders'] = $orders;
if($client >=1){
 $total['client'] = $data[0]['client_name'];
}else{
 $total['client'] = 'لم يتم تحديد عميل';
}

} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}

require_once("../tcpdf/tcpdf.php");

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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

// set default header data
$pdf->SetHeaderData("../../../".$config['Company_logo'],60, ' اسم العميل: '.$total['client']."               "." الفترة الزمنية: ".date("Y-m-d",strtotime($start))." || ".date("Y-m-d",strtotime($end)),"السعر الصافي للعميل: ".number_format($total['client_price'])."                "."سعر التوصيل:".number_format($total['dev_price'])."\n"."عدد الطلبيات: ".$total['orders']." ");

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
                                        <th>رقم الوصل</th>
										<th>اسم ورقم هاتف العميل</th>
										<th width="100">اسم ورقم هاتف المستلم</th>
										<th>الفرع المرسل له</th>
										<th>عنوان الارسال</th>
                                        <th width="100">تاريخ الادخال</th>
										<th>سعر الوصل</th>
										<th>المبلغ المستلم</th>
										<th>سعر التوصيل</th>
										<th>الخصم</th>
										<th>السعر الصافي للعميل</th>
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
$pdf->Output('order'.date('Y-m-d h:i:s').'.pdf', 'I');
?>