<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asta People - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
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
                                <form action="<?php echo base_url('register/addUser')?>" method="POST">
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="inputUsername" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="inputUsername" name="inputUsername">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputEmail" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="inputEmail" name="inputEmail">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="inputFullname" class="form-label">Full Name</label>
                                                <input type="text" class="form-control" id="inputFullname" name="inputFullname">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputHandphone" class="form-label">Handphone</label>
                                                <input type="text" class="form-control" id="inputHandphone" name="inputHandphone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="inputPassword" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="inputPassword" name="inputPassword">
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
</body>

</html>