<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Controller\Pages\Page;
use App\Model\SalesDao;

class Create extends Page {

    
    public static function getPaymentSuccess (){
        $title = 'Venda registrada';
        $content = View::render('pages/paymentSuccess', compact('title'));
        return parent::getPage($title, $content);
    }

    public static function getCreate(){
        
        if(isset($_POST['btn-success'])){

            $obSalesDao = new SalesDao();
            $count = 1;
            $quantidadeParcelas = $_POST['quantidadeParcelas'];
            $quantidadeProdutos = $_POST['quantidadeProdutos'];

            if ($_POST['forma-pagamento'] != '') {
                
                $obVenda = new \App\Model\Venda('', $_POST['nomeCliente'], $_POST['forma-pagamento']);
                $id_venda = $obSalesDao->createClient($obVenda);
               
                $valorfor = $quantidadeProdutos;
                if($quantidadeParcelas > $quantidadeProdutos){
                    $valorfor = $quantidadeParcelas;
                } 

                if ($valorfor > 0) {

                    for ($i = 0; $i < $valorfor; $i++) {

                        if ($_POST['forma-pagamento'] == 1) {
                            
                            $valorParcela = $_POST['valor'.'-'.$i];
                            $produto = $_POST['produto'.'-'.$i];

                            if ($valorParcela != '' && $produto != '') {
                                
                                $obFinanceiro = new \App\Model\Financeiro('',$id_venda, $valorParcela, date('Y-m-d'), $produto, $count);
                                $obSalesDao->createPayment($obFinanceiro, $count);
                                
                                return self::getPaymentSuccess();
                            }

                        } else if ($_POST['forma-pagamento'] == 2) {

                            $produto = $_POST['produto'.'-'.$i];

                            if($count <= $quantidadeParcelas) {
                                $valorParcela = $_POST['valor'.'-'.$i];
                                $dataParcela = $_POST['data'.'-'.$i];
                            } else {
                                $valorParcela = '0';
                                $dataParcela = date('Y-m-d');
                            }
                            

                            if ($valorParcela != '' && $dataParcela != '') {
                               
                                $obFinanceiro = new \App\Model\Financeiro('',$id_venda, $valorParcela, $dataParcela, $produto, $count);
                                $obSalesDao->createPayment($obFinanceiro, $count); 

                                return self::getPaymentSuccess();
                            }
                        }
                        $count++;
                    } 
                }
            } 
        } 
        $title = 'Registrar venda';
        $content = View::render('pages/create', compact('title'));
        return parent::getPage($title, $content);
    }
}
