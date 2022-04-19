<script>
    $(function() {
        "use strict";

        $("#customCheck-all").on('click', function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
            $('div .ic_div-show').toggle();
        });

        $(document).on('click','.ic-parent-permission', function () {
            let parent_id = $(this).val();

            if ($(`#chkbx-all-${parent_id}`).is(':checked')) {
                $(`.parent-identy-${parent_id}`).each(function () {
                    $(this).prop('checked', true);
                });
            } else {
                $(`.parent-identy-${parent_id}`).each(function () {
                    $(this).prop('checked', false);
                });
            }
        });

        function clearInputs() {
            $('[name=name]').val('');
            $('input:checkbox:checked').prop('checked', false);
        }

        $('.open-modal').click(function(event) {

            let modal = $('#roleModal');
            modal.modal('show');

            let modal_type = $(this).attr('modal-type');

            clearInputs();

            if (modal_type == 'create') {
                $('[name=status]').val(1);
                modal.find('.modal-title').text('Create Role');
                modal.find('form').attr('action', "{{ route('role.store') }}");

            } else {
                modal.find('.modal-title').text('Update Role');

                let data = JSON.parse($(this).attr('data-info'));

                modal.find('form').attr('action', "/role/update/" + data.id);
                modal.find('[name=name]').val(data.name);

                let permissions = data.permission ? data.permission.split(', ') : [];

                for (let i = 0; i < permissions.length; i++) {
                    $('#chkbx-' + permissions[i].replace(/\s/g, '')).prop('checked', true);
                }

                let parent_checkboxes = ['administration', 'pickup', 'hub'];
                let checked_counter = 0;

                for (let el of parent_checkboxes) {
                    $(`.parent-identy-${el}`).each(function () {
                        if ($(this).is(':checked')) {
                            checked_counter++;
                        }
                        
                        if ($(`.parent-identy-${el}`).length == checked_counter) {
                            $(`#chkbx-all-${el}`).prop('checked', true);
                            checked_counter = 0;
                        }
                    });
                }

            }


        });
    });
</script>
