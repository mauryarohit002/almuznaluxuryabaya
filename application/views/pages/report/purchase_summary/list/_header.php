<thead>
    <th width="3%">#</th>
    <th width="4%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="entry_no-fa-caret-up" name="sorting" onclick="sorting_data('-entry_no')" checked="checked">
                <label for="entry_no-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-success" id="-entry_no"></i>
                </label>

                <span class="text-uppercase">entry no</span>
                <input type="radio" class="d-none" id="entry_no-fa-caret-down" name="sorting" onclick="sorting_data('entry_no')">
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

                <span class="text-uppercase">entry date</span>
                <input type="radio" class="d-none" id="entry_date-fa-caret-down" name="sorting" onclick="sorting_data('entry_date')">
                <label for="entry_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="entry_date"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="10%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="supplier_name-fa-caret-up" name="sorting" onclick="sorting_data('-supplier_name')">
                <label for="supplier_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-supplier_name"></i>
                </label>

                <span class="text-uppercase">supplier name</span>
                <input type="radio" class="d-none" id="supplier_name-fa-caret-down" name="sorting" onclick="sorting_data('supplier_name')">
                <label for="supplier_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="supplier_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="8%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="supplier_mobile-fa-caret-up" name="sorting" onclick="sorting_data('-supplier_mobile')">
                <label for="supplier_mobile-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-supplier_mobile"></i>
                </label>

                <span class="text-uppercase">supplier mobile</span>
                <input type="radio" class="d-none" id="supplier_mobile-fa-caret-down" name="sorting" onclick="sorting_data('supplier_mobile')">
                <label for="supplier_mobile-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="supplier_mobile"></i>
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
                <input type="radio" class="d-none" id="sub_amt-fa-caret-up" name="sorting" onclick="sorting_data('-sub_amt')">
                <label for="sub_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-sub_amt"></i>
                </label>

                <span class="text-uppercase">sub amt <br/> <span id="totals_sub_amt"><?php echo $data['totals']['sub_amt']; ?></span></span>
                <input type="radio" class="d-none" id="sub_amt-fa-caret-down" name="sorting" onclick="sorting_data('sub_amt')">
                <label for="sub_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="sub_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="disc_amt-fa-caret-up" name="sorting" onclick="sorting_data('-disc_amt')">
                <label for="disc_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-disc_amt"></i>
                </label>

                <span class="text-uppercase">disc amt <br/> <span id="totals_disc_amt"><?php echo $data['totals']['disc_amt']; ?></span></span>
                <input type="radio" class="d-none" id="disc_amt-fa-caret-down" name="sorting" onclick="sorting_data('disc_amt')">
                <label for="disc_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="disc_amt"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="6%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="taxable_amt-fa-caret-up" name="sorting" onclick="sorting_data('-taxable_amt')">
                <label for="taxable_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-taxable_amt"></i>
                </label>

                <span class="text-uppercase">taxable amt <br/> <span id="totals_taxable_amt"><?php echo $data['totals']['taxable_amt']; ?></span></span>
                <input type="radio" class="d-none" id="taxable_amt-fa-caret-down" name="sorting" onclick="sorting_data('taxable_amt')">
                <label for="taxable_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="taxable_amt"></i>
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
                <input type="radio" class="d-none" id="bill_disc_amt-fa-caret-up" name="sorting" onclick="sorting_data('-bill_disc_amt')">
                <label for="bill_disc_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-bill_disc_amt"></i>
                </label>

                <span class="text-uppercase">bill disc <br/> <span id="totals_bill_disc_amt"><?php echo $data['totals']['bill_disc_amt']; ?></span></span>
                <input type="radio" class="d-none" id="bill_disc_amt-fa-caret-down" name="sorting" onclick="sorting_data('bill_disc_amt')">
                <label for="bill_disc_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="bill_disc_amt"></i>
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