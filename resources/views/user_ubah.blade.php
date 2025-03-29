<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Ubah Data User</h1>
        <form method="POST" action="/user/ubah_simpan/{{ $data->user_id }}">
            
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $data->username }}" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ $data->nama }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (Kosongkan jika tidak ingin diubah)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="level_id" class="form-label">Level Pengguna</label>
                <select class="form-select" id="level_id" name="level_id" required>
                    <option value="">Pilih Level</option>
                    <option value="1" {{ $data->level_id == 1 ? 'selected' : '' }}>Admin</option>
                    <option value="2" {{ $data->level_id == 2 ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/user" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
