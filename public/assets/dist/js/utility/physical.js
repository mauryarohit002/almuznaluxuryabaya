$(document).ready(function () {
	$("#_entry_no")
		.select2(
			select2_default({
				url: `utility/physical/get_select2/_entry_no`,
				placeholder: "ENTRY NO",
			})
		)
		.on("change", () => trigger_search());
	$("#bm_id")
		.select2(
			select2_default({
				url: `utility/physical/get_select2/_bm_id`,
				placeholder: "SCAN",
				barcode: "bm_id",
			})
		)
		.on("change", (event) => get_barcode_data(event.target.value));

	if ($("#psm_id").length && $("#psm_id").val() != 0) {
		get_transaction($("#psm_id").val());
	}
});
// core_functions
// const get_url_string = (key) => {
// 	const params = new Proxy(new URLSearchParams(window.location.search), {
// 		get: (searchParams, props) => searchParams.get(props),
// 	});
// 	return params[`${key}`];
// };
const get_transaction = (id) => {
	if (["edit", "view"].includes(get_url_string("action"))) {
		let id = get_url_string("id");
		if (id) {
			let path = `utility/physical/get_transaction/${id}`;
			ajaxCall(
				"GET",
				path,
				"",
				"JSON",
				(resp) => {
					if (handle_response(resp)) {
						const { data, msg } = resp;
						if (data && data.length != 0) {
							data.forEach((row) => add_scan_row(row));
						}
					}
				},
				(errmsg) => {}
			);
		}
	}
};
const add_update = (id) => {
	event.preventDefault();
	remove_physical_master_notifier();
	let check = true;
	let cnf = true;
	let total_tr = $("#scan_barcode_wrapper tr").length;
	if ($("#psm_entry_no").val() == "") {
		notifier("psm_entry_no", "Required");
		check = false;
	}
	if ($("#psm_entry_date").val() == "") {
		notifier("psm_entry_date", "Required");
		check = false;
	}
	if ($("#psm_scan_qty").val() <= 0) {
		notifier("psm_scan_qty", "Required");
		check = false;
	}
	if ($("#psm_scan_qty").val() > 0 && $("#psm_unscan_qty").val() > 0) {
		if (
			!confirm(
				`${$(
					"#psm_unscan_qty"
				).val()} Unscan qty will be consider as missing stock. Do you want to continue?`
			)
		) {
			cnf = false;
		}
	}
	if (!check) {
		toastr.error("You forgot to enter some information.", "Oh snap!!!", {
			closeButton: true,
			progressBar: true,
			preventDuplicates: true,
		});
		$("body, html").animate({ scrollTop: 0 }, 1000);
	} else if (!cnf) {
	} else {
		let path = `utility/physical/add_update/${id}`;
		let form_data = $("#physical_form").serialize();
		ajaxCall(
			"POST",
			path,
			form_data,
			"JSON",
			(resp) => {
				if (handle_response(resp)) {
					const { data, msg } = resp;
					if (id == 0) {
						window.location.href = `${base_url}/utility/physical?action=view`;
					} else {
					}
					remove_physical_master_notifier();
					toastr.success("", msg, {
						closeButton: true,
						progressBar: true,
						preventDuplicates: true,
					});
					$("body, html").animate({ scrollTop: 0 }, 1000);
					setTimeout(function () {
						window.location.reload();
					}, RELOAD_TIME);
				}
			},
			(errmsg) => {}
		);
	}
};
const remove_record = (data) => {
	let path = `utility/physical/remove/${data.psm_id}`;
	let html = `<table class="table table-sm table-hover" style="font-size:0.8rem;">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">ENTRY NO : </td>
                            <td width="70%">${data.psm_entry_no}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">ENTRY DATE : </td>
                            <td width="70%">
                            	${data.psm_entry_date.split("-").reverse().join("-")}
                        	</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">SCAN QTY : </td>
                            <td width="70%">${data.psm_scan_qty}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">UNSCAN QTY : </td>
                            <td width="70%">${data.psm_unscan_qty}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">SCAN AMT : </td>
                            <td class="font-weight-bold" width="70%">
                            	${data.psm_scan_amt}
                        	</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">UNSCAN AMT : </td>
                            <td class="font-weight-bold" width="70%">
                            	${data.psm_unscan_amt}
                        	</td>
                        </tr>
                    </tbody>
                </table>`;
	remove_datav2(html, path);
};
const remove_physical_master_notifier = () => {
	notifier("psm_entry_no");
	notifier("psm_entry_date");
	notifier("psm_scan_qty");
};
// core_functions

