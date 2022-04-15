$(document).ready(function(){
    ConfigJs.fetch_id='agencies_data';
    ConfigJs.pre_url='agency/';
    init_data();
    btn_edit();

    $(document).on('blur', '.update', function(){
        const id = $(this).data("id");
        const column_name = $(this).data("column");
        const value = $(this).text();
        update_data(id, column_name, value);
    });

    $('#add1').click(function(){
        add_row();
    });
    $(document).on('click', '#insert1', function(){
        const agency_id = $('#data1').val();
        const agency_name = $('#data2').val();
        const agency_url = $('#data3').val();
        const agency_timezone = $('#data4').val();
        const agency_lang = $('#data5').val();
        const agency_phone = $('#data6').val();
        const agency_fare_url = $('#data7').val();
        const agency_email = $('#data8').val();
        const gtfs = $('#gtfs').text();
        const resource = $(this).data("resource");
        console.log(resource);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({

                url:"/agency/store",
                method:"POST",
                data:{
                    agency_id:agency_id,
                    agency_name:agency_name,
                    agency_url:agency_url,
                    agency_timezone:agency_timezone,
                    agency_lang:agency_lang,
                    agency_phone:agency_phone,
                    agency_fare_url:agency_fare_url,
                    agency_email:agency_email,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    messageFlash(data,'success');
                    $('#agencies_data').DataTable().destroy();
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
function add_row(){
    let html = '<tr>';
    html += '<td contenteditable style="border: solid #00a651 1px; padding: 0 !important;" > <input id="data1" type="text" placeholder="Agency ID" style="width: 100%"></td>';
    html += '<td contenteditable style="border: solid #00a651 1px; padding: 0 !important;" ><input id="data2" type="text" placeholder="Agency Name" style="width: 100%"></td>';
    html += '<td contenteditable style="border: solid #00a651 1px; padding: 0 !important;" ><input id="data3" type="text" placeholder="Agency Url" style="width: 100%"></td>';
    html += '<td contenteditable style="border: solid #00a651 1px; padding: 0 !important;" ><input id="data4" type="text" placeholder="Agency Timezone" style="width: 100%"></td>';
    html += '<td contenteditable style="border: solid #00a651 1px; padding: 0 !important;" ><input id="data5" type="text" placeholder="Agency Lang" style="width: 100%"></td>';
    html += '<td contenteditable style="border: solid #00a651 1px; padding: 0 !important;" ><input id="data6" type="text" placeholder="Agency Phone" style="width: 100%"></td>';
    html += '<td contenteditable style="border: solid #00a651 1px; padding: 0 !important;" ><input id="data7" type="text" placeholder="Agency Fare url" style="width: 100%"></td>';
    html += '<td contenteditable style="border: solid #00a651 1px; padding: 0 !important;" ><input id="data8" type="text" placeholder="Agency Email" style="width: 100%"></td>';
    html += '<td><button style="font-size:15px;width:100%" type="button" name="insert" id="insert1" class="btn btn-success btn-xs" data-resource="/agency/">Insert</button></td>';
    html += '</tr>';
    $('#agencies_data tbody').prepend(html);
}
