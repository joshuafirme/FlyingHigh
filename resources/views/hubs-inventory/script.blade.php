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

        $('.btn-pickup').click(function() {
            let v = $(this);
            let sku = v.attr('data-sku');
            $('#tbl-pickup').html('');
            fetch("{{ url('/pickup.json') }}")
                .then(data => data.json())
                .then(data => {
                    console.log(data)
                    for (item of data.lineItems) {

                    }
                    let html = '<tr>';
                    html += '<td>' + data.custName +
                        ' <br> <a href="mailto:' + data.customerEmail + '">' + data.customerEmail +
                        '</a></td>';
                    html += '<td>' + data.orderId + '</td>';
                    html += '<td>' + data.packageTotal + '</td>';
                    html += '<td>' + data.dateTimeSubmittedIso + '</td>';
                    html +=
                        '<td><a href="#"> Order List </a></td>';
                    html += '</tr>';
                    $('#tbl-pickup').append(html)
                })
        });

        $('#transfer-form').submit(function(event) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btn-pickup').html("Please wait...");
                    $.ajax({
                            type: 'POST',
                            url: "{{ url('/product/transfer') }}",
                            data: $(this).serialize()
                        })

                        .done(function(data) {

                            if (data.success && data.message == 'transfer_success') {
                                swalSuccess();
                            } else {
                                swalError();
                            }
                            $('#btn-pickup').html("Transfer");
                        })
                        .fail(function() {
                            alert("Posting failed. Please try again.");
                            $('#btn-pickup').html("Transfer");
                        });
                }
            })

            return false;
        });

        function swalSuccess() {
            Swal.fire(
                'Transfer success!',
                '',
                'success'
            );
        }

        function swalError() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Not enough stock!',
            })
        }

    });
</script>
