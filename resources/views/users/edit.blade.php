@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Edit User</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Leave blank to keep current password">

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">Leave blank if you don't want to change the password.</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection