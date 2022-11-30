<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];


include $ROOT . "/vendor/autoload.php";

// include $ROOT . "/config/database.php";
// include $ROOT . "/classes/stockvell.classes.php";
// include $ROOT . "/classes/member.classes.php";
use Models\Stockvell\StockvellForms;
use Models\Member\MemberForms;





//  Create stockvell pack
if(isset($_POST['create_stockvell_pack'])){
    /**
   * @var getting all inputs
   */
  $name = $_POST["name"];
  $payment = $_POST["payment"];
  $payment_frequency = $_POST["payment_frequency"];
  $category = $_POST["category"];
  $max_member = $_POST["max_member"];
  $withdraw_frequency = $_POST["withdraw_frequency"];
  $description = $_POST["description"];
  $agreement = $_POST["agreement"];
  $currency = $_POST["currency"];
  $start_at = $_POST["start_at"];
  $end_at = $_POST["end_at"];
  
  $member_id = $_POST["member_id"];
  $address_proof = $_FILES["address_proof"];
  $govt_id_proof = $_FILES["govt_id_proof"];


  // echo json_encode(array($name, $goal, $payment, $payment_frequency, $category, $withdraw_frequency));
  // echo htmlspecialchars($agreement);
  // echo htmlentities($agreement);
  // echo strip_tags($agreement);

  // echo strval(htmlentities($agreement, ENT_COMPAT, 'UTF-8'));
  // exit();
  
  $stockvell_form = new StockvellForms();
  $stockvell_form->setStockvell($name, $payment, $description, $payment_frequency, $category, $max_member, $withdraw_frequency, $agreement, $currency, $start_at, $end_at );
  $stockvell_form->createStockvellPackByMember($member_id, $address_proof, $govt_id_proof);

}





if (isset($_POST["member_update_submit"])) {
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  /**
   * @var getting all inputs
   */
  $ROOT = $_SERVER['DOCUMENT_ROOT'];
  $member_id = $_SESSION['member_id'];

  $firstname = $_POST["firstname"];
  $surname = $_POST["surname"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $password2 = $_POST["password2"];
  $country = $_POST["country"];
  $phone = $_POST["phone"];
  $city = $_POST["city"];
  $gender = $_POST["gender"];
  $profession = $_POST["profession"];
  $interest = $_POST["interest"];
  $source = $_POST["source"];
  $govt_id = $_FILES["govt_id"];
  // echo json_encode($govt_id);



  $member_forms = new MemberForms();
  $member_forms->setMember($firstname, $surname, $email, $password, $password2, $country, $phone, $gender, $profession, $interest, $govt_id, $source, $city);
  $member_forms->updateDynamicMember($member_id, "edit_member/");
}

if(isset($_POST["stockvell_close_request_submit"])){
  $stockvell_id = $_POST["stockvell_id"];
  $leader_id = $_POST["leader_id"];
  $stockvell_form = new StockvellForms();
  $stockvell_form->requestToCloseStockvell($leader_id, $stockvell_id, "dashboard/");
}

if(isset($_POST["stockvell_generate_link_submit"])){
  $stockvell_id = $_POST["stockvell_id"];
  $leader_id = $_POST["leader_id"];
  $stockvell_form = new StockvellForms();
  $stockvell_form->generateStockvellLink($stockvell_id, 'dashboard/');
}


if(isset($_POST["withdraw_member_submit"])){
  $stockvell_id = $_POST["stockvell_id"];
  $withdraw_member_id = $_POST["withdraw_member_id"];
  // echo json_encode(array($stockvell_id, $withdraw_member_id));
  // exit();
  $stockvell_form = new StockvellForms();
  $stockvell_form->setWithdrawMember($stockvell_id, $withdraw_member_id, "dashboard/");
}

?>