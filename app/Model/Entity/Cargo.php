<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Cargo{

  /**
   * Identificador único da vaga
   * @var integer
   */
  public $cargo_id;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $cargo_nm;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $cargo_des;

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
   * Método responsável por cadastrar uma nova vaga no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR A VAGA NO BANCO
    $obDatabase = new Database('tb_cargo');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->cargo_id = $obDatabase->insert([
                                      'cargo_id'    => $this->cargo_id,
                                      'cargo_nm' => $this->cargo_nm,
                                      'cargo_des' => $this->cargo_des,
                                      'ativo_fl'     => $this->ativo_fl
                                    ]);

    //RETORNAR SUCESSO
    return true;
  }

  /**
   * Método responsável por atualizar a vaga no banco
   * @return boolean
   */
  public function atualizar(){

    //DEFINIR A DATA DE ATUALIZACAO date('Y-m-d H:i:s')
    date_default_timezone_set('America/Sao_Paulo');
    $this->data_up = date('Y-m-d H:i:s');

    return (new Database('tb_cargo'))->update('cargo_id = '.$this->cargo_id,[
                                                                'cargo_nm'    => $this->cargo_nm,
                                                                'cargo_des' => $this->cargo_des,
                                                                'ativo_fl'    => $this->ativo_fl,
                                                                'data_up'  => $this->data_up
                                                              ]);
  }

  /**
   * Método responsável por excluir a vaga do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_cargo'))->delete('cargo_id = '.$this->cargo_id);
  }

  /**
   * Método responsável por obter as vagas do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getCargos($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_cargo'))->select($where,$order,$limit,$fields);
  }

     /**
   * Método responsável por obter a quantdade de cargos do banco de dados
   * @param  string $where
   * @return integer
   */
  public static function getQuantidadeCargos($where = null){
    return (new Database('tb_cargo'))->select($where,null,null,'COUNT(*) as qtd')
                                  ->fetchObject()
                                  ->qtd;

  }

  /**
   * Método responsável por buscar uma vaga com base em seu ID
   * @param  integer $id
   * @return Vaga
   */
  public static function getCargo($id){
    return self::getCargos('cargo_id = '.$id)->fetchObject(self::class);
  }

}
