<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Arquivo{

  /**
   * Identificador único da vaga
   * @var integer
   */
  public $arquivo_id;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $arquivo_nm;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $arquivo_temp;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $arquivo_tam;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $arquivo_type;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $arquivo_icon;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $id_usuario;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $id_chamado;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
   public $id_sessao;

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
   * Método responsável por criar um link de recuperação de senha no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR A VAGA NO BANCO
    $obDatabase = new Database('tb_arquivo');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->arquivo_id = $obDatabase->insert([
                                      'arquivo_nm' => $this->arquivo_nm,
                                      'arquivo_temp' => $this->arquivo_temp,
                                      'arquivo_tam' => $this->arquivo_tam,
                                      'arquivo_type' => $this->arquivo_type,
                                      'arquivo_icon' => $this->arquivo_icon,
                                      'id_usuario' => $this->id_usuario,
                                      'id_sessao' => $this->id_sessao,
                                      'id_sessao' => $this->id_sessao
                                    ]);

    //RETORNAR SUCESSO
    return true;
  }

  /**
   * Método responsável por inutilizar um link do banco
   * @return boolean
   */
  public function atualizar(){

    //DEFINIR A DATA DE ATUALIZACAO date('Y-m-d H:i:s')
    date_default_timezone_set('America/Sao_Paulo');
    $this->data_alter = date('Y-m-d H:i:s');

    return (new Database('tb_arquivo'))->update('arquivo_id = '.$this->arquivo_id,[
                                                  'arquivo_temp' => $this->arquivo_temp,
                                                  'arquivo_tam' => $this->arquivo_tam,
                                                  'arquivo_type' => $this->arquivo_type,
                                                  'arquivo_icon' => $this->arquivo_icon,
                                                  'id_usuario' => $this->id_usuario,
                                                  'id_chamado' => $this->id_chamado,
                                                  'id_sessao' => $this->id_sessao,                                                  
                                                  'data_up' => $this->data_up
                                                              ]);
  }

  /**
   * Método responsável por excluir o link do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_arquivo'))->delete('arquivo_id = '.$this->arquivo_id);
  }

  /**
   * Método responsável por obter os links do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getArquivos($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_arquivo'))->select($where,$order,$limit,$fields);
  }

     /**
   * Método responsável por obter a quantdade de Links do banco de dados
   * @param  string $where
   * @return integer
   */
  public static function getQuantidadeArquivos($where = null){
    return (new Database('tb_arquivo'))->select($where,null,null,'COUNT(*) as qtd')
                                  ->fetchObject()
                                  ->qtd;

  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Arquivo
   */
  public static function getArquivoPorId($arquivo_id = null){
    return self::getArquivos('arquivo_id = '.$arquivo_id)->fetchObject(self::class);
  }

}
