<div class="main p-3">
        <div class="text-center">
            <h1>Weeping Angel - Protótipo 0.1</h1>
            <h2>Formulário para adição de máquina</h2>
        </div>
        <!-- Formulario -->
        <div class="container">
            <div class="col-10 my-5 py-1">
                <form class="form-control" action="<?= site_url('/admin/add-machine'); ?>" method="POST">
                    <div class="row mb-3">
                        <!-- Tombamento -->
                        <label for="machine-tomb" class="col-form-label col-3">Tombamento da máquina:</label>
                        <div class="col-sm-9 mb-3">
                            <input type="text" class="form-control" name="machine-tomb" id="machine-tomb" placeholder="Insira o tombamento da máquina">
                        </div>
                        <!-- Comentário -->
                        <label for="comment" class="col-form-label col-3">Comentário: (opcional)</label>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" name="comment" id="comment-area"></textarea>
                        </div>
                        <div class="mt-1">
                            <div class="d-grid gap-2">
                                <button class="btn btn-success">Registrar máquina</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Fim do formulario -->
    </div>
</div>
<script>
    // Script para preservar os dados dos campos ao recarregar a página
    const machineTomb = document.getElementById("machine-tomb");
    
    // Carrega os valores salvos
    machineTomb.value = localStorage.getItem("machine-tomb");
    
    machineTomb.addEventListener("input", () => {
        localStorage.setItem("machine-tomb", machineTomb.value);
    });

    // Puta que pariu, que linguagem bosta do caralho
</script>