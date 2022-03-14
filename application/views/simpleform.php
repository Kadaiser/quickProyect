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
        }

        .closeicon {
            width: 15px;
            cursor: pointer;
        }
    </style>


    <script type="text/javascript">
        $(document).ready(function() {

            $('#tastkTable').DataTable({
                "destroy": true,
                "ajax": 'http://localhost/index.php/welcome/loadtasks',
                "info": false,
                "ordering": false,
                "lengthChange": false,
                "searching": false,
                "pageLength": 10,
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
                            console.log(data);
                            return data.map(tag => "<span class='tag-container'>" + tag +
                                "</span>");
                        }

                    },
                    {
                        data: 'id',
                        render: function(data, type) {
                            return "<img class='closeicon' src='https://cdn-icons.flaticon.com/png/512/2976/premium/2976286.png?token=exp=1647279127~hmac=11b49bbaa0491e67f035e2921f10307d' onclick='closeTask(" +
                                data + ")'>";
                        }
                    },
                ],
            });
        });

        function closeTask(id) {
            if (confirm("¿Cerrar tarea (" + id + ")?")) {

            }
        }
    </script>

</head>

<body>

    <?php //var_dump($categories);?>


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
                <input type="text" id="inputTask">
            </div>
            <div class="col-3">
                <?php foreach ($categories as $key => $value) : ?>
                <input type="checkbox" value=<?=$key?>> <?= $value ?>
                <?php endforeach; ?>
            </div>
            <div class="col-1">
                <button type="submit">Añadir</button>
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