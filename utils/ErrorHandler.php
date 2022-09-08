<?php
namespace Utils;

/**
 * @validate all inputs and show all errors
 */
class ErrorHandler
{

    public function __construct()
    {
        $this->errors = [];
    }


    public function setCommonErrors($error)
    {
        $error = $_GET["error"];
        // echo $error;
        switch ($error) {
            case 'stmtfailed': {
                    $err_msg = __("Invalid MySQL query!");
                    array_push($this->errors, $err_msg);
                    break;
                }
            case 'incorrectpassword': {
                    $err_msg = __("Incorrect password!");
                    array_push($this->errors, $err_msg);
                    break;
                }
            case 'usernotfound': {
                    $err_msg = __("This email address is not in our database. Use a registered email address!");
                    array_push($this->errors, $err_msg);
                    break;
                }
            case 'emptyinput': {
                    $err_msg = __("Make sure to fill in all the fields!");
                    array_push($this->errors, $err_msg);
                    break;
                }
            case 'invalidusername': {
                    $err_msg = __("The username should be more than one character long!");
                    array_push($this->errors, $err_msg);
                    break;
                }
            case 'invalidphone': {
                    $err_msg = __("Make sure to use a valid phone number!");
                    array_push($this->errors, $err_msg);
                    break;
                }
            case 'invalidemail': {
                    $err_msg = __("Make sure to use a valid email address!");
                    array_push($this->errors, $err_msg);
                    break;
                }
            case 'passwordnotmatch': {
                    $err_msg = __("Password did not match!");
                    array_push($this->errors, $err_msg);
                    break;
                }
            case 'alreadyexist': {
                    $err_msg = __("This email address already exists!");
                    array_push($this->errors, $err_msg);
                    break;
                }
            case 'invalidfile': {
                    $err_msg = __("Government ID must be less than 2 megabytes and allowed file formats are pdf, png, jpeg, and jpg!");
                    array_push($this->errors, $err_msg);
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
        $err_msg = '';
        if (count($this->errors) > 0) {
            foreach ($this->errors as $err) {
                $err_msg .= "<div class='err-msg d-flex align-items-center'>
                <img src='/public/icons/error.svg' width='25' alt='error-message' class='error-message mx-3'>
                <p class='m-0'>$err</p>
                </div>";
            }
            return "<div class='alert alert-danger'>$err_msg</div>";
        }
    }
}
