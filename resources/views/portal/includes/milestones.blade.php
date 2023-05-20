<section class="property-highlights">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <span class="title-hl">Historical Milestones</span>
                {{-- <p class="content-hl">In a span of 30 years, we have passionately grown from a lighting supplier to a lighting solution provider. Passion. Precision. Partnership. Fumaco.</p> --}}
                <div class="row">
                    @foreach($milestones->slice(0, 3) as $milestone)
                    <div class="col-md-12 col-sm-12">
                        <div class="features-box">
                            <div class="features-icon">
                                <i class="icon-hourglass"></i>
                            </div>
                            <div class="features-content">
                                 @if(Auth::user())
                                @if(Auth::user()->user_group == 'Editor')
                                <p class="pull-right">
                    <a href="#" id="editPostBtn" data-image="{{ $milestone->featured_file }}" data-title="{{ $milestone->title }}" data-content="{{ $milestone->content }}" data-id="{{ $milestone->id }}" style="color: white !important;"><i class="fa fa-pencil"></i> Edit</a> |
                    <a href="#" id="deletePostBtn" data-image="{{ $milestone->featured_file }}" data-title="{{ $milestone->title }}" data-id="{{ $milestone->id }}" style="color: white !important;"><i class="icon-trash"></i> Delete</a>
                     </p>
                     @endif
                     @endif
                                <h4>{{ $milestone->title }}</h4>

                                <p>{!! str_limit($milestone->content, 150) !!}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    {{-- <div class="col-md-6 col-sm-6">
                        <div class="features-box">
                            <div class="features-icon">
                                <i class="icon-layers"></i>
                            </div>
                            <div class="features-content">
                                <h4>Large play center in yard</h4>
                                <p>Your kids will be happy having all these things around</p>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <a href="/historical_milestones" class="btn btn-common">Read More</a>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="touch-slider owl-carousel">
                    @foreach($milestones as $milestone_image)
                        @if($milestone_image->featured_file)
                        <div class="item">
                            <img src="{{ asset('storage/uploads/files/'. $milestone_image->featured_file) }}" alt="">
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>