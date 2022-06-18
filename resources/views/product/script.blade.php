<script>
    $(function() {
        "use strict";

        lastSycned()

        setInterval(() => {
            lastSycned();
        }, 60000);

        function lastSycned() {
            let timeago = convertUnixTimeToTimeAgo("{{ Cache::get('sku_master_last_sync') }}");
            let timeago_splitted = timeago.split(' ');
            let ago_text = timeago_splitted[0] > 1 ? 's ago' : ' ago';
            console.log(timeago)
            $('#last_synced').html(timeago + ago_text)
        }

        function convertUnixTimeToTimeAgo(datetime) { 
            let timestamp = Math.floor(new Date(datetime).getTime() / 1000)
            var seconds = Math.floor(((new Date().getTime()/1000) - timestamp)),
            interval = Math.floor(seconds / 31536000);

            if (interval > 1) return interval + " year";
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) return interval + " month";
            
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) return interval + " day";

            interval = Math.floor(seconds / 3600);
            if (interval >= 1) return interval + " hr";

            interval = Math.floor(seconds / 60);
            if (interval > 1) return interval + " min";

            return Math.floor(seconds) + "s";
        }


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

        function appendInputs(data){
            var html = '';
            html += '<tr id="' + data.id + '_' + data.itemNumber + '">';
            html += '<td>';
            html += data.description;
            html += '<input type="hidden" class="form-control" name="sku[]" value="' + data.itemNumber + '">';
            html += '</td>';
            html += '<td>';
            html += '<select class="form-control" name="lot_code[]" required>';
      
            for (let item of data.lot_codes) {
                let lot_code = item.lot_code != 0 ? item.lot_code : 'N/A';
                html += '<option value="'+ item.lot_code +'">'+ lot_code +'</option>';
            }
 
            html += '</select>';
            html += '</td>';
            html += '<td>' + data.stock + '</td>';
            html += '<td>';
            html +='<input type="number" class="form-control" name="qty[]" required max="' + data.stock + '" min="1">';
            html += '</td>';

            html += '<td>';
            html += '<select class="form-control" name="hub_id[]" required>';
            html += '<option selected disabled value="">Choose Hub...</option>';
            html += '@foreach ($hubs as $item)';
                html += ' <option value="{{ $item->id }}">{{ $item->name }}</option>';
            html += '@endforeach ';
            html += '</select>';
            html += '</td>';
            html += '<td><a class="btn btn-remove-sku" data-id="' + data.id + '_' + data.itemNumber + '"><i class="fa fa-trash"></i></a></td>';
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
                                    .itemNumber) != -1) {
                                is_selected = true;
                            }
                            return {
                                label: v.itemNumber + ' | ' + v.description,
                                value: v.itemNumber,
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

        function initLoader() {
            $('.tbl-lot-codes').html('<tr><td colspan="3" class="text-center"><i class="fas fa-circle-notch fa-spin" style="font-size: 21px;"></i></td></tr>');
        }

        function getLotCodes(sku, type = "edit") {
            
            initLoader();    
            fetch("/api/lotcode/" + sku)
                .then(data => data.json())
                .then(result => {
                    console.log(result)
                    setTimeout(() => {
                    $('.tbl-lot-codes').html('');
                        if (result.length > 0) {
                                for (let item of result) {
                                    let lot_code = item.lot_code == 0 ? 'N/A' : item.lot_code;
                                    let expiration = item.expiration ? item.expiration.substring(0, 10) : 'N/A';
                                    let html = '<tr id="'+item.id+'">';
                                    if (type=='edit') {
                                        html += '<td><input type="text" name="lot_code[]" readonly class="form-control" value="'+ lot_code +'"></td>';
                                        html += '<td>'+item.stock+'</td>';
                                        html += '<td><input type="date" name="expiration[]" class="form-control" value="'+ expiration +'"></td>';
                                        html += '<td><a data-id="'+item.id+'" class="btn-archive" href="#" style="color:#DC0100;">Archive</a></td>';
                                    }
                                    else {
                                        html += '<td>'+ lot_code+'</td>';
                                        html += '<td>'+ item.stock +'</td>';
                                        html += '<td>'+ expiration +'</td>';
                                    }
                                    html += '</tr>';
                                    $('.tbl-lot-codes').append(html);
                                }
                        }
                        else {
                            let html = '<tr>';
                            html += '<td colspan="4"><div class="alert alert-primary">No data found.</div></td>';
                            html += '</tr>';
                            $('.tbl-lot-codes').append(html);
                        }
                    }, 300);
                })
        }

        function incrementStock(sku, qty) {
                $.ajax({
                    type: 'POST',
                    url: '/increment-stock',
                    data: {
                        sku: sku,
                        qty: qty
                    }
                })
                .done(function(data) {
                    console.log(data)
                })
                .fail(function() {
                    swalError('Importing failed, please try again or contact support.');
                });
        }

        
        function appendLotCodes(sku, mdl) {
            fetch("/api/lotcode/" + sku)
                .then(data => data.json())
                .then(data => {
                    console.log(data)
                    mdl.find('[name=lot_code]').html('');
                    for (let item of data) {
                        let html = `<option exp="${item.expiration}" value="${item.lot_code}">${item.lot_code == 0 ? 'N/A' : item.lot_code}</option>`
                        mdl.find('[name=lot_code]').append(html);
                    }
                    let exp = mdl.find("[name=lot_code] option:selected").attr('exp');
                    mdl.find('[name=expiration]').val(exp);
            });
        }

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
                title: 'Oops... Error was occured.',
                html: html,
            })
        }

        function eventsListener() {
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
                    $('.tbl-lot-codes').html('');
                    $('[name=status]').val(1);
                    modal.find('.modal-title').text('Create Product');
                    modal.find('form').attr('action', "{{ route('product.store') }}");
                    $('#bundle-qty-container').addClass('d-none');
                    initChoices('choices-multiple-remove-button');
                } else {
                    modal.find('.modal-title').text('Update Product');
                    let data = JSON.parse($(this).attr('data-info'));
                    modal.find('form').attr('action', "/product/update/" + data.id);
                    initChoices('choices-multiple-remove-button', data.bundles);

                    console.log(data)
                    for (var key of Object.keys(data)) {
                        if (key == 'expiration') {
                            data[key] = data[key] ? data[key].substring(0, 10) : '';
                        }
                        if (key == 'qty') {
                            modal.find('[name=' + key + ']').prop('readonly', true);
                        }
                        modal.find('[name=' + key + ']').val(data[key]);
                    }
                    getLotCodes(data.itemNumber);
                }
            });
            
            $('#btn-add-lot-code').click(function() { 
                let html = '<tr>';
                    html += '<td><input type="text" name="lot_code[]" class="form-control"></td>';
                    html += '<td></td>';
                    html += '<td><input type="date" name="expiration[]" class="form-control"></td>';
                html += '</tr>'
                $('.tbl-lot-codes').append(html);
            });

            $('.btn-view-detail').click(function() {
                let data = JSON.parse($(this).attr('data-info'));
                let modal = $('#detailModal');
                let tbl = $('.tbl-product-details');
                tbl.find('tbody').empty()
                for (var key of Object.keys(data)) {
                    if (key == 'id' ||  key == 'productDescription' || key == 'lotCode' || key == 'stock' || key == 'status' || key == 'created_at' || key == 'updated_at') {
                        continue;
                    }
                    let html_attr = '';
                    html_attr += '<tr>';
                        html_attr += '<td>'+key+'</td>';
                        html_attr += '<td>'+data[key]+'</td>';
                    html_attr += ' </tr>';
                    tbl.find('tbody').append(html_attr)
                }
                getLotCodes(data.itemNumber, 'details');
            });

            $(document).on('click', '#btn-sync-skumaster', function() {
                let btn = $(this);
                btn.prop("disabled", true);
                let current_html = btn.html();
                btn.html('Syncing...')
                $.ajax({
                    type: 'POST',
                    url: "{{ url('/api/sync-skumasters') }}",
                    data: {
                        'client_id' : "{{ env('LF_CLIENT_ID') }}",
                        'client_secret' : "{{ env('LF_CLIENT_SECRET') }}"
                    },
                }).done(function(response) { console.log(response)
                    if (response.success) {
                        lastSycned()
                        swalSuccess(response.message);
                    }
                    else {
                        swalError(response.exceptionMessage);
                    }
                    btn.html(current_html);
                    btn.prop("disabled", false);
                })
                .fail(function(e) {
                    swalError(e.responseJSON.message);
                    btn.html(current_html);
                    btn.prop("disabled", false);
                });
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
                                html += '<td>'+item.lot_code+'</td>';
                                html += '<td>'+item.stock+'</td>';
                                html += '<td>'+item.expiration+'</td>';
                                html += '</tr>';
                                $('#tbl-hubs-stock').append(html);
                            }
                        }
                        else {
                            let html = '<tr>';
                            html += '<td colspan="4"><div class="alert alert-primary">No data found.</div></td>';
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

            $(document).on('click', '.btn-archive', function() {
                let v = $(this);
                let id = v.attr('data-id');
                 Swal.fire({
                    title: 'Are you sure do you want to archive this lot code?',
                    text: "",
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
                                url: "/product/lotcode/archive/"+id
                            })

                            .done(function(data) {
                                $('#' + id).remove();
                                swalSuccess('Product Lot code was successfully archived.')
                            })
                            .fail(function() {
                                alert("Error occured. Please try again.");
                            });
                    }
                })

                return false;
            });
            

            $(document).on('keyup', '#barcode-scan', function(e) { 
                let barcode = $(this).val();
                if (e.keyCode == 86 || e.keyCode == 8) {
                    fetch("/api/product/barcode/" + barcode)
                        .then(data => data.json())
                        .then(data => {
                            console.log(data)
                            if (data.itemNumber) {
                                $(this).val('')
                                appendInputs(data);
                            }
                        });
                }
            });

            $('.btn-lot-codes').click(function() {
                let data = JSON.parse($(this).attr('data-info'));
                let mdl = $('#lotCodesModal');
                mdl.find('.modal-title').text('Lot Codes');
                mdl.find('.itemNumber-text').html(data.itemNumber); 
                mdl.find('.description-text').html(data.description); 
                getLotCodes(data.itemNumber, 'lotcodes');
            });

            $('.btn-transfer').click(function() {
                let v = $(this);
                let sku = v.attr('data-sku');
                let description = v.attr('data-desc');
                let current_stock = v.attr('data-stock');
                let mdl = $('#transferModal');
                mdl.find('[name=sku]').val(sku);
                mdl.find('[name=description]').val(description);
                mdl.find('[name=current_stock]').val(current_stock);

                appendLotCodes(sku, mdl);
            });

            $('.btn-stock-adjustment').click(function() {
                let v = $(this);
                let sku = v.attr('data-sku');
                let description = v.attr('data-desc');
                let stock = v.attr('data-stock');
                let mdl = $('#stockAdjustmentModal');
                mdl.find('[name=sku]').val(sku);
                mdl.find('[name=description]').val(description);
                mdl.find('[name=stock]').val(stock);

                appendLotCodes(sku, mdl);
                
            });


            $('#lot_codes').change(function() { 
                let mdl = $('#stockAdjustmentModal');
                let exp = $('option:selected', this).attr('exp');
                mdl.find('[name=expiration]').val(exp);
            });

            $('[name=lot_code]').change(function() { 
                let mdl = $('#transferModal');
                let exp = $('option:selected', this).attr('exp');
                mdl.find('[name=expiration]').val(exp);
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
                                    console.log(data.lot_codes)
                                    for (let sku of data.lot_codes) {
                                        html += '<a target="_blank" href="#">'+sku+'</a><br>';
                                    }
                                    swalError(html);
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
                                }
                                else if (data.message == 'not_enough_stock') {
                                    swalError('Invalid qty');
                                }
                                else {
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


            $('#btn-open-import-via-barcode').click(function(e) {
                e.preventDefault();
                $('#barcodeScanModal').modal('show');
                let modal = $('#barcodeScanModal');
                modal.find('[name=qty]').val(1);
                setTimeout(function() {
                    $('#barcode-scan-input').focus();
                },1000)
            });

            $('#barcode-scan-input').keyup(function(e) {
                let modal = $('#barcodeScanModal');
                let el = $(this);
                let barcode = el.val();
                modal.find('.error-message').addClass('d-none');
                let qty = modal.find('[name=qty]').val();
                console.log(e.keyCode)
                if (e.keyCode == 86) {
                    fetch("/api/product/barcode/" + barcode)
                        .then(data => data.json())
                        .then(data => {
                            console.log(data)
                            if (data.itemNumber) {
                                el.val('');
                                modal.find('[name=sku]').val(data.itemNumber);
                                modal.find('[name=description]').val(data.description);
                                incrementStock(data.itemNumber, qty);
                            }
                            else {
                                modal.find('.error-message').removeClass('d-none');
                                modal.find('.error-message').html(data.itemNumber);
                            }
                    });
                }
            });

            $('#import-via-api-form').submit(function(e) {
                e.preventDefault();
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
                        } 
                        else if (data.message == 'some_sku_not_exists') {
                            let html = data.description + '<br>';
                            console.log(data.itemNumber_list)
                            for (let sku of data.itemNumber_list) {
                                html += '<a target="_blank" href="#">'+sku+'</a><br>';
                            }
                            swalError(html);
                        }
                        else {
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
        }

        eventsListener();

    });
</script>
