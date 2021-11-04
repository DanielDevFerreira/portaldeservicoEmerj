<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Categoriadeic{

  /**
   * Identificador único da Categoria de IC
   * @var integer
   */
  public $categoria_ic_id;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $categoria_ic_nm;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $categoria_ic_titulo;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $categoria_ic_descricao;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $categoria_ic_icon;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $categoria_ic_style;

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
   * Método responsável por cadastrar um nova IC no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR O IC NO BANCO
    $obDatabase = new Database('tb_categoria_ic');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->categoria_ic_id = $obDatabase->insert([
                                      'categoria_ic_nm' => $this->categoria_ic_nm,
                                      'categoria_ic_titulo' => $this->categoria_ic_titulo,
                                      'categoria_ic_descricao' => $this->categoria_ic_descricao,
                                      'categoria_ic_icon' => $this->categoria_ic_icon,
                                      'categoria_ic_style' => $this->categoria_ic_style
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

    return (new Database('tb_categoria_ic'))->update('categoria_ic_id = '.$this->categoria_ic_id,[
                                                        'categoria_ic_nm' => $this->categoria_ic_nm,
                                                        'categoria_ic_titulo' => $this->categoria_ic_titulo,
                                                        'categoria_ic_descricao' => $this->categoria_ic_descricao,
                                                        'categoria_ic_icon' => $this->categoria_ic_icon,
                                                        'categoria_ic_style' => $this->categoria_ic_style,
                                                        'ativo_fl'    => $this->ativo_fl,
                                                        'data_up'  => $this->data_up
                                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_categoria_ic'))->delete('categoria_ic_id = '.$this->categoria_ic_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getCategoriadeics($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_categoria_ic'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Categoriadeic
   */
  public static function getCategoriadeicPorId($categoria_ic_id){
    return self::getCategoriadeics('categoria_ic_id = '.$categoria_ic_id)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param string $nome
   * @return Usuario
   */
  public static function getCategoriadeicPorNome($nome){
      return (new Database('tb_categoria_ic'))->select('categoria_ic_nm = "'.$nome.'"')->fetchObject(self::class);
  }



  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeCategoriadeics($where = null){
    return (new Database('tb_categoria_ic'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
