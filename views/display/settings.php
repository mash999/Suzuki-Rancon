<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
$user = fetch_functions\get_row('users','USER_ID',$_SESSION['rancon_user_id'])[0];
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-cog"></i>Settings</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div id="settings" class="content">
	<h1 class="page-title">
		<span class="title">Settings</span>
	</h1>


	
	
	<div class="table-responsive col-lg-7 col-md-7 col-sm-7 col-xs-12 no-padding-left">
		<h2>User Account</h2>
		<table class="table table-bordered table-striped searchable-table">
			<tbody>
				<tr>
					<td>Full Name</td>
					<td><?php echo $user->USER_FULL_NAME;?></td>
				</tr>
				<tr>
					<td>Username</td>
					<td><?php echo $user->USER_NAME;?></td>
				</tr>
				<tr>
					<td>Access Level</td>
					<td>
						<?php 
						if($user->USER_ACCESS_LEVEL == 1) $access_level = "Regular User";
						if($user->USER_ACCESS_LEVEL == 2) $access_level = "Admin";
						if($user->USER_ACCESS_LEVEL == 3) $access_level = "Super Admin";
						echo $access_level
						;?>
					</td>
				</tr>
				<tr>
					<td>Created By</td>
					<td><?php echo fetch_functions\get_row('users','USER_ID',$user->CREATED_BY)[0]->USER_FULL_NAME;?></td>
				</tr>
				<tr>
					<td>Created On</td>
					<td><?php echo date("d M, Y", strtotime($user->ENTERED_AT));?></td>
				</tr>
				<tr>
					<td>Account Status</td>
					<td>
						<?php 
						if($user->ACCOUNT_STATUS == 0) $account_status = "Deactivated";
						if($user->ACCOUNT_STATUS == 1) $account_status = "Active";
						echo $account_status;
						?>
					</td>
				</tr>
			</tbody>
		</table>
	</div> <!-- /table-responsive -->	




	<div class="change-password col-lg-5 col-md-5 col-sm-7 col-xs-12 no-padding-right">
		<h2>Change Password</h2>
		<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post">
			<input type="password" class="form-control" name="current-password" placeholder="Current Password" required><br>
			<input type="password" class="form-control" name="new-password" placeholder="New Password" required><br>
			<input type="password" class="form-control" name="confirm-password" placeholder="Confirm Password" required><br>
			<button type="submit" name="change-password" class="btn btn-primary btn-sm">Save</button>
		</form>
	</div> <!-- /change-password -->
</div> <!-- /content -->

</body>
</html>