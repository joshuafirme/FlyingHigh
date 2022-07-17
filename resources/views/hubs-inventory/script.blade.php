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

        $(document).on('keyup', '#shipmentId-input', function(e) {

            console.log(e.keyCode)
            let mdl = $('#addPickupModal')

            if (e.keyCode == 86) {
                let __this = $(this);
                let shipmentId = __this.val();
                let url = `/order/${shipmentId}`;

                fetch(url)
                    .then(data => data.json())
                    .then(data => {
                        console.log(data)

                        for (var key of Object.keys(data.order_details)) {
                            mdl.find('[name=' + key + ']').val(data.order_details[key]);
                            mdl.find('#' + key).text(data.order_details[key]);
                        }


                        $('#tbl-pickup-items').html('');
                        let html = "";
                        for (let item of data.lineItems) {
                            if (item.lineType == 'PN' || item.lineType == 'N') {
                                continue;
                            }
                            let component_text = '';
                            if (item.remarks == 'Component') {
                                component_text = item.remarks;
                            }
                            let html = '<tr>';

                            html += '<td>' + item.lineNumber + '</td>';
                            html += '<td>' + component_text + ' ' +
                                item.partNumber + '<br>' +
                                item.name + '<br>' +
                                'Parent Kit: ' + item.parentKitItem + '</td>';
                            html += '<td>' + item.quantity + '</td>';
                            html += '<td></td>';
                            html += '<td></td>';
                            html += '<td></td>';
                            html += '<td></td>';
                            html += '</tr>';
                            $('#tbl-pickup-items').append(html)
                        }
                    });

                return false;
            }
        });

        $(document).on('submit', '#pickup-form', function(e) {

            return false;
        });


        $(document).on('click', '.btn-pickup', function() {
            let btn = $(this);
            let orderId = btn.attr('data-orderId');
            let partNumber = btn.attr('data-partNumber');
            let url = '/orders/tag-one-as-picked-up';

            Swal.fire({
                text: "Do you want to mark this as picked up?",
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
                            url: url,
                            data: {
                                orderId: orderId,
                                partNumber: partNumber
                            }
                        })

                        .done(function(data) {

                            if (data.success) {
                                swalSuccess('Product was successfully Tags as Picked Up');
                                setTimeout(() => {
                                    location.reload()
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
