<!-- Sidebar com as paradas -->
 <?php //dd($userName); ?>
 <div class="wrapper">
    <aside id="sidebar">
        <div class="d-flex">
            <button id="toggle-btn" type="button">
                <i class="bi bi-sliders"></i>
            </button>
            <div class="sidebar-logo">
                <a href="<?= site_url('/dashboard'); ?>">Weeping Angel</a>
            </div>
        </div>
        <ul class="sidebar-nav">
            <!-- dropdown máquinas -->
            <li class="sidebar-item">
                <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                data-bs-target="#machines">
                    <i class="bi bi-pc-display-horizontal"></i>
                    <span>Máquinas</span>
                </a>
                <ul id="machines" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <!-- <i class="bi bi-wrench"></i> -->
                        <a href="<?= site_url('dashboard/maintenance'); ?>" class="sidebar-link">Registrar manutenção</a>
                    </li>
                    <li class="sidebar-item">
                        <!-- <i class="bi bi-wrench"></i> -->
                        <a href="<?= site_url('dashboard/add-machine'); ?>" class="sidebar-link">Adicionar nova máquina</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="<?= site_url('machines/see-all'); ?>" class="sidebar-link">Ver todas as máquinas</a>
                    </li>
                </ul>
            </li>
            <!-- dropdown usuários -->
            <li class="sidebar-item">
                <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                data-bs-target="#users">
                    <!-- <i class="bi bi-person-circle"></i> -->
                     <i class="bi bi-people"></i>
                    <span>Usuários</span>
                </a>
                <ul id="users" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <!-- <i class="bi bi-person-fill-add"></i> -->
                        <a href="<?= site_url('dashboard/add-user'); ?>" class="sidebar-link">Adicionar usuário</a>
                    </li>
                    <li class="sidebar-item">
                        <!-- <i class="bi bi-people-fill"></i> -->
                        <a href="<?= site_url('users/see-all'); ?>" class="sidebar-link">Ver todos os usuários</a>
                    </li>
                </ul>
            </li>
            <!-- dropdown cofigurações -->
            <li class="sidebar-item">
                <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                data-bs-target="#settings">
                    <i class="bi bi-gear-fill"></i>
                    <span>Configurações</span>
                </a>
                <ul id="settings" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a href="<?= site_url('user/edit'); ?>" class="sidebar-link">
                            <!-- <i class="bi bi-door-open"></i> -->
                            <span id="edit-profile">Editar perfil</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="<?= site_url('user/logout'); ?>" class="sidebar-link">
                            <!-- <i class="bi bi-door-open"></i> -->
                            <span id="logout">Sair</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
    <!-- Fim da side bar -->