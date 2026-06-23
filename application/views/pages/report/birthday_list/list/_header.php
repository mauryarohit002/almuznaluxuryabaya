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
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="customer_birth_date-fa-caret-up" name="sorting" onclick="sorting_data('-customer_birth_date')">
                <label for="customer_birth_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-customer_birth_date"></i>
                </label>

                <span class="text-uppercase">birth date</span>
                <input type="radio" class="d-none" id="customer_birth_date-fa-caret-down" name="sorting" onclick="sorting_data('customer_birth_date')">
                <label for="customer_birth_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="customer_birth_date"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="customer_mobile-fa-caret-up" name="sorting" onclick="sorting_data('-customer_mobile')">
                <label for="customer_mobile-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-customer_mobile"></i>
                </label>

                <span class="text-uppercase">mobile no</span>
                <input type="radio" class="d-none" id="customer_mobile-fa-caret-down" name="sorting" onclick="sorting_data('customer_mobile')">
                <label for="customer_mobile-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="customer_mobile"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="10%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="customer_email-fa-caret-up" name="sorting" onclick="sorting_data('-customer_email')">
                <label for="customer_email-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-customer_email"></i>
                </label>

                <span class="text-uppercase">email</span>
                <input type="radio" class="d-none" id="customer_email-fa-caret-down" name="sorting" onclick="sorting_data('customer_email')">
                <label for="customer_email-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="customer_email"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="15%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="customer_address-fa-caret-up" name="sorting" onclick="sorting_data('-customer_address')">
                <label for="customer_address-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-customer_address"></i>
                </label>

                <span class="text-uppercase">address</span>
                <input type="radio" class="d-none" id="customer_address-fa-caret-down" name="sorting" onclick="sorting_data('customer_address')">
                <label for="customer_address-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="customer_address"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="8%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="city_name-fa-caret-up" name="sorting" onclick="sorting_data('-city_name')">
                <label for="city_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-city_name"></i>
                </label>

                <span class="text-uppercase">city</span>
                <input type="radio" class="d-none" id="city_name-fa-caret-down" name="sorting" onclick="sorting_data('city_name')">
                <label for="city_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="city_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="8%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="state_name-fa-caret-up" name="sorting" onclick="sorting_data('-state_name')">
                <label for="state_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-state_name"></i>
                </label>

                <span class="text-uppercase">state</span>
                <input type="radio" class="d-none" id="state_name-fa-caret-down" name="sorting" onclick="sorting_data('state_name')">
                <label for="state_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="state_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="8%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="country_name-fa-caret-up" name="sorting" onclick="sorting_data('-country_name')">
                <label for="country_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-country_name"></i>
                </label>

                <span class="text-uppercase">country</span>
                <input type="radio" class="d-none" id="country_name-fa-caret-down" name="sorting" onclick="sorting_data('country_name')">
                <label for="country_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="country_name"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="customer_pincode-fa-caret-up" name="sorting" onclick="sorting_data('-customer_pincode')">
                <label for="customer_pincode-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="-customer_pincode"></i>
                </label>

                <span class="text-uppercase">pincode</span>
                <input type="radio" class="d-none" id="customer_pincode-fa-caret-down" name="sorting" onclick="sorting_data('customer_pincode')">
                <label for="customer_pincode-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="customer_pincode"></i>
                </label>
            </div>
        </div>
    </th>
</thead>