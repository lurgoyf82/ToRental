<?php

	use Illuminate\Support\Facades\Cache;
	use App\Models\Alert;

	$routePath = request()->path();

	$alerts = Cache::remember('alerts', 60, function () {
		return Alert::getAllAggregatedAlertsSummary();
	});

	$class_alerts = array('0' => 'badge-secondary', '1' => 'badge-primary', '2' => 'badge-success', '3' => 'badge-warning', '4' => 'badge-danger');

	$count_alerts = $alerts[0];
	$color_alerts = $alerts[1];

?>
	<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
		 data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
		 data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
	<!--begin::Logo-->
	<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
		<!--begin::Logo image-->
		<img alt="Logo" src="logo-furgoni-2.svg" class="h-25px app-sidebar-logo-default"/>
		<img alt="Logo" src="logo-furgoni-2.svg" class="h-20px app-sidebar-logo-minimize"/>
		<!--end::Logo image-->
		<?php
			//include_once('sidebar-toggle.php');
		?>
	</div>
	<!--end::Logo-->
	<!--begin::sidebar menu-->
	<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
		<!--begin::Menu wrapper-->
		<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
			<!--begin::Scroll wrapper-->
			<div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
					 data-kt-scroll-activate="true" data-kt-scroll-height="auto"
					 data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
					 data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
					 data-kt-scroll-save-state="true">
				<!--begin::Menu-->
				<div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">

					<!--begin:Menu item-->
					<div class="menu-item pt-5">
						<!--begin:Menu content-->
						<div class="menu-content">
							<span class="menu-heading fw-bold text-uppercase fs-7">Dashboard</span>
						</div>
						<!--end:Menu content-->
					</div>
					<!--end:Menu item-->

					<!--begin:Menu item-->
					<div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-home fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
							</span>
							<span class="menu-title">Dashboard</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/dashboard">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Home</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
						</div>
						<!--end:Menu sub-->
					</div>
					<!--end:Menu item-->

					<!--begin:Menu item-->
					<div class="menu-item pt-5">
						<!--begin:Menu content-->
						<div class="menu-content">
							<span class="menu-heading fw-bold text-uppercase fs-7">Alerts</span>
						</div>
						<!--end:Menu content-->
					</div>
					<!--end:Menu item-->

					<!--begin:Menu item-->
					<div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-star fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
							</span>
							<span class="menu-title">Alert</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item Scadenza revisione meccanica-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/alert_revisione_meccanica">
									<span
										class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['revisione']] }} badge-lg">{{ $count_alerts['revisione'] }}</span>&nbsp;
									<span class="menu-title">Revisione Meccanica</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->

							<!--begin:Menu item Scadenza revisione bombole-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/alert_revisione_bombole">
									<span
										class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['bombole']] }} badge-lg">{{ $count_alerts['bombole'] }}</span>&nbsp;
									<span class="menu-title">Revisione Bombole</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->

							<!--begin:Menu item Scadenza revisione ATP-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/alert_revisione_atp">
									<span
										class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['atp']] }} badge-lg">{{ $count_alerts['atp'] }}</span>&nbsp;
									<span class="menu-title">Revisione ATP</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->

							<!--begin:Menu item Scadenza revisione tachigrafo-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/alert_revisione_tachigrafo">
									<span
										class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['tachigrafo']] }} badge-lg">{{ $count_alerts['tachigrafo'] }}</span>&nbsp;
									<span class="menu-title">Revisione Tachigrafo</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->

							<!--begin:Menu item Scadenza contratto di noleggio-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/alert_contratto_noleggio">
									<span class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['noleggio']] }} badge-lg">{{ $count_alerts['noleggio'] }}</span>&nbsp;
									<span class="menu-title">Contratto Noleggio</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->

							<!--begin:Menu item Scadenza polizza assicurativa-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/alert_polizza_assicurativa">
									<span class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['assicurazione']] }} badge-lg">{{ $count_alerts['assicurazione'] }}</span>&nbsp;
									<span class="menu-title">Polizza Assicurativa</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->

							<!--begin:Menu item Scadenza bollo-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/alert_scadenza_bollo">
									<span class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['bollo']] }} badge-lg">{{ $count_alerts['bollo'] }}</span>&nbsp;
									<span class="menu-title">Scadenza Bollo</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
						</div>
						<!--end:Menu sub-->
					</div>
					<!--end:Menu item-->

					@can('edit roles')
						{{-- Check if the user has the 'edit roles' permission --}}
						<!-- HTML content that should only be visible to users with the 'edit roles' permission -->
						<!--begin:Menu item-->
						<div class="menu-item pt-5">
							<!--begin:Menu content-->
							<div class="menu-content">
								<span class="menu-heading fw-bold text-uppercase fs-7">Gestione Ruoli</span>
							</div>
							<!--end:Menu content-->
						</div>
						<!--end:Menu item-->

						<!--begin:Menu item-->
						<div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
							<!--begin:Menu link-->
							<span class="menu-link">
								<span class="menu-icon">
									<i class="ki-duotone ki-gear fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</span>
								<span class="menu-title">Ruoli</span>
								<span class="menu-arrow"></span>
							</span>
							<!--end:Menu link-->
							<!--begin:Menu sub-->
							<div class="menu-sub menu-sub-accordion">
								<!--begin:Menu item-->
								<div class="menu-item">
									<!--begin:Menu link-->
									<a class="menu-link" href="/assign_role">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">Modifica</span>
									</a>
									<!--end:Menu link-->
								</div>
								<!--end:Menu item-->
							</div>
							<!--end:Menu sub-->
						</div>
						<!--end:Menu item-->
					@endcan

					<!--begin:Menu item-->
					<div class="menu-item pt-5">
						<!--begin:Menu content-->
						<div class="menu-content">
							<span class="menu-heading fw-bold text-uppercase fs-7">Veicolo</span>
						</div>
						<!--end:Menu content-->
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
							<span class="menu-title">Veicolo</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/create_veicolo">
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
								<a class="menu-link" href="/list_veicolo">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
									<span class="menu-title">Lista</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/update_veicolo">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Aggiorna</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/delete_veicolo">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Cancella</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
						</div>
						<!--end:Menu sub-->
					</div>
					<!--end:Menu item-->
					@if(Route::has('list_decorazione'))
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
							<span class="menu-title">Decorazione</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/create_decorazione">
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
								<a class="menu-link" href="/list_decorazione">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Elenco</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/update_decorazione">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Aggiorna</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/delete_decorazione">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Cancella</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
						</div>
						<!--end:Menu sub-->
					</div>
					<!--end:Menu item-->
					@endif
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
							<span class="menu-title">Assicurazione</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/create_assicurazione">
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
								<a class="menu-link" href="/list_assicurazione">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Elenco</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/update_assicurazione">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Aggiorna</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/delete_assicurazione">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Cancella</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
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
							<span class="menu-title">Bollo</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/create_bollo">
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
								<a class="menu-link" href="/list_bollo">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Elenco</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/update_bollo">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Aggiorna</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/delete_bollo">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Cancella</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
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
							<span class="menu-title">Tagliando</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/create_tagliando">
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
								<a class="menu-link" href="/list_tagliando">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Elenco</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/update_tagliando">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Aggiorna</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/delete_tagliando">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Cancella</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
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
									<span class="menu-title">Elenco</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
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
									<span class="menu-title">Elenco</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
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
							<span class="menu-title">Revisione</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/create_revisione">
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
								<a class="menu-link" href="/list_revisione">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Elenco</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/update_revisione">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Aggiorna</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/delete_revisione">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Cancella</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
						</div>
						<!--end:Menu sub-->
					</div>
					<!--end:Menu item-->

					@if(Route::has('list_stazionamento'))
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
							<span class="menu-title">Stazionamento Prolungato (Disabilitato)</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/create_stazionamento">
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
								<a class="menu-link" href="/list_stazionamento">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Elenco</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/update_stazionamento">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Aggiorna</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link" href="/delete_stazionamento">
									<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Cancella</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
						</div>
						<!--end:Menu sub-->
					</div>
					<!--end:Menu item-->
					@endif
					<!--begin:Menu item-->
					<a href="/logout">
					<div class="menu-item here menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<span class="menu-icon">
									<i class="ki-duotone ki-disconnect fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
									</i>
							</span>
								<span class="menu-title" style="color:red">LOGOUT</span>
						</span>
						<!--end:Menu link-->
					</div>
					</a>
					<!--end:Menu item-->

				</div>
				<!--end::Menu-->
			</div>
			<!--end::Scroll wrapper-->
		</div>
		<!--end::Menu wrapper-->
	</div>
	<!--end::sidebar menu-->
</div>
<!--end::Sidebar-->

<script>
	const currentRoute = "/{{ explode('/', request()->path())[0] }}";

	document.addEventListener('DOMContentLoaded', function () {
		var menuLinks = document.querySelectorAll('.menu-sub .menu-link[href]'); // Select only .menu-link with href inside .menu-sub

		menuLinks.forEach(function (link) {
			if (link.getAttribute('href') === currentRoute) {
				link.classList.add('active'); // Add 'active' to the matching link

				// Traverse to the grandparent to assign 'show'
				var grandParentMenuItem = link.parentElement.parentElement.parentElement;
				if (grandParentMenuItem) {
					grandParentMenuItem.classList.add('show');
				}

				// Locate the correct element for 'active' class in the grandparent
				var spanMenuLink = grandParentMenuItem.querySelector('.menu-link:not([href])');
				if (spanMenuLink) {
					spanMenuLink.classList.add('active');
				}
			}
		});
	});
</script>





