<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
if($_SESSION['rancon_access_level'] != 2 && $_SESSION['rancon_access_level'] != 3){
	echo "<script>location.href='$base_url/views/display/dashboard.php';</script>";
	die();
}
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-user-o"></i>Users</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div id="users" class="content">
	<h1 class="page-title">
		<span class="title">Users</span>
		<button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#create-modal"><i class="fa fa-pencil fa-rotate-270"></i>&nbsp; Create User</button>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search User">
	</h1>


	
	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table">
			<thead>
				<tr>
					<th>Full Name</th>
					<th>Username</th>
					<th>Access Level</th>
					<th>Created By</th>
					<th>Created On</th>
					<th>Account Status</th>
					<th class="fixed-width">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$users = fetch_functions\get_rows('users');
				foreach ($users as $user) {
					if($user->USER_ACCESS_LEVEL == 1)	$access_level = "Regular User";
					if($user->USER_ACCESS_LEVEL == 2)	$access_level = "Admin";
					if($user->USER_ACCESS_LEVEL == 3)	$access_level = "Super Admin";
					$created_by = fetch_functions\get_row('users','USER_ID',$user->CREATED_BY)[0]->USER_NAME;
					$created_on = date("d M, Y",strtotime($user->ENTERED_AT));
					if($user->ACCOUNT_STATUS == 0){
						$deactivated_by = fetch_functions\get_row('users','USER_ID',$user->DEACTIVATED_BY)[0]->USER_FULL_NAME;
						$deactivate_date = date("d M, Y",$user->UPDATED_AT) . " at " . date("h:i A",$user->UPDATED_AT);
						$account_status = "<a class='deactivate-notice-trigger' style='color:#c9302c;' href='#' data-html='true' data-toggle='popover' data-trigger='focus' title='Deactivation Detail' data-content='<p>Deactivated By $deactivated_by on $deactivate_date.</p><p><strong>Reason:</strong><br>$user->DEACTIVATION_REASON<p>' data-placement='top'>Deactivated <i class='fa fa-warning'></i></a>";
					}
					if($user->ACCOUNT_STATUS == 1) $account_status = "<span style='color:green;'>Active &nbsp;<i class='fa fa-check-circle-o'></i><span>";
					if($user->USER_ACCESS_LEVEL < $_SESSION['rancon_access_level']){
						if($user->ACCOUNT_STATUS == 0){
							$button = "<form action='$base_url/functions/process-forms.php' method='post'><input type='hidden' name='user-id' value='$user->USER_ID'><button class='btn btn-primary btn-sm' name='reactivate-user' type='submit' onclick=\"return confirm('Are you sure that you want to activate this user\'s access?')\">Activate</button></form>";
						}
						if($user->ACCOUNT_STATUS == 1){
							$button = "<button class='btn btn-primary btn-sm deactivate-modal-trigger' data-user='$user->USER_NAME' data-toggle='modal' data-target='#deactivate-modal'>Deactivate</button>";
						}
						$button .= "<button class='btn btn-primary btn-sm user-edit-modal-trigger' data-name = '$user->USER_FULL_NAME' data-access = '$user->USER_ACCESS_LEVEL' data-user='$user->USER_NAME' data-toggle='modal' data-target='#user-edit-modal'>Edit</button>";
						$button .= "<form action='$base_url/functions/process-forms.php' method='post'><input type='hidden' name='user-id' value='$user->USER_ID'><button class='btn btn-danger btn-sm' name='delete-user' type='submit' onclick=\" return confirm('Are you sure that you want to delete this user? Please be advised that you can not undo if you continue')\">Delete</button></form>";
					}
					else { $button = ""; }
					echo "
					<tr>
						<td>$user->USER_FULL_NAME</td>
						<td>$user->USER_NAME</td>
						<td>$access_level</td>
						<td>$created_by</td>
						<td>$created_on</td>
						<td>$account_status</td>
						<td class='fixed-width'>$button</td>
					";
				}
				?>
			</tbody>
		</table>
	</div> <!-- /table-responsive -->	
</div> <!-- /content -->




<!-- Modal -->
<div class="modal fade" id="create-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Create User's Account</h4>
			</div>
			<div class="modal-body">
				<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post">
					<input type="text" class="form-control" name="full-name" placeholder="User's Full Name" required><br>
					<input type="text" class="form-control" name="username" placeholder="User name" required><br>
					<?php if($_SESSION['rancon_access_level'] == 3){ ?>
					<select class="form-control" name="user-access-level" required>
						<option value="">Choose User Type</option>
						<option value="1">Regular User</option>
						<option value="2">Admin</option>
					</select><br>
					<?php } else { ?>
					<input type="hidden" name="user-access-level" value="1" readonly>
					<?php } ?>
					<button type="submit" name="create-user" class="btn btn-primary btn-sm">Create User</button>
				</form>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="deactivate-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Deactivate User's Account</h4>
			</div>
			<div class="modal-body">
				<p>Deactivating user - <strong><em></em></strong></p>
				<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post">
					<input type="hidden" class="form-control" name="username" id="selected-user-name" required>
					<input type="text" class="form-control" name="reason" placeholder="Reason for Disabling User's Account" required><br>
					<button type="submit" name="deactivate-user" class="btn btn-primary btn-sm">Deactivate User</button><br>
				</form>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="user-edit-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Edit User Information</h4>
			</div>
			<div class="modal-body">
				<p>Update information of <strong><em></em></strong></p>
				<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post">
					<input type="hidden" class="form-control" name="username" id="update-user-name" required>
					<input type="text" class="form-control" id="update-user-full-name" name="full-name" placeholder="User Full Name" required><br>
					<?php if($_SESSION['rancon_access_level'] == 3) { ?>
					<select name="user-access" id="update-user-access" class="form-control">
						<option value="">Choose Access Level</option>
						<option value="1">Regular User</option>
						<option value="2">Admin</option>
					</select>
					<br>
					<?php } ?>
					<button type="submit" name="update-user" class="btn btn-primary btn-sm">Update</button><br>
				</form>
			</div>
		</div>
	</div>
</div>

</body>
</html>