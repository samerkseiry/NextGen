<?php
namespace validation;
require_once 'ValidInterface.php';


class MaxTweet implements ValidInterface {

    private $name;
    private $value;

    public function __construct($name , $value) {
         $this->name = $name;
         $this->value = $value;

    }
    public function validate() {
        if (strlen($this->value) > 210) {
            return "$this->name must be not more than 210";
        }

        return '';
    }
}