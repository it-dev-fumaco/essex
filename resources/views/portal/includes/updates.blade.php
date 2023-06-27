<section class="search-properties section" style="margin: -30px 0 -50px 0;">
   <div class="col-md-10 col-md-offset-1">
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
   </div>
</section>