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
            
            getLotCodes(data.sku, 'details');
            for (var key of Object.keys(data)) {
                if (key == 'expiration') {
                    data[key] = data[key] ? data[key].substring(0, 10) : '';
                }
                if (key == 'status') {
                    data[key] = data[key] == 1 ? 'Active' : 'Inactive';
                }
                if (key == 'has_bundle') {
                    if (data[key] == 1) {
                        $('#bundle-qty-container').removeClass('d-none');
                        getBundleQty(data);
                    } else {
                        $('#bundle-qty-container').addClass('d-none');
                    }

                    continue;
                }
                modal.find('[name=' + key + ']').val(data[key]);
            }
        });

        function initLoader() {
            $('.tbl-lot-codes').html('<tr><td colspan="3" class="text-center"><i class="fas fa-circle-notch fa-spin" style="font-size: 21px;"></i></td></tr>');
        }

        function getLotCodes(sku, type = "edit") {
            
            initLoader();    
            fetch("/api/lotcode/" + sku)
                .then(data => data.json())
                .then(result => {
                    console.log(result)
                    setTimeout(() => {
                    $('.tbl-lot-codes').html('');
                        if (result.length > 0) {
                                for (let item of result) {
                                    let lot_code = item.lot_code == 0 ? 'N/A' : item.lot_code;
                                    let expiration = item.expiration ? item.expiration.substring(0, 10) : 'N/A';
                                    let html = '<tr>';
                                    if (type=='edit') {
                                        html += '<td><input type="text" name="lot_code[]" readonly class="form-control" value="'+ lot_code +'"></td>';
                                        html += '<td>'+item.stock+'</td>';
                                        html += '<td><input type="date" name="expiration[]" class="form-control" value="'+ expiration +'"></td>';
                                    }
                                    else {
                                        html += '<td>'+ lot_code +'</td>';
                                        html += '<td>'+item.stock+'</td>';
                                        html += '<td>'+ expiration +'</td>';
                                    }
                                    html += '</tr>';
                                    $('.tbl-lot-codes').append(html);
                                }
                        }
                        else {
                            let html = '<tr>';
                            html += '<td colspan="3"><div class="alert alert-primary">No data found.</div></td>';
                            html += '</tr>';
                            $('.tbl-lot-codes').append(html);
                        }
                    }, 300);
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
