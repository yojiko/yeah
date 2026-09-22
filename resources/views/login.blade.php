@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <h3 class="text-center mb-4">Admin Login</h3>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.authenticate') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Login</button>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection