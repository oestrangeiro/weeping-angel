<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\MachineModel;
use App\Traits\Validate;

// Controler responsável pela validação do login

class LoginController extends BaseController {

    /*
        USUARIOS PARA TESTE:
            ADMIN: 
                07831567313,
                2004
            
            USUARIO-COMUM:
                07831596330,
                091004
    
    */

    // ATRIBUTOS
    private $login;
    private $password;
    private $role;

    // MODELS PARA INJEÇÃO
    private $userModel;
    private $machineModel;

    // TRAITS
    use Validate;

    // METOFDOS
    public function __construct(){
        $this->userModel = new UsersModel();
    }

    public function login(){

        $this->login    = $this->request->getPost('user-login')     ?? null;
        $this->password = $this->request->getPost('user-password')  ?? null;

        $isThereSomeValueEmpty = $this->isSomeValueNull([$this->login, $this->password]);

        if($isThereSomeValueEmpty){
            return redirect()->to('/')->with(
                'error_message', 'Um ou mais campos vazios!'
            );
        }

        // CPF
        $userLoginCPFSanitized  = $this->sanitizeCPF($this->login);
        $isThisCPFValid         = $this->isAValidCPF($userLoginCPFSanitized);

        if(!$isThisCPFValid){
            return redirect()->back()->with(
                'error_message', 'O CPF informado é inválido, tente novamente!'
            );
        }
        // Se o CPF for válido, atribuo o valor (sem ponto, traços etc) ao atributo 'login'
        $this->login = $userLoginCPFSanitized;

        // Checando se existe um usuário com esse login (CPF)
        $userAlreadyExistWithThatLogin = $this->userModel->checksIfUserAlreadyExists($this->login);

        // Caso o login não exista
        if(!$userAlreadyExistWithThatLogin){
            return redirect()->to('/')->with(
                'error_message', 'Credenciais incorretas!'
            );
        }

        // Checando se as senhas batem
        $passwordEnc = $this->userModel->getPasswordEnc($this->login);

        // dd($passwordEnc);
        $passwordsMatches = password_verify($this->password, $passwordEnc);

        if(!$passwordsMatches){
            return redirect()->to('/')->with(
                'error_message', 'Credenciais incorretas!'
            );
        }

        $userName = $this->userModel->getUserName($this->login);
        $this->role = $this->userModel->getUserRole($this->login);

        $session = session();
        $session->set(['cpf' => $this->login]);
        $session->set(['loggedIn' => true]);
        $session->set(['userRole' => $this->role]);

        return redirect()->to('/dashboard')->with(
            'success_message', "'{$userName}' logado com sucesso!"
        );
    }

    public function logOut(){

        if(session()->get('loggedIn')){
            session()->remove('loggedIn');
        }

        $session = session();
        $cpf = $session->get('cpf');
        // dd($cpf);

        $userName = $this->userModel->getUserName($cpf);

        return redirect()->to('/')->with(
            'success_message', "Usuário {$userName} deslogado com sucesso!"
        );
    }
}
