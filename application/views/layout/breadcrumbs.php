<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0"><?= $breadcrumbs['title'] ?></h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <?php if(!empty($breadcrumbs['actions']['list'])){ ?> <li class="breadcrumb-item active"> <a href="<?= $breadcrumbs['actions']['list']; ?>"><?= $breadcrumbs['title'] ?></a></li><?php } ?>
                    <?php if(!empty($breadcrumbs['actions']['add'])){ ?> <li class="breadcrumb-item"><a href="<?= $breadcrumbs['actions']['add']; ?>">Add</a></li><?php } ?>
                </ol>
            </div>
        </div>
    </div>
</div>