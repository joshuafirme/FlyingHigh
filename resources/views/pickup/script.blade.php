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

        $('.btn-pickup-details').click(function() {
            let v = $(this);
            let orderId = v.attr('data-orderId');
            let order_details = v.attr('data-order-details');
            order_details = JSON.parse(order_details);
            console.log(order_details)
            $('#pickupModal').attr('shipmentId', order_details.shipmentId);
            $('#shipmentId').text(order_details.shipmentId);
            $('#orderId').text(order_details.orderId);
            $('#contractDate').text(order_details.contractDate);
            $('#dateTimeSubmittedIso').text(order_details.dateTimeSubmittedIso);
            $('#customerEmail').text(order_details.customerEmail);
            $('#customerEmail').attr('href', 'mailto:' + order_details.customerEmail);
            $('#custName').text(order_details.custName);
            $('#shipPhone').text(order_details.shipPhone);
            $('#shipCity').text(order_details.shipCity);
            $('#shipState').text(order_details.shipState);
            $('#shipZip').text(order_details.shipZip);
            $('#shippingChargeAmount').text(order_details.shippingChargeAmount);
            $('#salesTaxAmount').text(order_details.salesTaxAmount);
            $('#shippingTaxTotalAmount').text(order_details.shippingTaxTotalAmount);
            $('#packageTotal').text(order_details.packageTotal);

            $('#tbl-pickup-details').html('');
            fetch("/get-line-items/" + orderId)
                .then(data => data.json())
                .then(data => {
                    let html = "";
                    for (let item of data) {
                        if (item.sku == null) {
                            continue;
                        }
                        let html = '<tr>';
                        html += '<td>' + item.sku + '</td>';
                        html += '<td>' + item.description + '</td>';
                        html += '<td>' + item.quantity + '</td>';
                        html += '<td></td>';
                        html += '</tr>';
                        $('#tbl-pickup-details').append(html)
                    }

                })
        });

        $('#pickup-form').submit(function(event) {
            let btn = $('#btn-pickedup');
            let shipmentId = $('#pickupModal').attr('shipmentId');
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
                            url: '/pickup/tag-as-picked-up/' + shipmentId,
                            data: $(this).serialize()
                        })

                        .done(function(data) {

                            if (data.message == 'success') {
                                swalSuccess('Product was successfully Tags as Picked Up');
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                swalError('Error occured, please contact support!');
                            }
                            btn.html("Tag as Picked Up");
                        })
                        .fail(function() {
                            swalError('Error occured, please contact support!');
                            btn.html("Tag as Picked Up");
                        });
                }
            })

            return false;
        });

        $('.btn-tag-as-overdue').click(function(event) {
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
