<thead>
    <th width="3%">#</th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="entry_no-fa-caret-up" name="sorting" onclick="sorting_data('-entry_no')" checked="checked">
                <label for="entry_no-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-success" id="-entry_no"></i>
                </label>
                <span class="text-uppercase">Order no</span>
                <input type="radio" class="d-none" id="entry_no-fa-caret-down" name="sorting" onclick="sorting_data('entry_no')" >
                <label for="entry_no-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="entry_no"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="entry_date-fa-caret-up" name="sorting" onclick="sorting_data('-entry_date')">
                <label for="entry_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-entry_date"></i>
                </label>
                <span class="text-uppercase">Order&nbsp;date</span>
                <input type="radio" class="d-none" id="entry_date-fa-caret-down" name="sorting" onclick="sorting_data('entry_date')">
                <label for="entry_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="entry_date"></i>
                </label>
            </div>
        </div>
    </th>
   
    <th width="8%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="customer_name-fa-caret-up" name="sorting" onclick="sorting_data('-customer_name')">
                <label for="customer_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-customer_name"></i>
                </label>

                <span class="text-uppercase">customer</span>
                <input type="radio" class="d-none" id="customer_name-fa-caret-down" name="sorting" onclick="sorting_data('customer_name')">
                <label for="customer_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="customer_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="8%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="customer_mobile-fa-caret-up" name="sorting" onclick="sorting_data('-customer_mobile')">
                <label for="customer_mobile-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-customer_mobile"></i>
                </label>
                <span class="text-uppercase">Customer Mobile</span>
                <input type="radio" class="d-none" id="customer_mobile-fa-caret-down" name="sorting" onclick="sorting_data('customer_mobile')">
                <label for="customer_mobile-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="customer_mobile"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="trial_date-fa-caret-up" name="sorting" onclick="sorting_data('-trial_date')">
                <label for="trial_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-trial_date"></i>
                </label>
                <span class="text-uppercase">trial Date</span>
                <input type="radio" class="d-none" id="trial_date-fa-caret-down" name="sorting" onclick="sorting_data('trial_date')">
                <label for="trial_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="trial_date"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="delivery_date-fa-caret-up" name="sorting" onclick="sorting_data('-delivery_date')">
                <label for="delivery_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-delivery_date"></i>
                </label>
                <span class="text-uppercase">delivery date</span>
                <input type="radio" class="d-none" id="delivery_date-fa-caret-down" name="sorting" onclick="sorting_data('delivery_date')">
                <label for="delivery_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="delivery_date"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="total_amt-fa-caret-up" name="sorting" onclick="sorting_data('-total_amt')">
                <label for="total_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-total_amt"></i>
                </label>
                <span class="text-uppercase">total amt</span>
                <input type="radio" class="d-none" id="total_amt-fa-caret-down" name="sorting" onclick="sorting_data('total_amt')">
                <label for="total_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="total_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="advance_amt-fa-caret-up" name="sorting" onclick="sorting_data('-advance_amt')">
                <label for="advance_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-advance_amt"></i>
                </label>
                <span class="text-uppercase">adv amt</span>
                <input type="radio" class="d-none" id="advance_amt-fa-caret-down" name="sorting" onclick="sorting_data('advance_amt')">
                <label for="advance_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="advance_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="allocated_amt-fa-caret-up" name="sorting" onclick="sorting_data('-allocated_amt')">
                <label for="allocated_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-allocated_amt"></i>
                </label>
                <span class="text-uppercase">receipt amt</span>
                <input type="radio" class="d-none" id="allocated_amt-fa-caret-down" name="sorting" onclick="sorting_data('allocated_amt')">
                <label for="allocated_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="allocated_amt"></i>
                </label>
            </div>
        </div>
    </th>
     <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="balance_amt-fa-caret-up" name="sorting" onclick="sorting_data('-balance_amt')">
                <label for="balance_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-balance_amt"></i>
                </label>
                <span class="text-uppercase">Bal amt</span>
                <input type="radio" class="d-none" id="balance_amt-fa-caret-down" name="sorting" onclick="sorting_data('balance_amt')">
                <label for="balance_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="balance_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="status-fa-caret-up" name="sorting" onclick="sorting_data('-status')">
                <label for="status-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-status"></i>
                </label>
                <span class="text-uppercase">del status</span>
                <input type="radio" class="d-none" id="status-fa-caret-down" name="sorting" onclick="sorting_data('status')">
                <label for="status-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="status"></i>
                </label>
            </div>
        </div>
    </th>
</thead>