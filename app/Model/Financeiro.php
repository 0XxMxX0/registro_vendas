<?php

namespace App\Model;

class Financeiro {

    private $id_Financeiro;
    private $valor;
    private $data;
    private $produto;
    private $numeroParcela;

    public function __construct($id_Financeiro,$valor, $data, $produto, $numeroParcela){
        
        $this->id_Financeiro = $id_Financeiro;
        $this->valor = $valor;
        $this->data = $data;
        $this->produto = $produto;
        $this->numeroParcela = $numeroParcela;
    }

    public function getid_Financeiro(){
        return $this->id_Financeiro;
    }

    public function getNumeroParcela(){
        return $this->numeroParcela;
    }

    public function getProduto(){
        return $this->produto;
    }

    public function getValor(){
        return $this->valor;
    }

    public function getData(){
        return $this->data;
    }
}