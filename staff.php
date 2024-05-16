<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Management - Stock System</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <?php include 'sidebar.php'; ?>
  <div class="container mt-5">
    <a href="dashboard.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2>Staff Management</h2>
    <div class="row">
      <div class="col-md-6">
        <div class="card mb-4">
          <div class="card-header">
            Existing Staff
          </div>
          <div class="card-body">
            <ul class="list-group">
              <?php
              $conn = new mysqli('localhost', 'root', '', 'stocksystem');
              if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
              }
              $sql = "SELECT * FROM user";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $verificationStatus = $row['status'] == 1 ? 'Verified' : 'Not Verified';
                  echo "<li class='list-group-item'>" . $row['name'] . " - " . $row['email'] . " - Role: " . ($row['role'] == 0 ? 'Admin' : 'Staff') . " - Verification Status: " . $verificationStatus . "</li>";
                }
              } else {
                echo "<li class='list-group-item'>No staff members found.</li>";
              }
              $conn->close();
              ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card mb-4">
          <div class="card-header">
            Staff Actions
          </div>
          <div class="card-body">
            <ul class="list-group">
              <?php
              $conn = new mysqli('localhost', 'root', '', 'stocksystem');
              if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
              }
              $sql = "SELECT * FROM user";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<li class='list-group-item'>";
                  echo "<span>" . $row['name'] . " - " . $row['email'] . "</span>";
                  echo "<div class='btn-group float-right' role='group'>";
                  echo "<a href='edit_staff.php?email=" . $row['email'] . "' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i> Edit</a>";
                  echo "<a href='delete_staff.php?email=" . $row['email'] . "' class='btn btn-sm btn-danger'><i class='fas fa-trash'></i> Delete</a>";
                  $verificationIcon = $row['status'] == 1 ? 'fa-check-circle' : 'fa-times-circle';
                  $verificationColor = $row['status'] == 1 ? 'text-success' : 'text-danger';
                  echo "<button class='btn btn-sm btn-info verify-button' data-email='" . $row['email'] . "' data-status='" . $row['status'] . "'><i class='fas $verificationIcon $verificationColor'></i></button>";
                  echo "</div>";
                  echo "</li>";
                }
              } else {
                echo "<li class='list-group-item'>No staff members found.</li>";
              }
              $conn->close();
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            Add New Staff
          </div>
          <div class="card-body">
            <form id="addStaffForm" action="add_staff.php" method="POST">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role">
                  <option value="1">Staff</option>
                  <option value="0">Admin</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add Staff</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="successModalLabel">Success</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p id="successMessage"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#addStaffForm').submit(function(event) {
          event.preventDefault();
          $.ajax({
            type: 'POST',
            url: 'add_staff.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
              $('#successMessage').text(response.message);
              $('#successModal').modal('show');
              setTimeout(function() {
                location.reload();
              }, 2000);
            },
            error: function(xhr, status, error) {
              alert('Failed to add staff member.');
            }
          });
        });
      });
      $(document).ready(function() {
        $('.verify-button').click(function() {
          var email = $(this).data('email');
          var status = $(this).data('status');
          status = status === 1 ? 0 : 1;
          $.ajax({
            type: 'POST',
            url: 'status.php',
            data: { email: email, status: status },
            dataType: 'json',
            success: function(response) {
              $('#successMessage').text(response.message);
              $('#successModal').modal('show');
              setTimeout(function() {
                location.reload();
              }, 2000);
            },
            error: function(xhr, status, error) {
              alert('Failed to update verification status.');
            }
          });
        });
      });
    </script>
  </body>
  </html>
