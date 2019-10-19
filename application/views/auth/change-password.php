<div class="container mt-5">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <!-- <div class="col-lg-6 d-none d-lg-block bg-login-image"></div> -->
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center mt-2" style="margin-top:-25px;">
                                    <h1 class="h4 text-gray-900 mb-1">Change your password for</h1>
                                    <h5 class="mb-4"><?= $this->session->userdata('reset_email'); ?></h5>
                                    <?php if ($this->session->flashdata('message_success')) : ?>
                                        <div class="alert alert-success mb-2" role="alert" style="margin-bottom:-25px;">
                                            <?= $this->session->flashdata('message_success'); ?></div>
                                    <?php endif; ?>
                                    <?php if ($this->session->flashdata('message_danger')) : ?>
                                        <div class="alert alert-danger mb-2" role="alert" style="margin-bottom:-25px;">
                                            <?= $this->session->flashdata('message_danger'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <form class="user" method="post" action="<?= base_url('auth/changepassword'); ?>">
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Enter new password">
                                        <?= form_error('password', '<small class="text-danger pl-2">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="repeatpassword" name="repeatpassword" placeholder="Repeat new password">
                                        <?= form_error('repeatpassword', '<small class="text-danger pl-2">', '</small>'); ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Change Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>