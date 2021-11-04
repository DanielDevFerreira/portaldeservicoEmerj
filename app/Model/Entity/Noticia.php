<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Noticia{

  /**
   * Identificador único da Categoria de IC
   * @var integer
   */
  public $noticia_id;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $noticia_nm;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $noticia_tipo;

 /**
  * Descrição da Categoria de IC (pode conter html)
  * @var string
  */
 public $noticia_titulo;

 /**
  * Descrição da Categoria de IC (pode conter html)
  * @var string
  */
 public $noticia_imgtemp;

 /**
  * Descrição da Categoria de IC (pode conter html)
  * @var string
  */
 public $noticia_img;

 /**
  * Descrição da Categoria de IC (pode conter html)
  * @var string
  */
 public $noticia_imgalt;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $noticia_imgtittle;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $noticia_descricao;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $noticia_icon;

  /**
   * Descrição da Categoria de IC (pode conter html)
   * @var string
   */
  public $noticia_style;

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
    $obDatabase = new Database('tb_noticia');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->noticia_id = $obDatabase->insert([
                                      'noticia_nm' => $this->noticia_nm,
                                      'noticia_tipo' => $this->noticia_tipo,
                                      'noticia_titulo' => $this->noticia_titulo,
                                      'noticia_imgtemp' => $this->noticia_imgtemp,
                                      'noticia_img' => $this->noticia_img,
                                      'noticia_imgalt' => $this->noticia_imgalt,
                                      'noticia_imgtittle' => $this->noticia_imgtittle,
                                      'noticia_descricao' => $this->noticia_descricao,
                                      'noticia_icon' => $this->noticia_icon,
                                      'noticia_style' => $this->noticia_style,
                                      'data_inicio' => $this->data_inicio,
                                      'data_fim' => $this->data_fim,
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

    return (new Database('tb_noticia'))->update('noticia_id = '.$this->noticia_id,[
                                                        'noticia_nm' => $this->noticia_nm,
                                                        'noticia_tipo' => $this->noticia_tipo,
                                                        'noticia_titulo' => $this->noticia_titulo,
                                                        'noticia_imgtemp' => $this->noticia_imgtemp,
                                                        'noticia_img' => $this->noticia_img,
                                                        'noticia_imgalt' => $this->noticia_imgalt,
                                                        'noticia_imgtittle' => $this->noticia_imgtittle,
                                                        'noticia_descricao' => $this->noticia_descricao,
                                                        'noticia_icon' => $this->noticia_icon,
                                                        'noticia_style' => $this->noticia_style,
                                                        'ativo_fl'    => $this->ativo_fl,
                                                        'data_inicio' => $this->data_inicio,
                                                        'data_fim' => $this->data_fim,
                                                        'data_up'  => $this->data_up,
                                                        'id_usuario'  => $this->id_usuario
                                                              ]);
  }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_noticia'))->delete('noticia_id = '.$this->noticia_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getNoticias($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_noticia'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Noticia
   */
  public static function getNoticiaPorId($noticia_id){
    return self::getNoticias('noticia_id = '.$noticia_id)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param string $nome
   * @return Usuario
   */
  public static function getNoticiaPorNome($nome){
      return (new Database('tb_noticia'))->select('noticia_nm = "'.$nome.'"')->fetchObject(self::class);
  }



  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeNoticias($where = null){
    return (new Database('tb_noticia'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
