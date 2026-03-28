@php($lastDepartment = null)

@forelse ($employees as $emp)
    @if ($lastDepartment !== $emp->department)
        @php($lastDepartment = $emp->department)
        <div class="col-12 directory-department-heading">
            <h3>{{ $lastDepartment }}</h3>
        </div>
    @endif

    <div class="col-12 col-md-6 col-xl-4 directory-card-col">
        <a
            href="#"
            class="directory-card employee-profile-link"
            data-user-id="{{ $emp->user_id }}"
            title="View {{ $emp->employee_name }} profile"
        >
            <div class="directory-card__avatar-wrap">
                <div class="directory-card__avatar" style="background-image: url('{{ $emp->avatar_url }}');"></div>
            </div>
            <div class="directory-card__body">
                <div class="directory-card__name">{{ $emp->employee_name }}</div>
                <div class="directory-card__role">{{ $emp->designation ?: 'N/A' }}</div>
                <div class="directory-card__line">
                        <i class="fas fa-envelope"></i>
                    <span>{{ $emp->email ?: 'N/A' }}</span>
                </div>
                <div class="directory-card__line">
                    <i class="fa fa-phone"></i>
                    <span>{{ $emp->telephone ?: 'N/A' }}</span>
                </div>
            </div>
        </a>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-info" style="margin-top: 12px;">
            No employees found.
        </div>
    </div>
@endforelse

@if (isset($paginator) && $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="col-12" style="margin-top: 16px;">
        {{ $paginator->links('vendor.pagination.bootstrap-4') }}
    </div>
@endif