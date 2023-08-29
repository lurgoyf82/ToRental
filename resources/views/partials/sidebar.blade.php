<?php
    use App\Models\Alert;

    $alerts=Alert::all();

    $class_alerts=array('0'=>'badge-secondary','1'=>'badge-primary','2'=>'badge-success','3'=>'badge-warning','4'=>'badge-danger');

    $count_alerts=array('revisione'=>0,'tachigrafo'=>0,'bollo'=>0,'bombole'=>0,'cronotachigrafo'=>0,'atp'=>0,'noleggio'=>0,'assicurazione'=>0);
    $color_alerts=array('revisione'=>0,'tachigrafo'=>0,'bollo'=>0,'bombole'=>0,'cronotachigrafo'=>0,'atp'=>0,'noleggio'=>0,'assicurazione'=>0);

    foreach ($alerts as $alert) {
        if($alert->revisione_alert_level>0) {
            $count_alerts['revisione']++;
            if($color_alerts['revisione']<$alert->revisione_alert_level) {
                $color_alerts['revisione']=$alert->revisione_alert_level;
            }
        }

        if($alert->tachigrafo_alert_level>0) {
            $count_alerts['tachigrafo']++;
            if($color_alerts['tachigrafo']<$alert->tachigrafo_alert_level) {
                $color_alerts['tachigrafo']=$alert->tachigrafo_alert_level;
            }
        }

        if($alert->bollo_alert_level>0) {
            $count_alerts['bollo']++;
            if($color_alerts['bollo']<$alert->bollo_alert_level) {
                $color_alerts['bollo']=$alert->bollo_alert_level;
            }
        }

        if($alert->bombole_alert_level>0) {
            $count_alerts['bombole']++;
            if($color_alerts['bombole']<$alert->bombole_alert_level) {
                $color_alerts['bombole']=$alert->bombole_alert_level;
            }
        }

        if($alert->cronotachigrafo_alert_level>0) {
            $count_alerts['cronotachigrafo']++;
            if($color_alerts['cronotachigrafo']<$alert->cronotachigrafo_alert_level) {
                $color_alerts['cronotachigrafo']=$alert->cronotachigrafo_alert_level;
            }
        }
    }

    //var_dump($alerts);
    //die();
