<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Link{

  /**
   * Identificador único da vaga
   * @var integer
   */
  public $link_id;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $link_recuperacao;

  /**
   * Descrição da vaga (pode conter html)
   * @var string
   */
  public $id_usuario;

  /**
   * Data de publicação da vaga
   * @var string
   */
  public $data_link;

    /**
   * Data de alteração da vaga
   * @var string
   */
  public $data_alter;

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
    $obDatabase = new Database('tb_links_recuperacao_senha');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->link_id = $obDatabase->insert([
                                      'link_recuperacao' => $this->link_recuperacao,
                                      'id_usuario' => $this->id_usuario,
                                      'data_link' => $this->data_link
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

    return (new Database('tb_links_recuperacao_senha'))->update('link_id = '.$this->link_id,[
                                                                'link_recuperacao'  => 'utilizado',
                                                                'ativo_fl'    => 'n',
                                                                'data_alter'  => $this->data_alter
                                                              ]);
  }

  /**
   * Método responsável por excluir o link do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_links_recuperacao_senha'))->delete('link_id = '.$this->link_id);
  }

  /**
   * Método responsável por obter os links do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getLinks($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_links_recuperacao_senha'))->select($where,$order,$limit,$fields);
  }

     /**
   * Método responsável por obter a quantdade de Links do banco de dados
   * @param  string $where
   * @return integer
   */
  public static function getQuantidadeLinks($where = null){
    return (new Database('tb_links_recuperacao_senha'))->select($where,null,null,'COUNT(*) as qtd')
                                  ->fetchObject()
                                  ->qtd;

  }

  /**
   * Método responsável por buscar um LINK com base em seu CÓDIGO
   * @param  String $codigo_rec
   * @return Link
   */
  public static function getLink($codigo_rec){
    return (new Database('tb_links_recuperacao_senha'))->select('link_recuperacao = "'.$codigo_rec.'" AND data_link > NOW() AND ativo_fl = "s"')
                                  ->fetchObject(self::class);
  }

}
