<script>
    $(function() {
        "use strict";

        $(document).on('click', '.btn-transfer', function() {
            let mdl = $('#transferOneModal');
            mdl.modal('show');
            let _this = $(this);
            let obj = _this.attr('data-obj');
            obj = JSON.parse(obj);
            mdl.attr('data-shipmentid',obj.shipmentId);
            console.log(obj)

            mdl.find('[name=qtyTransfer]').val('')

            for (var key of Object.keys(obj)) {
                mdl.find('[name='+key+']').val(obj[key]);
            }
        });

        $('#btn-transfer-all').click(function(event) {
            let mdl = $('#transferModal');
            let btn = mdl.find('[typr=submit]');
            let id = mdl.find('[name=id]').val();
            let qty_transfer = mdl.find('[name=qty_transfer]').val();
            
            let url = '/stock-transfer/transfer/{{ $purchase_order->orderNumber }}';

            Swal.fire({
                title: 'Transfer all line items of Order # {{ $purchase_order->orderNumber }}',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
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
        });
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