?>
    <!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <img alt="Logo" src="logo-furgoni-2.svg" class="h-25px app-sidebar-logo-default" />
        <img alt="Logo" src="logo-furgoni-2.svg" class="h-20px app-sidebar-logo-minimize" />
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
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
                <!--begin::Menu-->
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item here  {{ (request()->is('alert_revisione_meccanica')||
                                               request()->is('alert_revisione_bombole')||
                                               request()->is('alert_revisione_atp')||
                                               request()->is('alert_revisione_tachigrafo')||
                                               request()->is('alert_revisione_cronotachigrafo')||
                                               request()->is('alert_contratto_noleggio')||
                                               request()->is('alert_polizza_assicurativa')||
                                               request()->is('alert_scadenza_bollo')) ? 'show' : '' }} menu-accordion" >
                    <!--begin:Menu link-->
                    <span class="menu-link {{ (request()->is('alert_revisione_meccanica')||
                                               request()->is('alert_revisione_bombole')||
                                               request()->is('alert_revisione_atp')||
                                               request()->is('alert_revisione_tachigrafo')||
                                               request()->is('alert_revisione_cronotachigrafo')||
                                               request()->is('alert_contratto_noleggio')||
                                               request()->is('alert_polizza_assicurativa')||
                                               request()->is('alert_scadenza_bollo')) ? 'active' : '' }}">
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
                            <a class="menu-link {{ (request()->is('alert_revisione_meccanica')) ? 'active' : '' }}" href="alert_revisione_meccanica">
                                <span class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['revisione']] }} badge-lg">{{ $count_alerts['revisione'] }}</span>&nbsp;
                                <span class="menu-title">Revisione Meccanica</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->

                        <!--begin:Menu item Scadenza revisione bombole-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ (request()->is('alert_revisione_bombole')) ? 'active' : '' }}" href="alert_revisione_bombole">
                                <span class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['bombole']] }} badge-lg">{{ $count_alerts['bombole'] }}</span>&nbsp;
                                <span class="menu-title">Revisione Bombole</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->

                        <!--begin:Menu item Scadenza revisione ATP-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ (request()->is('alert_revisione_atp')) ? 'active' : '' }}" href="alert_revisione_atp">
                                <span class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['atp']] }} badge-lg">{{ $count_alerts['atp'] }}</span>&nbsp;
                                <span class="menu-title">Revisione ATP</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->

                        <!--begin:Menu item Scadenza revisione tachigrafo-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ (request()->is('alert_revisione_tachigrafo')) ? 'active' : '' }}" href="alert_revisione_tachigrafo">
                                <span class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['tachigrafo']] }} badge-lg">{{ $count_alerts['tachigrafo'] }}</span>&nbsp;
                                <span class="menu-title">Revisione Tachigrafo</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->

                        <!--begin:Menu item Scadenza revisione cronotachigrafo-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ (request()->is('alert_revisione_cronotachigrafo')) ? 'active' : '' }}" href="alert_revisione_cronotachigrafo">
                                <span class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['cronotachigrafo']] }} badge-lg">{{ $count_alerts['cronotachigrafo'] }}</span>&nbsp;
                                <span class="menu-title">Revisione Cronotachigrafo</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->

                        <!--begin:Menu item Scadenza contratto di noleggio-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ (request()->is('alert_contratto_noleggio')) ? 'active' : '' }}" href="alert_contratto_noleggio">
                                <span class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['noleggio']] }} badge-lg">{{ $count_alerts['noleggio'] }}</span>&nbsp;
                                <span class="menu-title">Contratto Noleggio</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->

                        <!--begin:Menu item Scadenza polizza assicurativa-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ (request()->is('alert_polizza_assicurativa')) ? 'active' : '' }}" href="alert_polizza_assicurativa">
                                <span class="badge badge-square badge-outline {{ $class_alerts[$color_alerts['assicurazione']] }} badge-lg">{{ $count_alerts['assicurazione'] }}</span>&nbsp;
                                <span class="menu-title">Polizza Assicurativa</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->

                        <!--begin:Menu item Scadenza bollo-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ (request()->is('alert_scadenza_bollo')) ? 'active' : '' }}" href="alert_scadenza_bollo">
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
                <div data-kt-menu-trigger="click" class="menu-item here  {{ (request()->is('create_veicolo')||
                                                       request()->is('list_veicolo')||
                                                       request()->is('update_veicolo')||
                                                       request()->is('delete_veicolo')) ? 'show' : '' }} menu-accordion">
                <!--begin:Menu link-->
                <span class="menu-link {{ (request()->is('create_veicolo')||
                                                       request()->is('list_veicolo')||
                                                       request()->is('update_veicolo')||
                                                       request()->is('delete_veicolo')) ? 'active' : '' }}">
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
                        <a class="menu-link {{ (request()->is('create_veicolo')) ? 'active' : '' }}" href="create_veicolo">
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
                        <a class="menu-link {{ (request()->is('list_veicolo')) ? 'active' : '' }}" href="list_veicolo">
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
                        <a class="menu-link {{ (request()->is('update_veicolo')) ? 'active' : '' }}" href="update_veicolo">
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
                        <a class="menu-link {{ (request()->is('delete_veicolo')) ? 'active' : '' }}" href="delete_veicolo">
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
                <span class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="create_decorazione">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="list_decorazione">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="update_decorazione">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="delete_decorazione">
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
                <span class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="create_assicurazione">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="list_assicurazione">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="update_assicurazione">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="delete_assicurazione">
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
                <span class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="create_bollo">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="list_bollo">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="update_bollo">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="delete_bollo">
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
                <span class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="create_tagliando">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="list_tagliando">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="update_tagliando">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="delete_tagliando">
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
                <span class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="create_gps">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="list_gps">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="update_gps">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="delete_gps">
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
                <span class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="create_multa">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="list_multa">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="update_multa">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="delete_multa">
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
                <span class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="create_revisione">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="list_revisione">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="update_revisione">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="delete_revisione">
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
                <span class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-element-11 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Stazionamento Prolungato</span>
                            <span class="menu-arrow"></span>
                        </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="create_stazionamento">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="list_stazionamento">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="update_stazionamento">
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
                        <a class="menu-link {{ (request()->is('alert_leasing')) ? 'active' : '' }}" href="delete_stazionamento">
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
<?php
/**/
?>
