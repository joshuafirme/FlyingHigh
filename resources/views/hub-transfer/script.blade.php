<script>
    $(function() {
        "use strict";

        readTransferList();

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


        function readTransferList() {

            fetch("/hub-transfer/list")
                .then(data => data.json())
                .then(data => {
                    $('#tbl_transfer_list').empty()
                    for (let item of data) {
                        let html = `<tr>`;
                        html += `<input type='hidden' name='sku[]' value='${item.sku}'>`;
                        html += `<input type='hidden' name='lot_code[]' value='${item.lot_code}'>`;
                        html += `<input type='hidden' name='uom[]' value='${item.uom}'>`;
                        html += `<td>${item.sku}</td>`;
                        html += `<td>${item.lot_code}</td>`;
                        html += `<td>${item.stock}</td>`;
                        html +=
                            `<td style="width: 10%;"><input type='number' class='form-control' name='qty_to_transfer[]' required max='${item.stock}'></td>`;
                        html += `<td>
                                        <a class="btn btn-sm btn-outline-danger w-auto m-1 btn-remove" data-id="${item.id}"><i class="fa fa-trash"></i></a>
                                    </td>`;
                        html += `</tr>`
                        $('#tbl_transfer_list').append(html)
                    }
                });
        }


        $(document).on('click', '.btn-add', function(e) {
            let __btn = $(this);
            __btn.html('<i class="fas fa-spinner fa-spin"></i>');
            let id = $(this).attr('data-id');

            $.ajax({
                    type: 'POST',
                    url: '/hub-transfer',
                    data: {
                        lot_code_id: id,
                    }
                })
                .done(function(data) {
                    console.log(data)
                    if (data.success) {
                        readTransferList();
                    } else {
                        swalError(data.message)
                    }
                    __btn.html('<i class="fa fa-plus"></i>');
                })
                .fail(function() {
                    swalError('Importing failed, please try again or contact support.');
                    __btn.html('<i class="fa fa-plus"></i>');
                });
        });

        $(document).on('click', '.btn-remove', function(e) {
            let __btn = $(this);
            __btn.html('<i class="fas fa-spinner fa-spin"></i>');
            let id = $(this).attr('data-id');

            $.ajax({
                    type: 'POST',
                    url: '/hub-transfer/' + id,
                })
                .done(function(data) {
                    console.log(data)

                    __btn.html('<i class="fa fa-plus"></i>');
                    readTransferList();
                })
                .fail(function() {
                    swalError('Importing failed, please try again or contact support.');
                    __btn.html('<i class="fa fa-plus"></i>');
                });
        });

        $(document).on('submit', '#transfer-form', function(e) {
            let form = $(this);

            Swal.fire({
                title: 'Confirmation',
                text: `You are going to transfer products to ${ $("#hub option:selected").text() }. Click yes to confirm.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            type: 'POST',
                            _token: '{{ csrf_token() }}',
                            url: '/do-transfer',
                            data: $(this).serialize()
                        })
                        .done(function(data) {

                            if (data.success) {
                                swalSuccess('Product was successfully Tags as Picked Up');
                                setTimeout(() => {
                                    loadLineItems(orderId)
                                }, 500);
                            } else if (data.message == 'not_enough_stock') {
                                swalError('Not enough stock.');
                            } else {
                                swalError('Error occured, please contact support!');
                            }
                        })
                        .fail(function() {
                            swalError('Error occured, please contact support!');
                        });
                }
            })

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
