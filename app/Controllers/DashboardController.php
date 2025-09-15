<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\MachineModel;
use App\Models\MaintenanceModel;

class DashboardController extends BaseController{

    private $userModel;
    private $machineModel;
    private $maintenanceModel;

    public function __construct(){

        $this->userModel        = new UsersModel();
        $this->machineModel     = new MachineModel();
        $this->maintenanceModel = new MaintenanceModel();
    }

    public function index(){

        // Checa se o usuário está logado
        if(!session()->get('loggedIn')){
            return redirect()->to('/')->with(
                'error_message', 'Você precisa estar logado para acessar essa página'
            );
        }

        $userCPFInSession = session()->get('cpf');

        $userID = $this->userModel->getUserIDByCPF($userCPFInSession);

        $manutencoesDoUsuario = $this->maintenanceModel->getAllMaintenancesByUserID($userID);

        // Caso o usuário não tenha nenhuma manutenção sob a responsabilidade dele
        // redireciono ele para uma view específica
        if(!$manutencoesDoUsuario){
            return  view('includes/header') .
                    view('includes/sidebar') .
                    view('errors/users/has-no-maintenances') . 
                    view('includes/footer');
        }

        // pegando o nome do cara
        $userName = $this->userModel->getUserName($userCPFInSession);

        return  view('includes/header') .
                view('includes/alerts') .
                view('includes/sidebar', ['userName' => $userName]) .
                view('dashboard', ['manutencoesDoUsuario' => $manutencoesDoUsuario]) .
                view('includes/footer');
    }

    public function viewFormToAddUser(){
        return  view('includes/header') .
                view('includes/alerts') .
                view('includes/sidebar') .
                view('dashboard/users/addUser') .
                view('includes/footer');
    }

    public function viewFormToAddMachine(){
        return  view('includes/header') .
                view('includes/alerts') .
                view('includes/sidebar') .
                view('dashboard/machines/addMachine') .
                view('includes/footer');
    }

    // Formulário para o usuário registrar uma manutenção no banco
    public function viewFormToCreateMaintenance(){

        $prestadoresDeServico = $this->userModel->getAllUsersActive();
        $maquinas             = $this->machineModel->getAllMachinesActives();

        return  view('includes/header') .
                view('includes/alerts') .
                view('includes/sidebar') .
                view('dashboard/machines/maintenance', [
                    'prestadoresDeServico' => $prestadoresDeServico,
                    'maquinasAtivas'       => $maquinas,
                    ]) .
                view('includes/footer');
    }
    
}