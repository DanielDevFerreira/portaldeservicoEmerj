<?php

namespace App\Model\Entity;

use \App\Model\Entity\Departamento as EntityDepartamento;
use \App\Db\Database;
use \PDO;

class Usuario{

  /**
   * Identificador único do usuário
   * @var integer
   */
  public $usuario_id;

  /**
   * Nome do usuário
   * @var string
   */
  public $usuario_nm;

  /**
   * E-mail do usuário chave única
   * @var string
   */
  public $email;

  /**
   * Senha do usuário (criptogravada com hash do PHP)
   * @var string
   */
  public $senha;

  /**
   * Código do perfil de acesso do usuário
   * @var integer
   */
  public $id_perfil;

  /**
   * Código do cargo do usuário (chave estrangeira da tabela cargo)
   * @var integer
   */
  public $id_cargo;

  /**
   * Código do cargo da lotação do usuário (chave estrangeira da tabela lotação)
   * @var integer
   */
  public $id_departamento;

  /**
   * Sala do usuário
   * @var string
   */
  public $sala;

  /**
   * Telefone de contato do usuário
   * @var string
   */
  public $usuario_fone;

  /**
   * Data de cadastro do usuário
   * @var string
   */
  public $data_add;

    /**
   * Data da última alteração nos dados do usuário
   * @var string
   */
  public $data_up;

    /**
   * Define se o usuário está ativo
   * @var string(s/n)
   */
  public $ativo_fl;

  /**
   * Método responsável por cadastrar um novo usuário no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERE UM USUÁRIO NO BANCO
    $obDatabase = new Database('tb_usuario');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;
    $this->usuario_id = $obDatabase->insert([
                                      'usuario_nm' => $this->usuario_nm,
                                      'email' => $this->email,
                                      'senha' => $this->senha,
                                      'id_perfil' => $this->id_perfil,
                                      'id_cargo' => $this->id_cargo,
                                      'id_departamento' => $this->id_departamento,
                                      'sala' => $this->sala,
                                      'usuario_fone' => $this->usuario_fone,
                                      'ativo_fl' => $this->ativo_fl
                                    ]);

    //RETORNAR SUCESSO
    return true;
  }

  /**
   * Método responsável por retornar uma instancia de usuário com base em seu e-mail
   * @param string $email
   * @return Usuario
   */
  public static function getUsuarioPorEmail($email){
      return (new Database('tb_usuario'))->select('email = "'.$email.'"')->fetchObject(self::class);
  }

  /**
   * Método responsável por retornar uma instancia de usuário com base em seu id
   * @param integer $id
   * @return Usuario
   */
  public static function getUsuarioPorId($id){
      return self::getUsuarios('usuario_id = '.$id)->fetchObject(self::class);
  }

  /**
   * Método responsável por atualizar os dados do usuário no banco
   * @return boolean
   */
  public function atualizar(){

    //DEFINIR A DATA DE ATUALIZACAO date('Y-m-d H:i:s')
    date_default_timezone_set('America/Sao_Paulo');
    $this->data_up = date('Y-m-d H:i:s');

    return (new Database('tb_usuario'))->update('usuario_id = '.$this->usuario_id,[
                                                                'usuario_nm'    => $this->usuario_nm,
                                                                'email' => $this->email,
                                                                'senha' => $this->senha,
                                                                'id_perfil' => $this->id_perfil,
                                                                'id_cargo' => $this->id_cargo,
                                                                'id_departamento' => $this->id_departamento,
                                                                'sala' => $this->sala,
                                                                'usuario_fone' => $this->usuario_fone,
                                                                'data_up'  => $this->data_up,
                                                                'ativo_fl' => $this->ativo_fl
                                                              ]);
  }

  /**
   * Método responsável por atualizar os dados do usuário no banco
   * @return boolean
   */
  public function atualizadataultimoacesso(){

    //DEFINIR A DATA DE ATUALIZACAO date('Y-m-d H:i:s')
    date_default_timezone_set('America/Sao_Paulo');
    $this->dt_ulti_acesso = date('Y-m-d H:i:s');

    return (new Database('tb_usuario'))->update('usuario_id = '.$this->usuario_id,[
                                                                'dt_ulti_acesso'  => $this->dt_ulti_acesso,
                                                              ]);
  }

  /**
   * Método responsável por excluir um usuário do banco
   * @return boolean
   */
  public function excluir(){
    //EXCLUI O USUÁRIO DO BANCO DE DADOS
    return (new Database('tb_usuario'))->delete('usuario_id = '.$this->usuario_id);
  }

  /**
   * Método responsável por obter os usuário do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getUsuarios($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_usuario'))->select($where,$order,$limit,$fields);
  }
     /**
   * Método responsável por obter a quantdade de Usuarios do banco de dados
   * @param  string $where
   * @return integer
   */
  public static function getQuantidadeUsuarios($where = null){
    return (new Database('tb_usuario'))->select($where,null,null,'COUNT(*) as qtd')
                                  ->fetchObject()
                                  ->qtd;

  }

  /**
 * Método responsável por obter a quantdade de Usuarios do banco de dados
 * @param  integer $where
 * @return string
 */
  public function getDepartamentoPaiDousuarioPorID($id_departamento){
      $obDepartamento = EntityDepartamento::getDepartamentoPorId($id_departamento);
      $departamento_sg = $obDepartamento->departamento_sg;
      $departamento_sg_pai = EntityDepartamento::getDepartamentoPorId($obDepartamento->cod_dep_super)->departamento_sg;
      if ($departamento_sg_pai == 'EMERJ') {
        return $departamento_sg;
      } else {
        return $departamento_sg_pai;
      }
  }

  /**
 * Método responsável por obter a quantdade de Usuarios do banco de dados
 * @param  integer $where
 * @return integer
 */
  public function getIDdepartamentoPaiDousuarioPorID($id_departamento){
      $obDepartamento = EntityDepartamento::getDepartamentoPorId($id_departamento);
      $departamento_id = $obDepartamento->departamento_id;
      $departamento_id_pai = EntityDepartamento::getDepartamentoPorId($obDepartamento->cod_dep_super)->departamento_id;
      if ($departamento_id_pai == 4) {
        return $departamento_id;
      } else {
        return $departamento_id_pai;
      }
  }
}
