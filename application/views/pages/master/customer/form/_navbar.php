<?php 
    $tab = '<div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <ul class="nav nav-pills nav-fill nav-pills-primary" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a 
                            class="nav-link active text-uppercase" 
                            id="general_tab" 
                            data-toggle="tab"
                            href="#general_content" 
                            role="tab" 
                            aria-controls="general_content" 
                            aria-selected="true"
                        >general detail</a>
                    </li>
                    <li class="nav-item">
                        <a 
                            class="nav-link text-uppercase" 
                            id="measurement_tab" 
                            data-toggle="tab"
                            href="#measurement_content" 
                            role="tab" 
                            aria-controls="measurement_content" 
                            aria-selected="false"
                        >measurement</a>
                    </li>
                </ul>
            </div>';
    $this->load->view('pages/component/form/_navbar', ['tab' => $tab]); 
?>