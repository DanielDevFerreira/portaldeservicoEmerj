<?php

namespace App\Entity;

use \App\Db\Database;
use \PDO;

class Perfil{

  /**
   * Identificador único da vaga
   * @var integer
   */
  public $perfil_id;

  /**
   * Título da vaga
   * @var string
   */
  public $perfil_cod;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $perfil_nm;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $perfil_des;

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
    $obDatabase = new Database('tb_perfil');

     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->perfil_id = $obDatabase->insert([
                                      'perfil_cod'    => $this->perfil_cod,
                                      'perfil_nm' => $this->perfil_nm,
                                      'perfil_des' => $this->perfil_des,
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

    return (new Database('tb_perfil'))->update('perfil_id = '.$this->perfil_id,[
                                                                'perfil_nm'    => $this->perfil_nm,
                                                                'perfil_cod' => $this->perfil_cod,
                                                                'perfil_des' => $this->perfil_des,
                                                                'ativo_fl'    => $this->ativo_fl,
                                                                'data_up'  => $this->data_up
                                                              ]);
  }

  /**
   * Método responsável por excluir a vaga do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_perfil'))->delete('perfil_id = '.$this->perfil_id);
  }

  /**
   * Método responsável por obter as vagas do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @return array
   */
  public static function getPerfis($where = null, $order = null, $limit = null){
    return (new Database('perfil_tb'))->select($where,$order,$limit)
                                  ->fetchAll(PDO::FETCH_CLASS,self::class);
  }

     /**
   * Método responsável por obter a quantdade de perfis do banco de dados
   * @param  string $where
   * @return integer
   */
  public static function getQuantidadePerfis($where = null){
    return (new Database('perfil_tb'))->select($where,null,null,'COUNT(*) as qtd')
                                  ->fetchObject()
                                  ->qtd;

  }

  /**
   * Método responsável por buscar uma vaga com base em seu ID
   * @param  integer $id
   * @return Vaga
   */
  public static function getPerfil($perfil_id){
    return (new Database('perfil_tb'))->select('perfil_id = '.$perfil_id)
                                  ->fetchObject(self::class);
  }

}
