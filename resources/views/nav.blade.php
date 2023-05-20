<nav class="navbar navbar-default">
   <div class="col-md-10 col-md-offset-1">
      <div class="row">
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
               <li><a href="/services/directory"><i class="icon-briefcase"></i>Phone & Email Directory</a>
               </li>
            </ul>

            @if(Auth::user())
               <div class="pull-right" style="margin: 16px 0 0 0;"><a href="{{ url('/home') }}" class="btn btn-success"><i class="fa fa-users"></i> &nbsp;Essex Dashboard</a></div>
            @endif

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