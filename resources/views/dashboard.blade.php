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
            <div class="col-lg-2 col-md-2 col-12">
                <img src="/assets/logo.png" class="img-thumbnail" width="100%">
            </div>
            <div class="col-lg 10 col-md-10 col-12">
                <table class="table table-striped table-responsive-stack" id="tableOne">
                    <thead class="table-primary">
                        <tr>
                            <th>Name</th>
                            <th>Board</th>
                            <th>Uptime</th>
                            <th>CPU</th>
                        </tr>
                    </thead>

                    <tbody>
                        <td>{{ env('APP_NAME') }}</td>
                        <td>{{ $resources['board-name'] }}</td>
                        <td>{{ $resources['uptime'] }}</td>
                        <td>{{ $resources['cpu-load'] }}%</td>
                    </tbody>
                </table>
            </div>

        </div>

        <div class="row mt-4 gap-5 mb-5 mx-3">
            <div class="col border text-center p-0">
                <div class="bg-success-subtle border-bottom py-2">Client UP</div>
                <h5 class="p-2">{{ count($actives) }} Client</h5>
            </div>

            <div class="col border text-center p-0">
                <div class="bg-danger-subtle border-bottom py-2">Client Down</div>
                <h5 class="p-2">{{ count($clients) - count($actives) }} Client</h5>
            </div>
        </div>

        <div class="row mx-3">
            <div class="col px-0">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-general-tab" data-bs-toggle="tab" data-bs-target="#nav-general" type="button" role="tab" aria-controls="nav-general" aria-selected="true">General</button>
                        <button class="nav-link" id="nav-optical-tab" data-bs-toggle="tab" data-bs-target="#nav-optical" type="button" role="tab" aria-controls="nav-optical" aria-selected="false">OLT</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-general" role="tabpanel" aria-labelledby="nav-general-tab" tabindex="0">
                        <div class="card border-top-0">
                            <div class="card-body overflow-x-scroll">
                                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal"> + Add</button>
                                <table class="table table-striped w-100" id="table">
                                    <thead>
                                        <tr>
                                            {{-- <th style="width: 50px">#</th> --}}
                                            <th>#</th>
                                            <th>Status</th>
                                            <th>Client Name</th>
                                            <th>Username</th>
                                            <th>Profile</th>
                                            <th>Address</th>
                                            <th>Remote</th>
                                            {{-- <th style="width: 150px">Action</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($clients as $client)
                                            @php
                                                $isActive = false;
                                                $address = '';

                                                foreach ($actives as $active) {
                                                    if ($active['name'] == $client['name']) {
                                                        $isActive = true;
                                                        $address = $active['address'];
                                                    }
                                                }

                                            @endphp

                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td scope="col">
                                                    @if ($isActive)
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                                            <path fill="#00ff00" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z" />
                                                        </svg> Up
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                                            <path fill="#ff0000" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z" />
                                                        </svg> Down
                                                    @endif
                                                </td>
                                                <td>{{ $client['comment'] ?? '' }}</td>
                                                <td>{{ $client['name'] }}</td>
                                                <td>{{ $client['profile'] }}</td>
                                                <td>{{ $address }}</td>
                                                <td>
                                                    @if ($isActive)
                                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#remoteModal{{ str_replace('*', '', $client['.id']) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                                                <path fill="#0000ff"
                                                                    d="M403.8 34.4c12-5 25.7-2.2 34.9 6.9l64 64c6 6 9.4 14.1 9.4 22.6s-3.4 16.6-9.4 22.6l-64 64c-9.2 9.2-22.9 11.9-34.9 6.9s-19.8-16.6-19.8-29.6V160H352c-10.1 0-19.6 4.7-25.6 12.8L284 229.3 244 176l31.2-41.6C293.3 110.2 321.8 96 352 96h32V64c0-12.9 7.8-24.6 19.8-29.6zM164 282.7L204 336l-31.2 41.6C154.7 401.8 126.2 416 96 416H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96c10.1 0 19.6-4.7 25.6-12.8L164 282.7zm274.6 188c-9.2 9.2-22.9 11.9-34.9 6.9s-19.8-16.6-19.8-29.6V416H352c-30.2 0-58.7-14.2-76.8-38.4L121.6 172.8c-6-8.1-15.5-12.8-25.6-12.8H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96c30.2 0 58.7 14.2 76.8 38.4L326.4 339.2c6 8.1 15.5 12.8 25.6 12.8h32V320c0-12.9 7.8-24.6 19.8-29.6s25.7-2.2 34.9 6.9l64 64c6 6 9.4 14.1 9.4 22.6s-3.4 16.6-9.4 22.6l-64 64z" />
                                                            </svg>
                                                        </a>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                                            <path fill="#ff0000"
                                                                d="M403.8 34.4c12-5 25.7-2.2 34.9 6.9l64 64c6 6 9.4 14.1 9.4 22.6s-3.4 16.6-9.4 22.6l-64 64c-9.2 9.2-22.9 11.9-34.9 6.9s-19.8-16.6-19.8-29.6V160H352c-10.1 0-19.6 4.7-25.6 12.8L284 229.3 244 176l31.2-41.6C293.3 110.2 321.8 96 352 96h32V64c0-12.9 7.8-24.6 19.8-29.6zM164 282.7L204 336l-31.2 41.6C154.7 401.8 126.2 416 96 416H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96c10.1 0 19.6-4.7 25.6-12.8L164 282.7zm274.6 188c-9.2 9.2-22.9 11.9-34.9 6.9s-19.8-16.6-19.8-29.6V416H352c-30.2 0-58.7-14.2-76.8-38.4L121.6 172.8c-6-8.1-15.5-12.8-25.6-12.8H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96c30.2 0 58.7 14.2 76.8 38.4L326.4 339.2c6 8.1 15.5 12.8 25.6 12.8h32V320c0-12.9 7.8-24.6 19.8-29.6s25.7-2.2 34.9 6.9l64 64c6 6 9.4 14.1 9.4 22.6s-3.4 16.6-9.4 22.6l-64 64z" />
                                                        </svg>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ str_replace('*', '', $client['.id']) }}">Edit</button>
                                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ str_replace('*', '', $client['.id']) }}">Delete</button>
                                                </td>
                                            </tr>

                                            @php
                                                $no++;
                                            @endphp

                                            <div class="modal fade" id="editModal{{ str_replace('*', '', $client['.id']) }}" tabindex="-1" aria-labelledby="editModal{{ str_replace('*', '', $client['.id']) }}Label" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="editModal{{ str_replace('*', '', $client['.id']) }}Label">Edit client</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="POST">
                                                            <div class="modal-body">
                                                                @csrf
                                                                @method('put')
                                                                <input type="hidden" name="id" value="{{ $client['.id'] }}">
                                                                <div class="form-group mb-3">
                                                                    <label>Nama Client</label>
                                                                    <input type="text" name="name" class="form-control" required value="{{ $client['comment'] ?? '' }}">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label>Username</label>
                                                                    <input type="text" class="form-control" name="username" required value="{{ $client['name'] }}">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label>Password</label>
                                                                    <input type="text" class="form-control" name="password" required value="{{ $client['password'] }}">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label>Profile</label>
                                                                    <select name="profile" required class="form-control">
                                                                        @foreach ($profiles as $profile)
                                                                            <option {{ $client['profile'] == $profile['name'] ? 'selected' : '' }} value="{{ $profile['name'] }}">{{ $profile['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="deleteModal{{ str_replace('*', '', $client['.id']) }}" tabindex="-1" aria-labelledby="deleteModal{{ str_replace('*', '', $client['.id']) }}Label" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="deleteModal{{ str_replace('*', '', $client['.id']) }}Label">Delete client</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="POST">
                                                            <div class="modal-body">
                                                                @csrf
                                                                @method('delete')
                                                                <input type="hidden" name="id" value="{{ $client['.id'] }}">
                                                                <input type="hidden" name="name" value="{{ $client['name'] }}">
                                                                <div class="form-group mb-3">
                                                                    <label>Nama Client</label>
                                                                    <input type="text" class="form-control" disabled value="{{ $client['comment'] ?? '' }}">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label>Username</label>
                                                                    <input type="text" class="form-control" disabled value="{{ $client['name'] }}">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label>Password</label>
                                                                    <input type="text" class="form-control" disabled value="{{ $client['password'] }}">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label>Profile</label>
                                                                    <input type="text" class="form-control" disabled value="{{ $client['profile'] }}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="remoteModal{{ str_replace('*', '', $client['.id']) }}" tabindex="-1" aria-labelledby="remoteModal{{ str_replace('*', '', $client['.id']) }}Label" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="remoteModal{{ str_replace('*', '', $client['.id']) }}Label">Remote Client</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h5>Pilih Port</h5>
                                                            <a href="http://{{ $address }}:80" target="_blank" class="btn btn-primary">80</a>
                                                            <a href="http://{{ $address }}:8080" target="_blank" class="btn btn-primary">8080</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="addModalLabel">Add new client</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="" method="POST">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-group mb-3">
                                                                <label>Nama Client</label>
                                                                <input type="text" name="name" class="form-control">
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label>Username</label>
                                                                <input type="text" name="username" class="form-control">
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label>Password</label>
                                                                <input type="text" name="password" class="form-control">
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label>Profile</label>
                                                                <select name="profile" required class="form-control">
                                                                    @foreach ($profiles as $profile)
                                                                        <option value="{{ $profile['name'] }}">{{ $profile['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-optical" role="tabpanel" aria-labelledby="nav-optical-tab" tabindex="0">
                        <div class="card border-top-0">
                            <div class="card-body overflow-x-scroll">
                                <table class="table table-striped w-100" id="table-optical">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>MAC Address</th>
                                            <th>Distance</th>
                                            <th>Receive Power</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($lives as $live)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td><a href="/graph/{{ $live->mac }}">{{ $live->name }}</a></td>
                                                <td>{{ $live->mac }}</td>
                                                <td>{{ $live->distance }}M</td>
                                                <td>{{ $live->power }}dBm</td>
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
        </div>

    </div>

    <script src="/assets/bootstrap.bundle.min.js"></script>
    <script src="/assets/jquery.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script> --}}
    <script src="/assets/jquery.dataTables.min.js"></script>

    <script>
        $('#table').dataTable({
            "responsive": true,
            "autoWidth": false,
        });

        $('#table-optical').dataTable({
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
</body>

</html>
