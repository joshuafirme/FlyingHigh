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

        function loadLineItems(shipmentId) {

            fetch("/shipment/line-items/" + shipmentId)
                .then(data => data.json())
                .then(data => {
                    $('#tbl-pickup-details').html('');
                    let html = "";
                    for (let item of data) {
                        if (item.sku == null) {
                            continue;
                        }
                        let html = '<tr>';
                        html += '<td>SKU: ' + item.sku + '<br> Lot Code: ' + item.lotNumber + '<br>' + item.description + '</td>';
                        html += '<td>' + item.trackingNo + '</td>';
                        html += '<td>' + item.qtyOrdered + '</td>';
                        html += '<td>' + item.qtyShipped + '</td>';
                    //    html += '<td>' + item.reasonCode + '</td>';
                        html += '<td>' + item.shipDateTime + '</td>';
                    //    html += `<td><a class="btn btn-sm btn-primary btn-tag-as-delivered" data-details='`+JSON.stringify(item)+`'>Tag as Delivered</a></td>`;
                        html += '</tr>';
                        $('#tbl-pickup-details').append(html)
                    }

                })
        }

        function initLoader() {
            $('#tbl-pickup-details').html(
                '<tr><td colspan="8" class="text-center"><i class="fas fa-circle-notch fa-spin" style="font-size: 21px;"></i></td></tr>'
            );
        }


        $('.btn-pickup-details').click(function() {
            let mdl = $('#pickupModal');
            mdl.modal('show')
            let v = $(this);
            let shipmentId = v.attr('data-shipmentId');
            let order_details = v.attr('data-order-details');
            order_details = JSON.parse(order_details);
            console.log(order_details)

            $('#pickupModal').attr('shipmentId',shipmentId);

            for (var key of Object.keys(order_details)) {
                mdl.find('.' + key).text(order_details[key]);
            }

            $('#tbl-pickup-details').html('');
            initLoader();
            setTimeout(function() {
                loadLineItems(shipmentId);
            }, 300)
        });

        $(document).on('click', '.btn-mark-as-completed', function(event) {
            let shipmentId = $('#pickupModal').attr('shipmentId');
            let status = 1;
            changeStatus(shipmentId, status)
        });

        $(document).on('click', '.btn-mark-as-overdue', function(event) {
            let shipmentId = $('#pickupModal').attr('shipmentId');
            let status = 2;
            changeStatus(shipmentId, status)
        });

        $(document).on('click', '.btn-mark-as-partially-completed', function(event) {
            let shipmentId = $('#pickupModal').attr('shipmentId');
            let status = 3;
            changeStatus(shipmentId, status)
        });

        $(document).on('click', '.btn-tag-as-delivered', function(event) {
            let btn = $(this);
            let v = JSON.parse(btn.attr('data-details'));
            console.log(v)
            let hub_id = $('[name=hub_id]').find("option:selected").val();
            if (!hub_id) {
                swalError('Please select a Hub');
                return;
            }
            let url = '/pickup/tag-one-as-picked-up';

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
                            data: {
                                orderId: orderId,
                                sku: sku,
                                qty: qty,
                                hub_id: hub_id
                            }
                        })

                        .done(function(data) {

                            if (data.success == true) {
                                swalSuccess('Product was successfully Tags as Picked Up');
                                setTimeout(() => {
                                    loadLineItems(orderId)
                                }, 500);
                            } else if (data.message == 'not_enough_stock') {
                                swalError('Not enough stock.');
                            } else {
                                swalError('Error occured, please contact support!');
                            }
                            btn.html("Picked-up");
                        })
                        .fail(function() {
                            swalError('Error occured, please contact support!');
                            btn.html("Picked-up");
                        });
                }
            })

            return false;
        });



        $('#pickup-form').submit(function(event) {
            let btn = $('#btn-pickedup');
            let shipmentId = $('#pickupModal').attr('shipmentId');
            let orderId = $('#pickupModal').attr('orderId');
            let hub_id = $('[name=hub_id]').find("option:selected").val();
            let status = 1;
            let url = '/shipment/change-status/' + shipmentId + '/' + status;

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

                            if (data.message == 'success') {
                                swalSuccess('Shipment was successfully tagged as Delivered');
                            }
                            else {
                                swalError('Error occured, please contact support!');
                            }
                            btn.html("Tag as Delivered");
                        })
                        .fail(function() {
                            swalError('Error occured, please contact support!');
                            btn.html("Tag as Delivered");
                        });
                }
            })

            return false;
        });

        $(document).on('click', '.btn-tag-as-overdue', function(event) {
            let shipmentId = $(this).attr('data-shipmentId');
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to tag this Pick Up as Overdue?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            type: 'POST',
                            url: '/pickup/tag-as-overdue/' + shipmentId,
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                        })

                        .done(function(data) {

                            if (data.message == 'success') {
                                swalSuccess('Product was successfully Tags as Overdue');
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
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

        $('#return-form').submit(function(event) {

            let orderId = $(this).find('[name=orderId]').val();
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to tag this as Returned?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            type: 'POST',
                            url: '/pickup/return',
                            data: $(this).serialize()
                        })
                        .done(function(data) {

                            if (data.success == true) {
                                swalSuccess('Item was successfully tagged as Returned');
                                setTimeout(() => {
                                    loadLineItems(orderId);
                                }, 500);
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
