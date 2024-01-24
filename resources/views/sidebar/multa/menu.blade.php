
<!--begin:Menu item-->
<div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
	<!--begin:Menu link-->
	<span class="menu-link">
							<span class="menu-icon">
									<i class="ki-duotone ki-element-11 fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
									</i>
							</span>
							<span class="menu-title">GPS</span>
							<span class="menu-arrow"></span>
						</span>
	<!--end:Menu link-->
	<!--begin:Menu sub-->
	<div class="menu-sub menu-sub-accordion">
		<!--begin:Menu item-->
		<div class="menu-item">
			<!--begin:Menu link-->
			<a class="menu-link" href="/create_gps">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
				<span class="menu-title">Inserisci</span>
			</a>
			<!--end:Menu link-->
		</div>
		<!--end:Menu item-->
		<!--begin:Menu item-->
		<div class="menu-item">
			<!--begin:Menu link-->
			<a class="menu-link" href="/list_gps">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
				<span class="menu-title">Lista</span>
			</a>
			<!--end:Menu link-->
		</div>
		<!--end:Menu item-->
		@if(request()->is('update_gps/*'))
			<!--begin:Menu item-->
			<div class="menu-item">
				<!--begin:Menu link-->
				<a class="menu-link" href="/update_gps">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
					<span class="menu-title">Aggiorna</span>
				</a>
				<!--end:Menu link-->
			</div>
			<!--end:Menu item-->
		@endif

		@if(request()->is('delete_gps/*'))
			<!--begin:Menu item-->
			<div class="menu-item">
				<!--begin:Menu link-->
				<a class="menu-link" href="/delete_gps">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
					<span class="menu-title">Cancella</span>
				</a>
				<!--end:Menu link-->
			</div>
			<!--end:Menu item-->
		@endif
	</div>
	<!--end:Menu sub-->
</div>
<!--end:Menu item-->
<!--begin:Menu item-->
<div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
	<!--begin:Menu link-->
	<span class="menu-link">
							<span class="menu-icon">
									<i class="ki-duotone ki-element-11 fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
									</i>
							</span>
							<span class="menu-title">Multa</span>
							<span class="menu-arrow"></span>
						</span>
	<!--end:Menu link-->
	<!--begin:Menu sub-->
	<div class="menu-sub menu-sub-accordion">
		<!--begin:Menu item-->
		<div class="menu-item">
			<!--begin:Menu link-->
			<a class="menu-link" href="/create_multa">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
				<span class="menu-title">Inserisci</span>
			</a>
			<!--end:Menu link-->
		</div>
		<!--end:Menu item-->
		<!--begin:Menu item-->
		<div class="menu-item">
			<!--begin:Menu link-->
			<a class="menu-link" href="/list_multa">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
				<span class="menu-title">Lista</span>
			</a>
			<!--end:Menu link-->
		</div>
		<!--end:Menu item-->

		@if(request()->is('update_multa/*'))
			<!--begin:Menu item-->
			<div class="menu-item">
				<!--begin:Menu link-->
				<a class="menu-link" href="/update_multa">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
					<span class="menu-title">Aggiorna</span>
				</a>
				<!--end:Menu link-->
			</div>
			<!--end:Menu item-->
		@endif
		@if(request()->is('delete_multa/*'))
			<!--begin:Menu item-->
			<div class="menu-item">
				<!--begin:Menu link-->
				<a class="menu-link" href="/delete_multa">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
					<span class="menu-title">Cancella</span>
				</a>
				<!--end:Menu link-->
			</div>
			<!--end:Menu item-->
		@endif
	</div>
	<!--end:Menu sub-->
</div>
<!--end:Menu item-->
