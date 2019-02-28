<script id="row-template" type="text/x-handlebarsTemplate">
{{#each this}}
<tr id="row-{{id_pa}}">
  <td class="text-center middle no">{{no}}</td>
  <td class="middle">{{pdCode}}</td>
  <td class="middle">{{pdName}}</td>
  <td class="middle text-center">
    <input type="number" class="form-control input-sm text-center receive-box" id="receive-{{id_pa}}" value="{{qty}}" />
    <span class="hide" id="label-{{id_pa}}">{{qty}}</span>
    <input type="hidden" id="productId-{{id_pa}}" value="{{id_pd}}" />
  </td>
  <td class="middle text-center">
    <button type="button" class="btn btn-sm btn-danger" id="btn-remove-{{id_pa}}" onclick="deleteRow({{id_pa}})"><i class="fa fa-trash"></i></button>
  </td>
</tr>
{{/each}}
</script>
