<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model{

    protected $table            = 'prestadores_de_servico_tb';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nome', 'email', 'cpf', 'senha', 'cargo', 'soft_delete'];
    protected $useTimestamps    = true;


    // Método para verificar se o usuário já existe
    public function checksIfUserAlreadyExists(string $userCPF){
        $user = $this->select('id')->where('cpf', $userCPF)->find();

        if($user){
            return true;
        }
        return false;
    }

    // Outro método para verificar se o usuário já existe
    public function checksIfUserAlreadyExistsById(string $userID){
        $user = $this->select('id')->where('id', $userID)->find();

        if($user){
            return true;
        }

        return false;
    }

    // Método para 'softdelete' um usuário
    public function softDeleteUser(string $userID){
        // fazer update do campo soft_delete para 1
    }

    // Método que lista todos os usuários deletados
    public function getAllUsersDeleted(){
        $usersDeleted = $this->select()->where('soft_delete', '1')->findAll();

        return $usersDeleted;
    }

    public function getPasswordEnc(string $userCPF){
        $passwordEnc = $this->select('senha')->where('cpf', $userCPF)->find();

        return $passwordEnc[0]['senha'];
    }

    public function getUserName(string $userCPF){
        $userName = $this->select('nome')->where('cpf', $userCPF)->find();

        return $userName[0]['nome'];
    }

    // Método para retornar todos os usuários ativos no banco
    public function getAllUsersActive(){
        $users = $this->select('id, nome, cpf, cargo, email, created_at, updated_at')->where('soft_delete', 0)->findAll();
        // $users = $this->select('id, nome, cargo, email, created_at, updated_at')->where('soft_delete', 0)->paginate($usersPerPage);

        return $users;
    }

    // Método para obter o cargo do usuário
    public function getUserRole($userCPF){
        $userRole = $this->select('cargo')->where('cpf', $userCPF)->find();

        return $userRole[0]['cargo'];
    }

    public function getUserIDByCPF(string $userCPF){
        $userID = $this->select('id')->where('cpf', $userCPF)->find();

        if(!$userID){
            false;
        }

        return $userID[0]['id'];
    }

    // Método para fazer atualização de uma máquina no banco de dados
    public function updateUserData(array $userData): bool{

        if($this->update($userData['id'], $userData)){
            return true;
        }
        return false;
    }
}