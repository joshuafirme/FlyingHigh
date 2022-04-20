<script>
    $(function() {
        "use strict";

        $('.btn-pickup-details').click(function() {
            let v = $(this);
            let orderId = v.attr('data-orderId');
            let order_details = v.attr('data-order-details');
            order_details = JSON.parse(order_details);
            console.log(order_details)

            if (order_details.status == 1) {
                $('#pickup-form').attr('action', '/pickup/return/' + order_details.shipmentId);
                $('#btn-pickedup').html('Return');
            }

            $('#pickupModal').attr('shipmentId', order_details.shipmentId);
            $('#pickupModal').attr('status', order_details.status);
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
    });
</script>
