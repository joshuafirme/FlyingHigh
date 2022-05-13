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

        function loadLineItems(shipmentId, tbl) {

            fetch("/shipment/line-items/" + shipmentId)
                .then(data => data.json())
                .then(data => {
                    $('.tbl-pickup-details').html('');
                    let html = "";
                    for (let item of data) {
                        let html = '<tr>';
                        html += '<td>SKU: ' + item.partNumber + '<br> Lot Code: ' + item.lotNumber +
                            '<br>' + item.description + '</td>';
                        html += '<td>' + item.trackingNo + '</td>';
                        html += '<td>' + item.qtyOrdered + '</td>';
                        html += '<td>' + item.qtyShipped + '</td>';
                        //    html += '<td>' + item.reasonCode + '</td>';
                        html += '<td>' + item.shipDateTime + '</td>';
                        //    html += `<td><a class="btn btn-sm btn-primary btn-tag-as-delivered" data-details='`+JSON.stringify(item)+`'>Tag as Delivered</a></td>`;
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

        $(document).on('click', '.btn-ship', function() {
            let mdl = $('#shipModal');
            mdl.modal('show');
            let _this = $(this);
            let order_details = _this.attr('data-order-details');
            order_details = JSON.parse(order_details);
            mdl.attr('data-shipmentid',order_details.shipmentId);
            console.log(order_details)

            for (var key of Object.keys(order_details)) {
                mdl.find('.' + key).text(order_details[key]);
            }
            let tbl = "."
            loadLineItems(order_details.shipmentId)
        });


        $('.btn-deliver').click(function() {
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
        
        $(document).on('submit', '#ship-form', function() {
            let _this = $(this);
            let mdl = $('#shipModal');
            let shipmentId = mdl.attr('data-shipmentid');
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to tag this as Shipped?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            type: 'POST',
                            url: '/shipment/do-ship/' + shipmentId,
                        })
                        .done(function(data) {

                            if (data.success) {
                                swalSuccess('Order was successfully Shipped');
                            } 
                            else if (data.success == false) {
                                swalError(data.message);
                            }
                            else {
                                swalError('Error occured, please contact support!');
                            }

                            _this.find('button[type=submit]').prop('disabled', true);
                        })
                        .fail(function() {
                            swalError('Error occured, please contact support!');
                        });
                }
            })
            return false;
        });



        $('#pickup-form').submit(function(event) {
            let btn = $('#btn-pickedup');
            let shipmentId = $('#pickupModal').attr('shipmentId');
            let orderId = $('#pickupModal').attr('orderId');
            let status = 1;
            let url = '/shipment/do-delivered/' + shipmentId;

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
                            url: url
                        })

                        .done(function(data) {
                            console.log(data)
                            if (data.success == true) {
                                swalSuccess('Shipment was successfully tagged as Delivered');
                            } 
                            else if (data.sku_list && data.sku_list.length > 0) {
                                let html ='Some of stocks are not enough, please check the SKU below.<br>';
                                for (let item of data.sku_list) {
                                    html += '<a target="_blank">SKU: ' + item.sku + '<br> LotCode: ' + item.lot_code + 
                                        '</a><br><hr>';
                                }
                                swalError(html);
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
