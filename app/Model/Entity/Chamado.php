<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;

class Chamado{



  /**
   * Identificador único da chamado
   * @var integer
   */
  public $chamado_id;

  /**
   * Nome do usuário
   * @var string
   */
  public $chamado_nm;


  /**
   * E-mail do usuário chave única
   * @var string
   */
  public $chamado_des;

  /**
   * Data de publicação da chamado
   * @var string
   */
  public $data_add;

    /**
   * Data de alteração da chamado
   * @var string
   */
  public $data_up;

  /**
 * Define se o usuário está ativo
 * @var string(s/n)
 */
 public $ativo_fl;

  /**
   * Descrição da chamado (pode conter html)
   * @var integer
   */
  public $id_usuario;

  /**
   * Descrição da chamado (pode conter html)
   * @var integer
   */
  public $autorizado_por;

  /**
   * Descrição da chamado (pode conter html)
   * @var integer
   */
  public $atendido_por;

  /**
   * Descrição da chamado (pode conter html)
   * @var integer
   */
  public $solicitado_por;

  /**
   * Descrição da chamado (pode conter html)
   * @var integer
   */
  public $aberto_para;

  /**
 * Data do atendimento do chamado
 * @var string
 */
  public $dt_atendimento;

  /**
 * Data do atendimento do chamado
 * @var string
 */
  public $nr_solicitacao;

  /**
 * Data do atendimento do chamado
 * @var string
 */
  public $nr_requisicao;

  /**
   * E-mail do usuário chave única
   * @var string
   */
  public $chamado_obs;

  /**
   * Status do chamado
   * @var integer
   */
  public $id_status;

  /**
   * Descrição da chamado (pode conter html)
   * @var integer
   */
  public $id_chamado_pai;



  /**
   * Método responsável por cadastrar um novo chamado no banco
   * @return boolean
   */
  public function cadastrar(){
    //DEFINIR A DATA
    //$this->data_add = date('Y-m-d H:i:s');

    //INSERIR O IC NO BANCO
    $obDatabase = new Database('tb_chamado');
     // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;

    $this->chamado_id = $obDatabase->insert([
                                      'chamado_nm' => $this->chamado_nm,
                                      'chamado_des' => $this->chamado_des,
                                      'id_usuario' => $_SESSION['admin']['usuario']['usuario_id'],
                                      'solicitado_por' => $this->solicitado_por,
                                      'aberto_para' => $this->aberto_para,
                                      'chamado_obs' => $this->chamado_obs,
                                      'id_status' => $this->id_status,
                                      'id_chamado_pai' => strlen($this->id_chamado_pai) > 0 ? $this->id_chamado_pai : NULL
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

    return (new Database('tb_chamado'))->update('chamado_id = '.$this->chamado_id,[
                                                  'chamado_nm' => $this->chamado_nm,
                                                  'chamado_des' => $this->chamado_des,
                                                  'id_usuario' => $_SESSION['admin']['usuario']['usuario_id'],
                                                  'solicitado_por' => $this->solicitado_por
                                                  ]);
   }

  /**
   * Método responsável por excluir um IC do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('tb_chamado'))->delete('chamado_id = '.$this->chamado_id);
  }

  /**
   * Método responsável por obter os IC's do banco de dados
   * @param  string $where
   * @param  string $order
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getChamados($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('tb_chamado'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por buscar um IC com base em seu ID
   * @param integer $id
   * @return Chamado
   */
  public static function getChamadoPorId($chamado_id){
    return self::getChamados('chamado_id = '.$chamado_id)->fetchObject(self::class);
  }

  /**
  * Método responsável por obter a quantdade de IC's do banco de dados
  * @param  string $where
  * @return integer
  */
  public static function getQuantidadeChamados($where = null){
    return (new Database('tb_chamado'))->select($where,null,null,'COUNT(*) as qtd')
                                 ->fetchObject()
                                 ->qtd;
  }
}
