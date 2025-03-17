<!DOCTYPE html>
<html>
<head>
    <title>Pet Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Pets List</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('pets.form') }}" class="btn btn-primary">Add New Pet</a>
        <form action="{{ route('pets.index') }}" method="GET" class="d-inline">
            <label for="status" class="form-label d-inline me-2">Filter by Status:</label>
            <select name="status[]" id="status" multiple class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="available" {{ !isset($selectedStatuses) || in_array('available', $selectedStatuses) ? 'selected' : '' }}>Available</option>
                <option value="pending" {{ !isset($selectedStatuses) || in_array('pending', $selectedStatuses) ? 'selected' : '' }}>Pending</option>
                <option value="sold" {{ !isset($selectedStatuses) || in_array('sold', $selectedStatuses) ? 'selected' : '' }}>Sold</option>
            </select>
        </form>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status</th>
            <th>Tags</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($pets as $pet)
            <tr>
                <td>{{ $pet['id'] ?? 'N/A' }}</td>
                <td>{{ $pet['name'] ?? 'Unnamed' }}</td>
                <td>{{ $pet['status'] ?? 'N/A' }}</td>
                <td>{{ implode(', ', array_map(fn($tag) => $tag['name'] ?? 'N/A', $pet['tags'] ?? [])) }}</td>
                <td>
                    <a href="{{ route('pets.show', $pet['id']) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('pets.edit', $pet['id']) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('pets.destroy', $pet['id']) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No pets found</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $pets->links() }}
    </div>
</div>
</body>
</html>
