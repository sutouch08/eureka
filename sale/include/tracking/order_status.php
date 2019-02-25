<div class="row">
  <div class="col-sm-6 col-xs-12">
    <table class="table border-1">
      <tr>
        <td colspan="3" align="center">สถานะ</td>
      </tr>
<?php if(dbNumRows($state) > 0) : ?>
<?php   while($rs = dbFetchObject($state)) : ?>
      <tr style="font-size:10px; background-color: <?php echo state_color($rs->id_order_state); ?>">
        <td style="padding-top: 10px; padding-bottom: 10px; color:#FFF;">
          <?php echo $rs->state_name; ?>
        </td>
        <td style="padding-top: 10px; padding-bottom: 10px; color:#FFF;">
          <?php echo $rs->first_name.' '.$rs->last_name; ?>
        </td>
        <td style="padding-top: 10px; padding-bottom: 10px; color:#FFF;">
          <?php echo date('d-m-Y H:i:s', strtotime($rs->date_add)); ?>
        </td>
      </tr>
<?php   endwhile; ?>
<?php else : ?>
      <tr>
        <td style="padding-top:10px; padding-bottom:10px;">
          <?php echo $order->currentState(); ?>
        </td>
    		<td style="padding-top:10px; padding-bottom:10px; "></td>
    		<td style="padding-top:10px; padding-bottom:10px;">
          <?php echo date('d-m-Y H:i:s', strtotime($order->date_upd)); ?>
        </td>
      </tr>
<?php endif; ?>
    </table>
  </div>
</div>
</hr>
