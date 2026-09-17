<thead>
    <th width="3%">#</th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="start_date-fa-caret-up" name="sorting" onclick="sorting_data('-start_date')" checked="checked">
                <label for="start_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-success" id="-start_date"></i>
                </label>
                <span class="text-uppercase">Month</span>
                <input type="radio" class="d-none" id="start_date-fa-caret-down" name="sorting" onclick="sorting_data('start_date')" >
                <label for="start_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="start_date"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="open_qty-fa-caret-up" name="sorting" onclick="sorting_data('-open_qty')">
                <label for="open_qty-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-open_qty"></i>
                </label>
                <span class="text-uppercase">opening&nbsp;qty</span>
                <input type="radio" class="d-none" id="open_qty-fa-caret-down" name="sorting" onclick="sorting_data('open_qty')">
                <label for="open_qty-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="open_qty"></i>
                </label>
            </div>
        </div>
    </th>
   
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="pur_qty-fa-caret-up" name="sorting" onclick="sorting_data('-pur_qty')">
                <label for="pur_qty-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-pur_qty"></i>
                </label>
                <span class="text-uppercase">pur qty</span>
                <input type="radio" class="d-none" id="pur_qty-fa-caret-down" name="sorting" onclick="sorting_data('pur_qty')">
                <label for="pur_qty-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="pur_qty"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="pur_return_qty-fa-caret-up" name="sorting" onclick="sorting_data('-pur_return_qty')">
                <label for="pur_return_qty-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-pur_return_qty"></i>
                </label>
                <span class="text-uppercase">return qty</span>
                <input type="radio" class="d-none" id="pur_return_qty-fa-caret-down" name="sorting" onclick="sorting_data('pur_return_qty')">
                <label for="pur_return_qty-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="pur_return_qty"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="outward_qty-fa-caret-up" name="sorting" onclick="sorting_data('-outward_qty')">
                <label for="outward_qty-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-outward_qty"></i>
                </label>
                <span class="text-uppercase">outward qty</span>
                <input type="radio" class="d-none" id="outward_qty-fa-caret-down" name="sorting" onclick="sorting_data('outward_qty')">
                <label for="outward_qty-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="outward_qty"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="inward_qty-fa-caret-up" name="sorting" onclick="sorting_data('-inward_qty')">
                <label for="inward_qty-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-inward_qty"></i>
                </label>
                <span class="text-uppercase">inward qty</span>
                <input type="radio" class="d-none" id="inward_qty-fa-caret-down" name="sorting" onclick="sorting_data('inward_qty')">
                <label for="inward_qty-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="inward_qty"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="sale_qty-fa-caret-up" name="sorting" onclick="sorting_data('-sale_qty')">
                <label for="sale_qty-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-sale_qty"></i>
                </label>
                <span class="text-uppercase">sale qty</span>
                <input type="radio" class="d-none" id="sale_qty-fa-caret-down" name="sorting" onclick="sorting_data('sale_qty')">
                <label for="sale_qty-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="sale_qty"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="close_qty-fa-caret-up" name="sorting" onclick="sorting_data('-close_qty')">
                <label for="close_qty-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-close_qty"></i>
                </label>
                <span class="text-uppercase">closing qty</span>
                <input type="radio" class="d-none" id="close_qty-fa-caret-down" name="sorting" onclick="sorting_data('close_qty')">
                <label for="close_qty-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="close_qty"></i>
                </label>
            </div>
        </div>
    </th>
   
   
</thead>