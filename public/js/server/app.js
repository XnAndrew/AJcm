$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});


$('#startDatePicker').datetimepicker
({
    format: 'YYYY-MM-DD HH:mm',
    sideBySide: true
});

$('#endDatePicker').datetimepicker
({
    format: 'YYYY-MM-DD HH:mm',
    sideBySide: true,
    useCurrent: false
});

$("#startDatePicker").on("dp.change", function (e)
{
    $('#endDatePicker').data("DateTimePicker").minDate(e.date);
});

$("#endDatePicker").on("dp.change", function (e)
{
    $('#startDatePicker').data("DateTimePicker").maxDate(e.date);
});

function handleFileSelect(evt)
{
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++)
    {

    // Only process image files.
    if (!f.type.match('image.*'))
    {
        continue;
    }

    var reader = new FileReader();

    // Closure to capture the file information.
    reader.onload = (function(theFile)
    {
        return function(e)
        {
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');

            $('#logoPreview').html(span);

            $('#clearLogo').removeClass('hide');

            $("#fullLogo").removeClass('hide');

            $("#clrLogo").removeAttr('checked');
        };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
    }
}

function clearLogo()
{
    $("#logoSelect").val('');
    $("#clrLogo").attr('checked', 'checked');
    $("#logoPreview").html("");
    $("#fullLogo").addClass('hide');
    $("#clearLogo").addClass('hide');
}

// document.getElementById('logoSelect').addEventListener('change', handleFileSelect, false);
// document.getElementById('clearLogo').addEventListener('click', clearLogo, false);

$('#logoSelect').change(handleFileSelect);
$('#clearLogo').click(clearLogo);

new Vue({

   el: '#eventList',

   components: {
    'event-list': {

        props: ['events'],

        template: '#eventsTemplate',

        methods: {
            sortBy: function(key)
            {
                if(this.sortKey == key)
                {
                    this.reverse *= -1;
                }
                else {
                    this.reverse = 1;
                }
                this.sortKey = key;
            }
        },

        directives : {
            start : function(time)
            {
                 this.el.innerHTML =  moment(time).format('Do of MMMM YYYY @ HH:mm');
            },
            end : function(time)
            {
                 this.el.innerHTML =  moment(time).format('Do of MMMM YYYY @ HH:mm');
            }
        },

        data : function()
        {
            return {
                reverse: 1,
                search : "",
                sortKey: "sch_title"
            }
        }
    }
}
});
