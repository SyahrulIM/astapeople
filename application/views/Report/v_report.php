<!-- Page content-->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1 class="mt-4">Laporan</h1>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <strong>Filter</strong>
        </div>
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="absensi_start" class="form-label">Attendance Date (Start)</label>
                    <input type="date" id="absensi_start" name="absensi_start" class="form-control" value="<?= $this->input->get('absensi_start') ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="absensi_end" class="form-label">Attendance Date (End)</label>
                    <input type="date" id="absensi_end" name="absensi_end" class="form-control" value="<?= $this->input->get('absensi_end') ?>" required>
                </div>
                <?php if ($this->session->userdata('idrole') == 1 || $this->session->userdata('idrole') == 5) : ?>
                <div class="col-md-3">
                    <label for="employee" class="form-label">Employee</label>
                    <select name="employee" id="employee" class="form-select">
                        <option value="">-- Select Employee --</option>
                        <?php if (!empty($employee)) : ?>
                        <?php foreach ($employee as $emp) : ?>
                        <option value="<?= $emp->idppl_employee ?>" <?= ($this->input->get('employee') == $emp->idppl_employee) ? 'selected' : '' ?>>
                            <?= $emp->name ?>
                        </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-md-3 d-flex align-items-end">
                    <div>
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="<?= base_url('report') ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
            <hr>
            <div class="row">
                <div class="col text-center">
                    <h3>Summary</h3>
                </div>
            </div>
            <div class="row text-center">
                <div class="col">
                    <h5>Total Working Days</h5>
                    <p><?= $summary['total_days'] ?? 0 ?></p>
                </div>
                <div class="col">
                    <h5>Staff</h5>
                    <p><?= $summary['staff'] ?? 0 ?></p>
                </div>
                <div class="col">
                    <h5>Absent</h5>
                    <p><?= $summary['absent'] ?? 0 ?></p>
                </div>
                <div class="col">
                    <h5>Incomplete Attendance</h5>
                    <p><?= $summary['incomplete'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Edit Modal -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Absen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="permitForm" method="post" action="<?= base_url('report/edit') ?>">
                    <div class="modal-body">
                        <input type="hidden" name="employee_id" id="modalEmployeeId">
                        <input type="hidden" name="date" id="modalDate">

                        <!-- Info Absen -->
                        <div class="mb-3">
                            <h6 class="fw-bold">Attendance Detail</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Employee</th>
                                    <td id="detailEmployee"></td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td id="detailDate"></td>
                                </tr>
                                <tr>
                                    <th>Check In</th>
                                    <td id="detailCheckIn"></td>
                                </tr>
                                <tr>
                                    <th>Check Out</th>
                                    <td id="detailCheckOut"></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td id="detailStatus"></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Input Edit -->
                        <div class="mb-3">
                            <label for="time_start" class="form-label">Start Time</label>
                            <input type="time" class="form-control" name="time_start" id="time_start" required>
                        </div>
                        <div class="mb-3">
                            <label for="time_end" class="form-label">End Time</label>
                            <input type="time" class="form-control" name="time_end" id="time_end" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End -->

    <div class="row">
        <div class="col">
            <table id="tableReport" class="display" style="width:100%">
                <!-- In the table header -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Full Name</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Late(Min)</th>
                        <th>Early Leave(Min)</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Status Edit</th>
                        <?php if ($this->session->userdata('idrole') == 1 || $this->session->userdata('idrole') == 5) { ?>
                        <td>Action</td>
                        <?php } ?>
                    </tr>
                </thead>

                <!-- In the table body -->
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($presence as $row) {
                        $date = $row->date;
                        $checkIn = $row->check_in;
                        $checkOut = $row->check_out;
                        $reason = $row->reason;
                        $isPermission = $row->is_permission;
                        $weekday = date('w', strtotime($date));
                        $isWeekend = ($weekday == 0);
                        $isSaturday = ($weekday == 6);
                        $isNationalHoliday = $row->holiday_type === 'National Holiday';
                        $isEdit = $row->is_edit;

                        // Time thresholds
                        $workStart = ($isSaturday ? '08:10:00' : '08:10:00');
                        $workEnd   = ($isSaturday ? '13:00:00' : '16:30:00');

                        $isLate = $checkIn && $checkIn > $workStart;
                        $isEarlyLeave = $checkOut && $checkOut < $workEnd;

                        // Calculate lateness and early leave in minutes
                        $lateMinutes = 0;
                        $earlyLeaveMinutes = 0;

                        if ($isLate) {
                            $lateMinutes = (strtotime($checkIn) - strtotime($workStart)) / 60;
                        }

                        if ($isEarlyLeave) {
                            $earlyLeaveMinutes = (strtotime($workEnd) - strtotime($checkOut)) / 60;
                        }
                        ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row->name ?></td>
                        <td><?= $date ?></td>
                        <td><?= $checkIn ?: '-' ?></td>
                        <td><?= $checkOut ?: '-' ?></td>
                        <td><?= $lateMinutes > 0 ? round($lateMinutes) : '-' ?></td>
                        <td><?= $earlyLeaveMinutes > 0 ? round($earlyLeaveMinutes) : '-' ?></td>
                        <?php
                            $isVerify = isset($row->is_verify) ? $row->is_verify : 0;
                            if (empty($reason)) { ?>
                        <td>-</td>
                        <?php } elseif ($reason === 'Dinas' && $isVerify === '1') { ?>
                        <td><span class="badge text-bg-success">Dinas</span></td>
                        <?php } elseif (($reason === 'Sick' && $isVerify === '1') || ($reason === 'Personal' && $isVerify === '1') || ($reason === 'Cuti' && $isVerify === '1')
                            ) { ?>
                        <td><span class="badge text-bg-danger"><?= $reason; ?></span></td>
                        <?php } else { ?>
                        <td>Menunggu Verifikasi</td>
                        <?php } ?>
                        <td>
                            <?php if ($isWeekend) : ?>
                            <span class="badge bg-danger">Minggu/Libur</span>
                            <?php elseif ($isNationalHoliday) : ?>
                            <span class="badge bg-danger">National Holiday</span>
                            <?php elseif (empty($checkIn) && empty($checkOut)) : ?>
                            <span class="badge bg-danger">Absent</span>
                            <?php elseif (empty($checkIn) || empty($checkOut)) : ?>
                            <span class="badge bg-danger">Incomplete</span>
                            <?php elseif ($isLate && $isEarlyLeave) : ?>
                            <span class="badge bg-danger">Late & Early Leave</span>
                            <?php elseif ($isLate) : ?>
                            <span class="badge bg-danger">Late</span>
                            <?php elseif ($isEarlyLeave) : ?>
                            <span class="badge bg-danger">Early Leave</span>
                            <?php else : ?>
                            <span class="badge bg-success">Present</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($isEdit == 1) { ?>
                            <span class="badge bg-warning">Diedit</span>
                            <?php } else { ?>
                            <span class="badge bg-success">Tidak Diedit</span>
                            <?php } ?>
                        </td>
                        <?php if ($this->session->userdata('idrole') == 1 || $this->session->userdata('idrole') == 5) { ?>
                        <td>
                            <button type="button" class="btn btn-warning btn-edit" data-employee-id="<?= $row->idppl_employee ?>" data-date="<?= $date ?>" data-reason="<?= $reason ?>">
                                Edit Absensi
                            </button>
                        </td>
                        <?php } ?>
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
        // Initialize DataTable
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

        // Handle permit button click
        $('#tableReport').on('click', '.btn-permit', function() {
            var employeeId = $(this).data('employee-id');
            var employeeName = $(this).closest('tr').find('td:eq(1)').text();
            var date = $(this).data('date');
            var existingReason = $(this).data('reason');

            // Set values in modal
            $('#modalEmployeeId').val(employeeId);
            $('#modalDate').val(date);
            $('#permitModal .modal-title').text('Submit Permit for ' + employeeName + ' - ' + date);

            if (existingReason && existingReason !== '-') {
                $('#reason').val(existingReason);
            } else {
                $('#reason').val('');
            }

            // Show modal
            var modal = new bootstrap.Modal(document.getElementById('permitModal'));
            modal.show();
        });
    });

    $(document).on("click", ".btn-edit", function() {
        let employeeId = $(this).data("employee-id");
        let date = $(this).data("date");

        $.ajax({
            url: "<?= base_url('report/get_attendance_detail') ?>",
            type: "POST",
            dataType: "json",
            data: {
                employee_id: employeeId,
                date: date
            },
            success: function(res) {
                if (res.status === "success") {
                    let data = res.data;

                    // isi detail info
                    $("#detailEmployee").text(data.employee_name);
                    $("#detailDate").text(data.date);
                    $("#detailCheckIn").text(data.check_in || "-");
                    $("#detailCheckOut").text(data.check_out || "-");
                    $("#detailStatus").text(data.status || "-");

                    // isi input form
                    $("#modalEmployeeId").val(employeeId);
                    $("#modalDate").val(data.date);
                    $("#time_start").val(data.check_in);
                    $("#time_end").val(data.check_out);

                    // buka modal
                    $("#modalEdit").modal("show");
                } else {
                    alert(res.message);
                }
            },
            error: function() {
                alert("Gagal ambil data. Coba lagi!");
            }
        });
    });

    $(document).on("submit", "#permitForm", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= base_url('report/edit') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res) {
                if (res.status === "success") {
                    alert(res.message);
                    $("#modalEdit").modal("hide");
                    location.reload();
                } else {
                    alert(res.message);
                }
            },
            error: function() {
                alert("Terjadi kesalahan saat update absensi!");
            }
        });
    });
</script>