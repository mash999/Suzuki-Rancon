<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <i class="fa fa-bars visible-xs menu-bar" aria-hidden="true"></i>
        <span><i class="fa fa-bar-chart"></i>Reports</span>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
    <h1 class="page-title"><span class="title">Reports</span></h1>
    <div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
        <table class="table table-bordered table-striped report-table">
            <thead>
                <tr>
                    <th>Types of Reports</th>
                    <th class="fixed-width">Action</th>
                </tr>
            </thead>


            <tbody>
                <tr>
                    <td>Customers Report</td>
                    <td class="fixed-width" data-entries="customers">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                
                </tr>


                <tr>
                    <td>Suppliers Report</td>
                    <td class="fixed-width" data-entries="suppliers">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                
                </tr>


                <tr>
                    <td>Stock Status</td>
                    <td class="fixed-width" data-stock="stock" data-entries="stock" data-parts="stock_parts">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td> 
                </tr>


                <tr>
                    <td>In Progress Status</td>
                    <td class="fixed-width" data-pending="pending" data-entries="pending" data-parts="pending_parts">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td> 
                </tr>
                

                <tr>
                    <td>CKD Enter Report</td>
                    <td class="fixed-width" data-entries="entries" data-parts="parts" data-type="ckd">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                 
                </tr>
                

                <tr>
                    <td>CKD Enter By BOM Report</td>
                    <td class="fixed-width" data-entries="entries" data-parts="parts" data-type="ckdbom">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                 
                </tr>
                

                <tr>
                    <td>CBU Enter Report</td>
                    <td class="fixed-width" data-entries="entries" data-parts="parts" data-type="cbu">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                 
                </tr>
                

                <tr>
                    <td>Manufacturing Parts Enter Report</td>
                    <td class="fixed-width" data-entries="entries" data-parts="parts" data-type="manufacturing-parts">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                 
                </tr>
                

                <tr>
                    <td>Backup Spare Parts Enter Report</td>
                    <td class="fixed-width" data-entries="entries" data-parts="parts" data-type="spare-parts">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                 
                </tr>
                

                <tr>
                    <td>Additional Parts Enter Report</td>
                    <td class="fixed-width" data-entries="entries" data-parts="parts" data-type="additional-parts">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                 
                </tr>
                

                <tr>
                    <td>CKD Issue to Assembly Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="ckd-issue" data-status="issued">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>
                </tr>
                

                <tr>
                    <td>CKD BOM Issue to Assembly Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="ckd-bom-issue" data-status="issued">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>
                </tr>
                

                <tr>
                    <td>Cripple issue to Assembly Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="cripple-issue" data-status="issued">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                 
                </tr>
                

                <tr>
                    <td>CBU Issue to Assembly Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="cripple-issue" data-status="issued">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>
                </tr>
                

                <tr>
                    <td>Manufacturing Parts Issue to MF Unit Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="manufacturing-issue" data-status="issued">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>
                </tr>
                

                <tr>
                    <td>Backup Spare Parts Issue Assembly Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="spare-issue" data-status="issued">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>
                </tr>
                

                <tr>
                    <td>CKD Receive from Assembly Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="ckd-issue" data-status="received">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>
                </tr>
                

                <tr>
                    <td>CKD By BOM Receive from Assembly Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="ckd-bom-issue" data-status="received">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>
                </tr>
                

                <tr>
                    <td>Cripple Receive from Assembly Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="cripple-issue" data-status="received">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>            
                </tr>
                

                <tr>
                    <td>CBU Receive from Assembly Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="cbu-issue" data-status="received">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>          
                </tr>
                

                <tr>
                    <td>Manufacturing Parts Receive from MF Unit Report</td>
                    <td class="fixed-width" data-entries="issues" data-parts="issue_records" data-type="manufacturing-issue" data-status="received">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>           
                </tr>
                

                <tr>
                    <td>Delivery Challan</td>
                    <td class="fixed-width" data-entries="delivery" data-parts="delivery_parts" data-type="normal">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>          
                </tr>
                

                <tr>
                    <td>Backup Spare Parts Delivert Report</td>
                    <td class="fixed-width" data-entries="backup_delivery" data-parts="delivery_parts" data-type="backup">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>          
                </tr>
                

                <tr>
                    <td>Additional Parts Delivert Report</td>
                    <td class="fixed-width" data-entries="additional_delivery" data-parts="delivery_parts" data-type="additional">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>          
                </tr>
                

                <tr>
                    <td>Return in Order Report</td>
                    <td class="fixed-width" data-entries="return_order" data-parts="returned_parts">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>          
                </tr>
                

                <tr>
                    <td>Purchase Requisitions Report</td>
                    <td class="fixed-width" data-entries="purchase_requisitions" data-parts="purchase_requisitions_parts">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                 
                </tr>
                

                <tr>
                    <td>Claim Declaration for CKD Report</td>
                    <td class="fixed-width" data-entries="claims" data-parts="claims_parts" data-type="ckd-claim">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>                 
                </tr>
                

                <tr>
                    <td>Claim Declaration for CKD BOM Report</td>
                    <td class="fixed-width" data-entries="claims" data-parts="claims_parts" data-type="ckd-bom-claim">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>           
                </tr>
                

                <tr>
                    <td>Claim Declaration for CBU Report</td>
                    <td class="fixed-width" data-entries="claims" data-parts="claims_parts" data-type="cbu-claim">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>              
                </tr>
                

                <tr>
                    <td>Claim Declaration for Manufacturing Parts Report</td>
                    <td class="fixed-width" data-entries="claims" data-parts="claims_parts" data-type="manufacturing-claim">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>           
                </tr>
                

                <tr>
                    <td>Claim Declaration for Backup Parts Report</td>
                    <td class="fixed-width" data-entries="claims" data-parts="claims_parts" data-type="spare-claim">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>           
                </tr>
                

                <tr>
                    <td>Claim Declaration for Additional Parts Report</td>
                    <td class="fixed-width" data-entries="claims" data-parts="claims_parts" data-type="additional-claim">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>           
                </tr>
                

                <tr>
                    <td>Claim Declaration for Claim Parts Report</td>
                    <td class="fixed-width" data-entries="claims" data-parts="claims_parts" data-type="claims-claim">
                        <button type="button" class="btn btn-primary btn-sm get-excel" name="excel"><i class="fa fa-file-excel-o"></i> &nbsp;Get Excel</button>
                        <button type="button" class="btn btn-primary btn-sm get-pdf" name="pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</button>
                    </td>           
                </tr>
            </tbody>
        </table>
    </div> <!-- /table-responsive -->
