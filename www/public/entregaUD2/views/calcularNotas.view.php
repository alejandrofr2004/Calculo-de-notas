<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Calcular notas con un JSON</h1>

</div>
<!-- Content Row -->

<div class="row">
    <div class="col-12">
        <div class="alert alert-success">

        </div>
    </div>
    }
    <div class="col-12">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">JSON</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <!--<form action="./?sec=formulario" method="post">                   -->
                <form method="post" action="./?sec=calcularNotas">
                    <!--<input type="hidden" name="sec" value="iterativas01" />-->
                    <div class="mb-3">
                        <label for="texto">JSON:</label>
                        <textarea class="form-control" name="json" id="json" rows="3" placeholder="Inserte el json"><?php echo $data['input']['json'] ?? ''; ?></textarea>
                        <p class="text-danger small"><?php echo isset($data['errores']['json']) ? implode('<br/>',$data['errores']['json']) : ''; ?></p>
                    </div>
                    <div class="mb-3">
                        <input type="submit" value="Enviar" name="enviar" class="btn btn-primary"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
