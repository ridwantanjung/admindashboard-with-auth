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
                                    <h1 class="h4 text-gray-900 mb-3">Forgot your password ?</h1>
                                    <p class="mt-4">We get it, stuff happens. Just enter your email address below and we'll send you a link to reset your password!</p>
                                    <?php if ($this->session->flashdata('message_success')) : ?>
                                        <div class="alert alert-success mb-2" role="alert" style="margin-bottom:-25px;">
                                            <?= $this->session->flashdata('message_success'); ?></div>
                                    <?php endif; ?>
                                    <?php if ($this->session->flashdata('message_danger')) : ?>
                                        <div class="alert alert-danger mb-2" role="alert" style="margin-bottom:-25px;">
                                            <?= $this->session->flashdata('message_danger'); ?></div>
                                    <?php endif; ?>
                                </div>

                                <form class="user" method="post" action="<?= base_url('auth/forgotpassword'); ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email Address..." value="<?= set_value('email'); ?>">
                                        <?= form_error('email', '<small class="text-danger pl-2">', '</small>'); ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Reset Password
                                    </button>
                                </form>
                                <hr>
                                <div class="mt-4">
                                    <div class="text-center">
                                        <a class="small" href="<?= base_url('auth') ?>">Back to login</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="<?= base_url('auth/registration') ?>">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>