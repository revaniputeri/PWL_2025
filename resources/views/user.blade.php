<!DOCTYPE html>
<html>

<head>
    <title>Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Data User</h1>
        <table class="table table-bordered">
            {{-- <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>ID Level Pengguna</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data->user_id }}</td>
                    <td>{{ $data->username }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->level_id }}</td>
                </tr>
            </tbody> --}}
            <thead class="table-dark">
                <tr>
                    <th>Jumlah Pengguna</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>