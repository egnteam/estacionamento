<div class="app-sidebar colored">
	<div class="sidebar-header">
		<a class="header-brand" href="index.html">
			<div class="logo-img">
				<img src="src/img/brand-white.svg" class="header-brand-img" alt="lavalite">
			</div>
			<span class="text">ThemeKit</span>
		</a>
		<button type="button" class="nav-toggle"><i data-toggle="expanded" class="ik ik-toggle-right toggle-icon"></i>
		</button>
		<button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
	</div>

	<div class="sidebar-content">
		<div class="nav-container">
			<nav id="main-menu-navigation" class="navigation-main">
				<div class="nav-lavel">Parknow</div>
				<div class="nav-item <?php echo($this->router->fetch_class() == 'home' && $this->router->fetch_method() == 'index' ? 'active' : ''); ?>">
					<a data-toggle="tooltip" data-placement="bottom" title="Home" href="<?php echo base_url('/') ?>"><i
								class="ik ik-bar-chart-2"></i><span>Home</span></a>
				</div>
				<div class="nav-item <?php echo($this->router->fetch_class() == 'mensalistas' && $this->router->fetch_method() == 'index' ? 'active' : ''); ?>">
					<a data-toggle="tooltip" data-placement="bottom" title="Gerenciar Mensalitas"
					   href="<?php echo base_url('mensalistas') ?>"><i class="fa-solid fa-users"></i><span>Gerenciar Mensalistas</span></a>
				</div>

				<div class="nav-lavel">Administração</div>

				<div class="nav-item <?php echo($this->router->fetch_class() == 'usuarios' && $this->router->fetch_method() == 'index' ? 'active' : ''); ?>">
					<a data-toggle="tooltip" data-placement="bottom" title="Gerenciar Usuarios"
					   href="<?php echo base_url('usuarios') ?>"><i
								class="ik ik-users"></i><span>Gerenciar Usuários</span></a>
				</div>
				<div class="nav-item <?php echo($this->router->fetch_class() == 'sistema' && $this->router->fetch_method() == 'index' ? 'active' : ''); ?>">
					<a data-toggle="tooltip" data-placement="bottom" title="Gerenciar sistema"
					   href="<?php echo base_url('sistema') ?>"><i
								class="ik ik-settings"></i><span>Gerenciar sistema</span></a>
				</div>
				<div class="nav-item <?php echo($this->router->fetch_class() == 'precificacoes' && $this->router->fetch_method() == 'index' ? 'active' : ''); ?>">
					<a data-toggle="tooltip" data-placement="bottom" title="Gerenciar Preficicaçoes"
					   href="<?php echo base_url('precificacoes') ?>"><i class="ik ik-dollar-sign"></i><span>Gerenciar Precificaçoes</span></a>
				</div>
				<div class="nav-item <?php echo($this->router->fetch_class() == 'formas' && $this->router->fetch_method() == 'index' ? 'active' : ''); ?>">
					<a data-toggle="tooltip" data-placement="bottom" title="Gerenciar Formas de Pagamento"
					   href="<?php echo base_url('formas') ?>"><i class="fa-regular fa-money-bill"></i><span>Formas de Pagamento</span></a>
				</div>


			</nav>
		</div>
	</div>
</div>
