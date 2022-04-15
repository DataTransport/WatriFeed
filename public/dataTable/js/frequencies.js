$(document).ready(function(){

    ConfigJs.fetch_id='frequencies_data';
    ConfigJs.pre_url='frequency/';
    init_data();
    btn_edit();

    $(document).on('blur', '.update', function(){
        const id = $(this).data("id");
        const column_name = $(this).data("column");
        const value = $(this).text();
        update_data(id, column_name, value);
    });
    $(document).on('change', '.tripId2', function(){
        const id = $(this).data("id");
        const column_name ="trip_id";
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
            let options='';


            data1.forEach(function(element) {
                options+=`<option value="`+element.trip_id+`"> `+element.trip_id+`</option>`;

            });

            tripField = ` <td >
                                 <select id="stid_data1" name="" class="" data-id="" >`+
                options
                +`</select>
                                    </td>`;


                let html = '<tr>';
                html +=tripField;
                html += '<td contenteditable id="data2"></td>';
                html += '<td contenteditable id="data3"></td>';
                html += '<td contenteditable id="data4"></td>';
                html += '<td contenteditable id="data5"></td>';
                html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/frequency/">Insert</button></td>';
                html += '</tr>';

                $('#frequencies_data tbody').prepend(html);

        });


    });
    $(document).on('click', '#insert', function(){
        const trip_id = $('#stid_data1').find(":selected").text();
        const start_time = $('#data2').text();
        const end_time = $('#data3').text();
        const headway_secs = $('#data4').text();
        const exact_times = $('#data5').text();
        const gtfs = $('#gtfs').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/frequency/store",
                method:"POST",
                data:{
                    trip_id:trip_id,
                    start_time:start_time,
                    end_time:end_time,
                    headway_secs:headway_secs,
                    exact_times:exact_times,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    $('.errors_row').html('').css('display','none');
                    messageFlash(data,'success');
                    $('#frequencies_data').DataTable().destroy();
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
