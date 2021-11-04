<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Subsessao{

  /**
   * Identificador único da Subsessão
   * @var integer
   */
  public $subsessao_id;

  /**
   * Nome da Subsessão (pode conter html)
   * @var string
   */
  public $subsessao_nm;

  /**
   * Nome da Subsessão (pode conter html)
   * @var string
   */
  public $subsessao_titulo;

  /**
   * Nome da Subsessão (pode conter html)
   * @var string
   */
  public $subsessao_conteudo;

  /**
   * Descrição da Subsessão (pode conter html)
   * @var string
   */
  public $subsessao_dropdownlabel;

 /**
  * Link da Subsessão (pode conter html)
  * @var string
  */
 public $subsessao_dropdownlink;

 /**
  * Descrição da Categoria de IC (pode conter html)
  * @var string
  */
 public $subsessao_navbarDropdownMenuLink;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $subsessao_des;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $subsessao_icon;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $subsessao_style;

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
public $id_sessao;

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
    $obDatabase = new Database('tb_subsessao');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->subsessao_id = $obDatabase->insert([
                                      'subsessao_nm' => $this->subsessao_nm,
                                      'subsessao_titulo' => $this->subsessao_titulo,
                                      'subsessao_conteudo' => $this->subsessao_conteudo,
                                      'subsessao_des' => $this->subsessao_des,
                                      'subsessao_icon' => $this->subsessao_icon,
                                      'subsessao_style' => $this->subsessao_style,
                                      'id_sessao'  => $this->id_sessao,
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

    return (new Database('tb_subsessao'))->update('subsessao_id = '.$this->subsessao_id,[
                                              'subsessao_nm' => $this->subsessao_nm,
                                              'subsessao_titulo' => $this->subsessao_titulo,
                                              'subsessao_conteudo' => $this->subsessao_conteudo,
                                              'subsessao_des' => $this->subsessao_des,
                                              'subsessao_icon' => $this->subsessao_icon,
                                              'subsessao_style' => $this->subsessao_style,
                                              'data_up' => $this->data_up,
                                              'id_sessao'  => $this->id_sessao,
                                              'id_usuario'  => $this->id_usuario
                                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_subsessao'))->delete('subsessao_id = '.$this->subsessao_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getSubsessoes($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_subsessao'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Subsessao
   */
  public static function getSubsessaoPorId($subsessao_id){
    return self::getSubsessoes('subsessao_id = '.$subsessao_id)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param string $nome
   * @return Subsessao
   */
  public static function getSubsessaoPorNome($nome){
      return (new Database('tb_subsessao'))->select('subsessao_nm = "'.$nome.'"')->fetchObject(self::class);
  }

  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param integer $id
   * @return Subsessao
   */
  public static function getSubsessaoPorSessao($id){
      return (new Database('tb_subsessao'))->select('id_sessao = "'.$id.'"')->fetchObject(self::class);
  }

  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param integer $id
   * @return integer
   */
  public static function getQuantidadeSubsessoesPorSessao($id){
      return (new Database('tb_subsessao'))->select('id_sessao = "'.$id.'"',null,null,'COUNT(*) as qtd')
                              ->fetchObject()
                              ->qtd;
  }






  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeSubsessoes($where = null){
    return (new Database('tb_subsessao'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
