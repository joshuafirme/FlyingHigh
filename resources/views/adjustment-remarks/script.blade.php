<script>
    $(function() {
        "use strict";

        function clearInputs() {
            let modal = $('#remarksModal');
            let inputs = modal.find('input');
            $.each(inputs, function(i, v) {
                if (i > 1) {
                    $(v).val('');
                }
            });
        }

        $('.open-modal').click(function(event) {

            let modal = $('#remarksModal');
            modal.modal('show');

            let modal_type = $(this).attr('modal-type');
            clearInputs();

            if (modal_type == 'create') {
                $('[name=status]').val(1);
                modal.find('.modal-title').text('Create remarks');

                modal.find('form').attr('action', "{{ route('adjustment-remarks.store') }}");

            } else {
                modal.find('.modal-title').text('Update adjustment-remarks');

                let data = JSON.parse($(this).attr('data-info'));

                modal.find('form').attr('action', "/adjustment-remarks/update/" + data.id);

                for (var key of Object.keys(data)) {
                    modal.find('[name=' + key + ']').val(data[key]);
                }
            }


        });

        $('.btn-delete').click(function(event) {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You are going to delete this data. All contents related with this data will be lost. Do you want to delete it?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            type: 'POST',
                            url: '/adjustment-remarks/delete/' + id,
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                        })

                        .done(function(data) {

                            if (data.status == 'success') {
                                swalSuccess('Remarks was deleted.');
                                $("#record-id-" + id).fadeOut(100);
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
