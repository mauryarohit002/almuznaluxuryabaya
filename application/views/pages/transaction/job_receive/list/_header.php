<thead>
    <th width="3%">#</th>
    <th width="5%">entry no</th>
    <th width="8%">entry date</th>
    <th width="5%">order no</th>
    <th width="8%">order date</th>
    <th width="8%">barcode</th>
    <th width="10%">sku</th>
    <th width="10%">Process</th>
    <th width="10%">Karigar</th>
    <!-- <th width="8%">view</th> -->
    <?php if(in_array('edit', $action_data)): ?>
        <th width="3%">edit</th> 
    <?php endif; ?>
    <?php if(in_array('delete', $action_data)): ?>
        <th width="3%">delete</th>
    <?php endif; ?>
</thead>