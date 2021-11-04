<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Decom{

  /**
   * Identificador único da Categoria de IC
   * @var integer
   */
  public $decom_id;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $decom_nm;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $decom_tipo;

 /**
  * Descrição da Categoria de IC (pode conter html)
  * @var string
  */
 public $decom_url;

 /**
  * Descrição da Categoria de IC (pode conter html)
  * @var string
  */
 public $decom_txturl;

 /**
  * Descrição da Categoria de IC (pode conter html)
  * @var string
  */
 public $decom_imgtemp;

 /**
  * Descrição da Categoria de IC (pode conter html)
  * @var string
  */
 public $decom_img;

 /**
  * Descrição da Categoria de IC (pode conter html)
  * @var string
  */
 public $decom_imgdesc;


  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $decom_titulo;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $decom_descricao;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $decom_icon;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $decom_style;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $data_inicio;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $data_fim;

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
    $obDatabase = new Database('tb_decom');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->decom_id = $obDatabase->insert([
                                      'decom_nm' => $this->decom_nm,
                                      'decom_tipo' => $this->decom_tipo,
                                      'decom_url' => $this->decom_url,
                                      'decom_txturl' => $this->decom_txturl,
                                      'decom_imgtemp' => $this->decom_imgtemp,
                                      'decom_img' => $this->decom_img,
                                      'decom_imgdesc' => $this->decom_imgdesc,
                                      'decom_titulo' => $this->decom_titulo,
                                      'decom_descricao' => $this->decom_descricao,
                                      'decom_icon' => $this->decom_icon,
                                      'decom_style' => $this->decom_style,
                                      'data_inicio' => $this->data_inicio,
                                      'data_fim' => $this->data_fim
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

    return (new Database('tb_decom'))->update('decom_id = '.$this->decom_id,[
                                                        'decom_nm' => $this->decom_nm,
                                                        'decom_tipo' => $this->decom_tipo,
                                                        'decom_url' => $this->decom_url,
                                                        'decom_txturl' => $this->decom_txturl,
                                                        'decom_imgtemp' => $this->decom_imgtemp,
                                                        'decom_img' => $this->decom_img,
                                                        'decom_imgdesc' => $this->decom_imgdesc,
                                                        'decom_titulo' => $this->decom_titulo,
                                                        'decom_descricao' => $this->decom_descricao,
                                                        'decom_icon' => $this->decom_icon,
                                                        'decom_style' => $this->decom_style,
                                                        'ativo_fl'    => $this->ativo_fl,
                                                        'data_inicio' => $this->data_inicio,
                                                        'data_fim' => $this->data_fim,
                                                        'data_up'  => $this->data_up
                                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_decom'))->delete('decom_id = '.$this->decom_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getDecoms($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_decom'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Decom
   */
  public static function getDecomPorId($decom_id){
    return self::getDecoms('decom_id = '.$decom_id)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param string $nome
   * @return Usuario
   */
  public static function getDecomPorNome($nome){
      return (new Database('tb_decom'))->select('decom_nm = "'.$nome.'"')->fetchObject(self::class);
  }



  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeDecoms($where = null){
    return (new Database('tb_decom'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
