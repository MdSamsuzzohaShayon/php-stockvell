<?php 
namespace Utils;

class HelperFunctions {
    public function findByWordFromArrayOfSentence($search_word, $sentence_of_arr){
        $sentence = null;
        try {
            //code...
            for ($i=0; $i < count($sentence_of_arr); $i++) { 
                // echo $search_word;
                // echo $sentence_of_arr[$i];
                if(str_contains($sentence_of_arr[$i], $search_word)){
                    $sentence = $sentence_of_arr[$i];
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $sentence;
    }
}
