<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Netberry Solutions</title>
    <link rel="icon" href="https://www.netberry.es/wp-content/uploads/2021/03/cropped-favicon-32x32.png" sizes="32x32">
    <link rel="icon" href="https://www.netberry.es/wp-content/uploads/2021/03/cropped-favicon-192x192.png"
        sizes="192x192">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


    <style type="text/css">
        .tag-container {
            margin: 2px;
            padding: 2px;
            border: 1px solid black;
            border-radius: 5px;
            background-color: lightgray;
            cursor: default;
        }

        .closeicon img {
            width: 15px;
            cursor: pointer;
        }
    </style>


    <script type="text/javascript">
        var table;
        $(document).ready(function() {

            table = $('#tastkTable').DataTable({
                "destroy": true,
                "ajax": 'http://localhost/index.php/Welcome/loadtasks',
                "info": false,
                "ordering": false,
                "lengthChange": false,
                "searching": false,
                "paging": false,
                "columns": [{
                        data: 'id',
                        "visible": false
                    },
                    {
                        data: 'task'
                    },
                    {
                        data: 'tags',
                        render: function(data, type) {
                            console.log(data)
                            return data.map(tag => "<span class='tag-container'>" + tag +
                                "</span>").join('');
                            //template literals use the toString() method which by default joins the returned array by map with a ,.
                            //To avoid this "problem" you can use join('')
                        }

                    },
                    {
                        className: 'closeicon',
                        data: 'id',
                        render: function(data, type) {
                            return "<img id=" + data +
                                " src='https://cdn-icons.flaticon.com/png/512/2976/premium/2976286.png?token=exp=1647279127~hmac=11b49bbaa0491e67f035e2921f10307d'>";
                        }
                    },
                ],
            });



            // Add event listener for opening and closing account details
            $('#tastkTable tbody').on('click', 'td.closeicon', function() {
                var row = $(this).parents('tr');
                var ID = $(this).children().attr('id');
                if (confirm("¿Cerrar tarea (" + ID + ")?")) {
                    $.ajax({
                            type: "POST",
                            url: "http://localhost/index.php/Welcome/closeTask",
                            data: {
                                id: ID
                            },
                            cache: false
                        })
                        .done(function(response) {
                            var result = JSON.parse(response);
                            if (result.status == 0) {
                                alert("Error al cerrar la tarea:" + result.msg);
                            } else {
                                alert("Tarea cerrada con exito");
                                //table.row( this ).delete();
                                table.row(row).remove().draw();
                            }
                        })
                        .fail(function() {
                            alert("Error en el servidor");
                        });
                }
            });

            $("#addTask").on('click', function() {
                var tags = $(".tags:checked").map(function() {
                    return $(this).val();
                }).get();
                var tagsNames = $(".tags:checked").map(function() {
                    return $(this).attr("text");
                }).get();
                var task = $("#inputTask").val();

                console.log(tags);
                console.log(tagsNames);
                console.log(task);

                $.ajax({
                        type: "POST",
                        url: "http://localhost/index.php/Welcome/addTask",
                        data: {
                            task: task,
                            tags: tags,
                        }
                    })
                    .done(function(response) {
                        var result = JSON.parse(response);

                        if (result.status == 0) {
                            alert("Error al crear la tarea:" + result.msg);
                        } else {
                            var id = result.msg

                            data = {
                                id: id,
                                task: task,
                                tags: tagsNames
                            };

                            var rowNode = table
                                .row.add(data)
                                .draw()
                                .node(); //created node to highlight that it was newly added


                            $(rowNode)
                                .css('color', 'red')
                                .animate({
                                    color: 'black'
                                });

                            //clear imputs
                            $("#inputTask").val('');
                            $('input:checkbox').removeAttr('checked');

                        }
                    })
                    .fail(function() {
                        alert("Error en el servidor");
                    });
            });

            //END ON READY
        });
    </script>

</head>

<body>



    <div class="container my-4">
        <div class="row">
            <div class="col-6">
                <h1>Gestor de tareas</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <hr class="my-4">
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <input type="text" id="inputTask" placeholder="Nueva Tarea...">
            </div>
            <div class="col-3">
                <?php foreach ($categories as $key => $value) : ?>
                <input type="checkbox" class="tags"
                    text="<?=$value?>" value=<?=$key?>>
                <?= $value ?>
                <?php endforeach; ?>
            </div>
            <div class="col-1">
                <button id="addTask" type="button">Añadir</button>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-6">
                <table id="tastkTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Tarea</th>
                            <th>Cateagorias</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id='tastkTableBody'>
                    </tbody>
                </table>
            </div>
        </div>

    </div>


</body>

</html>