<thead>
    <th width="3%">#</th>
    <th width="10%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="customer_name-fa-caret-up" name="sorting" onclick="sorting_data('-customer_name')">
                <label for="customer_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-customer_name"></i>
                </label>

                <span class="text-uppercase">customer</span>
                <input type="radio" class="d-none" id="customer_name-fa-caret-down" name="sorting" onclick="sorting_data('customer_name')" checked="checked">
                <label for="customer_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-success" id="customer_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="10%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="opening_amt-fa-caret-up" name="sorting" onclick="sorting_data('-opening_amt')">
                <label for="opening_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-opening_amt"></i>
                </label>

                <span class="text-uppercase">opening amt</span>
                <input type="radio" class="d-none" id="opening_amt-fa-caret-down" name="sorting" onclick="sorting_data('opening_amt')">
                <label for="opening_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="opening_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="10%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="sales_amt-fa-caret-up" name="sorting" onclick="sorting_data('-sales_amt')">
                <label for="sales_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-sales_amt"></i>
                </label>

                <span class="text-uppercase">order amt</span>
                <input type="radio" class="d-none" id="sales_amt-fa-caret-down" name="sorting" onclick="sorting_data('sales_amt')">
                <label for="sales_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="sales_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="10%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="receipt_amt-fa-caret-up" name="sorting" onclick="sorting_data('-receipt_amt')">
                <label for="receipt_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-receipt_amt"></i>
                </label>

                <span class="text-uppercase">receipt amt</span>
                <input type="radio" class="d-none" id="receipt_amt-fa-caret-down" name="sorting" onclick="sorting_data('receipt_amt')">
                <label for="receipt_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="receipt_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="10%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="closing_amt-fa-caret-up" name="sorting" onclick="sorting_data('-closing_amt')">
                <label for="closing_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-closing_amt"></i>
                </label>

                <span class="text-uppercase">closing amt</span>
                <input type="radio" class="d-none" id="closing_amt-fa-caret-down" name="sorting" onclick="sorting_data('closing_amt')">
                <label for="closing_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="closing_amt"></i>
                </label>
            </div>
        </div>
    </th>
</thead>