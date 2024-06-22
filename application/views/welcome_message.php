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
                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    <nav>
                                        <ul id="paginador" class="pagination justify-content-center mt-3"></ul>
                                    </nav>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div id="modal-content" class="modal" tabindex="-1"></div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://momentjs.com/downloads/moment.js"></script>
        <script src="assets/js/jquery.validate.min.js"></script>
        <script src="assets/js/additional-methods.min.js"></script>

        <script>
            $(function () {
                let $dataTable = $('#dataTable');

                //ABRIR MODAL PARA CREAR O EDITAR EVENTOS
                $('body').on('click', '.btn-crear-editar', function (ev) {
                    ev.preventDefault();
                    let id = parseInt(this.dataset.id);
                    let url = './modal-crear-editar';

                    if (id > 0) {
                        url = './modal-crear-editar/' + id;
                    }

                    $.ajax({
                        method: "GET",
                        url: url,
                    }).done(function (response) {
                        $('#modal-content').empty().html(response);
                        $('#modal-content').modal('toggle');
                    });
                });

                //ELIMINAR EVENTOS
                $('body').on('click', '.btn-eliminar', function (ev) {
                    ev.preventDefault();
                    let id = parseInt(this.dataset.id);
                    let titulo = this.dataset.titulo;

                    let confirmacion = confirm('¿Desea eliminar el evento "' + titulo + '"?');

                    if (confirmacion) {
                        $.ajax({
                            method: "POST",
                            url: "./borrar",
                            data: {id: id}
                        }).done(function (response) {
                            getData();
                        });
                    }
                });

                //ENVIAR EL FORMULARIO DE CREAR O EDITAR EVENTOS

                $('#modal-content').on('shown.bs.modal', function (ev) {
                    let $form = $('#form-modal');

                    $form.validate({
                        errorClass: 'invalid-feedback',
                        validClass: 'valid-feedback',
                        rules: {
                            'evento[titulo]': {
                                required: true,
                                minlength: 3,
                                normalizer: function (value) {
                                    return $.trim(value);
                                }
                            },
                            'evento[descripcion]': {
                                required: true,
                                minlength: 3,
                                normalizer: function (value) {
                                    return $.trim(value);
                                }
                            },
                            'evento[fecha_inicio]': {
                                required: true,
                            },
                            'evento[fecha_fin]': {
                                required: true,
                            }
                        },
                        messages: {
                            'evento[titulo]': {
                                required: 'El campo es obligatorio',
                                minlength: 'Ingrese m&iacute;nimo 3 caracteres'
                            },
                            'evento[descripcion]': {
                                required: 'El campo es obligatorio',
                                minlength: 'Ingrese m&iacute;nimo 3 caracteres'
                            },
                            'evento[fecha_inicio]': {
                                required: 'El campo es obligatorio',
                            },
                            'evento[fecha_fin]': {
                                required: 'El campo es obligatorio',
                            }
                        },
                        submitHandler: function (form) {
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
                        },
                        highlight: function (element, errorClass) {
                            $(element).addClass('is-invalid').removeClass('is-valid');
                        },
                        unhighlight: function (element, errorClass, validClass) {
                            $(element).addClass('is-valid').removeClass('is-invalid');
                        }
                    });
                });

                //VALIDAR LA FECHA DE FIN SEGUN LA FECHA DE INICIO
                $('body').on('change', '#ifecha_inicio', function (ev) {
                    let fecha_inicio = this.value;

                    $('#ifecha_fin').prop('min', fecha_inicio);
                });

                //VALIDAR LA FECHA DE INICIO SEGUN LA FECHA DE FIN
                $('body').on('change', '#ifecha_fin', function (ev) {
                    let fecha_fin = this.value;

                    $('#ifecha_inicio').prop('max', fecha_fin);
                });

                //LISTAR LOS EVENTOS CREADOS
                function getData(url) {
                    if (!url) {
                        url = "./eventos";
                    }

                    $.ajax({
                        method: "GET",
                        url: url,
                    }).done(function (response) {
                        $dataTable.find('tbody').empty();
                        $('#paginador').empty();

                        setDataTable(response['eventos']);
                        setPaginacion(response['total'], response['pag_actual'], response['pag_items']);
                    });
                }

                $('#paginador').on('click', '.page-link', function (ev) {
                    ev.preventDefault();

                    getData(this.href);
                });

                //POBLAR LA TABLA PARA MOSTRARLO AL USUARIO
                function setDataTable(data) {
                    if (data.length > 0) {
                        for (var i in data) {
                            var evento = data[i];
                            var fecha_inicio = moment(evento['fecha_inicio'], "YYYY-MM-DD");
                            var fecha_fin = moment(evento['fecha_fin'], "YYYY-MM-DD");

                            var $tr = $('<tr></tr>');
                            var $td_id = $('<td>' + evento['id'] + '</td>');
                            var $td_titulo = $('<td>' + evento['titulo'] + '</td>');
                            var $td_descripcion = $('<td>' + evento['descripcion'] + '</td>');
                            var $td_fecha_inicio = $('<td>' + fecha_inicio.format('DD/MM/YYYY') + '</td>');
                            var $td_fecha_fin = $('<td>' + fecha_fin.format('DD/MM/YYYY') + '</td>');
                            var $td_acciones = $('<td></td>');
                            var $btn_editar = $('<button class="btn btn-warning btn-crear-editar me-1" data-id="' + evento['id'] + '" data-titulo="' + evento['titulo'] + '">Editar</button>');
                            var $btn_eliminar = $('<button class="btn btn-danger btn-eliminar" data-id="' + evento['id'] + '" data-titulo="' + evento['titulo'] + '">Borrar</button>');

                            $td_id.appendTo($tr);
                            $td_titulo.appendTo($tr);
                            $td_descripcion.appendTo($tr);
                            $td_fecha_inicio.appendTo($tr);
                            $td_fecha_fin.appendTo($tr);
                            $btn_editar.appendTo($td_acciones);
                            $btn_eliminar.appendTo($td_acciones);
                            $td_acciones.appendTo($tr);

                            $tr.appendTo($dataTable);
                        }
                    }
                }

                function setPaginacion(total, pag_actual, pag_items) {
                    let paginador = $('#paginador');
                    let paginas = Math.ceil(total / pag_items);

                    for (var i = 1; i <= paginas; i++) {
                        var active = (i === parseInt(pag_actual)) ? 'active' : '';

                        paginador.append('<li class="page-item ' + active + '"><a class="page-link" href="./eventos?page=' + i + '">' + i + '</a></li>');
                    }
                }

                getData();
            });
        </script>
    </body>
</html>