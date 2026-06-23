<thead>
  <!--   <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="module_name-fa-caret-up" name="sorting" onclick="sorting_data('-module_name')">
                <label for="module_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-module_name"></i>
                </label>

                <span class="text-uppercase">module</span>
                <input type="radio" class="d-none" id="module_name-fa-caret-down" name="sorting" onclick="sorting_data('module_name')">
                <label for="module_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="module_name"></i>
                </label>
            </div>
        </div>
    </th>  --> 
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="type-fa-caret-up" name="sorting" onclick="sorting_data('-type')">
                <label for="type-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-type"></i>
                </label>
                <span class="text-uppercase">Type</span>
                <input type="radio" class="d-none" id="type-fa-caret-down" name="sorting" onclick="sorting_data('type')">
                <label for="type-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="type"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="apparel_name-fa-caret-up" name="sorting" onclick="sorting_data('-apparel_name')" checked="checked">
                <label for="apparel_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-success" id="-apparel_name"></i>
                </label>
                <span class="text-uppercase">apparel</span>
                <input type="radio" class="d-none" id="apparel_name-fa-caret-down" name="sorting" onclick="sorting_data('apparel_name')">
                <label for="apparel_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="apparel_name"></i>
                </label>
            </div>
        </div>
    </th>
   
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="qty-fa-caret-up" name="sorting" onclick="sorting_data('-qty')">
                <label for="qty-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-qty"></i>
                </label>

                <span class="text-uppercase">qty <br/> <span id="totals_qty"><?php echo $data['totals']['qty']; ?></span></span>
                <input type="radio" class="d-none" id="qty-fa-caret-down" name="sorting" onclick="sorting_data('qty')">
                <label for="qty-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="qty"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="rate-fa-caret-up" name="sorting" onclick="sorting_data('-rate')">
                <label for="rate-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-rate"></i>
                </label>
                <span class="text-uppercase">rate</span>
                <input type="radio" class="d-none" id="rate-fa-caret-down" name="sorting" onclick="sorting_data('rate')">
                <label for="rate-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="rate"></i>
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
                <span class="text-uppercase">amt</span>
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
    <th width="5%">
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
                <input type="radio" class="d-none" id="gst_amt-fa-caret-up" name="sorting" onclick="sorting_data('-gst_amt')">
                <label for="gst_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-gst_amt"></i>
                </label>
                <span class="text-uppercase">gst amt <br/> <span id="totals_gst_amt"><?php echo $data['totals']['gst_amt']; ?></span></span>
                <input type="radio" class="d-none" id="gst_amt-fa-caret-down" name="sorting" onclick="sorting_data('gst_amt')">
                <label for="gst_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="gst_amt"></i>
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