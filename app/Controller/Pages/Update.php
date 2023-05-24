<?php

namespace App\Controller\Pages;

use \App\Controller\Pages\Page;
use \App\Model\SalesDao;
use \App\Utils\View;
use App\Model;

class Update extends Page {

    public static function getUpdate($id){

        $obSalesDao = new SalesDao();
        
        if($id != ''){
            
            $sql = "SELECT *
                    FROM venda as v
                    INNER JOIN financeiro as f 
                    ON v.Id_Venda = f.Id_Venda
                    WHERE v.Id_Venda = $id";

            $select = \App\Model\Connect::getConn()->prepare($sql);
            $select->execute();

            if($select->rowCount() > 0){
                $resultado = $select->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                return [];
            }
            
            if(count($resultado) > 0){

                $financeiro = '';
                $financeiroN = '';
                $produto  = '';


                foreach($resultado as $key) {
                    $nomeCliente = $key['NomeCliente'];
                    $formaPagamento = ($key['FormaPagamento'] == 1) ? 'Boleto' : (($key['FormaPagamento'] == 2) ? 'Cartão de crédito/débito' : 'Não informado');

                    if($key['Produto'] != null){
                       $produto .= "<div class='form-floating mb-3 mt-2 col-5'>
                                    <input type='text' class='form-control' id='nomeCliente' name='nomeCliente' disabled placeholder='Produto: {$key['Produto']} | Data: {$key['Date']} | Valor: {$key['Valor']}'>
                                    <label for='nomeCliente'>Produto: {$key['Produto']} | Data: {$key['Date']} | Valor: {$key['Valor']}</label>
                                 </div>"; 
                    }
                    

                    if($key['Valor'] != 0){

                        $financeiro .= "<div class='row'>
                                            <div class='input-group mb-3 col'>
                                                <span class='input-group-text'  id='dataParcela'>{$key['NumeroParcela']}</span>
                                                <input type='date' class='form-control' aria-label='Data da parcela' aria-describedby='dataParcela' name='date-{$key['NumeroParcela']}' value='{$key['Date']}'>
                                            </div>
                                            <div class='col-5'>
                                                <input type='text' class='form-control' placeholder='R$ {$key['Valor']}' name='valor-{$key['NumeroParcela']}' aria-label='valorParcela'>
                                            </div>
                                        </div>";
                    } else if ($key['Valor'] == 0){

                        $titleFinanceiroNo = 'Parcelas não ativas';
                        $financeiroN .= "<div class='row'>
                                            <div class='input-group mb-3 col'>
                                                <span class='input-group-text' disabled id='dataParcela'>{$key['NumeroParcela']}</span>
                                                <input type='date' class='form-control' aria-label='Data da parcela' aria-describedby='dataParcela' name='date-{$key['NumeroParcela']}' value='{$key['Date']}'>
                                            </div>
                                            <div class='col-5'>
                                                <input type='text' class='form-control' disable placeholder='R$ {$key['Valor']}' name='valor-{$key['NumeroParcela']}' aria-label='valorParcela'>
                                            </div>
                                        </div>";
                    }

                    
                
                    if(isset($_POST['btn-success'])) {
                        
                        $parcelaLine = $key['NumeroParcela'];

                        $produto = $_POST['produto-'.$parcelaLine];
                        $nomeCliente2 = $_POST['nomeCliente'];
                        $valorParcela = $_POST['valor-'.$parcelaLine];
                        $dataParcela = $_POST['date-'.$parcelaLine];
                        
                        if($produto != '' || $nomeCliente2 != '' || $valorParcela != '' || $dataParcela != ''){

                            if($produto != $key['Produto'] && $produto != ''){
                                $produtoUpdate = $produto;
                            } else {
                                $produtoUpdate = $key['Produto'];
                            }
                            if($nomeCliente2 != $nomeCliente && $nomeCliente2 != ''){
                                $nomeClienteUpdate = $nomeCliente2;
                            } else {
                                $nomeClienteUpdate = $nomeCliente;
                            }
                            if($valorParcela != $key['Valor'] && $valorParcela != ''){
                                $valorParcelaUpdate = $valorParcela;
                            } else {
                                $valorParcelaUpdate = $key['Valor'];
                            }
                            if($dataParcela != $key['Date'] && $dataParcela != ''){
                                $dateParcelaUpdate = $dataParcela;
                            } else {
                                $dateParcelaUpdate = $key['Date'];
                            }

                            $obFinanceiro = new Model\Financeiro($key['Id_Financeiro'],'', $valorParcelaUpdate, $dateParcelaUpdate, $produtoUpdate, $key['NumeroParcela']);
                            $obVenda = new Model\Venda($key['Id_Venda'],$nomeClienteUpdate, $formaPagamento);
                            $obSalesDao->update($obFinanceiro, $obVenda);
                            $_SESSION['messagerBar'] = ['alert' => 'success', 'messeger' => 'Venda editada com sucesso!'];
                            header('Location: index.php');
                        } 
                    }
                }
            }
        }

        $title = 'Atualizar venda';
        $content = View::render('pages/update', [
            'title' => $title,
            'nomeCliente' => $nomeCliente,
            'formaPagamento' => $formaPagamento,
            'financeiro' => $financeiro,
            'financeiroNãofaturado' => $financeiroN,
            'titleFinanceiroNo' => $titleFinanceiroNo != '' ? $titleFinanceiroNo : '',
            'produto' => $produto,
        ]);
        return parent::getPage($title, $content);
    }
}