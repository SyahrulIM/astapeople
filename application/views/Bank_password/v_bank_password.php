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
                        <option value="gmail" <?php if ($this->input->get('inputFilterAccount') === 'gmail') {
                                                    echo 'selected';
                                                } ?>>Gmail</option>
                        <option value="tokopedia" <?php if ($this->input->get('inputFilterAccount') === 'tokopedia') {
                                                        echo 'selected';
                                                    } ?>>Tokopedia</option>
                        <option value="shopee" <?php if ($this->input->get('inputFilterAccount') === 'shopee') {
                                                    echo 'selected';
                                                } ?>>Shopee</option>
                        <option value="tiktok" <?php if ($this->input->get('inputFilterAccount') === 'tiktok') {
                                                    echo 'selected';
                                                } ?>>Tiktok</option>
                        <option value="lazada" <?php if ($this->input->get('inputFilterAccount') === 'lazada') {
                                                    echo 'selected';
                                                } ?>>Lazada</option>
                        <option value="forstock" <?php if ($this->input->get('inputFilterAccount') === 'forstock') {
                                                        echo 'selected';
                                                    } ?>>Forstock</option>
                        <option value="facebook" <?php if ($this->input->get('inputFilterAccount') === 'facebook') {
                                                        echo 'selected';
                                                    } ?>>Facebook</option>
                        <option value="instagram" <?php if ($this->input->get('inputFilterAccount') === 'instagram') {
                                                        echo 'selected';
                                                    } ?>>Instagram</option>
                        <option value="desty" <?php if ($this->input->get('inputFilterAccount') === 'desty') {
                                                    echo 'selected';
                                                } ?>>Desty</option>
                        <option value="zalora" <?php if ($this->input->get('inputFilterAccount') === 'zalora') {
                                                    echo 'selected';
                                                } ?>>Zalora</option>
                        <option value="blibli" <?php if ($this->input->get('inputFilterAccount') === 'blibli') {
                                                    echo 'selected';
                                                } ?>>Blibli</option>
                        <option value="kotime" <?php if ($this->input->get('inputFilterAccount') === 'kotime') {
                                                    echo 'selected';
                                                } ?>>Kotime</option>
                        <option value="jagoan hosting" <?php if ($this->input->get('inputFilterAccount') === 'jagoan hosting') {
                                                            echo 'selected';
                                                        } ?>>Jagoan Hosting</option>
                        <option value="email" <?php if ($this->input->get('inputFilterAccount') === 'email') {
                                                    echo 'selected';
                                                } ?>>Email</option>
                        <option value="fast" <?php if ($this->input->get('inputFilterAccount') === 'fast') {
                                                    echo 'selected';
                                                } ?>>Fast</option>
                        <option value="dropbox" <?php if ($this->input->get('inputFilterAccount') === 'dropbox') {
                                                    echo 'selected';
                                                } ?>>Dropbox</option>
                        <option value="compro" <?php if ($this->input->get('inputFilterAccount') === 'compro') {
                                                    echo 'selected';
                                                } ?>>Compro</option>
                        <option value="website" <?php if ($this->input->get('inputFilterAccount') === 'website') {
                                                    echo 'selected';
                                                } ?>>Website</option>
                        <option value="lynk" <?php if ($this->input->get('inputFilterAccount') === 'lynk') {
                                                    echo 'selected';
                                                } ?>>Lynk</option>
                        <option value="linktree" <?php if ($this->input->get('inputFilterAccount') === 'linktree') {
                                                        echo 'selected';
                                                    } ?>>Linktree</option>
                        <option value="apple" <?php if ($this->input->get('inputFilterAccount') === 'apple') {
                                                    echo 'selected';
                                                } ?>>Apple</option>
                        <option value="canva" <?php if ($this->input->get('inputFilterAccount') === 'canva') {
                                                    echo 'selected';
                                                } ?>>Canva</option>
                        <option value="pinterest" <?php if ($this->input->get('inputFilterAccount') === 'pinterest') {
                                                        echo 'selected';
                                                    } ?>>Pinterest</option>
                        <option value="google" <?php if ($this->input->get('inputFilterAccount') === 'google') {
                                                    echo 'selected';
                                                } ?>>Google</option>
                        <option value="ginee" <?php if ($this->input->get('inputFilterAccount') === 'ginee') {
                                                    echo 'selected';
                                                } ?>>Ginee</option>
                        <option value="tiktokshop kotime" <?php if ($this->input->get('inputFilterAccount') === 'tiktokshop kotime') {
                                                                echo 'selected';
                                                            } ?>>Tiktokshop Kotime</option>
                        <option value="rumahweb" <?php if ($this->input->get('inputFilterAccount') === 'rumahweb') {
                                                        echo 'selected';
                                                    } ?>>Rumahweb</option>
                        <option value="github" <?php if ($this->input->get('inputFilterAccount') === 'github') {
                                                    echo 'selected';
                                                } ?>>Github</option>
                        <option value="git pat" <?php if ($this->input->get('inputFilterAccount') === 'git pat') {
                                                    echo 'selected';
                                                } ?>>Git Pat</option>
                        <option value="ssh-access cpanel" <?php if ($this->input->get('inputFilterAccount') === 'ssh-access cpanel') {
                                                                echo 'selected';
                                                            } ?>>SSH Access Cpanel</option>
                        <option value="tiktok kotime" <?php if ($this->input->get('inputFilterAccount') === 'tiktok kotime') {
                                                            echo 'selected';
                                                        } ?>>Tiktok Kotime</option>
                        <option value="gopay" <?php if ($this->input->get('inputFilterAccount') === 'gopay') {
                                                    echo 'selected';
                                                } ?>>Gopay</option>
                        <option value="tokopedia kotime" <?php if ($this->input->get('inputFilterAccount') === 'tokopedia kotime') {
                                                                echo 'selected';
                                                            } ?>>Tokopedia Kotime</option>
                        <option value="shopee kotime" <?php if ($this->input->get('inputFilterAccount') === 'shopee kotime') {
                                                            echo 'selected';
                                                        } ?>>Shopee Kotime</option>
                        <option value="mitra asta tokopedia" <?php if ($this->input->get('inputFilterAccount') === 'mitra asta tokopedia') {
                                                                    echo 'selected';
                                                                } ?>>Mitra Asta Tokopedia</option>
                        <option value="evermos" <?php if ($this->input->get('inputFilterAccount') === 'evermos') {
                                                    echo 'selected';
                                                } ?>>Evermos</option>
                        <option value="forstok" <?php if ($this->input->get('inputFilterAccount') === 'forstok') {
                                                    echo 'selected';
                                                } ?>>Forstok</option>
                        <option value="dana" <?php if ($this->input->get('inputFilterAccount') === 'dana') {
                                                    echo 'selected';
                                                } ?>>Dana</option>
                        <option value="microsoft" <?php if ($this->input->get('inputFilterAccount') === 'microsoft') {
                                                        echo 'selected';
                                                    } ?>>Microsoft</option>
                        <option value="olx" <?php if ($this->input->get('inputFilterAccount') === 'olx') {
                                                echo 'selected';
                                            } ?>>OLX</option>
                        <option value="xendit" <?php if ($this->input->get('inputFilterAccount') === 'xendit') {
                                                    echo 'selected';
                                                } ?>>Xendit</option>
                        <option value="brevo" <?php if ($this->input->get('inputFilterAccount') === 'brevo') {
                                                    echo 'selected';
                                                } ?>>Brevo</option>
                        <option value="cctv d9" <?php if ($this->input->get('inputFilterAccount') === 'cctv d9') {
                                                    echo 'selected';
                                                } ?>>CCTV D9</option>
                        <option value="cctv d17" <?php if ($this->input->get('inputFilterAccount') === 'cctv d17') {
                                                        echo 'selected';
                                                    } ?>>CCTV D17</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="inputFilterCategory" class="form-label">Category</label>
                    <select id="inputFilterCategory" name="inputFilterCategory" class="form-select">
                        <option value="" <?= $this->input->get('inputFilterCategory') === '' ? 'selected' : '' ?>>Semua</option>
                        <option value="other" <?= $this->input->get('inputFilterCategory') == 'other' ? 'selected' : '' ?>>Other</option>
                        <option value="kotime" <?= $this->input->get('inputFilterCategory') == 'kotime' ? 'selected' : '' ?>>Kotime</option>
                        <option value="marketplace" <?= $this->input->get('inputFilterCategory') == 'marketplace' ? 'selected' : '' ?>>Marketplace</option>
                        <option value="evermos" <?= $this->input->get('inputFilterCategory') == 'evermos' ? 'selected' : '' ?>>Evermos</option>
                    </select>
                </div>
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
                                <option value="cctv d9">CCTV D9</option>
                                <option value="cctv d17">CCTV D17</option>
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
                            <input type="text" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Verifikasi</label>
                            <input type="text" class="form-control" name="verification">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" required>
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
                                <option value="cctv d9">CCTV D9</option>
                                <option value="cctv d17">CCTV D17</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Browser</label>
                            <input type="text" class="form-control" id="editBrowser" name="browser">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="text" class="form-control" id="editPassword" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Verifikasi</label>
                            <input type="text" class="form-control" id="editVerification" name="verification">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="editCategory" name="category" required>
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
                        <td><?php echo $vbp->pic_names ? $vbp->pic_names : '-'; ?></td>
                        <td><?php echo ucfirst($vbp->category); ?></td>
                        <td><?php echo $vbp->browser; ?></td>
                        <td><?php echo $vbp->email; ?></td>
                        <td><?php echo $vbp->password; ?></td>
                        <td><?php echo $vbp->verification; ?></td>
                        <td><?php echo $vbp->description; ?></td>
                        <td>
                            <button class="btn btn-warning btn-edit">
                                Edit
                            </button>
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
        // --- DataTable init (biarkan seperti semula) ---
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
                updateBubbles();
            }
            this.value = ""; // reset dropdown setelah pilih
        });

        function updateBubbles() {
            const container = $('#picContainer');
            container.empty();

            // normalize & dedupe
            selectedPics = Array.from(new Set(selectedPics.map(x => String(x).trim()).filter(Boolean)));

            selectedPics.forEach(id => {
                const userName = $(`#selectPic option[value="${id}"]`).text() || 'Unknown';
                const bubble = $(`
                <span class="badge bg-primary me-2 mb-2">
                    ${userName}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-1 remove-pic" data-id="${id}" aria-label="Remove"></button>
                </span>`);
                container.append(bubble);
            });

            $('#picIds').val(selectedPics.join(','));
        }

        $('#picContainer').on('click', '.remove-pic', function() {
            const id = String($(this).data('id'));
            selectedPics = selectedPics.filter(item => String(item) !== id);
            updateBubbles();
        });

        // Ensure hidden pic_ids is correct before actual form submit (Add form)
        $('#modalAdd form').on('submit', function() {
            $('#picIds').val(selectedPics.join(','));
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
            this.value = ""; // reset dropdown setelah pilih
        });

        function updateEditBubbles() {
            const container = $('#editPicContainer');
            container.empty();

            // normalize & dedupe
            editSelectedPics = Array.from(new Set(editSelectedPics.map(x => String(x).trim()).filter(Boolean)));

            editSelectedPics.forEach(id => {
                const userName = $(`#editSelectPic option[value="${id}"]`).text() || 'Unknown';
                const bubble = $(`
                <span class="badge bg-primary me-2 mb-2">
                    ${userName}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-1 remove-edit-pic" data-id="${id}" aria-label="Remove"></button>
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

        // Handle Edit Button Click (ajax fetch, then populate)
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).closest('tr').data('id');

            // clear previous edit state
            editSelectedPics = [];
            updateEditBubbles();

            $.ajax({
                url: "<?= base_url('bank_password/edit/') ?>" + id,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (res.status === "success") {
                        let data = res.data;

                        // Fill form with existing data
                        $("#editId").val(id);
                        $("#editAccount").val(data.account);
                        $("#editBrowser").val(data.browser);
                        $("#editEmail").val(data.email);
                        $("#editPassword").val(data.password);
                        $("#editVerification").val(data.verification);
                        $("#editCategory").val(data.category);
                        $("#editDescription").val(data.description);

                        // Handle PICs (trim spaces and ignore empty)
                        let picIds = [];
                        if (data.pic_ids && data.pic_ids.length) {
                            picIds = data.pic_ids.split(',').map(s => s.trim()).filter(Boolean);
                        }
                        editSelectedPics = picIds;
                        updateEditBubbles();

                        // Show modal
                        $("#modalEdit").modal("show");
                    } else {
                        alert(res.message || "Gagal mengambil data.");
                    }
                },
                error: function(xhr, status, err) {
                    console.error(xhr.responseText);
                    alert("Gagal mengambil data. Coba lagi!");
                }
            });
        });

        // Handle Edit Form Submission (AJAX)
        $("#editForm").on("submit", function(e) {
            e.preventDefault();

            // make sure hidden input up-to-date
            $('#editPicIds').val(editSelectedPics.join(','));

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
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat memperbarui data!");
                }
            });
        });

        // Delete handler (biarkan seperti semula)
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
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Gagal menghapus data. Coba lagi!");
                    }
                });
            }
        });

    });
</script>