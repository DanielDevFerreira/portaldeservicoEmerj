<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Andamento{



  /**
   * Identificador único da andamento
   * @var integer
   */
  public $andamento_id;

  /**
   * Data de publicação da andamento
   * @var string
   */
  public $data_add;

    /**
   * Data de alteração da andamento
   * @var string
   */
  public $data_up;

  /**
   * Descrição da andamento (pode conter html)
   * @var integer
   */
  public $id_chamado;

  /**
   * Descrição da andament (pode conter html)
   * @var integer
   */
  public $id_atendimento;

  /**
   * Descrição da andament (pode conter html)
   * @var integer
   */
  public $id_itemdeconf;

 /**
 * Data do atendimento do andament
 * @var string
 */
  public $dt_atendimento;

  /**
   * Status do andament
   * @var integer
   */
  public $id_status;


  /**
   * Método responsável por cadastrar um novo andament no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR O IC NO BANCO
    $obDatabase = new Database('tb_andamento');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->andament_id = $obDatabase->insert([
                                      'id_chamado' => $this->id_chamado,
                                      'id_atendimento' => $this->id_atendimento,
                                      'id_itemdeconf' => $this->id_itemdeconf
                                    ]);

    //RETORNAR SUCESSO
    return true;
  }

  /**
   * Método responsável por atualizar o IC no banco
   * @return boolean
   */
  public function atualizar(){

    //DEFINIR A DATA DE ATUALIZACAO date('Y-m-d H:i:s')
    date_default_timezone_set('America/Sao_Paulo');
    $this->data_up = date('Y-m-d H:i:s');

    return (new Database('tb_andamento'))->update('andamento_id = '.$this->andamento_id,[
                                                'id_chamado' => $this->id_chamado,
                                                'id_atendimento' => $this->id_atendimento,
                                                'id_itemdeconf' => $this->id_itemdeconf
                                                  ]);
   }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_andamento'))->delete('andamento_id = '.$this->andamento_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getAndamentos($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_andamento'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Andamento
   */
  public static function getAndamentoPorId($andamento_id){
    return self::getAndamentos('andamento_id = '.$andamento_id)->fetchObject(self::class);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getAndamentosPorChamado($id_chamado, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_andamento'))->select('id_chamado = '.$id_chamado,$order,$limit,$fields);
  }




  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeAndamentos($where = null){
    return (new Database('tb_andamento'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
