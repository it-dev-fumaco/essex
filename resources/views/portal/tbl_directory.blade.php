@foreach ($employees as $department => $employee)
    <div class="col-md-12 text-center text-uppercase" style="margin-top: 15px;">
        <h3 style="letter-spacing: 1px;">{{ $department }}</h3>
    </div>
    @foreach ($employee as $emp)
        <div class="col-md-3" style="padding: 8px;">
            <div class="card shadow h-100 p-0" style="background-color: #fff;">
                <div class="card-body p-0">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-4 col-xl-3 text-center" style="display: flex; justify-content: center; align-items: center;">
                                @php
                                    $image = $emp->image ? $emp->image : 'storage/img/user.png';
                                    if(!Storage::disk('public')->exists(str_replace('storage/', null, $image))){
                                        $image = 'storage/img/user.png';
                                    }
                                @endphp
                                <div class="profile-image" style="background-image: url({{ asset($image) }}); border: 1px solid rgba(0,0,0,.3)"></div>
                            </div>
                            <div class="col-8 col-xl-9" style="padding: 0;">
                                <span style="font-weight: 600; font-size: 14px; line-height: 20px; word-break: break-word !important" class="d-block responsive-font">{{ $emp->employee_name }}</span>
                                <span class="text-muted d-block responsive-font" style="font-size: 13px;line-height: 19px;">{{ $emp->designation }}</span>
                                <span class="d-block dir-caption" style="white-space: nowrap !important; line-height: 19px;">
                                    <i class="fa fa-envelope"></i>&nbsp;{{ $emp->email ? $emp->email : 'N/A' }}
                                </span>
                                <span class="d-block dir-caption" style="white-space: nowrap !important; line-height: 19px;">
                                    <i class="fa fa-phone"></i>&nbsp;{{ $emp->telephone ? $emp->telephone : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach