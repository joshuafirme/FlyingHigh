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

        function loadLineItems(orderId) {

            fetch("/get-line-items/" + orderId)
                .then(data => data.json())
                .then(data => {
                    $('.tbl-orders-details').html('');
                    let html = "";
                    for (let item of data) {
                        if (item.lineType == 'PN' || item.lineType == 'N') {
                            continue;
                        }
                        let html = '<tr>';
                        html += '<td>' +
                            item.partNumber + '<br>' +
                            item.name + '<br>' +
                            'Parent Kit: ' + item.parentKitItem + '</td>';
                        html += '<td>' + item.pv + '</td>';
                        html += '<td>' + item.quantity + '</td>';
                        html += '<td>' + item.itemUnitPrice + '</td>';
                        html += '<td>' + item.salesPrice + '</td>';
                        html += '<td>' + item.taxableAmount + '</td>';
                        html += '<td>' + item.lineItemTotal + '</td>';
                        html += '</tr>';
                        $('.tbl-orders-details').append(html)
                    }

                })
        }

        function appendLotCodes(sku, mdl) {
            fetch("/api/lotcode/" + sku)
                .then(data => data.json())
                .then(data => {
                    console.log(data)
                    mdl.find('.lot-code-' + sku).html('');
                    for (let item of data) {
                        let html =
                            `<option exp="${item.expiration}" value="${item.lot_code}">${item.lot_code == 0 ? 'N/A' : item.lot_code}</option>`
                        mdl.find('.lot-code-' + sku).append(html);
                    }
                });
        }

        $('#lot_codes').change(function() {
            let mdl = $('#returnModal');
            let exp = $('option:selected', this).attr('exp');
            mdl.find('[name=expiration]').val(exp);
        });

        $('#btn-show-fetch-modal').click(function() {
            let mdl = $('#fetchOrderModal');
            mdl.modal('show')

            return false;
        });

        $('#btn-bulk-fetch').click(function() {
            let btn = $(this);
            let shipment_count = $('input[name="shipment_count"]').val();
            if (!shipment_count) {
                swalError('Please enter the number of shipment.')
                return;
            }
            btn.html('Fetching <i class="fas fa-circle-notch fa-spin"></i>');

            fetchShipment(btn);
        });

        $('#btn-single-fetch').click(function() {
            let btn = $(this);
            let shipmentId = $('#fetchOrderModal input[name="shipmentId"]').val();
            if (!shipmentId) {
                swalError('Please enter the Shipment ID.')
                return;
            }
            btn.html('Fetching <i class="fas fa-circle-notch fa-spin"></i>');

            fetchShipment(btn, shipmentId);
        });

        function fetchShipment(btn, shipmentId = null) {

            let url = `/api/shipment`;
            if (shipmentId) {
                url = `/api/shipment/${shipmentId}`;
            }
            $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        'client_id': "{{ env('LF_CLIENT_ID') }}",
                        'client_secret': "{{ env('LF_CLIENT_SECRET') }}"
                    },
                }).done(function(response) {
                    console.log(response)
                    if (response.success) {
                        swalSuccess(response.message);
                    } else {
                        swalError(response.message);
                    }
                    btn.html('Fetch');
                    btn.prop("disabled", false);
                })
                .fail(function(e) {
                    swalError(e.responseJSON.message);
                    btn.html('Fetch');
                    btn.prop("disabled", false);
                });
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
                            url: '/orders/change-status/' + shipmentId + '/' + status,
                        })
                        .done(function(data) {
                            if (data.success == true) {
                                let status_text = getStatusTextClass(status);
                                swalSuccess('Order was successfully Mark as ' + status_text.text);
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

        function getStatusTextClass(status) {
            let status_text = 'Pending';
            let status_class = 'text-primary';
            if (status == 1) {
                status_text = 'Shipped';
                status_class = 'text-success';
            } else if (status == 2) {
                status_text = 'Delivered';
                status_class = 'text-danger';
            } else if (status == 3) {
                status_text = 'Picked Up';
                status_class = 'text-warning';
            }
            return {
                text: status_text,
                class: status_class
            }
        }

        function initLoader() {
            $('.tbl-orders-details').html(
                '<tr><td colspan="5" class="text-center"><i class="fas fa-circle-notch fa-spin" style="font-size: 21px;"></i></td></tr>'
            );
        }

        $(document).on('click', '.btn-ship', function() {
            let mdl = $('#shipModal');
            mdl.modal('show');
            let _this = $(this);
            let order_details = _this.attr('data-order-details');
            order_details = JSON.parse(order_details);
            console.log(order_details)

            for (var key of Object.keys(order_details)) {
                mdl.find('[name=' + key + ']').val(order_details[key]);
                mdl.find('#' + key).text(order_details[key]);
            }

            fetch("/get-line-items/" + order_details.orderId)
                .then(data => data.json())
                .then(data => {
                    $('.tbl-ship-items').html('');
                    let html = "";
                    for (let item of data) {
                        if (item.lineType == 'PN' || item.lineType == 'N') {
                            continue;
                        }
                        let html = '<tr>';
                        html += '<td>' +
                            item.partNumber + '<br>' +
                            item.name + '<br>' +
                            'Parent Kit: ' + item.parentKitItem + '</td>';
                        html += '<td>' + item.pv + '</td>';
                        html += '<td>' + item.quantity + '</td>';
                        html += '<td>' + item.itemUnitPrice + '</td>';
                        html += '<td>' + item.salesPrice + '</td>';
                        html += '<td>' + item.taxableAmount + '</td>';
                        html += '<td>' + item.lineItemTotal + '</td>';
                        //   html += '<td><input name="qtyShipped[]" class="form-control" type="number" max="'+item.quantity+'" required></td>';  
                        //    html += '<td><select name="lot_code[]" class="form-control lot-code-'+item.partNumber+'" required></select></td>';
                        html += '</tr>';
                        $('.tbl-ship-items').append(html)

                        appendLotCodes(item.partNumber, mdl)
                    }

                })
        });

        $(document).on('submit', '#ship-form', function() {
            let _this = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to assign this shipment to ${$('[name="receiver"] option:selected').text()}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            type: 'POST',
                            url: '/order/do-ship',
                            data: $(this).serialize()
                        })
                        .done(function(data) {
                            if (data.success) {
                                swalSuccess(
                                    `Order ship was successfully assigned to ${$('[name="receiver"] option:selected').text()}`
                                );
                            } else if (data.success == false) {
                                swalError(data.message);
                                setTimeout(function() {
                                    location.reload()
                                }, 1500)
                            } else {
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

        $(document).on('click', '.btn-cancel', function() {
            let btn = $(this);
            let shipmentId = btn.attr('data-shipmentId');
            let url = '/order/cancel/' + shipmentId;

            Swal.fire({
                text: "You are canceling this order ship " + shipmentId +
                    ", which is not irreversible. Do you wish to proceed?",
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
                            url: url
                        })

                        .done(function(data) {

                            if (data.success) {
                                swalSuccess(data.message);
                                setTimeout(() => {
                                    location.reload()
                                }, 1800);
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


        $(document).on('click', '#release-form', function() {
            let btn = $(this);
            let shipmentId = "";
            let url = '/order/cancel/' + shipmentId;

            Swal.fire({
                text: "This trigger will also notify YL that the shipment has been picked up. Do you want to continue?",
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
                            url: url
                        })

                        .done(function(data) {

                            if (data.success) {
                                swalSuccess(data.message);
                                setTimeout(() => {
                                    location.reload()
                                }, 1800);
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



        $(document).on('click', '.shipmentId-txt', function() {

            let element = $(this).attr('data-id');
            $('.shipmentId-txt').attr('data-original-title', 'Copy');
            copyText(element);
            $(this).attr('data-original-title', 'Copied!').tooltip('show');
        });

        function copyText(element) {

            var copyText = document.getElementById(element);

            copyText.select();

            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText.value);
        }



        $('.btn-orders-details').click(function() {
            let mdl = $('#ordersModal');
            let v = $(this);
            let orderId = v.attr('data-orderId');
            let order_details = v.attr('data-order-details');
            order_details = JSON.parse(order_details);
            console.log(order_details)

            if (order_details.status == 1) {
                $('#btn-pickedup').remove();
                mdl.find('[name=hub_id]').remove();
            }

            $('#ordersModal').attr('shipmentId', order_details.shipmentId);
            $('#ordersModal').attr('orderId', order_details.orderId);
            $('#ordersModal').attr('status', order_details.status);
            for (var key of Object.keys(order_details)) {
                console.log(key + ' ' + order_details[key])
                if (order_details[key] == null || order_details[key] == "") {
                    order_details[key] = "N/A";
                }
                mdl.find('.' + key).text(order_details[key]);
            }
            let status = getStatusTextClass(order_details.status);
            $('.status').addClass(status.class);
            $('.status').text(status.text);

            $('.tbl-orders-details').html('');
            initLoader();
            setTimeout(function() {
                loadLineItems(orderId);
            }, 300)
        });

        $(document).on('click', '.btn-mark-as-completed', function(event) {
            let shipmentId = $('#ordersModal').attr('shipmentId');
            let status = 1;
            changeStatus(shipmentId, status)
        });

        $(document).on('click', '.btn-mark-as-overdue', function(event) {
            let shipmentId = $('#ordersModal').attr('shipmentId');
            let status = 2;
            changeStatus(shipmentId, status)
        });

        $(document).on('click', '.btn-mark-as-partially-completed', function(event) {
            let shipmentId = $('#ordersModal').attr('shipmentId');
            let status = 3;
            changeStatus(shipmentId, status)
        });

        $(document).on('click', '.btn-tag-one-picked-up', function(event) {
            let btn = $(this);
            let orderId = btn.attr('data-orderId');
            let sku = btn.attr('data-sku');
            let qty = btn.attr('data-qty');
            let hub_id = $('[name=hub_id]').find("option:selected").val();
            console.log(hub_id)
            if (!hub_id) {
                swalError('Please select a Hub');
                return;
            }
            let url = '/orders/tag-one-as-picked-up';

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



        $('#orders-form').submit(function(event) {
            let btn = $('#btn-pickedup');
            let shipmentId = $('#ordersModal').attr('shipmentId');
            let orderId = $('#ordersModal').attr('orderId');
            let hub_id = $('[name=hub_id]').find("option:selected").val();
            let status = $('#ordersModal').attr('status');
            let url = '/orders/tag-as-picked-up/' + shipmentId;

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
                                swalSuccess('Items was successfully Tags as Picked Up');
                                setTimeout(() => {
                                    loadLineItems(orderId)
                                }, 500);
                            } else if (data.message == 'not_enough_stock') {
                                let html =
                                    'Some of stocks are not enough, please check the SKU below.<br>';
                                for (let item of data.sku_list) {
                                    html += '<a target="_blank" href="/hubs/' + hub_id +
                                        '/search?key=' + item.sku + '">' + item
                                        .description +
                                        '</a><br>';
                                }
                                swalError(html);
                            } else {
                                swalError('Error occured, please contact support!');
                            }
                            btn.html("Tag as all Picked Up");
                        })
                        .fail(function() {
                            swalError('Error occured, please contact support!');
                            btn.html("Tag as all Picked Up");
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
                            url: '/orders/tag-as-overdue/' + shipmentId,
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
                            url: '/orders/return',
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
                icon: 'warning',
                title: 'Oops...',
                html: html,
            })
        }
    });
</script>
