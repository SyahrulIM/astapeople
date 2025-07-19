<!-- Page content-->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1 class="mt-4">Attendance Report</h1>
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
                <div class="col-md-3">
                    <label for="employee" class="form-label">Employee</label>
                    <select name="employee" id="employee" class="form-select">
                        <option value="">-- Select Employee --</option>
                        <?php foreach ($employee as $emp) : ?>
                            <option value="<?= $emp->idppl_employee ?>" <?= ($this->input->get('employee') == $emp->idppl_employee) ? 'selected' : '' ?>>
                                <?= $emp->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
                    <p><?php echo $total_days; ?></p>
                </div>
                <div class="col">
                    <h5>Present</h5>
                    <p><?= $summary['present'] ?? 0 ?></p>
                </div>
                <div class="col">
                    <h5>Absent</h5>
                    <p><?= $summary['absent'] ?? 0 ?></p>
                </div>
                <div class="col">
                    <h5>National Holiday</h5>
                    <p><?= $summary['national_holiday'] ?? 0 ?></p>
                </div>
                <div class="col">
                    <h5>Incomplete Attendance</h5>
                    <p><?= $summary['incomplete'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table id="tableReport" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Full Name</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Late(Min)</th>
                        <th>Early Leave(Min)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($presence as $row) {
                        $date = $row->date;
                        $checkIn = $row->check_in;
                        $checkOut = $row->check_out;
                        $weekday = date('w', strtotime($date));
                        $isWeekend = ($weekday == 0);
                        $isSaturday = ($weekday == 6);
                        $isNationalHoliday = !$isWeekend && isset($absent_count_by_date[$date]) && $absent_count_by_date[$date] > 8;

                        // Time thresholds
                        $workStart = ($isSaturday ? '08:10:00' : '08:10:00');
                        $workEnd   = ($isSaturday ? '13:00:00' : '16:30:00');

                        $isLate = $checkIn && $checkIn > $workStart;
                        $isEarlyLeave = $checkOut && $checkOut < $workEnd;

                        // Hitung keterlambatan dan pulang awal (dalam menit)
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
                            <td><?= $checkIn ?></td>
                            <td><?= $checkOut ?></td>
                            <td><?= $lateMinutes > 0 ? round($lateMinutes) : '' ?></td>
                            <td><?= $earlyLeaveMinutes > 0 ? round($earlyLeaveMinutes) : '' ?></td>
                            <td>
                                <?php if ($isWeekend) : ?>
                                    <span class="badge bg-secondary">Weekend</span>
                                <?php elseif ($isNationalHoliday) : ?>
                                    <span class="badge bg-warning text-dark">National Holiday</span>
                                <?php elseif (empty($checkIn) && empty($checkOut)) : ?>
                                    <span class="badge bg-danger">Absent</span>
                                <?php elseif (empty($checkIn) || empty($checkOut)) : ?>
                                    <span class="badge bg-danger">Incomplete Attendance</span>
                                <?php elseif ($isLate && $isEarlyLeave) : ?>
                                    <span class="badge bg-warning text-dark">Late & Early Leave</span>
                                <?php elseif ($isLate) : ?>
                                    <span class="badge bg-info text-dark">Late</span>
                                <?php elseif ($isEarlyLeave) : ?>
                                    <span class="badge bg-info text-dark">Early Leave</span>
                                <?php else : ?>
                                    <span class="badge bg-success">Present</span>
                                <?php endif; ?>
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
        new DataTable('#tableReport', {
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
</script>