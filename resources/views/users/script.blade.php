<script>
    $(function() {
        "use strict";

        function clearInputs() {
            $('[name=name]').val('');
            $('[name=email]').val('');
            $('[name=access_level]').val('');
            $('[name=status]').val('');
        }

        function removePasswordHtml() {
            $('#change-password').remove();
        }

        $('.open-modal').click(function(event) {

            let modal = $('#userModal');
            modal.modal('show');

            let modal_type = $(this).attr('modal-type');

            clearInputs();
            removePasswordHtml();

            if (modal_type == 'create') {

                modal.find('.modal-title').text('Create User');
                modal.find('#change-password').remove();

                let password_container = '<div class="col-md-12 password-container">';
                password_container +=
                    '<label for="validationCustom02" class="form-label">Password</label>';
                password_container +=
                    '<input type="password" class="form-control" name="password" required>';
                password_container += '</div>';
                modal.find('.modal-body').append(password_container);
                modal.find('form').attr('action', "{{ route('users.store') }}");

            } else {
                modal.find('.modal-title').text('Update User');
                modal.find('.password-container').remove();
                let change_pass_html = '<div class="d-block" id="change-password">';
                change_pass_html +=
                    '<a class="btn btn-sm btn-primary btn-change-pass">Change Password</a>';
                change_pass_html += '</div>';

                modal.find('.modal-body').append(change_pass_html);


                let data = JSON.parse($(this).attr('data-info'));

                modal.find('form').attr('action', "/user/update/" + data.id);

                modal.find('[name=name]').val(data.name);
                modal.find('[name=email]').val(data.email);
                modal.find('[name=access_level]').val(data.access_level);
                modal.find('[name=status]').val(data.status);
            }


        });

        $(document).on('click', '#change-password .btn-change-pass', function() {
            $(this).remove();
            let change_pass_html = '<div><label class="mt-2">New Password</label>';
            change_pass_html += '<div class="d-flex">';
            change_pass_html +=
                '<input type="password" class="form-control" name="password" autocomplete="new-password" required>';
            change_pass_html += '<a class="btn btn-sm btn-danger" id="btn-cancel">Cancel</a>';
            change_pass_html += '</div></div>';

            $('#change-password').append(change_pass_html);
        });

        $(document).on('click', '#btn-cancel', function(event) {
            $(this).parent().parent().remove();
            $(this).remove();
            let change_pass_html = '<div class="d-flex col-12">';
            change_pass_html += '<a class="btn btn-sm btn-primary btn-change-pass">Change password</a>';
            change_pass_html += '</div>';

            $('#change-password').append(change_pass_html);
        });
    });
</script>
