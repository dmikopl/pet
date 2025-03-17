<!DOCTYPE html>
<html>
<head>
    <title>Edit Pet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Edit Pet</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pets.update', $pet['id']) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $pet['name'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="available" {{ $pet['status'] == 'available' ? 'selected' : '' }}>Available</option>
                <option value="pending" {{ $pet['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="sold" {{ $pet['status'] == 'sold' ? 'selected' : '' }}>Sold</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Photo URLs (comma-separated)</label>
            <input type="text" id="photoUrls" name="photoUrls" class="form-control" value="{{ old('photoUrls', implode(',', $pet['photoUrls'] ?? [])) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Tags (comma-separated)</label>
            <input type="text" id="tags" name="tags" class="form-control" value="{{ old('tags', implode(',', array_map(fn($tag) => $tag['name'], $pet['tags'] ?? []))) }}"><br><br>        </div>
        <button type="submit" class="btn btn-primary">Update Pet</button>
        <a href="{{ route('pets.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
