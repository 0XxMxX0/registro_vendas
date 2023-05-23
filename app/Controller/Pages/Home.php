<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Controller\Pages\Page;
use App\Model\SalesDao;
use Exception;

class Home extends Page {

    public static function getHome(){
        
        $obSalesDao = new SalesDao();
        
        if(isset($_GET['delete'])){
            ?>
            <div class="modal bg-dark bg-opacity-50 " tabindex="-1" style="display: block;"> 
                <form method="post" class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-light">Excluir registro de venda</h5>
                    </div>
                    <div class="modal-body">
                        <p>Você tem certeza que deseja excluir esse <b>Registro de venda</b>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary" name="btn-cancel" data-bs-dismiss="modal">Cancelar exclusão</button>
                        <button type="submit" class="btn btn-outline-danger" name="btn-delete">Excluir Registro</button>
                    </div>
                    </div>
                </form>
            </div>
            <?php

            if(isset($_POST['btn-delete'])){
                
                $obSalesDao->delete($_GET['delete']);
                $_SESSION['messagerBar'] = ['alert' => 'success', 'messeger' => 'Venda excluida com sucesso!'];
                header('Location: index.php');

            } else if(isset($_POST['btn-cancel'])){
                header('Location: index.php');
            }
        } 
       
        $table = '';
        foreach ($obSalesDao->read() as $sales) {
            $formaPagamento = ($sales['FormaPagamento'] == 1) ? 'Boleto' : (($sales['FormaPagamento'] == 2) ? 'Cartão de crédito/débito' : 'Não informado');

            $sql = "SELECT sum(f.Valor) as total
                    FROM venda as v
                    INNER JOIN financeiro as f 
                    ON v.Id_Venda = f.Id_Venda
                    WHERE v.Id_Venda = {$sales['Id_Venda']}";

            $select = \App\Model\Connect::getConn()->prepare($sql);
            $select->execute();

            $resultado = ($select->rowCount() > 0) ? $select->fetch(\PDO::FETCH_ASSOC) : array();

            $table .= "<tr>
                        <th scope='row'>{$sales['Id_Venda']}</th>
                        <td>{$sales['NomeCliente']}</td>
                        <td>{$formaPagamento}</td>
                        <td>R$ {$resultado['total']}.00</td>
                        <td>
                            <div class='btn-group' role='group' aria-label='Basic outlined example'>
                                <a href='index.php?type=update&id={$sales['Id_Venda']}' type='button' class='btn btn-outline-primary'>Editar</a>
                                <a href='index.php?delete={$sales['Id_Venda']}' type='button' class='btn btn-outline-danger'>Excluir</a>
                            </div>
                        </td>
                    </tr>";
        }

        $title = 'Histórico de vendas';
        $content = View::render('pages/home', [
            'title' => $title,
            'tableLine' => $table,
        ]);
        return parent::getPage($title, $content);
    }
}
