<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asta People - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center vh-100">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center mb-5">
                                    <img src="<?php echo base_url('assets/image/logo A asta biru.png') ?>" alt="logo A asta" height="150px">
                                    <h3>User Register</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <form action="<?php echo base_url('register/addUser') ?>" method="POST">
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="inputUsername" class="form-label">Username(Nama Panggilan)</label>
                                                <input type="text" class="form-control" id="inputUsername" name="inputUsername">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputEmail" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="inputEmail" name="inputEmail">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputAddressNow" class="form-label">Alamat Sekarang</label>
                                                <input type="text" class="form-control" id="inputAddressNow" name="inputAddressNow">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputPlaceBirth" class="form-label">Tempat Lahir</label>
                                                <input type="text" class="form-control" id="inputAddressNow" name="inputAddressNow">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputReligion" class="form-label">Agama</label>
                                                <select name="inputReligion" id="inputReligion" class="form-select">
                                                    <option value="" selected disabled>Pilih</option>
                                                    <option value="islam">Islam</option>
                                                    <option value="kristen">kristen</option>
                                                    <option value="hindu">Hindu</option>
                                                    <option value="budha">Budha</option>
                                                    <option value="konghucu">Konghucu</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputHusbandWife" class="form-label">Nama Suami / Istri</label>
                                                <input type="text" class="form-control" id="inputHusbandWife" name="inputHusbandWife">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="inputFullname" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" id="inputFullname" name="inputFullname">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputHandphone" class="form-label">Handphone</label>
                                                <input type="text" class="form-control" id="inputHandphone" name="inputHandphone">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputAddressKtp" class="form-label">Alamat KTP</label>
                                                <input type="text" class="form-control" id="inputAddressKtp" name="inputAddressKtp">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputDateBirth" class="form-label">Tanggal Lahir</label>
                                                <input type="date" class="form-control" id="inputDateBirth" name="inputDateBirth">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputStatusMerried" class="form-label">Status Kawin</label>
                                                <select name="inputStatusMerried" id="inputStatusMerried" class="form-select">
                                                    <option value="" selected disabled>Pilih</option>
                                                    <option value="belum_Kawin">Belum Kawin</option>
                                                    <option value="sudah_Kawin">Sudah Kawin</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputNumberChildren" class="form-label">Jumlah Anak</label>
                                                <input type="number" max="100" class="form-control" id="inputNumberChildren" name="inputNumberChildren" oninput="if(this.value > 100) this.value = 100;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="inputPassword" class="form-label">Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="inputPassword" name="inputPassword">
                                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#inputPassword');
        const icon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // ganti icon Font Awesome
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    </script>
</body>

</html>