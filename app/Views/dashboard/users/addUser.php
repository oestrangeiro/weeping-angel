<div class="main py-3">
    <div class="text-center">
        <h1>Weeping Angel - Protótipo 0.1</h1>
        <h2>Adicionar usuário</h2>
    </div>
    <div class="container">
        <div class="col-12  my-5 py-1">
            <form class="form-control" action="<?= site_url('/admin/add-user'); ?>" method="POST">
                <div class="row mb-3">
                    <!-- Nome -->
                    <label for="user-name" class="col-form-label col-2">Nome do usuário:</label>
                    <div class="col-sm-10 mb-3">
                        <input type="text" class="form-control" name="user-name" id="user-name" placeholder="Insira o nome de usuário aqui">
                    </div>
                    <!-- Senha -->
                    <label for="user-password" class="col-form-label col-2">Senha:</label>
                    <div class="col-sm-10 mb-3">
                        <input type="password" class="form-control" name="user-password" id="user-password" placeholder="Insira a senha do usuário aqui">
                    </div>
                    <!-- CPF -->
                    <label for="user-cpf" class="col-form-label col-2">CPF:</label>
                    <div class="col-sm-10 mb-3">
                        <input type="text" class="form-control" name="user-cpf" id="user-cpf" placeholder="Insira o CPF do usuário aqui">
                    </div>
                    <!-- Email -->
                    <label for="user-email" class="col-form-label col-2">Email:</label>
                    <div class="col-sm-10 mb-3">
                        <input type="email" class="form-control" name="user-email" id="user-email" placeholder="Insira o email do usuário aqui">
                    </div>
                    <!-- Cargo -->
                    <label for="user-role" class="col-form-label col-2">Cargo:</label>
                    <div class="col-sm-10 mb-3">
                        <select class="form-select" name="user-role">
                            <option value="user" selected>Usuário</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mt-1">
                        <div class="d-grid gap-2">
                            <button class="btn btn-success">Registrar usuário</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Script para preservar os dados dos campos ao recarregar a página
    // const userName      = document.getElementById("user-name");
    const userEmail     = document.getElementById("user-email");
    const userCPF       = document.getElementById("user-cpf");
    // const userPassword  = document.getElementById("user-password");

    // Carrega os valores salvos
    // userName.value      = localStorage.getItem("user-name");
    userEmail.value     = localStorage.getItem("user-email");
    userCPF.value       = localStorage.getItem("user-cpf");
    // userPassword.value  = localStorage.getItem("user-password");

    // userName.addEventListener("input", () => {
    //     localStorage.setItem("user-name", userName.value);
    // });

    userEmail.addEventListener("input", () => {
        localStorage.setItem("user-email", userEmail.value);
    });

    userCPF.addEventListener("input", () => {
        localStorage.setItem("user-cpf", userCPF.value);
    });

    // userPassword.addEventListener("input", () => {
    //     localStorage.setItem("user-password", userPassword.value);
    // });

    // Puta que pariu, que linguagem bosta do caralho
</script>