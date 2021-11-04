<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Tipodeic{

  /**
   * Identificador único da vaga
   * @var integer
   */
  public $tipodeic_id;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $tipodeic_nm;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $tipodeic_des;

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
   * Define se o Tipode IC está ativa
   * @var string(s/n)
   */
  public $ativo_fl;

  /**
   * Identificador único da vaga
   * @var integer
   */
  public $id_categoria_ic;


  /**
   * Método responsável por cadastrar um nova IC no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR O IC NO BANCO
    $obDatabase = new Database('tb_tipodeic');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->tipodeic_id = $obDatabase->insert([
                                      'tipodeic_nm' => $this->tipodeic_nm,
                                      'tipodeic_des' => $this->tipodeic_des,
                                      'ativo_fl'     => $this->ativo_fl,
                                      'id_categoria_ic' => $this->id_categoria_ic
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

    return (new Database('tb_tipodeic'))->update('tipodeic_id = '.$this->tipodeic_id,[
                                                                'tipodeic_nm'    => $this->tipodeic_nm,
                                                                'tipodeic_des' => $this->tipodeic_des,
                                                                'ativo_fl'    => $this->ativo_fl,
                                                                'data_up'  => $this->data_up,
                                                                'id_categoria_ic' => $this->id_categoria_ic
                                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_tipodeic'))->delete('tipodeic_id = '.$this->tipodeic_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getTipodeics($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_tipodeic'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Tipodeic
   */
  public static function getTipodeicPorId($tipodeic_id){
    return self::getTipodeics('tipodeic_id = '.$tipodeic_id)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param string $nome
   * @return Usuario
   */
  public static function getTipodeicPorNome($nome){
      return (new Database('tb_tipodeic'))->select('tipodeic_nm = "'.$nome.'"')->fetchObject(self::class);
  }



  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeTipodeics($where = null){
    return (new Database('tb_tipodeic'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
