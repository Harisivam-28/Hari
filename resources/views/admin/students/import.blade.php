@extends('layouts.admin')

@section('title', 'Import Students')
@section('page-title', 'Import Students')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Import</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-file-upload mr-2 text-success"></i> Import Students from CSV/Excel</h3></div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>CSV Format:</strong> Your file must have the following column headers:<br>
                        <code>first_name, last_name, email, password, date_of_birth, gender, blood_group, phone, address, aadhar_number, parent_name, parent_phone, class, section, roll_number, admission_date</code>
                    </div>

                    <form action="{{ route('admin.students.import.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><i class="fas fa-file-csv mr-1"></i> Select File (CSV, XLSX, XLS)</label>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input @error('file') is-invalid @enderror" id="importFile" accept=".csv,.xlsx,.xls" required>
                                <label class="custom-file-label" for="importFile">Choose file...</label>
                                @error('file') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-upload mr-2"></i> Import Students
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-table mr-2 text-primary"></i> Sample CSV Format</h3></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0" style="font-size:0.8rem;">
                            <thead style="background:#f8f9fa;"><tr><th>first_name</th><th>last_name</th><th>email</th><th>password</th><th>parent_name</th><th>class</th><th>section</th></tr></thead>
                            <tbody>
                                <tr><td>Rahul</td><td>Kumar</td><td>rahul@school.com</td><td>pass123</td><td>Mr. Kumar</td><td>Class 10</td><td>A</td></tr>
                                <tr><td>Priya</td><td>Sharma</td><td>priya@school.com</td><td>pass123</td><td>Mr. Sharma</td><td>Class 9</td><td>B</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endpush
