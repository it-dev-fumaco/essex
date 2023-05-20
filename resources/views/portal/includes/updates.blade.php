<section class="search-properties section" style="margin: -30px 0 -50px 0;">
   <div class="col-md-10 col-md-offset-1">
      {{-- <div> --}}
         <div class="row border-danger">
            <div class="col-md-9 border-danger">
               <div class="row">
                  <h3 class="center">General IT Concerns</h3>
                  @foreach ($general_concerns as $concern)
                     <div class="col-md-4" style="padding: 10px 20px 10px 20px;">
                        <a href="/article/{{ $concern->slug }}">
                           <div class="container-fluid card card-kb">
                              <div>
                                 <p>{{ $concern->title }}</p>
                              </div>
                              <hr style="border: 1px solid rgba(175, 175, 175, .4); margin: 10px !important">
                              <div class="text-concat">
                                 {{ $concern->short_text }}
                              </div>
                           </div>
                        </a>
                     </div>
                  @endforeach
               </div>
            </div>
            <div class="col-md-3 border-danger">
               <div style="padding: 15px 0 15px 0;">
                  <div class="alert alert-info" style="margin: 10px;">
                     <h4>Reminder</h4>
                     <br> 
                     <p>1. FIRST TIME USERS - please read the <a href="/article/{{ $it_policy }}" style="color: inherit; text-decoration: underline">IT Guidelines and Policies</a>.</p>
                     <p style="font-weight: 500 !important">2. Shutdown computers, and turn off monitors, printers, photocopiers, laptops, AVR s(Automatic voltage regulators) and transformers.</p>
                     <p style="font-weight: 500 !important">3. Log off each terminal after use</p>
                  </div>
                  <br>
                  <div class="alert alert-warning" style="margin: 10px;">
                     <h4>Need for Support?</h4>
                     <br>
                     <p style="font-weight: 500 !important">If you cannot find an answer in the knowledgebase, email IT at <b>it@fumaco.local</b> or <b>it@fumaco.com</b></p>
                  </div>
               </div>
            </div>
         </div>
      {{-- </div> --}}
      {{-- <div class="row">
         <div class="col-md-12">
            <h2 class="section-title center">Updates</h2>
         </div>
         <div class="col-md-9">
            <div class="inner-box contact-info">
               <div class="row">
                  @foreach($updates as $update)
                  <div class="col-md-12">
                     <span class="title-2" style="border: 0;">{{ $update->title }}</span>
                     @if(Auth::user())
                     @if(Auth::user()->user_group == 'Editor')
                     <a href="#" id="editPostBtn" data-image="{{ $update->featured_file }}"
                        data-title="{{ $update->title }}" data-content="{{ $update->content }}"
                        data-id="{{ $update->id }}"><i class="fa fa-pencil"></i> Edit</a> | <a href="#"
                        id="deletePostBtn" data-title="{{ $update->title }}" data-id="{{ $update->id }}"><i
                           class="fa fa-pencil"></i> Delete</a>
                     @endif
                     @endif
                     <br><br>
                     <span>{!! $update->content !!}</span>
                     <hr>
                  </div>
                  @endforeach
                  <div class="col-md-12" style="padding-top: 10px;">
                     <a href="/updates" class="btn btn-common">Read More</a>
                     <div class="clearfix"></div>
                  </div>
               </div>
            </div>
         </div>

         <aside id="sidebar" class="col-md-3 right-sidebar">

            <div class="widget widget-popular-posts">
               <h5 class="widget-title">Latest Updates</h5>
               <ul class="posts-list">
                  @foreach($latest_news as $news)
                  <li>
                     <div class="widget-content">
                        <a href="#">{{ $news->title }}</a>
                        <span>{{ strip_tags(str_limit($news->content, 38)) }}<br>
                           <i class="icon-user"></i> {{ $news->employee_name }}
                           <div class="pull-right">
                              <i class="icon-calendar"></i> {{ date('m-d-Y', strtotime($news->date_modified)) }}
                           </div>
                        </span>
                     </div>
                     <div class="clearfix"></div>
                  </li>
                  @endforeach
               </ul>
            </div>
         </aside>

      </div> --}}
   </div>
</section>