<script>
    $(function() {
        "use strict";

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
                swalError('An error occured, please refresh your page and test again.')
            }
            let url = `/stock-transfer/transfer/${orderNumber}/${receiptDate}`;

            ajaxPost(url)

            return false;
        });

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
