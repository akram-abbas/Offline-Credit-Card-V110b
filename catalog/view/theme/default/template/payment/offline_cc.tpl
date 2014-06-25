<?php 
$thisyear = date('Y');
$startdate = '2000';
$count = $thisyear - $startdate;

$x=0;
while($x<=$count)
  {
  	
  $exp_block.='<option value="'.$startdate.'">'.$startdate.'</option>';
  
   $x++;
   $startdate++;
  } 

?>

<h2><?php echo $text_credit_card; ?></h2>
<div id="payment">
  <table class="form">
    <tr <?php if(!$use_cc_name) { echo 'style="display:none"'; } ?>>
      <td width="6"><?php echo $entry_cc_owner; ?></td>
      <td width="167"><input type="text" name="card_name" value="" /></td>
    </tr>
    <tr <?php if(!$use_cc_type) { echo 'style="display:none"'; } ?>>
      <td><?php echo $entry_cc_type; ?></td>
      <td><select name="card_type">
      	<option value="" selected="selected" disabled="disabled"><?php echo $entry_cc_type; ?></option>
	  <?php foreach($use_cc_type_list as $row) {?>
      	<option value="<?php echo $row; ?>"><?php echo $row; ?></option>
      <?php } ?></select>
      </td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_number; ?></td>
      <td><input type="text" name="cc_number" value="" /></td>
    </tr>
    <tr>
      <td>Start Date<br />
<em>(if present)</em></td>
      <td><select name="cc_start_date_month">
      <option disabled="disabled" selected="selected"></option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
          
        </select>
        /
        <select name="cc_start_date_year">
        <option disabled="disabled" selected="selected"></option>
         <?php echo $exp_block; ?>
        </select></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_expire_date; ?></td>
      <td><select name="cc_expire_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select name="cc_expire_date_year">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_cvv2; ?></td>
      <td><input type="text" name="cc_cvv2" value="" size="3" /></td>
    </tr>
  </table>
</div>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=payment/offline_cc/send',
		data: $('#payment :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			
			$('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
				
				$('#button-confirm').attr('disabled', false);
			}
			
			$('.attention').remove();
			
			if (json['success']) {
				location = json['success'];
			}
		}
	});
});
//--></script>