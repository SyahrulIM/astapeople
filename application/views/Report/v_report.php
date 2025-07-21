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
                    <p><?= $summary['total_days'] ?? 0 ?></p>
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

    <!-- Start Permit Modal -->
    <div class="modal fade" id="permitModal" tabindex="-1" aria-labelledby="permitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permitModalLabel">Submit Permit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="permitForm" method="post" action="<?= base_url('report/permit') ?>">
                    <div class="modal-body">
                        <input type="hidden" name="employee_id" id="modalEmployeeId">
                        <input type="hidden" name="date" id="modalDate">

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <select class="form-select" id="reason" name="reason" required>
                                <option value="">-- Select Reason --</option>
                                <option value="Sick">Sick</option>
                                <option value="Personal">Personal</option>
                                <option value="Cuti">Cuti</option>
                            </select>
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
                        <th>Permission</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <td>Action</td>
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
                            <td>
                                <?php if ($row->is_permission == 1) : ?>
                                    <span class="badge bg-success">Yes</span>
                                <?php else : ?>
                                    <span class="badge bg-danger">No</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($reason) ? $reason : '-' ?></td>
                            <td>
                                <?php if ($isWeekend) : ?>
                                    <span class="badge bg-secondary">Weekend</span>
                                <?php elseif ($isNationalHoliday) : ?>
                                    <span class="badge bg-warning">National Holiday</span>
                                <?php elseif (empty($checkIn) && empty($checkOut)) : ?>
                                    <span class="badge bg-danger">Absent</span>
                                <?php elseif (empty($checkIn) || empty($checkOut)) : ?>
                                    <span class="badge bg-danger">Incomplete</span>
                                <?php elseif ($isLate && $isEarlyLeave) : ?>
                                    <span class="badge bg-warning">Late & Early Leave</span>
                                <?php elseif ($isLate) : ?>
                                    <span class="badge bg-info">Late</span>
                                <?php elseif ($isEarlyLeave) : ?>
                                    <span class="badge bg-info">Early Leave</span>
                                <?php else : ?>
                                    <span class="badge bg-success">Present</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (
                                    ($isWeekend === false && $isNationalHoliday === false) &&
                                    (
                                        (empty($checkIn) && empty($checkOut)) || // Absent
                                        (empty($checkIn) || empty($checkOut)) || // Incomplete
                                        $isLate || $isEarlyLeave // Late, Early Leave, Late & Early Leave
                                    )
                                ) : ?>
                                    <button type="button" class="btn btn-success btn-permit" data-employee-id="<?= $row->idppl_employee ?>" data-date="<?= $date ?>">
                                        Permit
                                    </button>
                                <?php else : ?>
                                    -
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

            // Set values in modal
            $('#modalEmployeeId').val(employeeId);
            $('#modalDate').val(date);
            $('#permitModal .modal-title').text('Submit Permit for ' + employeeName + ' - ' + date);

            // Show modal
            var modal = new bootstrap.Modal(document.getElementById('permitModal'));
            modal.show();
        });

        // Handle form submission
        $('#permitForm').on('submit', function(e) {
            e.preventDefault();

            // Show loading state
            var submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        $('#permitModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while submitting the permit.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Submit');
                }
            });
        });
    });
</script>