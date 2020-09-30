// SET GLOBAL VARIABLE TO USE IN PAGINATION
var leftMost = 1;
var rightMost = 5;




$(document).ready(function(){
	// COLOR CURRENT PAGE
	var url = window.location.href,
		file = url.split('/'),
		file = file[file.length - 1],
		page = file.split('.php')[0],
		group = "";

	if(page == "suppliers" || page == "suppliers-form")	page = "suppliers";
	if(page == "customers" || page == "customers-form")	page = "customers";

	if(page == "ckd" || page == "ckd-details" || page == "ckd-enter" || page == "ckd-edit")	page = "ckd";
	if(page == "ckdbom" || page == "ckdbom-details" || page == "ckdbom-enter" || page == "ckdbom-edit")	page = "ckdbom";
	if(page == "cbu" || page == "cbu-details" || page == "cbu-enter" || page == "cbu-edit")	page = "cbu";
	if(page == "manufacturing-parts" || page == "manufacturing-parts-details" || page == "manufacturing-parts-enter" || page == "manufacturing-parts-edit")	page = "manufacturing-parts";
	if(page == "spare-parts" || page == "spare-parts-details" || page == "spare-parts-enter" || page == "spare-parts-edit")	page = "spare-parts";
	if(page == "additional-parts" || page == "additional-parts-details" || page == "additional-parts-enter" || page == "additional-parts-edit")	page = "additional-parts";
	if(page == "ckd-issue" || page == "ckd-issue-details" || page == "ckd-issue-enter" || page == "ckd-issue-edit")	page = "ckd-issue";
	if(page == "ckd-bom-issue" || page == "ckd-bom-issue-details" || page == "ckd-bom-issue-enter" || page == "ckd-bom-issue-edit")	page = "ckd-bom-issue";
	if(page == "cripple-issue" || page == "cripple-issue-details" || page == "cripple-issue-enter" || page == "cripple-issue-edit")	page = "cripple-issue";
	if(page == "cbu-issue" || page == "cbu-issue-details" || page == "cbu-issue-enter" || page == "cbu-issue-edt")	page = "cbu-issue";
	if(page == "manufacturing-issue" || page == "manufacturing-issue-details" || page == "manufacturing-issue-enter" || page == "manufacturing-issue-edit")	page = "manufacturing-issue";
	if(page == "spare-issue" || page == "spare-issue-details" || page == "spare-issue-enter" || page == "spare-issue-edit")	page = "spare-issue";


	if(page == "ckd-issue-received" || page == "ckd-issue-received-details" || page == "ckd-issue-received-enter" || page == "ckd-issue-received-edit")	page = "ckd-issue-received";
	if(page == "ckd-bom-issue-received" || page == "ckd-bom-issue-received-details" || page == "ckd-bom-issue-received-enter" || page == "ckd-bom-issue-received-edit")	page = "ckd-bom-issue-received";
	if(page == "cripple-issue-received" || page == "cripple-issue-received-details" || page == "cripple-issue-received-enter" || page == "cripple-issue-received-edit")	page = "cripple-issue-received";
	if(page == "cbu-issue-received" || page == "cbu-issue-received-details" || page == "cbu-issue-received-enter" || page == "cbu-issue-received-edit")	page = "cbu-issue-received";
	if(page == "manufacturing-issue-received" || page == "manufacturing-issue-received-details" || page == "manufacturing-issue-received-enter" || page == "manufacturing-issue-received-edit")	page = "manufacturing-issue-received";

	if(page == "order-delivery" || page == "order-delivery-details" || page == "order-delivery-enter" || page == "order-delivery-edit")	page = "order-delivery";
	if(page == "backup-order-delivery" || page == "backup-order-delivery-details" || page == "backup-delivery-enter" || page == "backup-delivery-edit")	page = "backup-order-delivery";
	if(page == "additional-order-delivery" || page == "additional-order-delivery-details" || page == "additional-order-delivery-enter" || page == "additional-order-delivery-edit")	page = "additional-order-delivery";

	if(page == "purchase-requisitions" || page == "purchase-requisitions-details" || page == "purchase-requisitions-enter" || page == "purchase-requisitions-edit")	page = "purchase-requisitions";
	if(page == "return-order" || page == "return-order-details" || page == "return-order-enter" || page == "return-order-edit")	page = "return-order";

	if(page == "ckd-claim" || page == "ckd-claim-details" || page == "ckd-claim-enter" || page == "ckd-claim-edit")	page = "ckd-claim";
	if(page == "ckd-bom-claim" || page == "ckd-bom-claim-details" || page == "ckd-bom-claim-enter" || page == "ckd-bom-claim-edit")	page = "ckd-bom-claim";
	if(page == "cripple-claim" || page == "cripple-claim-details" || page == "cripple-claim-enter" || page == "cripple-claim-edit")	page = "cripple-claim";
	if(page == "cbu-claim" || page == "cbu-claim-details" || page == "cbu-claim-enter" || page == "cbu-claim-edit")	page = "cbu-claim";
	if(page == "manufacturing-claim" || page == "manufacturing-claim-details" || page == "manufacturing-claim-enter" || page == "manufacturing-claim-edit")	page = "manufacturing-claim";
	if(page == "spare-claim" || page == "spare-claim-details" || page == "spare-claim-enter" || page == "spare-claim-edit")	page = "spare-claim";
	if(page == "additional-claim" || page == "additional-claim-details" || page == "additional-claim-enter" || page == "additional-claim-edit")	page = "additional-claim";
	if(page == "claims-claim" || page == "claims-claim-details" || page == "claims-claim-enter" || page == "claims-claim-edit")	page = "claims-claim";

	
	$('nav li a').each(function(){
		if($.trim($(this).data('menu')) == $.trim(page)){
			if($(this).parent('li').parent('ul').hasClass('secondary-level')){
				$(this).closest('ul').show();
				$(this).closest('ul').parent('li').addClass('selected');
				$(this).parent('li').css('padding-left','50px');
				$(this).children('.fa-caret-right').css('opacity','1');
			}
			else{
				$('nav li a').removeClass('selected');
				$(this).addClass('selected');
			}
		}
	});    




	// SET TITLE OF THE PAGE
	var url = window.location.href,
    tmp = url.split('/'),
    page = tmp[tmp.length - 1].split('.php')[0],
    menu = page.split('-');
    if(menu.length > 1) { 
    	page = "";
    	for(var i=0; i<menu.length; i++){
    		page = page + menu[i].substr(0,1).toUpperCase() + menu[i].substr(1) + " ";
    	}
    	menu = page;
    }
    else { menu = page; }
    
	var title = $('.page-title .title').text();
    $('title').text(title.substr(0,1).toUpperCase() + title.substr(1) + " | Rancon Motors");
    $('nav ul li a').each(function(){	
        if($(this).data('select') == menu){
            $(this).addClass('selected').siblings('a').removeClass('selected');
        }
    });




    // DISABLE FORM SUBMIT ON PRESSING ENTER
    $('form input').on('keypress',function(e){
    	if(e.which == 13){
    		e.preventDefault();
    	}
    });




	// SIDEBAR MENU DROP DOWN WHEN A MENU ITEM IS CLICKED
	$('nav .drops-down').on('click',function(){
		$(this).siblings('.secondary-level').slideToggle(300);
	});




	// SIDBEBAR HEIGHT FIXED WHEN THE HEIGHT OF WINDOW CHANGES
	// WHEN THE HEIGHT OF THE WINDOW CHANGES, SET SIDEBAR HEIGHT TO THE HEIGHT OF THE WINDOW
	heightFix();




	// SHOW UPDATE MESSAGE
	$('#status-msg-modal').modal({show:true});




	// SET ALL THE EMPTY FIELDS OF THE TABLES AS '-'
	$('.table td').each(function(){
		if($(this).text() == "")
			$(this).text('----');
	});
	// HIGHLIGHT ROW COLOR ON CLICK
	$('.display-table tbody tr').on('click',function(){
		$(this).addClass('highlighted').siblings().removeClass('highlighted');
		$(this).children('td').addClass('td-color').parents('tr').siblings().children('td').removeClass('td-color');
	});
	$(document).on('click',function(e){
	    if(!$(e.target).closest('.display-table table').length) {
			$('.display-table tbody tr').removeClass('highlighted');
			$('.display-table tbody td').removeClass('td-color');
    	}        
	});




	// SET APPROPRIATE VALUE FOR USER
	$('.deactivate-modal-trigger').on('click',function(){
		var username = $(this).data('user');
		$('#deactivate-modal').find('p em').text(username).closest('p').next('form').children('#selected-user-name').val(username);
	});
	$('.user-edit-modal-trigger').on('click',function(){
		var $this = $(this),
			username = $this.data('user'),
			fullName = $this.data('name'),
			accessLevel = $this.data('access');

		$('#user-edit-modal').find('p em').text(username).closest('p')
			.next('form').children('#update-user-name').val(username)
			.siblings('#update-user-full-name').val(fullName)
			.siblings('#update-user-access').val(accessLevel);
	});




	// SET UP POPOVER
	$('[data-toggle="popover"]').popover(); 




	// MAKE TABLE HEADER FIXED WHEN THE PAGE IS SCROLLED TO A CERTAIN PART
	var editableTable = $('.editable-table');
	if(editableTable[0]){
		var allowedDistance = editableTable.offset().top,
			scrolled = 0;
		
		$(window).on('scroll',function(){
			scrolled = $(this).scrollTop();
			fixedHeaderTable(scrolled,allowedDistance,editableTable);
		});
		$(window).resize(function(){
			allowedDistance = editableTable.offset().top;
			fixedHeaderTable(scrolled,allowedDistance,editableTable);
		});	
	}




	// SEARCH TABLE
	$('#search-input').on('keyup', function() {
	   var that = this;
	    // affect all table rows on in systems table
	    var tableBody = $('.searchable-table tbody');
	    var tableRowsClass = $('.searchable-table tbody tr');
	    tableRowsClass.each( function(i, val) {
	        //Lower text for case insensitive
	        var rowText = $(val).text().toLowerCase();
	        var inputText = $(that).val().toLowerCase();
	        if(inputText.length > 2){
	            $('.search-query-sf').remove();
	            tableBody.prepend('<tr class="search-query-sf"><td colspan="20"><strong>Searching for: "'
	                + $(that).val()
	                + '"</strong></td></tr>');
	            if( rowText.indexOf( inputText ) == -1 ){
	                //hide rows
	                tableRowsClass.eq(i).hide();
	            }
	            else{
	                $('.search-sf').remove();
	                tableRowsClass.eq(i).show();
	            }
	        }
	        else{
	        	$('.hide-row').hide();
	        	$('.show-row').show();
	            $('.search-query-sf').remove();
	            $('.search-sf').remove();
	        }
	    });	

	    //all tr elements are hidden
	    if(tableRowsClass.children(':visible').length == 0){
	        tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="20">No entries found.</td></tr>');
	    }
	});




	// PAGINATIONS
	var rows =  $('table.searchable-table tbody tr'),
		pagination = $('.pagination'),
		numOfRows = rows.length,
		active = 1,
		display = 25,
		pages = "";

	if(numOfRows%display == 0) pages = parseInt(numOfRows/2);
	else pages = parseInt(numOfRows/display) + 1; 
	appendPagination(leftMost, leftMost+4, 1, pages);
	activatePagination(pages,display);




	// GENERATE MORE ROWS TO ADD PARTS AND REMOVE ROWS FROM THE INPUT TABLE OF PARTS
	$('.add-row').on('click',function(){
		var tbody = $(this).closest('table').children('tbody'),
			row = "<tr>" + tbody.children('tr').eq(0).html() + "</tr>";
		$(row).insertAfter(tbody.children('tr').last()).find('input').val('');
		activateDeleteRow();
		heightFix();
	});
	activateDeleteRow();




	// FORM VALIDATIONS
	// PURCHASE ORDER VALIDATIONS
	$('#purchase-decoy-btn').on('click',function(){
		if($('#ordered-quantity').val() < 0 || $('#receiving-quantity').val() < 0){
			var str = "<h3>Please Fix The Following Error and Try Again : </h3>";
			if($('#ordered-quantity').val() < 0){
				str = str + "<p><i class='fa fa-arrow-right'></i>&nbsp; Ordered Quantity Can't be Negative</p>";
			}
			if($('#receiving-quantity').val() < 0){
				str = str + "<p><i class='fa fa-arrow-right'></i>&nbsp; Receiving Quantity Can't be Negative</p>";
			}
			$('.validation-error').empty().append(str);
		}
		else{
			$('.submit-btn').trigger('click');
		}
	});




	// SET SUPPLIER/CUSTOMER NAME AND ADDRESS WHEN SUPPLIER/CUSTOMER CODE IS CHOSEN 
	$('#supplier-code').on('change',function(){	
		$.ajax({
			url : '../../functions/process-forms.php',
			method : 'post',
			data : {val : $(this).val(), supplierCodeChanged : true},
			success : function(context){
				var parts = context.split('//');
				$('#supplier-name').val(parts[0]);
				$('#supplier-address').val(parts[1]);
				$('#supplier-contact').val(parts[2]);

				$('#td-supplier-name .cell-value').text(parts[0]);
				$('#td-supplier-address').text(parts[1]);
				$('#td-supplier-contact').text(parts[2]);
				$('#td-supplier-email').text(parts[3]);
			} 
		});
	});

	$('#customer-code').on('change',function(){	
		$.ajax({
			url : '../../functions/process-forms.php',
			method : 'post',
			data : {val : $(this).val(), customerCodeChanged : true},
			success : function(context){
				var parts = context.split('//');
				$('#customer-name').val(parts[0]);
				$('#customer-address').val(parts[1]);
				$('#customer-contact').val(parts[2]);

				$('#td-customer-name .cell-value').text(parts[0]);
				$('#td-customer-address').text(parts[1]);
				$('#td-customer-contact').text(parts[2]);
			} 
		});
	});




	// SET SUPPLIER/CUSTOMER/FRAME/ENGINE INFORMATION WHEN SUPPLIER/CUSTOMER NAME OR PART NUMBER/COLOR CODE IN DELIVERY CHALLAN PAGE IS CHOSEN 
	$('#supplier-name').on('keyup',function(e){
		if(e.which != 40 && e.which != 38 && e.which !=13){
			$.ajax({
				url : '../../functions/process-forms.php',
				method : 'post',
				data : {val : $(this).val(), supplierNameChanged : true},
				success : function(context){
					if(context == "")	$('.search-result').hide();
					else{
						$('#supplier-name').next('.search-result').show().html(context);
						$('.search-result li').first().addClass('focused');
					}
					activateSearchResult();
				} 
			});
		}
		if(e.which == 40){
			if(!$('.search-result li.focused').hasClass('last-child')){
				$('.search-result li.focused').removeClass('focused').next('li').addClass('focused');
				setSearchValue($('.search-result li.focused'));
			}
		}
		if(e.which == 38){
			if(!$('.search-result li.focused').hasClass('first-child')){
				$('.search-result li.focused').removeClass('focused').prev('li').addClass('focused');
				setSearchValue($('.search-result li.focused'));
			}
		}
		if(e.which == 13){
			$(this).val($('.search-result li.focused').text());
			$('.search-result').hide().empty();
			setSearchValue($('.search-result li.focused'));
		}
	});

	$('#customer-name').on('keyup',function(e){
		if(e.which != 40 && e.which != 38 && e.which !=13){
			$.ajax({
				url : '../../functions/process-forms.php',
				method : 'post',
				data : {val : $(this).val(), customerNameChanged : true},
				success : function(context){
					if(context == "")	$('.search-result').hide();
					else{
						$('#customer-name').next('.search-result').show().html(context);
						$('.search-result li').first().addClass('focused');
					}
					activateSearchResult();
				} 
			});
		}
		if(e.which == 40){
			if(!$('.search-result li.focused').hasClass('last-child')){
				$('.search-result li.focused').removeClass('focused').next('li').addClass('focused');
				setSearchValue($('.search-result li.focused'));
			}
		}
		if(e.which == 38){
			if(!$('.search-result li.focused').hasClass('first-child')){
				$('.search-result li.focused').removeClass('focused').prev('li').addClass('focused');
				setSearchValue($('.search-result li.focused'));
			}
		}
		if(e.which == 13){
			$(this).val($('.search-result li.focused').text());
			$('.search-result').hide();
			setSearchValue($('.search-result li.focused'));
		}
	});

	$('.part-number input').on('change',function(){
		var $this = $(this),
			partNumber = $this.val(),
			colorCode = $this.parent('td').siblings('.color-code').children('input').val();
		
		$.ajax({
			url : '../../functions/process-forms.php',
			method : 'post',
			dataType : 'json',
			data : { setFrameAndEngine : true, partNumber : partNumber, colorCode : colorCode },
			success : function(context){
				$this.parent('td').siblings('.frame-number').children('select').empty().append(context[0]);
				$this.parent('td').siblings('.engine-number').children('select').empty().append(context[1]);
			}
		});
	});

	$('.color-code input').on('change',function(){
		var $this = $(this),
			partNumber = $this.parent('td').siblings('.part-number').children('input').val(),
			colorCode = $this.val();
		
		$.ajax({
			url : '../../functions/process-forms.php',
			method : 'post',
			dataType : 'json',
			data : { setFrameAndEngine : true, partNumber : partNumber, colorCode : colorCode },
			success : function(context){
				$this.parent('td').siblings('.frame-number').children('select').empty().append(context[0]);
				$this.parent('td').siblings('.engine-number').children('select').empty().append(context[1]);
			}
		});
	});

	$(document).on('click',function(e){
	    if(!$(e.target).closest('.input-searchable').length) {
	    	$('.search-result').hide();
    	}        
	});

	$('#supplier-name, #customer-name').on('focusin',function(){
		$('.search-result').css('opacity','1');
		if($(this).val() !== ""){
			$('.search-result').show();
		}
	});

	$('#supplier-name, #customer-name').on('focusout',function(){
		$('.search-result').css('opacity','0');
	});




	// LET USER CHOOSE SUPPLER/CUSTOMER CODE BASED ON WHETHER AN ITEM IS BEING RETURNED TO THE SUPPLIER OR CUSTOMER
	$('#returned-to').on('change',function(){
		var $this = $(this);
		if(($this).val() == "Supplier"){
			$('#return-to-supplier').removeClass('hidden');
			$('#return-to-customer').addClass('hidden');
			$('#supplier-code').attr('name','code').attr('required','required');
			$('#customer-code').attr('name','').removeAttr('required');
		}
		if(($this).val() == "Customer"){
			$('#return-to-customer').removeClass('hidden');
			$('#return-to-supplier').addClass('hidden');
			$('#customer-code').attr('name','code').attr('required','required');
			$('#supplier-code').attr('name','').removeAttr('required');
		}
	});




	// RETURN TO MANAGE FOR INLINE EDITING IN RETURN-ORDER-DETAILS.PHP PAGE
	$('#return-to-type').on('change',function(){
		var $this = $(this);
			
		if(($this).val() == "Supplier"){
			var str = "<table class='table table-bordered'><tr><th>Suppliers Code</th><td><span class='cell-value' style='display:none;'>----</span><select class='edit-basic-input' id='supplier-code' style='display:block;'><option value=''>Supplier Code</option>";
			$.ajax({
				url : '../../functions/process-forms.php',
				method : 'post',
				data : { getSuppliers : true },
				success : function(context){
					str = str + context + "</select></td></tr><tr><th>Supplier Name</th><td id='td-supplier-name'>----</td></tr><tr><th>Supplier Address</th><td id='td-supplier-address'>----</td></tr><tr><th>Supplier Contact</th><td id='td-supplier-contact'>----</td></tr></table>";
					$('#return-order-info-table').empty().append(str);
					$('#supplier-code').on('change',function(){	
						$.ajax({
							url : '../../functions/process-forms.php',
							method : 'post',
							data : {val : $(this).val(), supplierCodeChanged : true},
							success : function(context){
								var parts = context.split('//');
								$('#supplier-name').val(parts[0]);
								$('#supplier-address').val(parts[1]);
								$('#supplier-contact').val(parts[2]);

								$('#td-supplier-name').text(parts[0]);
								$('#td-supplier-address').text(parts[1]);
								$('#td-supplier-contact').text(parts[2]);
								$('#td-supplier-email').text(parts[3]);
							} 
						});
					});
				}
			});
		}


		if(($this).val() == "Customer"){
			var str = "<table class='table table-bordered'><tr><th>Customer Code</th><td><span class='cell-value' style='display:none;'>----</span><select class='edit-basic-input' id='customer-code' style='display:block;'><option value=''>Customer Code</option>";
			$.ajax({
				url : '../../functions/process-forms.php',
				method : 'post',
				data : { getCustomers : true },
				success : function(context){
					str = str + context + "</select></td></tr><tr><th>Customer Name</th><td id='td-customer-name'>----</td></tr><tr><th>Customer Address</th><td id='td-customer-address'>----</td></tr><tr><th>Customer Contact</th><td id='td-customer-contact'>----</td></tr></table>";
					$('#return-order-info-table').empty().append(str);
					$('#customer-code').on('change',function(){	
						$.ajax({
							url : '../../functions/process-forms.php',
							method : 'post',
							data : {val : $(this).val(), customerCodeChanged : true},
							success : function(context){
								var parts = context.split('//');
								$('#customer-name').val(parts[0]);
								$('#customer-address').val(parts[1]);
								$('#customer-contact').val(parts[2]);

								$('#td-customer-name').text(parts[0]);
								$('#td-customer-address').text(parts[1]);
								$('#td-customer-contact').text(parts[2]);
								$('#td-customer-email').text(parts[3]);
							} 
						});
					});
				}
			});
		}
	});




	$('.activate-edit').on('click',function(){
		$('.info-table').find('.edit-basic-input').show();
		$('.edit-basic-input').each(function(){
			$(this).siblings('.cell-value').hide();
			$(this).val($(this).prev('.cell-value').text()).show();
			$(this).closest('tr').find('.supplier-name').val()
		});
	});




	$('.delete-records').on('click',function(e){
		e.preventDefault();
		if(confirm('All the parts associated with this record will be delete too. Are you sure that you want to continue?')){
			var id = $(this).data('ref'),
				type = $(this).data('type'),
				mainTable = $(this).data('main'),
				relatedTable = $(this).data('section');

			$.ajax({
				url : '../../functions/process-forms.php',
				method : 'post',
				data : { mainTable : mainTable, deleteRelatedRows : relatedTable, id : id, type : type },
				success : function(context){
					if(context == "error") alert("Something went wrong. Record could not be deleted.");
					else if(context == "success"){
						window.location.href = window.location.href;
					}
				}
			});	
		}
	});




	$('.inline-edit').on('click',function(e){
		e.preventDefault();
		var $this = $(this),
			id = $this.data('key'),
			type = $this.data('type');
		
		$(this).closest('tr').siblings('tr').find('.edit-input').hide().siblings('.cell-value').show();
		$this.closest('tr').find('.edit-input').each(function(){
			$(this).val($(this).siblings('.cell-value').text());
			$(this).show();
			$(this).siblings('.cell-value').hide();
		});

		$('.editable-table input, .editable-table select').on('keypress',function(ev){
			if(ev.which == 13){
				var values = [];
				$this.closest('tr').find('.edit-input').each(function(){
					values.push($(this).val());
				});
				$.ajax({
					url : '../../functions/process-forms.php',
					method : 'post',
					data : { partsInlineEdit: type, id : id, values : values },
					success : function(context){
						if(context == "true"){
							$this.closest('tr').find('.cell-value').each(function(){
								$(this).text($(this).siblings('.edit-input').val());
								$(this).show();
								$(this).siblings('.edit-input').hide();
							});
						}
					}
				});
			}
		});
	});

	$(document).on('click',function(e){
	    if(!$(e.target).closest('.editable-table').length) {
			$('.editable-table tbody tr').find('td').children('.cell-value').show().siblings('.edit-input').hide();
    	}        
	});




	$('.activate-edit').on('click',function(){
		var $this = $(this),
			id = $this.data('key'),
			section = $this.data('section');

		$('.info-table input, .info-table select').on('keypress',function(ev){
			if(ev.which == 13){
				var values = [];
				$('.edit-basic-input').each(function(){
					values.push($(this).val());
				});
				$.ajax({
					url : '../../functions/process-forms.php',
					method : 'post',
					data : { entriesInlineEdit: true, id : id, values : values , section : section },
					success : function(context){
						if(context == "true"){
							$('.info-table .cell-value').each(function(){
								$(this).text($(this).next('.edit-basic-input').val());
								$(this).show();
								$(this).siblings('.edit-basic-input').hide();
							});
							$('.key-point').text($('.cell-key-point').text());
						}
					}
				});
				$('.search-result').hide();
			}
		});
	});

	$(document).on('click',function(e){
	    if(!$(e.target).closest('.editable-table').length) {
			$('.editable-table tbody tr').find('td').children('.cell-value').show().siblings('.edit-input').hide();
    	}        
	});




	$('#excel-file').on('change',function(){
		var text = $(this).val().split('fakepath')[1].substr(1);
		$('#file-name').empty().append("<i style='margin-right:10px;' class='fa fa-times-circle remove-file-name'></i>" + text);
		$('.remove-file-name').css('cursor','pointer');
		$('.remove-file-name').on('click',function(){
			$('#excel-file').val('');
			$('#file-name').empty();
		});
	});




	// SET VALUES TO RECEIVE ISSUES WHEN A PURCHASE REQUISITION IS SELECTED
	$('#received-requisition-number').on('change',function(){
		var reqNum = $(this).val(),
			type = $(this).data('type');

		$.ajax({
			url : '../../functions/process-forms.php',
			method : 'post',
			dataType : 'json',
			data : { issuesReceived : true, reqNum : reqNum, type : type },
			success : function(context){
				$('#set-site').val(context[0]);
				$('#set-name').val(context[1]);
				$('#set-designation').val(context[2]);
				$('#set-department').val(context[3]);
				$('#set-invoice-no').val(context[4]);
				$('#set-lc-no').val(context[5]);
				$('#set-lot-no').val(context[6]);
				$('#set-ppd-no').val(context[7]);
				$('#set-body').html(context[8]);
				activateDeleteRow();
				heightFix();
			}
		});
	});




	// SET VALUES TO ADDITIONAL PARTS WHEN A PURCHASE REQUISITION IS SELECTED
	$('#parts-purchase-requisition-number').on('change',function(){
		var reqNum = $(this).val();
		$.ajax({
			url : '../../functions/process-forms.php',
			method : 'post',
			dataType : 'json',
			data : { setAdditionalParts : true, reqNum : reqNum },
			success : function(context){
				console.log(context[0]);
				$('#date').val(context[0]);
				$('#site').val(context[1]);
				$('#supplier-code').val(context[2]);
				$('#supplier-name').val(context[3]);
				$('#supplier-address').val(context[4]);
				$('#requisitions-parts').html(context[5]);
			}
		});
	});




	// SET VALUES TO RETURN ORDER WHEN A DELIVERY CHALLAN IS SELECTED
	$('#delivery-challan-number').on('change',function(){
		var challanNum = $(this).val();
		$.ajax({
			url : '../../functions/process-forms.php',
			method : 'post',
			dataType : 'json',
			data : { setReturnOrder : true, challanNum : challanNum },
			success : function(context){
				$('#sales-channel').val(context[0]);
				$('#customer-code').val(context[1]);
				$('#customer-name').val(context[2]);
				$('#customer-address').val(context[3]);
				$('#customer-contact').val(context[4]);
				$('#challan-parts').html(context[5]);
				activateDeleteRow();
				heightFix();
			}
		});
	});




	// DELETE CLAIM IMAGE
	$('#delete-claim-image').on('click',function(e){
		if(!confirm('Are you sure that you want to delete this image?')){
			e.preventDefault();
		}
	});




	// UPDATE PAGES
	var success = false;
	$('.update-records').on('click',function(){
		var $this =  $(this),
			inputs = $('.edit-bulk-inputs'),
			inputSize = inputs.length,
			returnPage = $this.next('.return-page').text();

		$('form').append("<h3 style='float:left; font-size: 18px; color: #293949; margin-left: 180px; margin-top: -45px;'>Please Wait .... <i class='fa fa-spinner fa-spin'></i></h3>");
		inputs.each(function(){
			var entityHolder = $(this).closest('.entity-holder'),
				thisInput = $(this),
				type = "";

			if(thisInput.hasClass('date-picker')) type = "date";

			$.ajax({
				url : '../../functions/process-forms.php',
				method : 'post',
				data : { 
					updatePage : true, 
					table : entityHolder.data('entity'),
					column : thisInput.data('in'),
					value : thisInput.val(),
					updateCol : entityHolder.data('getin'),
					prevVal : thisInput.data('get'),
					type : type
				},
				success : function(context){
					if(inputSize > 1) inputSize--;
					else window.location.href = returnPage;
				}
			});
		});
	});




	// DATE PICKERS IN THE CUSTOMERS ADD AND EDIT PAGE
    $(".date-picker").datepicker({
	    changeMonth: true,
	    changeYear: true,
	    yearRange: '-100:+0',
	    dateFormat: 'dd-mm-yy'
    });




    // SET SELECT ITEM VALUE IN EDIT PAGES
	$('.select-input').each(function(){
		var $this = $(this);
		$this.val($this.data('value'));	
	});




    // IMAGE, EXPORT AND CONTENT MODAL
    $('.content-modal-trigger').on('click',function(){
    	$('.content-modal').fadeIn();
    	$('.content-modal').find('h4 span').text($('.key-point').text());
    });
    $('.content-fadeout').on('click',function(){
    	$(this).closest('.content-modal').fadeOut();
    });

    $('.image-modal-trigger').on('click',function(){
    	$('.image-modal-body img').attr('src', $(this).data('src'));
    	$('#image-key').val($(this).data('key'));
    	$('#image-modal').fadeIn();
		if($('#image-modal').find('#this-image').attr('src') == '../../img/placeholder.jpg'){
			$('#delete-claim-image').hide();
		}
		else{
			$('#delete-claim-image').show();
		}
    });
    $('#image-modal-fadeout').on('click',function(){
    	$(this).closest('#image-modal').fadeOut();
    });
    $('#claim-image-file').on('change',function(){
    	previewImg(this,'this-image');
    });

    $('.export-file').on('click',function(){
    	var $this = $(this),
    		action = $this.data('action'),
    		entries = $this.data('entries'),
    		parts = $this.data('parts'),
    		key = $this.data('key'),
    		fileName = $this.data('fileName');

    	$.ajax({
    		url : '../../functions/process-forms.php',
    		method : 'post',
    		data : { setSessionVars : true, action : action, entries : entries, parts : parts, key : key, fileName : fileName },
    		success : function(){
    			var win = window.open('../../functions/export-file.php','blank');
    			if(win){ win.focus(); }
				else { alert('Please allow popups for this website'); }
    		}
    	});
    });


    // GENERATE REPORTS
    $('.get-excel, .get-pdf').on('click',function(){
    	var $this = $(this);
    	$('#export-modal .export-modal-title').text($this.parent('td').prev('td').text());
    	$('#entries').val($this.parent('td').data('entries'));
    	$('#parts').val($this.parent('td').data('parts'));
    	$('#type').val($this.parent('td').data('type'));
    	$('#status').val($this.parent('td').data('status'));
    	$('#report-type').val($this.attr('name'));
    	if($this.parent('td').data('stock') == "stock")	$('#stock-type').show();
    	else $('#stock-type').hide();
    	if($this.parent('td').data('pending') == "pending")	$('#pending-type').show();
    	else $('#pending-type').hide();
    	
    	$('#export-modal').fadeIn();
    });
    $('.fade-modal').on('click',function(){
    	$('#export-modal').fadeOut();
    	$('#stock-type').val('');
    	$('#pending-type').val('');
    });
    $('#stock-type').on('change',function(){
    	$('#type').val($(this).val());
    });
    $('#pending-type').on('change',function(){
    	$('#type').val($(this).val());
    });


    // CLEAR OTHER INPUT FIELDS WHEN ONE OF THEM HAS VALUES
    $('.options').on('change',function(){
    	var val = $(this).val();
    	$('.options').val('');
    	$('.to-from-options').val('');
    	$('.quick-options').each(function(){
    		$(this).prop('checked',false);
    	});
    	$(this).val(val);
    	$('#selection-type').val('generic');
    	$('#selection-value').val($(this).attr('name'));
    });
    $('.to-from-options').on('change',function(){
    	$('.options').val('');
    	$('.quick-options').each(function(){
    		$(this).prop('checked',false);
    	});
    	$('#selection-type').val('generic');
    	$('#selection-value').val($(this).data('range'));
    });
    $('.quick-options').on('click',function(){
    	$('.options, .to-from-options').val('');
    	$('#selection-type').val('quick');
    	$('#selection-value').val($(this).val());
    });
});




