            <!-- Page content-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h1 class="mt-4"> Pengajuan Ijin</h1>
                    </div>
                </div>

                <!-- Button trigger modal Tambah Pengguna -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRequest">
                    <i class="fa-solid fa-plus"></i> Tambah Pengajuan Ijin
                </button>
                <!-- End -->

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

                <!-- Modal Tambah Pengguna -->
                <div class="modal fade" id="addRequest" tabindex="-1" aria-labelledby="addRequestLabel" aria-hidden="true">
                    <form method="post" action="<?php echo base_url('time_off/addRequest') ?>" enctype="multipart/form-data" id="addRequestForm">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="addRequestLabel">Tambah Pengajuan Ijin</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Alasan</label>
                                        <select class="form-select" id="reason" name="reason" required>
                                            <option value="">-- Pilih Alasan --</option>
                                            <option value="Sick">Sakit</option>
                                            <option value="Personal">Personal</option>
                                            <option value="Cuti">Cuti</option>
                                            <option value="Dinas">Dinas</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dateRequest" class="form-label">Tanggal diajukan</label>
                                        <input type="date" class="form-control" id="dateRequest" name="dateRequest" min="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End -->

                <!-- Modal Logout Confirmation -->
                <div class="modal fade" id="logoutConfirmationModal" tabindex="-1" aria-labelledby="logoutConfirmationLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="logoutConfirmationLabel">Konfirmasi Logout</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Pengajuan ijin berhasil dibuat. Apakah Anda ingin logout sekarang?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                <a href="<?php echo base_url('auth/logout'); ?>" class="btn btn-primary">Ya, Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End -->

                <!-- Modal Edit Request -->
                <div class="modal fade" id="editRequest" tabindex="-1" aria-labelledby="editRequestLabel" aria-hidden="true">
                    <form method="post" action="<?php echo base_url('time_off/editRequest') ?>" enctype="multipart/form-data">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="editRequestLabel">Edit Request Time Off</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="idtime_off" id="edit_idtime_off">
                                    <div class="mb-3">
                                        <label for="edit_reason" class="form-label">Reason</label>
                                        <select class="form-select" id="edit_reason" name="reason" required>
                                            <option value="">-- Select Reason --</option>
                                            <option value="Sick">Sick</option>
                                            <option value="Personal">Personal</option>
                                            <option value="Cuti">Cuti</option>
                                            <option value="Dinas">Dinas</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_dateRequest" class="form-label">Date Request</label>
                                        <input type="date" class="form-control" id="edit_dateRequest" name="dateRequest" min="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End -->

                <!-- Modal Konfirmasi Verifikasi -->
                <div class="modal fade" id="modalVerify" tabindex="-1" aria-labelledby="modalVerifyLabel" aria-hidden="true">
                    <form method="post" action="<?= base_url('time_off/verifyRequest') ?>">
                        <input type="hidden" name="idtime_off" id="verify_id">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Verifikasi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin memverifikasi request time off ini?</p>
                                    <ul>
                                        <li><strong>Tanggal:</strong> <span id="verify_date"></span></li>
                                        <li><strong>Alasan:</strong> <span id="verify_reason"></span></li>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="action" value="1" class="btn btn-success">Setujui</button>
                                    <button type="submit" name="action" value="2" class="btn btn-danger">Tolak</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End -->

                <!-- Start Modal Konfirmasi Delete -->
                <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="<?= site_url('time_off/deleteRequest') ?>">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="modalDeleteLabel">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="idtime_off" id="delete_id">
                                    <p>
                                        Apakah kamu yakin ingin menghapus request
                                        <b id="delete_reason"></b> pada tanggal
                                        <b id="delete_date"></b>?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End -->

                <div class="row">
                    <div class="col">
                        <table id="tableproduct" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <?php if ($this->session->userdata('idrole') == 1 || $this->session->userdata('idrole') == 6) { ?>
                                    <th>Nama</th>
                                    <?php } ?>
                                    <th>Tanggal</th>
                                    <th>Alasan</th>
                                    <th>Status Verify</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($data_time_off as $dtokey => $dtovalue) { ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <?php if ($this->session->userdata('idrole') == 1 || $this->session->userdata('idrole') == 6) { ?>
                                    <td><?= $dtovalue->full_name; ?></td>
                                    <?php } ?>
                                    <td><?= $dtovalue->date; ?></td>
                                    <td><?= $dtovalue->reason; ?></td>
                                    <td>
                                        <?php if ($dtovalue->is_verify == 1) { ?>
                                        <span class="badge text-bg-success">Disetujui</span>
                                        <?php } elseif ($dtovalue->is_verify == 2) { ?>
                                        <span class="badge text-bg-danger">Ditolak</span>
                                        <?php } else { ?>
                                        <span class="badge text-bg-warning">Pending</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <!-- Tombol Edit Request -->
                                        <button type="button" class="btn btn-warning btn-edit" data-id="<?= $dtovalue->idtime_off ?>" data-date="<?= $dtovalue->date ?>" data-reason="<?= $dtovalue->reason ?>" data-bs-toggle="modal" data-bs-target="#editRequest">
                                            Edit Request
                                        </button>

                                        <!-- Tombol Edit Verifikasi (hanya jika role admin) -->
                                        <?php if ($this->session->userdata('idrole') == 1 || $this->session->userdata('idrole') == 6) { ?>
                                        <button type="button" class="btn btn-success btn-verify" data-id="<?= $dtovalue->idtime_off ?>" data-reason="<?= $dtovalue->reason ?>" data-date="<?= $dtovalue->date ?>" data-bs-toggle="modal" data-bs-target="#modalVerify">
                                            Edit Verifikasi
                                        </button>
                                        <?php } ?>

                                        <?php if ($this->session->userdata('idrole') == 1 || $this->session->userdata('idrole') == 6) { ?>
                                        <button type="button" class="btn btn-danger btn-delete" data-id="<?= $dtovalue->idtime_off ?>" data-reason="<?= $dtovalue->reason ?>" data-date="<?= $dtovalue->date ?>" data-bs-toggle="modal" data-bs-target="#modalDelete">
                                            Delete
                                        </button>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
            <!-- 2. DataTables JS -->
            <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
            <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
            <script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
            <script src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.dataTables.js"></script>
            <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
            <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.dataTables.js"></script>
            <!-- 3. Bootstrap bundle -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
            <!-- 4. Core theme JS -->
            <script src="<?php echo base_url(); ?>js/scripts.js"></script>

            <!-- Initialize DataTables AFTER all scripts are loaded -->
            <script>
                $(document).ready(function() {
                    new DataTable('#tableproduct', {
                        responsive: true,
                        layout: {
                            bottomEnd: {
                                paging: {
                                    firstLast: false
                                }
                            }
                        }
                    });

                    // Check if we need to show logout modal after successful submission
                    <?php if ($this->session->flashdata('show_logout_modal')) : ?>
                    // Clear the flashdata so it doesn't show again on refresh
                    <?php $this->session->unset_userdata('show_logout_modal'); ?>

                    // Show the logout confirmation modal
                    setTimeout(function() {
                        var logoutModal = new bootstrap.Modal(document.getElementById('logoutConfirmationModal'));
                        logoutModal.show();
                    }, 1000); // Show after 1 second delay
                    <?php endif; ?>
                });

                document.addEventListener('DOMContentLoaded', function() {
                    const editButtons = document.querySelectorAll('.btn-edit');

                    editButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const reason = this.getAttribute('data-reason');
                            const date = this.getAttribute('data-date');

                            document.getElementById('edit_idtime_off').value = id;
                            document.getElementById('edit_reason').value = reason;
                            document.getElementById('edit_dateRequest').value = date;
                        });
                    });
                });
                document.addEventListener('DOMContentLoaded', function() {
                    const verifyButtons = document.querySelectorAll('.btn-verify');

                    verifyButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const reason = this.getAttribute('data-reason');
                            const date = this.getAttribute('data-date');

                            document.getElementById('verify_id').value = id;
                            document.getElementById('verify_reason').textContent = reason;
                            document.getElementById('verify_date').textContent = date;
                        });
                    });
                });
                document.addEventListener('DOMContentLoaded', function() {
                    const deleteButtons = document.querySelectorAll('.btn-delete');
                    deleteButtons.forEach(btn => {
                        btn.addEventListener('click', function() {
                            document.getElementById('delete_id').value = this.getAttribute('data-id');
                            document.getElementById('delete_reason').textContent = this.getAttribute('data-reason');
                            document.getElementById('delete_date').textContent = this.getAttribute('data-date');
                        });
                    });
                });
            </script>
            </body>

            </html>