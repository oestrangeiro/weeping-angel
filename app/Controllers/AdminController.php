<?php 
namespace App\Controllers;

use App\Models\UsersModel;
// Trait pessoal e modular para validação e sanitização de dados
use App\Traits\Validate;

class AdminController extends BaseController {

    // ATRIBUTOS
    private $userName;
    private $userCPF;
    private $userEmail;
    private $userPassword;
    private $userRole;

    // Models
    private $userModel;
    
    // Controller

    use Validate;

    public function __construct(){
        $this->userModel =  new UsersModel();
    }

    // MÉTODOS

    // Método para adicionar um usuário
    public function addUser(){

        $userName       = $this->request->getPost('user-name')      ?? null;
        $userCPF        = $this->request->getPost('user-cpf')       ?? null;
        $userEmail      = $this->request->getPost('user-email')     ?? null;
        $userPassword   = $this->request->getPost('user-password')  ?? null;
        $userRole       = $this->request->getPost('user-role')      ?? null;

        // dd(
        //     [
        //         $userName,
        //         $userEmail,
        //         $userCPF,
        //         $userPassword,
        //         $userRole
        //     ]
        // );

        // Verificando se há algum campo vazio
        $isThereSomeFieldEmpty = $this->isSomeValueNull([$userName, $userEmail, $userCPF, $userPassword, $userRole]);

        // dd($isThereSomeFieldEmpty);

        if($isThereSomeFieldEmpty){
            return redirect()->back()->with(
                'error_message', 'Um ou mais campos vazios!'
            );
        }

        // Verificando se o usuário já existe
        $userAlreadExistsInDatabase = $this->userModel->checksIfUserAlreadyExists($userCPF);

        if($userAlreadExistsInDatabase){
            return redirect()->back()->with(
                'error_message', 'Já existe um usuário com esse login'
            );
        }

        // Se o usuário ainda não existe no banco de dados, prossigo com a validação e a inserção dos dados
        // NOME
        $userName = $this->escapeEntry($userName);

        // CPF
        $userCPFSanitized   = $this->sanitizeCPF($userCPF);
        $isThisCPFValid     = $this->isAValidCPF($userCPFSanitized);

        if(!$isThisCPFValid){
            return redirect()->back()->with(
                'error_message', 'O CPF informado é inválido, tente novamente!'
            );
        }

        // EMAIL
        $userEmailSanitized = $this->sanitizeEmail($userEmail);

        $isAnValidEmail = $this->isThisEmailValid($userEmailSanitized);
        // dd($isAnValidEmail);
        if(!$isAnValidEmail){
            return redirect()->back()->with(
                'error_message', 'O email informado é inválido, tente novamente!'
            );
        }
        
        // SENHA
        // Removendo eventuais espaços
        $userPasswordWithoutSpaces = $this->removeSpaces($userPassword);

        // Encriptando a senha (algoritmo bcrypt)
        $userPasswordEnc = password_hash($userPasswordWithoutSpaces, PASSWORD_DEFAULT);
        
        // CARGO
        // Talvez desnecessário, mas vai que algum gaiatinho tenta alterar o select no frontend...
        $enumRoles = [
            'usuario',
            'admin'
        ];
        // Acho que ainda dá pra melhorar, mas depois me preocupo com isso
        foreach($enumRoles as $role){
            if($role == $userRole){
                break;
            }else{
                continue;
            }
            return redirect()->back()->with(
                'error_message', 'O cargo informado é inválido, tente novamente!'
            );
        }

        // Após todas essa validações, só me resta inserir o registro no banco
        $userDataToInsert = [
            'nome'  => $userName,
            'email' => $userEmailSanitized,
            'cpf'   => $userCPFSanitized,
            'senha' => $userPasswordEnc,
            'cargo' => $userRole == 'usuario' ? 'usuario' : 'admin'
        ];

        if($this->userModel->insert($userDataToInsert)){
            return redirect()->back()->with(
                'success_message', 'O usuário foi criado com sucesso!'
            );
        }
        return redirect()->back()->with(
            'error_message', 'Falha ao criar o usuário!'
        );
    }
    
    // Método para listar todos os usuários
    public function listAllUsers(){

        $usersPerPages = 10;

        $users = $this->userModel->getAllUsersActive($usersPerPages);
        $pager = $this->userModel->pager;

        return  view('includes/header') .
                view('includes/alerts') .
                view('includes/sidebar') .
                view('dashboard/users/listAllUsers', [
                    "usersActives" => $users,
                    "pager"        => $pager
                ]) .
                view('includes/footer');
    }
    
    // Método para softdeletar um usuário
    public function softDeleteUser(){

        $userID  = $this->request->getPost('user-id');

        // Estou pegando o cpf para fazer uma validação de integridade dos dados
        $userCPF = $this->request->getPost('user-cpf');

        dd([$userID, $userCPF]);
    }

    // Editar o cara
    public function editUser(){

        $id     = $this->request->getPost('user-id')    ?? null;
        $name   = $this->request->getPost('user-name')  ?? null;
        $cpf    = $this->request->getPost('user-cpf')   ?? null;
        $email  = $this->request->getPost('user-email') ?? null;
        $role   = $this->request->getPost('user-role')  ?? null;

        // dd([$id, $name, $cpf, $email, $role]);

        $isThereSomeFieldEmpty = $this->isSomeValueNull([$id, $name, $cpf, $email, $role]);

        if($isThereSomeFieldEmpty){
            return redirect()->to('users/see-all')->with(
                'error_message', 'Um ou mais campos vazios!'
            );
        }

        // Sanitização e validação das informações
        // ID
        // Checo se existe algum usuário com esse ID
        $userExists = $this->userModel->checksIfUserAlreadyExistsById($id);

        if(!$userExists){
            return redirect()->to('users/see-all')->with(
                'error_message', 'Erro ao editar informações do usuário!'
            );
        }

        // NOME
        $nameSanitized = $this->escapeEntry($name);
        
        // CPF
        $cpf            = $this->sanitizeCPF($cpf);
        $isThisCPFValid = $this->isAValidCPF($cpf);

        if(!$isThisCPFValid){
            return redirect()->to('users/see-all')->with(
                'error_message', 'CPF inválido!'
            );
        }

        // EMAIL
        $email = $this->sanitizeEmail($email);
        $isThisEmailValid = $this->isThisEmailValid($email);

        if(!$isThisEmailValid){
            return redirect()->to('users/see-all')->with(
                'error_message', 'Email inválido!'
            );
        }

        $enumRoles = [
            'usuario',
            'admin'
        ];
        // Acho que ainda dá pra melhorar, mas depois me preocupo com isso
        foreach($enumRoles as $r){
            if($r == $role){
                break;
            }else{
                continue;
            }
            return redirect()->back()->with(
                'error_message', 'O cargo informado é inválido, tente novamente!'
            );
        }

        // Fazendo o update no banco
        $userData = [
            'id'    => $id,
            'nome'  => $name,
            'cpf'   => $cpf,
            'email' => $email,
            'cargo' => 'usuario' ? 'usuario' : 'admin'
        ];

        if(!$this->userModel->updateUserData($userData)){
            return redirect()->back()->with(
                'error_message', 'Erro ao editar informações do usuário, tente novamente!'
            );
        }

        return redirect()->back()->with(
            'success_message', 'Informações do usuário atualizadas com sucesso!'
        );
    }
}