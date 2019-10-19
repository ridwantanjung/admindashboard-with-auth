<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>
    <div class="row">
        <div class="col-lg-6">
            <!-- flash data -->
            <?php if ($this->session->flashdata('message_success')) : ?>
                <div class="alert alert-success mb-3 text-center" role="alert" style="margin-bottom:-25px;">
                    <?= $this->session->flashdata('message_success'); ?>
                </div>
            <?php elseif ($this->session->flashdata('message_danger')) : ?>
                <div class="alert alert-danger mb-3 text-center" role="alert" style="margin-bottom:-25px;">
                    <?= $this->session->flashdata('message_danger'); ?>
                </div>
            <?php endif; ?>
            <!-- end flash data -->
            <form action="<?= base_url('user/changepassword'); ?>" method="post">
                <div class="form-group">
                    <label for="old_password">Old Password</label>
                    <input type="password" class="form-control" id="old_password" name="old_password">
                    <?= form_error('old_password', '<small class="text-danger pl-2">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                    <?= form_error('new_password', '<small class="text-danger pl-2">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="repeatnew_password">Repeat New Password</label>
                    <input type="password" class="form-control" id="repeatnew_password" name="repeatnew_password">
                    <?= form_error('repeatnew_password', '<small class="text-danger pl-2">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary float-right">Change Password</button>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->