function appendPagination(start,end,activeVal,pages){
	var pagination = $('.pagination');
	pagination.empty();
	if(pages > 1){
		if(start > 1) pagination.append("<li><a href='#' class='go-prev'>«</a></li>");
		for(var i = start; i <= end; i++){
			if(i <= pages){
				if(i == activeVal) pagination.append("<li class='active'><a href='#''>" + i + "</a></li>");
				else pagination.append("<li><a href='#''>" + i + "</a></li>");
			}
			else break;
		}
		if(i <= pages) pagination.append("<li><a href='#' class='go-next'>»</a></li>");	
	}
}




function activatePagination(pages,display){
	$('.pagination li a').on('click',function(e){
		e.preventDefault();
		var $this = $(this),
			totalRow = $('.searchable-table tbody tr').length,
			pageVal = parseInt($.trim($this.text())),
			starting = display * (pageVal-1),
			activeVal = "";

		if($this.hasClass('go-prev') || $this.hasClass('go-next')){
			if($this.hasClass('go-prev')){
				if(leftMost - 1 <= 0) {
					leftMost = 1;
					if(leftMost + 4 > pages) rightMost = pages;
					else rightMost = leftMost + 4;
				}
				else {
					rightMost = leftMost - 1;
					if(rightMost - 4 > 0) leftMost = rightMost - 4;
					else leftMost = 1;
				}
				activeVal = rightMost;
				starting = display * (activeVal-1);
			}
			else{
				if(rightMost + 1 <= pages) leftMost = rightMost + 1;
				if(rightMost + 5 <= pages) rightMost = rightMost + 5;
				else rightMost = pages;
				activeVal = leftMost;
				starting = display * (activeVal-1);
			}
			appendPagination(leftMost, rightMost, activeVal, pages);
			activatePagination(pages,display);
		}
		
		triggerPage(starting,display);
		$this.parent('li').addClass('active').siblings('li').removeClass('active');	
	});
}




