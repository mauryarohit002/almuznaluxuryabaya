<thead>
    <th width="3%">#</th>
    <th width="4%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="bill_no-fa-caret-up" name="sorting" onclick="sorting_by('bill_no', 'asc')" checked="checked">
                <label for="bill_no-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-success" id="bill_no_asc"></i>
                </label>

                <span class="text-uppercase">bill no</span>
                <input type="radio" class="d-none" id="bill_no-fa-caret-down" name="sorting" onclick="sorting_by('bill_no', 'desc')">
                <label for="bill_no-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="bill_no_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="bill_date-fa-caret-up" name="sorting" onclick="sorting_by('bill_date', 'asc')">
                <label for="bill_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="bill_date_asc"></i>
                </label>

                <span class="text-uppercase">bill date</span>
                <input type="radio" class="d-none" id="bill_date-fa-caret-down" name="sorting" onclick="sorting_by('bill_date', 'desc')">
                <label for="bill_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="bill_date_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="8%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="supplier_name-fa-caret-up" name="sorting" onclick="sorting_by('supplier_name', 'asc')">
                <label for="supplier_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="supplier_name_asc"></i>
                </label>

                <span class="text-uppercase">supplier name</span>
                <input type="radio" class="d-none" id="supplier_name-fa-caret-down" name="sorting" onclick="sorting_by('supplier_name', 'desc')">
                <label for="supplier_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="supplier_name_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="product_name-fa-caret-up" name="sorting" onclick="sorting_by('product_name', 'asc')">
                <label for="product_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="product_name_asc"></i>
                </label>

                <span class="text-uppercase">product</span>
                <input type="radio" class="d-none" id="product_name-fa-caret-down" name="sorting" onclick="sorting_by('product_name', 'desc')">
                <label for="product_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="product_name_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="design_name-fa-caret-up" name="sorting" onclick="sorting_by('design_name', 'asc')">
                <label for="design_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="design_name_asc"></i>
                </label>
                <span class="text-uppercase">fabric code</span>
                <input type="radio" class="d-none" id="design_name-fa-caret-down" name="sorting" onclick="sorting_by('design_name', 'desc')">
                <label for="design_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="design_name_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="color_name-fa-caret-up" name="sorting" onclick="sorting_by('color_name', 'asc')">
                <label for="color_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="color_name_asc"></i>
                </label>

                <span class="text-uppercase">color</span>
                <input type="radio" class="d-none" id="color_name-fa-caret-down" name="sorting" onclick="sorting_by('color_name', 'desc')">
                <label for="color_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="color_name_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="4%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="size_name-fa-caret-up" name="sorting" onclick="sorting_by('size_name', 'asc')">
                <label for="size_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="size_name_asc"></i>
                </label>

                <span class="text-uppercase">size</span>
                <input type="radio" class="d-none" id="size_name-fa-caret-down" name="sorting" onclick="sorting_by('size_name', 'desc')">
                <label for="size_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="size_name_desc"></i>
                </label>
            </div>
        </div>
    </th>

    <th width="4%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="qty-fa-caret-up" name="sorting" onclick="sorting_by('qty', 'asc')">
                <label for="qty-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="qty_asc"></i>
                </label>

                <span class="text-uppercase">qty</span>
                <input type="radio" class="d-none" id="qty-fa-caret-down" name="sorting" onclick="sorting_by('qty', 'desc')">
                <label for="qty-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="qty_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="4%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="rate-fa-caret-up" name="sorting" onclick="sorting_by('rate', 'asc')">
                <label for="rate-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="rate_asc"></i>
                </label>

                <span class="text-uppercase">rate</span>
                <input type="radio" class="d-none" id="rate-fa-caret-down" name="sorting" onclick="sorting_by('rate', 'desc')">
                <label for="rate-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="rate_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="sub_amt-fa-caret-up" name="sorting" onclick="sorting_by('sub_amt', 'asc')">
                <label for="sub_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="sub_amt_asc"></i>
                </label>

                <span class="text-uppercase">sub amt <br/> <span id="totals_sub_amt"><?php echo $data['totals']['sub_amt']; ?></span></span>
                <input type="radio" class="d-none" id="sub_amt-fa-caret-down" name="sorting" onclick="sorting_by('sub_amt', 'desc')">
                <label for="sub_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="sub_amt_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="disc_amt-fa-caret-up" name="sorting" onclick="sorting_by('disc_amt', 'asc')">
                <label for="disc_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="disc_amt_asc"></i>
                </label>

                <span class="text-uppercase">disc amt <br/> <span id="totals_disc_amt"><?php echo $data['totals']['disc_amt']; ?></span></span>
                <input type="radio" class="d-none" id="disc_amt-fa-caret-down" name="sorting" onclick="sorting_by('disc_amt', 'desc')">
                <label for="disc_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="disc_amt_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="6%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="taxable_amt-fa-caret-up" name="sorting" onclick="sorting_by('taxable_amt', 'asc')">
                <label for="taxable_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="taxable_amt_asc"></i>
                </label>

                <span class="text-uppercase">taxable amt <br/> <span id="totals_taxable_amt"><?php echo $data['totals']['taxable_amt']; ?></span></span>
                <input type="radio" class="d-none" id="taxable_amt-fa-caret-down" name="sorting" onclick="sorting_by('taxable_amt', 'desc')">
                <label for="taxable_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="taxable_amt_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="sgst_amt-fa-caret-up" name="sorting" onclick="sorting_by('sgst_amt', 'asc')">
                <label for="sgst_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="sgst_amt_asc"></i>
                </label>

                <span class="text-uppercase">sgst amt <br/> <span id="totals_sgst_amt"><?php echo $data['totals']['sgst_amt']; ?></span></span>
                <input type="radio" class="d-none" id="sgst_amt-fa-caret-down" name="sorting" onclick="sorting_by('sgst_amt', 'desc')">
                <label for="sgst_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="sgst_amt_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="cgst_amt-fa-caret-up" name="sorting" onclick="sorting_by('cgst_amt', 'asc')">
                <label for="cgst_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="cgst_amt_asc"></i>
                </label>

                <span class="text-uppercase">cgst amt <br/> <span id="totals_cgst_amt"><?php echo $data['totals']['cgst_amt']; ?></span></span>
                <input type="radio" class="d-none" id="cgst_amt-fa-caret-down" name="sorting" onclick="sorting_by('cgst_amt', 'desc')">
                <label for="cgst_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="cgst_amt_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="igst_amt-fa-caret-up" name="sorting" onclick="sorting_by('igst_amt', 'asc')">
                <label for="igst_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="igst_amt_asc"></i>
                </label>

                <span class="text-uppercase">igst amt <br/> <span id="totals_igst_amt"><?php echo $data['totals']['igst_amt']; ?></span></span>
                <input type="radio" class="d-none" id="igst_amt-fa-caret-down" name="sorting" onclick="sorting_by('igst_amt', 'desc')">
                <label for="igst_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="igst_amt_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="total_amt-fa-caret-up" name="sorting" onclick="sorting_by('total_amt', 'asc')">
                <label for="total_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="total_amt_asc"></i>
                </label>

                <span class="text-uppercase">total amt <br/> <span id="totals_total_amt"><?php echo $data['totals']['total_amt']; ?></span></span>
                <input type="radio" class="d-none" id="total_amt-fa-caret-down" name="sorting" onclick="sorting_by('total_amt', 'desc')">
                <label for="total_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="total_amt_desc"></i>
                </label>
            </div>
        </div>
    </th>
</thead>