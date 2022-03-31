<script>
    $(function() {
        "use strict";

        function clearInputs() {
            let modal = $('#hubModal');
            let inputs = modal.find('input');
            $.each(inputs, function(i, v) {
                if (i > 1) {
                    $(v).val('');
                }
            });
        }

        $('.open-modal').click(function(event) {

            let modal = $('#hubModal');
            modal.modal('show');

            let modal_type = $(this).attr('modal-type');
            clearInputs();

            if (modal_type == 'create') {

                modal.find('.modal-title').text('Create Hub');

                modal.find('form').attr('action', "{{ route('hub.store') }}");

            } else {
                modal.find('.modal-title').text('Update Hub');

                let data = JSON.parse($(this).attr('data-info'));

                modal.find('form').attr('action', "/hub/update/" + data.id);

                for (var key of Object.keys(data)) {
                    modal.find('[name=' + key + ']').val(data[key]);
                }
            }


        });
    });
</script>
