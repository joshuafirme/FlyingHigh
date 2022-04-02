<script>
    $(function() {
        "use strict";

        function clearInputs() {
            let modal = $('#clientModal');
            let inputs = modal.find('input');
            $.each(inputs, function(i, v) {
                if (i > 1) {
                    $(v).val('');
                }
            });
        }

        $('.open-modal').click(function(event) {

            let modal = $('#clientModal');
            modal.modal('show');

            let modal_type = $(this).attr('modal-type');
            clearInputs();

            if (modal_type == 'create') {
                $('[name=status]').val(1);
                modal.find('.modal-title').text('Create client');

                modal.find('form').attr('action', "{{ route('client.store') }}");

            } else {
                modal.find('.modal-title').text('Update client');

                let data = JSON.parse($(this).attr('data-info'));

                modal.find('form').attr('action', "/client/update/" + data.id);

                for (var key of Object.keys(data)) {
                    modal.find('[name=' + key + ']').val(data[key]);
                }
            }


        });
    });
</script>
