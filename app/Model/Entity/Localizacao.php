<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Localizacao{

  /**
   * Identificador único da localização
   * @var integer
   */
  public $localizacao_id;

  /**
   * Descrição da localizacao (pode conter html)
   * @var string
   */
  public $localizacao_nm;

  /**
   * Descrição da localizacao (pode conter html)
   * @var string
   */
  public $localizacao_des;

  /**
   * Data de publicação da localizacao
   * @var string
   */
  public $data_add;

    /**
   * Data de alteração da localizacao
   * @var string
   */
  public $data_up;

    /**
   * Define se a localizacao ativa
   * @var string(s/n)
   */
  public $ativo_fl;

  /**
   * Define o id do departamento
   * @var integer
   */
  public $id_departamento;

  /**
   * Método responsável por cadastrar um nova localização no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR UMA NOVA LOCALIZAÇÃO NO BANCO
    $obDatabase = new Database('tb_localizacao');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->localizacao_id = $obDatabase->insert([
                                      'localizacao_nm' => $this->localizacao_nm,
                                      'localizacao_des' => $this->localizacao_des,
                                      'id_departamento' => $this->id_departamento
                                    ]);

    //RETORNAR SUCESSO
    return true;
  }

  /**
   * Método responsável por atualizar a localização no banco
   * @return boolean
   */
  public function atualizar(){

    //DEFINIR A DATA DE ATUALIZACAO date('Y-m-d H:i:s')
    date_default_timezone_set('America/Sao_Paulo');
    $this->data_up = date('Y-m-d H:i:s');

    return (new Database('tb_localizacao'))->update('localizacao_id = '.$this->localizacao_id,[
                                                                'localizacao_nm'    => $this->localizacao_nm,
                                                                'localizacao_des' => $this->localizacao_des,
                                                                'ativo_fl'    => $this->ativo_fl,
                                                                'data_up'  => $this->data_up,
                                                                'id_departamento' => $this->id_departamento
                                                              ]);
  }

  /**
   * Método responsável por excluir uma localização do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_localizacao'))->delete('localizacao_id = '.$this->localizacao_id);
  }

  /**
   * Método responsável por obter as localizações do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getLocalizacoes($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_localizacao'))->select($where,$order,$limit,$fields);
  }


  /**
   * Método responsável por buscar uma localização com base em seu ID
   * @param integer $id
   * @return Localizacao
   */
  public static function getLocalizacaoPorId($localizacao_id){
    return self::getLocalizacoes('localizacao_id = '.$localizacao_id)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param string $nome
   * @return Usuario
   */
  public static function getLocalizacaoPorNome($nome){
      return (new Database('tb_localizacao'))->select('localizacao_nm = "'.$nome.'"')->fetchObject(self::class);
  }



  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeLocalizacoes($where = null){
    return (new Database('tb_localizacao'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
