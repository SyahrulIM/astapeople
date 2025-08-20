<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asta Warehouse - Login</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/image/favicon.ico'); ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

  <!-- Login 1 - Bootstrap Brain Component -->
  <div class="bg-light py-3 py-md-5">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-12 col-md-11 col-lg-8 col-xl-7 col-xxl-6">
          <div class="bg-white p-4 p-md-5 rounded shadow-sm">
            <div class="row">
              <div class="col-12">
                <div class="text-center mb-5">
                  <div class="d-flex justify-content-center align-items-center">
                    <img src="<?php echo base_url('assets/image/logo A asta biru.png') ?>" alt="logo A asta" style="height: 2.5rem; margin-right: 10px;">
                    <h1 class="m-0">People</h1>
                  </div>
                </div>
              </div>
            </div>

            <!-- Flash messages -->
            <?php if ($this->session->flashdata('error')) : ?>
              <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')) : ?>
              <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>
            <!-- End -->

            <form action="<?php echo base_url('auth/login'); ?>" method="post">
              <div class="row gy-3 gy-md-4 overflow-hidden">
                <div class="col-12">
                  <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                      </svg>
                    </span>
                    <input type="text" class="form-control" name="username" id="username" required>
                  </div>
                </div>
                <div class="col-12">
                  <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <!-- icon kiri -->
                    <span class="input-group-text">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                        <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z" />
                        <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                      </svg>
                    </span>

                    <!-- input password -->
                    <input type="password" class="form-control" name="password" id="password" required>

                    <!-- tombol eye -->
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                      <i class="fa fa-eye" aria-hidden="true"></i>
                    </button>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn btn-primary btn-lg" type="submit">Login</button>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-grid">
                    <a href="<?php echo base_url('register'); ?>" type="button" class="btn btn-secondary">Register</a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
<script>
  const togglePassword = document.querySelector('#togglePassword');
  const passwordField = document.querySelector('#password');
  const icon = togglePassword.querySelector('i');

  togglePassword.addEventListener('click', function() {
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);

    // ganti icon
    if (type === 'password') {
      icon.classList.remove('bi-eye-slash');
      icon.classList.add('bi-eye');
    } else {
      icon.classList.remove('bi-eye');
      icon.classList.add('bi-eye-slash');
    }
  });
</script>

</html>