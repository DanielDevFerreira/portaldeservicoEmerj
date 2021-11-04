<?php

namespace App\Communication;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use \App\Utils\Environment;

ini_set('default_charset', 'utf-8');

//GARREGA AS VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__.'/../');

//DEFINE AS CONFIGURAÇÕES DE BANCO DE DADOS
define('HOST_SMTP_',getenv('HOST_SMTP'));
define('USER_SMTP_',getenv('USER_SMTP'));
define('PASS_SMTP_',getenv('PASS_SMTP'));
define('SECURE_SMTP_',getenv('SECURE_SMTP'));
define('PORT_SMTP_',getenv('PORT_SMTP'));
define('CHARSET_SMTP_',getenv('CHARSET_SMTP'));
define('FROM_EMAIL_',getenv('FROM_EMAIL'));
define('FROM_NAME_',getenv('FROM_NAME'));

class Email{

  /**
  * Credenciais de acesso ao SMTP
  * @var string
  */
  const HOST_SMTP = HOST_SMTP_;
  const USER_SMTP = USER_SMTP_;
  const PASS_SMTP = PASS_SMTP_;
  const SECURE_SMTP = SECURE_SMTP_;
  const PORT_SMTP =PORT_SMTP_;
  const CHARSET_SMTP = CHARSET_SMTP_;

  /**
  * Dados do remetente
  * @var string
  */
  const FROM_EMAIL = FROM_EMAIL_;
  const FROM_NAME = FROM_NAME_;

  /**
  * Mensagem de erro do envio
  * @var string
  */
  private $error;

  /**
  * Mensagem de erro do envio
  * @return string
  */
  public function getError(){
    return $this->error;
  }

/**
 * Método responsável por enviar um e-mail
 * @param string/array $enderecoEmail
 * @param string $assuntoEmail
 * @param string $corpoEmail
 * @param string/array $anexosEmail
 * @param string/array $ccs
 * @param string/array $bccs
 * @param boolean
 */
  public function sendEmail($enderecoEmail,$assuntoEmail,$corpoEmail,$anexosEmail = [],$ccs = [],$bccs = []){
    //LIMPAR MENSAGEM DE ERRO
    $this->error = '';

    //INSTANCIA DE PHPMAILER
    $obMail = new PHPMailer(true);
    try{
      //CREDENCIAIS DE ACESSO AO SMPT
      $obMail->SMTPDebug = 0;
      $obMail->isSMTP(true);
      $obMail->Host = self::HOST_SMTP;
      $obMail->SMTPAuth = true;
      $obMail->Username = self::USER_SMTP;
      $obMail->Password = self::PASS_SMTP;
      $obMail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //self::SECURE_SMTP; Enable implicit TLS encryption
      $obMail->Port = self::PORT_SMTP;                    //use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
      $obMail->CharSet = self::CHARSET_SMTP;

      //REMETENTE
      $obMail->setFrom(self::FROM_EMAIL,self::FROM_NAME);

      //DESTINATARIOS
      $enderecoEmail = is_array($enderecoEmail) ? $enderecoEmail : [$enderecoEmail];
      foreach ($enderecoEmail as $enderecoEmail){
        $obMail->addAddress($enderecoEmail);
      }

      //ANEXOS
      $anexosEmail = is_array($anexosEmail) ? $anexosEmail : [$anexosEmail];
      foreach ($anexosEmail as $anexosEmail){
        $obMail->addAttachment($anexosEmail);
      }

      //CCs
      $ccs = is_array($ccs) ? $ccs : [$ccs];
      foreach ($ccs as $ccs){
        $obMail->addCC($ccs);
      }

      //BCCs
      $bccs = is_array($bccs) ? $bccs : [$bccs];
      foreach ($bccs as $bccs){
        $obMail->addBCC($bccs);
      }

      //CORPO DO E-MAIL
      $obMail->isHTML(true);
      $obMail->Subject = $assuntoEmail;
      $obMail->Body = $corpoEmail;

      return $obMail->send();

    }catch(PHPMailerException $e){
      $this->error = $e->getMessage();
      return false;
    }
  }


}
