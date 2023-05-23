<?php

namespace App\Model;

class Venda {
    
    private $id_Venda;
    private $clienteNome;
    private $FormaPagamento;

    public function __construct($id_Venda, $clienteNome, $FormaPagamento){
        $this->id_Venda = $id_Venda;
        $this->clienteNome = $clienteNome;
        $this->FormaPagamento = $FormaPagamento;
    }

    public function getId_Vendas(){
        return $this->id_Venda;
    }

    public function getClienteNome(){
        return $this->clienteNome;
    }

    public function getFormaPagamento(){
        return $this->FormaPagamento;
    }
}