<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Create Visitor Pass</h4>
            </div>
            <div class="card-body p-4">
                <form action="store.php" method="POST">

                    <!-- Pass ID -->
                    <div class="mb-3">
                        <label for="pass_id" class="form-label">Pass ID </label>
                        <input type="text" class="form-control" id="pass_id" name="pass_id" readonly>
                    </div>

                    <!-- Gym ID -->
                    <div class="mb-3">
                        <label for="gym_id" class="form-label">Gym ID</label>
                        <input type="text" class="form-control" id="gym_id" name="gym_id" required>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Visitor Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <!-- Contact -->
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="tel" class="form-control" id="contact" name="contact" pattern="^[0-9]{7,15}$" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>

                    <!-- Time From -->
                    <div class="mb-3">
                        <label for="time_from" class="form-label">Time From</label>
                        <input type="datetime-local" class="form-control" id="time_from" name="time_from" required>
                    </div>

                    <!-- Time To -->
                    <div class="mb-3">
                        <label for="time_to" class="form-label">Time To</label>
                        <input type="datetime-local" class="form-control" id="time_to" name="time_to" required>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="" disabled selected>-- Select Method --</option>
                            <option value="pay_now">Pay Now</option>
                            <option value="pay_at_visit">Pay at Visit</option>
                        </select>
                    </div>

                    <!-- Payment Status -->
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="" disabled selected>-- Select Status --</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>

                    <!-- Transaction ID -->
                    <div class="mb-3">
                        <label for="transaction_id" class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" id="transaction_id" name="transaction_id">
                    </div>


                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn-our px-5 py-2">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </main>
    <?php require("../assets/link.php"); ?>
</div>