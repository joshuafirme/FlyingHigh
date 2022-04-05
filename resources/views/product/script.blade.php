<script>
    $(function() {
        "use strict";

        function clearInputs() {
            let modal = $('#postModal');
            let inputs = modal.find('input');
            $.each(inputs, function(i, v) {
                if (i > 1) {
                    $(v).val('');
                }
            });
        }

        $('.open-modal').click(function(event) {

            let modal = $('#postModal');
            modal.modal('show');
            let modal_type = $(this).attr('modal-type');
            clearInputs();
            if (modal_type == 'create') {
                $('[name=status]').val(1);
                modal.find('.modal-title').text('Create Product');
                modal.find('form').attr('action', "{{ route('product.store') }}");
            } else {
                modal.find('.modal-title').text('Update Product');
                let data = JSON.parse($(this).attr('data-info'));
                modal.find('form').attr('action', "/product/update/" + data.id);

                for (var key of Object.keys(data)) {
                    if (key == 'expiration') {
                        data[key] = data[key].substring(0, 10);
                        console.log(data[key])
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
