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

        $('.open-modal').click(function(event) {

            let modal = $('#postModal');
            modal.modal('show');
            let modal_type = $(this).attr('modal-type');
            clearInputs();
            if (modal_type == 'create') {
                $('[name=status]').val(1);
                modal.find('.modal-title').text('Create Product');
                modal.find('form').attr('action', "{{ route('product.store') }}");
            } else {
                modal.find('.modal-title').text('Update Product');
                let data = JSON.parse($(this).attr('data-info'));
                modal.find('form').attr('action', "/product/update/" + data.id);

                for (var key of Object.keys(data)) {
                    modal.find('[name=' + key + ']').val(data[key]);
                }
            }
        });

        $('.btn-transfer').click(function() {
            let v = $(this);
            let sku = v.attr('data-sku');
            let description = v.attr('data-desc');
            let tm = $('#transferModal');
            tm.find('[name=sku]').val(sku);
            tm.find('[name=description]').val(description);
        });

        $('#transfer-form').submit(function(event) {
            $('#btn-transfer').html("Please wait...");

            $.ajax({
                    type: 'POST',
                    url: "{{ url('/product/transfer') }}",
                    data: $(this).serialize()
                })
                .done(function(data) {
                    console.log(data)
                    if (data.success && data.message == 'transfer_success') {
                        Swal.fire(
                            'Transfer success!',
                            '',
                            'success'
                        );
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Not enough stock!',
                        })
                    }

                    $('#btn-transfer').html("Transfer");
                })
                .fail(function() {
                    alert("Posting failed. Please try again.");
                });

            return false;
        });

    });
</script>
