<?php

function inputElement($input_name, $input_text, $single_input, $input_type, $default_value = null, $additional_con_class = null, $additional_class = null, $item_list = [], $required=false)
{
    $input_con_class = "";
    $single_input === false ? $input_con_class .= "col-md-6 " : $input_con_class .= "one-input ";
    if (!is_null($additional_con_class)) $input_con_class .= $additional_con_class;

    $input_class = "form-control text-primary bg-secondary border border-primary ";
    if (!is_null($additional_class)) $input_class .= $additional_class;

    $new_default = "";
    if (!is_null($default_value)) $new_default = $default_value;

    $required_input = "";
    if($required === true) $required_input = "required";


    if ($input_type === "text" || $input_type === "email" || $input_type === "password" || $input_type === "number") {
        return "<div class='$input_con_class'>
                    <label for='$input_name' class='form-label text-capitalize'>$input_text</label>
                    <input $required_input type='$input_type' value='$new_default' name='$input_name' class='$input_class' id='$input_name'>
                </div>";
    } elseif ($input_type === 'file') {
        return "<div class='$input_con_class'>
                    <label for='$input_name' class='form-label text-capitalize'>$input_text</label>
                    <input $required_input type='$input_type' value='$new_default' name='$input_name' class='$input_class' id='$input_name'>
                </div>";
    } elseif ($input_type === 'textarea') {
        return "<div class='$input_con_class'>
                    <label for='$input_name' class='form-label text-capitalize'>$input_text</label>
                    <textarea $required_input rows='2' name='$input_name' class='$input_class' id='$input_name'> $new_default </textarea>
                </div>";
    } elseif ($input_type === 'select') {
        $option_list = "";
        foreach ($item_list as $item) {
            if ($item === $new_default) {
                $option_list .= "<option value='$item' selected class='text-capitalize'> $item </option> ";
            } else {
                $option_list .= "<option value='$item' class='text-capitalize'> $item </option> ";
            }
        }

        return "<div class='$input_con_class'>
                    <label for='$input_name' class='form-label'>$input_text</label>
                    <select name='$input_name' class='form-control text-primary bg-secondary border border-primary' id='$input_name'>
                          $option_list
                    </select>
                </div>";
    }
}
