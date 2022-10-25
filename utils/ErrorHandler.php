<?php

namespace Utils;

/**
 * @validate all inputs and show all errors
 */
class ErrorHandler
{

    public function __construct()
    {
        $this->errors = "";
    }


    public function setCommonErrors($error)
    {
        $error = $_GET["error"];
        // echo $error;
        switch ($error) {
            case 'stmtfailed': {
                    $this->errors =  __("Invalid MySQL query!");
                    break;
                }
            case 'pperror': {
                    $this->errors =  __("You must agree with our privacy policy!");
                    break;
                }            
            case 'reachlimit': {
                    $this->errors =  __("This pack reach maximum number of a pack please try to join another pack");
                    break;
                }
            case 'incorrectpassword': {
                    $this->errors =  __("Incorrect password!");
                    break;
                }
            case 'usernotfound': {
                    $this->errors =  __("This email address is not in our database. Use a registered email address!");
                    break;
                }
            case 'stockvellnotfound': {
                    $this->errors =  __("This stockvell pack is not found in the database!");
                    break;
                }
            case 'emptyinput': {
                    $this->errors =  __("Make sure to fill in all the fields!");
                    break;
                }
            case 'invalidusername': {
                    $this->errors =  __("The username should be more than one character long!");
                    break;
                }
            case 'invalidphone': {
                    $this->errors =  __("Make sure to use a valid phone number!");
                    break;
                }
            case 'invalidemail': {
                    $this->errors =  __("Make sure to use a valid email address!");
                    break;
                }
            case 'passwordnotmatch': {
                    $this->errors =  __("Password did not match!");
                    break;
                }
            case 'alreadyexist': {
                    $this->errors =  __("This email address already exists!");
                    break;
                }
            case 'alreadymember': {
                    $this->errors =  __("You are already a member of this pack!");
                    break;
                }
            case 'invalidfile': {
                    $this->errors =  __("Government ID must be less than 2 megabytes and allowed file formats are pdf, png, jpeg, and jpg!");
                    break;
                }
            case 'invalidcode': {
                    $this->errors =  __("Recovery code is not valid please try again!");
                    break;
                }
            case 'alreadyexistphone': {
                    $this->errors =  __("An account with this phone number already exists. please use another one!");
                    break;
                }
            case 'none': {
                    $this->errors =  __("Operation successfull!");
                    break;
                }

            default:
                # code...
                break;
        }
        return $this->errors;
    }

    public function displayErrors()
    {
        if (isset($_GET["error"])) {
            $error = $_GET["error"];
            if ($error === "none") {
                return "<div class='alert alert-success'>
                            <div class='success-msg d-flex align-items-center'>
                                <img src='/public/icons/success.svg' width='25' alt='success-message' class='success-message mx-3'>
                                <p class='m-0'>$this->errors</p>
                            </div>
                        </div>";
            } else {
                return "<div class='alert alert-danger'>
                                <div class='err-msg d-flex align-items-center'>
                                    <img src='/public/icons/error.svg' width='25' alt='error-message' class='error-message mx-3'>
                                    <p class='m-0'>$this->errors</p>
                                </div>
                        </div>";
            }
        }
    }
}
