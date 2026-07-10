@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
                    <div>
                        <h1 class="h3 mb-1">Admin Dashboard</h1>
                        <p class="text-muted mb-0">Welcome, {{ $user->name }} ({{ $user->email }})</p>
                    </div>
                </div>

                <div class="p-4 bg-white rounded-3 border">
                    <p class="mb-0">You are logged in as an admin. Use this dashboard route for admin-only pages and features.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
