@extends('layouts.admin')

@section('title', 'Students')
@section('page-title', 'Student Management')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Students</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title" style="font-weight: 600;"><i class="fas fa-user-graduate mr-2 text-primary"></i> All Students</h3>
            <div>
                <a href="{{ route('admin.students.import') }}" class="btn btn-outline-success btn-sm mr-2">
                    <i class="fas fa-file-upload mr-1"></i> Import CSV
                </a>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Add Student
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="studentsTable" class="table table-hover table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Adm. No</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Parent</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td><strong>{{ $student->admission_no }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle mr-2" style="width:32px;height:32px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:linear-gradient(135deg,#667eea,#764ba2);color:white;font-size:0.75rem;font-weight:600;">
                                            {{ strtoupper(substr($student->first_name,0,1).substr($student->last_name,0,1)) }}
                                        </div>
                                    @endif
                                    {{ $student->full_name }}
                                </div>
                            </td>
                            <td>{{ $student->academicClass->name ?? '-' }}</td>
                            <td>{{ $student->section ?? '-' }}</td>
                            <td>{{ $student->parent_name }}</td>
                            <td>{{ $student->phone ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info btn-xs" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning btn-xs" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {
        $('#studentsTable').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25,
        });
    });
</script>
@endpush
