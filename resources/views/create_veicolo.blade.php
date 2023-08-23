<!DOCTYPE html>
@include('partials.creator')
<html lang="en">
    <!--begin::Head-->
    @include('partials.header')
    <!--end::Head-->
    <!--begin::Body-->
    <body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <!--begin::Theme mode setup on page load-->
        <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
        <!--end::Theme mode setup on page load-->
            <!--begin::App-->
            <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
                <!--begin::Page-->
                <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                    @include('partials.headbar')
                    <!--begin::Wrapper-->
                    <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                        @include('partials.sidebar')
                        @include('partials.create-veicolo-main')
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Page-->
            </div>
            <!--end::App-->
        <!--begin::Drawers-->
    <!--begin::Activities drawer-->

    @include('partials.footer')
    @include('partials.scripts')



    <script>
        var id_marca = $('#id_marca');
        var id_modello = $('#id_modello');
        id_marca.change(function() {
            var marcaID = $(this).val();
            if (marcaID) {
                $.ajax({
                    type: "GET",
                    url: "/get-modello-by-marca/" + marcaID,
                    success: function(res) {
                        if (res) {
                            id_modello.empty();
                            id_modello.append('<option>Select</option>');
                            $.each(res, function(key, value) {
                                id_modello.append('<option value="' + key + '">' + value + '</option>');
                            });
                        } else {
                            id_modello.empty();
                        }
                    }
                });
            } else {
                id_modello.empty();
            }
        });


    </script>

    </body>
<!--end::Body-->
</html>
