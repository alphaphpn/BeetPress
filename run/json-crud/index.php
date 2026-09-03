<?php
$jsonFile = 'data.json';

// Initialize JSON file if it doesn't exist
if (!file_exists($jsonFile)) {
	file_put_contents($jsonFile, json_encode([], JSON_PRETTY_PRINT));
}

// Helper functions to read/write JSON
function getData($file) {
	return json_decode(file_get_contents($file), true) ?: [];
}

function saveData($file, $data) {
	file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT));
}

// --- API ENDPOINTS (HANDLES AJAX REQUESTS) ---
if (isset($_GET['action'])) {
	header('Content-Type: application/json');
	$action = $_GET['action'];
	$data = getData($jsonFile);

	// FETCH ALL
	if ($action === 'fetch') {
		echo json_encode(['data' => $data]);
		exit;
	}

	// FETCH SINGLE
	if ($action === 'get_single' && isset($_GET['id'])) {
		$id = $_GET['id'];
		foreach ($data as $item) {
			if ($item['id'] == $id) {
				echo json_encode(['status' => 'success', 'data' => $item]);
				exit;
			}
		}
		echo json_encode(['status' => 'error', 'message' => 'Record not found']);
		exit;
	}

	// CREATE OR UPDATE
	if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
		$id = $_POST['id'] ?? null;
		$name = trim($_POST['name'] ?? '');
		$email = trim($_POST['email'] ?? '');
		$role = trim($_POST['role'] ?? '');

		if (empty($name) || empty($email) || empty($role)) {
			echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
			exit;
		}

		if ($id) { // Update
			foreach ($data as &$item) {
				if ($item['id'] == $id) {
					$item['name'] = $name;
					$item['email'] = $email;
					$item['role'] = $role;
					break;
				}
			}
		} else { // Create
			$newItem = [
				'id' => time() . rand(100, 999), // Unique ID generation
				'name' => $name,
				'email' => $email,
				'role' => $role
			];
			$data[] = $newItem;
		}

		saveData($jsonFile, $data);
		echo json_encode(['status' => 'success', 'message' => $id ? 'Record updated successfully!' : 'Record added successfully!']);
		exit;
	}

	// DELETE
	if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
		$id = $_POST['id'] ?? null;
		$newData = array_filter($data, function ($item) use ($id) {
			return $item['id'] != $id;
		});

		saveData($jsonFile, $newData);
		echo json_encode(['status' => 'success', 'message' => 'Record deleted successfully!']);
		exit;
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PHP & JSON DataTables CRUD</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
	<div class="card shadow">
		<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
			<h4 class="mb-0">User Management System</h4>
			<button class="btn btn-light btn-sm" id="btnOpenAddModal">
				<i class="fas fa-plus me-1"></i> Add New User
			</button>
		</div>
		<div class="card-body">
			<table id="userTable" class="table table-striped table-bordered w-100">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Role</th>
						<th>Actions</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="userForm">
				<div class="modal-header">
					<h5 class="modal-title" id="modalTitle">Add User</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="userId" name="id">
					<div class="mb-3">
						<label for="userName" class="form-label">Full Name</label>
						<input type="text" class="form-control" id="userName" name="name" required>
					</div>
					<div class="mb-3">
						<label for="userEmail" class="form-label">Email Address</label>
						<input type="email" class="form-control" id="userEmail" name="email" required>
					</div>
					<div class="mb-3">
						<label for="userRole" class="form-label">Role</label>
						<select class="form-select" id="userRole" name="role" required>
							<option value="">Select Role</option>
							<option value="Admin">Admin</option>
							<option value="Editor">Editor</option>
							<option value="User">User</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary" id="btnSave">Save Changes</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
	const userModal = new bootstrap.Modal(document.getElementById('userModal'));

	// Initialize DataTable
	const table = $('#userTable').DataTable({
		ajax: 'index.php?action=fetch',
		columns: [
			{ data: 'id' },
			{ data: 'name' },
			{ data: 'email' },
			{ data: 'role' },
			{
				data: null,
				render: function(data, type, row) {
					return `
						<button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}"><i class="fas fa-edit"></i> Edit</button>
						<button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}"><i class="fas fa-trash"></i> Delete</button>
					`;
				},
				orderable: false
			}
		]
	});

	// Open Add Modal
	$('#btnOpenAddModal').click(function() {
		$('#userForm')[0].reset();
		$('#userId').val('');
		$('#modalTitle').text('Add New User');
		userModal.show();
	});

	// Save/Update Form Submit
	$('#userForm').submit(function(e) {
		e.preventDefault();
		$.ajax({
			url: 'index.php?action=save',
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.status === 'success') {
					userModal.hide();
					table.ajax.reload();
					Swal.fire('Success', response.message, 'success');
				} else {
					Swal.fire('Error', response.message, 'error');
				}
			}
		});
	});

	// Open Edit Modal
	$('#userTable').on('click', '.edit-btn', function() {
		const id = $(this).data('id');
		$.getJSON(`index.php?action=get_single&id=${id}`, function(response) {
			if (response.status === 'success') {
				$('#userId').val(response.data.id);
				$('#userName').val(response.data.name);
				$('#userEmail').val(response.data.email);
				$('#userRole').val(response.data.role);
				$('#modalTitle').text('Edit User');
				userModal.show();
			} else {
				Swal.fire('Error', response.message, 'error');
			}
		});
	});

	// Delete Record
	$('#userTable').on('click', '.delete-btn', function() {
		const id = $(this).data('id');
		Swal.fire({
			title: 'Are you sure?',
			text: "This action cannot be undone!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.post('index.php?action=delete', { id: id }, function(response) {
					if (response.status === 'success') {
						table.ajax.reload();
						Swal.fire('Deleted!', response.message, 'success');
					} else {
						Swal.fire('Error', response.message, 'error');
					}
				}, 'json');
			}
		});
	});
});
</script>
</body>
</html>