<thead>
    <th width="3%">#</th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="fabric_name-fa-caret-up" name="sorting" onclick="sorting_data('-fabric_name')">
                <label for="fabric_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-fabric_name"></i>
                </label>
                <span class="text-uppercase">fabric</span>
                <input type="radio" class="d-none" id="fabric_name-fa-caret-down" name="sorting" onclick="sorting_data('fabric_name')">
                <label for="fabric_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="fabric_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="design_name-fa-caret-up" name="sorting" onclick="sorting_data('-design_name')">
                <label for="design_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-design_name"></i>
                </label>

                <span class="text-uppercase">fabric code</span>
                <input type="radio" class="d-none" id="design_name-fa-caret-down" name="sorting" onclick="sorting_data('design_name')">
                <label for="design_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="design_name"></i>
                </label>
            </div>
        </div>
    </th>
     <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="category_name-fa-caret-up" name="sorting" onclick="sorting_data('-category_name')">
                <label for="category_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-category_name"></i>
                </label>

                <span class="text-uppercase">category</span>
                <input type="radio" class="d-none" id="category_name-fa-caret-down" name="sorting" onclick="sorting_data('category_name')">
                <label for="category_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="category_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="color_name-fa-caret-up" name="sorting" onclick="sorting_data('-color_name')">
                <label for="color_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-color_name"></i>
                </label>

                <span class="text-uppercase">color</span>
                <input type="radio" class="d-none" id="color_name-fa-caret-down" name="sorting" onclick="sorting_data('color_name')">
                <label for="color_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="color_name"></i>
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
                <input type="radio" class="d-none" id="mrp-fa-caret-up" name="sorting" onclick="sorting_data('-mrp')">
                <label for="mrp-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-mrp"></i>
                </label>

                <span class="text-uppercase">mrp</span>
                <input type="radio" class="d-none" id="mrp-fa-caret-down" name="sorting" onclick="sorting_data('mrp')">
                <label for="mrp-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="mrp"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="pt_mtr-fa-caret-up" name="sorting" onclick="sorting_data('-pt_mtr')">
                <label for="pt_mtr-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-pt_mtr"></i>
                </label>

                <span class="text-uppercase">pur qty <br/> <span id="totals_pt_mtr"><?php echo $data['totals']['pt_mtr']; ?></span></span>
                <input type="radio" class="d-none" id="pt_mtr-fa-caret-down" name="sorting" onclick="sorting_data('pt_mtr')">
                <label for="pt_mtr-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="pt_mtr"></i>
                </label>
            </div>
        </div>
    </th>
     <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="prt_mtr-fa-caret-up" name="sorting" onclick="sorting_data('-prt_mtr')">
                <label for="prt_mtr-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-prt_mtr"></i>
                </label>
                <span class="text-uppercase">purRet qty <br/> <span id="totals_prt_mtr"><?php echo $data['totals']['prt_mtr']; ?></span></span>
                <input type="radio" class="d-none" id="prt_mtr-fa-caret-down" name="sorting" onclick="sorting_data('prt_mtr')">
                <label for="prt_mtr-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="prt_mtr"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="ot_mtr-fa-caret-up" name="sorting" onclick="sorting_data('-ot_mtr')">
                <label for="ot_mtr-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-ot_mtr"></i>
                </label>
                <span class="text-uppercase">order qty <br/> <span id="totals_ot_mtr"><?php echo $data['totals']['ot_mtr']; ?></span></span>
                <input type="radio" class="d-none" id="ot_mtr-fa-caret-down" name="sorting" onclick="sorting_data('ot_mtr')">
                <label for="ot_mtr-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="ot_mtr"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="bal_mtr-fa-caret-up" name="sorting" onclick="sorting_data('-bal_mtr')" checked="checked">
                <label for="bal_mtr-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-success" id="-bal_mtr"></i>
                </label>

                <span class="text-uppercase">bal qty <br/> <span id="totals_bal_mtr"><?php echo $data['totals']['bal_mtr']; ?></span></span>
                <input type="radio" class="d-none" id="bal_mtr-fa-caret-down" name="sorting" onclick="sorting_data('bal_mtr')">
                <label for="bal_mtr-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="bal_mtr"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="bal_amt-fa-caret-up" name="sorting" onclick="sorting_data('-bal_amt')">
                <label for="bal_amt-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-bal_amt"></i>
                </label>
                <span class="text-uppercase">purchase value<br/> <span id="totals_bal_amt"><?php echo $data['totals']['bal_amt']; ?></span></span>
                <input type="radio" class="d-none" id="bal_amt-fa-caret-down" name="sorting" onclick="sorting_data('bal_amt')">
                <label for="bal_amt-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="bal_amt"></i>
                </label>
            </div>
        </div>
    </th>
     <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="bal_mrp-fa-caret-up" name="sorting" onclick="sorting_data('-bal_mrp')">
                <label for="bal_mrp-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-bal_mrp"></i>
                </label>
                <span class="text-uppercase">mrp value<br/> <span id="totals_bal_mrp"><?php echo $data['totals']['bal_mrp']; ?></span></span>
                <input type="radio" class="d-none" id="bal_mrp-fa-caret-down" name="sorting" onclick="sorting_data('bal_mrp')">
                <label for="bal_mrp-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="bal_mrp"></i>
                </label>
            </div>
        </div>
    </th>
</thead>