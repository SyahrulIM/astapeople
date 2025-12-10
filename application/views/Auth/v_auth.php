<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asta People - Login</title>

  <!-- Meta Tag -->
  <meta name="description" content="Dashboard People Management System Asta Homeware.">
  <meta name="keywords" content="Asta, People, HR, Dashboard, Karyawan">
  <meta name="robots" content="index, follow">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/image/favicon.ico'); ?>">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

  <style>
    /* STRETCH FIT BACKGROUND */
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      overflow: hidden;
    }

    .bg-image {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      object-fit: fill;
      /* <= STRETCH FIT */
      z-index: -1;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(6px);
      border-radius: 1rem;
    }
  </style>
</head>

<body>

  <!-- Background Image Stretch Fit -->
  <img src="<?php echo base_url('assets/image/photo.jpg'); ?>" class="bg-image">

  <div class="py-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7 col-xl-6">

          <div class="p-4 p-md-5 shadow login-card">
            <div class="text-center mb-4">
              <div class="d-flex justify-content-center align-items-center">
                <img src="<?php echo base_url('assets/image/logo A asta biru.png') ?>" alt="Asta Logo" style="height: 2.5rem; margin-right: 10px;">
                <h1 class="m-0">People</h1>
              </div>
            </div>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?= $this->session->flashdata('error') ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= $this->session->flashdata('success') ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <!-- End Flash -->

            <form action="<?php echo base_url('auth/login'); ?>" method="post">

              <div class="mb-3">
                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa fa-user"></i></span>
                  <input type="text" class="form-control" name="username" id="username" required>
                </div>
              </div>

              <div class="mb-4">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa fa-key"></i></span>

                  <input type="password" class="form-control" name="password" id="password">

                  <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="fa fa-eye"></i>
                  </button>
                </div>
              </div>

              <div class="d-grid mb-2">
                <button class="btn btn-primary btn-lg" type="submit">Login</button>
              </div>

              <div class="d-grid">
                <a href="https://forms.gle/hC56Bj8MPkmsG1o6A" class="btn btn-secondary">Register</a>
              </div>

            </form>

          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Toggle Password JS -->
  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");
    const icon = togglePassword.querySelector("i");

    togglePassword.addEventListener("click", function() {
      const isPassword = passwordField.type === "password";
      passwordField.type = isPassword ? "text" : "password";

      icon.classList.toggle("fa-eye");
      icon.classList.toggle("fa-eye-slash");
    });
  </script>

</body>

</html>