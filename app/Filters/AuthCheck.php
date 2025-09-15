<?php 

namespace App\Filters;

/*
    Filtro para checar se um usuário possui privilégios suficientes para acessar uma ou mais determinadas rotas
*/

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class AuthCheck implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null){

        // Se o cara não tiver logado, redireciono ele para uma página de erro genérica

        if(!session()->get('loggedIn')){
            return redirect()->to('/')->with(
                'error_message', 'Você deve estar logado para acessar essa página!'
            );
        }

        // Caso o usuário precise do cargo de admin para acessar uma rota
        // redireciono ele para uma página generica de erro

        $userRole = session()->get('userRole');
        
        if($arguments && !in_array($userRole, $arguments)){
            return redirect()->to('error/permission-denied');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        // Não preciso fazer nada depois da requisição
    }
}