@foreach ($employees as $department => $employee)
    <div class="col-md-12 text-center text-uppercase" style="margin-top: 15px;">
        <h3 style="letter-spacing: 1px;">{{ $department }}</h3>
    </div>
    @foreach ($employee as $emp)
        <div class="col-md-3" style="padding: 8px;">
            <div class="card" style="background-color: #fff; box-shadow: 1px 1px 4px rgba(0,0,0,.4); padding-top: 5px; padding-bottom: 5px;">
                <div class="row">
                    <div class="col-md-3 text-center" style="padding-top: 10px; padding-bottom: 10px;">
                        @php
                            $image = $emp->image ? $emp->image : 'storage/img/user.png';
                            if(!Storage::disk('public')->exists(str_replace('storage/', null, $image))){
                                $image = 'storage/img/user.png';
                            }
                        @endphp
                        <div class="profile-image" style="background-image: url({{ asset($image) }}); border: 1px solid rgba(0,0,0,.3)"></div>
                    </div>
                    <div class="col-md-9" style="padding: 0;">
                        <span style="font-weight: 600; font-size: 14px; line-height: 20px;" class="d-block">{{ $emp->employee_name }}</span>
                        <small class="text-muted d-block" style="font-size: 13px;line-height: 19px;">{{ $emp->designation }}</small>
                        <small class="d-block" style="white-space: nowrap !important;font-size: 11px; line-height: 19px;"><i class="fa fa-envelope"></i>&nbsp;{{ $emp->email ? $emp->email : 'N/A' }}</small>
                        <small class="d-block" style="white-space: nowrap !important; font-size: 11px; line-height: 19px;"><i class="fa fa-phone"></i>&nbsp;{{ $emp->telephone ? $emp->telephone : 'N/A' }}</small>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach