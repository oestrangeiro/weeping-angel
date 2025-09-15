<style>
    .min-vh{
        height: 200px;
    }

    /* body{
        background-color: gray;
    }

    form {
        border-color: black;
        border-width: 2px;
        border-style: solid;
    } */
</style>
<div class="min-vh">
</div>
<div class="container">
    <div class="col-9">
        <form action="<?= site_url('/login'); ?>" method="POST">
            <div class="row mb-3">
                <label for="login" class="col-form-label col-1">Login:</label>
                <div class="col-sm-11 mb-3">
                    <input type="text" id="login" class="form-control" name="user-login" placeholder="Insira seu login aqui">
                </div>
                <label for="password" class="col-form-label col-1">Senha:</label>
                <div class="col-sm-11 mb-3">
                    <input type="password" class="form-control" name="user-password" placeholder="Insira a sua senha aqui">
                </div>
                <div class="mt-1">
                    <div class="d-grid gap-2">
                        <button class="btn btn-success">Entrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

    // Script para preservar os dados dos campos ao recarregar a página
    const login = document.getElementById("login");

    // Carrega os valores salvos
    login.value = localStorage.getItem("login");

    login.addEventListener("input", () => {
        localStorage.setItem("login", login.value);
    });

</script>