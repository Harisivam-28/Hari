@extends('layouts.admin')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <form action="{{ route('admin.students.update', $student) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-user mr-2 text-primary"></i> Personal Information</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label>First Name <span class="text-danger">*</span></label><input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $student->first_name) }}" required>@error('first_name')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label>Last Name <span class="text-danger">*</span></label><input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $student->last_name) }}" required>@error('last_name')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><div class="form-group"><label>Date of Birth</label><input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"></div></div>
                            <div class="col-md-4">
                                <div class="form-group"><label>Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-control" required>
                                        <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><label>Blood Group</label>
                                    <select name="blood_group" class="form-control">
                                        <option value="">Select</option>
                                        @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}"></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Aadhar Number</label><input type="text" name="aadhar_number" class="form-control" value="{{ old('aadhar_number', $student->aadhar_number) }}" maxlength="12"></div></div>
                        </div>
                        <div class="form-group"><label>Address</label><textarea name="address" class="form-control" rows="2">{{ old('address', $student->address) }}</textarea></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-users mr-2 text-success"></i> Parent / Guardian</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label>Parent Name <span class="text-danger">*</span></label><input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $student->parent_name) }}" required></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Parent Phone</label><input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone', $student->parent_phone) }}"></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-key mr-2 text-warning"></i> Account</h3></div>
                    <div class="card-body">
                        <div class="form-group"><label>Email <span class="text-danger">*</span></label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $student->user->email) }}" required>@error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
                        <div class="form-group"><label>Admission No</label><input type="text" class="form-control" value="{{ $student->admission_no }}" readonly></div>
                        <div class="form-group"><label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-school mr-2 text-info"></i> Academic</h3></div>
                    <div class="card-body">
                        <div class="form-group"><label>Class <span class="text-danger">*</span></label>
                            <select name="class_id" class="form-control" required>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label>Section</label><input type="text" name="section" class="form-control" value="{{ old('section', $student->section) }}"></div>
                        <div class="form-group"><label>Roll Number</label><input type="text" name="roll_number" class="form-control" value="{{ old('roll_number', $student->roll_number) }}"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-camera mr-2 text-danger"></i> Photo</h3></div>
                    <div class="card-body text-center">
                        <label for="photo" style="cursor:pointer;">
                            <div id="photoPreview" class="rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width:100px;height:100px;background:#f0f0f0;border:2px dashed #ccc;overflow:hidden;">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">
                                @else
                                    <i class="fas fa-camera fa-2x text-muted"></i>
                                @endif
                            </div>
                            <small class="text-muted d-block mt-2">Click to change</small>
                        </label>
                        <input type="file" name="photo" id="photo" class="d-none" accept="image/*" onchange="previewImage(this)">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mb-3"><i class="fas fa-save mr-2"></i> Update Student</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) { document.getElementById('photoPreview').innerHTML = '<img src="' + e.target.result + '" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">'; };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
