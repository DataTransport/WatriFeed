$(document).ready(function(){

    ConfigJs.fetch_id='shapes_data';
    ConfigJs.pre_url='shape/';
    init_data();
    btn_edit();

    $(document).on('blur', '.update', function(){
        const id = $(this).data("id");
        const column_name = $(this).data("column");
        const value = $(this).text();
        update_data(id, column_name, value);
    });

    $('#add').click(function(){

        let html = '<tr>';
        html += '<td contenteditable id="data1"></td>';
        html += '<td contenteditable id="data2"></td>';
        html += '<td contenteditable id="data3"></td>';
        html += '<td contenteditable id="data4"></td>';
        html += '<td contenteditable id="data5"></td>';
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/shape/">Insert</button></td>';
        html += '</tr>';

        $('#shapes_data tbody').prepend(html);


    });
    $(document).on('click', '#insert', function(){
        const shape_id = $('#data1').text();
        const shape_pt_lat = $('#data2').text();
        const shape_pt_lon = $('#data3').text();
        const shape_pt_sequence = $('#data4').text();
        const shape_dist_traveled = $('#data5').text();
        const gtfs = $('#gtfs').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/shape/store",
                method:"POST",
                data:{
                    shape_id:shape_id,
                    shape_pt_lat:shape_pt_lat,
                    shape_pt_lon:shape_pt_lon,
                    shape_pt_sequence:shape_pt_sequence,
                    shape_dist_traveled:shape_dist_traveled,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    messageFlash(data,'success');
                    $('#shapes_data').DataTable().destroy();
                    fetch_data();
                    setInterval(function(){
                        location.reload();
                    }, 2000);
                },
                error:function (data) {
                    const errors = $.parseJSON(data.responseText);
                    let message='';
                    $.each(errors.errors, function (key, value) {
                        message+=value+'<br>';
                    });
                    messageFlash(message,'error');
                }
            });

    });

    delete_row()

});
