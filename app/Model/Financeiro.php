<?php

namespace App\Model;

class Financeiro {

    private $id_Financeiro;
    private $id_Venda;
    private $valor;
    private $data;
    private $produto;
    private $numeroParcela;

    public function __construct($id_Financeiro, $id_Venda, $valor, $data, $produto, $numeroParcela){
        
        $this->id_Financeiro = $id_Financeiro;
        $this->id_Venda = $id_Venda;
        $this->valor = $valor;
        $this->data = $data;
        $this->produto = $produto;
        $this->numeroParcela = $numeroParcela;
    }

    public function getid_Financeiro(){
        return $this->id_Financeiro;
    }
    
    public function getid_Venda(){
        return $this->id_Venda;
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