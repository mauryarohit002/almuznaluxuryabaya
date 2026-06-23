<thead>
    <th width="10%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="apparel_name-fa-caret-up" name="sorting" onclick="sorting_data('-apparel_name')" checked="checked">
                <label for="apparel_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-success" id="-apparel_name"></i>
                </label>

                <span class="text-uppercase">item / product</span>
                <input type="radio" class="d-none" id="apparel_name-fa-caret-down" name="sorting" onclick="sorting_data('apparel_name')">
                <label for="apparel_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="apparel_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="4%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="hsn_name-fa-caret-up" name="sorting" onclick="sorting_data('-hsn_name')">
                <label for="hsn_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-hsn_name"></i>
                </label>

                <span class="text-uppercase">hsn</span>
                <input type="radio" class="d-none" id="hsn_name-fa-caret-down" name="sorting" onclick="sorting_data('hsn_name')">
                <label for="hsn_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="hsn_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="total_mtr-fa-caret-up" name="sorting" onclick="sorting_data('-total_mtr')">
                <label for="total_mtr-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-total_mtr"></i>
                </label>

                <span class="text-uppercase">total mtr <br/> <span id="totals_total_mtr"><?php echo $data['totals']['total_mtr']; ?></span></span>
                <input type="radio" class="d-none" id="total_mtr-fa-caret-down" name="sorting" onclick="sorting_data('total_mtr')">
                <label for="total_mtr-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="total_mtr"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="amt-fa-caret-up" name="sorting" onclick="sorting_data('-amt')">
                <label for="amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-amt"></i>
                </label>

                <span class="text-uppercase">amt  <br/> <span id="totals_amt"><?php echo $data['totals']['amt']; ?></span>
                <input type="radio" class="d-none" id="amt-fa-caret-down" name="sorting" onclick="sorting_data('amt')">
                <label for="amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="sgst_amt-fa-caret-up" name="sorting" onclick="sorting_data('-sgst_amt')">
                <label for="sgst_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-sgst_amt"></i>
                </label>

                <span class="text-uppercase">sgst amt <br/> <span id="totals_sgst_amt"><?php echo $data['totals']['sgst_amt']; ?></span></span>
                <input type="radio" class="d-none" id="sgst_amt-fa-caret-down" name="sorting" onclick="sorting_data('sgst_amt')">
                <label for="sgst_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="sgst_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="cgst_amt-fa-caret-up" name="sorting" onclick="sorting_data('-cgst_amt')">
                <label for="cgst_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-cgst_amt"></i>
                </label>

                <span class="text-uppercase">cgst amt <br/> <span id="totals_cgst_amt"><?php echo $data['totals']['cgst_amt']; ?></span></span>
                <input type="radio" class="d-none" id="cgst_amt-fa-caret-down" name="sorting" onclick="sorting_data('cgst_amt')">
                <label for="cgst_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="cgst_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="igst_amt-fa-caret-up" name="sorting" onclick="sorting_data('-igst_amt')">
                <label for="igst_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-igst_amt"></i>
                </label>

                <span class="text-uppercase">igst amt <br/> <span id="totals_igst_amt"><?php echo $data['totals']['igst_amt']; ?></span></span>
                <input type="radio" class="d-none" id="igst_amt-fa-caret-down" name="sorting" onclick="sorting_data('igst_amt')">
                <label for="igst_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="igst_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="tax_amt-fa-caret-up" name="sorting" onclick="sorting_data('-tax_amt')">
                <label for="tax_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-tax_amt"></i>
                </label>

                <span class="text-uppercase">tax amt <br/> <span id="totals_tax_amt"><?php echo $data['totals']['tax_amt']; ?></span></span>
                <input type="radio" class="d-none" id="tax_amt-fa-caret-down" name="sorting" onclick="sorting_data('tax_amt')">
                <label for="tax_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="tax_amt"></i>
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

                <span class="text-uppercase">total amt <br/> <span id="totals_total_amt"><?php echo $data['totals']['total_amt']; ?></span></span>
                <input type="radio" class="d-none" id="total_amt-fa-caret-down" name="sorting" onclick="sorting_data('total_amt')">
                <label for="total_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="total_amt"></i>
                </label>
            </div>
        </div>
    </th>
</thead>