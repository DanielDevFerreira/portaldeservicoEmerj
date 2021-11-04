<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Sessao{

  /**
   * Identificador único da Sessão
   * @var integer
   */
  public $sessao_id;

  /**
   * Nome da Sessão (pode conter html)
   * @var string
   */
  public $sessao_nm;

  /**
   * Nome da Sessão (pode conter html)
   * @var string
   */
  public $sessao_titulo;

  /**
   * Nome da Sessão (pode conter html)
   * @var string
   */
  public $sessao_conteudo;

 /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $sessao_des;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $sessao_icon;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $sessao_style;

  /**
   * Data de publicação da Categoria de IC
   * @var string
   */
  public $data_add;

    /**
   * Data de alteração da Categoria de IC
   * @var string
   */
  public $data_up;

    /**
   * Define se o Tipode IC está ativa
   * @var string(s/n)
   */
  public $ativo_fl;

  /**
 * Define se o Tipode IC está ativa
 * @var integer
 */
public $id_pagina;

/**
* Define se o Tipode IC está ativa
* @var integer
*/
public $id_usuario;

  /**
   * Método responsável por cadastrar um nova IC no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR O IC NO BANCO
    $obDatabase = new Database('tb_sessao');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->sessao_id = $obDatabase->insert([
                                      'sessao_nm' => $this->sessao_nm,
                                      'sessao_titulo' => $this->sessao_titulo,
                                      'sessao_conteudo' => $this->sessao_conteudo,
                                      'sessao_des' => $this->sessao_des,
                                      'sessao_icon' => $this->sessao_icon,
                                      'sessao_style' => $this->sessao_style,
                                      'id_pagina'  => $this->id_pagina,
                                      'id_usuario'  => $this->id_usuario
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

    return (new Database('tb_sessao'))->update('sessao_id = '.$this->sessao_id,[
                                              'sessao_nm' => $this->sessao_nm,
                                              'sessao_titulo' => $this->sessao_titulo,
                                              'sessao_conteudo' => $this->sessao_conteudo,
                                              'sessao_des' => $this->sessao_des,
                                              'sessao_icon' => $this->sessao_icon,
                                              'sessao_style' => $this->sessao_style,
                                              'data_up' => $this->data_up,
                                              'ativo_fl' => $this->ativo_fl,
                                              'id_pagina'  => $this->id_pagina,
                                              'id_usuario'  => $this->id_usuario
                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_sessao'))->delete('sessao_id = '.$this->sessao_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getSessoes($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_sessao'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Sessao
   */
  public static function getSessaoPorId($sessao_id){
    return self::getSessoes('sessao_id = '.$sessao_id)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param string $nome
   * @return Sessao
   */
  public static function getSessaoPorNome($nome){
      return (new Database('tb_sessao'))->select('sessao_nm = "'.$nome.'"')->fetchObject(self::class);
  }

  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param integer $id
   * @return Sessao
   */
  public static function getSessaoPorPagina($id){
      return (new Database('tb_sessao'))->select('id_pagina = "'.$id.'"')->fetchObject(self::class);
  }

  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param integer $id
   * @return integer
   */
  public static function getQuantidadeSessoesPorPagina($id){
      return (new Database('tb_sessao'))->select('id_pagina = "'.$id.'"',null,null,'COUNT(*) as qtd')
                              ->fetchObject()
                              ->qtd;
  }






  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeSessoes($where = null){
    return (new Database('tb_sessao'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
