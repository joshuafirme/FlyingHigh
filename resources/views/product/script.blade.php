<script>
    $(function() {
        "use strict";
        const bundles_choices = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
            placeholderValue: 'Choose SKU',
        });

        const el_choices_multi_sku = document.getElementById('choices-multiple-sku');
        const multi_sku = new Choices(el_choices_multi_sku, {
            removeItemButton: true,
            placeholderValue: 'Choose SKU',
            delimiter: ',',
            editItems: true,
        });

        el_choices_multi_sku.addEventListener(
            'addItem',
            function(event) {
                let sku = event.detail.value
                fetch("/api/get-qty/" + sku)
                    .then(data => data.json())
                    .then(maxQty => {
                        console.log(maxQty)
                        var html = '<div class="col-12 mt-3 input-' + event.detail
                            .value + '">';
                        html += '<p class="col-form-label label-' + sku +
                            '">' + event.detail.label + '</p> <small>Current stock: ' +
                            maxQty +
                            '</small>';
                        html +=
                            '<input type="number" min="1" max="' + maxQty +
                            '" class="form-control choices-text-remove-button" name="qty[]" type="text" required placeholder="Enter quantity">';
                        html += '<input type="hidden" name="sku[]" value="' + sku + '"></div>';
                        $('#multi-sku-input').append(html);
                    })

            },
            false,
        );

        el_choices_multi_sku.addEventListener(
            'removeItem',
            function(event) {
                $('.input-' + event.detail.value).remove();
            },
            false,
        );

        async function initChoices(element, bundles = []) {

            if ($('#' + element).length > 0) {
                if (element == 'choices-multiple-sku') {
                    setChoices(multi_sku, bundles);
                } else {
                    setChoices(bundles_choices, bundles);
                }
            }
        }

        function setChoices(object, bundles) {

            object.setChoices(function() {
                return fetch(
                        '/api/get-all-sku'
                    )
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        return data.map(function(v) {
                            let is_selected = false;
                            if (bundles != null && bundles.indexOf(v
                                    .sku) != -1) {
                                is_selected = true;
                            }
                            return {
                                label: v.sku + ' | ' + v.description,
                                value: v.sku,
                                selected: is_selected
                            };
                        });
                    });
            })
        }

        function clearInputs() {
            let modal = $('#postModal');
            let inputs = modal.find('input,select');
            $('.bundle-choices').addClass('d-none');
            $.each(inputs, function(i, v) {
                if (i > 1) {
                    if ($(v).attr('type') == 'checkbox') {
                        return;
                    }
                    $(v).val('');
                }
            });

        }

        $('.open-modal').click(function(event) {

            let modal = $('#postModal');
            modal.modal({
                backdrop: 'static',
                keyboard: false
            })
            modal.modal('show');
            let modal_type = $(this).attr('modal-type');
            clearInputs();
            if (modal_type == 'create') {
                $('[name=status]').val(1);
                modal.find('.modal-title').text('Create Product');
                modal.find('form').attr('action', "{{ route('product.store') }}");
                initChoices('choices-multiple-remove-button');
            } else {
                bundles_choices.clearStore();
                modal.find('.modal-title').text('Update Product');
                let data = JSON.parse($(this).attr('data-info'));
                modal.find('form').attr('action', "/product/update/" + data.id);
                initChoices('choices-multiple-remove-button', data.bundles);
                console.log(data)
                for (var key of Object.keys(data)) {
                    if (key == 'expiration') {
                        data[key] = data[key] ? data[key].substring(0, 10) : '';
                    }
                    if (key == 'has_bundle') {
                        if (data[key] == 1) {
                            modal.find('[name=' + key + ']').prop('checked', true);
                        } else {
                            modal.find('[name=' + key + ']').prop('checked', false);
                        }
                        if ($('[name=' + key + ']').is(':checked')) {
                            $('.bundle-choices').removeClass('d-none');
                        } else {
                            $('.bundle-choices').addClass('d-none');
                        }
                        continue;
                    }
                    if (key == 'qty') {
                        modal.find('[name=' + key + ']').prop('readonly', true);
                    }
                    modal.find('[name=' + key + ']').val(data[key]);
                }
            }
        });

        $('.btn-transfer').click(function() {
            let v = $(this);
            let sku = v.attr('data-sku');
            let description = v.attr('data-desc');
            let tm = $('#transferModal');
            tm.find('[name=sku]').val(sku);
            tm.find('[name=description]').val(description);
        });

        $('.btn-stock-adjustment').click(function() {
            let v = $(this);
            let sku = v.attr('data-sku');
            let description = v.attr('data-desc');
            let mdl = $('#stockAdjustmentModal');
            mdl.find('[name=sku]').val(sku);
            mdl.find('[name=description]').val(description);
            mdl.find('[name=qty]').attr('max', qty);
        });

        $('.btn-bulk-transfer').click(function() {
            multi_sku.clearStore();
            $('#multi-sku-input').html('');
            initChoices('choices-multiple-sku');
        });

        $('#bulk-transfer-form').submit(function(event) {
            let btn = $('#btn-bulk-transfer');
            Swal.fire({
                title: 'Are you sure?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.html("Please wait...");
                    $.ajax({
                            type: 'POST',
                            url: "{{ url('/product/bulk-transfer') }}",
                            data: $(this).serialize()
                        })

                        .done(function(data) {

                            if (data.success && data.message == 'transfer_success') {
                                swalSuccess('Products was transferred!');
                                setTimeout(function() {
                                    location.reload();
                                }, 2800);
                            } else if (data.message == 'not_enough_stock') {
                                swalError('Some of stocks are changed, please try again.');
                            }
                            btn.html("Transfer");
                        })
                        .fail(function() {
                            swalError('Error occured, please contact dev team.');
                            btn.html("Transfer");
                        });
                }
            })

            return false;
        });

        $('#stock-adjustment-form').submit(function(event) {
            Swal.fire({
                title: 'Are you sure?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btn-adjust').html("Please wait...");
                    $.ajax({
                            type: 'POST',
                            url: "{{ url('/product/adjust') }}",
                            data: $(this).serialize()
                        })

                        .done(function(data) {

                            if (data.message == 'success') {
                                swalSuccess('Product stock was adjusted!');
                                setTimeout(function() {
                                    location.reload();
                                }, 2500);
                            } else {
                                swalError('Error occured, please contact dev team.');
                            }
                            $('#btn-adjust').html("Adjust");
                        })
                        .fail(function() {
                            swalError('Error occured, please contact dev team.');
                            $('#btn-adjust').html("Adjust");
                        });
                }
            })

            return false;
        });

        $('#transfer-form').submit(function(event) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btn-transfer').html("Please wait...");
                    $.ajax({
                            type: 'POST',
                            url: "{{ url('/product/transfer') }}",
                            data: $(this).serialize()
                        })

                        .done(function(data) {

                            if (data.success && data.message == 'transfer_success') {
                                swalSuccess('Transfer success!')
                            } else {
                                swalError('Not enough stock!');
                            }
                            $('#btn-transfer').html("Transfer");
                        })
                        .fail(function() {
                            alert("Posting failed. Please try again.");
                            $('#btn-transfer').html("Transfer");
                        });
                }
            })

            return false;
        });

        $('#btn-fetch').click(function(e) {
            let btn = $(this);
            let api_endpoint = $('[name=api_endpoint]').val();
            e.preventDefault();
            btn.attr("disabled", true);
            btn.html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Fetching...'
            );

            $('#orders-container').html('');

            fetch(api_endpoint)
                .then(data => data.json())
                .then(data => {
                    $('#transactionReferenceNumber').text('Transaction Reference Number: ' + data
                        .transactionReferenceNumber);

                    let tbl_html = '';

                    let orders = data.purchaseOrderReceiptHeaders;

                    for (let item of orders) {
                        tbl_html += '<b class="mt-2">Order Number: ' + item.orderNumber + '</b>';
                        tbl_html += '<table class="table table-hover mt-1">';
                        tbl_html += '<thead>';
                        tbl_html += '   <tr>';
                        tbl_html += '        <th scope="col">Line Number</th>';
                        tbl_html += '       <th scope="col">Item Number</th>';
                        tbl_html += '        <th scope="col">Qty Rcd Good</th>';
                        tbl_html += '        <th scope="col">Qty Rcd Bad</th>';
                        tbl_html += '        <th scope="col">Bill of Lading</th>';
                        tbl_html += '        <th scope="col">Unit Of Measure</th>';
                        tbl_html += '        <th scope="col">Location</th>';
                        tbl_html += '        <th scope="col">Lot Number</th>';
                        tbl_html += '        <th scope="col">Lot Expiration</th>';
                        tbl_html += '    </tr>';
                        tbl_html += '</thead>';
                        tbl_html += '<tbody id="tbl-' + item.orderNumber + '">';

                        tbl_html += '</tbody>';
                        tbl_html += '</table>';
                        $('#orders-container').append(tbl_html);

                        $.each(item.purchaseOrderReceiptDetails, function(index, v) {

                            setTimeout(() => {
                                let html = '<tr>';
                                html += '<td>' + v.lineNumber + '</td>';
                                html += '<td>' + v.itemNumber + '</td>';
                                html += '<td>' + v.qtyRcdGood + '</td>';
                                html += '<td>' + v.qtyRcdBad + '</td>';
                                html += '<td>' + v.billOfLading + '</td>';
                                html += '<td>' + v.unitOfMeasure + '</td>';
                                html += '<td>' + v.location + '</td>';
                                html += '<td>' + v.lotNumber + '</td>';
                                html += '<td>' + v.lotExpiration + '</td>';
                                html += '<td></td>';
                                html += '</tr>';
                                $('#tbl-' + item.orderNumber).append(html);
                                if (index + 1 == item.purchaseOrderReceiptDetails
                                    .length) {
                                    btn.html('Fetch');
                                    btn.attr("disabled", false);
                                }
                            }, 300 * index);

                        });
                    }

                });

            return false;
        });


        $('#has_bundle').click(function(e) {
            if ($(this).is(':checked')) {
                $('.bundle-choices').removeClass('d-none');
            } else {
                $('.bundle-choices').addClass('d-none');
            }
        });

        $('#import-via-api-form').submit(function(e) {
            $('#btn-api-import').html('Importing...');
            let api_endpoint = $('[name=api_endpoint]').val();
            $.ajax({
                    type: 'GET',
                    url: '/product/api/import',
                    data: $(this).serialize()
                })

                .done(function(data) {
                    console.log(data)
                    if (data.message == 'transaction_success') {
                        swalSuccess('Transaction success!')
                    } else {
                        swalError('Transaction reference number ' + data
                            .transactionReferenceNumber + ' is already exists.');
                    }
                    $('#btn-api-import').html("Import");
                })
                .fail(function() {
                    swalError('Importing failed, please try again or contact support.');
                    $('#btn-api-import').html("Import");
                });

            return false;
        });

        function swalSuccess(message) {
            Swal.fire(
                message,
                '',
                'success'
            );
        }

        function swalError(text) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: text,
            })
        }

    });
</script>
