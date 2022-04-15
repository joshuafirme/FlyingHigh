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

        $('.btn-view-detail').click(function() {
            let data = JSON.parse($(this).attr('data-info'));
            let modal = $('#detailModal');
            console.log(data)
            for (var key of Object.keys(data)) {
                if (key == 'expiration') {
                    data[key] = data[key] ? data[key].substring(0, 10) : '';
                }
                 if (key == 'status') {
                    data[key] = data[key] == 1 ? 'Active': 'Inactive';
                }
                if (key == 'has_bundle') {
                    if (data[key] == 1) {
                        modal.find('[name=' + key + ']').prop('checked', true);
                        $('#bundle-qty-container').removeClass('d-none');
                        getBundleQty(data.sku);
                    } else {
                        modal.find('[name=' + key + ']').prop('checked', false);
                        $('#bundle-qty-container').addClass('d-none');
                    }
                    
                    continue;
                }
                modal.find('[name=' + key + ']').val(data[key]);
            }
        });

        function getBundleQty(sku) {
            // Replace this endpoint, qty must come from hub
            fetch("/api/product/bundle-qty-list/" + sku)
                .then(data => data.json())
                .then(result => {
                    $('#tbl-bundle-qty').html('');
                    console.log(result)
                    if (result.data.length > 0) {
                        for (let item of result.data) {
                            let html = '<tr>';
                            html += '<td><a target="_blank" href="/hubs/hub-1/1/search?key=' + item.sku + '">' +
                                item.sku + ' | ' + item.description + '</a></td>';
                            html += '<td>' + item.qty + '</td>';
                            html += '<td><a target="_blank" href="/hubs/hub-1/1/search?key=' + item.sku +
                                '"><i class="fa fa-eye"></i></a></td>';
                            html += '</tr>';
                            $('#tbl-bundle-qty').append(html);
                        }
                    } else {
                        let html = '<tr>';
                        html +=
                        '<td colspan="2"><div class="alert alert-primary">No data found.</div></td>';
                        html += '</tr>';
                        $('#tbl-bundle-qty').append(html);
                    }
                })
        }

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
