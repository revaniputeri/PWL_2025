<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Data Kategori</h1>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID Kategori</th>
                    <th>Kode Kategori</th>
                    <th>Nama Kategori</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $kategori)
                    <tr>
                        <td>{{ $kategori->kategori_id }}</td>
                        <td>{{ $kategori->kategori_kode }}</td>
                        <td>{{ $kategori->kategori_nama }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
