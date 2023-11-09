/*bm_admin_params */
jQuery( function( $ ) {

	var Admin = {
		init: function() {
			$(document).on('click','.bm-hub-generate-key-send-mail-action',this.generate_key_and_send_activation_email);
			$(document).on('click','.bm-delete-post',this.delete_post);
			$(document).on('click','.bm-view-activation-key',this.view_activation_key);
			$(document).on('click','.bm-hub-promotional-send-mail-action',this.promotional_send_mail_action);
			$(document).on('change','.bm-status-selection',this.status_selection);
			$('.bm-status-selection').change();
			$(document).on('click','.bm-preview-action',this.preview_action);
			$(document).on('click','.bm-activation-key',this.copy_activation_key);
			$(document).on('click','.bm-userslist-preview-action',this.userslist_preview_action);
			$(document).on('click','.bm-hub-userslist-import-action',this.userslist_import_action);
			$(document).on('click','.bm-hub-appointment-key-send-mail-action',this.appointment_send_mail_action);
			$(document).on('click','.bm-view-appointment-activation-key',this.view_appointment_activation_key);
			if('toplevel_page_bm_dashboard' == bm_admin_params.screen_id){
				this.display_pie_chart();
				this.display_bar_chart();
			}
		},
		delete_post:function(){
			if(!confirm('Are you sure you want to delete?')){
				return false;
			}
		},
		generate_key_and_send_activation_email:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			
			$('#bm_hub_api_name').closest('td').find('.bm-error').hide();
			if('' == $('#bm_hub_api_name').val()){
			   $('#bm_hub_api_name').closest('td').find('.bm-error').text('API Name is required').css('color','red').show();
				return false;
			}
						
			if(!confirm('Are you sure you want to proceed?')){
				return false;
			}
			
			var data={
				action:'bm_generate_key_and_send_activation_email',
				api_server_type: $('.bm-server-selection').val(),
				api_name: $('#bm_hub_api_name').val(),
				post_id:$this.data('post_id'),
			};
						
			$.ajax({
				url:  bm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					console.log(response);
					if(response.success){
						$.alert({
    						title: 'Success',
    						content: 'Email Sent Successfully',
							boxWidth:'50%',
							useBootstrap: false,
						});
						setTimeout(function () {
							window.location.reload();
						}, 2500);
					}else if(response.data.error){
						$.alert({
    						title: 'Error',
							type: 'red',
    						content: response.data.error,
							boxWidth:'50%',
							useBootstrap: false,
						});
					}
				}
			});
			return false;
		},
		promotional_send_mail_action:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			if(!confirm('Are you sure you want to proceed?')){
				return false;
			}
			
			var data={
				action:'bm_promotional_send_mail_action',
				status_selection: $('.bm-status-selection').val(),
			};
			$.ajax({
				url:  bm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					console.log(response);
					if(response.success){
						$.alert({
    						title: 'Success',
    						content: 'Email Sent Successfully',
							boxWidth:'50%',
							useBootstrap: false,
						});
						if('yes' == $('.bm-import-page').val()){
							window.location.href=response.data.url;
						}
					}else if(response.data.error){
						$.alert({
    						title: 'Error',
							type: 'red',
    						content: response.data.error,
							boxWidth:'50%',
							useBootstrap: false,
						});
					}
				}
			});
			return false;
		},
		view_activation_key:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			$.alert({
    			title: 'Activation key',
    			content: bm_admin_params.bm_get_activation_key_html,
				boxWidth:'50%',
				useBootstrap: false,
			});
			return false;
		},
		status_selection:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			$('#bm_upload').closest('tr').hide();
			$('.bm-upload-action').closest('tr').hide();
			$('.bm-hub-promotional-send-mail-action').show();
			$('.bm-hub-promotional-send-mail-action').closest('tr').show();
			if('import' == $this.val()){
				$('#bm_upload').closest('tr').show();
				$('.bm-upload-action').closest('tr').show();
				$('.bm-hub-promotional-send-mail-action').closest('tr').hide();
			}
		},
		preview_action:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			$.alert({
    			title: 'Preview Emails',
    			content: bm_admin_params.csv_emails_html,
				boxWidth:'50%',
				useBootstrap: false,
			});
			return false;
		},
		userslist_preview_action:function(){
			event.preventDefault();
			var $this = $(event.currentTarget);
			$.alert({
    			title: 'Users List',
    			content: bm_admin_params.csv_userslist_html,
				boxWidth:'75%',
				useBootstrap: false,
			});
			return false;
		},
		userslist_import_action:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			if(!confirm('Are you sure you want to proceed?')){
				return false;
			}
			
			var data={
				action:'bm_userslist_import_action',
			};
			$.ajax({
				url:  bm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					console.log(response);
					if(response.success){
						$.alert({
    						title: 'Success',
    						content: 'Data Imported Successfully',
							boxWidth:'50%',
							useBootstrap: false,
						});
						setTimeout(function () {
							if('yes' == $('.bm-import-page').val()){
								window.location.href=response.data.url;
							}
						},2500);
					}else if(response.data.error){
						$.alert({
    						title: 'Error',
							type: 'red',
    						content: response.data.error,
							boxWidth:'50%',
							useBootstrap: false,
						});
					}
				}
			});
			return false;
		},
		appointment_send_mail_action:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			
			$('#bm_hub_appoinment_api_name').closest('td').find('.bm-error').hide();
			if('' == $('#bm_hub_appoinment_api_name').val()){
			   $('#bm_hub_appoinment_api_name').closest('td').find('.bm-appointment-error').text('API Name is required').css('color','red').show();
				return false;
			}
						
			if(!confirm('Are you sure you want to proceed?')){
				return false;
			}
			
			var data={
				action:'bm_appointment_send_mail_action',
				api_name: $('#bm_hub_appoinment_api_name').val(),
				post_id:$this.data('post_id'),
			};
						
			$.ajax({
				url:  bm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					console.log(response);
					if(response.success){
						$.alert({
    						title: 'Success',
    						content: 'Email Sent Successfully',
							boxWidth:'50%',
							useBootstrap: false,
						});
						setTimeout(function () {
							window.location.reload();
						}, 2500);
					}else if(response.data.error){
						$.alert({
    						title: 'Error',
							type: 'red',
    						content: response.data.error,
							boxWidth:'50%',
							useBootstrap: false,
						});
					}
				}
			});
			return false;
		},
		display_pie_chart:function(event){
			var xValues = ["Not Activated", "Free Trial", "Premium", "Expired"];
			var yValues = [bm_admin_params.not_activated_count, bm_admin_params.free_trial_count, bm_admin_params.premium_count, bm_admin_params.expired_count];
			var barColors = [
  				"#b91d47",
  				"#00aba9",
 			    "#2b5797",
  				"#e8c3b9",
			];

			new Chart("myChart", {
  				type: "pie",
  				data: {
    				labels: xValues,
    				datasets: [{
      					backgroundColor: barColors,
      					data: yValues
    				}]
  				},
  				options: {
    			title: {
      				display: true,
      				text: "Plugin Usage - Current Year"
    			}
  				}
			});
		},
		display_bar_chart(){
			var xValues = ["Jan", "Feb", "Marh", "Apr", "May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
			var array = bm_admin_params.months_count_data.split(',');
			var i,yValues = [];
			for (i = 0; i < array.length; i++) {
				yValues[i] = array[i];
			}
			var barColors = ["red", "green","blue","orange","brown","purple","pink","yellow","violet","cyan","grey","brown"];

			new Chart("bm_bar_chart", {
  				type: "bar",
  				data: {
    				labels: xValues,
    				datasets: [{
      					backgroundColor: barColors,
      					data: yValues
    				}]
  				},
  				options: {
    				legend: {display: false},
    				title: {
      					display: true,
      					text: "Plugin Usage Per Month"
    				}
  				}
			});
		},
		copy_activation_key:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			var $tempElement = $("<input>");
        	$("body").append($tempElement);
        	$tempElement.val($('.bm-activation-key').data('key')).select();
        	document.execCommand("Copy");
        	$tempElement.remove();
			$('.btn-default').click();
			$.alert({
    			title: 'Copied Successfully' ,
    			content:'<div style="border: 2px dotted; background: #ededed; text-align: center; padding: 10px; margin-top: 20px;">'+$('.bm-activation-key').data('key')+'</div>',
				boxWidth:'50%',
				useBootstrap: false,
			});
			return false;
		},
		view_appointment_activation_key:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			$.alert({
    			title: 'Activation key',
    			content: bm_admin_params.bm_get_appointment_activation_key_html,
				boxWidth:'50%',
				useBootstrap: false,
			});
			return false;
		},
	};
	Admin.init();
} );
