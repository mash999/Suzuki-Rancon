<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>

<style>
.panel-yellow {
  border-color: #f0ad4e;
}
.panel-yellow > .panel-heading {
  border-color: #f0ad4e;
  color: white;
  background-color: #f0ad4e;
}
a.yellow {
  color: #f0ad4e;
}
a.yellow:hover {
  color: #df8a13;
}	
.panel-green {
  border-color: #5cb85c;
}
.panel-green > .panel-heading {
  border-color: #5cb85c;
  color: white;
  background-color: #5cb85c;
}
a.green {
  color: #5cb85c;
}
a.green:hover {
  color: #3d8b3d;
}
</style>









<div class="topbar">
    <div class="breadcrumbs">
        <i class="fa fa-bars visible-xs menu-bar" aria-hidden="true"></i>
        <a href="#"><i class="fa fa-dashboard"></i> &nbsp; Dashboard</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title"><span class="title">Dashboard</span></h1>
	<div class="row">
		<a href="#">
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-comments fa-5x"></i>
							</div>
		                    <div class="col-xs-9 text-right">
								<div>Assembling Status </div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<span class="pull-left">View Details</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
		                <div class="clearfix"></div>
		            </div>
		        </div>
		    </div>
	  	</a>
	    

	    <a href="<?php echo $base_url;?>/views/reports/reports.php?ready-bike" class="green" target="blank">
		    <div class="col-lg-3 col-md-6">
		    	<div class="panel panel-green">
		        	<div class="panel-heading">
		            	<div class="row">
		                	<div class="col-xs-3">
		                    	<i class="fa fa-tasks fa-5x"></i>
		                	</div>
		                	<div class="col-xs-9 text-right">
		                		<!-- <div class="huge">0</div> -->
		                		<div>Ready Motorcycle</div>
		                	</div>
		            	</div>
		        	</div>
			        <div class="panel-footer">
			        	<span class="pull-left">View Details</span>
			        	<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
			            <div class="clearfix"></div>
					</div>
				</div>
			</div>
		</a>


		<a href="<?php echo $base_url;?>/views/reports/reports.php?current-stock" class="yellow" target="blank">
		    <div class="col-lg-3 col-md-6">
		        <div class="panel panel-yellow">
		        	<div class="panel-heading">
		        		<div class="row">
		                	<div class="col-xs-3">
		                		<i class="fa fa-shopping-cart fa-5x"></i>
		                	</div>
		                	<div class="col-xs-9 text-right">
		                		<div>Current Stock</div>
		                	</div>
		                </div>
		            </div>
		            <div class="panel-footer">
		            	<span class="pull-left">View Details</span>
		            	<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
		            	<div class="clearfix"></div>
		            </div>
		        </div>
		    </div>
		</a>

		
		<a href="#" class="green">
		    <div class="col-lg-3 col-md-6">
		        <div class="panel panel-green">
		        	<div class="panel-heading">
		        		<div class="row">
		        			<div class="col-xs-3">
		        				<i class="fa fa-shopping-cart fa-5x"></i>
		        			</div>
		        			<div class="col-xs-9 text-right">
		        				<div>Delivery Status</div>
		        			</div>
		        		</div>
		        	</div>
		        	<div class="panel-footer">
		        		<span class="pull-left">View Details</span>
		        		<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
		        		<div class="clearfix"></div>
		        	</div>
		        </div>
		    </div>
		 </div>
		</a>
	</div>
</div> <!-- /content -->

</body>
</html>