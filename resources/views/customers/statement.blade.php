@extends('layout.base')

@section('page-styles')
@endsection

@section('page-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 30px;">
                            <h5 class="card-title">View Customer Statement for {{ $customer->name }}</h5>

                            <div class="d-flex gap-2">
                                <a href="{{ route('customers.statement.pdf', $customer) }}" target="_blank"
                                    class="btn btn-danger">
                                    <i class="fa fa-file-pdf-o"></i> Print PDF
                                </a>

                                <a href="{{ route('customers.index') }}" type="button" class="btn btn-primary">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <p><strong>Total Invoiced:</strong> GH₵ {{ number_format($total_invoiced, 2) }}</p>
                            <p><strong>Total Paid:</strong> GH₵ {{ number_format($total_paid, 2) }}</p>
                            <p><strong>Balance:</strong> GH₵ {{ number_format($balance, 2) }}</p>

                            <table class="table mt-4">
                                <thead>
                                    <tr>
                                        <th><strong>Date</strong></th>
                                        <th><strong>Type</strong></th>
                                        <th><strong>Description</strong></th>
                                        <th><strong>Amount (GH₵)</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($statement as $row)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                                            <td>{{ $row['type'] }}</td>
                                            <td>{{ $row['description'] }}</td>
                                            <td
                                                class="{{ in_array($row['type'], ['Payment', 'Withholding Tax']) ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($row['amount'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
