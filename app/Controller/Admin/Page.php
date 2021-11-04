<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Page{


  /**
   * Método responsável por renderizar o topo da nossa página genérica
   * @param string
   */
  public static function getHeader(){
    //return 'Olá Mundo';
    return View::render('admin/header');
  }

  /**
   * Método responsável por renderizar o rodapé da nossa página genérica
   * @param string
   */
  public static function getFooter(){
    //return 'Olá Mundo';
    return View::render('admin/footer');
  }

  /**
   * [getPaginationLink description]
   * @param  array $queryParams
   * @param  array $page
   * @param  string $url
   * @return [type]
   */
    private static function getPaginationLink($queryParams,$page,$url,$label = null){

      $acao = $queryParams['acao'] ?? '';
      //ALTERA A PAGINA
      $queryParams['pagina'] = $page['pagina'];

      //LINK
      if ($acao == 'pg') {
        $link = $url.'?'.http_build_query($queryParams);
      } else {
        $link = $url.'?'.http_build_query($queryParams).'&acao=pg';
      }

      //VIEW
      return View::render('pages/pagination/link',[
        'pagina' => $label ?? $page['pagina'],
        'link' => $link,
        'active' => $page['atual'] ? 'active' : ''
      ]);

    }

  /**
   * Módulos disponíveis no painel (AQUI FICA O TEXO DO MENU)
   * @var array $currentModule
   * @return string
   */
  private static function getModules($currentDepartamento,$currentPerfil) {
      if (($currentDepartamento == 'DETEC') or ($currentPerfil == 1)) {
        return [
          'home' => [
            'label' => 'Home',
            'link' => URL.'/',
            'dropdown' => '',
            'dropdown-toggle' => '',
            'navbarMenuLink' => '',
            'data-toggle' => ''
          ],
          'departamentos' => [
            'label' => 'Departamentos',
            'link' => URL.'/admin/departamentos',
            'dropdown' => '',
            'dropdown-toggle' => '',
            'navbarMenuLink' => '',
            'data-toggle' => ''
          ],
          'usuarios' => [
            'label' => 'Usuários',
            'link' => URL.'/admin/usuarios',
            'dropdown' => '',
            'dropdown-toggle' => '',
            'navbarMenuLink' => '',
            'data-toggle' => ''
          ],
          'chamados' => [
            'label' => 'Chamados',
            'link' => URL.'/admin/chamados',
            'dropdown' => '',
            'dropdown-toggle' => '',
            'navbarMenuLink' => '',
            'data-toggle' => ''
          ],
          'servicos' => [
            'label' => 'Serviços',
            'link' => '#',
            'dropdown' => 'dropdown',
            'dropdown-toggle' => 'dropdown-toggle',
            'navbarMenuLink' => 'navbarDropdownMenuLinkServicos',
            'data-toggle' => 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"'
          ],
          'atendimentos' => [
            'label' => 'Atendimentos',
            'link' => URL.'/admin/atendimentos',
            'dropdown' => '',
            'dropdown-toggle' => '',
            'navbarMenuLink' => '',
            'data-toggle' => ''
          ],
          'itensconfs' => [
            'label' => 'ICs',
            'link' => '#',
            'dropdown' => 'dropdown',
            'dropdown-toggle' => 'dropdown-toggle',
            'navbarMenuLink' => 'navbarDropdownMenuLinkICs',
            'data-toggle' => 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"'
          ],
          'localizacoes' => [
            'label' => 'Localizações',
            'link' => URL.'/admin/localizacoes',
            'dropdown' => '',
            'dropdown-toggle' => '',
            'navbarMenuLink' => '',
            'data-toggle' => ''
          ],
          'novosite' => [
            'label' => 'Novo Site',
            'link' => URL.'/admin/localizacoes',
            'dropdown' => 'dropdown',
            'dropdown-toggle' => 'dropdown-toggle',
            'navbarMenuLink' => 'navbarDropdownMenuLinkNovoSite',
            'data-toggle' => 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"'
          ]
        ];
      } elseif ($currentDepartamento == 'DECOM') {
        return [
          'home' => [
            'label' => 'Home',
            'link' => URL.'/',
            'dropdown' => '',
            'dropdown-toggle' => '',
            'navbarMenuLink' => '',
            'data-toggle' => ''
          ],
          'servicos' => [
            'label' => 'Serviços DECOM',
            'link' => '#',
            'dropdown' => 'dropdown',
            'dropdown-toggle' => 'dropdown-toggle',
            'navbarMenuLink' => 'navbarDropdownMenuLinkServicos',
            'data-toggle' => 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"'
          ],
          'noticias' => [
            'label' => 'Notícias',
            'link' => URL.'/admin/noticias',
            'dropdown' => 'dropdown',
            'dropdown-toggle' => 'dropdown-toggle',
            'navbarMenuLink' => 'navbarDropdownMenuLinkNoticias',
            'data-toggle' => 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"'
          ]
        ];
      } elseif ($currentDepartamento == 'DEDES') {
        return [
          'home' => [
            'label' => 'Home',
            'link' => URL.'/',
            'dropdown' => '',
            'dropdown-toggle' => '',
            'navbarMenuLink' => '',
            'data-toggle' => ''
          ],
          'servicos' => [
            'label' => 'Serviços DEDES',
            'link' => '#',
            'dropdown' => 'dropdown',
            'dropdown-toggle' => 'dropdown-toggle',
            'navbarMenuLink' => 'navbarDropdownMenuLinkServicos',
            'data-toggle' => 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"'
          ]
        ];
      } elseif ($currentDepartamento == 'DEADM') {
        return [
          'home' => [
            'label' => 'Home',
            'link' => URL.'/',
            'dropdown' => '',
            'dropdown-toggle' => '',
            'navbarMenuLink' => '',
            'data-toggle' => ''
          ],
          'servicos' => [
            'label' => 'Serviços DEADM',
            'link' => '#',
            'dropdown' => 'dropdown',
            'dropdown-toggle' => 'dropdown-toggle',
            'navbarMenuLink' => 'navbarDropdownMenuLinkServicos',
            'data-toggle' => 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"'
          ]
        ];
      } else {
        return [
          'home' => [
            'label' => 'Home',
            'link' => URL.'/',
            'dropdown' => '',
            'dropdown-toggle' => '',
            'navbarMenuLink' => '',
            'data-toggle' => ''
          ],
          'servicos' => [
            'label' => 'Serviços '.$currentDepartamento,
            'link' => '#',
            'dropdown' => 'dropdown',
            'dropdown-toggle' => 'dropdown-toggle',
            'navbarMenuLink' => 'navbarDropdownMenuLinkServicos',
            'data-toggle' => 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"'
          ]
        ];
      }
    }


  /**
   * Módulos disponíveis no painel (AQUI FICA O TEXO DO MENU)
   * @var array $currentModule
   * @return string
   */
  private static function getDropdownmodules($currentDepartamento,$currentPerfil) {
      if (($currentDepartamento == 'DETEC') or ($currentPerfil == 1)) {
        return [
          'servicos' => [
          'dropdownlabel' => 'Serviços',
          'dropdownlink' => URL.'/admin/servicos',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkServicos',
        ],
        'itensconfs' => [
          'dropdownlabel' => 'ICs',
          'dropdownlink' => URL.'/admin/itensconfs',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkICs',
        ],
        'tipodeics' => [
          'dropdownlabel' => 'Tipos de IC´s',
          'dropdownlink' => URL.'/admin/tipodeics',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkICs',
        ],
        'tipodeservicos' => [
          'dropdownlabel' => 'Tipos de Serviços',
          'dropdownlink' => URL.'/admin/tipodeservicos',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkServicos',
        ],
        'categoriadeics' => [
          'dropdownlabel' => 'Categorias de ICs',
          'dropdownlink' => URL.'/admin/categoriadeics',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkICs',
        ],
        'noticias' => [
          'dropdownlabel' => 'Notícias',
          'dropdownlink' => URL.'/admin/noticias',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkNovoSite',
        ],
        'paginas' => [
          'dropdownlabel' => 'Páginas',
          'dropdownlink' => URL.'/admin/paginas',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkNovoSite',
        ],
        'sessoes' => [
          'dropdownlabel' => 'Sessões',
          'dropdownlink' => URL.'/admin/sessoes',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkNovoSite',
        ],
        'subsessoes' => [
          'dropdownlabel' => 'Subsessões',
          'dropdownlink' => URL.'/admin/subsessoes',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkNovoSite',
        ]
      ];
    } elseif ($currentDepartamento == 'DECOM') {
      return [
        'cadastra' => [
          'dropdownlabel' => 'Conteúdo Instagram',
          'dropdownlink' => URL.'/admin/decoms',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkServicos',
        ],
        'visualiza' => [
          'dropdownlabel' => 'Ver Linktree',
          'dropdownlink' => URL.'/decom',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkServicos',
        ],
        'noticias' => [
          'dropdownlabel' => 'Notícias',
          'dropdownlink' => URL.'/admin/noticias',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkNoticias',
        ]
      ];
    } elseif ($currentDepartamento == 'DEDES') {
      return [
        'servicos' => [
          'dropdownlabel' => 'Serviços DEDES',
          'dropdownlink' => URL.'/admin/chamados',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkServicos',
        ]
      ];
    } elseif ($currentDepartamento == 'DEADM') {
      return [
        'servicos' => [
          'dropdownlabel' => 'Gestão de Usuários',
          'dropdownlink' => URL.'/admin/usuarios',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkServicos',
        ]
      ];
    } else {
      return [
        'servicos' => [
          'dropdownlabel' => 'Abrir chamado',
          'dropdownlink' => URL.'/admin/chamados/novo',
          'navbarDropdownMenuLink' => 'navbarDropdownMenuLinkServicos',
        ]
      ];
    }
  }


  /**
   * Método responsável por retornar o conteúdo (view) da estrutura genérica de página do painel
   * @param string $title
   * @param string $content
   * @return string
   */
  public static function getPage($title,$content,$currentModule,$currentDepartamento,$currentPerfil){

    return View::render('admin/page',[
      'titulo' => $title,
      'header' => self::getHeader(),
      'content' => $content,
      'footer' => self::getFooter(),
      'menu' => self::getMenu($currentModule,$currentDepartamento,$currentPerfil)
    ]);
  }


  /**
   * Método responsável por renderizar a view do painel com coteúdo dinâmico
   * @param string $currentModule
   * @return string
   */
  private static function getMenudropdown($currentModule,$navbarMenuLink,$currentDepartamento,$currentPerfil){
    //LINKS DO menu
    $dropdownlinks = '';

    //ITERA OS MÓDULOS
    foreach (self::getDropdownmodules($currentDepartamento,$currentPerfil) as $hash=>$dropdownmodule) {
      if ($navbarMenuLink == $dropdownmodule['navbarDropdownMenuLink']) {
      $dropdownlinks .= View::render('admin/menu/dropdownitem',[
        'dropdownlabel' => $dropdownmodule['dropdownlabel'],
        'dropdownlink' => $dropdownmodule['dropdownlink']
      ]);
    }
      // code...
    }
    //RENDERIZA A RENDERIZAÇÃO DO DROPDOW
    return View::render('admin/menu/dropdownbox',[
      'navbarDropdownMenuLink' => $navbarMenuLink,
      'dropdownitens' => $dropdownlinks
    ]);
  }

  /**
   * Método responsável por renderizar a view do painel com coteúdo dinâmico
   * @param string $currentModule
   * @return string
   */
  private static function getMenu($currentModule,$currentDepartamento,$currentPerfil){
    //LINKS DO menu
    $links = '';

    //ITERA OS MÓDULOS
    foreach (self::getModules($currentDepartamento,$currentPerfil) as $hash=>$module) {
      $links .= View::render('admin/menu/link',[
        'label' => $module['label'],
        'link' => $module['link'],
        'current' => $hash == $currentModule ? 'text-primary' : '',
        'dropdown'=> $module['dropdown'],
        'dropdown-toggle'=> $module['dropdown-toggle'],
        'navbarMenuLink'=> $module['navbarMenuLink'],
        'dropdownbox' => ($module['dropdown'] == 'dropdown') ? self::getMenudropdown($currentModule,$module['navbarMenuLink'],$currentDepartamento,$currentPerfil) : '',
        'data-toggle' => $module['data-toggle']
      ]);
      // code...
    }
    //RENDERIZA A RENDERIZAÇÃO DO MENU
    return View::render('admin/menu/box',[
      'links' => $links
    ]);
  }

  /**
   * Método responsável por renderizar a view do painel com coteúdo dinâmico
   * @param string $title
   * @param string $content
   * @param string $currentModule
   * @return string
   */
  public static function getPanel($title,$content,$currentModule,$currentDepartamento,$currentPerfil){
    //RENDERIZA A VIEW DO PAINEL
    $contentPanel = View::render('admin/panel',[
    'titulo' => $title,
    'header' => self::getHeader(),
    'footer' => self::getFooter(),
    'content' => $content,
    'menu' => self::getMenu($currentModule,$currentDepartamento,$currentPerfil)
    ]);

    //RETORNA A PAGINA RENDERIZADA
    return self::getPage($title,$contentPanel,$currentModule,$currentDepartamento,$currentPerfil);
  }


  /**
   * Método responsável por renderizar o layout de paginação
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  public static function getPagination($request,$obPagination){
    //OBTER AS PÁGINAS
    $pages = $obPagination->getPages();

    //VERIFICA A QUANTIDADE DE PÁGINAS
    if(count($pages) <= 1) return '';

    //LINKS
    $links = '';

    //URL ATUAL (SEM GETS)
    $url = $request->getRouter()->getCurrentUrl();

    //GET
    $queryParams = $request->getQueryParams();

    //PÁGINA ATUAL
    $currentPage = $queryParams['pagina'] ?? 1;

    //LIMITE DE PÁGINAS
    $limit = getenv('PAGINATION_LIMIT');

    //MEIO DE PAGINAÇÃO
    $middle = ceil($limit/2);

    //INÍCIO DA PAGINAÇÃO
    $start = $middle > $currentPage ? 0 : $currentPage - $middle;

    //AJUSTA O FINAL DA PAGINAÇÃO
    $limit = $limit + $start;

    $primeirapagina = "<i class='bi-caret-left-fill' style='font-size: 1rem;'></i>";

    //LINK INICIAL
    if($start > 0){
      $links .= self::getPaginationLink($queryParams,reset($pages),$url,$primeirapagina);
    }

    //RENDERIZA OS LINKS
    foreach ($pages as $page) {
      //VERIFICA O START DA PAGINAÇÃO
      if($page['pagina'] <= $start) continue;

      $ultimapagina = "<i class='bi-caret-right-fill' style='font-size: 1rem;'></i>";

      //VERIFICA O LIMITE DE PAGINAÇÃO
      if($page['pagina'] > $limit){
        $links .= self::getPaginationLink($queryParams,end($pages),$url,$ultimapagina);
        break;
      }

      //AJUSTA O INÍCIO DA PAGINAÇÃO
      if($limit > count($page)){
        $diff = $limit - count($page);
        $start = $start - $diff;
      }

      $links .= self::getPaginationLink($queryParams,$page,$url);

    }
    //RENDERIZA BOX DE PAGINAÇÃO
    return View::render('admin/pagination/box',[
      'links' => $links
    ]);

  }
}
