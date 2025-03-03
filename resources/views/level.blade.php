<!DOCTYPE html>
<html>
<head>
    <title>Data Level Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Data Level Pengguna</h1>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID Level</th>
                    <th>Kode Level</th>
                    <th>Nama Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $level)
                    <tr>
                        <td>{{ $level->level_id }}</td>
                        <td>{{ $level->level_code }}</td>
                        <td>{{ $level->level_nama }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
