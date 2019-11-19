<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Blackouzz Webframework" />
        <meta name="author" content="@blackoutzz" />
        <title>Web Framework</title>
        
        <!-- Custom fonts for this template-->
        <link href="/assets/themes/dashboard/plugins/font-awesome/css/all.min.css" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
        <link href="/assets/themes/dashboard/css/sb-admin-2.min.css" rel="stylesheet" />
        <!-- Bootstrap core JavaScript-->
        <script src="/assets/themes/dashboard/plugins/jquery/jquery.min.js"></script>
        <script src="/assets/themes/dashboard/plugins/bootstrap/js/bootstrap.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="/assets/themes/dashboard/plugins/jquery-easing/jquery.easing.min.js"></script>
        <script src="/assets/themes/dashboard/js/app.js"></script>    
    </head>
    <body id="page-top">
        <div id="wrapper">
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                    <div class="sidebar-brand-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">
                        <div>Web.Framework</div>
                        <sup>@Blackoutzz</sup>
                    </div>
                </a>
                <hr class="sidebar-divider my-0" />
                <br/>
                <div class="text-center d-none d-md-inline" >
                    <button id="sidebarToggle" class="rounded-circle border-0"></button>
                </div>
            </ul>
            <div id="content-wrapper" class="d-flex flex-column">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                    </ul>
                </nav>
                <div id="content" class="view">
                    <?php $this->load_view(); ?> 
                </div>
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; 2019 <a href="https://blackoutzz.me">@Blackouzz.me</a></span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
        <script src="/assets/themes/dashboard/js/sb-admin-2.min.js"></script>
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">�</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button id="dashboard-user-logoff" class="btn btn-primary">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>