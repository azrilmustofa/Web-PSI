@extends('layouts.master_kasir')

@section('title', 'Dashboard Kasir')

@section('content')

<div class="container py-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-5">

        <div>
            <h2 class="fw-bold mb-1">
                Dashboard Kasir
            </h2>

            <p class="text-muted mb-0">
                Kelola custom order customer
            </p>
        </div>


    </div>

    {{-- ALERT --}}
    @if(session('success'))

    <div class="alert alert-success rounded-4 border-0 shadow-sm">

        {{ session('success') }}

    </div>

    @endif

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Customer</th>
                            <th>Furniture</th>
                            <th>Kayu</th>
                            <th>Ukuran</th>
                            <th>Catatan</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($data as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $item->user->name }}
                            </td>

                            <td>
                                {{ $item->jenis_furniture }}
                            </td>

                            <td>
                                {{ $item->jenis_kayu }}
                            </td>

                            <td>
                                {{ $item->ukuran }}
                            </td>

                            <td>
                                {{ $item->catatan }}
                            </td>

                            <td>

                                <form action="{{ route('custom.status', $item->id) }}"
                                      method="POST">

                                    @csrf

                                    <select name="status"
                                            onchange="this.form.submit()"
                                            class="form-select rounded-pill">

                                        <option value="Pending"
                                            {{ $item->status == 'Pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>

                                        <option value="Diproses"
                                            {{ $item->status == 'Diproses' ? 'selected' : '' }}>
                                            Diproses
                                        </option>

                                        <option value="Selesai"
                                            {{ $item->status == 'Selesai' ? 'selected' : '' }}>
                                            Selesai
                                        </option>

                                    </select>

                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7" class="text-center text-muted py-4">

                                Belum ada custom order

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection