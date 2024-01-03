<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="/assets/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/jquery.dataTables.min.css">
    {{-- <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet"> --}}

    <style>
        .table-responsive-stack tr {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
        }

        .table-responsive-stack td,
        .table-responsive-stack th {
            display: block;
            /* flex-grow | flex-shrink | flex-basis */
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
        }

        .table-responsive-stack .table-responsive-stack-thead {
            font-weight: bold;
        }

        @media screen and (max-width: 768px) {
            .table-responsive-stack tr {
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
                border-bottom: 3px solid #ccc;
                display: block;
            }

            /*  IE9 FIX   */
            .table-responsive-stack td {
                float: left\9;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <nav class="bg-primary p-3 d-flex align-items-center justify-content-between mb-3">
        <span class="text-white">{{ env('APP_NAME') }}</span>
        <a class="link-light text-decoration-none" href="/logout">Logout</a>
    </nav>

    <div class="container-fluid">
        <div class="row px-3">
            <canvas id="graph"></canvas>
        </div>

        <div class="row mx-3 mt-2">
            <div class="col px-0">
                <div class="card border-top-0">
                    <div class="card-body overflow-x-scroll">
                        <table class="table table-striped w-100" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Distance</th>
                                    <th>Receive Power</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data as $i)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $i->distance }}</td>
                                        <td>{{ $i->power }}</td>
                                        <td>{{ $i->timestamp }}</td>
                                    </tr>

                                    @php
                                        $no++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="/assets/bootstrap.bundle.min.js"></script>
    <script src="/assets/jquery.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script> --}}
    <script src="/assets/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $('#table').dataTable({
            "responsive": true,
            "autoWidth": false,
        });
    </script>

    {{-- belum rapih responsive table --}}
    <script>
        $(document).ready(function() {


            // inspired by http://jsfiddle.net/arunpjohny/564Lxosz/1/
            $('.table-responsive-stack').each(function(i) {
                var id = $(this).attr('id');
                //alert(id);
                $(this).find("th").each(function(i) {
                    $('#' + id + ' td:nth-child(' + (i + 1) + ')').prepend('<span class="table-responsive-stack-thead">' + $(this).text() + ':</span> ');
                    $('.table-responsive-stack-thead').hide();

                });



            });





            $('.table-responsive-stack').each(function() {
                var thCount = $(this).find("th").length;
                var rowGrow = 100 / thCount + '%';
                //console.log(rowGrow);
                $(this).find("th, td").css('flex-basis', rowGrow);
            });




            function flexTable() {
                if ($(window).width() < 768) {

                    $(".table-responsive-stack").each(function(i) {
                        $(this).find(".table-responsive-stack-thead").show();
                        $(this).find('thead').hide();
                    });


                    // window is less than 768px   
                } else {


                    $(".table-responsive-stack").each(function(i) {
                        $(this).find(".table-responsive-stack-thead").hide();
                        $(this).find('thead').show();
                    });



                }
                // flextable   
            }

            flexTable();

            window.onresize = function(event) {
                flexTable();
            };






            // document ready  
        });
    </script>

    @php
        $labels = [];
        $value = [];
        foreach ($data as $label) {
            array_push($labels, $label->timestamp);
            array_push($value, $label->power);
        }
    @endphp

    <script>
        const ctx = document.getElementById('graph');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: '# Receive Power',
                    data: @json($value),
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        // beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
