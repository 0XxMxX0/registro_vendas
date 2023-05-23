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
            $produtos = $_POST['quantidadeParcelas'];
            
            if($_POST['forma-pagamento'] == 1){
                $produtos = 1;
            } 

            if($produtos > 0){

                $infoVenda = [];

                for($i = 0; $i < $produtos; $i++){
                    if($_POST['forma-pagamento'] != ''){

                        if($_POST['forma-pagamento'] == 1){
                            $i++;

                            if($_POST['boletoTotal'] != '' && $_POST['produto'.'-'.$i] != ''){
                                $obVenda = new \App\Model\Venda('',$_POST['nomeCliente'],$_POST['forma-pagamento']);
                                $obFinanceiro = new \App\Model\Financeiro('', $_POST['boletoTotal'], date('Y-m-d'),$_POST['produto'.'-'.$i],$i);
                                $obSalesDao->create($obVenda, $obFinanceiro, $produtos);

                                return self::getPaymentSuccess();
                            } 

                        } else if($_POST['forma-pagamento'] == 2){

                            if($_POST['produto'.'-'.$i] != '' && $_POST['valor'.'-'.$i] != '' && $_POST['data'.'-'.$i] != ''){

                                $obVenda = new \App\Model\Venda('',$_POST['nomeCliente'],$_POST['forma-pagamento']);
                                $obFinanceiro = new \App\Model\Financeiro('',$_POST['valor'.'-'.$i],$_POST['data'.'-'.$i],$_POST['produto'.'-'.$i],$i);
                                $obSalesDao->create($obVenda, $obFinanceiro, $produtos);
                                
                                return self::getPaymentSuccess();

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
