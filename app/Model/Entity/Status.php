<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Status{

  /**
   * Identificador único do status
   * @var integer
   */
  public $status_id;

  /**
   * Nome do Staus
   * @var string
   */
  public $status_nm;

  /**
   * Data de publicação do status
   * @var string
   */
  public $data_add;

    /**
   * Data de alteração do status
   * @var string
   */
  public $data_up;

    /**
   * Define se o status está ativa
   * @var string(s/n)
   */
  public $ativo_fl;

  /**
   * Método responsável por cadastrar um nova status no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR O IC NO BANCO
    $obDatabase = new Database('tb_status');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->status_id = $obDatabase->insert([
                                      'status_nm' => $this->status_nm
                                    ]);

    //RETORNAR SUCESSO
    return true;
  }

  /**
   * Método responsável por atualizar o status no banco
   * @return boolean
   */
  public function atualizar(){

    //DEFINIR A DATA DE ATUALIZACAO date('Y-m-d H:i:s')
    date_default_timezone_set('America/Sao_Paulo');
    $this->data_up = date('Y-m-d H:i:s');

    return (new Database('tb_status'))->update('status_id = '.$this->status_id,[
                                                        'status_nm' => $this->status_nm,
                                                        'ativo_fl'    => $this->ativo_fl,
                                                        'data_up'  => $this->data_up
                                                              ]);
  }

  /**
   * Método responsável por excluir um status do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_status'))->delete('status_id = '.$this->status_id);
  }

  /**
   * Método responsável por obter os status do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getStatus($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_status'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por buscar um status com base em seu ID
   * @param integer $id
   * @return Categoriadeic
   */
  public static function getStatusPorId($status_id){
    return self::getStatus('status_id = '.$status_id)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de status com base em seu nome
   * @param string $nome
   * @return Usuario
   */
  public static function getStatusPorNome($nome){
      return (new Database('tb_status'))->select('status_nm = "'.$nome.'"')->fetchObject(self::class);
  }

  /**
  * Método responsável por obter a quantdade de status do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeStatus($where = null){
    return (new Database('tb_status'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
