
<?php   if($rd->po_reference != '') : ?>
<?php    $qs = $po->getPoBacklog($rd->id_po); ?>
  <?php  if(dbNumRows($qs) > 0) : ?>
    <?php $no = 1; ?>
    <?php while($rs = dbFetchObject($qs)) : ?>
      <?php $id_pa = $rs->id_product_attribute; ?>
      <?php $id_pd = $rs->id_product; ?>
      <?php $qty = isset($rs->received) ? (($rs->qty > $rs->received) ? $rs->qty - $rs->received : 0) : $rs->qty; ?>
    <tr id="row-<?php echo $id_pa; ?>">
      <td class="text-center middle no"><?php echo $no; ?></td>
      <td class="middle"><?php echo $rs->reference; ?></td>
      <td class="middle"><?php echo $rs->product_name; ?></td>
      <td class="middle text-center">
        <input type="number" class="form-control input-sm text-center receive-box" id="receive-<?php echo $id_pa; ?>" value="<?php //echo $qty; ?>" />
        <span class="hide" id="label-<?php echo $id_pa; ?>"><?php echo $qty; ?></span>
        <input type="hidden" id="productId-<?php echo $id_pa; ?>" value="<?php echo $id_pd; ?>" />
      </td>
      <td class="middle text-center">
        <button type="button" class="btn btn-sm btn-danger" id="btn-remove-<?php echo $id_pa; ?>" onclick="cancleReceiveItem('<?php echo $id_pa; ?>')">
            <i class="fa fa-trash"></i>
        </button>
      </td>
    </tr>
    <?php $no++; ?>
    <?php endwhile; ?>
  <?php else : ?>
    <tr id="pre_label"><td align='center' colspan='7'><h4>----------  ยังไม่มีสินค้า ----------</h4></td></tr>
  <?php endif; ?>
<?php else : ?>
  <tr id="pre_label"><td align='center' colspan='7'><h4>----------  ยังไม่มีสินค้า ----------</h4></td></tr>
<?php endif; ?>
