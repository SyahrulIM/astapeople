            <!-- Page content-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h1 class="mt-4">Absensi</h1>
                    </div>
                </div>
                <!-- Start Import Absensi .xlsx -->
                <form action="<?= base_url('presence/import') ?>" method="post" enctype="multipart/form-data">
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>Import Absensi (xlsx)</strong>
                            <div>
                                <a href="<?= base_url('assets\template_excel\absensi\1_StandardReport.xls') ?>" class="btn btn-sm btn-success me-2" download>
                                    Download Template Absensi
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="month" class="form-label">Select Month:</label>
                                    <select name="month" id="month" class="form-select" required>
                                        <?php
                                        $months = [
                                            '01' => 'January', '02' => 'February', '03' => 'March',
                                            '04' => 'April', '05' => 'May', '06' => 'June',
                                            '07' => 'July', '08' => 'August', '09' => 'September',
                                            '10' => 'October', '11' => 'November', '12' => 'December',
                                        ];
                                        foreach ($months as $num => $name) {
                                            $selected = ($num == date('m')) ? 'selected' : '';
                                            echo "<option value=\"$num\" $selected>$name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="year" class="form-label">Select Year:</label>
                                    <select name="year" id="year" class="form-select" required>
                                        <?php
                                        $current_year = date('Y');
                                        for ($i = $current_year; $i >= $current_year - 5; $i--) {
                                            $selected = ($i == $current_year) ? 'selected' : '';
                                            echo "<option value=\"$i\" $selected>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="file" class="form-label">Pilih File Excel:</label>
                                <input type="file" class="form-control" name="file" id="file" accept=".xlsx,.xls,.csv" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
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
                <div class="row">
                    <div class="col">
                        <table id="tableproduct" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User Input</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($presence as $pkey => $pvalue) { ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $pvalue->created_by; ?></td>
                                    <td><?php echo $pvalue->created_date; ?></td>
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
                });

                document.addEventListener("DOMContentLoaded", function() {
                    const form = document.querySelector('#addUser form');
                    const inputFoto = document.getElementById('inputFoto');

                    function isValidImage(file) {
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                        return allowedTypes.includes(file.type);
                    }

                    form.addEventListener('submit', function(e) {
                        if (inputFoto.files.length > 0 && !isValidImage(inputFoto.files[0])) {
                            alert('Format file Foto harus JPG, JPEG, atau PNG.');
                            e.preventDefault();
                            return;
                        }
                    });
                });

                document.addEventListener('DOMContentLoaded', function() {
                    const editButtons = document.querySelectorAll('.btnEditUser');

                    editButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const full_name = this.getAttribute('data-full_name');
                            const username = this.getAttribute('data-username');
                            const email = this.getAttribute('data-email');
                            const foto = this.getAttribute('data-foto');
                            const idrole = this.getAttribute('data-idrole');

                            document.getElementById('editNamaLengkap').value = full_name;
                            document.getElementById('editUsername').value = username;
                            document.getElementById('editEmail').value = email;

                            // Reset value file karena keamanan browser tidak izinkan isi file input diset via JS
                            document.getElementById('editFoto').value = '';

                            // Set selected option pada select role
                            const selectRole = document.getElementById('editRole');
                            Array.from(selectRole.options).forEach(option => {
                                if (option.value === idrole) {
                                    option.selected = true;
                                } else {
                                    option.selected = false;
                                }
                            });
                        });
                    });
                });
            </script>
            </body>

            </html>