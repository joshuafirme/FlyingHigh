<script>
    $(function() {
        "use strict";

        const el_choices_multi = document.getElementById('transactionReferenceNumber');
        const choices = new Choices(el_choices_multi, {
            removeItemButton: true,
            placeholderValue: 'Search Transaction Reference #',
            delimiter: ',',
            editItems: true,
        });

        $(document).on('click', '[data-target="#confirmModal"]', function(event) {
            let transactionReferenceNumber = $('#transactionReferenceNumber').val();
            let url = `/stock-transfer/received-list/${transactionReferenceNumber}`;
            let identifier = "#orderListContainer";
            fetchData(url, identifier)
        });

        $(document).on('change', '#transactionReferenceNumber', function(event) {
            let __this = $(this);
            let transactionReferenceNumber = __this.val();
            let url = `/stock-transfer/received-list/${transactionReferenceNumber}`;
            let identifier = "#orderListContainer";
            fetchData(url, identifier)
        });

        $(document).on('click', '#btn-send-confirmation', function(event) {
            let __this = $(this);
            __this.html("Please wait...")
            __this.prop("disabled", true)
            let transactionReferenceNumber = $('#transactionReferenceNumber').val();
            let url = `/api/confirm-purchase-orders/${transactionReferenceNumber}`;

            let data = {
                'client_id': "{{ env('LF_CLIENT_ID') }}",
                'client_secret': "{{ env('LF_CLIENT_SECRET') }}"
            }
            $.ajax({
                    type: 'POST',
                    _token: '{{ csrf_token() }}',
                    url: url,
                    data: data
                })
                .done(function(data) {
                    console.log(data)
                    if (data.success) {
                        responseMessage(data, 'success')
                    } else {
                        responseMessage(data, 'danger')
                    }

                    __this.html("Send")
                    __this.prop("disabled", false)
                })
                .fail(function() {
                    swalError(data.exceptionMessage);

                    __this.html("Send")
                    __this.prop("disabled", false)
                });
        });

        function responseMessage(data, alert_class) {
            $('.confirmation-message').empty();
            let alert = `<div class='col-12 confirmation-message'>`;
            alert += `<p>Response</p>`;
            alert += `<pre><code><div class="alert alert-${alert_class}" role="alert">`;
            alert += JSON.stringify(data, undefined, 2);
            alert += `</div></code></pre>`;
            alert += `</div>`;
            $('#confirmModal .modal-body').append(alert)
        }

        $(document).on('click', '.btn-receive', function(event) {
            let __this = $(this);
            let mdl = $('#receiveModal');
            mdl.modal('show');
            let btn = mdl.find('[type=submit]');
            let obj = __this.attr('data-item');
            obj = JSON.parse(obj);
            for (var key of Object.keys(obj)) {
                mdl.find('[name=' + key + ']').val(obj[key]);
                mdl.find('[class=' + key + ']').text(obj[key]);
            }
            $('#receiveForm').attr('orderNumber', obj.orderNumber);
        });

        $('#receiveForm').on('submit', function(event) {
            let orderNumber = $(this).attr('orderNumber')
            let receiptDate = $(this).find('[name=receiveDate]').val()
            if (!orderNumber) {
                swalError('An error occured, please refresh the page and try again.')
            }
            let url = `/stock-transfer/transfer/${orderNumber}/${receiptDate}`;

            ajaxPost(url)

            return false;
        });

        async function fetchData(url, identifier) {

            $(identifier).empty();
            $('.confirmation-message').empty();
            let html = `<tr>`;
            html += `<td class="text-center" colspan="7">Fetching...</td>`;
            html += `</tr>`;
            $(identifier).append(html);
            fetch(url)
                .then(data => data.json())
                .then(data => {
                    $(identifier).empty();
                    setTimeout(function() {
                        if (data.length > 0) {
                            for (let item of data) {
                                let html = `<tr>`;
                                html += `<td>${item.orderNumber}</td>`;
                                html += `<td>${item.orderType}</td>`;
                                html += `<td>${item.orderDate}</td>`;
                                html += `<td>${item.vendorNo}</td>`;
                                html += `<td>${item.vendorName}</td>`;
                                html += `<td>${item.shipFromAddress}</td>`;
                                html += `<td>${item.shipFromCountry}</td>`;
                                html += `</tr>`;

                                $(identifier).append(html);
                            }
                        } else {
                            let html = `<tr>`;
                            html += `<td class="text-center" colspan="7">No data found</td>`;
                            html += `</tr>`;
                            $(identifier).append(html);
                        }
                    }, 300)
                });
        }

        async function ajaxPost(url, data = null) {
            $.ajax({
                    type: 'POST',
                    _token: '{{ csrf_token() }}',
                    url: url,
                    data: data
                })
                .done(function(data) {
                    console.log(data)
                    if (data.success) {
                        swalSuccess(data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 2500);
                    } else {
                        swalError(data.exceptionMessage);
                    }
                    $('.btn-confirm-receive').html("Receive");
                })
                .fail(function() {
                    swalError('Error occured, please contact support!');
                    $('.btn-confirm-receive').html("Receive");
                });
        }

        /*$(document).on('click', '#select-from-old', function() {
            let mdl = $('#transferModal');
            let html = '<div class="col-md-12 mt-3 lot-codes-container">';
                html +=    '<label class="form-label">Lot Code</label>';
                html +=    '<select class="form-control" name="lot_code"></select>';
                html += '</div>';
            if ($(this).prop('checked')) {
                let sku = mdl.find('[name=sku]').val();
                mdl.find('[name=lot_code]').parent().remove();
                mdl.find('[name=expiration]').parent().remove();
                mdl.find('.modal-body').append(html);
                appendLotCodes(sku, mdl)
            } 
            else {
                mdl.find('.lot-codes-container').remove();
                appendLotCodeAndExpirationHTML(mdl);
            }
        });  

        function appendLotCodeAndExpirationHTML(mdl) {
            let html = '<div class="col-md-12 mt-3 lot-codes-container">';
                html +=    '<label class="form-label">Lot Code</label>';
                html +=    '<input type="text" name="lot_code" class="form-control">';
                html += '</div>';
                html += '<div class="col-md-12 mt-3 lot-codes-container">';
                html +=    '<label class="form-label">Expiration</label>';
                html +=    '<input type="date" name="expiration" class="form-control">';
                html += '</div>';
            mdl.find('.modal-body').append(html);
        }

        function appendLotCodes(sku, mdl) {
            fetch("/api/lotcode/" + sku)
                .then(data => data.json())
                .then(data => {
                    console.log(data)
                    mdl.find('[name=lot_code]').html('');
                    if (data.length > 0) {
                        for (let item of data) {
                            if (item.lot_code == 0) { continue; }
                            let html = `<option exp="${item.expiration}" value="${item.lot_code}">${item.lot_code == 0 ? 'N/A' : item.lot_code}</option>`
                            mdl.find('[name=lot_code]').append(html);
                        }
                    }
                    else {
                        let html = `<option disabled value="0" selected>N/A</option>`;
                        mdl.find('[name=lot_code]').append(html);
                    }
                    let exp = mdl.find("[name=lot_code] option:selected").attr('exp');
                    mdl.find('[name=expiration]').val(exp);
            });
        }

        $('#transferForm').submit(function(event) {
            let mdl = $('#transferModal');
            let btn = mdl.find('[typr=submit]');
            let id = mdl.find('[name=id]').val();
            let qty_transfer = mdl.find('[name=qty_transfer]').val();
            
            let url = '/stock-transfer/transfer';

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
                            _token: '{{ csrf_token() }}',
                            url: url,
                            data: $(this).serialize()
                        })

                        .done(function(data) {
                            console.log(data)
                            if (data.success == true) {
                                swalSuccess('Product was successfully transferred.');
                                setTimeout(() => {
                                    location.reload();
                                }, 2500);
                            } 
                            else {
                                swalError('Qty to transfer is greater than pending qty.');
                            }
                            btn.html("Transfer");
                        })
                        .fail(function() {
                            swalError('Error occured, please contact support!');
                            btn.html("Transfer");
                        });
                }
            })

            return false;
        });*/


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
