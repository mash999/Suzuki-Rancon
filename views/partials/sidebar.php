<?php 
require_once '../../functions/functions.php';
if(!isset($_SESSION['rancon_user_id']) || !isset($_SESSION['rancon_access_level'])){
    echo "<script>location.href='$base_url';</script>";
    die();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loading</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    
    <!-- GOOGLE FONTS AND FONT AWESOME-->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> 
   

    <!-- JQUERY AND JQUERY UI -->
    <link rel="stylesheet" href="<?php echo $base_url;?>/jquery-ui/jquery-ui.min.css">
    <script type="text/javascript" src="<?php echo $base_url;?>/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url;?>/jquery-ui/jquery-ui.min.js"></script>


    <!-- BOOTSTRAP CONNECTIONS -->
    <link rel="stylesheet" href="<?php echo $base_url;?>/css/bootstrap.min.css">
    <script src="<?php echo $base_url;?>/js/bootstrap.min.js"></script>


    <!-- CUSTOM SCRIPTS -->
    <link rel="stylesheet" href="<?php echo $base_url;?>/css/style.css">
    <script src="<?php echo $base_url;?>/js/script.js"></script>
    
</head>

<body>
    <a href="<?php echo $base_url;?>/logout.php" id="logout"><i class="fa fa-sign-out"></i> &nbsp; Logout</a>

    <!-- SIDE BAR NAVIGATION SECTION -->
    <nav>        
        <h1>Wims v1.0.0</h1>
        <ul>
            <li><a href="<?php echo $base_url . '/views/display/dashboard.php';?>" data-menu="dashboard" data-select="nav"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            
            <?php if($_SESSION['rancon_access_level'] > 1) { ?>
            <li><a href="<?php echo $base_url . '/views/display/users.php';?>" data-menu="users" data-select="nav"><i class="fa fa-user-o"></i> Users</a></li>
            <?php } ?>

            <li>
                <span class="drops-down"></span>
                <i class="fa fa-address-book"></i> Profiles
                <ul class="secondary-level">
                    <li><a href="<?php echo $base_url;?>/views/display/suppliers.php" data-menu="suppliers"><i class="fa fa-caret-right"></i> Suppliers Profile</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/customers.php" data-menu="customers"><i class="fa fa-caret-right"></i> Customers Profile</a></li>
                </ul>
            </li>

            <li>
                <span class="drops-down"></span>
                <i class="fa fa-pencil-square-o"></i> Enter
                <ul class="secondary-level">
                    <li><a href="<?php echo $base_url;?>/views/display/ckd.php" data-menu ="ckd"><i class="fa fa-caret-right"></i> CKD Parts Enter</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/ckdbom.php" data-menu="ckdbom"><i class="fa fa-caret-right"></i> CKD by BOM Parts Enter</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/cbu.php" data-menu="cbu"><i class="fa fa-caret-right"></i> CBU Parts Enter</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/manufacturing-parts.php" data-menu="manufacturing-parts"><i class="fa fa-caret-right"></i> Manufacturing Parts Enter</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/spare-parts.php" data-menu="spare-parts"><i class="fa fa-caret-right"></i> Backup Spare Parts Enter</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/additional-parts.php" data-menu="additional-parts"><i class="fa fa-caret-right"></i> Additional Parts Enter</a></li>
                </ul>
            </li>

            <li>
                <span class="drops-down"></span>
                <i class="fa fa-list-ul"></i> Issues
                <ul class="secondary-level">
                    <li><a href="<?php echo $base_url;?>/views/display/ckd-issue.php" data-menu="ckd-issue"><i class="fa fa-caret-right"></i> CKD Issue to Assembly</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/ckd-bom-issue.php" data-menu="ckd-bom-issue"><i class="fa fa-caret-right"></i> CKD By BOM Issue to Assembly</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/cripple-issue.php" data-menu="cripple-issue"><i class="fa fa-caret-right"></i> Cripple Issue to Assembly</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/cbu-issue.php" data-menu="cbu-issue"><i class="fa fa-caret-right"></i> CBU Issue to Assembly</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/manufacturing-issue.php" data-menu="manufacturing-issue"><i class="fa fa-caret-right"></i> Manufacturing Parts Issue to MF Unit</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/spare-issue.php" data-menu="spare-issue"><i class="fa fa-caret-right"></i> Backup Spare Parts Issue to Assembly</a></li>
                </ul>
            </li>

            <li>
                <span class="drops-down"></span>
                <i class="fa fa-shopping-basket"></i> Issues Received
                <ul class="secondary-level">
                    <li><a href="<?php echo $base_url;?>/views/display/ckd-issue-received.php" data-menu="ckd-issue-received"><i class="fa fa-caret-right"></i> CKD Issues Received</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/ckd-bom-issue-received.php" data-menu="ckd-bom-issue-received"><i class="fa fa-caret-right"></i> CKD By BOM Issues Received</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/cripple-issue-received.php" data-menu="cripple-issue-received"><i class="fa fa-caret-right"></i> Cripple Issues Received</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/cbu-issue-received.php" data-menu="cbu-issue-received"><i class="fa fa-caret-right"></i> CBU Issues Received</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/manufacturing-issue-received.php" data-menu="manufacturing-issue-received"><i class="fa fa-caret-right"></i> Manufacturing Parts Issues Received</a></li>
                </ul>
            </li>

            <li>
                <span class="drops-down"></span>
                <i class="fa fa-check-square-o"></i> Order Delivery
                <ul class="secondary-level">
                    <li><a href="<?php echo $base_url;?>/views/display/order-delivery.php" data-menu="order-delivery"><i class="fa fa-caret-right"></i> Delivery Challan</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/backup-order-delivery.php" data-menu="backup-order-delivery"><i class="fa fa-caret-right"></i> Backup Parts Delivery</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/additional-order-delivery.php" data-menu="additional-order-delivery"><i class="fa fa-caret-right"></i> Additional Parts Delivery</a></li>
                </ul>
            </li>

            <li><a href="<?php echo $base_url;?>/views/display/return-order.php" data-menu="return-order"><i class="fa fa-refresh"></i> Return In Order</a></li>

            <li><a href="<?php echo $base_url;?>/views/display/purchase-requisitions.php" data-menu="purchase-requisitions"><i class="fa fa-money"></i> Purchase Requisitions</a></li>


            <li>
                <span class="drops-down"></span>
                <i class="fa fa-chain-broken"></i> Claims
                <ul class="secondary-level">
                    <li><a href="<?php echo $base_url;?>/views/display/ckd-claim.php" data-menu="ckd-claim"><i class="fa fa-caret-right"></i> Claims for CKD</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/ckd-bom-claim.php" data-menu="ckd-bom-claim"><i class="fa fa-caret-right"></i> Claims for CKD BOM</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/cbu-claim.php" data-menu="cbu-claim"><i class="fa fa-caret-right"></i> Claims for CBU</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/manufacturing-claim.php" data-menu="manufacturing-claim"><i class="fa fa-caret-right"></i> Claims for Manufacturing Parts</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/spare-claim.php" data-menu="spare-claim"><i class="fa fa-caret-right"></i> Claims for Spare Parts</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/additional-claim.php" data-menu="additional-claim"><i class="fa fa-caret-right"></i> Claims for Additional Parts</a></li>
                    <li><a href="<?php echo $base_url;?>/views/display/claims-claim.php" data-menu="claims-claim"><i class="fa fa-caret-right"></i> Claims for claim Parts</a></li>
                </ul>
            </li>

            <li><a href="<?php echo $base_url;?>/views/reports/reports.php" data-menu="reports"><i class="fa fa-bar-chart"></i> Reports</a></li>
            <li><a href="<?php echo $base_url;?>/views/display/settings.php" data-menu="settings"><i class="fa fa-cog"></i> Settings</a></li>

        </ul>

        <p>&copy; Rancon Motors<br> Developed by <a href="http://www.whitepaper.tech" target="_blank">White Paper</a></p>
    </nav>




<?php
if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
    echo "
    <div id='status-msg-modal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-body'>$_SESSION[msg]</div>
            </div>
        </div>
    </div>
    ";
    $_SESSION['msg'] = "";
}
?>