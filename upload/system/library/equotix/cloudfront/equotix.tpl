<!-- <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.9";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="tab-pane" id="tab-about">
  <div class="row">
	<div class="col-sm-4">
	  <h2><i class="fa fa-user"></i> Your License</h2>
	  <div class="table-responsive">
	    <table class="table">
		  <tr>
			<td>License Holder</td>
			<td><?php echo $license['name']; ?></td>
		  </tr>
		  <tr>
			<td>Order ID</td>
			<td><?php echo $license['order_id']; ?></td>
		  </tr>
		  <tr>
			<td>License Purchased</td>
			<td><?php echo $license['date_purchased']; ?></td>
		  </tr>
		  <tr>
			<td<?php echo (strtotime($license['date_expired']) < time()) ? ' class="danger"' : ''; ?>><a href="https://marketinsg.zendesk.com/hc/en-us/articles/205102008-One-Year-Premium-Support" target="_blank">Premium Support</a> Expire<?php echo (strtotime($license['date_expired']) < time()) ? 'd' : 's'; ?></td>
			<td<?php echo (strtotime($license['date_expired']) < time()) ? ' class="danger"' : ''; ?>><?php echo $license['date_expired']; ?><?php echo (strtotime($license['date_expired']) < time()) ? ' (<a href="https://www.marketinsg.com/information/renew?extension=' . $purchase_url . '&order_id=' . $license['order_id'] . '" target="_blank">Renew</a>)' : ''; ?></td>
		  </tr>
		  <tr>
		    <td>Licensed Domains</td>
		    <td><?php foreach ($license['domains'] as $domain) { ?>
			  <i class="fa fa-check"></i> <a href="http://<?php echo $domain['domain']; ?>" target="_blank"><?php echo $domain['domain']; ?></a> (<?php echo $domain['date_added']; ?>)<br />
			  <?php } ?></td>
		  </tr>
		  <tr>
			<td colspan="2" class="text-center <?php echo (strtotime($license['date_expired']) < time()) ? 'danger' : 'success'; ?>"><a href="http://license.marketinsg.com/" target="_blank">MANAGE LICENSE</a></td>
		  </tr>
	    </table>
	  </div>
	</div>
	<div class="col-sm-8">
	  <h2><i class="fa fa-heart"></i> Connect with Us</h2>
	  <div class="table-responsive">
	    <table class="table">
	      <tr>
		    <td class="col-xs-3 text-center">
		      <a href="https://marketinsg.zendesk.com/hc/en-us/requests/new" target="_blank" class="btn btn-lg btn-default" style="font-size:60px;" data-toggle="tooltip" title="Open a support ticket"><i class="fa fa-life-ring"></i></a>
		  	  <h2>Support</h2>
		    </td>
		    <td class="col-xs-3 text-center">
		      <a href="https://www.opencart.com/index.php?route=account/rating" target="_blank" class="btn btn-lg btn-default" style="font-size:60px;" data-toggle="tooltip" title="Add a review"><i class="fa fa-star"></i></a>
			  <h2>Rate</h2>
		    </td>
		    <td class="col-xs-3 text-center">
		      <a href="https://www.marketinsg.com/<?php echo $purchase_url; ?>" target="_blank" class="btn btn-lg btn-default" style="font-size:60px;" data-toggle="tooltip" title="Purchase a new license"><i class="fa fa-tag"></i></a>
			  <h2>Purchase</h2>
		    </td>
			<td class="col-xs-3 text-center">
		      <button type="button" id="button-update" class="btn btn-lg btn-default" style="font-size:60px;" data-toggle="tooltip" title="Check for updates"><i class="fa fa-refresh"></i></button>
			  <h2>Update</h2>
		    </td>
		  </tr>
	    </table>
	  </div>
	</div>
  </div>
  <div class="row">
    <div class="col-sm-4">
	  <h2><i class="fa fa-wrench"></i> Premium Services</h2>
	  <div class="responsive">
	    <table class="table">
		  <?php if ($services) { ?>
		    <?php foreach ($services as $service) { ?>
		    <tr>
		      <td><?php echo $service['service']; ?></td>
		      <td><?php echo $service['cost']; ?></td>
		    </tr>
		    <?php } ?>
			<tr>
			  <td colspan="2" class="text-center success">
			    <a href="https://www.marketinsg.com/information/contact/" target="_blank">GET IN TOUCH</a>
			  </td>
			</tr>
		  <?php } ?>
		</table>
	  </div>
	</div>
	<div class="col-sm-8">
	  <h2><i class="fa fa-facebook-official"></i> Follow Us</h2>
	  <div class="responsive">
	    <table class="table">
		  <tr>
		    <td colspan="3" class="text-center">
			  <div class="fb-page" data-href="https://www.facebook.com/Equotix" data-tabs="timeline" data-width="500" data-height="300" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/Equotix" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/Equotix">Equotix</a></blockquote></div>
			</td>
		  </tr>
		</table>
	  </div>
	</div>
  </div>
  <div style="color:#222222;text-align:center;"><?php echo $extension; ?> v<?php echo $version; ?> by <a href="https://www.marketinsg.com" target="_blank">MarketInSG</a></div>
</div>
<?php if (empty($license['license_key'])) { ?>
<div style="position:fixed;height:100%;width:100%;z-index:99999;background:rgba(255,255,255,0.8);top:0;left:0;">
  <div class="container">
	<div class="row" style="padding:80px;">
	  <div class="col-md-4 col-md-offset-4 text-center">
		<h2>Register Extension for Support &amp; Updates</h2>
		<div class="form-group text-left">
		  <label class="control-label" for="license-key">License Key <a href="https://marketinsg.zendesk.com/hc/en-us/articles/205251588-How-should-I-register-the-extension-I-purchased-" target="_blank" style="font-weight:normal">(Where's my license key? <i class="fa fa-question-circle"></i>)</a></label>
		  <input type="text" name="license_key" id="license-key" value="" placeholder="XXXXXXX-XXXXXXX-XXXXXXX-XXXXXXX-XXXXXXX-XXXXXXX" class="form-control" autofocus />
		</div>
		<button type="button" id="button-license" class="btn btn-success btn-lg">Register Website</button>
	  </div>
	</div>
  </div>
</div>
<?php } ?>

<script type="text/javascript">
$('input[name=\'license_key\']').on('keypress', function(e) {
	if (e.which == 13) {
		$('#button-license').trigger('click');
		
		e.preventDefault();
	}
});

$('#button-update').on('click', function() {
	$.ajax({
		url: 'index.php?route=<?php echo $folder; ?>/<?php echo $code; ?>/checkUpdate<?php echo $equotix_token; ?>',
		type: 'get',
		dataType: 'json',
		beforeSend: function() {
			$('#button-update').prop('disabled', true);
			$('#button-update').html('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function(json) {
			$('#button-update').prop('disabled', false);
			$('#button-update').html('<i class="fa fa-refresh"></i>');
			
			$('#tab-about .alert').remove();
			
			$('#tab-about').prepend('<div class="alert ' + json['type'] + '">' + json['message'] + '</div>');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-license').on('click', function() {
	$.ajax({
		url: 'index.php?route=<?php echo $folder; ?>/<?php echo $code; ?>/validateLicense<?php echo $equotix_token; ?>',
		type: 'post',
		data: $('input[name=\'license_key\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-license').prop('disabled', true);
			$('#button-license').after('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function(json) {
			$('#button-license').prop('disabled', false);
			$('.fa-spinner').remove();
		
			if (json['success']) {
				location.reload();
			} else if (json['error']) {
				alert(json['error']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
</script> -->