<thead>
    <th width="3%">#</th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="module_name-fa-caret-up" name="sorting" onclick="sorting_data('-module_name')">
                <label for="module_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-module_name"></i>
                </label>

                <span class="text-uppercase">type</span>
                <input type="radio" class="d-none" id="module_name-fa-caret-down" name="sorting" onclick="sorting_data('module_name')">
                <label for="module_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="module_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="entry_date-fa-caret-up" name="sorting" onclick="sorting_data('-entry_date')" checked="checked">
                <label for="entry_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-success" id="-entry_date"></i>
                </label>

                <span class="text-uppercase">entry date</span>
                <input type="radio" class="d-none" id="entry_date-fa-caret-down" name="sorting" onclick="sorting_data('entry_date')">
                <label for="entry_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="entry_date"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="payment_mode_name-fa-caret-up" name="sorting" onclick="sorting_data('-payment_mode_name')">
                <label for="payment_mode_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-payment_mode_name"></i>
                </label>

                <span class="text-uppercase">payment mode</span>
                <input type="radio" class="d-none" id="payment_mode_name-fa-caret-down" name="sorting" onclick="sorting_data('payment_mode_name')">
                <label for="payment_mode_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="payment_mode_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="payment_mode_amt-fa-caret-up" name="sorting" onclick="sorting_data('-payment_mode_amt')">
                <label for="payment_mode_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-payment_mode_amt"></i>
                </label>

                <span class="text-uppercase">amt <br/> <span id="totals_payment_mode_amt"><?php echo $data['totals']['payment_mode_amt']; ?></span></span>
                <input type="radio" class="d-none" id="payment_mode_amt-fa-caret-down" name="sorting" onclick="sorting_data('payment_mode_amt')">
                <label for="payment_mode_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="payment_mode_amt"></i>
                </label>
            </div>
        </div>
    </th>
</thead>