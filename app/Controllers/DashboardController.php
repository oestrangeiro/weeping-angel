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

        $userCPFInSession = session()->get('cpf');

        $userID = $this->userModel->getUserIDByCPF($userCPFInSession);

        $manutencoesDoUsuario = $this->maintenanceModel->getAllMaintenancesByUserID($userID);
        // Colocando o nome do cara logado na sessão
        // pra poder mostrar sempre na dashboard
        $userName = $this->userModel->getUserName($userCPFInSession);

        $session = session();
        $session->set('userName', $userName);
        
        // Caso o usuário não tenha nenhuma manutenção sob a responsabilidade dele
        // redireciono ele para uma view específica
        if(!$manutencoesDoUsuario){
            return  view('includes/header') .
                    view('includes/sidebar') .
                    view('errors/users/has-no-maintenances') . 
                    view('includes/footer');
        }


        return  view('includes/header') .
                view('includes/alerts') .
                view('includes/sidebar') .
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