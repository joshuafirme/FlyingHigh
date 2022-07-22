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
                $('[name=status]').val(1);
                modal.find('.modal-title').text('Add Branch Plant');

                modal.find('form').attr('action', "{{ route('pickup-location.store') }}");

            } else {
                modal.find('.modal-title').text('Update Branch Plant');

                let data = JSON.parse($(this).attr('data-info'));

                modal.find('form').attr('action', "/pickup-location/update/" + data.id);

                for (var key of Object.keys(data)) {
                    modal.find('[name=' + key + ']').val(data[key]);
                }
            }


        });
    });
</script>
