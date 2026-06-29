<thead>
    <tr>
        <th width="3%">#</th>
        <th width="7%">entry no</th>
        <th width="10%">entry date</th>
        <th width="15%">order no</th>
        <th width="15%">customer</th>
        <th width="10%">receipt amt</th>
        <th width="15%">notes</th>
        <th width="10%">status</th>
        <th width="3%">print</th> 

        <?php if(in_array('view', $action_data)): ?>
            <th width="3%">view</th> 
        <?php endif; ?>
        <?php if(in_array('edit', $action_data)): ?>
            <th width="3%">edit</th> 
        <?php endif; ?>
        <?php if(in_array('delete', $action_data)): ?>
            <th width="3%">delete</th>
        <?php endif; ?>
    </tr>
</thead>