</div> <!-- /content -->









<div id="export-modal">
    <form action="<?php echo $base_url;?>/functions/reports.php" method="post" class="export-modal-body" target="_blank">
        <h3 class="export-modal-title"></h3>
        <div class="left">
           <select name="" id="stock-type" class="stock-options">
                <option value="">Choose Type</option>
                <option value="ckd">CKD Enter</option>
                <option value="ckdbom">CKD By BOM Enter</option>
                <option value="cbu">CBU Enter</option>
                <option value="manufacturing-parts">Manufacturing Parts Enter</option>
                <option value="spare-parts">Backup Spare Parts Enter</option>
                <option value="additional-parts">Additional Parts Enter</option>
                <option value="ready-bike">Ready Bikes Stock</option>
                <option value="mf-frame-sa-stock">MF Frame & SA Stock</option>
           </select>


           <select name="" id="pending-type" class="pending-options">
                <option value="">Choose Type</option>
                <option value="ckd">CKD In Progress</option>
                <option value="ckdbom">CKD By BOM In Progress</option>
                <option value="cbu">CBU In Progress</option>
           </select>
        
            <p>
                Last 
                <select name="hour" class="options"> 
                    <option value=""></option>
                    <?php for($i = 1; $i <= 24; $i++) echo "<option value='$i'>$i</option>";?> 
                </select> 
                hour
            </p>
            <p>
                From <input type="text" data-range="date-range" name="date-range-start" class="to-from-options date-picker" placeholder="Start Date">
                to  <input type="text" data-range ="date-range" name="date-range-end" class="to-from-options date-picker" placeholder="End Date">
            </p>
            <p>Report of <input id="specific-date" type="text" class="options date-picker" name="specify-date" placeholder="Specify Date"></p>
            <button type="submit" name="generate-report" class="btn btn-primary btn-sm">Generate Report</button>
            <button type="button" class="btn btn-primary btn-sm fade-modal">Cancel</button>
        </div> <!-- /left -->

       <!--  <div class="right">
            <input type="radio" name="quick-options" id="hourly" class="quick-options" value="hourly"> &nbsp; <label for="hourly">Last Hour</label> <br>
            <input type="radio" name="quick-options" id="daily" class="quick-options" value="daily"> &nbsp; <label for="daily">Last Day</label> <br>
            <input type="radio" name="quick-options" id="weekly" class="quick-options" value="weekly"> &nbsp; <label for="weekly">Last Week</label> <br>
            <input type="radio" name="quick-options" id="monthly" class="quick-options" value="monthly"> &nbsp; <label for="monthly">Last Month</label> <br>
            <input type="radio" name="quick-options" id="yearly" class="quick-options" value="yearly"> &nbsp; <label for="yearly">Last Year</label> <br>
        </div> --> <!-- /right -->

        <input type="hidden" name="entries" id="entries" value="">
        <input type="hidden" name="parts" id="parts" value="">
        <input type="hidden" name="type" id="type" value="">
        <input type="hidden" name="status" id="status" value="">
        <input type="hidden" name="report-type" id="report-type" value="">
        <input type="hidden" name="selection-type" id="selection-type" value="">
        <input type="hidden" name="selection-value" id="selection-value" value="">
    </form> <!-- /export-modal-body -->
</div> <!-- /export-modal -->

</body>
</html>


<?php 
if(isset($_GET['current-stock']) || isset($_GET['ready-bike'])){
    $todays_date = date("d-m-Y",time()+4*60*60);
    echo "
    <script>
        $('#export-modal').show();
        $('#pending-type').hide();
        $('#entries').val('stock');
        $('#parts').val('stock_parts');
        $('#report-type').val('pdf');
        $('#export-modal .export-modal-title').text('Stock Status');
        $('#specific-date').val('$todays_date');
    </script>
    ";
    if(isset($_GET['ready-bike'])){
        echo "
        <script>
            $('#stock-type').val('ready-bike');
            $('#type').val('ready-bike');
        </script>
        ";
    }
}
?>