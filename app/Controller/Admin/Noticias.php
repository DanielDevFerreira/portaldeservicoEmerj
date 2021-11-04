<?php

namespace App\Controller\Admin;

use \DateTime;

use \App\Utils\View;
use \App\Model\Entity\Noticia as EntityNoticia;
use \App\Controller\Admin\Servicos as AdminServico;
use \App\Controller\Admin\Itensconfs as AdminItensconfs;
use \App\Model\Entity\Arquivo as EntityArquivo;
use \App\File\Upload;
use \App\Db\Pagination;

const DIR_NOTICIA = 'noticia';
const FIELD_NOTICIA = 'noticia';
const ROTA_NOTICIA = 'noticias';
const ICON_NOTICIA = 'book';
const TITLE_NOTICIA = 'Notícias';
const TITLELOW_NOTICIA = 'a Notícia';

class Noticias extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Localizacões
   * @param Request $request
   * @return string
   */
    public static function getListNoticias($request,$errorMessage = null){

      $permissao = false;
      $status = self::getStatus($request);
      $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
      $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

      //STATUS
      if(!isset($currentDepartamento)) return $permissao = false;

     //MENSAGENS DE STATUS
     switch ($currentDepartamento) {
       case 'DECOM':
         $permissao = false;
         break;
       case 'DETEC':
         $permissao = true;
         break;
       case 'EMERJ':
         $permissao = false;
         break;
     }
     //STATUS
     if(!isset($currentPerfil)) return $permissao = false;

    //MENSAGENS DE STATUS
    switch ($currentPerfil) {
      case 1:
        $permissao = true;
        break;
      case 2:
        $permissao = true;
        break;
    }

      if (!$permissao) {
        $request->getRouter()->redirect('/?status=sempermissao');
      }

      //CONTEÚDO DA HOME
      $content = View::render('admin/modules/'.DIR_NOTICIA.'/index',[
        'icon' => ICON_NOTICIA,
        'title' =>TITLE_NOTICIA,
        'titlelow' => TITLELOW_NOTICIA,
        'direntity' => ROTA_NOTICIA,
        'itens' => self::getNoticiaItens($request,$obPagination),
        'status' => $status
      ]);
      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel(TITLE_NOTICIA.' - EMERJ',$content,ROTA_NOTICIA,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização das Conteúdos do Noticia para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
   private static function getNoticiaItens($request,&$obPagination){

     $itens = '';
     $tipodeic = '';
     $idreferenciado = '';

     //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
     $strEditModal = View::render('admin/modules/'.DIR_NOTICIA.'/editmodal',[]);
     $strAddModal = View::render('admin/modules/'.DIR_NOTICIA.'/addmodal',[]);
     $strAtivaModal = View::render('admin/modules/'.DIR_NOTICIA.'/ativamodal',[]);
     $strDeleteModal = View::render('admin/modules/'.DIR_NOTICIA.'/deletemodal',[]);

     //RESULTADO DA PAGINA
     $results = EntityNoticia::getNoticias();

     //MONTA E RENDERIZA OS ITENS DE Noticia
     while($obNoticia = $results->fetchObject(EntityNoticia::class)){
       $itens .= View::render('admin/modules/'.DIR_NOTICIA.'/item',[
        'id' => $obNoticia->noticia_id,
        'noticia_nm' => $obNoticia->noticia_nm,
        'noticia_tipo' => $obNoticia->noticia_tipo ?? '',
        'noticia_titulo' => $obNoticia->noticia_titulo,
        'noticia_imgtemp' => $obNoticia->noticia_imgtemp,
        'noticia_img' => $obNoticia->noticia_img,
        'noticia_imgalt' => $obNoticia->noticia_imgalt,
        'noticia_imgtittle' => $obNoticia->noticia_imgtittle,
        'descricao' => $obNoticia->noticia_descricao ?? '',
        'noticia_icon' => $obNoticia->noticia_icon,
        'noticia_style' => $obNoticia->noticia_style,
        'dt_ini' => $obNoticia->data_inicio,
        'dt_fim' => $obNoticia->data_fim,
        'datainicio' => date('d/m/Y', strtotime($obNoticia->data_inicio)),
        'horainicio' => date('H', strtotime($obNoticia->data_inicio)),
        'mininicio' => date('i', strtotime($obNoticia->data_inicio)),
        'datafim' => date('d/m/Y', strtotime($obNoticia->data_fim)),
        'horafim' => date('H', strtotime($obNoticia->data_fim)),
        'minfim' => date('i', strtotime($obNoticia->data_fim)),
        'texto_ativo' => ('s' == $obNoticia->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obNoticia->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obNoticia->ativo_fl) ? 'table-active' : 'table-danger',
        'id_usuário' => $obNoticia->id_usuario,
        'icon' => ICON_NOTICIA,
        'title' =>TITLE_NOTICIA,
        'titlelow' => TITLELOW_NOTICIA,
        'direntity' => ROTA_NOTICIA
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    $itens .= $strEditModal;
    $itens .= $strAddModal;
    return $itens;
  }



  /**
   * Método responsável por montar a renderização do select de Conteúdos do Noticia para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
   public static function getNoticiaItensSelect($request,$id){
      $itensSelect = '';
      $resultsSelect = EntityNoticia::getNoticias(null,'noticia_id ASC');

      while($obNoticia = $resultsSelect->fetchObject(EntityNoticia::class)){
        $itensSelect .= View::render('admin/modules/'.DIR_NOTICIA.'/itemselect',[
          'idSelect' => $obNoticia->noticia_id,
          'selecionado' => ($id == $obNoticia->noticia_id) ? 'selected' : '',
          'nomeSelect' => $obNoticia->noticia_titulo
        ]);
      }
      return $itensSelect;
    }

    public static function getTransformaData($data)
    {
        [$d, $m, $y] = explode('/', $data);
        return implode('-', [$y, $m, $d]);
    }

    /**
    * Método responsável pela exclusão dos arquivos temporários de upload dos Chamados
    * @param Request $request
    * @return string
    */
    public static function setRemoveUploadAjax($request){

      $id_arquivo = filter_input(INPUT_POST, 'id_arquivo', FILTER_SANITIZE_NUMBER_INT) ?? 0;

      $obArquivo = EntityArquivo::getArquivoPorId($id_arquivo);

      if(!$obArquivo instanceof EntityArquivo){
        echo "Arquivo não existe no banco.";
      }
      $arquivo = __DIR__.'/../../../files/'.ROTA_NOTICIA.'/tmp/'.$obArquivo->arquivo_temp;
      //EXCLUI O USUÁRIO
      $obArquivo->excluir();
      //REDIRECIONA O USUÁRIO
      if(file_exists($arquivo)){
         unlink($arquivo);
       } else {
         echo "Arquivo '.$arquivo.' não existe no diretório.";
       }
    }

    /**
    * Método responsável pela renderização da view de listagem de Chamados
    * @param Request $request
    * @return string
    */
    public static function redimensionaJPG($nome,$tamx,$tamy,$newJpg){

      $imagem_temporaria = imagecreatefromjpeg($nome);

			$largura_original = imagesx($imagem_temporaria);
			$altura_original = imagesy($imagem_temporaria);

			$imagem_redimensionada = imagecreatetruecolor($tamx,$tamy);
			imagecopyresampled($imagem_redimensionada, $imagem_temporaria, 0, 0, 0, 0, $tamx, $tamy, $largura_original, $altura_original);

			imagejpeg($imagem_redimensionada, $newJpg);

    }

  /**
  * Método responsável pela renderização da view de listagem de Chamados
  * @param Request $request
  * @return string
  */
  public static function setUploadAjax($request){

    $strMsnUpload = '';
    $strMsnUploadSucess = '';
    $strMsnUploadFail = '';
    $strMsnUploadError = '';
    if(isset($_FILES['arquivo'])){
      //INSTÂNCIAS DO UPLOAD
      $uploads = Upload::createMultiUpload($_FILES['arquivo']);
      foreach ($uploads as $obUpload) {

        $strIcon = '';
        $strNome = $obUpload->name ?? '';
        $strNomeTmp = $obUpload->tmp_name ?? '';
        $strSize = $obUpload->size ?? '';
        $strExtensao = strtolower($obUpload->extension) ?? '';

        //GERA UM NOME ALEATÓRIO PARA O ARQUIVO
        $obUpload->generateNewName();

        $allowed = array('jpg','jepg','png','gif');
        if(!in_array($strExtensao,$allowed)) {
          $strMsnUploadFail = $strMsnUploadFail.'
            <div class="alert mb-0 pr-2 pl-3 pt-2 pb-2 alert-warning alert-dismissible fade show" role="alert">
              <i class="bi-exclamation-octagon" style="font-size: 1.5rem; margin-right: 8px; vertical-align: bootom; padding-bootom: 0px; color: red;"></i>
               Problemas ao enviar o arquivo <strong>'.$strNome.'.'.$strExtensao.'</strong>. Extensão <strong>'.strtoupper($strExtensao).'</strong> não permitida!
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
              </button>
            </div>';
        } else {
          $allowedIMG = array('jpg','jepg','png','gif');
           if(in_array($strExtensao,$allowedIMG)) {
             $strIcon = 'file-image';
          }
          $strErro = $obUpload->error ?? '';



          //MOVE OS ARQUIVOS DE UPLOAD
          $sucesso = $obUpload->upload(__DIR__.'/../../../files/'.ROTA_NOTICIA.'/tmp',false);

          if($sucesso){

            self::redimensionaJPG(__DIR__.'/../../../files/'.ROTA_NOTICIA.'/tmp/'.$obUpload->name.'.'.$strExtensao, 1150, 600, __DIR__.'/../../../files/'.ROTA_NOTICIA.'/fotos/2021' . $strNome . '.jpg');
            self::redimensionaJPG(__DIR__.'/../../../files/'.ROTA_NOTICIA.'/tmp/'.$obUpload->name.'.'.$strExtensao, 800, 419, __DIR__.'/../../../files/'.ROTA_NOTICIA.'/banner/' . $strNome . '.jpg');
            self::redimensionaJPG(__DIR__.'/../../../files/'.ROTA_NOTICIA.'/tmp/'.$obUpload->name.'.'.$strExtensao, 450, 236, __DIR__.'/../../../files/'.ROTA_NOTICIA.'/principal/' . $strNome . '.jpg');
            self::redimensionaJPG(__DIR__.'/../../../files/'.ROTA_NOTICIA.'/tmp/'.$obUpload->name.'.'.$strExtensao, 350, 183, __DIR__.'/../../../files/'.ROTA_NOTICIA.'/capa/' . $strNome . '.jpg');


            //NOVA ISNTANCIA DE CHAMADO
            $obArquivo = new EntityArquivo;
            ////$obChamado::getChamadoPorEmail($posVars['email']);
            $obArquivo->arquivo_nm = $strNome;
            $obArquivo->arquivo_temp = $obUpload->getBasename();
            $obArquivo->arquivo_tam = $strSize;
            $obArquivo->arquivo_type = $strExtensao;
            $obArquivo->arquivo_icon = $strIcon;
            $obArquivo->id_usuario = $_SESSION['admin']['usuario']['usuario_id'];
            $obArquivo->id_sessao = $_SESSION['admin']['usuario']['fileschamado'];
            $obArquivo->cadastrar();

            $strMsnUploadSucess = $strMsnUploadSucess.'
                   <div class="p-1 bg-light border" id="upload_arquivo_'.$obArquivo->arquivo_id.'">
                     <i class="bi-'.$strIcon.'" style="font-size: 1.5rem; margin: 8px; vertical-align: bootom; padding-bootom: 0px; color: cornflowerblue;"></i>
                     Arquivo <strong>'.$strNome.'.'.$strExtensao.'</strong> carregado com sucesso!
                     <a tabindex="0" id="'.$obArquivo->arquivo_id.'" onclick="file_remove('.$obArquivo->arquivo_id.');" class="btn btn-sm btn-danger float-sm-right role="button" title="Id='.$obArquivo->arquivo_id.'Clique para remover o arquivo '.$strNome.'.'.$strExtensao.' da sua lista de upload.">X</a>
                   </div>';

            continue;
          }else{
            $strMsnUploadError = $strMsnUploadError.'Problemas ao enviar o(s) arquivo(s) <strong>Erro: '.$strErro.'<br>';
          }
          echo $strMsnUploadError;
          exit;
        }
      }
      echo $strMsnUploadSucess.$strMsnUploadFail;
    }
  }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo Chamado
     * @param Request $request
     * @return string
     */
     public static function getNovaNoticia($request){

       $status = '';
       $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
       $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

       //CONTEÚDO DA NOTÍCIA
       $content = View::render('admin/modules/'.DIR_NOTICIA.'/form',[
         'icon' => ICON_NOTICIA,
         'title' =>TITLE_NOTICIA,
         'titlelow' => TITLELOW_NOTICIA,
         'direntity' => ROTA_NOTICIA,
         'itens' => self::getNoticiaItens($request,$obPagination),
         'noticia_nm' => 'Notícia DECOM',
         'noticia_tipo' => 2,
         'noticia_titulo' => '',
         'noticia_imgalt' => '',
         'noticia_imgtittle' => '',
         'descricao' => '',
         'datainicio' => '',
         'horainicio' => '',
         'mininicio' => '',
         'datafim' => '',
         'horafim' => '',
         'minfim' => '',
         'id_usuario' => $_SESSION['admin']['usuario']['usuario_id'] ?? '',
         'status' => $status
       ]);

       //RETORNA A PÁGINA COMPLETA
       return parent::getPanel('Cadastrar Notícia - EMERJ',$content,'noticias',$currentDepartamento,$currentPerfil);
     }

   /**
    * Método responsável por cadastro de uma nova Localização no banco
    * @param Request $request
    * @return string
    */
    public static function setNovaNoticia($request){

      date_default_timezone_set('America/Sao_Paulo');

      if (isset($_FILES['noticiaUpload'])) {

        //INSTÂNCIAS DO UPLOAD
        $uploads = Upload::createMultiUpload($_FILES['noticiaUpload']);

        foreach ($uploads as $obUpload) {

          $strNome = $obUpload->name ?? '';
          $strNomeTmp = $obUpload->tmp_name ?? '';
          $strSize = $obUpload->size ?? '';
          $strExtensao = strtolower($obUpload->extension) ?? '';

          //GERA UM NOME ALEATÓRIO PARA O ARQUIVO
          $obUpload->generateNewName();

          //MOVE OS ARQUIVOS DE UPLOAD
          $sucesso = $obUpload->upload(__DIR__.'/../../../files/'.ROTA_NOTICIA.'/originais',false);
          $strNomeImg = $obUpload->getBasename();

        }
      }

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $noticia_titulo = $posVars['noticia_titulo'];
      $noticia_imgalt = filter_input(INPUT_POST, 'noticia_imgalt', FILTER_SANITIZE_STRING);
      $noticia_imgtittle = filter_input(INPUT_POST, 'noticia_imgtittle', FILTER_SANITIZE_STRING);
      $descricao = $posVars['descricao'];
      $datainicio = filter_input(INPUT_POST, 'datainicio', FILTER_SANITIZE_STRING);
      $horainicio = filter_input(INPUT_POST, 'horainicio', FILTER_SANITIZE_STRING);
      $mininicio = filter_input(INPUT_POST, 'mininicio', FILTER_SANITIZE_STRING);
      $datafim = filter_input(INPUT_POST, 'datafim', FILTER_SANITIZE_STRING);
      $horafim = filter_input(INPUT_POST, 'horafim', FILTER_SANITIZE_STRING);
      $minfim = filter_input(INPUT_POST, 'minfim', FILTER_SANITIZE_STRING);
      $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_NUMBER_INT);

      $data_inicio = self::getTransformaData($datainicio).' '.$horainicio.':'.$mininicio.':00';
      $data_fim = self::getTransformaData($datafim).' '.$horafim.':'.$minfim.':00';

      $agora = date("Y-m-d H:i:s");
      $agoraDiff = new DateTime($agora);
      $data_inicioDiff = new DateTime($data_inicio);
      $data_fimDiff = new DateTime($data_fim);

      $intervalo1 = $data_inicioDiff->diff($agoraDiff);

      if ($agoraDiff >= $data_inicioDiff) {
        $request->getRouter()->redirect('/admin/noticias?status=updatefaildateAgora');
      }

      $intervalo2 = $data_fimDiff->diff($data_inicioDiff);

      if (($intervalo2->d < 1) || ($data_fimDiff <= $data_inicioDiff)) {
        $request->getRouter()->redirect('/admin/noticias?status=updatefaildatediff');
      }
      //NOVA ISNTANCIA DE DEPARTAMENTO
      $obNoticia = new EntityNoticia;

      $obNoticia->noticia_nm = $nome;
      $obNoticia->noticia_tipo = $tipo;
      $obNoticia->noticia_titulo = html_entity_decode($noticia_titulo);
      $obNoticia->noticia_imgtemp = $strNome;
      $obNoticia->noticia_img = $strNomeImg;
      $obNoticia->noticia_imgalt = $noticia_imgalt;
      $obNoticia->noticia_imgtittle = $noticia_imgtittle;
      $obNoticia->noticia_descricao = html_entity_decode($descricao);
      $obNoticia->data_inicio = $data_inicio;
      $obNoticia->data_fim = $data_fim;
      $obNoticia->id_usuario = $id_usuario;
      $obNoticia->cadastrar();

      $idNoticia = $obNoticia->noticia_id;

      if($sucesso){

        $dirOrigem = __DIR__.'/../../../files/'.ROTA_NOTICIA.'/originais';
        $dirImages = __DIR__.'/../../../files/'.ROTA_NOTICIA.'/images';

        $imageUploaded = $dirOrigem.'/'.$strNomeImg;

        self::redimensionaJPG($imageUploaded, 1150, 600, $dirImages.'/1150_'.$idNoticia.'_'.$strNomeImg);
        self::redimensionaJPG($imageUploaded, 800, 419, $dirImages.'/800_'.$idNoticia.'_'.$strNomeImg);
        self::redimensionaJPG($imageUploaded, 450, 236, $dirImages.'/450_'.$idNoticia.'_'.$strNomeImg);
        self::redimensionaJPG($imageUploaded, 350, 183, $dirImages.'/350_'.$idNoticia.'_'.$strNomeImg);
      }

      //REDIRECIONA O DEPARTAMENTO
      $request->getRouter()->redirect('/admin/noticias?status=gravado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por gravar a edição de uma Localização
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getEditNoticia($request,$id){

       $status = '';
       $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
       $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

        $obNoticia = EntityNoticia::getNoticiaPorId($id);

       //CONTEÚDO DA NOTÍCIA
       $content = View::render('admin/modules/'.DIR_NOTICIA.'/form',[
         'icon' => ICON_NOTICIA,
         'title' => 'Alterar '.TITLE_NOTICIA,
         'titlelow' => TITLELOW_NOTICIA,
         'direntity' => ROTA_NOTICIA,
         'itens' => self::getNoticiaItens($request,$obPagination),
         'noticia_id' => $obNoticia->noticia_id ?? '',
         'noticia_nm' => $obNoticia->noticia_nm ?? '',
         'noticia_tipo' => $obNoticia->noticia_tipo ?? '',
         'noticia_titulo' => $obNoticia->noticia_titulo ?? '',
         'noticia_img' => '450_'.$obNoticia->noticia_id.'_'.$obNoticia->noticia_img ?? '',
         'noticia_imgalt' => $obNoticia->noticia_imgalt ?? '',
         'noticia_imgtittle' => $obNoticia->noticia_imgtittle ?? '',
         'descricao' => $obNoticia->noticia_descricao ?? '',
         'datainicio' => date('d/m/Y', strtotime($obNoticia->data_inicio)),
         'horainicio' => date('H', strtotime($obNoticia->data_inicio)),
         'mininicio' => date('i', strtotime($obNoticia->data_inicio)),
         'datafim' => date('d/m/Y', strtotime($obNoticia->data_fim)),
         'horafim' => $horafim ?? '',
         'minfim' => $minfim ?? '',
         'id_usuario' => $obNoticia->id_usuario ?? '',
         'status' => $status
       ]);

       return parent::getPanel('Alterar Notícia - EMERJ',$content,'noticias',$currentDepartamento,$currentPerfil);


     }

   /**
    * Método responsável por gravar a edição de uma Localização
    * @param Request $request
    * @param integer $id
    * @return string
    */
    public static function setEditNoticia($request,$id){

      date_default_timezone_set('America/Sao_Paulo');
      $strNomeImg = '';

      if (isset($_FILES['noticiaUpload'])) {

        //INSTÂNCIAS DO UPLOAD
        $uploads = Upload::createMultiUpload($_FILES['noticiaUpload']);

        foreach ($uploads as $obUpload) {

          $strNome = $obUpload->name ?? '';
          $strNomeTmp = $obUpload->tmp_name ?? '';
          $strSize = $obUpload->size ?? '';
          $strExtensao = strtolower($obUpload->extension) ?? '';

          //GERA UM NOME ALEATÓRIO PARA O ARQUIVO
          $obUpload->generateNewName();

          //MOVE OS ARQUIVOS DE UPLOAD
          $sucesso = $obUpload->upload(__DIR__.'/../../../files/'.ROTA_NOTICIA.'/originais',false);
          $strNomeImg = $obUpload->getBasename();

        }
      }

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
      $noticia_titulo = filter_input(INPUT_POST, 'noticia_titulo', FILTER_SANITIZE_STRING);
      $noticia_imgalt = filter_input(INPUT_POST, 'noticia_imgalt', FILTER_SANITIZE_STRING);
      $noticia_imgtittle = filter_input(INPUT_POST, 'noticia_imgtittle', FILTER_SANITIZE_STRING);
      $descricao = $posVars['descricao'];
      $datainicio = filter_input(INPUT_POST, 'datainicio', FILTER_SANITIZE_STRING);
      $horainicio = filter_input(INPUT_POST, 'horainicio', FILTER_SANITIZE_STRING);
      $mininicio = filter_input(INPUT_POST, 'mininicio', FILTER_SANITIZE_STRING);
      $datafim = filter_input(INPUT_POST, 'datafim', FILTER_SANITIZE_STRING);
      $horafim = filter_input(INPUT_POST, 'horafim', FILTER_SANITIZE_STRING);
      $minfim = filter_input(INPUT_POST, 'minfim', FILTER_SANITIZE_STRING);
      $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_NUMBER_INT);

      $data_inicio = self::getTransformaData($datainicio).' '.$horainicio.':'.$mininicio.':00';
      $data_fim = self::getTransformaData($datafim).' '.$horafim.':'.$minfim.':00';

      $agora = date("Y-m-d H:i:s");
      $agoraDiff = new DateTime($agora);
      $data_inicioDiff = new DateTime($data_inicio);
      $data_fimDiff = new DateTime($data_fim);

      $intervalo1 = $data_inicioDiff->diff($agoraDiff);

      if ($agoraDiff >= $data_inicioDiff) {
        //$request->getRouter()->redirect('/admin/noticias?status=updatefaildateAgora');
      }

      $intervalo2 = $data_fimDiff->diff($data_inicioDiff);

      if (($intervalo2->d < 1) || ($data_fimDiff <= $data_inicioDiff)) {
        //$request->getRouter()->redirect('/admin/noticias?status=updatefaildatediff');
      }

      //OBTÉM O USUÁRIO DO BANCO DE DADOS
      $obNoticia = EntityNoticia::getNoticiaPorId($id);

      if(!$obNoticia instanceof EntityNoticia){
        $request->getRouter()->redirect('/admin/noticias?status=updatefail');
      }

      //ATUALIZA A INSTANCIA
      $obNoticia->noticia_id = $id;
      $obNoticia->noticia_nm = $nome;
      $obNoticia->noticia_tipo = $tipo;
      $obNoticia->noticia_titulo = $noticia_titulo;
      if ($strSize > 0) {
        $obNoticia->noticia_imgtemp = $strNome;
        $obNoticia->noticia_img = $strNomeImg;
      }
      $obNoticia->noticia_imgalt = $noticia_imgalt;
      $obNoticia->noticia_imgtittle = $noticia_imgtittle;
      $obNoticia->noticia_descricao = html_entity_decode($descricao);
      $obNoticia->data_inicio = $data_inicio;
      $obNoticia->data_fim = $data_fim;
      $obNoticia->id_usuario = $id_usuario;
      $obNoticia->atualizar();

      if($sucesso){

        $dirOrigem = __DIR__.'/../../../files/'.ROTA_NOTICIA.'/originais';
        $dirImages = __DIR__.'/../../../files/'.ROTA_NOTICIA.'/images';

        $imageUploaded = $dirOrigem.'/'.$strNomeImg;

        self::redimensionaJPG($imageUploaded, 1150, 600, $dirImages.'/1150_'.$id.'_'.$strNomeImg);
        self::redimensionaJPG($imageUploaded, 800, 419, $dirImages.'/800_'.$id.'_'.$strNomeImg);
        self::redimensionaJPG($imageUploaded, 450, 236, $dirImages.'/450_'.$id.'_'.$strNomeImg);
        self::redimensionaJPG($imageUploaded, 350, 183, $dirImages.'/350_'.$id.'_'.$strNomeImg);
      }

      //REDIRECIONA O USUÁRIO PARA A PAGINA INICIAL DE LISTAR CATEGORIAS DE IC's
      $request->getRouter()->redirect('/admin/noticias?status=alterado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por retornar o formulário de alteração de status de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getAltStatusNoticia($request,$id){

       //OBTÉM A CATEGORIA DO IC PELO SEU ID DO BANCO DE DADOS
       $obNoticia = EntityNoticia::getNoticiaPorId($id);
       $strNome = $obNoticia->noticia_nm;



       if(!$obNoticia instanceof EntityNoticia){
         $request->getRouter()->redirect('/admin/noticias?status=updatefail');
       }

       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       if($obNoticia->ativo_fl == 's'){
         $altStatus = 'n';
         $strMsn = ' DESATIVADO ';
       } elseif (($obNoticia->ativo_fl == 'n') || ($obNoticia->ativo_fl == '')) {
         $strMsn = ' ATIVADO ';
         $altStatus = 's';
       }

       //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
       $obNoticia->ativo_fl = $altStatus;
       $obNoticia->atualizar();

       //REDIRECIONA O USUÁRIO
       $request->getRouter()->redirect('/admin/noticias?status=statusupdate&nm='.$strNome.$strMsn);

     }

   /**
      * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getDeleteNoticia($request,$id){

       //OBTÉM O LOCALIZAÇÃO DO BANCO DE DADOS
        $obNoticia = EntityNoticia::getNoticiaPorId($id);
        $strNome = $obNoticia->noticia_nm;

        if(!$obNoticia instanceof EntityNoticia){
          $request->getRouter()->redirect('/admin/noticias');
        }

       //EXCLUI O USUÁRIO
        $obNoticia->excluir();
        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/noticias?status=deletado&nm='.$strNome.$strMsn.'&acao=alter');
      }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
     */
     private static function getStatus($request){
      //QUERY PARAMS
      $queryParams = $request->getQueryParams();
      $nm = filter_input(INPUT_GET, 'nm', FILTER_SANITIZE_STRING) ?? '';
      $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);

      //STATUS
      if(!isset($status)) return '';

     //MENSAGENS DE STATUS
     switch ($status) {
       case 'gravado':
         return Alert::getSuccess('Dados d'.TITLE_NOTICIA.' <strong>'.$nm.'</strong> cadastrados com sucesso!');
         // code...
         break;
       case 'alterado':
         return Alert::getSuccess('Dados d'.TITLELOW_NOTICIA.' <strong>'.$nm.'</strong> alterados com sucesso!');
         // code...
         break;
       case 'deletado':
         return Alert::getSuccess('Registro d'.TITLE_NOTICIA.'  <strong>'.$nm.'</strong> deletado com sucesso!');
         // code...
         break;
       case 'updatefaildatediff':
         return Alert::getError('A diferença entre as datas fim e a início não pode ser inferior a um dia!');
         // code...
         break;
       case 'updatefaildateAgora':
         return Alert::getError('A data de início não pode ser anterior a data/hora de agora!');
         // code...
         break;

       case 'duplicado':
         return Alert::getError('Já existe '.TITLELOW_NOTICIA.' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_NOTICIA.'!');
         // code...
         break;
       case 'statusupdate':
         return Alert::getSuccess('Status d'.TITLELOW_NOTICIA.' <strong>'.$nm.'</strong> com sucesso!');
         // code...
         break;
     }
   }
}
