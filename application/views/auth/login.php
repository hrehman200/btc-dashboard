<div class="row">
      <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
            <div class="d-table-cell align-middle">

                  <div class="text-center mt-4">
                        <h1 class="h2">Signin</h1>
                        <p class="lead">
                              Signin by entering your information.
                        </p>
                  </div>

                  <?php
                  if (isset($_SESSION['error'])) {
                  ?>
                        <p class="alert alert-danger my-2"><?= $_SESSION['error'] ?></p>
                  <?php
                  }
                  ?>

                  <div class="card">
                        <div class="card-body">
                              <div class="m-sm-4">

                                    <?php if (strlen($message)) { ?>
                                          <div class="alert alert-danger">
                                                <div class="alert-message"><?= $message ?></div>
                                          </div>
                                    <?php } ?>

                                    <form method="post" action="<?= site_url('login/submit') ?>">

                                          <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input class="form-control form-control-lg" type="email" name="email" placeholder="Enter your email" value="<?php if ($_SESSION['login_error']) {
                                                                                                                                                                  echo set_value('email');
                                                                                                                                                            } ?>" />
                                                <?php
                                                if ($_SESSION['login_error']) {
                                                      echo form_error('email');
                                                }
                                                ?>

                                          </div>
                                          <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <input class="form-control form-control-lg" type="password" name="password" placeholder="Enter password" value="<?php if ($_SESSION['login_error']) {
                                                                                                                                                                        echo set_value('password');
                                                                                                                                                                  } ?>" />
                                                <?php
                                                if ($_SESSION['login_error']) {
                                                      echo form_error('password');
                                                }
                                                ?>

                                          </div>
                                          <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-lg btn-primary">Sign in</button>
                                          </div>
                                    </form>
                              </div>
                        </div>
                  </div>

            </div>
      </div>
</div>
<?php unset($_SESSION['login_error']); ?>