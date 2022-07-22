<script>
    $(function() {
        "use strict";

        function clearInputs() {
            let modal = $('#postModal');
            let inputs = modal.find('input,select');
            $('.bundle-choices').addClass('d-none');
            $.each(inputs, function(i, v) {
                if (i > 1) {
                    if ($(v).attr('type') == 'checkbox') {
                        modal.find('input[type="checkbox"]').prop('checked', false);
                        return;
                    }
                    $(v).val('');
                }
            });
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

            
            $(document).on('click', '.btn-update-exp', function() {
                let v = $(this);
                let data = v.attr('data-item');
                data = JSON.parse(data)
                let mdl = $('#updateExpModal');
                mdl.modal('show');
                for (var key of Object.keys(data)) {
                    mdl.find(`[name='${key}']`).val(data[key])
                    if (key=='expiration') {
                        let datetime = new Date(data[key])
                        let time = datetime.toLocaleTimeString()
                        let yyyy = datetime.getFullYear();
                        let mm = datetime.getMonth() + 1; // Months start at 0!
                        let dd = datetime.getDate();

                        if (dd < 10) dd = '0' + dd;
                        if (mm < 10) mm = '0' + mm;

                        let exp = yyyy + '-' + mm + '-' + dd;
                        console.log(time.length)
                        console.log(exp)
                        if (time.length < 11) {
                            exp = exp + " " + '0' + time;
                        }
                        else {
                            exp = exp + " " + time;
                        }
                        exp = exp.replace(' AM', '');
                        exp = exp.replace(' PM', '');
                    mdl.find(`[name='${key}']`).val(exp)
                    }
                    
                }
                $('#updateExpform').attr('data-id', data.id);
            });

            $('#updateExpform').submit(function(event) {
                let id = $(this).attr('data-id');
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
                                url: `/inventory/update-expiration/${id}`,
                                data: $(this).serialize()
                            })

                            .done(function(data) {
                                console.log(data)
                                if (data.success) {
                                    swalSuccess(data.message);
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2500);
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


            $(document).on('click', '.btn-stock-adjustment', function() {
                let v = $(this);
                let data = v.attr('data-item');
                data = JSON.parse(data)
                let mdl = $('#stockAdjustmentModal');
                mdl.modal('show');
                for (var key of Object.keys(data)) {
                    if (key=='sku') {
                        $('.stock-adjustment-link').attr('href', '/reports/stock-adjustment?sku=' + data[key])
                    }
                    mdl.find(`[name='${key}']`).val(data[key])
                }
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
                                url: "{{ url('/inventory/adjust') }}",
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
