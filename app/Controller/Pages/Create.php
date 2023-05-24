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


            $quantidadeParcelas = $_POST['quantidadeParcelas'];
            $quantidadeProdutos = $_POST['quantidadeProdutos'];

            if($_POST['forma-pagamento'] == 1){
                $quantidadeParcelas = 1;
            } 

            if ($quantidadeParcelas > 0) {
                for ($i = 1; $i <= $quantidadeParcelas; $i++) {
                    if ($_POST['forma-pagamento'] != '') {
                        if ($_POST['forma-pagamento'] == 1) {
                            // ...
                        } else if ($_POST['forma-pagamento'] == 2) {
                            $valorParcela = $_POST['valor'.'-'.$i];
                            $dataParcela = $_POST['data'.'-'.$i];
                            $produto = $_POST['produto'.'-'.$i];
                            
                            if ($i <= $quantidadeProdutos) {
                                if ($produto != '' && $valorParcela != '' && $dataParcela != '') {
                                    $obVenda = new \App\Model\Venda('', $_POST['nomeCliente'], $_POST['forma-pagamento']);
                                    $obFinanceiro = new \App\Model\Financeiro('', $valorParcela, $dataParcela, $produto, $i);
                                    $obSalesDao->create($obVenda, $obFinanceiro, $quantidadeParcelas);
                                    return self::getPaymentSuccess();
                                }
                            } else {
                                $valorParcela = 0;
                                $dataParcela = '0000-00-00';
            
                                if ($produto != '' && $valorParcela == 0 && $dataParcela == '0000-00-00') {
                                    $obVenda = new \App\Model\Venda('', $_POST['nomeCliente'], $_POST['forma-pagamento']);
                                    $obFinanceiro = new \App\Model\Financeiro('', $valorParcela, $dataParcela, $produto, $i);
                                    $obSalesDao->create($obVenda, $obFinanceiro, $quantidadeParcelas);
                                    return self::getPaymentSuccess();
                                }
                            }
                        }
                    } else {
                        $_SESSION['messagerBar'] = ['alert' => 'danger', 'messeger' => 'Formato de pagamento deve ser preenchido!'];
                    }
                }
            } else {
                $_SESSION['messagerBar'] = ['alert' => 'danger', 'messeger' => 'Dados incompletos'];
            }
        } 

        $title = 'Registrar venda';
        $content = View::render('pages/create', compact('title'));
        return parent::getPage($title, $content);
    }
}
