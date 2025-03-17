<!DOCTYPE html>
<html>
<head>
    <title>Pet Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Pet Details</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->has('error'))
        <div class="alert alert-danger">{{ $errors->first('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $pet['name'] }}</h5>
            <p><strong>ID:</strong> {{ $pet['id'] }}</p>
            <p><strong>Status:</strong> {{ $pet['status'] }}</p>
            <p><strong>Tags:</strong> {{ implode(', ', array_map(fn($tag) => $tag['name'], $pet['tags'] ?? [])) }}</p>
            <p><strong>Photos:</strong></p>
            <ul>
                @foreach ($pet['photoUrls'] ?? [] as $url)
                    <li><a href="{{ $url }}" target="_blank">{{ $url }}</a></li>
                @endforeach
            </ul>
            <div class="mt-3">
                <a href="{{ route('pets.edit', $pet['id']) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('pets.destroy', $pet['id']) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</button>
                </form>
                <a href="{{ route('pets.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <h3>Upload Image</h3>
        <form method="POST" action="{{ route('pets.uploadImage', $pet['id']) }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">File</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Additional Metadata</label>
                <input type="text" name="additionalMetadata" class="form-control" maxlength="255">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
            <a href="{{ route('pets.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
</body>
</html>
