<!-- 
    Página para informar ao usuário que ele não pode acessar tal rota, geralmente por limitações de privilégios
-->
<div class="container">
    <div class="row">
        <h3 class="text-center bg-alert p-5 m-5">
            Você não pode acessar essa página.
            Motivo: Privilégios insuficientes
        </h3>
        <p>Se você acha que não deveria estar vendo este erro, contate o seu administrador</p>
    </div>
    <a href="<?= site_url('/dashboard'); ?>">Voltar</a>
</div>