<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require("_access.php");
access([1]);
require("dbconnection.php");
require("_crpt.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;


$success = 0;
$error = [];
$name    = $_REQUEST['branch_name'];
$email   = $_REQUEST['branch_email'];
$phone   = $_REQUEST['branch_phone'];
$city    = $_REQUEST['branch_city'];
$manager = $_REQUEST['branch_manager'];


$v->addRuleMessage('isPhoneNumber', ' رقم هاتف غير صحيح  ');

$v->addRule('isPhoneNumber', function($value, $input, $args) {
    return   (bool) preg_match("/^[0-9]{10,15}$/",$value);
});
$v->addRuleMessage('unique', 'القيمة المدخلة مستخدمة بالفعل ');

$v->addRule('unique', function($value, $input, $args) {

    $value  = trim($value);
    $exists = getData($GLOBALS['con'],"SELECT * FROM branches WHERE name =".$value);
    return ! (bool) count($exists);
});
$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'regex'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'مسموح ب {value} رمز كحد اعلى ',
    'email'      => 'البريد الالكتروني غيز صحيح',
]);

$v->validate([
    'branch_name'    => [$name,    'required'],
    'branch_email'   => [$email,   'email'],
    'branch_phone'   => [$phone,   "required|isPhoneNumber"],
    'branch_manager'   => [$manager,   "required|int"],
    'branch_city'  => [$city,  'required|int']
]);

if($v->passes()) {
  $sql = 'insert into branches (name,phone,email,city_id,manager) values
                             (?,?,?,?,?)';
  $result = setData($con,$sql,[$name,$phone,$email,$city,$manager]);
  if($result > 0){
    $success = 1;
  }
}else{
  $error = [
           'branch_name_err'=> implode($v->errors()->get('branch_name')),
           'branch_email_err'=>implode($v->errors()->get('branch_email')),
           'branch_phone_err'=>implode($v->errors()->get('branch_phone')),
           'branch_manager_err'=>implode($v->errors()->get('branch_manager')),
           'branch_city_err'=>implode($v->errors()->get('branch_city'))
           ];
}
echo json_encode(['success'=>$success, 'error'=>$error]);
?>