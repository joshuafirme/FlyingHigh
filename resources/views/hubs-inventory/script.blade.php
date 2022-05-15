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

        $(document).on('click', '.btn-pickup', function() {
            let mdl = $('#pickupModal');
            mdl.modal('show')
            let v = $(this);
            let shipmentId = v.attr('data-shipmentId');
            let order_details = v.attr('data-order-details');
            order_details = JSON.parse(order_details);
            console.log(order_details)

            $('#pickupModal').attr('shipmentId', shipmentId);

            for (var key of Object.keys(order_details)) {
                mdl.find('.' + key).text(order_details[key]);
            }

            $('.tbl-pickup-details').html('');
            initLoader();
            setTimeout(function() {
                loadLineItems(shipmentId);
            }, 300)
        });

        $(document).on('click', '.btn-pickup-one', function() {
            let btn = $(this);
            let v = JSON.parse(btn.attr('data-details'));
            console.log(v)
            let url = '/shipment/do-pickup';

            Swal.fire({
                title: 'Please confirm',
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
                                orderId: v.orderId,
                                partNumber: v.partNumber,
                                qtyShipped: v.qtyShipped
                            }
                        })

                        .done(function(data) {
                            console.log(data)
                            if (data.success == true) {
                                $('.tbl-pickup-details').html('');
                                initLoader();
                                setTimeout(function() {
                                    loadLineItems(v.shipmentId);
                                }, 300)
                                swalSuccess('Item was successfully tagged as Picked-up');
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


        $(document).on('click', '.btn-mark-as-partial', function(event) {
            let shipmentId = $(this).attr('data-shipmentId');
            let status = 3;
            changeStatus(shipmentId, status)
        });

        $(document).on('click', '.btn-mark-as-completed', function(event) {
            let shipmentId = $(this).attr('data-shipmentId');
            let status = 4;
            changeStatus(shipmentId, status)
        });

        function getStatusTextClass(status) {
            let status_text = 'Unclaimed';
            let status_class = 'text-primary';
            if (status == 4) {
                status_text = 'Completed';
                status_class = 'text-success';
            } else if (status == 3) {
                status_text = 'Partially Completed';
                status_class = 'text-warning';
            }
            return {
                text: status_text,
                class: status_class
            }
        }

        function changeStatus(shipmentId, status) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Please confirm.",
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
                            url: '/shipment/change-status/' + shipmentId + '/' + status,
                        })
                        .done(function(data) {
                            if (data.success == true) {
                                let status_text = getStatusTextClass(status);
                                swalSuccess('Order was successfully Mark as ' + status_text.text);
                                setTimeout(function() {
                                    location.reload()
                                },600)
                            } else {
                                swalError('An error occured, please contact support!');
                            }
                        })
                        .fail(function() {
                            swalError('An error occured, please contact support!');
                        });
                }
            })
        }

        function loadLineItems(shipmentId, tbl) {

            fetch("/shipment/line-items/" + shipmentId)
                .then(data => data.json())
                .then(data => {
                    $('.tbl-pickup-details').html('');
                    let html = "";
                    for (let item of data) {
                        let html = '<tr>';
                        html += '<td>' + item.orderId + '</td>';
                        html += '<td>SKU: ' + item.partNumber + '<br> Lot Code: ' + item.lotNumber +
                            '<br>' + item.description + '</td>';
                        html += '<td>' + item.trackingNo + '</td>';
                        html += '<td>' + item.qtyOrdered + '</td>';
                        html += '<td>' + item.qtyShipped + '</td>';
                        html += '<td>' + item.shipDateTime + '</td>';
                        html += '<td>';
                        if (item.status == 2) {
                            html += "Pending";
                        } else if (item.status == 3) {
                            html += "Picked-up";
                        }
                        html += '</td>';
                        html += '<td>';
                        if (item.status == 2) {
                            html += `<a class="btn btn-sm btn-primary btn-pickup-one" data-details='` + JSON.stringify(item) + `'>Tag as Picked up</a>`;
                        }
                        html += '</td>';
                        html += '</tr>';
                        $('.tbl-pickup-details').append(html)
                    }

                })
        }

        function initLoader() {
            $('.tbl-pickup-details').html(
                '<tr><td colspan="8" class="text-center"><i class="fas fa-circle-notch fa-spin" style="font-size: 21px;"></i></td></tr>'
            );
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
                title: 'Oops...',
                html: html,
            })
        }

    });
</script>
