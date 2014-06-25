<?php echo $header; ?>

<script language="javascript">
<!-- Begin
function getRandomNum(lbound, ubound) {
return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
}

function getRandomChar() {
var numberChars = "0123456789";
var lowerChars = "abcdefghijklmnopqrstuvwxyz";
var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
var otherChars = "`@#$%";
var charSet = numberChars;
charSet += lowerChars;
charSet += upperChars;
charSet += otherChars;
return charSet.charAt(getRandomNum(0, charSet.length));
}

function getPassword() {

length = 44;
	
var rc = "";
if (length > 0)
rc = rc + getRandomChar();
for (var idx = 1; idx < length; ++idx) {
rc = rc + getRandomChar();
}

document.getElementById('offline_encryption').value = rc;

}
// End -->
</script>




<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $entry_total; ?></td>
            <td><input type="text" name="offline_cc_total" value="<?php echo $offline_cc_total; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="offline_cc_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $offline_cc_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="offline_cc_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $offline_cc_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
         
          <tr>
            <td><?php echo $entry_email; ?></td>
            <td><input type="text" name="offline_email" value="<?php echo $offline_email; ?>" size="20" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_encryption; ?></td>
            <td><input type="text" name="offline_encryption" id="offline_encryption" value="<?php echo $offline_encryption; ?>" size="40" /> <a style="cursor:pointer;" onmouseup="getPassword();">Generate Safe Password</a></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="offline_cc_sort_order" value="<?php echo $offline_cc_sort_order; ?>" size="1" /></td>
          </tr>
          
          <tr>
            <td>Save Credit Card Name Seperate From Billing Name?</td>
            <td><input type="radio" name="entry_use_cc_name" value="1" <?php if($entry_use_cc_name) { ?>checked="checked"<?php } ?> /> Yes <input type="radio" name="entry_use_cc_name" value="0" <?php if(!$entry_use_cc_name) { ?>checked="checked"<?php } ?> /> No</td>
          </tr>
          
          <tr>
            <td>Save Credit Card Type?</td>
            <td><input type="radio" name="entry_use_cc_type" value="1" <?php if($entry_use_cc_type) { ?>checked="checked"<?php } ?> onclick="showCC();" /> Yes <input type="radio" name="entry_use_cc_type" value="0" <?php if(!$entry_use_cc_type) { ?>checked="checked"<?php } ?> onclick="hideCC();" /> No</td>
          </tr>
          
          
          <tr id="ccitems">
            <td>Accepted Credit Cards</td>
            <td>
            	<div><input type="checkbox" name="entry_accept_visa" value="1"  <?php if($entry_accept_visa) { ?>checked="checked"<?php } ?> /> Visa</div>
                <div><input type="checkbox" name="entry_accept_master" value="1"  <?php if($entry_accept_master) { ?>checked="checked"<?php } ?> /> MasterCard</div>
                <div><input type="checkbox" name="entry_accept_ae" value="1"  <?php if($entry_accept_ae) { ?>checked="checked"<?php } ?> /> American Express</div>
                <div><input type="checkbox" name="entry_accept_cu" value="1"  <?php if($entry_accept_cu) { ?>checked="checked"<?php } ?> /> China UnionPay</div>
                <div><input type="checkbox" name="entry_accept_jcb" value="1"  <?php if($entry_accept_jcb) { ?>checked="checked"<?php } ?> /> JCB</div>
            </td>
          </tr>
           <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="offline_cc_status">
                <?php if ($offline_cc_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script>

function showCC() {
	$('ccitems').fadeIn();	
}

function hideCC() {
	$('ccitems').fadeOut();	
}

</script>
<?php echo $footer; ?> 