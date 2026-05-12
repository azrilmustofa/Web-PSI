@extends('layouts.master_admin')

@section('title', 'Data Pengguna')

@section('content')

<div class="container py-4">

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h3 class="fw-bold mb-1">
                        Data Pengguna
                    </h3>

                    <p class="text-muted mb-0">
                        Daftar seluruh pengguna aplikasi
                    </p>

                </div>

                {{-- BUTTON TAMBAH --}}
                <button class="btn btn-dark rounded-pill px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#tambahUserModal">

                    + Tambah Pengguna

                </button>

            </div>

            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($users as $user)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td class="fw-semibold">
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>

                                @if($user->role == 'admin')

                                    <span class="badge bg-danger px-3 py-2 rounded-pill">
                                        Admin
                                    </span>

                                @elseif($user->role == 'kasir')

                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                        Kasir
                                    </span>

                                @else

                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        Customer
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="5" class="text-center py-4 text-muted">

                                Belum ada data pengguna

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- ================= MODAL TAMBAH USER ================= --}}
<div class="modal fade"
     id="tambahUserModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header border-0 pb-0">

                <h5 class="modal-title fw-bold">
                    Tambah Pengguna
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body p-4">

                <form action="{{ route('datpen.store') }}"
                      method="POST">

                    @csrf

                    {{-- NAMA --}}
                    <div class="mb-3">

                        <label class="fw-semibold mb-2">
                            Nama
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control rounded-3"
                               required>

                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">

                        <label class="fw-semibold mb-2">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control rounded-3"
                               required>

                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-3">

                        <label class="fw-semibold mb-2">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control rounded-3"
                               required>

                    </div>

                    {{-- ROLE --}}
                    <div class="mb-4">

                        <label class="fw-semibold mb-2">
                            Role
                        </label>

                        <select name="role"
                                class="form-select rounded-3"
                                required>

                            <option value="">
                                Pilih Role
                            </option>

                            <option value="admin">
                                Admin
                            </option>

                            <option value="kasir">
                                Kasir
                            </option>

                            <option value="customer">
                                Customer
                            </option>

                        </select>

                    </div>

                    {{-- BUTTON --}}
                    <button type="submit"
                            class="btn btn-dark rounded-pill w-100 py-2">

                        Simpan Pengguna

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection