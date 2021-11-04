<?php

namespace App\Db;

use \PDO;
use \PDOException;
use \App\Utils\Environment;
use \App\Utils\Geralog;

ini_set('default_charset', 'utf-8');

//GARREGA AS VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__.'/../');

//DEFINE AS CONFIGURAÇÕES DE BANCO DE DADOS
define('HOST_',getenv('DB_HOST'));
define('NAME_',getenv('DB_NAME'));
define('NAME_EVENTO_',getenv('DB_NAME_EVENTO'));
define('USER_',getenv('DB_USER'));
define('PASS_',getenv('DB_PASS'));

class Database{

  /**
   * Host de conexão com o banco de dados
   * @var string
   */
  const HOST = HOST_;

  /**
   * Nome do banco de dados
   * @var string
   */
  const NAME = NAME_;

  /**
   * Nome do banco de dados
   * @var string
   */
  const NAME_EVENTO = NAME_EVENTO_;

  /**
   * Usuário do banco
   * @var string
   */
  const USER = USER_;

  /**
   * Senha de acesso ao banco de dados
   * @var string
   */
  const PASS = PASS_;

  /**
   * Nome da tabela a ser manipulada
   * @var string
   */
  private $table;

  /**
   * Nome das tabelas a serem manipuladas
   * @var string
   */
  private $tabelas;

  /**
   * Instancia de conexão com o banco de dados
   * @var PDO
   */
  private $connection;

  /**
   * Define a tabela e instancia e conexão
   * @param string $table
   */
  public function __construct($table = null,$strBancoName = null){
    $this->table = $table;

    //CHAMADA DINAMICA DO BANCO DA CONEXÃO
    switch ($strBancoName) {
      case 'emerjco_evento':
        return $this->setConnection(self::NAME_EVENTO);
        // code...
        break;
      default:
        return $this->setConnection(self::NAME);
    }
  }

  /**
   * Método responsável por criar uma conexão com o banco de dados
   */
  private function setConnection($bancoName){
    try{
      $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.$bancoName,self::USER,self::PASS,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
      $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
      Geralog::getInstance()->inserirLog("erro","Erro: Código: " . $e->getCode() .
              " Mensagem: " . $e->getMessage());
      die('ERROR: '.$e->getMessage());
    }
  }



  /**
   * Método responsável por executar queries dentro do banco de dados
   * @param  string $query
   * @param  array  $params
   * @return PDOStatement
   */
  public function execute($query,$params = []){
    try{
      $statement = $this->connection->prepare($query);
      $statement->execute($params);
      return $statement;
    }catch(PDOException $e){
      Geralog::getInstance()->inserirLog("erro","Erro: Código: " . $e->getCode() .
              " Mensagem: " . $e->getMessage());
      die('ERROR: '.$e->getMessage());
    }
  }

  /**
   * Método responsável por inserir dados no banco
   * @param  array $values [ field => value ]
   * @return integer ID inserido
   */
  public function insert($values){
    //DADOS DA QUERY
    $fields = array_keys($values);
    $binds  = array_pad([],count($fields),'?');

    //MONTA A QUERY
    $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';

    //EXECUTA O INSERT
    $this->execute($query,array_values($values));

    //RETORNA O ID INSERIDO
    return $this->connection->lastInsertId();
  }

  /**
   * Método responsável por executar uma consulta no banco
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $fields
   * @return PDOStatement
   */
  public function select($where = null, $order = null, $limit = null, $fields = '*'){
    //DADOS DA QUERY
    $where = strlen($where) ? 'WHERE '.$where : '';
    $order = strlen($order) ? 'ORDER BY '.$order : '';
    $limit = strlen($limit) ? 'LIMIT '.$limit : '';

    //MONTA A QUERY
    $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

    //EXECUTA A QUERY
    return $this->execute($query);
  }


  /**
   * Método responsável por executar uma consulta no banco
   * @param  string $where
   * @param  string $order
   * @param  string $from
   * @param  string $limit
   * @param  string $fields
   * @return PDOStatement
   */
  public function select2($where = null, $order = null, $from = null, $limit = null, $fields = '*'){
    //DADOS DA QUERY
    $where = strlen($where) ? 'WHERE '.$where : '';
    $order = strlen($order) ? 'ORDER BY '.$order : '';
    $from = strlen($from ) ? 'FROM '.$from  : '';
    $limit = strlen($limit) ? 'LIMIT '.$limit : '';

    //MONTA A QUERY
    $query = 'SELECT '.$fields.' '.$from.' '.$where.' '.$order.' '.$limit;

    //EXECUTA A QUERY

    //echo "<pre>"; print_r($query); echo "<pre>";
    return $this->execute($query);
  }
  
  
  /**
   * Método responsável por executar sql para o calendario dinâmico
   * */
  public function select3($query){
    return $this->execute($query);
  }

  /**
   * Método responsável por executar atualizações no banco de dados
   * @param  string $where
   * @param  array $values [ field => value ]
   * @return boolean
   */
  public function update($where,$values){
    //DADOS DA QUERY
    $fields = array_keys($values);

    //MONTA A QUERY
    $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;

    //EXECUTAR A QUERY
    $this->execute($query,array_values($values));

    //RETORNA SUCESSO
    return true;
  }

  /**
   * Método responsável por excluir dados do banco
   * @param  string $where
   * @return boolean
   */
  public function delete($where){
    //MONTA A QUERY
    $query = 'DELETE FROM '.$this->table.' WHERE '.$where;

    //EXECUTA A QUERY
    $this->execute($query);

    //RETORNA SUCESSO
    return true;
  }

}
