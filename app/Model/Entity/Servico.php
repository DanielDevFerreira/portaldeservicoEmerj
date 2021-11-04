<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Servico{



  /**
   * Identificador único do serviço
   * @var integer
   */
  public $servico_id;

  /**
   * Título do serviço (pode conter html)
   * @var string
   */
  public $servico_nm;

  /**
   * Descrição do serviço (pode conter html)
   * @var string
   */
  public $servico_des;

  /**
   * Data de publicação do serviço
   * @var string
   */
  public $data_add;

    /**
   * Data de alteração do serviço
   * @var string
   */
  public $data_up;

    /**
   * Define se o serviço esta ativo
   * @var string(s/n)
   */
  public $ativo_fl;

  /**
 * Tipo de serviço relacionado
 * @var integer
 */
public $id_tipodeservico;

  /**
   * Método responsável por cadastrar um nova IC no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR O IC NO BANCO
    $obDatabase = new Database('tb_servico');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->servico_id = $obDatabase->insert([
                                      'servico_nm' => $this->servico_nm,
                                      'servico_des' => $this->servico_des,
                                      'ativo_fl'     => $this->ativo_fl,
                                      'id_tipodeservico'  => $this->id_tipodeservico
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

    return (new Database('tb_servico'))->update('servico_id = '.$this->servico_id,[
                                                                'servico_nm'    => $this->servico_nm,
                                                                'servico_des' => $this->servico_des,
                                                                'ativo_fl'    => $this->ativo_fl,
                                                                'data_up'  => $this->data_up,
                                                                'id_tipodeservico' => $this->id_tipodeservico
                                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_servico'))->delete('servico_id = '.$this->servico_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getServicos($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_servico'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getServicoDeChamado($where = null, $order = null, $from = null, $limit = null, $fields = '*'){
    return (new Database('tb_servico'))->select2($where,$order,$from,$limit,$fields);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return Servico
   */
  public static function getServicoDeChamado2($where = null, $order = null, $from = null, $limit = null, $fields = '*'){
    return (new Database('tb_servico'))->select2($where,$order,$from,$limit,$fields)->fetchObject(self::class);
  }


  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Servico
   */
  public static function getServicoPorId($servico_id = null){
    return self::getServicos('servico_id = '.$servico_id)->fetchObject(self::class);
  }


  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeServicos($where = null){
    return (new Database('tb_servico'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
