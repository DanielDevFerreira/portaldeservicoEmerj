<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Pagina{

  /**
   * Identificador único da Categoria de IC
   * @var integer
   */
  public $pagina_id;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $pagina_nm;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $pagina_label;

 /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $pagina_des;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $pagina_icon;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $pagina_style;

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
 * @var string(s/n)
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
    $obDatabase = new Database('tb_pagina');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->pagina_id = $obDatabase->insert([
                                      'pagina_nm' => $this->pagina_nm,
                                      'pagina_label' => $this->pagina_label,
                                      'pagina_des' => $this->pagina_des,
                                      'pagina_icon' => $this->pagina_icon,
                                      'pagina_style' => $this->pagina_style,
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

    return (new Database('tb_pagina'))->update('pagina_id = '.$this->pagina_id,[
                                              'pagina_nm' => $this->pagina_nm,
                                              'pagina_label' => $this->pagina_label,
                                              'pagina_des' => $this->pagina_des,
                                              'pagina_icon' => $this->pagina_icon,
                                              'pagina_style' => $this->pagina_style,
                                              'data_up' => $this->data_up,
                                              'ativo_fl' => $this->ativo_fl,
                                              'id_usuario'  => $this->id_usuario
                                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_pagina'))->delete('pagina_id = '.$this->pagina_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getPaginas($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_pagina'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Pagina
   */
  public static function getPaginaPorId($pagina_id){
    return self::getPaginas('pagina_id = '.$pagina_id)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param string $nome
   * @return Usuario
   */
  public static function getPaginaPorNome($nome){
      return (new Database('tb_pagina'))->select('pagina_nm = "'.$nome.'"')->fetchObject(self::class);
  }



  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadePaginas($where = null){
    return (new Database('tb_pagina'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
