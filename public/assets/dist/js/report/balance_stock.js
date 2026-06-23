$(document).ready(function(){
    $("#brmm_supplier_id").select2(select2_default({
        url:`master/supplier/get_select2/_name`,
        placeholder:'SUPPLIER',
    })).on('change', () => trigger_search());
    $("#brmm_sku_id").select2(select2_default({
        url:`master/sku/get_select2/_name`,
        placeholder:'SKU',
    })).on('change', () => trigger_search());
    $("#brmm_apparel_id").select2(select2_default({
        url:`master/apparel/get_select2/_name`,
        placeholder:'APPAREL',
    })).on('change', () => trigger_search());
    $("#brmm_branch_id").select2(select2_default({
        url:`master/branch/get_select2/_name`,
        placeholder:'BRANCH',
    })).on('change', () => trigger_search());
});