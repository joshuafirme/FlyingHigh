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
                            }
                            else {
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
