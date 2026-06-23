<?php $this->load->view('pages/component/_form'); ?>

<div class="modal fade" id="skuModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h5 class="modal-title text-uppercase">Add New SKU</h5>
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <!-- ✅ ADD FORM HERE -->
            <form id="skuForm">

                <div class="modal-body">
                    <div id="skuFormContainer"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-success" onclick="saveSku()">
                        Save SKU
                    </button>
                </div>

            </form>
            <!-- ✅ END FORM -->

        </div>
    </div>
</div>

