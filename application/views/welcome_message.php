<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bootstrap demo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>

        <div class="container">
            <div class="row my-3">
                <div class="col-6">
                    <button type="button" class="btn btn-primary btn-crear-editar" data-id="0">Nuevo</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>T&iacute;tulo</th>
                                <th>Descripci&oacute;n</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="modal-content" class="modal" tabindex="-1"></div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script>
            $(function () {
                let $dataTable = $('#dataTable');

                $('body').on('click', '.btn-crear-editar', function (ev) {
                    ev.preventDefault();
                    let id = parseInt(this.dataset.id);

                    $.ajax({
                        method: "GET",
                        url: "./modal-crear-editar",
                        data: {id: id}
                    }).done(function (response) {
                        $('#modal-content').empty().html(response);
                        $('#modal-content').modal('toggle');
                    });
                });

                $('body').on('click', '.btn-eliminar', function (ev) {
                    ev.preventDefault();
                    let id = parseInt(this.dataset.id);

                    $.ajax({
                        method: "POST",
                        url: "./eliminar",
                        data: {id: id}
                    }).done(function (response) {
                        getData();
                    });
                });

                $('body').on('submit', '#form-modal', function () {
                    let $form = $(this);
                    let inputs = $form.serializeArray();

                    $.ajax({
                        method: "POST",
                        url: "./modal-guardar",
                        data: inputs
                    }).done(function (response) {
                        $('#modal-content').empty();
                        $('#modal-content').modal('toggle');

                        getData();
                    });
                });

                function getData() {
                    $.ajax({
                        method: "GET",
                        url: "./usuarios",
                    }).done(function (response) {
                        $dataTable.find('tbody').empty();

                        setDataTable(response);
                    });
                }

                function setDataTable(data) {
                    if (data.length > 0) {
                        for (var i in data) {
                            var user = data[i];
                            var $tr = $('<tr></tr>');
                            var $td_id = $('<td>' + user['id'] + '</td>');
                            var $td_nombres = $('<td>' + user['name'] + '</td>');
                            var $td_telefono = $('<td>' + user['phone'] + '</td>');
                            var $td_correo = $('<td>' + user['email'] + '</td>');
                            var $td_acciones = $('<td></td>');
                            var $btn_editar = $('<button class="btn btn-warning btn-crear-editar me-1" data-id="' + user['id'] + '">Editar</button>');
                            var $btn_eliminar = $('<button class="btn btn-danger btn-eliminar" data-id="' + user['id'] + '">Borrar</button>');

                            $td_id.appendTo($tr);
                            $td_nombres.appendTo($tr);
                            $td_telefono.appendTo($tr);
                            $td_correo.appendTo($tr);
                            $btn_editar.appendTo($td_acciones);
                            $btn_eliminar.appendTo($td_acciones);
                            $td_acciones.appendTo($tr);

                            $tr.appendTo($dataTable);
                        }
                    }
                }

                getData();
            });
        </script>
    </body>
</html>