// additional_functions
const initiate_process = () => {
	let path = `utility/physical/initiate_process`;
	ajaxCall(
		"GET",
		path,
		"",
		"JSON",
		(resp) => {
			if (handle_response(resp)) {
				const { data, msg } = resp;
				if (data && data.length != 0) {
					window.location.href = `${base_url}/utility/physical?action=edit&id=${data["id"]}`;
				}
			}
		},
		(errmsg) => {}
	);
};
const get_barcode_data = (bm_id) => {
	let psm_id = $("#psm_id").val();
	if (bm_id) {
		let path = `utility/physical/get_barcode_data/${psm_id}/${bm_id}`;
		ajaxCall(
			"GET",
			path,
			"",
			"JSON",
			(resp) => {
				if (handle_response(resp)) {
					const { data, msg } = resp;
					if (data && data.length != 0) {
						// add_scan_row({
						//     psst_id          : data[0]['psst_id'],
						//     bm_item_code    : data[0]['bm_item_code'],
						//     gender_name       : data[0]['gender_name'],
						//     category_name   : data[0]['category_name'],
						//     color_name      : data[0]['color_name'],
						//     psst_size        : data[0]['size_name'],
						//     psst_rate        : data[0]['rate'],
						//     isExist         : false,
						// });
						$("#scan_barcode_wrapper").html("");
						get_transaction(psm_id);
						toastr.success(
							`${data[0]['brmm_item_code']}`,
							`${msg}`,
							{
								closeButton: true,
								progressBar: true,
								preventDuplicates: true,
							}
						);
						$("#psm_scan_qty").val(data[0]["scan_qty"]);
						$("#psm_unscan_qty").val(data[0]["unscan_qty"]);

						// if ($("#scan_barcode_wrapper tr").length > 20) {
							$("#scan_barcode_wrapper").find("tr:last-child").remove();
						// }
					}
				}
				setTimeout(() => {
					$("#bm_id").val(null).trigger("change");
					$("#bm_id").select2("open");
				}, 500);
			},
			(errmsg) => {
				setTimeout(() => {
					$("#bm_id").val(null).trigger("change");
					$("#bm_id").select2("open");
				}, 500);
			}
		);
	}
};
const add_scan_row = (data) => {
	let {
		psst_id,
		brmm_item_code,
		brmm_description,
		apparel_name,
		sku_name,
		supplier_name,
		psst_rate,
		psst_qty,
		isExist,
	} = data;
	let tr = `<tr id="scanid_${psst_id}">
                <td width="15%">${brmm_item_code}</td>
                <td width="20%">${brmm_description}</td>
                <td width="10%">${sku_name}</td>
                <td width="10%">${apparel_name}</td>
                <td width="10%">${supplier_name}</td>
                <td width="5%">${psst_rate}</td>
                <td width="5%">${psst_qty}</td>
                <td width="5%">${
									isExist
										? `<button 
                                type="button" 
                                class="btn btn-sm btn-primary" 
                            ><i class="text-danger fa fa-ban"></i></button>`
										: `<button 
                                type="button" 
                                class="btn btn-sm btn-primary" 
                                onclick="remove_barcode(${psst_id})"
                            ><i class="text-danger fa fa-trash"></i></button>`
								}
                </td>
            </tr>`;
	$("#scan_barcode_wrapper").prepend(tr);
};
const remove_barcode = (psst_id) => {
	let psm_id = $("#psm_id").val();
	if (psst_id) {
		let path = `utility/physical/remove_barcode/${psm_id}/${psst_id}`;
		ajaxCall(
			"GET",
			path,
			"",
			"JSON",
			(resp) => {
				if (handle_response(resp)) {
					const { data, msg } = resp;
					if (data && data.length != 0) {
						$("#scan_barcode_wrapper").html("");
						get_transaction(psm_id);
						// $(`#scanid_${psst_id}`).detach();
						toastr.success(
							`${data[0]["bm_item_code"]}`,
							'Barcode removed from the list',
							{
								closeButton: true,
								progressBar: true,
								preventDuplicates: true,
							}
						);
						$("#psm_scan_qty").val(data[0]["scan_qty"]);
						$("#psm_unscan_qty").val(data[0]["unscan_qty"]);
						if (data[0]["scan_qty"] > 0) {
							$(".master_block_btn").prop("disabled", false);
						} else {
							$(".master_block_btn").prop("disabled", true);
						}
					}
				}
			},
			(errmsg) => {}
		);
	}
};
const search_scan_barcode = (barcode) => {
	let len = barcode.length;
	let total_tr = $("#scan_barcode_wrapper tr").length;
	notifier("search_scan");
	if (total_tr > 0 && len === 12) {
		let last_id = 0;
		for (let i = 1; i <= total_tr; i++) {
			let cnt = $(`#scan_barcode_wrapper tr:nth-child(${i})`).attr("id");
			let explode = cnt.split("_");
			let id = explode[1];

			let scan_barcode = $(`#psst_item_code_${id}`).val();
			if (barcode == scan_barcode) {
				last_id = id;
				$(`#scanid_${id}`).addClass("text-success");
			} else {
				$(`#scanid_${id}`).removeClass("text-success");
			}
		}
		if (last_id != 0) {
			$("#scan_barcode_wrapper").animate(
				{
					scrollTop: $(`#scanid_${last_id}`).offset().top - 500,
				},
				2000
			);
		} else {
			notifier("search_scan", "Barcode not found in scan list.");
		}
	} else {
		$(`#scan_barcode_wrapper tr`).removeClass("text-success");
	}
};
// additional_functions
