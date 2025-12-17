<!-- Page content-->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1 class="mt-4">Bank Password</h1>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <strong>Filter</strong>
        </div>
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="inputFilterAccount" class="form-label">Account</label>
                    <select id="inputFilterAccount" name="inputFilterAccount" class="form-select">
                        <option value="" <?php if ($this->input->get('inputFilterAccount') === '') {
                                                echo 'selected';
                                            } ?>>Semua</option>
                        <?php foreach ($account as $key => $value) { ?>
                        <option value="<?php echo $value->account; ?>" <?php if ($this->input->get('inputFilterAccount') === $value->account) {
                                                                                echo 'selected';
                                                                            } ?>>
                            <?php echo strtoupper($value->account); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="inputFilterEmail" class="form-label">Email</label>
                    <select id="inputFilterEmail" name="inputFilterEmail" class="form-select">
                        <option value="" <?php if ($this->input->get('inputFilterEmail') === '') {
                                                echo 'selected';
                                            } ?>>Semua</option>
                        <?php foreach ($email as $key => $value) { ?>
                        <option value="<?php echo $value->email; ?>" <?php if ($this->input->get('inputFilterEmail') === $value->email) {
                                                                                echo 'selected';
                                                                            } ?>>
                            <?php echo strtoupper($value->email); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="inputFilterCategory" class="form-label">Category</label>
                    <select id="inputFilterCategory" name="inputFilterCategory" class="form-select">
                        <option value="" <?= $this->input->get('inputFilterCategory') === '' ? 'selected' : '' ?>>Semua</option>
                        <?php foreach ($category as $key => $value) { ?>
                        <option value="<?php echo $value->category; ?>" <?php if ($this->input->get('inputFilterCategory') === $value->category) {
                                                                                echo 'selected';
                                                                            } ?>>
                            <?php echo strtoupper($value->category); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="inputFilterVerification" class="form-label">Verification</label>
                    <select id="inputFilterVerification" name="inputFilterVerification" class="form-select">
                        <option value="" <?= $this->input->get('inputFilterVerification') === '' ? 'selected' : '' ?>>Semua</option>
                        <?php foreach ($verification as $key => $value) { ?>
                        <option value="<?php echo $value->verification; ?>" <?php if ($this->input->get('inputFilterVerification') === $value->verification) {
                                                                                    echo 'selected';
                                                                                } ?>>
                            <?php echo strtoupper($value->verification); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Start diagram password -->
                <?php if ($active_filters === 1 && $this->input->get('inputFilterAccount')) { ?>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col text-center">
                            <h3>Context Diagram</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-10" style="text-align: center;">
                            <span class="badge text-bg-primary fs-6">
                                <?php echo strtoupper($this->input->get('inputFilterAccount')) ?>
                            </span>
                        </div>
                        <div class="col"></div>
                        <div class="col-12" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col-12">
                            <div style="border: none;border-top: 5px solid black;width: 76%;justify-self: center;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: center;">
                            <span class="badge text-bg-primary fs-6">
                                Email
                            </span>
                        </div>
                        <div class="col" style="text-align: center;">
                            <span class="badge text-bg-primary fs-6">
                                Verification
                            </span>
                        </div>
                        <div class="col" style="text-align: center;">
                            <span class="badge text-bg-primary fs-6">
                                Devices
                            </span>
                        </div>
                        <div class="col" style="text-align: center;">
                            <span class="badge text-bg-primary fs-6">
                                PIC
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <?php foreach ($email_filter as $keys) { ?>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <a href="<?php echo base_url('bank_password?inputFilterEmail=' . $keys->email) ?>">
                                        <span class="badge text-bg-primary fs-6 mt-2 d-inline-block">
                                            <?php echo $keys->email; ?>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col">
                            <?php foreach ($verification_filter as $keys) { ?>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <span class="badge text-bg-primary fs-6 mt-2 d-inline-block">
                                        <?php echo $keys->verification; ?>
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col">
                            <?php foreach ($devices_filter as $keys) { ?>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <span class="badge text-bg-primary fs-6 mt-2 d-inline-block">
                                        <?php echo $keys->devices; ?>
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col">
                            <?php foreach ($role_filter as $keys) { ?>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <span class="badge text-bg-primary fs-6 mt-2 d-inline-block">
                                        <?php echo $keys->nama_role; ?>
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } else if ($active_filters === 1 && $this->input->get('inputFilterEmail')) { ?>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col text-center">
                            <h3>Context Diagram</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-10" style="text-align: center;">
                            <span class="badge text-bg-primary fs-6">
                                <?php echo strtoupper($this->input->get('inputFilterEmail')) ?>
                            </span>
                        </div>
                        <div class="col"></div>
                        <div class="col-12" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col-12">
                            <div style="border: none;border-top: 5px solid black;width: 76%;justify-self: center;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: center;">
                            <span class="badge text-bg-primary fs-6">
                                Account
                            </span>
                        </div>
                        <div class="col" style="text-align: center;">
                            <span class="badge text-bg-primary fs-6">
                                Verification
                            </span>
                        </div>
                        <div class="col" style="text-align: center;">
                            <span class="badge text-bg-primary fs-6">
                                Devices
                            </span>
                        </div>
                        <div class="col" style="text-align: center;">
                            <span class="badge text-bg-primary fs-6">
                                PIC
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                        <div class="col" style="justify-items: center;">
                            <div class="vertical" style="border-left: 5px solid black; height: 50px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <?php foreach ($account_filter as $keys) { ?>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <a href="<?php echo base_url('bank_password?inputFilterAccount=' . $keys->account) ?>">
                                        <span class="badge text-bg-primary fs-6 mt-2 d-inline-block">
                                            <?php echo $keys->account; ?>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col">
                            <?php foreach ($verification_filter as $keys) { ?>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <span class="badge text-bg-primary fs-6 mt-2 d-inline-block">
                                        <?php echo $keys->verification; ?>
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col">
                            <?php foreach ($devices_filter as $keys) { ?>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <span class="badge text-bg-primary fs-6 mt-2 d-inline-block">
                                        <?php echo $keys->devices; ?>
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col">
                            <?php foreach ($role_filter as $keys) { ?>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <span class="badge text-bg-primary fs-6 mt-2 d-inline-block">
                                        <?php echo $keys->nama_role; ?>
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- End -->
                <div class="col-md-3 d-flex align-items-end">
                    <div>
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="<?= base_url('bank_password') ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col mt-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                Tambahkan Account dan Password
            </button>
        </div>
    </div>

    <!-- Start Tambah Account Modal -->
    <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabel">Tambah Account & Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo base_url('bank_password/createBankAccount'); ?>" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Account</label>
                            <select class="form-select" name="account" required>
                                <option value="">-- Pilih Account --</option>
                                <option value="alfamart">Alfamart</option>
                                <option value="gmail">Gmail</option>
                                <option value="tokopedia">Tokopedia</option>
                                <option value="shopee">Shopee</option>
                                <option value="tiktok">Tiktok</option>
                                <option value="lazada">Lazada</option>
                                <option value="forstock">Forstock</option>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="desty">Desty</option>
                                <option value="zalora">Zalora</option>
                                <option value="blibli">Blibli</option>
                                <option value="kotime">Kotime</option>
                                <option value="jagoan hosting">Jagoan Hosting</option>
                                <option value="email">Email</option>
                                <option value="fast">Fast</option>
                                <option value="dropbox">Dropbox</option>
                                <option value="compro">Compro</option>
                                <option value="website">Website</option>
                                <option value="lynk">Lynk</option>
                                <option value="linktree">Linktree</option>
                                <option value="apple">Apple</option>
                                <option value="canva">Canva</option>
                                <option value="pinterest">Pinterest</option>
                                <option value="google">Google</option>
                                <option value="ginee">Ginee</option>
                                <option value="tiktokshop kotime">Tiktokshop Kotime</option>
                                <option value="rumahweb">Rumahweb</option>
                                <option value="github">Github</option>
                                <option value="git pat">Git Pat</option>
                                <option value="ssh-access cpanel">SSH Access Cpanel</option>
                                <option value="tiktok kotime">Tiktok Kotime</option>
                                <option value="gopay">Gopay</option>
                                <option value="tokopedia kotime">Tokopedia Kotime</option>
                                <option value="shopee kotime">Shopee Kotime</option>
                                <option value="mitra asta tokopedia">Mitra Asta Tokopedia</option>
                                <option value="evermos">Evermos</option>
                                <option value="forstok">Forstok</option>
                                <option value="dana">Dana</option>
                                <option value="microsoft">Microsoft</option>
                                <option value="olx">OLX</option>
                                <option value="xendit">Xendit</option>
                                <option value="brevo">Brevo</option>
                                <option value="d9">Gudang D9</option>
                                <option value="d17">Gudang D17</option>
                                <option value="pajak.io">Pajak.io</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Browser</label>
                            <input type="text" class="form-control" name="browser">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" name="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="text" class="form-control" name="password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Verifikasi</label>
                            <input type="text" class="form-control" name="verification">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">-- Pilih Category --</option>
                                <option value="other">Other</option>
                                <option value="kotime">Kotime</option>
                                <option value="marketplace">Marketplace</option>
                                <option value="evermos">Evermos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PIC</label>
                            <select id="selectPic" class="form-select">
                                <option value="">-- Pilih PIC --</option>
                                <?php foreach ($users as $u) : ?>
                                <option value="<?= $u->iduser ?>"><?= $u->full_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- tempat bubble -->
                        <div id="picContainer" class="mb-3"></div>
                        <!-- hidden input untuk simpan id PIC -->
                        <input type="hidden" name="pic_ids" id="picIds">
                        <div class="mb-3">
                            <label class="form-label">Perangkat</label>
                            <select id="selectDevice" class="form-select">
                                <option value="">-- Pilih Perangkat --</option>
                                <?php foreach ($devices as $d) : ?>
                                <option value="<?= $d->idppl_devices ?>"><?= $d->devices ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- tempat bubble -->
                        <div id="devicesContainer" class="mb-3"></div>
                        <!-- hidden input untuk simpan id devices -->
                        <input type="hidden" name="devices_ids" id="devicesIds">
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Start Edit Modal -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Account & Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="post">
                    <input type="hidden" id="editId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Account</label>
                            <select class="form-select" id="editAccount" name="account" required>
                                <option value="">-- Pilih Account --</option>
                                <option value="alfamart">Alfamart</option>
                                <option value="gmail">Gmail</option>
                                <option value="tokopedia">Tokopedia</option>
                                <option value="shopee">Shopee</option>
                                <option value="tiktok">Tiktok</option>
                                <option value="lazada">Lazada</option>
                                <option value="forstock">Forstock</option>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="desty">Desty</option>
                                <option value="zalora">Zalora</option>
                                <option value="blibli">Blibli</option>
                                <option value="kotime">Kotime</option>
                                <option value="jagoan hosting">Jagoan Hosting</option>
                                <option value="email">Email</option>
                                <option value="fast">Fast</option>
                                <option value="dropbox">Dropbox</option>
                                <option value="compro">Compro</option>
                                <option value="website">Website</option>
                                <option value="lynk">Lynk</option>
                                <option value="linktree">Linktree</option>
                                <option value="apple">Apple</option>
                                <option value="canva">Canva</option>
                                <option value="pinterest">Pinterest</option>
                                <option value="google">Google</option>
                                <option value="ginee">Ginee</option>
                                <option value="tiktokshop kotime">Tiktokshop Kotime</option>
                                <option value="rumahweb">Rumahweb</option>
                                <option value="github">Github</option>
                                <option value="git pat">Git Pat</option>
                                <option value="ssh-access cpanel">SSH Access Cpanel</option>
                                <option value="tiktok kotime">Tiktok Kotime</option>
                                <option value="gopay">Gopay</option>
                                <option value="tokopedia kotime">Tokopedia Kotime</option>
                                <option value="shopee kotime">Shopee Kotime</option>
                                <option value="mitra asta tokopedia">Mitra Asta Tokopedia</option>
                                <option value="evermos">Evermos</option>
                                <option value="forstok">Forstok</option>
                                <option value="dana">Dana</option>
                                <option value="microsoft">Microsoft</option>
                                <option value="olx">OLX</option>
                                <option value="xendit">Xendit</option>
                                <option value="brevo">Brevo</option>
                                <option value="d9">Gudang D9</option>
                                <option value="d17">Gudang D17</option>
                                <option value="pajak.io">Pajak.io</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Browser</label>
                            <input type="text" class="form-control" id="editBrowser" name="browser">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="text" class="form-control" id="editPassword" name="password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Verifikasi</label>
                            <input type="text" class="form-control" id="editVerification" name="verification">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="editCategory" name="category">
                                <option value="">-- Pilih Category --</option>
                                <option value="other">Other</option>
                                <option value="kotime">Kotime</option>
                                <option value="marketplace">Marketplace</option>
                                <option value="evermos">Evermos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PIC</label>
                            <select id="editSelectPic" class="form-select">
                                <option value="">-- Pilih PIC --</option>
                                <?php foreach ($users as $u) : ?>
                                <option value="<?= $u->iduser ?>"><?= $u->full_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="editPicContainer" class="mb-3"></div>
                        <input type="hidden" name="pic_ids" id="editPicIds">
                        <div class="mb-3">
                            <label class="form-label">Devices</label>
                            <select id="editSelectDevice" class="form-select">
                                <option value="">-- Pilih Device --</option>
                                <?php foreach ($devices as $d) : ?>
                                <option value="<?= $d->idppl_devices ?>"><?= $d->devices ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="editDeviceContainer" class="mb-3"></div>
                        <input type="hidden" name="devices_ids" id="editDeviceIds">
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" id="editDescription" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Edit Modal -->

    <div class="row">
        <div class="col">
            <table id="tableReport" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Akun</th>
                        <th>PIC</th>
                        <th>Category</th>
                        <th>Browser</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Verifikasi</th>
                        <th>Devices</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data_bp as $vbp) { ?>
                    <tr data-id="<?php echo $vbp->idppl_bank_password; ?>">
                        <td><?php echo $no++; ?></td>
                        <td><?php echo ucfirst($vbp->account); ?></td>
                        <td><?php echo $vbp->role ? $vbp->role : '-'; ?></td>
                        <td><?php echo ucfirst($vbp->category); ?></td>
                        <td><?php echo $vbp->browser; ?></td>
                        <td><?php echo $vbp->email; ?></td>
                        <td><?php echo $vbp->password; ?></td>
                        <td><?php echo $vbp->verification; ?></td>
                        <td><?php echo $vbp->device_names ? $vbp->device_names : '-'; ?></td>
                        <td><?php echo $vbp->description; ?></td>
                        <td>
                            <button class="btn btn-warning btn-edit">Edit</button>
                            <button class="btn btn-danger btn-delete">Delete</button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url(); ?>js/scripts.js"></script>

<script>
    $(document).ready(function() {
        // --- DataTable init ---
        var table = new DataTable('#tableReport', {
            responsive: false,
            scrollX: true,
            layout: {
                bottomEnd: {
                    paging: {
                        firstLast: false
                    }
                }
            }
        });

        // ---------------- ADD PIC ----------------
        let selectedPics = [];

        $('#selectPic').on('change', function() {
            const userId = $.trim(this.value);
            if (!userId) {
                this.value = '';
                return;
            }

            if (!selectedPics.includes(userId)) {
                selectedPics.push(userId);
                updatePicBubbles();
            }
            this.value = "";
        });

        function updatePicBubbles() {
            const container = $('#picContainer');
            container.empty();

            selectedPics = Array.from(new Set(selectedPics.map(x => String(x).trim()).filter(Boolean)));

            selectedPics.forEach(id => {
                const userName = $(`#selectPic option[value="${id}"]`).text() || 'Unknown';
                const bubble = $(`
                <span class="badge bg-primary me-2 mb-2">
                    ${userName}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-1 remove-pic" data-id="${id}"></button>
                </span>`);
                container.append(bubble);
            });

            $('#picIds').val(selectedPics.join(','));
        }

        $('#picContainer').on('click', '.remove-pic', function() {
            const id = String($(this).data('id'));
            selectedPics = selectedPics.filter(item => String(item) !== id);
            updatePicBubbles();
        });

        // ---------------- ADD DEVICES ----------------
        let selectedDevices = [];

        $('#selectDevice').on('change', function() {
            const deviceId = $.trim(this.value);
            if (!deviceId) {
                this.value = '';
                return;
            }

            if (!selectedDevices.includes(deviceId)) {
                selectedDevices.push(deviceId);
                updateDeviceBubbles();
            }
            this.value = "";
        });

        function updateDeviceBubbles() {
            const container = $('#devicesContainer');
            container.empty();

            selectedDevices = Array.from(new Set(selectedDevices.map(x => String(x).trim()).filter(Boolean)));

            selectedDevices.forEach(id => {
                const deviceName = $(`#selectDevice option[value="${id}"]`).text() || 'Unknown';
                const bubble = $(`
                <span class="badge bg-primary me-2 mb-2">
                    ${deviceName}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-1 remove-devices" data-id="${id}"></button>
                </span>`);
                container.append(bubble);
            });

            $('#devicesIds').val(selectedDevices.join(','));
        }

        $('#devicesContainer').on('click', '.remove-devices', function() {
            const id = String($(this).data('id'));
            selectedDevices = selectedDevices.filter(item => String(item) !== id);
            updateDeviceBubbles();
        });

        // ---------------- Form Submit ADD ----------------
        $('#modalAdd form').on('submit', function() {
            $('#picIds').val(selectedPics.join(','));
            $('#devicesIds').val(selectedDevices.join(','));
        });

        // ---------------- EDIT PIC ----------------
        let editSelectedPics = [];

        $('#editSelectPic').on('change', function() {
            const userId = $.trim(this.value);
            if (!userId) {
                this.value = '';
                return;
            }

            if (!editSelectedPics.includes(userId)) {
                editSelectedPics.push(userId);
                updateEditBubbles();
            }
            this.value = "";
        });

        function updateEditBubbles() {
            const container = $('#editPicContainer');
            container.empty();

            editSelectedPics = Array.from(new Set(editSelectedPics.map(x => String(x).trim()).filter(Boolean)));

            editSelectedPics.forEach(id => {
                const userName = $(`#editSelectPic option[value="${id}"]`).text() || 'Unknown';
                const bubble = $(`
                <span class="badge bg-primary me-2 mb-2">
                    ${userName}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-1 remove-edit-pic" data-id="${id}"></button>
                </span>`);
                container.append(bubble);
            });

            $('#editPicIds').val(editSelectedPics.join(','));
        }

        $('#editPicContainer').on('click', '.remove-edit-pic', function() {
            const id = String($(this).data('id'));
            editSelectedPics = editSelectedPics.filter(item => String(item) !== id);
            updateEditBubbles();
        });

        // ---------------- EDIT DEVICES ----------------
        let editSelectedDevices = [];

        $("#editSelectDevice").on("change", function() {
            let id = $(this).val();
            if (id && !editSelectedDevices.includes(id)) {
                editSelectedDevices.push(id);
                updateEditDeviceBubbles();
            }
            $(this).val("");
        });

        function updateEditDeviceBubbles() {
            let container = $("#editDeviceContainer");
            container.html("");
            editSelectedDevices.forEach(id => {
                let text = $("#editSelectDevice option[value='" + id + "']").text();
                container.append(`
                <span class="badge bg-info me-1">
                    ${text}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-1 remove-edit-device" data-id="${id}"></button>
                </span>`);
            });
            $("#editDeviceIds").val(editSelectedDevices.join(","));
        }

        $(document).on("click", ".remove-edit-device", function() {
            let id = $(this).data("id");
            editSelectedDevices = editSelectedDevices.filter(d => d !== String(id));
            updateEditDeviceBubbles();
        });

        // ---------------- Handle Edit Button ----------------
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).closest('tr').data('id');

            // clear previous state
            editSelectedPics = [];
            editSelectedDevices = [];
            updateEditBubbles();
            updateEditDeviceBubbles();

            $.ajax({
                url: "<?= base_url('bank_password/edit/') ?>" + id,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (res.status === "success") {
                        let data = res.data;

                        $("#editId").val(id);
                        $("#editAccount").val(data.account);
                        $("#editBrowser").val(data.browser);
                        $("#editEmail").val(data.email);
                        $("#editPassword").val(data.password);
                        $("#editVerification").val(data.verification);
                        $("#editCategory").val(data.category);
                        $("#editDescription").val(data.description);

                        // PICs
                        if (data.pic_ids) {
                            editSelectedPics = data.pic_ids.split(',').map(s => s.trim()).filter(Boolean);
                            updateEditBubbles();
                        }

                        // ✅ FIX: pakai device_ids (bukan devices_ids)
                        if (data.device_ids) {
                            editSelectedDevices = data.device_ids.split(',').map(s => s.trim()).filter(Boolean);
                            updateEditDeviceBubbles();
                        }

                        $("#modalEdit").modal("show");
                    } else {
                        alert(res.message || "Gagal mengambil data.");
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert("Gagal mengambil data. Coba lagi!");
                }
            });
        });

        // ---------------- Handle Edit Form Submit ----------------
        $("#editForm").on("submit", function(e) {
            e.preventDefault();
            $('#editPicIds').val(editSelectedPics.join(','));
            $('#editDeviceIds').val(editSelectedDevices.join(','));

            let id = $("#editId").val();

            $.ajax({
                url: "<?= base_url('bank_password/updateBankAccount/') ?>" + id,
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(res) {
                    if (res.status === "success") {
                        alert(res.message);
                        $("#modalEdit").modal("hide");
                        location.reload();
                    } else {
                        alert(res.message || "Gagal memperbarui data.");
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat memperbarui data!");
                }
            });
        });

        // ---------------- Delete ----------------
        $(document).on('click', '.btn-delete', function() {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                let id = $(this).closest('tr').data('id');

                $.ajax({
                    url: "<?= base_url('bank_password/deleteBankAccount/') ?>" + id,
                    type: "POST",
                    dataType: "json",
                    success: function(res) {
                        if (res.status === "success") {
                            alert(res.message);
                            location.reload();
                        } else {
                            alert(res.message);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Gagal menghapus data. Coba lagi!");
                    }
                });
            }
        });
    });
</script>