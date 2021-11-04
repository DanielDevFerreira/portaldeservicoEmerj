<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Departamento{

  /**
   * Identificador único do usuário
   * @var integer
   */
  public $departamento_id;

  /**
   * Nome do usuário
   * @var string
   */
  public $departamento_nm;

  /**
   * E-mail do usuário chave única
   * @var string
   */
  public $departamento_des;

  /**
   * departamento_sg do usuário (criptogravada com hash do PHP)
   * @var string
   */
  public $departamento_sg;

  /**
   * departamento_sg do usuário (criptogravada com hash do PHP)
   * @var string
   */
  public $prot_nr;

    /**
   * Data de cadastro do departamento
   * @var string
   */
  public $data_add;

    /**
   * Data da última alteração nos dados do departamento
   * @var string
   */
  public $data_up;

    /**
   * Define se o usuário está ativo
   * @var string(s/n)
   */
  public $ativo_fl;

  /**
 * Define se o usuário está ativo
 * @var integer
 */
   public $cod_dep_super;

  /**
   * Método responsável por cadastrar um novo usuário no banco
   * @return boolean
   */
  public function cadastrar(){
    // A DATA DE INSERT RESOLVIDA DO BD
    $obDatabase = new Database('tb_departamento');

    $this->departamento_id = $obDatabase->insert([
                                      'departamento_nm' => $this->departamento_nm,
                                      'departamento_des' => $this->departamento_des,
                                      'departamento_sg' => $this->departamento_sg,
                                      'prot_nr' => $this->prot_nr,
                                      'cod_dep_super' => $this->cod_dep_super
                                    ]);
    //RETORNAR SUCESSO
    return true;
  }

  /**
   * Método responsável por atualizar a vaga no banco
   * @return boolean
   */
  public function atualizar(){

    //DEFINIR A DATA DE ATUALIZACAO date('Y-m-d H:i:s')
    date_default_timezone_set('America/Sao_Paulo');
    $this->data_up = date('Y-m-d H:i:s');

    return (new Database('tb_departamento'))->update('departamento_id = '.$this->departamento_id,[
                                                     'departamento_nm'    => $this->departamento_nm,
                                                     'departamento_des' => $this->departamento_des,
                                                     'departamento_sg' => $this->departamento_sg,
                                                     'prot_nr' => $this->prot_nr,
                                                     'data_up'  => $this->data_up,
                                                     'ativo_fl' => $this->ativo_fl,
                                                     'cod_dep_super' => $this->cod_dep_super
                                                    ]);
  }

  /**
   * Método responsável por excluir a vaga do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_departamento'))->delete('departamento_id = '.$this->departamento_id);
  }

  /**
   * Método responsável por obter as vagas do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getDepartamentos($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_departamento'))->select($where,$order,$limit,$fields);
  }

   /**
   * Método responsável por obter a quantdade de Departamentos do banco de dados
   * @param  string $where
   * @return integer
   */
  public static function getQuantidadeDepartamentos($where = null){
    return (new Database('tb_departamento'))->select($where,null,null,'COUNT(*) as qtd')
                                  ->fetchObject()
                                  ->qtd;
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return Servico
   */
  public static function getDepartamentoDeChamado2($where = null, $order = null, $from = null, $limit = null, $fields = '*'){
    return (new Database('tb_departamento'))->select2($where,$order,$from,$limit,$fields)->fetchObject(self::class);
  }

  /**
   * Método responsável por buscar uma vaga com base em seu ID
   * @param  integer $id
   * @return Departamento
   */
  public static function getDepartamentoPorId($id){
    return self::getDepartamentos('departamento_id = '.$id)->fetchObject(self::class);
  }

  /**
   * Método responsável por buscar uma vaga com base em seu ID
   * @param  string $sigla
   * @return Departamento
   */
  public static function getDepartamentoIdPorSigla($sigla){
    return self::getDepartamentos('departamento_sg="'.$sigla.'"')->fetchObject(self::class);
  }

  /**
   * Método responsável por buscar uma vaga com base em seu ID
   * @param  integer $id
   * @return Departamento
   */
  public static function getDepartamentoPorProt($prot_nr){
    return self::getDepartamentos('prot_nr = '.$prot_nr)->fetchObject(self::class);
  }

}
