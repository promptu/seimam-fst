<nav class="main-header navbar-expand-sm navbar-dark bg-main">
	<div class="container">
		<div class="row py-2">
			<div class="col-sm-8">
        <a href="{{ url($user_ses['active_app']['link']) }}" class="navbar-brand" style="width: 320px">
					<img src="{{ url(env('APP_ICON')) }}" alt="Site Logo" class="float-left" style="width:60px; filter: drop-shadow(1pt 1pt 2pt #ffffff);">
					<div class="float-left ml-2">
						<h6 class="m-0">{!! $user_ses['active_app']['nama'] !!}</h6>
						<h3 class="m-0">{!! env('APP_NAME_HTML') !!}</h3>
					</div>
				</a>
			</div>
			<div class="col-sm-4 d-flex justify-content-end">					
				<a class="navbar-toggler order-1 mr-auto ml-2 pt-3" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				<ul class="navbar-nav navbar-no-expand order-2 ml-auto mt-2 mr-2" style="">
					<li class="nav-item dropdown float-right mt-1" style="">
						<a class="nav-link" data-toggle="dropdown" href="#" style="background-color:#3EB489; opacity:0.6; border-radius:15px; height:40px; width:70px; padding: 0px; bottom: 3px">
							<img src="{{ url($user_ses['pict']) }}" alt="User Picture" class="" 
								style="width:40px; border-radius: 15px 0 0 15px;">
							<i class="fas fa-chevron-down" style="margin-left: 5px;"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<div class="card mb-0">
								<div class="card-body mb-0">
									<div class="d-flex">										
										<img src="{{ url($user_ses['pict']) }}" alt="User Picture" style="width:45px; height:45px;">
										<div class="ml-2">
											<h6 class="text-bold mb-0">{{$user_ses['nama']}}</h6>
											<a href="{{ url('/backoffice/user-profile') }}">Lihat Profil <i class="fas fa-arrow-right"></i></a>
										</div>
									</div>
									<hr>
									<p class="text-muted"><label style="width:60px"><i class="fas fa-user-cog"></i>  Role </label>: {{ $user_ses['active_role']['nama'] }}</p>
									<a href="{{ url('/gate') }}" class="btn btn-outline-info mt-2"><i class="fab fa-buffer"></i> Daftar Modul</a>
									<a href="{{ url('/sign-out') }}" class="btn btn-outline-danger mt-2"><i class="fas fa-sign-out-alt"></i> Sign-out</a>
								</div>
							</div>
						</div>
					</li>
					<li class="notif-icon nav-item dropdown mr-2 float-right" style="padding-top: 0px;">
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="far fa-bell fa-xs" style="font-size: 20px;"></i>
							<span class="badge badge-warning navbar-badge">15</span>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<span class="dropdown-header">15 Notifications</span>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item">
								<i class="fas fa-envelope mr-2"></i> 4 new messages
								<span class="float-right text-muted text-sm">3 mins</span>
							</a>
						</div>
					</li>
				</ul>
			</div>
			<div class="col-sm-12 p-0">					
				<div class="collapse navbar-collapse" id="navbarCollapse">
          {!! '<ul class="main-nav navbar-nav"><li class="nav-item"><a href="'.url($user_ses['active_app']['link']).'" class="nav-link">Dashboard</a></li>'.$user_ses['menu']['navbar'].'</ul>' !!}
				</div>
			</div>
		</div>
	</div>
</nav>
<aside id="mainSidebar" class="main-sidebar sidebar-dark-primary elevation-4">
	<a href="{{ url($user_ses['active_app']['link']) }}" class="brand-link">
		<img src="{{ url(env('APP_ICON')) }}" alt="{{ env('APP_NAME') }}" class="brand-image img-circle" style="opacity: .8">
		<span class="brand-text font-weight-light">{{ $user_ses['active_app']['nama'] }}</span>
	</a>
	<div class="sidebar">
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image"><img src="{{ url($user_ses['pict']) }}" class="img-circle elevation-2" alt="User Image"></div>
			<div class="info"><a href="javascript:void(0);" class="d-block">{{ $user_ses['nama'] }}</a></div>
		</div>
		<div class="form-inline">
			<div class="input-group" data-widget="sidebar-search">
				<input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
				<div class="input-group-append">
					<button class="btn btn-sidebar"><i class="fas fa-search fa-fw"></i></button>
				</div>
			</div>
		</div>
    <nav class="mt-2">
      {!! '<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true"><li class="nav-item"><a href="'.url($user_ses['active_app']['link']).'" class="nav-link">Dashboard</a></li>'.$user_ses['menu']['sidebar'].'</ul>' !!}
    </nav>
	</div>
</aside>