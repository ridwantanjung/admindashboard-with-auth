<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>
    <div class="card mb-3 col-lg-9 float-left" style=" max-width: 850px;">
        <div class="row no-gutters" style="margin-left:-12px;">
            <div class="col-md-5">
                <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="card-img" alt="profile">
            </div>
            <div class="col-md-7">
                <div class="card-body">
                    <h5 class="card-title"><?= $user['name']; ?></h5>
                    <p class="card-text"><?= $user['email']; ?></p>
                    <p class="card-text"><small class="text-muted">Member Since <?= date('d F Y', $user['date_created']); ?> </small></p>
                    <a href="<?= base_url('user/edit') ?>" class="btn btn-sm btn-primary"><b>Edit</b> <i class="far fa-edit"></i></a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->