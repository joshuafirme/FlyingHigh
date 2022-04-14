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
        var counter = 1;
        el_choices_multi_sku.addEventListener(
            'addItem',
            function(event) {
                let sku = event.detail.value;
                fetch("/api/product/sku/" + sku)
                    .then(data => data.json())
                    .then(data => {
                        console.log(data)
                        appendInputs(data);
                    })

            },
            false,
        );

        function appendInputs(data) {
            var html = '';
            html += '<tr id="' + data.id + '_' + data.sku + '">';
            html += '<td>';
            html += data.description;
            html += '<input type="hidden" class="form-control" name="sku[]" value="' + data.sku +
                '">';
            html += '</td>';
            html += '<td>' + data.qty + '</td>';
            html += '<td>';
            html +=
                '<input type="number" class="form-control" name="qty[]" required max="' +
                data.qty + '" min="1">';
            html += '</td>';

            html += '<td>';
            html += '<select class="form-control" name="hub_id[]" required>';
            html += '<option selected disabled value="">Choose Hub...</option>';
            html += '@foreach ($hubs as $item)';
                html += ' <option value="{{ $item->id }}">{{ $item->name }}</option>';
            html += '@endforeach ';
            html += '</select>';
            html += '</td>';
            html += '<td><a class="btn btn-remove-sku" data-id="' + data.id + '_' + data.sku + '"><i class="fa fa-trash"></i></a></td>';
            html += '</tr>';
            $('#inputs-container').append(html);
        }

        el_choices_multi_sku.addEventListener(
            'removeItem',
            function(event) {
                // $('#' + event.detail.id +'_' + event.detail.value).remove();
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

        function getBundleQty(sku) {
            fetch("/api/product/bundle-qty-list/" + sku)
                .then(data => data.json())
                .then(result => {
                    $('#tbl-bundle-qty').html('');
                    console.log(result)
                    if (result.data.length > 0) {
                        for (let item of result.data) {
                            let html = '<tr>';
                            html += '<td><a target="_blank" href="/product/search?key='+item.sku+'">'+item.sku+' | '+item.description+'</a></td>';
                            html += '<td>'+item.qty+'</td>';
                            html += '<td><a target="_blank" href="/product/search?key='+item.sku+'"><i class="fa fa-eye"></i></a></td>';
                            html += '</tr>';
                            $('#tbl-bundle-qty').append(html);
                        }
                    }
                    else {
                        let html = '<tr>';
                        html += '<td colspan="2"><div class="alert alert-primary">No data found.</div></td>';
                        html += '</tr>';
                        $('#tbl-bundle-qty').append(html);
                    }
                })
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
                $('#bundle-qty-container').addClass('d-none');
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
                            $('#choices-multiple-remove-button').prop('required', true);
                            $('#bundle-qty-container').removeClass('d-none');
                            getBundleQty(data.sku);
                        } else {
                            modal.find('[name=' + key + ']').prop('checked', false);
                            $('#choices-multiple-remove-button').prop('required', false);
                            $('#bundle-qty-container').addClass('d-none');
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

        $(document).on('click', '.btn-hubs-stock', function() {
            let sku = $(this).attr('data-sku');
            let desc = $(this).attr('data-desc');
            $('#tbl-hubs-stock').html('');
            $('#sku-text').html(sku); 
            $('#description-text').html(desc); 
            
            fetch("/product/hubs/" + sku)
                .then(data => data.json())
                .then(data => {
                    if (data.length > 0) {
                        for (let item of data) {
                            let html = '<tr>';
                            html += '<td>'+item.hub+'</td>';
                            html += '<td>'+item.stock+'</td>';
                            html += '</tr>';
                            $('#tbl-hubs-stock').append(html);
                        }
                    }
                    else {
                        let html = '<tr>';
                        html += '<td colspan="2"><div class="alert alert-primary">No data found.</div></td>';
                        html += '</tr>';
                        $('#tbl-hubs-stock').append(html);
                    }
                });
        });

        $(document).on('click', '.btn-remove-sku', function() {
            let v = $(this);
            let id = v.attr('data-id');
            $('#' + id).remove();
        });

        $(document).on('keyup', '#barcode-scan', function(e) { 
            let barcode = $(this).val();
            console.log(e.keyCode)
            if (e.keyCode == 86 || e.keyCode == 8) {
                fetch("/api/product/barcode/" + barcode)
                    .then(data => data.json())
                    .then(data => {
                        console.log(data)
                        if (data.sku) {
                            $(this).val('')
                            appendInputs(data);
                        }
                    });
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
        });

        $('.btn-bulk-transfer').click(function() {
            multi_sku.clearStore();
            $('#inputs-container').html('');
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
                                let html = 'Some of stocks are not enough, please click the SKU below to see the available stock.<br>';
                                console.log(data.sku_list)
                                for (let sku of data.sku_list) {
                                    html += '<a target="_blank" href="#">'+sku+'</a><br>';
                                }
                                swalError(html);
                            }
                            else if (data.message == 'not_enough_bundle'){

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
                $('#choices-multiple-remove-button').prop('required', true);
            } else {
                $('.bundle-choices').addClass('d-none');
                $('#choices-multiple-remove-button').prop('required', false);
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

        function swalError(html) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: html,
            })
        }

    });
</script>
