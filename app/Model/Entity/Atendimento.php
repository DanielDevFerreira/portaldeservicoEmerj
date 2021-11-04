<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Atendimento{



  /**
   * Identificador único da atendimento
   * @var integer
   */
  public $atendimento_id;

  /**
   * Serviço relacionado
   * @var integer
   */
  public $id_servico;

  /**
   * Item de configuração relacionado
   * @var integer
   */
  public $id_tipodeic;

  /**
   * Departamento relacionado
   * @var integer
   */
  public $id_departamento;

  /**
   * Departamento relacionado
   * @var integer
   */
  public $sla;

  /**
   * Data de publicação da atendimento
   * @var string
   */
  public $data_add;

    /**
   * Data de alteração da atendimento
   * @var string
   */
  public $data_up;

    /**
   * Define se o atendimento está ativo
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
    $obDatabase = new Database('tb_atendimento');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->atendimento_id = $obDatabase->insert([
                                      'id_servico' => $this->id_servico,
                                      'id_tipodeic' => $this->id_tipodeic,
                                      'id_departamento' => $this->id_departamento,
                                      'sla' => $this->sla
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

    return (new Database('tb_atendimento'))->update('atendimento_id = '.$this->atendimento_id,[
                                                                  'id_servico' => $this->id_servico,
                                                                  'id_tipodeic' => $this->id_tipodeic,
                                                                  'id_departamento' => $this->id_departamento,
                                                                  'sla' => $this->sla,
                                                                  'ativo_fl' => $this->ativo_fl
                                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_atendimento'))->delete('atendimento_id = '.$this->atendimento_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getAtendimentos($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_atendimento'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getAtendimentos2($where = null, $order = null, $from = null, $limit = null, $fields = '*'){
      return (new Database('tb_atendimento'))->select2($where,$order,$from,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Atendimento
   */
  public static function getAtendimentoPorId($atendimento_id){
    return self::getAtendimentos('atendimento_id = '.$atendimento_id)->fetchObject(self::class);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Atendimento
   */
  public static function getAtendimentoPorItens($id_servico,$id_departamento,$id_tipodeic){
    return self::getAtendimentos('id_servico = '.$id_servico.' AND id_departamento = '.$id_departamento.' AND id_tipodeic = '.$id_tipodeic)->fetchObject(self::class);
  }

  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeAtendimentos($where = null){
    return (new Database('tb_atendimento'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
