$(document).ready(function(){

    ConfigJs.fetch_id='calendars_data';
    ConfigJs.pre_url='calendar/';
    init_data();
    btn_edit();

    $(document).on('blur', '.update', function(){
        const id = $(this).data("id");
        const column_name = $(this).data("column");
        const value = $(this).text();
        update_data(id, column_name, value);
    });
    $(document).on('change', '.tripId', function(){
        const id = $(this).data("id");
        const column_name ="trip_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });
    $(document).on('change', '.stopId', function(){
        const id = $(this).data("id");
        const column_name ="stop_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });

    $('#add6').click(function(){
// exit();
        let html = '<tr>';
        html += '<td contenteditable id="data1"></td>';
        html += '<td contenteditable id="data2"></td>';
        html += '<td contenteditable id="data3"></td>';
        html += '<td contenteditable id="data4"></td>';
        html += '<td contenteditable id="data5"></td>';
        html += '<td contenteditable id="data6"></td>';
        html += '<td contenteditable id="data7"></td>';
        html += '<td contenteditable id="data8"></td>';
        html += '<td contenteditable id="data9"></td>';
        html += '<td contenteditable id="data10"></td>';
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/calendar/">Insert</button></td>';
        html += '</tr>';

        $('#calendars_data tbody').prepend(html);


    });
    $(document).on('click', '#insert', function(){
        const service_id = $('#data1').text();
        const monday = $('#data2').text();
        const tuesday = $('#data3').text();
        const wednesday = $('#data4').text();
        const thursday = $('#data5').text();
        const friday = $('#data6').text();
        const saturday = $('#data7').text();
        const sunday = $('#data8').text();
        const start_date = $('#data9').text();
        const end_date = $('#data10').text();
        const gtfs = $('#gtfs').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/calendar/store",
                method:"POST",
                data:{
                    service_id:service_id,
                    monday:monday,
                    tuesday:tuesday,
                    wednesday:wednesday,
                    thursday:thursday,
                    friday:friday,
                    saturday:saturday,
                    sunday:sunday,
                    start_date:start_date,
                    end_date:end_date,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    console.dir(data);
                    messageFlash(data,'success');
                    $('#calendars_data').DataTable().destroy();
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
