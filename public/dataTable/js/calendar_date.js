$(document).ready(function(){

    ConfigJs.fetch_id='calendar_dates_data';
    ConfigJs.pre_url='calendardate/';
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
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/calendardate/">Insert</button></td>';
        html += '</tr>';

        $('#calendar_dates_data tbody').prepend(html);


    });
    $(document).on('click', '#insert', function(){
        const service_id = $('#data1').text();
        const date = $('#data2').text();
        const exception_type = $('#data3').text();
        const gtfs = $('#gtfs').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/calendardate/store",
                method:"POST",
                data:{
                    service_id:service_id,
                    date:date,
                    exception_type:exception_type,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    messageFlash(data,'success');
                    $('#calendar_dates_data').DataTable().destroy();
                    fetch_data();
                    setInterval(function(){
                        location.reload();
                    }, 1000);
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
