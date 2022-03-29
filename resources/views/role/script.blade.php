<script>
    $(function(){
        "use strict";

        function clearInputs() {
            $('[name=name]').val('');
            $('input:checkbox:checked').prop('checked', false);  
        }

        function removePasswordHtml() {
            $('#change-password').remove();
        }
        
        $('.open-modal').click(function(event) {

            let modal = $('#roleModal');
            modal.modal('show');

            let modal_type = $(this).attr('modal-type');

            clearInputs();

            if (modal_type == 'create') {

                modal.find('.modal-title').text('Create Role');
                modal.find('form').attr('action', "{{route('role.store')}}");

            }
            else {
                modal.find('.modal-title').text('Update Role');

                let data = JSON.parse($(this).attr('data-info'));
                
                modal.find('form').attr('action', "/role/update/"+data.id);
                modal.find('[name=name]').val(data.name);

                let permissions = data.permission ? data.permission.split(', ') : [];

                for (let i = 0; i < permissions.length; i++) {
                    $('#chkbx-'+permissions[i].replace(/\s/g, '')).prop('checked', true);  
                }
            }
            

        });
    });
</script>