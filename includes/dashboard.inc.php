<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];


include $ROOT . "/vendor/autoload.php";

// include $ROOT . "/config/database.php";
// include $ROOT . "/classes/stockvell.classes.php";
// include $ROOT . "/classes/member.classes.php";
use Models\Stockvell\Stockvell;
use Models\Stockvell\StockvellForms;
use Models\Member\MemberForms;


$foundMember = new Stockvell(); // Variables getting from dashboard.php
$foundMember->setMember($member_email, $member_id );
$cmr_result = $foundMember->getCurrentMember(); // cm = current member result
$psr_result = $foundMember->getAllPendingStockvell("PENDING", false); // psr = pending search result
$asr_result = $foundMember->getAllApprovedStockvellOfAMember("APPROVED", $member_id); // asr = approved search result



//  Create stockvell pack
if(isset($_POST['create-stockvell'])){
    /**
   * @var getting all inputs
   */
  $name = $_POST["name"];
  $goal = $_POST["goal"];
  $payment = $_POST["payment"];
  $payment_frequency = $_POST["payment_frequency"];
  $category = $_POST["category"];
  $withdraw_frequency = $_POST["withdraw_frequency"];
  $agreement = $_POST["agreement"];

  // echo json_encode(array($name, $goal, $payment, $payment_frequency, $category, $withdraw_frequency));
  // echo htmlspecialchars($agreement);
  // echo htmlentities($agreement);
  // echo strip_tags($agreement);

  // echo strval(htmlentities($agreement, ENT_COMPAT, 'UTF-8'));
  // exit();
  
  $stockvell_control = new StockvellForms($name, $goal, $payment, $payment_frequency, $category, $withdraw_frequency, $agreement);
  $stockvell_control->validateAndCreate();

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

?>