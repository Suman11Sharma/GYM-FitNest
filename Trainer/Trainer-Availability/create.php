 <?php require("../sidelayout.php"); ?>
 <div id="layoutSidenav_content">
     <main class="container mt-4">
         <div class="card shadow-lg border-0 rounded-3">
             <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                 <h4 class="mb-0">Create Customer</h4>
                 <a href="index.php" class="btn btn-light btn-sm border ms-3">
                     <i class="fas fa-arrow-left"></i>
                 </a>
             </div>
             <div class="card-body p-4">
                 <form action="store.php" method="POST">

                     <!-- Availability ID (Auto / Serial No.) -->
                     <div class="mb-3">
                         <label for="availability_id" class="form-label">Availability ID </label>
                         <input type="text" class="form-control" id="availability_id" name="availability_id" readonly>
                     </div>

                     <!-- Trainer ID -->
                     <div class="mb-3">
                         <label for="trainer_id" class="form-label">Trainer ID</label>
                         <input type="text" class="form-control" id="trainer_id" name="trainer_id" required>
                     </div>

                     <!-- Day of Week -->
                     <div class="mb-3">
                         <label for="day_of_week" class="form-label">Day of the Week</label>
                         <select class="form-select" id="day_of_week" name="day_of_week" required>
                             <option value="" disabled selected>-- Select Day --</option>
                             <option value="monday">Monday</option>
                             <option value="tuesday">Tuesday</option>
                             <option value="wednesday">Wednesday</option>
                             <option value="thursday">Thursday</option>
                             <option value="friday">Friday</option>
                             <option value="saturday">Saturday</option>
                         </select>
                     </div>

                     <!-- Start Time -->
                     <div class="mb-3">
                         <label for="start_time" class="form-label">Start Time</label>
                         <input type="time" class="form-control" id="start_time" name="start_time" required>
                     </div>

                     <!-- End Time -->
                     <div class="mb-3">
                         <label for="end_time" class="form-label">End Time</label>
                         <input type="time" class="form-control" id="end_time" name="end_time" required>
                     </div>

                     <!-- Buttons -->
                     <div class="text-center">
                         <button type="submit" class="btn-our px-5 py-2">Submit</button>
                     </div>
                 </form>
             </div>
         </div>
     </main>

     <?php require("../assets/link.php"); ?>
 </div>