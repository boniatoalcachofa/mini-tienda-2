<?php

class Producto{
    public $id;
    public $name;
    public $amount;
    public $categ;
    public $price;
    
    public function __construct($id, $name,$amount,$categ,$price) {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
        $this->categ = $categ;
        $this->price = $price;
    }
    function setAmount($amount){
        $this->amount = $amount;
    }
    function getAmount() {
        return $this->amount;
    }
    function setId($id){
        $this->id = $id;
    }
    function getId() {
        return $this->id;
    }
    function setName($name){
        $this->id = $name;
    }
    function getName() {
        return $this->name;
    }
    function setPrice($price){
        $this->price = $price;
    }
    function getPrice() {
        return $this->price;
    }
    //Shows the product's info
    function showInfo() {
        return $this->name.": Cantidad ".$this->amount." Precio: ".($this->price*$this->amount);
    }
}
?>