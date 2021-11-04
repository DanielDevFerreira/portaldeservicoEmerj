<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Tipodeservico{

  /**
   * Identificador único da vaga
   * @var integer
   */
  public $tipodeservico_id;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $tipodeservico_nm;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $tipodeservico_des;

  /**
   * Data de publicação da vaga
   * @var string
   */
  public $data_add;

    /**
   * Data de alteração da vaga
   * @var string
   */
  public $data_up;

    /**
   * Define se a vaga ativa
   * @var string(s/n)
   */
  public $ativo_fl;

  /**
   * Método responsável por cadastrar um nova IC no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR O IC NO BANCO
    $obDatabase = new Database('tb_tipodeservico');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->tipodeservico_id = $obDatabase->insert([
                                      'tipodeservico_nm' => $this->tipodeservico_nm,
                                      'tipodeservico_des' => $this->tipodeservico_des
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

    return (new Database('tb_tipodeservico'))->update('tipodeservico_id = '.$this->tipodeservico_id,[
                                                                'tipodeservico_nm'    => $this->tipodeservico_nm,
                                                                'tipodeservico_des' => $this->tipodeservico_des,
                                                                'ativo_fl'    => $this->ativo_fl,
                                                                'data_up'  => $this->data_up
                                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_tipodeservico'))->delete('tipodeservico_id = '.$this->tipodeservico_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getTipodeservicos($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_tipodeservico'))->select($where,$order,$limit,$fields);
  }


  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return Servico
   */
  public static function getTipodeServicoDeChamado2($where = null, $order = null, $from = null, $limit = null, $fields = '*'){
    return (new Database('tb_tipodeservico'))->select2($where,$order,$from,$limit,$fields)->fetchObject(self::class);
  }


  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Tipodeservico
   */
  public static function getTipodeservicoPorId($tipodeservico_id){
    return self::getTipodeservicos('tipodeservico_id = '.$tipodeservico_id)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param string $nome
   * @return Usuario
   */
  public static function getTipodeservicoPorNome($nome){
      return (new Database('tb_tipodeservico'))->select('tipodeservico_nm = "'.$nome.'"')->fetchObject(self::class);
  }



  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeTipodeservicos($where = null){
    return (new Database('tb_tipodeservico'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
