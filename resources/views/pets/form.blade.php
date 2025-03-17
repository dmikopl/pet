<!DOCTYPE html>
<html>
<head>
    <title>Add Pet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Add New Pet</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pets.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="available">Available</option>
                    <option value="pending">Pending</option>
                    <option value="sold">Sold</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tags (comma-separated)</label><br>
                <input type="text" id="tags" name="tags" class="form-control"  value="{{ old('tags') }}"><br><br>
            </div>
            <button type="submit" class="btn btn-primary">Add Pet</button>
            <a href="{{ route('pets.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
