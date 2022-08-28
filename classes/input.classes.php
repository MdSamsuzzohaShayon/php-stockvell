<?php

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
                    array_push($this->errors, "Invalid MySQL query!");
                    break;
                }
            case 'incorrectpassword': {
                    array_push($this->errors, "Incorrect password!");
                    break;
                }
            case 'usernotfound': {
                    array_push($this->errors, "This email address is not in our database. Use a registered email address!");
                    break;
                }
            case 'emptyinput': {
                    array_push($this->errors, "Make sure to fill all the fields!");
                    break;
                }
            case 'invalidusername': {
                    array_push($this->errors, "Username should be more than one charecter long!");
                    break;
                }
            case 'invalidphone': {
                    array_push($this->errors, "Make sure to use a valid phone number!");
                    break;
                }
            case 'invalidemail': {
                    array_push($this->errors, "Make sure to use a valid email address!");
                    break;
                }
            case 'passwordnotmatch': {
                    array_push($this->errors, "Password did not match!");
                    break;
                }
            case 'alreadyexist': {
                    array_push($this->errors, "This email address is already exist!");
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
                <img src='public/icons/error.svg' width='25' alt='error-message' class='error-message mx-3'>
                <p class='m-0'>$err</p>
                </div>";
            }
            return "<div class='alert alert-danger'>$err_msg</div>";
        }
    }
}
