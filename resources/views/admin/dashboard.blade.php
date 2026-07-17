@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="inner text-white">
                    <h3>{{ $totalStudents }}</h3>
                    <p>Total Students</p>
                </div>
                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                <a href="{{ route('admin.students.index') }}" class="small-box-footer text-white">
                    View All <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="inner text-white">
                    <h3>₹{{ number_format($totalRevenue, 0) }}</h3>
                    <p>Total Revenue</p>
                </div>
                <div class="icon"><i class="fas fa-rupee-sign"></i></div>
                <a href="{{ route('admin.payments.index') }}" class="small-box-footer text-white">
                    View Payments <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #fc5c7d 0%, #6a82fb 100%);">
                <div class="inner text-white">
                    <h3>₹{{ number_format($pendingFees, 0) }}</h3>
                    <p>Pending Fees</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
                <a href="{{ route('admin.payments.index') }}?status=pending" class="small-box-footer text-white">
                    View Pending <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="inner text-white">
                    <h3>{{ $paymentStats['completed'] }}</h3>
                    <p>Completed Payments</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <a href="{{ route('admin.payments.index') }}?status=completed" class="small-box-footer text-white">
                    View All <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-weight: 600;"><i class="fas fa-chart-line mr-2 text-primary"></i> Monthly Revenue</h3>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="280"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-weight: 600;"><i class="fas fa-chart-pie mr-2 text-info"></i> Payment Status</h3>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="280"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-weight: 600;"><i class="fas fa-history mr-2 text-success"></i> Recent Payments</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th>Student</th>
                        <th>Fee Category</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $payment)
                        <tr>
                            <td>
                                <strong>{{ $payment->student->full_name }}</strong>
                                <br><small class="text-muted">{{ $payment->student->admission_no }}</small>
                            </td>
                            <td>{{ $payment->feeStructure->feeCategory->name ?? 'N/A' }}</td>
                            <td><strong>₹{{ number_format($payment->amount, 2) }}</strong></td>
                            <td>{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>No payments recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
            datasets: [{
                label: 'Revenue (₹)',
                data: {!! json_encode(array_column($monthlyRevenue, 'amount')) !!},
                backgroundColor: 'rgba(102, 126, 234, 0.7)',
                borderColor: '#667eea',
                borderWidth: 2,
                borderRadius: 8,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Pending', 'Failed'],
            datasets: [{
                data: [{{ $paymentStats['completed'] }}, {{ $paymentStats['pending'] }}, {{ $paymentStats['failed'] }}],
                backgroundColor: ['#38ef7d', '#ffc107', '#dc3545'],
                borderWidth: 0,
                hoverOffset: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush
