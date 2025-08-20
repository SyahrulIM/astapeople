<?php
$start = $start ?? null;
$end = $end ?? null;
$dates = [];
$show_periode = $this->input->get('show_periode');

if ($start && $end) {
    $period = new DatePeriod(
        new DateTime($start),
        new DateInterval('P1D'),
        (new DateTime($end))->modify('+1 day')
    );

    foreach ($period as $date) {
        $dates[] = $date->format('Y-m-d');
    }
}
?>
<!-- Page content-->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1 class="mt-4">Tunjangan</h1>
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
                    <label for="togglePeriodeColumns" class="form-label">Tabel Columns</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="togglePeriodeColumns" name="show_periode" value="1" <?= $show_periode == '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="togglePeriodeColumns">Periode Dates</label>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div>
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="<?= base_url('report') ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Result -->
    <div class="row mt-4">
        <div class="col">
            <a href="<?php echo base_url('allowance/exportExcel?absensi_start=' . $start . '&absensi_end=' . $end); ?>">
                <button type="button" class="btn btn-success">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </button>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table id="tableMeal" class="table table-striped table-bordered nowrap" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Full Name</th>
                        <?php if ($show_periode == '1') : ?>
                            <?php foreach ($dates as $d) : ?>
                                <th><?= date('d M', strtotime($d)) ?></th>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <th>Total Attendance</th>
                        <th>Meal Allowance (Rp)</th>
                        <th>Total Allowance (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($results)) : $no = 1; ?>
                        <?php foreach ($results as $emp) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $emp['name'] ?></td>
                                <?php if ($show_periode == '1') : ?>
                                    <?php foreach ($dates as $d) : ?>
                                        <td class="text-center"><?= $emp['presence'][$d] ?? '-' ?></td>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <td class="text-center"><?= $emp['total_attend'] ?></td>
                                <td class="text-end">20,000</td>
                                <td class="text-end"><?= number_format($emp['total_attend'] * 20000, 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.dataTables.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url(); ?>js/scripts.js"></script>

<!-- Custom JS -->
<script>
    $(document).ready(function() {
        const table = new DataTable('#tableMeal', {
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

        // Handle checkbox toggle
        $('#togglePeriodeColumns').on('change', function() {
            const isVisible = $(this).is(':checked');
            $('.periode-column').toggle(isVisible);
        });

        // Set initial visibility based on server value
        const isPeriodeVisible = <?= $show_periode == '1' ? 'true' : 'false' ?>;
        if (!isPeriodeVisible) {
            $('.periode-column').hide();
        }
    });
</script>