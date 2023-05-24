<?php

namespace App\Model;
use \App\Model\Connect;

class SalesDao{
    

    public function createClient(Venda $venda){
        
        $sql = "INSERT INTO venda(NomeCLiente,FormaPagamento) VALUES (?,?)";
        
        $insert = Connect::getConn()->prepare($sql);
        $insert->bindValue(1, $venda->getClienteNome());
        $insert->bindValue(2, $venda->getFormaPagamento());
        $status = $insert->execute() ? true : false;
        
        if($status){
            $lastId = Connect::getConn()->lastInsertId();
            return $lastId;
        }
    }    
    
    public function createPayment(Financeiro $financeiro, $numeroParcela){

        
        if($financeiro != ''){
            
            if($financeiro->getid_Venda() != '' && $financeiro->getData() != '' && $financeiro->getValor() != ''){
                
                $sql = "INSERT INTO financeiro (Id_Venda, Valor, Date, Produto, NumeroParcela) VALUES (?,?,?,?,?)";

                $insert = Connect::getConn()->prepare($sql);
                $insert->bindValue(1, $financeiro->getid_Venda());
                $insert->bindValue(2, $financeiro->getValor());
                $insert->bindValue(3, $financeiro->getData());
                $insert->bindValue(4, $financeiro->getProduto());
                $insert->bindValue(5, $numeroParcela);
                $status = $insert->execute() ? true : false;
                return $status;
            }
        }
        return false;
    }


    public function read(){

        $sql = "SELECT * 
        FROM venda";

        $select = Connect::getConn()->prepare($sql);
        $select->execute();

        if($select->rowCount() > 0){
            $resultado = $select->fetchAll(\PDO::FETCH_ASSOC);
            return $resultado;  
        } else {
            return [];
        }
    }

    public function update(Financeiro $financeiro, Venda $venda){

        $sql = "UPDATE financeiro SET Valor = ?, Date = ?, Produto = ? WHERE Id_Financeiro = ?";
        var_dump($financeiro);
        var_dump($venda);
        $update = Connect::getConn()->prepare($sql);
        $update->bindValue(1, $financeiro->getValor());
        $update->bindValue(2, $financeiro->getData());
        $update->bindValue(3, $financeiro->getProduto());
        $update->bindValue(4, $financeiro->getid_Financeiro());
        $update->execute();
        
        $sql2 = "UPDATE venda SET NomeCliente = ? WHERE Id_Venda = ?";
        
        $update = Connect::getConn()->prepare($sql2);
        $update->bindValue(1, $venda->getClienteNome());
        $update->bindValue(2, $venda->getId_Vendas());
        $update->execute();

    }
    
    public function delete($id_Venda){

        $sql = "DELETE FROM venda WHERE Id_Venda = ?";
        $sql2 = "DELETE FROM financeiro WHERE Id_Venda = ?";

        $delete = Connect::getConn()->prepare($sql);
        $delete->bindValue(1, $id_Venda);
        $delete->execute();
        
        $delete = Connect::getConn()->prepare($sql2);
        $delete->bindValue(1, $id_Venda);
        $delete->execute();
    }
}