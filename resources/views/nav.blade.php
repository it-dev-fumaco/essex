<nav class="navbar navbar-default">
   <div class="col-md-12">
      <div class="row">
         <div class="row" style="padding: 0; margin: 0">
            <div class="col-md-3" style="padding-top: 10px;">
               <div class="row" style="display: flex; justify-content: center; align-items: center;">
                  <div class="col-md-4 col-md-offset-1" style="padding: 0;">
                     <a href="/">
                        <img src="{{ asset('storage/img/logo5.png') }}" style="width: 100%;">
                     </a>
                  </div>
                  <div class="col-md-7">
                     <img src="{{ asset('storage/img/company_logo.png') }}" width="100"><br><span class="header-text" style="">Employee Portal</span>
                  </div>
               </div>
            </div>
            <div class="col-md-9">
               <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                     <i class="fa fa-bars"></i>
                  </button>
               </div>
               <div class="navbar-collapse collapse">
                  <ul class="nav navbar-nav">
                     <li><a href="/"><i class="icon-home"></i> &nbsp;Home</a></li>
                     <li><a href="/updates"><i class="icon-info"></i> &nbsp;Updates</a>
                     <ul class="dropdown">
                           <li><a href="/gallery"><i class="icon-hourglass"></i> &nbsp;Gallery</a></li>
                     </ul>
                     </li>
                     <li><a href="/manuals"><i class="icon-notebook"></i> &nbsp;Manuals</a></li>
                     <li><a href="#"><i class="icon-book-open"></i> &nbsp;Services</a>
                        <ul class="dropdown">
                           <li><a href="/services/internet">Internet</a></li>
                           <li><a href="/services/email">Email</a></li>
                           <li><a href="/services/system">System</a></li>
                        </ul>
                     </li>
                     <li><a href="#"><i class="icon-briefcase"></i> &nbsp;Memorandum / Policy</a>
                        <ul class="dropdown">
                           <li><a href="/policies">Operational Policies</a></li>
                           <li><a href="/itguidelines">IT Guidelines and Policy</a></li>
                        </ul>
                     </li>
                     <li>
                        <a href="/services/directory"><i class="icon-briefcase"></i>&nbsp;Employee Directory</a>
                     </li>
                  </ul>
      
                  @if(Auth::user())
                     <div class="pull-right dashboard-btn"><a href="{{ url('/home') }}" class="btn btn-success"><i class="fa fa-users"></i> &nbsp;Essex Dashboard</a></div>
                  @endif
      
               </div>
            </div>
         </div>
         
      </div>
   </div>
   <ul class="wpb-mobile-menu">
      <li><a href="/"><i class="icon-home"></i> &nbsp;Home</a></li>
      <li><a href="/updates"><i class="icon-info"></i> &nbsp;Updates</a></li>
      <li><a href="/gallery"><i class="icon-hourglass"></i> &nbsp;Gallery</a></li>
      <li><a href="/manuals"><i class="icon-notebook"></i> &nbsp;Manuals</a></li>
      <li><a href="#"><i class="icon-book-open"></i> &nbsp;Services</a>
         <ul class="dropdown">
            <li><a href="/services/internet">Internet Services</a></li>
            <li><a href="/services/directory">Phone & Email Directory</a></li>
         </ul>
      </li>
      <li><a href="#"><i class="icon-briefcase"></i> &nbsp;Memorandum / Policy</a>
         <ul class="dropdown">
            <li><a href="/policies">Operational Policies</a></li>
         </ul>
      </li>
   </ul>
</nav>