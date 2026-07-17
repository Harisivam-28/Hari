@extends('layouts.admin')

@section('title', 'Add Student')
@section('page-title', 'Add New Student')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Add Student</li>
@endsection

@section('content')
    <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Personal Info -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-user mr-2 text-primary"></i> Personal Information</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                    @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                    @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-control" required>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Blood Group</label>
                                    <select name="blood_group" class="form-control">
                                        <option value="">Select</option>
                                        @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Aadhar Number</label>
                                    <input type="text" name="aadhar_number" class="form-control" value="{{ old('aadhar_number') }}" maxlength="12">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Parent Info -->
                <div class="card">
                    <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-users mr-2 text-success"></i> Parent / Guardian Information</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Parent Name <span class="text-danger">*</span></label>
                                    <input type="text" name="parent_name" class="form-control @error('parent_name') is-invalid @enderror" value="{{ old('parent_name') }}" required>
                                    @error('parent_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Parent Phone</label>
                                    <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account & Academic Info -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-key mr-2 text-warning"></i> Login Account</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-school mr-2 text-info"></i> Academic Details</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Class <span class="text-danger">*</span></label>
                            <select name="class_id" class="form-control @error('class_id') is-invalid @enderror" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Section</label>
                            <input type="text" name="section" class="form-control" value="{{ old('section') }}" placeholder="e.g. A">
                        </div>
                        <div class="form-group">
                            <label>Roll Number</label>
                            <input type="text" name="roll_number" class="form-control" value="{{ old('roll_number') }}">
                        </div>
                        <div class="form-group">
                            <label>Admission Date</label>
                            <input type="date" name="admission_date" class="form-control" value="{{ old('admission_date', date('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-camera mr-2 text-danger"></i> Photo</h3></div>
                    <div class="card-body text-center">
                        <div class="form-group">
                            <label for="photo" class="d-block" style="cursor:pointer;">
                                <div id="photoPreview" class="rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width:100px;height:100px;background:#f0f0f0;border:2px dashed #ccc;">
                                    <i class="fas fa-camera fa-2x text-muted"></i>
                                </div>
                                <small class="text-muted d-block mt-2">Click to upload</small>
                            </label>
                            <input type="file" name="photo" id="photo" class="d-none" accept="image/*" onchange="previewImage(this)">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mb-3">
                    <i class="fas fa-save mr-2"></i> Save Student
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').innerHTML = '<img src="' + e.target.result + '" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
