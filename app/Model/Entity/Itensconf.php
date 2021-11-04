<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Itensconf{



  /**
   * Identificador único da vaga
   * @var integer
   */
  public $itemdeconfiguracao_id;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $itemdeconfiguracao_nm;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $itemdeconfiguracao_des;

  /**
   * Número do patrinônio
   * @var integer
   */
  public $patrimonio_nr;

  /**
   * Número do patrinônio
   * @var integer
   */
  public $dgtec_nr;

  /**
   * Número do patrinônio
   * @var string
   */
  public $memoria;

  /**
   * Número do patrinônio
   * @var string
   */
  public $hardisc;

  /**
   * Número do patrinônio
   * @var string
   */
  public $monitor_nm;

  /**
   * Número do patrinônio
   * @var string
   */
  public $estabilizador;

  /**
   * Número do patrinônio
   * @var string
   */
  public $sistema_ope;

  /**
   * Número do patrinônio
   * @var string
   */
  public $rede_nm;

  /**
   * Número do patrinônio
   * @var string
   */
  public $office;

  /**
   * Número do patrinônio
   * @var string
   */
  public $obs;

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
 * Define se a vaga ativa
 * @var integer
 */
public $id_tipodeic;

/**
* Define se a vaga ativa
* @var integer
*/
public $id_localizacao;

/**
* Define se a vaga ativa
* @var integer
*/
public $id_usuario;

/**
* Define se a vaga ativa
* @var integer
*/
public $id_departamento;

/**
* Define se a vaga ativa
* @var integer
*/
public $cod_itemdeconfiguracao_dep;



  /**
   * Método responsável por cadastrar um nova IC no banco
   * @return boolean
   */
  public function cadastrar(){
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR O IC NO BANCO
    $obDatabase = new Database('tb_itemdeconfiguracao');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;
    $this->itemdeconfiguracao_id = $obDatabase->insert([
                                      'itemdeconfiguracao_nm' => $this->itemdeconfiguracao_nm,
                                      'itemdeconfiguracao_des' => $this->itemdeconfiguracao_des,
                                      'itemdeconfiguracao_des' => $this->itemdeconfiguracao_des,
                                      'patrimonio_nr' => strlen($this->patrimonio_nr) > 1 ? $this->patrimonio_nr : NULL,
                                      'dgtec_nr' => strlen($this->dgtec_nr) > 1 ? $this->dgtec_nr : NULL,
                                      'memoria' => $this->memoria,
                                      'hardisc' => $this->hardisc,
                                      'monitor_nm' => $this->monitor_nm,
                                      'estabilizador' => $this->estabilizador,
                                      'sistema_ope' => $this->sistema_ope,
                                      'rede_nm' => $this->rede_nm,
                                      'office' => $this->office,
                                      'obs' => $this->obs,
                                      'id_tipodeic'     => $this->id_tipodeic,
                                      'id_localizacao'     => $this->id_localizacao,
                                      'id_usuario'     => $this->id_usuario,
                                      'id_departamento'     => $this->id_departamento,
                                      'cod_itemdeconfiguracao_dep' => $this->cod_itemdeconfiguracao_dep
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

    return (new Database('tb_itemdeconfiguracao'))->update('itemdeconfiguracao_id = '.$this->itemdeconfiguracao_id,[
                                                                'itemdeconfiguracao_nm'    => $this->itemdeconfiguracao_nm,
                                                                'itemdeconfiguracao_des' => $this->itemdeconfiguracao_des,
                                                                'patrimonio_nr' => strlen($this->patrimonio_nr) > 1 ? $this->patrimonio_nr : NULL,
                                                                'dgtec_nr' => strlen($this->dgtec_nr) > 1 ? $this->dgtec_nr : NULL,
                                                                'memoria' => $this->memoria,
                                                                'hardisc' => $this->hardisc,
                                                                'monitor_nm' => $this->monitor_nm,
                                                                'estabilizador' => $this->estabilizador,
                                                                'sistema_ope' => $this->sistema_ope,
                                                                'rede_nm' => $this->rede_nm,
                                                                'office' => $this->office,
                                                                'obs' => $this->obs,
                                                                'ativo_fl'     => $this->ativo_fl,
                                                                'id_tipodeic'     => $this->id_tipodeic,
                                                                'id_localizacao'     => $this->id_localizacao,
                                                                'id_usuario'     => $this->id_usuario,
                                                                'id_departamento'     => $this->id_departamento,
                                                                'cod_itemdeconfiguracao_dep' => $this->cod_itemdeconfiguracao_dep
                                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_itemdeconfiguracao'))->delete('itemdeconfiguracao_id = '.$this->itemdeconfiguracao_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getItensconfs($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_itemdeconfiguracao'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $fields
   * @param  string $tables
   * @return PDOStatement
   */
  public static function getItensconfsDistinct($where = null, $order = null, $limit = null, $fields = '*', $tabelas = null){
    return (new Database('tb_itemdeconfiguracao'))->selectPersonalizado($where,$order,$limit,$fields,$tabelas);
  }


  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getItemdeconfiguracaoDeChamado2($where = null, $order = null, $from = null, $limit = null, $fields = '*'){
    return (new Database('tb_itemdeconfiguracao'))->select2($where,$order,$from,$limit,$fields);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getItemdeconfiguracaoDeAtendimento($where = null, $order = null, $from = null, $limit = null, $fields = '*'){
    return (new Database('tb_itemdeconfiguracao'))->select2($where,$order,$from,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Itensconf
   */
  public static function getItensconfPorId($id){
    return self::getItensconfs('itemdeconfiguracao_id = '.$id)->fetchObject(self::class);
  }

  /**
   * Método responsável por buscar um IC com base em seu número de patrimônio
   * @param integer $id
   * @return Itensconf
   */
  public static function getItensconfPorPatrimonio($patrimonio){
    return self::getItensconfs('patrimonio_nr = '.$patrimonio)->fetchObject(self::class);
  }

  /**
   * Método responsável por buscar um IC com base em seu número de patrimônio
   * @param integer $id
   * @return Itensconf
   */
  public static function getItensconfPorNrDGTEC($ndgtec){
    return self::getItensconfs('dgtec_nr = '.$ndgtec)->fetchObject(self::class);
  }



  /**
   * Método responsável por buscar IC's com base em seu Serviço
   * @param integer $id_serviço
   * @return Itensconf
   */
  public static function getItensconfPorIdServico($id_servico){
    //SELECT distinct itemdeconfiguracao_id, itemdeconfiguracao_nm FROM tb_itemdeconfiguracao, tb_atendimento, tb_servico where tb_servico.servico_id = tb_atendimento.id_servico AND tb_servico.servico_id=4 order by itemdeconfiguracao_nm;
    return self::getItensconfsDistinct('tb_servico.servico_id = tb_atendimento.id_servico AND tb_servico.servico_id = '.$id_servico,'itemdeconfiguracao_nm',null,'DISTINCT itemdeconfiguracao_id, itemdeconfiguracao_nm','tb_itemdeconfiguracao, tb_atendimento, tb_servico')->fetchObject(self::class);
  }

  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeItensconfs($where = null){
    return (new Database('tb_itemdeconfiguracao'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
