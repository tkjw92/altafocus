<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="/assets/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/jquery.dataTables.min.css">
</head>

<body>

    <nav class="bg-primary p-3 d-flex align-items-center justify-content-between mb-3">
        <span class="text-white">{{ env('APP_NAME') }}</span>
        <a class="link-light text-decoration-none" href="/logout">Logout</a>
    </nav>

    <div class="container-fluid px-5 pt-3">

        <div class="d-flex">
            <img src="/assets/logo.png" class="img-thumbnail" width="10%">
            <table class="table table-striped m-2 d-block">
                <thead>
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

        <div class="row mt-4 gap-5 mb-5">
            <div class="col border text-center p-0">
                <div class="bg-success-subtle border-bottom py-2">Client UP</div>
                <h5 class="p-2">{{ count($actives) }} Client</h5>
            </div>

            <div class="col border text-center p-0">
                <div class="bg-danger-subtle border-bottom py-2">Client Down</div>
                <h5 class="p-2">{{ count($clients) - count($actives) }} Client</h5>
            </div>
        </div>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal"> + Add</button>

        <table class="table table-striped" id="table">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>Status</th>
                    <th>Client Name</th>
                    <th>Username</th>
                    <th>Profile</th>
                    <th>Address</th>
                    <th>Remote</th>
                    <th style="width: 150px">Action</th>
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

    <script src="/assets/bootstrap.bundle.min.js"></script>
    <script src="/assets/jquery.min.js"></script>
    <script src="/assets/jquery.dataTables.min.js"></script>

    <script>
        $('#table').dataTable({
            responsive: true
        });
    </script>
</body>

</html>
