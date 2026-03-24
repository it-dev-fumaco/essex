@foreach ($employees as $department => $employee)
    <div class="col-12 directory-department-heading">
        <h3>{{ $department }}</h3>
    </div>
    @foreach ($employee as $emp)
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
                        <i class="fa fa-envelope-o"></i>
                        <span>{{ $emp->email ?: 'N/A' }}</span>
                    </div>
                    <div class="directory-card__line">
                        <i class="fa fa-phone"></i>
                        <span>{{ $emp->telephone ?: 'N/A' }}</span>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
@endforeach