function triggerPage(starting,display){
	$('.searchable-table tbody tr').removeClass('show-row').addClass('hide-row').hide();
	if(starting == 0) starting = 1;	
	for(var i = starting; i < starting + display; i++){
		$('#row-num-' + i).removeClass('hide-row').addClass('show-row').show();
	}
}




function activateDeleteRow(){
	$('.trash-it').on('click',function(){
		$(this).closest('tr').remove();
	});
}




function heightFix(){
	if($('.content').height() + 145 > $('nav').height()){
		$('nav').css('min-height', $('.content').height() + 145);
	}
}




function previewImg(input,id) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
	    reader.onload = function(e) {
	    	$('#' + id).attr('src', e.target.result);
	    }
    	reader.readAsDataURL(input.files[0]);
	}
}




function activateSearchResult(){
	$('.search-result li').on('click',function(){
		setSearchValue($(this));
		$('.search-result').hide();
	});
}




function setSearchValue($this){
	$this.parent('ul').prev('input').val($this.text());
	if($this.parent('ul').prev('input').prop('id') == 'supplier-name'){
		$('#supplier-code').val($this.data('code'));
		$('#supplier-address').val($this.data('address'));
		$('#supplier-contact').val($this.data('contact'));

		$('#td-supplier-code').children('.cell-value').text($this.data('code'));
		$('#td-supplier-name').children('.cell-value').text($this.text());
		$('#td-supplier-address').text($this.data('address'));
		$('#td-supplier-contact').text($this.data('contact'));
		$('#td-supplier-email').text($this.data('email'));
	}

	if($this.parent('ul').prev('input').prop('id') == 'customer-name'){
		$('#customer-code').val($this.data('code'));
		$('#customer-address').val($this.data('address'));
		$('#customer-contact').val($this.data('contact'));

		$('#td-customer-code').children('.cell-value').text($this.data('code'));
		$('#td-customer-name').children('.cell-value').text($this.text());
		$('#td-customer-address').text($this.data('address'));
		$('#td-customer-contact').text($this.data('contact'));
		$('#td-customer-email').text($this.data('email'));
	}
}




function fixedHeaderTable(scrolled,allowedDistance,editableTable){
	if(scrolled >= allowedDistance - 20){
		$('#scroll-placeholder').show();
		editableTable.find('thead').css('position','absolute').css('width','auto').css('left','0').css('border-top','1px solid #ccc').css('top', scrolled - allowedDistance + 19 + 'px').css('background','#fff');
	}
	else{
		$('#scroll-placeholder').hide();
		editableTable.find('thead').css('position','static').css('border-top','none').css('background','transparent');
	}
}




async function wait(time){
	await sleep(time);
}




function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}