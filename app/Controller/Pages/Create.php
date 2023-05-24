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
            $error = [];
            $quantidadeParcelas = $_POST['quantidadeParcelas'];
            $quantidadeProdutos = $_POST['quantidadeProdutos'];

            if ($_POST['forma-pagamento'] != '') {
                
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
                                $obFinanceiro[] = new \App\Model\Financeiro('','', $valorParcela, date('Y-m-d'), $produto, $count);
                            } else {
                                $error[] = "Produto {$produto} | Valor {$valorParcela} | Parcela {$count}";
                            }

                            if(count($error) == 0 AND $count >= $valorfor){
                                
                                $obVenda = new \App\Model\Venda('', $_POST['nomeCliente'], $_POST['forma-pagamento']);
                                $id_venda = $obSalesDao->createClient($obVenda);

                                $a = 0;
                                while($a < count($obFinanceiro)){
                                    $obSalesDao->createPayment($obFinanceiro[$a], $count, $id_venda);
                                    $a++;
                                }
                                
                                $_SESSION['messagerBar'] = ['alert' => 'success', 'messeger' => 'Venda registrada com sucesso!'];
                                return self::getPaymentSuccess();
                                
                            } else if($error[$i]){
                                $_SESSION['messagerBar'] = ['alert' => 'danger', 'messeger' => "a Parcela ($error[$i]) não foi registrado"];
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

                                $obFinanceiro[] = new \App\Model\Financeiro('','', $valorParcela, $dataParcela, $produto, $count);
                            
                            } else {
                                $error[] = 'error';
                            }

                            if(count($error) == 0 AND $count >= $valorfor){
                                
                                $obVenda = new \App\Model\Venda('', $_POST['nomeCliente'], $_POST['forma-pagamento']);
                                $id_venda = $obSalesDao->createClient($obVenda);

                                $a = 0;
                                while($a < count($obFinanceiro)){
                                    $obSalesDao->createPayment($obFinanceiro[$a], $count, $id_venda);
                                    $a++;
                                }
                                
                                $_SESSION['messagerBar'] = ['alert' => 'success', 'messeger' => 'Venda registrada com sucesso!'];
                                return self::getPaymentSuccess();
                                
                            } else if($error[$i]){
                                $_SESSION['messagerBar'] = ['alert' => 'danger', 'messeger' => "a Parcela ($error[$i]) não foi registrado"];
                            }
                        }
                        $count++;
                    } 
                } else {
                    $_SESSION['messagerBar'] = ['alert' => 'danger', 'messeger' => 'Produto ou parcelas não informadas!'];
                    header('Location: index.php');
                }
            } 
        } 
        $title = 'Registrar venda';
        $content = View::render('pages/create', compact('title'));
        return parent::getPage($title, $content);
    }
}
