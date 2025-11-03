<?php
include "../../database/user_authentication.php";
include "../../database/db_connect.php";

$gym_id = $_SESSION['gym_id'] ?? null;
if (!$gym_id) {
    die("⚠️ Gym ID not found in session. Please log in again.");
}

// ✅ Fetch payouts (trainer name if trainer_booking, else customer name)
$sql = "
SELECT 
    gp.payout_id,
    gp.payment_type,
    gp.amount,
    gp.payout_status,
    gp.paid_at,
    CASE 
        WHEN gp.payment_type = 'trainer_booking' THEN t.name
        WHEN gp.payment_type = 'visitor_pass' THEN vp.name
        ELSE c.full_name
    END AS person_name
FROM gym_payouts gp
LEFT JOIN trainer_bookings tb 
    ON gp.payment_type = 'trainer_booking' 
    AND gp.reference_id = tb.booking_id
LEFT JOIN trainers t 
    ON tb.trainer_id = t.trainer_id
LEFT JOIN visitor_passes vp 
    ON gp.payment_type = 'visitor_pass' 
    AND gp.reference_id = vp.pass_id
LEFT JOIN customer_subscriptions cs 
    ON gp.payment_type = 'customer_subscription' 
    AND gp.reference_id = cs.subscription_id
LEFT JOIN customers c 
    ON cs.user_id = c.customer_id
WHERE gp.gym_id = '$gym_id'
ORDER BY gp.created_at DESC
";

$payouts = $conn->query($sql);
?>

<?php require("../sidelayout.php"); ?>

<div id="layoutSidenav_content">
    <main class="container-fluid mt-4">

        <!-- ✅ Title + Search (Clean & Aligned) -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h4 class="fw-bold mb-0">Gym Payout Records</h4>

            <div class="d-flex align-items-center gap-2" style="flex-shrink: 0;">
                <input type="text" id="customSearchBox" name="search"
                    class="form-control form-control-sm"
                    placeholder="Search Payment Type, Name, Amount, Status, or Date..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    style="min-width: 280px;" />
                <button type="button" class="btn btn-sm btn-our" id="searchBtn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table id="payoutTable" class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Payment Type</th>
                            <th>Name</th>
                            <th>Amount (Rs)</th>
                            <th>Payout Status</th>
                            <th>Paid At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($payouts && $payouts->num_rows > 0) {
                            $sn = 1;
                            while ($row = $payouts->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $sn++; ?></td>
                                    <td><?= ucfirst(str_replace('_', ' ', $row['payment_type'])) ?></td>
                                    <td><?= htmlspecialchars($row['person_name'] ?: 'N/A') ?></td>
                                    <td><?= number_format($row['amount'], 2) ?></td>
                                    <td>
                                        <?php if ($row['payout_status'] == 'paid') { ?>
                                            <span class="badge bg-success">Paid</span>
                                        <?php } else { ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php } ?>
                                    </td>
                                    <td><?= $row['paid_at'] ? date("Y-m-d h:i A", strtotime($row['paid_at'])) : '-' ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    No payout records found for your gym.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
</div>

<?php require("../assets/link.php"); ?>

</div>

<!-- ✅ DataTables + jQuery -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#payoutTable').DataTable({
            pageLength: 15,
            order: [
                [0, 'asc']
            ],
            dom: 't<"d-flex justify-content-between align-items-center mt-3"ip>',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
            columnDefs: [{
                targets: 0,
                orderable: false
            }]
        });

        // ✅ Custom Search Integration
        $('#customSearchBox').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>