$(document).ready(function(){

    ConfigJs.fetch_id='levels_data';
    ConfigJs.pre_url='level/';
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
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/level/">Insert</button></td>';
        html += '</tr>';

        $('#levels_data tbody').prepend(html);


    });
    $(document).on('click', '#insert', function(){
        const level_id = $('#data1').text();
        const level_index = $('#data2').text();
        const level_name = $('#data3').text();
        const gtfs = $('#gtfs').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/level",
                method:"POST",
                data:{
                    level_id:level_id,
                    level_index:level_index,
                    level_name:level_name,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    messageFlash(data,'success');
                    $('#levels_data').DataTable().destroy();
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
