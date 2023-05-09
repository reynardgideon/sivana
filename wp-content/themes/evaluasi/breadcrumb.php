<!-- Page-header start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title text-center">
                    <h3 class="m-b-10"><?= is_archive() ? strtoupper(get_post_type()) : get_the_title() ?></h3>
                </div>
            </div>
            <!--
            <div class="col-md-4">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="index.html"> <i class="fa fa-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Dashboard</a>
                    </li>
                </ul>
            </div>
            -->
        </div>
    </div>
</div>
<!-- Page-header end -->