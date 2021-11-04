<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Evento{

  /**
   * Identificador único da localização
   * @var integer
   */
  public $codigo;

  /**
   * Descrição da evento (pode conter html)
   * @var string
   */
  public $nome;

  /**
   * Descrição da evento (pode conter html)
   * @var string
   */
  public $local;


  /**
   * Método responsável por cadastrar um nova localização no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');


    //INSERIR UMA NOVA EVENTO NO BANCO
    $obDatabase = new Database('evento',NAME_EVENTO);
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->codigo = $obDatabase->insert([
                                      'nome' => $this->nome,
                                      'local' => $this->local
                                    ]);

    //RETORNAR SUCESSO
    return true;
  }

  /**
   * Método responsável por atualizar a localização no banco
   * @return boolean
   */
  public function atualizar(){

    //DEFINIR A DATA DE ATUALIZACAO date('Y-m-d H:i:s')
    date_default_timezone_set('America/Sao_Paulo');
    $this->data_up = date('Y-m-d H:i:s');

    return (new Database('evento'))->update('codigo = '.$this->codigo,[
                                                                'nome'    => $this->nome,
                                                                'local' => $this->local
                                                              ]);
  }

  /**
   * Método responsável por excluir uma localização do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('evento','emerjco_evento'))->delete('codigo = '.$this->codigo);
  }

  /**
   * Método responsável por obter as localizações do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getEventos($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('evento','emerjco_evento'))->select($where,$order,$limit,$fields);
  }


  /**
   * Método responsável por buscar uma localização com base em seu ID
   * @param integer $id
   * @return Evento
   */
  public static function getEventoPorId($codigo){
    return self::getEventos('codigo = '.$codigo)->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar uma instancia de tipo de IC com base em seu nome
   * @param string $nome
   * @return Usuario
   */
  public static function getEventoPorNome($nome){
      return (new Database('evento','emerjco_evento'))->select('nome = "'.$nome.'"')->fetchObject(self::class);
  }



  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeEventos($where = null){
    return (new Database('evento','emerjco_evento'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
