<?php

if (isset($_POST["submit"])) {
  /**
   * @var getting all inputs
   */
  $firstname = $_POST["firstname"];
  $surname = $_POST["surname"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $password2 = $_POST["password2"];
  $country = $_POST["country"];
  $phone = $_POST["phone"];
  $gender = $_POST["gender"];
  $profession = $_POST["profession"];
  $interest = $_POST["interest"];
  $govt_id = $_POST["govt_id"];
  $source = $_POST["source"];

  // $input_list = array(
  //   'firstname' => $firstname,
  //   'surname' => $surname,
  //   'email' => $email,
  //   'password' => $password,
  //   'password2' => $password2,
  //   'country' => $country,
  //   'phone' => $phone,
  //   'gender' => $gender,
  //   'profession' => $profession,
  //   'interest' => $interest,
  //   'govt_id' => $govt_id,
  //   'source' => $source,
  // );

  // echo json_encode($input_list);
  // exit();


  include "../config/database.php";
  include "../classes/signup.classes.php";

  $signup = new SignupController($firstname, $surname, $email, $password, $password2, $country, $phone, $gender, $profession, $interest, $govt_id, $source);
  $signup->signupMember();

  header("location: ../login.php");
}
