<?php

namespace Utils;

class InputField
{
    public function __construct()
    {
        $this->input_class = "form-control text-primary bg-secondary border border-primary ";
        $this->input_label_class = "$this->input_label_class";
    }
    private function setConClass($single_input, $additional_con_class)
    {
        $input_con_class = "";
        $single_input === false ? $input_con_class .= "col-md-6 " : $input_con_class .= "one-input ";
        if (!is_null($additional_con_class)) $input_con_class .= $additional_con_class;
        return $input_con_class;
    }

    private function setRequired($required)
    {
        $required_input = "";
        if ($required === true) $required_input = "required";
        return $required_input;
    }

    private function setDefaultValue($default_value)
    {
        $new_default = "";
        if (!is_null($default_value)) {
            $new_default = $default_value;
        }
        return $new_default;
    }

    private function setInputClass($additional_class)
    {
        $input_class = $this->input_class;
        if (!is_null($additional_class)) $input_class .= $additional_class;
        return $input_class;
    }

    private function makeOptionList($item_list, $default)
    {
        $option_list = "";
        foreach ($item_list as $item) {
            if ($item === $default) {
                // echo "Default - " . $default . "Item" . $item;
                $option_list .= "<option value='$item' selected class='text-capitalize selected-item'> $item </option> ";
            } else {
                $option_list .= "<option value='$item' class='text-capitalize'> $item </option> ";
            }
        }

        return $option_list;
    }
    private function makePhoneOptionList($countries_code, $default)
    {
        // $phone_code = [];
        // foreach ($countries_code as $code => $code_country) {
        //     array_push($phone_code, "+$code");
        // }
        $option_list = "";
        foreach ($countries_code as $code => $code_country) {
            if ($code_country === $default) {
                // echo "Default - " . $default . "Item" . $item;
                $option_list .= "<option value='+$code' selected class='text-capitalize selected-item'> $code_country </option> ";
            } else {
                $option_list .= "<option value='+$code' class='text-capitalize'> $code_country </option> ";
            }
        }

        return $option_list;
    }
    public function inputText($input_name, $input_text, $single_input, $input_type, $required = false, $default_value = null, $additional_con_class = null, $additional_class = null)
    {
        $translated_text = __($input_text);
        $input_con_class = $this->setConClass($single_input, $additional_con_class);
        $required_input = $this->setRequired($required);
        $new_default = $this->setDefaultValue($default_value);
        $input_class = $this->setInputClass($additional_class);
        return "<div class='$input_con_class'>
                    <label for='$input_name' class='$this->input_label_class'>$translated_text</label>
                    <input $required_input type='$input_type' value='$new_default' name='$input_name' class='$input_class' id='$input_name'>
                </div>";
    }

    public function inputFile($input_name, $input_text, $single_input, $required = false, $default_value = null, $additional_con_class = null, $additional_class = null)
    {
        $translated_text = __($input_text);
        $input_con_class = $this->setConClass($single_input, $additional_con_class);
        $required_input = $this->setRequired($required);
        $new_default = $this->setDefaultValue($default_value);
        $input_class = $this->setInputClass($additional_class);
        return "<div class='$input_con_class'>
                    <label for='$input_name' class='$this->input_label_class'>$translated_text</label>
                    <input $required_input type='file' value='$new_default' name='$input_name' class='$input_class' id='$input_name'>
                </div>";
    }
    public function inputTextarea($input_name, $input_text, $single_input, $required = false, $default_value = null, $row = 2, $additional_con_class = null, $additional_class = null)
    {
        $translated_text = __($input_text);
        $input_con_class = $this->setConClass($single_input, $additional_con_class);
        $required_input = $this->setRequired($required);
        $new_default = $this->setDefaultValue($default_value);
        $input_class = $this->setInputClass($additional_class);
        return "<div class='$input_con_class'>
                    <label for='$input_name' class='$this->input_label_class'>$translated_text</label>
                    <textarea $required_input rows='$row' name='$input_name' class='$input_class' id='$input_name'> $new_default </textarea>
                </div>";
    }

    public function inputSelect($input_name, $input_text, $single_input, $default_value = null, $item_list, $additional_con_class = null, $additional_class = null)
    {
        $translated_text = __($input_text);
        $input_con_class = $this->setConClass($single_input, $additional_con_class);
        $new_default = $this->setDefaultValue($default_value);
        $input_class = $this->setInputClass($additional_class);

        // make option list
        $option_list = $this->makeOptionList($item_list, $new_default);

        return "<div class='$input_con_class' >
                    <label for='$input_name' class='$this->input_label_class'>$translated_text</label>
                    <select name='$input_name' class='$input_class' id='$input_name'>
                          $option_list
                    </select>
                </div>";
    }
    public function inputPhone($input_name, $input_text, $single_input, $required = false, $item_list, $default_value = null, $additional_con_class = null, $additional_class = null)
    {
        $translated_text = __($input_text);
        $input_con_class = $this->setConClass($single_input, $additional_con_class);
        $required_input = $this->setRequired($required);
        $input_class = $this->setInputClass($additional_class);
        $new_default = $this->setDefaultValue($default_value);
        $split_phone = explode("_", $default_value);
        $default_code = "";
        if (!empty($split_phone[0])) $default_code = $split_phone[0];
        // echo $default_code;
        $default_phone = "";
        if (!empty($split_phone[1])) $default_phone = $split_phone[1];

        // var_dump(array("code" => $default_code, "new default"=> $new_default, "default phone"=> $default_phone));
        // echo "<pre>";
        // print_r(array("code" => "br$default_code"));
        // echo "</pre>";
        $option_list = $this->makePhoneOptionList($item_list, $default_code);

        return "<div class='$input_con_class'>
                    <label for='$input_name' class='$this->input_label_class'>$translated_text</label>
                    <div class='phone-input-group d-flex'>
                        <select name='$input_name' class='option-list $input_class' id='$input_name-select'>
                          $option_list
                        </select>
                        <input $required_input type='text' value='$default_phone' name='$input_name' class='raw-number $input_class' id='$input_name-raw-input'>
                    </div>
                    <input type='hidden' value='$new_default' name='$input_name' id='$input_name'>
                </div>";
    }
    public function inputHidden($input_name, $default_value = null)
    {
        $new_default = $this->setDefaultValue($default_value);
        return "<input type='hidden' value='$new_default' name='$input_name' id='$input_name'>";
    }

    public function inputCheckbox($input_name, $input_text, $default_value = null)
    {
        $new_default = '';
        if($default_value === true) {
            $new_default = 'checked';
        }
        return "<div class='w-fit'>
                        <div class='form-check'>
                            <input class='form-check-input' type='checkbox' value='' name='$input_name' id='$input_name' $new_default >
                            <label class='form-check-label' for='$input_name'>$input_text</label>
                        </div>
                </div>
                ";
    }
}


