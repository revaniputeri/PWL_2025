<!DOCTYPE html>
<html>

<head>
    <title>Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Data User</h1>
        <a href="/user/tambah">+ Tambah User</a>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>ID Level Pengguna</th>
                    <th>Kode Level</th>
                    <th>Nama Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                    <tr>
                        <td>{{ $d->user_id }}</td>
                        <td>{{ $d->username }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->level_id }}</td>
                        <td>{{ $d->level->level_code ?? '' }}</td>
                        <td>{{ $d->level->level_nama ?? '' }}</td>
                        <td><a href="/user/ubah/{{ $d->user_id }}">Ubah</a> | <a
                                href="user/hapus/{{ $d->user_id }}">Hapus</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>