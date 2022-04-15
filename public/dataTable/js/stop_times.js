$(document).ready(function(){

    ConfigJs.fetch_id='stop_times_data';
    ConfigJs.pre_url='stoptime/';
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

    $('#add').click(function(){

        let t= $(this).data('t');
        let g= $(this).data('g');
        let m= $(this).data('m');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.get( '/trip/?t='+t+'&g='+g+'&_='+m+'&a=true', function( data1 ) {

            let tripField;
            let stopField;
            let options='';
            let options2='';


            data1.forEach(function(element) {
                options+=`<option value="`+element.trip_id+`"> `+element.trip_id+`</option>`;

            });

            tripField = ` <td >
                                 <select id="stid_data1" name="" class="" data-id="" >`+
                options
                +`</select>
                                    </td>`;

            $.get( '/stop/?t='+t+'&g='+g+'&_='+m+'&a=true', function( data ) {
                console.dir(data);
                data.forEach(function(element2) {
                    options2+=`<option value="`+element2.stop_id+`"> `+element2.stop_id+`</option>`;

                });

                stopField = ` <td >
                                 <select id="ssid_data4" name="" class="" data-id="" >`+
                    options2
                    +`</select>
                                    </td>`;


                let html = '<tr>';
                html +=tripField;
                html += '<td contenteditable id="data2"></td>';
                html += '<td contenteditable id="data3"></td>';
                html +=stopField;
                html += '<td contenteditable id="data5"></td>';
                html += '<td contenteditable id="data6"></td>';
                html += '<td contenteditable id="data7"></td>';
                html += '<td contenteditable id="data8"></td>';
                html += '<td contenteditable id="data9"></td>';
                html += '<td contenteditable id="data10"></td>';
                html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/stoptime/">Insert</button></td>';
                html += '</tr>';

                $('#stop_times_data tbody').prepend(html);
            });

        });




    });
    $(document).on('click', '#insert', function(){
        const trip_id = $('#stid_data1').find(":selected").text();
        const arrival_time = $('#data2').text();
        const departure_time = $('#data3').text();
        const stop_id = $('#ssid_data4').find(":selected").text();
        const stop_sequence = $('#data5').text();
        const stop_headsign = $('#data6').text();
        const pickup_type = $('#data7').text();
        const drop_off_type = $('#data8').text();
        const shape_dist_traveled = $('#data9').text();
        const timepoint = $('#data10').text();
        const gtfs = $('#gtfs').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/stoptime/store",
                method:"POST",
                data:{
                    trip_id:trip_id,
                    arrival_time:arrival_time,
                    departure_time:departure_time,
                    stop_id:stop_id,
                    stop_sequence:stop_sequence,
                    stop_headsign:stop_headsign,
                    pickup_type:pickup_type,
                    drop_off_type:drop_off_type,
                    shape_dist_traveled:shape_dist_traveled,
                    timepoint:timepoint,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    $('.errors_row').html('').css('display','none');
                    messageFlash(data,'success');
                    $('#stop_times_data').DataTable().destroy();
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

                    $('.errors_row').html(message).css('display','block');
                    messageFlash(message,'error');
                }
            });


    });


    delete_row()
});
