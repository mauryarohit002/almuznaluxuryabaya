<thead>
    <th width="3%">#</th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="entry_no-fa-caret-up" name="sorting" onclick="sorting_data('-entry_no')" checked="checked">
                <label for="entry_no-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-success" id="-entry_no"></i>
                </label>

                <span class="text-uppercase">issue no</span>
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

                <span class="text-uppercase">issue date</span>
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
                <input type="radio" class="d-none" id="order_no-fa-caret-up" name="sorting" onclick="sorting_data('-order_no')">
                <label for="order_no-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-order_no"></i>
                </label>

                <span class="text-uppercase">order no</span>
                <input type="radio" class="d-none" id="order_no-fa-caret-down" name="sorting" onclick="sorting_data('order_no')" >
                <label for="order_no-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="order_no"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="order_date-fa-caret-up" name="sorting" onclick="sorting_data('-order_date')">
                <label for="order_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-order_date"></i>
                </label>

                <span class="text-uppercase">order date</span>
                <input type="radio" class="d-none" id="order_date-fa-caret-down" name="sorting" onclick="sorting_data('order_date')">
                <label for="order_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="order_date"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="obt_item_code-fa-caret-up" name="sorting" onclick="sorting_data('-obt_item_code')">
                <label for="obt_item_code-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-obt_item_code"></i>
                </label>

                <span class="text-uppercase">Barcode</span>
                <input type="radio" class="d-none" id="obt_item_code-fa-caret-down" name="sorting" onclick="sorting_data('obt_item_code')">
                <label for="obt_item_code-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="obt_item_code"></i>
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
                <input type="radio" class="d-none" id="proces_name-fa-caret-up" name="sorting" onclick="sorting_data('-proces_name')">
                <label for="proces_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-proces_name"></i>
                </label>

                <span class="text-uppercase">process</span>
                <input type="radio" class="d-none" id="proces_name-fa-caret-down" name="sorting" onclick="sorting_data('proces_name')">
                <label for="proces_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="proces_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="8%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="karigar_name-fa-caret-up" name="sorting" onclick="sorting_data('-karigar_name')">
                <label for="karigar_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-karigar_name"></i>
                </label>

                <span class="text-uppercase">karigar</span>
                <input type="radio" class="d-none" id="karigar_name-fa-caret-down" name="sorting" onclick="sorting_data('karigar_name')">
                <label for="karigar_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="karigar_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="8%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="apparel_name-fa-caret-up" name="sorting" onclick="sorting_data('-apparel_name')">
                <label for="apparel_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-apparel_name"></i>
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
                <input type="radio" class="d-none" id="job_status-fa-caret-up" name="sorting" onclick="sorting_data('-job_status')">
                <label for="job_status-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-job_status"></i>
                </label>

                <span class="text-uppercase">status</span>
                <input type="radio" class="d-none" id="job_status-fa-caret-down" name="sorting" onclick="sorting_data('job_status')">
                <label for="job_status-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="job_status"></i>
                </label>
            </div>
        </div>
    </th>
</thead>