@extends('layouts.student')

@section('title', 'Fees & Payments')
@section('page-title', 'Fees & Payments')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Fees</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title" style="font-weight:600;"><i class="fas fa-money-bill-wave mr-2 text-primary"></i> Fee Details — {{ $student->academicClass->name ?? '' }}</h3>
        </div>
        <div class="card-body">
            @if(count($feeDetails) === 0)
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                    <h5>No fee structures assigned</h5>
                    <p>Contact admin to assign fee structures for your class.</p>
                </div>
            @else
                <form id="paymentForm">
                    <table class="table table-hover">
                        <thead style="background:#f8f9fa;">
                            <tr><th style="width:40px;"></th><th>Fee Category</th><th>Period</th><th>Amount</th><th>Due Date</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @php $pendingExists = false; @endphp
                            @foreach($feeDetails as $index => $fee)
                                <tr>
                                    <td>
                                        @if($fee['status'] === 'pending')
                                            @php $pendingExists = true; @endphp
                                            <input type="checkbox" class="fee-checkbox" data-fee-id="{{ $fee['fee_structure']->id }}" data-quarter="{{ $fee['quarter'] }}" data-amount="{{ $fee['amount'] }}" style="width:18px;height:18px;">
                                        @else
                                            <i class="fas fa-check-circle text-success"></i>
                                        @endif
                                    </td>
                                    <td><strong>{{ $fee['category'] }}</strong></td>
                                    <td>{{ $fee['quarter'] }}</td>
                                    <td><strong>₹{{ number_format($fee['amount'], 2) }}</strong></td>
                                    <td>{{ $fee['due_date'] ? \Carbon\Carbon::parse($fee['due_date'])->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if($fee['status'] === 'paid')
                                            <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Paid</span>
                                        @else
                                            <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($pendingExists)
                        <div class="card mt-3" style="background:#f8f9fa; border: 2px dashed #dee2e6;">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0" style="font-weight:700;">
                                        Total Selected: <span id="totalAmount" class="text-primary">₹0.00</span>
                                    </h5>
                                    <small class="text-muted">Select fees above to pay online</small>
                                </div>
                                <button type="button" id="payNowBtn" class="btn btn-lg" style="background:linear-gradient(135deg,#00b4d8,#0077b6);color:white;border:none;" disabled>
                                    <i class="fas fa-credit-card mr-2"></i> Pay Now via Razorpay
                                </button>
                            </div>
                        </div>
                    @endif
                </form>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    $(function() {
        let selectedFees = [];

        // Handle checkbox changes
        $('.fee-checkbox').on('change', function() {
            const feeId = $(this).data('fee-id');
            const quarter = $(this).data('quarter');
            const amount = parseFloat($(this).data('amount'));

            if (this.checked) {
                selectedFees.push({ fee_structure_id: feeId, quarter: quarter, amount: amount });
            } else {
                selectedFees = selectedFees.filter(f => !(f.fee_structure_id == feeId && f.quarter == quarter));
            }

            const total = selectedFees.reduce((sum, f) => sum + f.amount, 0);
            $('#totalAmount').text('₹' + total.toFixed(2));
            $('#payNowBtn').prop('disabled', selectedFees.length === 0);
        });

        // Pay Now
        $('#payNowBtn').on('click', function() {
            if (selectedFees.length === 0) return;

            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Processing...');

            $.ajax({
                url: '{{ route("payment.create") }}',
                type: 'POST',
                data: JSON.stringify({ fee_items: selectedFees }),
                contentType: 'application/json',
                success: function(data) {
                    const options = {
                        key: data.key,
                        amount: data.amount,
                        currency: data.currency,
                        name: '{{ config("app.name") }}',
                        description: 'Fee Payment',
                        order_id: data.order_id,
                        prefill: {
                            name: data.student_name,
                            email: data.student_email,
                        },
                        theme: { color: '#0077b6' },
                        handler: function(response) {
                            // Verify payment
                            $.ajax({
                                url: '{{ route("payment.verify") }}',
                                type: 'POST',
                                data: JSON.stringify({
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_signature: response.razorpay_signature,
                                }),
                                contentType: 'application/json',
                                success: function(res) {
                                    if (res.success) {
                                        alert('Payment successful! Receipt generated.');
                                        window.location.reload();
                                    } else {
                                        alert('Payment verification failed. Please contact admin.');
                                        window.location.reload();
                                    }
                                },
                                error: function() {
                                    alert('Payment verification failed. Please contact admin.');
                                    window.location.reload();
                                }
                            });
                        },
                        modal: {
                            ondismiss: function() {
                                btn.prop('disabled', false).html('<i class="fas fa-credit-card mr-2"></i> Pay Now via Razorpay');
                            }
                        }
                    };

                    const rzp = new Razorpay(options);
                    rzp.open();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.error || 'Failed to create order. Please try again.');
                    btn.prop('disabled', false).html('<i class="fas fa-credit-card mr-2"></i> Pay Now via Razorpay');
                }
            });
        });
    });
</script>
@endpush
