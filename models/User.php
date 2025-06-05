<?php

namespace Models;

class User{
    public $id;
    public $name;
    public $country;
    public $created_at;


    public function __construct($id,$name,$country,$created_at=null){
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->created_at = $created_at;


    }

}

?>